(function () {
  'use strict';

  /* =====================================================================
   * Réglages
   * =================================================================== */
  var DELAI_INACTIVITE = 30000;  // retour au défi 1 après 30 s sans activité
  var DELAI_AVERTISSEMENT = 22000; // message « Tu es encore là ? »
  var DELAI_BOUCLE = 2000;       // l'aperçu est rechargé s'il ne répond plus depuis 2 s
  var DELAI_RENDU = 350;         // attente après la frappe avant de mettre à jour l'aperçu
  var DELAI_STABLE = 1500;       // la réussite doit rester vraie 1,5 s (évite les faux positifs en cours de frappe)
  var DELAI_SUIVANT = 7000;      // passage automatique au défi suivant

  var DEFIS = window.DEFIS;
  var $ = function (id) { return document.getElementById(id); };

  var etat = {
    index: 0,
    codes: DEFIS.map(function (d) { return d.code; }),
    reussis: DEFIS.map(function () { return false; }),
    debloque: 0,
    introsVues: DEFIS.map(function () { return false; })
  };

  /* =====================================================================
   * Éditeur CodeMirror
   * =================================================================== */
  var editeur = CodeMirror($('editeur'), {
    value: '',
    mode: 'htmlmixed',
    theme: 'sobre',
    lineNumbers: true,
    lineWrapping: true,
    tabSize: 2,
    indentUnit: 2,
    autoCloseTags: true,
    autoCloseBrackets: true,
    matchBrackets: true,
    spellcheck: false,
    autocorrect: false,
    autocapitalize: false
  });

  var minuteurRendu = null;
  var chargementEnCours = false;
  editeur.on('change', function () {
    if (chargementEnCours) return;
    annulerReussite();
    etat.codes[etat.index] = editeur.getValue();
    clearTimeout(minuteurRendu);
    minuteurRendu = setTimeout(rendre, DELAI_RENDU);
    majStatut('', 'Modification…');
  });

  /* =====================================================================
   * Protection contre les boucles infinies (1/2)
   * On ajoute « __garde() && » dans la condition de chaque boucle for / while.
   * Si une même boucle tourne plus d'une seconde sans rendre la main, elle
   * est interrompue par une erreur.
   * =================================================================== */
  function sauterChaine(src, i) {
    var q = src[i], j = i + 1;
    while (j < src.length && src[j] !== q) {
      if (src[j] === '\\') j++;
      j++;
    }
    return j + 1;
  }

  function parentheseFermante(src, ouverte) {
    var prof = 0;
    for (var i = ouverte; i < src.length; i++) {
      var c = src[i];
      if (c === '"' || c === "'" || c === '`') { i = sauterChaine(src, i) - 1; continue; }
      if (c === '(') prof++;
      else if (c === ')') { prof--; if (prof === 0) return i; }
    }
    return -1;
  }

  function decouperPointVirgule(src) {
    var parts = [], prof = 0, debut = 0;
    for (var i = 0; i < src.length; i++) {
      var c = src[i];
      if (c === '"' || c === "'" || c === '`') { i = sauterChaine(src, i) - 1; continue; }
      if (c === '(' || c === '[' || c === '{') prof++;
      else if (c === ')' || c === ']' || c === '}') prof--;
      else if (c === ';' && prof === 0) { parts.push(src.slice(debut, i)); debut = i + 1; }
    }
    parts.push(src.slice(debut));
    return parts;
  }

  function protegerBoucles(src) {
    var sortie = '', i = 0, n = src.length;
    while (i < n) {
      var c = src[i];
      // commentaires
      if (c === '/' && src[i + 1] === '/') {
        var fin = src.indexOf('\n', i); fin = fin < 0 ? n : fin;
        sortie += src.slice(i, fin); i = fin; continue;
      }
      if (c === '/' && src[i + 1] === '*') {
        var fin2 = src.indexOf('*/', i + 2); fin2 = fin2 < 0 ? n : fin2 + 2;
        sortie += src.slice(i, fin2); i = fin2; continue;
      }
      // chaînes
      if (c === '"' || c === "'" || c === '`') {
        var j = sauterChaine(src, i);
        sortie += src.slice(i, j); i = j; continue;
      }
      // mots
      if (/[A-Za-z_$]/.test(c)) {
        var k = i;
        while (k < n && /[\w$]/.test(src[k])) k++;
        var mot = src.slice(i, k);
        var avant = sortie.replace(/\s+$/, '').slice(-1);
        sortie += mot; i = k;
        if ((mot === 'for' || mot === 'while') && avant !== '.') {
          var p = i;
          while (p < n && /\s/.test(src[p])) p++;
          if (src[p] === '(') {
            var ferme = parentheseFermante(src, p);
            if (ferme > 0) {
              var dedans = src.slice(p + 1, ferme);
              var nouveau = dedans;
              if (mot === 'while') {
                nouveau = '__garde() && (' + dedans + ')';
              } else {
                var parts = decouperPointVirgule(dedans);
                if (parts.length === 3) { // for (init; condition; pas) — pas for…of / for…in
                  parts[1] = parts[1].trim() ? ' __garde() && (' + parts[1] + ')' : ' __garde()';
                  nouveau = parts.join(';');
                }
              }
              sortie += src.slice(i, p) + '(' + nouveau + ')';
              i = ferme + 1;
            }
          }
        }
        continue;
      }
      sortie += c; i++;
    }
    return sortie;
  }

  /* =====================================================================
   * Script injecté dans l'aperçu : battement de cœur, erreurs, vérification.
   * Cette fonction est convertie en texte : elle doit rester autonome.
   * =================================================================== */
  function harnais(JETON, verifier) {
    var p = window.parent;
    function envoyer(m) { m.jeton = JETON; try { p.postMessage(m, '*'); } catch (e) { /* rien */ } }

    // Limite par « tâche » (jusqu'à ce que le navigateur reprenne la main) :
    // 1 seconde ou 10 000 tours de boucle au total.
    var t0 = 0, tours = 0;
    window.__garde = function () {
      var t = Date.now();
      if (!t0) { t0 = t; tours = 0; setTimeout(function () { t0 = 0; }, 0); }
      if (++tours > 10000 || t - t0 > 1000) { t0 = 0; throw new Error('BOUCLE_INFINIE'); }
      return true;
    };

    envoyer({ type: 'vivant' });
    setInterval(function () { envoyer({ type: 'vivant' }); }, 250);

    window.addEventListener('error', function (e) {
      var msg = String(e.message || 'Erreur inconnue');
      envoyer({ type: 'erreur', message: msg, boucle: msg.indexOf('BOUCLE_INFINIE') >= 0 });
    });

    var derniere = 0;
    ['pointerdown', 'pointermove', 'keydown', 'wheel'].forEach(function (t) {
      window.addEventListener(t, function () {
        var now = Date.now();
        if (now - derniere > 1000) { derniere = now; envoyer({ type: 'activite' }); }
      }, true);
    });

    // les liens ne quittent pas l'aperçu : on prévient la page principale
    document.addEventListener('click', function (e) {
      var lien = e.target && e.target.closest ? e.target.closest('a[href]') : null;
      if (lien) { e.preventDefault(); envoyer({ type: 'lien', href: lien.getAttribute('href') }); }
    }, true);

    function verif() {
      var r = null;
      try { r = verifier(document, window); } catch (e) { r = null; }
      envoyer({ type: 'verif', resultats: r });
    }
    window.addEventListener('load', function () { verif(); setTimeout(verif, 400); });
  }

  function construireDocument(code, defi, jeton) {
    var doc = new DOMParser().parseFromString(code, 'text/html');
    doc.querySelectorAll('script:not([src])').forEach(function (s) {
      s.textContent = protegerBoucles(s.textContent);
    });
    // boucles dans les attributs onclick="…", onload="…", etc.
    doc.querySelectorAll('*').forEach(function (el) {
      Array.prototype.slice.call(el.attributes).forEach(function (a) {
        if (/^on/i.test(a.name)) el.setAttribute(a.name, protegerBoucles(a.value));
      });
    });
    var meta = doc.createElement('meta');
    meta.setAttribute('charset', 'utf-8');
    var s = doc.createElement('script');
    s.textContent = '(' + harnais.toString() + ')(' + jeton + ', ' + defi.verifier.toString() + ');';
    doc.head.prepend(s);
    doc.head.prepend(meta);
    return '<!doctype html>\n' + doc.documentElement.outerHTML;
  }

  var PAGE_BOUCLE =
    '<!doctype html><html><head><meta charset="utf-8"><style>' +
    'body{margin:0;height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;' +
    'font-family:system-ui,sans-serif;background:#fff;color:#1c1c1c;text-align:center;padding:24px;box-sizing:border-box}' +
    'h1{margin:0 0 8px;font-size:22px;color:#c2410c}p{margin:0;font-size:16px;line-height:1.5;color:#555;max-width:420px}' +
    '</style></head><body><h1>Boucle infinie</h1>' +
    '<p>Ton programme tournait en rond sans jamais s’arrêter, l’aperçu a été rechargé. ' +
    'Vérifie la condition de ta boucle, puis modifie le code pour réessayer.</p></body></html>';

  /* =====================================================================
   * Aperçu en direct + protection contre les boucles infinies (2/2)
   * =================================================================== */
  /*
   * On crée une iframe NEUVE à chaque rendu (au lieu de changer son srcdoc) :
   * si une page reste bloquée dans une boucle, Chrome ne peut la libérer
   * qu'en supprimant le cadre ; une iframe réutilisée resterait coincée.
   * La nouvelle iframe est chargée en dessous puis affichée à son
   * événement « load » (double tampon : pas de clignotement).
   */
  var cadre = $('apercu-cadre');
  var iframe = $('apercu');   // la plus récente (en chargement ou affichée)
  var jeton = 0;
  var dernierBattement = 0;
  var surveillance = false;

  function nouvelleIframe(html) {
    var f = document.createElement('iframe');
    f.title = 'Aperçu en direct';
    f.setAttribute('sandbox', 'allow-scripts');
    f.className = 'en-chargement';
    f.srcdoc = html;
    f.addEventListener('load', function () {
      if (f !== iframe) return;
      // retire toutes les anciennes iframes et affiche la nouvelle
      cadre.querySelectorAll('iframe').forEach(function (autre) { if (autre !== f) autre.remove(); });
      f.classList.remove('en-chargement');
    });
    // une iframe encore en chargement d'un rendu précédent est abandonnée
    cadre.querySelectorAll('iframe.en-chargement').forEach(function (a) { a.remove(); });
    cadre.insertBefore(f, $('erreur'));
    iframe = f;
  }

  function rendre() {
    clearTimeout(minuteurRendu);
    jeton++;
    masquerErreur();
    var html;
    try {
      html = construireDocument(editeur.getValue(), DEFIS[etat.index], jeton);
    } catch (e) {
      html = editeur.getValue();
    }
    dernierBattement = Date.now();
    surveillance = true;
    nouvelleIframe(html);
  }

  function rechargerApercu() {
    surveillance = false;
    jeton++;
    cadre.querySelectorAll('iframe').forEach(function (a) { a.remove(); });
    nouvelleIframe(PAGE_BOUCLE);
    majStatut('erreur', 'Boucle infinie');
    toast('Ton code tournait en boucle sans fin : l’aperçu a été rechargé.');
  }

  setInterval(function () {
    if (!surveillance || document.hidden) return;
    if (Date.now() - dernierBattement > DELAI_BOUCLE) rechargerApercu();
  }, 200);
  document.addEventListener('visibilitychange', function () { dernierBattement = Date.now(); });

  window.addEventListener('message', function (e) {
    if (e.source !== iframe.contentWindow) return;
    var m = e.data;
    if (!m || m.jeton !== jeton) return;
    switch (m.type) {
      case 'vivant':
        dernierBattement = Date.now();
        break;
      case 'activite':
        activite();
        break;
      case 'erreur':
        if (m.boucle) afficherErreur('Ta boucle tournait sans fin (ou plus de 10 000 tours) : elle a été stoppée. Vérifie sa condition !');
        else afficherErreur('Erreur dans ton code : ' + m.message);
        break;
      case 'lien':
        toast('Ce lien mène vers ' + m.href + ' (les liens sont désactivés dans l’aperçu).');
        break;
      case 'verif':
        majObjectifs(m.resultats);
        break;
    }
  });

  function afficherErreur(txt) { var el = $('erreur'); el.textContent = txt; el.hidden = false; }
  function masquerErreur() { $('erreur').hidden = true; }

  /* =====================================================================
   * Objectifs et réussite
   * =================================================================== */
  var minuteurReussite = null;

  function annulerReussite() { clearTimeout(minuteurReussite); minuteurReussite = null; }

  // etatStatut : '' (neutre), 'ok' ou 'erreur'
  function majStatut(etatStatut, texte) {
    var s = $('statut');
    s.className = 'statut' + (etatStatut ? ' statut-' + etatStatut : '');
    s.textContent = texte;
  }

  function majObjectifs(resultats) {
    var defi = DEFIS[etat.index];
    var items = $('objectifs').children;
    var tousOk = Array.isArray(resultats) && resultats.length >= defi.objectifs.length;
    for (var i = 0; i < defi.objectifs.length; i++) {
      var r = (resultats && resultats[i]) || { ok: false, msg: '' };
      if (!r.ok) tousOk = false;
      items[i].classList.toggle('ok', !!r.ok);
      items[i].querySelector('.objectif-msg').textContent = r.msg || '';
    }
    if (etat.reussis[etat.index]) { majStatut('ok', 'Défi réussi'); return; }
    if (tousOk) {
      majStatut('ok', 'Objectif atteint');
      if (!minuteurReussite) minuteurReussite = setTimeout(reussite, DELAI_STABLE);
    } else {
      majStatut('', 'Pas encore');
      annulerReussite();
    }
  }

  var minuteurSuivant = null;

  function reussite() {
    minuteurReussite = null;
    var i = etat.index;
    if (etat.reussis[i]) return;
    etat.reussis[i] = true;
    etat.debloque = Math.max(etat.debloque, Math.min(i + 1, DEFIS.length - 1));
    majEtapes();
    editeur.getInputField().blur();
    lancerConfettis();

    var dernier = i === DEFIS.length - 1;
    $('bravo-etiquette').textContent = 'Défi ' + (i + 1) + ' sur ' + DEFIS.length;
    $('bravo-titre').textContent = 'Bravo, c’est réussi !';
    $('bravo-sous').textContent = DEFIS[i].bravo;
    $('bravo-appris').textContent = DEFIS[i].appris;
    $('btn-suivant').textContent = dernier ? 'Terminer' : 'Défi suivant';
    var barre = $('bravo-minuteur');
    barre.style.transition = 'none';
    barre.style.width = '100%';
    void barre.offsetWidth;
    barre.style.transition = 'width ' + DELAI_SUIVANT + 'ms linear';
    barre.style.width = '0%';
    montrer('bravo');
    clearTimeout(minuteurSuivant);
    minuteurSuivant = setTimeout(suivant, DELAI_SUIVANT);
  }

  function suivant() {
    clearTimeout(minuteurSuivant);
    cacher('bravo');
    if (etat.index >= DEFIS.length - 1) {
      afficherFinal();
    } else {
      allerA(etat.index + 1);
    }
  }

  function afficherFinal() {
    var ul = $('liste-appris');
    ul.innerHTML = '';
    DEFIS.forEach(function (d) {
      var li = document.createElement('li');
      li.innerHTML = '<span></span><small></small>';
      li.firstChild.textContent = d.titre;
      li.lastChild.textContent = d.langage;
      ul.appendChild(li);
    });
    montrer('final');
    lancerConfettis(true);
  }

  /* =====================================================================
   * Affichage d'un défi
   * =================================================================== */
  function majEtapes() {
    var nav = $('etapes');
    nav.innerHTML = '';
    DEFIS.forEach(function (d, i) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'etape';
      if (i === etat.index) b.classList.add('courante');
      if (etat.reussis[i]) b.classList.add('reussie');
      var accessible = i <= etat.debloque;
      if (!accessible) b.classList.add('verrou');
      b.disabled = !accessible;
      b.style.setProperty('--c', d.accent);
      b.innerHTML = '<span class="etape-rond"></span><span class="etape-nom"></span>';
      b.firstChild.textContent = etat.reussis[i] ? '✓' : String(i + 1);
      b.lastChild.textContent = d.titre;
      b.title = 'Défi ' + (i + 1) + ' : ' + d.titre;
      b.addEventListener('click', function () { if (i !== etat.index) allerA(i); });
      nav.appendChild(b);
    });
  }

  function construirePalette(defi) {
    var zone = $('palette');
    zone.innerHTML = '';
    if (!defi.palette) { zone.hidden = true; return; }
    zone.hidden = false;
    defi.palette.valeurs.forEach(function (v) {
      var b = document.createElement('button');
      b.type = 'button';
      if (defi.palette.type === 'couleurs') {
        b.className = 'puce puce-couleur';
        b.innerHTML = '<i></i><span></span>';
        b.firstChild.style.background = v;
        b.lastChild.textContent = v;
      } else {
        b.className = 'puce puce-emoji';
        b.textContent = v;
      }
      b.title = 'Insérer « ' + v + ' » à la place du curseur';
      // mousedown + preventDefault : l'éditeur garde sa sélection
      b.addEventListener('mousedown', function (e) { e.preventDefault(); });
      b.addEventListener('click', function () {
        editeur.replaceSelection(v);
        editeur.focus();
      });
      zone.appendChild(b);
    });
  }

  function allerA(i) {
    annulerReussite();
    clearTimeout(minuteurSuivant);
    etat.index = i;
    var d = DEFIS[i];

    document.documentElement.style.setProperty('--accent', d.accent);
    $('mission-numero').textContent = 'Défi ' + (i + 1) + ' sur ' + DEFIS.length;
    $('mission-langage').textContent = d.langage;
    $('mission-titre').textContent = d.titre;
    $('mission-consigne').innerHTML = d.consigne; // contenu de confiance (défini dans defis.js)
    $('mission-consigne').scrollTop = 0;
    $('indice').textContent = d.indice;
    $('indice').hidden = true;
    $('btn-indice').textContent = 'Afficher un indice';

    var ul = $('objectifs');
    ul.innerHTML = '';
    d.objectifs.forEach(function (o) {
      var li = document.createElement('li');
      li.innerHTML = '<span class="coche"></span><span class="objectif-texte"><span class="objectif-nom"></span><span class="objectif-msg"></span></span>';
      li.querySelector('.objectif-nom').textContent = o;
      ul.appendChild(li);
    });

    construirePalette(d);
    majEtapes();
    majStatut(etat.reussis[i] ? 'ok' : '', etat.reussis[i] ? 'Défi réussi' : 'Chargement…');

    var mission = $('mission');
    mission.classList.remove('entree');
    void mission.offsetWidth;
    mission.classList.add('entree');

    chargementEnCours = true;
    editeur.setValue(etat.codes[i]);
    editeur.clearHistory();
    chargementEnCours = false;
    editeur.setCursor(0, 0);
    editeur.refresh();
    rendre();

    // présentation du langage la première fois qu'on ouvre ce défi
    if ($('accueil').hidden) {
      var intro = introDuDefi(i);
      if (intro) afficherIntros([intro]);
    }
  }

  /* =====================================================================
   * Fenêtres d'introduction (page web, nouveau langage)
   * =================================================================== */
  var fileIntros = [];

  // renvoie l'intro du défi i si elle n'a pas encore été vue (et la marque comme vue)
  function introDuDefi(i) {
    if (!DEFIS[i].intro || etat.introsVues[i]) return null;
    etat.introsVues[i] = true;
    return DEFIS[i].intro;
  }

  function afficherIntros(liste) {
    fileIntros = liste.slice();
    introSuivante();
  }

  function introSuivante() {
    var it = fileIntros.shift();
    if (!it) { cacher('intro'); editeur.focus(); return; }
    $('intro-etiquette').textContent = it.etiquette;
    $('intro-titre').textContent = it.titre;
    $('intro-texte').innerHTML = it.texte; // contenu de confiance (défini dans defis.js)
    $('btn-intro').textContent = fileIntros.length ? 'Continuer' : 'À toi de jouer';
    editeur.getInputField().blur();
    var carte = $('intro').firstElementChild;
    carte.style.animation = 'none';
    void carte.offsetWidth;
    carte.style.animation = '';
    montrer('intro');
    $('btn-intro').focus();
  }

  /* =====================================================================
   * Boutons
   * =================================================================== */
  $('btn-indice').addEventListener('click', function () {
    var el = $('indice');
    el.hidden = !el.hidden;
    this.textContent = el.hidden ? 'Afficher un indice' : 'Masquer l’indice';
  });

  $('btn-reset').addEventListener('click', function () {
    etat.codes[etat.index] = DEFIS[etat.index].code;
    allerA(etat.index);
  });

  $('btn-suivant').addEventListener('click', suivant);
  $('btn-rejouer').addEventListener('click', function () { reinitialiser(); });
  $('btn-intro').addEventListener('click', introSuivante);
  $('btn-commencer').addEventListener('click', function () {
    cacher('accueil');
    var liste = [].concat(window.INTRO_WEB);
    var intro = introDuDefi(etat.index);
    if (intro) liste.push(intro);
    afficherIntros(liste);
  });

  // Ctrl/Cmd + S ne doit pas ouvrir « Enregistrer la page »
  document.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') e.preventDefault();
  });

  /* =====================================================================
   * Voiles (fenêtres par-dessus la page)
   * =================================================================== */
  function montrer(id) { var el = $(id); el.hidden = false; el.classList.remove('sortie'); }
  function cacher(id) { $(id).hidden = true; }

  var minuteurToast = null;
  function toast(txt) {
    var t = $('toast');
    t.textContent = txt;
    t.classList.add('visible');
    clearTimeout(minuteurToast);
    minuteurToast = setTimeout(function () { t.classList.remove('visible'); }, 4500);
  }

  /* =====================================================================
   * Inactivité : retour au défi 1 après 30 s
   * =================================================================== */
  var derniereActivite = Date.now();

  function activite() {
    derniereActivite = Date.now();
    if (!$('veille').hidden) cacher('veille');
  }
  ['pointerdown', 'pointermove', 'keydown', 'wheel', 'touchstart'].forEach(function (ev) {
    document.addEventListener(ev, activite, { passive: true, capture: true });
  });

  function estVierge() {
    return !$('accueil').hidden;
  }

  function reinitialiser() {
    annulerReussite();
    clearTimeout(minuteurSuivant);
    ['bravo', 'final', 'veille', 'intro'].forEach(cacher);
    etat.codes = DEFIS.map(function (d) { return d.code; });
    etat.reussis = DEFIS.map(function () { return false; });
    etat.introsVues = DEFIS.map(function () { return false; });
    etat.debloque = 0;
    montrer('accueil');
    allerA(0);
    editeur.getInputField().blur();
    montrer('accueil');
    derniereActivite = Date.now();
  }

  setInterval(function () {
    if (estVierge()) { cacher('veille'); return; }
    var inactif = Date.now() - derniereActivite;
    if (inactif >= DELAI_INACTIVITE) {
      reinitialiser();
    } else if (inactif >= DELAI_AVERTISSEMENT) {
      $('veille-compte').textContent = Math.ceil((DELAI_INACTIVITE - inactif) / 1000);
      if ($('veille').hidden) montrer('veille');
    }
  }, 250);

  /* =====================================================================
   * Confettis
   * =================================================================== */
  var canvas = $('confettis');
  var ctx = canvas.getContext('2d');
  var particules = [];
  var animEnCours = false;
  var COULEURS = ['#e8485f', '#3b5bdb', '#f2a516', '#1f9d74', '#7048c8'];

  function redimensionner() {
    var r = window.devicePixelRatio || 1;
    canvas.width = innerWidth * r;
    canvas.height = innerHeight * r;
    ctx.setTransform(r, 0, 0, r, 0, 0);
  }
  window.addEventListener('resize', redimensionner);
  redimensionner();

  function lancerConfettis(beaucoup) {
    var n = beaucoup ? 160 : 100;
    for (var i = 0; i < n; i++) {
      var gauche = i % 2 === 0;
      particules.push({
        x: gauche ? -10 : innerWidth + 10,
        y: innerHeight * (0.55 + Math.random() * 0.3),
        vx: (gauche ? 1 : -1) * (6 + Math.random() * 10),
        vy: -(10 + Math.random() * 12),
        t: 6 + Math.random() * 8,
        a: Math.random() * Math.PI,
        va: (Math.random() - 0.5) * 0.4,
        c: COULEURS[i % COULEURS.length],
        vie: 0
      });
    }
    if (!animEnCours) { animEnCours = true; requestAnimationFrame(animer); }
  }

  function animer() {
    ctx.clearRect(0, 0, innerWidth, innerHeight);
    particules = particules.filter(function (p) { return p.y < innerHeight + 40 && p.vie < 360; });
    particules.forEach(function (p) {
      p.vie++;
      p.vx *= 0.985;
      p.vy += 0.35;
      p.x += p.vx;
      p.y += p.vy;
      p.a += p.va;
      ctx.save();
      ctx.translate(p.x, p.y);
      ctx.rotate(p.a);
      ctx.fillStyle = p.c;
      ctx.fillRect(-p.t / 2, -p.t / 4, p.t, p.t / 2 * (0.6 + Math.abs(Math.sin(p.vie / 6))));
      ctx.restore();
    });
    if (particules.length) requestAnimationFrame(animer);
    else { animEnCours = false; ctx.clearRect(0, 0, innerWidth, innerHeight); }
  }

  /* =====================================================================
   * Démarrage
   * =================================================================== */
  var liste = $('liste-accueil');
  DEFIS.forEach(function (d, i) {
    var li = document.createElement('li');
    li.innerHTML = '<span></span><small></small>';
    li.firstChild.textContent = d.titre;
    li.lastChild.textContent = d.langage;
    liste.appendChild(li);
  });

  /* =====================================================================
   * Raccourci pour tester : ouvrir directement un défi (1 à 5)
   *  - depuis la console du navigateur : allerAuDefi(3)
   *  - ou via l'adresse : index.html?defi=3
   * =================================================================== */
  window.allerAuDefi = function (n) {
    var i = Math.max(1, Math.min(DEFIS.length, parseInt(n, 10) || 1)) - 1;
    etat.debloque = Math.max(etat.debloque, i);
    ['accueil', 'bravo', 'final', 'veille', 'intro'].forEach(cacher);
    clearTimeout(minuteurSuivant);
    activite();
    allerA(i);
  };

  allerA(0);

  var defiDemande = new URLSearchParams(location.search).get('defi');
  if (defiDemande) window.allerAuDefi(defiDemande);
})();
