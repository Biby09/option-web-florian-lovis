Départ — deuxième entité + URLs

Votre enseignant·e vous donne ce dossier. Placez son contenu
pour obtenir exactement cet arbre :

  m10/urls-propres/config.exemple.php
  m10/urls-propres/config.php
  m10/urls-propres/bootstrap.php
  m10/urls-propres/router.php
  m10/urls-propres/inc/header.php
  m10/urls-propres/inc/footer.php
  m10/urls-propres/src/Evenement.php
  m10/urls-propres/src/EvenementManager.php
  m10/urls-propres/src/Concert.php
  m10/urls-propres/src/ConcertManager.php
  m10/urls-propres/liste.php
  m10/urls-propres/fiche.php
  m10/urls-propres/concert.php

Le dossier data/ et ce README ne font pas partie de l’arbre à
rendre.

Ouvrez d’abord projets/04-urls-propres/1-COURS.pdf, puis
projets/04-urls-propres/2-ATELIER.pdf.

Si vous avez le projet 03, copiez-le ici puis ajoutez les
chemins manquants. Sinon, partez de ce dossier.

Depuis ce dossier, le serveur doit passer par le routeur :

  php -S localhost:8080 router.php

Puis ouvrez http://localhost:8080/ et, plus tard,
http://localhost:8080/festivals/paleo
