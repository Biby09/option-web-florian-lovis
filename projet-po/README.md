# Code ton premier programme

Application web pour les portes ouvertes : 5 défis progressifs (HTML, CSS, JavaScript) avec un éditeur de code et un aperçu en direct.

## Lancer

Ouvrir `index.html` dans Chrome, Edge ou Firefox (double-clic). Aucun serveur ni connexion internet n'est nécessaire : CodeMirror 5.65.18 est inclus dans `vendor/codemirror/`.

Pour la borne : passer le navigateur en plein écran (F11, ou Ctrl+Cmd+F sur Mac).

## Fichiers

| Fichier | Rôle |
|---|---|
| `index.html` | Structure de la page |
| `css/style.css` | Design |
| `js/defis.js` | **Les défis** : consignes, code de départ, fonction de vérification |
| `js/app.js` | Éditeur, aperçu, détection de réussite, protections, inactivité |

## Modifier ou ajouter un défi

Chaque entrée de `window.DEFIS` dans `js/defis.js` contient le code de départ (`code`), les libellés des objectifs (`objectifs`) et une fonction `verifier: function (doc, win) { … }` qui s'exécute **dans l'aperçu** et renvoie un tableau `{ ok, msg }` (un élément par objectif). Cette fonction doit être autonome : elle est convertie en texte puis injectée dans l'iframe.

Le champ facultatif `intro` (`etiquette`, `titre`, `texte` en HTML) affiche une fenêtre de présentation la première fois qu'on ouvre le défi (utilisé pour HTML, CSS et JavaScript). La fenêtre « Comment fonctionne une page web ? », affichée après « Commencer », est définie dans `window.INTRO_WEB`, en haut du même fichier.

Pour tester un défi sans faire les précédents : `index.html?defi=3`, ou `allerAuDefi(3)` dans la console du navigateur.

## Fonctionnement

- **Aperçu** : iframe `sandbox="allow-scripts"` (pas d'accès à la page principale, ni de popups, `alert()` ou formulaires). Une nouvelle iframe est créée à chaque modification, 350 ms après la dernière frappe.
- **Réussite** : quand tous les objectifs sont atteints et le restent pendant 1,5 s, une animation de félicitations s'affiche, puis le défi suivant démarre (bouton ou automatiquement après 7 s).
- **Boucles infinies**, deux protections :
  1. chaque boucle `for` / `while` / `do…while` (dans les `<script>` et les attributs `onclick=…`) est instrumentée et arrêtée au-delà de 1 s ou de 10 000 tours ; un message explique l'erreur ;
  2. en filet de sécurité, si l'aperçu ne répond plus pendant 2 s, l'iframe est supprimée et rechargée.
- **Inactivité** : après 22 s sans activité, un avertissement s'affiche ; à 30 s, tout revient au défi 1 avec l'écran d'accueil.

Réglages (délais) en haut de `js/app.js`.
