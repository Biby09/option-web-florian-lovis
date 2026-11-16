/*
 * Les défis.
 *
 * Chaque défi contient :
 *  - code      : le code pré-rempli que le visiteur doit modifier
 *  - objectifs : les libellés affichés dans la liste « Objectifs »
 *  - verifier  : une fonction exécutée DANS l'aperçu (iframe) après chaque
 *                modification. Elle reçoit (document, window) et renvoie un
 *                tableau { ok, msg } dans le même ordre que `objectifs`.
 *                ⚠️ Elle doit être autonome (aucune variable extérieure) car
 *                elle est convertie en texte puis injectée dans l'iframe.
 *                Écrire `verifier: function (doc, win) {…}` (pas de syntaxe
 *                raccourcie `verifier(doc) {…}`).
 *  - intro     : (facultatif) fenêtre affichée à l'ouverture du défi, pour
 *                présenter un nouveau langage. Le texte est du HTML.
 */

/*
 * Fenêtres affichées après « Commencer » : comment fonctionne une page web.
 * Plusieurs pages, affichées l'une après l'autre (bouton « Continuer »).
 */
window.INTRO_WEB = [
  {
    etiquette: 'Avant de commencer · 1 / 4',
    titre: 'C’est quoi, une page web ?',
    texte:
      '<p>Une page web, c’est tout ce que tu vois quand tu ouvres un site : des titres, des textes, des images, ' +
      'des boutons, des vidéos…</p>' +
      '<p>Mais derrière cette page, il y a un <strong>fichier texte</strong> écrit par une personne. ' +
      'Ce texte n’est pas écrit en français : il est écrit dans un langage spécial que l’ordinateur sait lire. ' +
      'C’est ce qu’on appelle du <strong>code</strong>.</p>' +
      '<p>Tu peux imaginer une recette de cuisine :</p>' +
      '<ul>' +
        '<li>le <strong>code</strong>, c’est la recette, écrite étape par étape ;</li>' +
        '<li>l’<strong>ordinateur</strong>, c’est le cuisinier qui suit la recette à la lettre ;</li>' +
        '<li>la <strong>page à l’écran</strong>, c’est le plat terminé.</li>' +
      '</ul>'
  },
  {
    etiquette: 'Avant de commencer · 2 / 4',
    titre: 'Du code à ton écran',
    texte:
      '<p>Le programme qui affiche les pages web s’appelle le <strong>navigateur</strong> : Chrome, Safari, ' +
      'Firefox, Edge… Tu l’utilises tous les jours.</p>' +
      '<p>Quand tu tapes l’adresse d’un site, ton navigateur va chercher les fichiers de code sur un ' +
      '<strong>serveur</strong>, un ordinateur allumé en permanence quelque part sur Internet. ' +
      'Puis il lit ce code, du haut vers le bas, et dessine la page.</p>' +
      '<div class="flux">' +
        '<span>Serveur</span><i>→</i><span>Fichiers de code</span><i>→</i><span>Navigateur</span><i>→</i><span>Page à l’écran</span>' +
      '</div>' +
      '<p>Bonne nouvelle : le navigateur est très tolérant. Si tu fais une petite faute, il ne plante pas, il fait ' +
      'de son mieux. Tu peux donc essayer sans avoir peur de casser quelque chose.</p>'
  },
  {
    etiquette: 'Avant de commencer · 3 / 4',
    titre: 'Trois langages, trois rôles',
    texte:
      '<p>Une page web utilise trois langages. Chacun a son travail, un peu comme pour construire une maison :</p>' +
      '<div class="trio">' +
        '<div><strong>HTML</strong><span>Le contenu</span><small>Les murs et les pièces : titres, textes, liens, images. C’est le squelette de la page.</small></div>' +
        '<div><strong>CSS</strong><span>L’apparence</span><small>La peinture et la déco : couleurs, tailles, positions, animations.</small></div>' +
        '<div><strong>JavaScript</strong><span>Le comportement</span><small>L’électricité : calculer, réagir aux clics, faire bouger la page.</small></div>' +
      '</div>' +
      '<p>Pas besoin de tout retenir maintenant : chaque langage sera présenté au moment où tu le rencontres.</p>'
  },
  {
    etiquette: 'Avant de commencer · 4 / 4',
    titre: 'Comment ça marche ici',
    texte:
      '<p>Tu vas relever 5 petits défis. Pour chacun :</p>' +
      '<ul>' +
        '<li>en haut, lis la <strong>consigne</strong> : elle explique chaque balise que tu vas utiliser ;</li>' +
        '<li>à <strong>gauche</strong>, tu modifies le code ;</li>' +
        '<li>à <strong>droite</strong>, tu vois le résultat en direct, à chaque lettre tapée ;</li>' +
        '<li>les <strong>objectifs</strong> se cochent tout seuls quand c’est réussi.</li>' +
      '</ul>' +
      '<p>Bloqué·e ? Clique sur <strong>Afficher un indice</strong>. Tout cassé ? ' +
      '<strong>Recommencer le défi</strong> remet le code de départ.</p>'
  }
];
window.DEFIS = [
  /* ------------------------------------------------------------------ */
  {
    titre: 'Construis ta page',
    intro: {
      etiquette: 'Nouveau langage',
      titre: 'HTML : le contenu de la page',
      texte:
        '<p><strong>HTML</strong> veut dire <em>HyperText Markup Language</em>, « langage de balisage ». ' +
        'Il ne sert pas à calculer : il sert à dire au navigateur <strong>ce qu’il y a dans la page</strong> ' +
        'et quel est le rôle de chaque morceau : ceci est un titre, ceci est un paragraphe, ceci est une liste…</p>' +
        '<p>Chaque morceau est entouré de <strong>balises</strong> : une balise ouvrante, le contenu, puis une ' +
        'balise fermante avec une barre oblique.</p>' +
        '<pre><code>&lt;h1&gt;Ceci est un titre&lt;/h1&gt;</code></pre>' +
        '<p>Toutes les pages web du monde, de YouTube à Wikipédia, commencent par du HTML.</p>'
    },
    langage: 'HTML',
    accent: '#d6336c',
    consigne:
      '<p>Une page web est faite de <strong>balises</strong>. Une balise s’ouvre (<code>&lt;p&gt;</code>) et se ferme ' +
      'avec une barre oblique (<code>&lt;/p&gt;</code>). Le texte se place entre les deux. ' +
      'Complète la page en suivant les objectifs.</p>' +
      '<p class="balises-titre">Les balises de ce défi</p>' +
      '<ul class="balises">' +
        '<li><code>&lt;h1&gt;…&lt;/h1&gt;</code> Le grand titre de la page (« h » pour <em>heading</em>, titre). ' +
          'Il s’affiche en gros et en gras. Change le texte qui se trouve entre les deux balises.</li>' +
        '<li><code>&lt;p&gt;…&lt;/p&gt;</code> Un paragraphe de texte. Le navigateur laisse un espace avant et après.</li>' +
        '<li><code>&lt;strong&gt;…&lt;/strong&gt;</code> Met en valeur un mot important : il s’affiche en gras. ' +
          'Entoure un mot du paragraphe avec ces deux balises.</li>' +
        '<li><code>&lt;ul&gt;…&lt;/ul&gt;</code> Une liste à puces (<em>unordered list</em>). Elle contient les éléments de la liste.</li>' +
        '<li><code>&lt;li&gt;…&lt;/li&gt;</code> Un élément de la liste (<em>list item</em>). Chaque <code>&lt;li&gt;</code> ' +
          'ajoute une puce. Il doit se trouver à l’intérieur du <code>&lt;ul&gt;</code>.</li>' +
        '<li><code>&lt;!-- … --&gt;</code> Un commentaire : une note pour les humains. Le navigateur ne l’affiche pas.</li>' +
      '</ul>',
    objectifs: [
      'Change le texte du titre <h1>',
      'Mets un mot du paragraphe en gras avec <strong>',
      'Ajoute un 3e élément <li> à la liste'
    ],
    indice:
      'Pour le gras, écris <strong>mer</strong> à la place de « mer ». Pour la liste, copie une ligne <li>…</li> ' +
      'et change son texte. Astuce : quand tu tapes une balise ouvrante, l’éditeur ajoute tout seul la balise fermante.',
    appris:
      'Le HTML décrit le contenu d’une page avec des balises ouvrantes et fermantes : <h1> pour un titre, <p> pour ' +
      'un paragraphe, <strong> pour le gras, <ul> et <li> pour une liste.',
    bravo: 'Tu as construit ta première page web.',
    palette: null,
    code:
`<!-- <h1> : le grand titre de la page -->
<h1>Mes vacances de rêve</h1>

<!-- <p> : un paragraphe de texte -->
<p>Cet été, je pars au bord de la mer avec mes amis.</p>

<!-- <ul> : une liste, chaque <li> est un élément -->
<ul>
  <li>Nager avec les dauphins</li>
  <li>Manger une glace</li>
</ul>
`,
    verifier: function (doc) {
      var res = [];

      var h1 = doc.querySelector('h1');
      if (!h1) res.push({ ok: false, msg: 'La balise <h1> a disparu.' });
      else {
        var titre = h1.textContent.trim();
        if (titre === 'Mes vacances de rêve') res.push({ ok: false, msg: 'Le titre n’a pas encore changé.' });
        else if (titre.length < 3) res.push({ ok: false, msg: 'Écris un titre d’au moins 3 lettres.' });
        else res.push({ ok: true, msg: 'Nouveau titre : « ' + titre + ' »' });
      }

      var gras = doc.querySelector('p strong');
      if (!gras) res.push({ ok: false, msg: 'Aucun mot en gras dans le paragraphe <p>.' });
      else if (!gras.textContent.trim()) res.push({ ok: false, msg: 'Écris un mot entre <strong> et </strong>.' });
      else res.push({ ok: true, msg: '« ' + gras.textContent.trim() + ' » est en gras.' });

      var items = Array.prototype.filter.call(doc.querySelectorAll('ul li'), function (li) {
        return li.textContent.trim().length > 0;
      });
      if (items.length >= 3) res.push({ ok: true, msg: items.length + ' éléments dans la liste.' });
      else res.push({ ok: false, msg: 'La liste a ' + items.length + ' élément' + (items.length > 1 ? 's' : '') + ', il en faut 3.' });

      return res;
    }
  },

  /* ------------------------------------------------------------------ */
  {
    titre: 'Mets de la couleur',
    intro: {
      etiquette: 'Nouveau langage',
      titre: 'CSS : l’apparence de la page',
      texte:
        '<p><strong>CSS</strong> veut dire <em>Cascading Style Sheets</em>, « feuilles de style ». ' +
        'Le HTML dit <strong>quoi</strong> afficher, le CSS dit <strong>comment</strong> l’afficher : ' +
        'couleurs, polices, tailles, marges, animations.</p>' +
        '<p>Une règle CSS choisit des éléments (ici tous les <code>h1</code>), puis leur donne des propriétés :</p>' +
        '<pre><code>h1 {\n  color: red;\n  font-size: 40px;\n}</code></pre>' +
        '<p>On sépare le contenu et l’apparence pour pouvoir changer tout le look d’un site sans toucher à son texte.</p>'
    },
    langage: 'CSS',
    accent: '#6741d9',
    consigne:
      '<p>Le CSS sert à décorer la page. Change la couleur du <strong>fond</strong> et la couleur du <strong>titre</strong>. ' +
      'Remplace les valeurs par un nom de couleur en anglais, ou sélectionne la valeur puis clique sur une couleur ci-dessous.</p>' +
      '<p class="balises-titre">Ce que tu vas utiliser</p>' +
      '<ul class="balises">' +
        '<li><code>&lt;style&gt;…&lt;/style&gt;</code> Une balise HTML qui contient du CSS. Tout ce qui est entre les deux ' +
          'balises est lu comme du CSS.</li>' +
        '<li><code>body { … }</code> Une règle pour <code>&lt;body&gt;</code>, le « corps » de la page, c’est-à-dire toute ' +
          'la partie visible. Ce qui est entre les accolades <code>{ }</code> s’applique à toute la page.</li>' +
        '<li><code>h1 { … }</code> Une règle pour tous les titres <code>&lt;h1&gt;</code> de la page.</li>' +
        '<li><code>background: …;</code> La couleur du fond. À changer dans <code>body</code>.</li>' +
        '<li><code>color: …;</code> La couleur du texte. À changer dans <code>h1</code>.</li>' +
      '</ul>' +
      '<p>Chaque ligne s’écrit <code>propriété: valeur;</code>. N’oublie pas le point-virgule à la fin.</p>',
    objectifs: ['Le fond n’est plus gris', 'Le titre a une nouvelle couleur'],
    indice:
      'Repère la ligne « background: lightgray; », efface lightgray et écris par exemple gold. Fais pareil avec « color: #333333; » dans h1.',
    appris:
      'Le CSS décide de l’apparence : couleurs, tailles, positions… Une règle ressemble à « propriété: valeur; ».',
    bravo: 'Ta carte a maintenant un style unique.',
    palette: {
      type: 'couleurs',
      valeurs: ['red', 'orange', 'gold', 'limegreen', 'turquoise', 'deepskyblue', 'royalblue', 'purple', 'hotpink', 'black', 'white']
    },
    code:
`<div class="carte">
  <h1>Ma carte de visite</h1>
  <p>Futur·e médiamaticen·ne 💻</p>
</div>

<style>
  body {
    background: lightgray;
    color: #333333;
    font-family: sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }
  h1 {
    color: #333333;
    font-size: 44px;
  }
  .carte {
    background: white;
    padding: 40px 60px;
    border-radius: 24px;
    text-align: center;
    font-size: 22px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  }
</style>
`,
    verifier: function (doc, win) {
      var res = [];
      var fond = win.getComputedStyle(doc.body).backgroundColor;
      if (fond === 'rgba(0, 0, 0, 0)' || fond === 'transparent') {
        res.push({ ok: false, msg: 'Cette couleur n’existe pas (encore ?). Essaie gold, red, hotpink…' });
      } else if (fond === 'rgb(211, 211, 211)') {
        res.push({ ok: false, msg: 'Le fond est encore gris (lightgray).' });
      } else {
        res.push({ ok: true, msg: 'Super fond !' });
      }
      var h1 = doc.querySelector('h1');
      if (!h1) {
        res.push({ ok: false, msg: 'Le titre <h1> a disparu.' });
      } else {
        var c = win.getComputedStyle(h1).color;
        if (c === 'rgb(51, 51, 51)') res.push({ ok: false, msg: 'Le titre est encore gris foncé (#333333).' });
        else res.push({ ok: true, msg: 'Joli titre !' });
      }
      return res;
    }
  },

  /* ------------------------------------------------------------------ */
  {
    titre: 'Un lien et un bouton',
    langage: 'HTML',
    accent: '#d9480f',
    consigne:
      '<p>Deux balises très utilisées sur le web : le <strong>lien</strong> et le <strong>bouton</strong>. ' +
      'Améliore le lien, puis ajoute un bouton là où le commentaire l’indique.</p>' +
      '<p class="balises-titre">Les balises de ce défi</p>' +
      '<ul class="balises">' +
        '<li><code>&lt;a&gt;…&lt;/a&gt;</code> Un lien (« a » pour <em>anchor</em>, ancre). Le texte entre les deux ' +
          'balises est ce qu’on voit et sur quoi on clique. Remplace « Clique ici » par un texte qui dit où mène le lien.</li>' +
        '<li><code>href="…"</code> Un <strong>attribut</strong> : une information en plus, écrite dans la balise ouvrante. ' +
          '<code>href</code> donne l’adresse du site où mène le lien. Garde les guillemets autour de l’adresse.</li>' +
        '<li><code>&lt;button&gt;…&lt;/button&gt;</code> Un bouton. Le texte entre les deux balises s’affiche sur le bouton. ' +
          'Il n’existe pas encore : c’est à toi de l’écrire.</li>' +
      '</ul>',
    objectifs: [
      'Remplace « Clique ici » par un texte qui dit où mène le lien',
      'Change l’adresse href pour un autre site',
      'Ajoute un bouton avec <button>…</button>'
    ],
    indice:
      'Exemple de lien : <a href="https://fr.wikipedia.org">Lire Wikipédia</a>. ' +
      'Pour le bouton, écris <button>Je m’inscris</button> sous le commentaire. ' +
      'Garde bien les guillemets autour de l’adresse !',
    appris:
      'Une balise peut avoir des attributs, comme href dans <a href="…">. Ils donnent des informations en plus : ' +
      'ici, l’adresse vers laquelle mène le lien.',
    bravo: 'Ta page a maintenant un lien et un bouton.',
    palette: null,
    code:
`<h1>Le club informatique</h1>
<p>Envie de créer des jeux et des sites web ? Rejoins-nous !</p>

<!-- <a> : un lien. href = l'adresse du site -->
<p>
  <a href="https://www.example.com">Clique ici</a>
</p>

<!-- Ajoute un bouton juste en dessous avec <button>…</button> -->


<style>
  body {
    font-family: sans-serif;
    max-width: 600px;
    margin: 40px auto;
    padding: 0 20px;
    font-size: 22px;
    background: #fff4e6;
  }
  h1 {
    color: #d9480f;
  }
  a {
    color: #1971c2;
    font-weight: bold;
  }
  button {
    font-size: 20px;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    color: white;
    background: #d9480f;
    cursor: pointer;
  }
</style>
`,
    verifier: function (doc) {
      var res = [];
      var a = doc.querySelector('a');
      if (!a) {
        res.push({ ok: false, msg: 'Le lien <a> a disparu.' });
        res.push({ ok: false, msg: 'Le lien <a> a disparu.' });
      } else {
        var texte = a.textContent.trim();
        if (/^clique ici$/i.test(texte)) res.push({ ok: false, msg: 'Le lien dit encore « Clique ici ».' });
        else if (texte.length < 3) res.push({ ok: false, msg: 'Écris un texte d’au moins 3 lettres dans le lien.' });
        else res.push({ ok: true, msg: 'Texte du lien : « ' + texte + ' »' });

        var href = (a.getAttribute('href') || '').trim();
        if (href === 'https://www.example.com') res.push({ ok: false, msg: 'Le lien mène encore vers example.com.' });
        else if (!/^https?:\/\/[^\s"]+\.[a-z]{2,}/i.test(href)) res.push({ ok: false, msg: 'L’adresse doit ressembler à https://www.site.ch' });
        else res.push({ ok: true, msg: 'Le lien mène vers ' + href });
      }
      var b = doc.querySelector('button');
      if (!b) res.push({ ok: false, msg: 'Pas encore de bouton dans la page.' });
      else if (!b.textContent.trim()) res.push({ ok: false, msg: 'Écris un texte entre <button> et </button>.' });
      else res.push({ ok: true, msg: 'Bouton « ' + b.textContent.trim() + ' » ajouté.' });
      return res;
    }
  },

  /* ------------------------------------------------------------------ */
  {
    titre: 'Fais rebondir la balle',
    langage: 'CSS',
    accent: '#1971c2',
    consigne:
      '<p>Cette balle est dessinée uniquement en CSS. Elle rebondit beaucoup trop lentement : rends-la ' +
      '<strong>plus rapide</strong>, puis change sa couleur.</p>' +
      '<p class="balises-titre">Ce que tu vas utiliser</p>' +
      '<ul class="balises">' +
        '<li><code>&lt;div class="balle"&gt;</code> Une boîte vide en HTML. L’attribut <code>class</code> lui donne un nom, ' +
          '« balle », pour pouvoir la décorer en CSS.</li>' +
        '<li><code>.balle { … }</code> La règle CSS de cette boîte. Le point devant le nom veut dire « les éléments ' +
          'qui ont la classe balle ».</li>' +
        '<li><code>width</code>, <code>height</code>, <code>border-radius: 50%</code> La largeur, la hauteur, et des coins ' +
          'arrondis à fond : le carré devient un rond.</li>' +
        '<li><code>background: …;</code> La couleur de la balle. Remplace <code>tomato</code>.</li>' +
        '<li><code>animation: rebondir 5s …;</code> Fait bouger la balle. <code>5s</code> veut dire « 5 secondes » pour un ' +
          'rebond : plus le nombre est petit, plus c’est rapide. Pour les nombres à virgule, on écrit un point : <code>0.5s</code>.</li>' +
      '</ul>',
    objectifs: ['Un rebond dure 1 seconde ou moins', 'La balle a une nouvelle couleur'],
    indice:
      'Change « 5s » en « 0.5s » (on écrit les nombres à virgule avec un point). Pour la couleur, remplace « tomato » ' +
      'dans .balle par une couleur ci-dessous.',
    appris:
      'Une animation CSS fait varier une propriété dans le temps, ici la position de la balle. ' +
      'Plus la durée est courte, plus le mouvement est rapide.',
    bravo: 'Ta balle rebondit à toute vitesse.',
    palette: {
      type: 'couleurs',
      valeurs: ['gold', 'limegreen', 'deepskyblue', 'royalblue', 'purple', 'hotpink', 'orange', 'black']
    },
    code:
`<div class="balle"></div>
<div class="sol"></div>

<style>
  body {
    margin: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    background: #e7f5ff;
  }
  .balle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: tomato;
    animation: rebondir 5s ease-in infinite alternate;
  }
  .sol {
    width: 100%;
    height: 60px;
    background: #69db7c;
  }
  @keyframes rebondir {
    from { transform: translateY(-300px); }
    to   { transform: translateY(0); }
  }
</style>
`,
    verifier: function (doc, win) {
      var b = doc.querySelector('.balle');
      if (!b) {
        return [
          { ok: false, msg: 'La balle (class="balle") a disparu.' },
          { ok: false, msg: 'La balle (class="balle") a disparu.' }
        ];
      }
      var res = [];
      var st = win.getComputedStyle(b);
      var d = String(st.animationDuration).split(',')[0].trim();
      var sec = parseFloat(d);
      if (/ms$/.test(d)) sec = sec / 1000;
      if (st.animationName === 'none' || !(sec > 0)) {
        res.push({ ok: false, msg: 'La balle ne bouge plus : vérifie la ligne animation (ex : 0.5s).' });
      } else if (sec > 1) {
        res.push({ ok: false, msg: 'Durée actuelle : ' + sec + ' s. Encore plus vite !' });
      } else {
        res.push({ ok: true, msg: 'Durée : ' + sec + ' s.' });
      }
      var c = st.backgroundColor;
      if (c === 'rgba(0, 0, 0, 0)' || c === 'transparent') res.push({ ok: false, msg: 'Cette couleur n’existe pas. Essaie gold, hotpink…' });
      else if (c === 'rgb(255, 99, 71)') res.push({ ok: false, msg: 'La balle est encore « tomato ».' });
      else res.push({ ok: true, msg: 'Nouvelle couleur appliquée.' });
      return res;
    }
  },

  /* ------------------------------------------------------------------ */
  {
    titre: 'La table de multiplication',
    intro: {
      etiquette: 'Nouveau langage',
      titre: 'JavaScript : le comportement de la page',
      texte:
        '<p>HTML et CSS décrivent une page, mais ils ne peuvent pas prendre de décisions ni faire de calculs. ' +
        '<strong>JavaScript</strong> est un vrai <strong>langage de programmation</strong> : il peut calculer, ' +
        'répéter des actions, réagir à un clic et modifier la page pendant qu’on l’utilise.</p>' +
        '<p>Un programme est une suite d’instructions que l’ordinateur exécute dans l’ordre, très vite :</p>' +
        '<pre><code>const prix = 12;\nconst total = prix * 3;   // l’ordinateur calcule 36</code></pre>' +
        '<p>C’est JavaScript qui fait fonctionner les jeux en ligne, les cartes interactives ou les messageries, ' +
        'directement dans ton navigateur.</p>'
    },
    langage: 'JavaScript',
    accent: '#2b8a3e',
    consigne:
      '<p>Voici du vrai <strong>JavaScript</strong>. Change la valeur de <code>nombre</code> pour afficher une ' +
      '<strong>autre table</strong>, et celle de <code>jusqua</code> pour aller <strong>jusqu’à 12</strong>.</p>' +
      '<p class="balises-titre">Ce que tu vas utiliser</p>' +
      '<ul class="balises">' +
        '<li><code>&lt;script&gt;…&lt;/script&gt;</code> Une balise HTML qui contient du JavaScript. Le navigateur ' +
          'exécute ce code, ligne après ligne.</li>' +
        '<li><code>const nombre = 7;</code> Crée une <strong>variable</strong> : une boîte avec un nom (<code>nombre</code>) ' +
          'qui garde une valeur (<code>7</code>). C’est ici que tu changes la table.</li>' +
        '<li><code>const jusqua = 5;</code> Une autre variable : jusqu’à combien la table va. Mets 12 ou plus.</li>' +
        '<li><code>// …</code> Un commentaire : l’ordinateur ignore tout ce qui suit les deux barres sur la ligne.</li>' +
        '<li><code>for (let i = 1; i &lt;= jusqua; i++)</code> Une <strong>boucle</strong> : elle répète le code entre ' +
          '<code>{ }</code>. <code>i</code> commence à 1, augmente de 1 à chaque tour (<code>i++</code>), et la boucle ' +
          's’arrête quand <code>i</code> dépasse <code>jusqua</code>.</li>' +
        '<li><code>nombre * i</code> Le calcul : <code>*</code> veut dire « fois ». Au 3e tour, ça donne 7 × 3.</li>' +
      '</ul>',
    objectifs: ['Affiche une autre table que celle de 7', 'Va au moins jusqu’à 12'],
    indice:
      'Change « const nombre = 7; » en « const nombre = 9; » et « const jusqua = 5; » en « const jusqua = 12; ». ' +
      'Si tu écris i-- au lieu de i++, la boucle ne s’arrête jamais. Pas de panique : l’aperçu se relance tout seul.',
    appris:
      'Une boucle permet à l’ordinateur de répéter une action autant de fois qu’on veut, sans se tromper. ' +
      'Essaie jusqua = 1000 : il calcule 1000 lignes en un clin d’œil.',
    bravo: 'Tu as programmé une boucle en JavaScript.',
    palette: null,
    code:
`<h1>Table de multiplication</h1>
<div id="table"></div>

<script>
  const nombre = 7;   // quelle table ?
  const jusqua = 5;   // jusqu'à combien ?

  const table = document.getElementById("table");

  for (let i = 1; i <= jusqua; i++) {
    const ligne = document.createElement("p");
    ligne.className = "ligne";
    ligne.textContent = nombre + " × " + i + " = " + (nombre * i);
    table.appendChild(ligne);
  }
</script>

<style>
  body {
    font-family: sans-serif;
    margin: 0;
    padding: 30px;
    background: #ebfbee;
  }
  h1 {
    color: #2b8a3e;
  }
  #table {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 10px;
  }
  .ligne {
    margin: 0;
    padding: 10px 14px;
    background: white;
    border-radius: 6px;
    font-family: monospace;
    font-size: 22px;
  }
</style>
`,
    verifier: function (doc) {
      var lignes = doc.querySelectorAll('.ligne');
      var n = lignes.length;
      var res = [];
      if (n === 0) {
        res.push({ ok: false, msg: 'Aucune ligne affichée pour l’instant.' });
      } else {
        var m = lignes[0].textContent.match(/^\s*(-?[\d.]+)\s*×/);
        var table = m ? m[1] : '?';
        if (table === '7') res.push({ ok: false, msg: 'C’est encore la table de 7.' });
        else res.push({ ok: true, msg: 'Table de ' + table + '.' });
      }
      if (n >= 12) res.push({ ok: true, msg: n + ' lignes calculées.' });
      else res.push({ ok: false, msg: n + ' ligne' + (n > 1 ? 's' : '') + ' pour l’instant, il en faut 12.' });
      return res;
    }
  }
];
