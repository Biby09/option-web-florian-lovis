Départ — annuaire avec classes

Votre enseignant·e vous donne ce dossier. Placez son contenu
pour obtenir exactement cet arbre :

  m10/annuaire-classes/config.exemple.php
  m10/annuaire-classes/config.php
  m10/annuaire-classes/bootstrap.php
  m10/annuaire-classes/src/Evenement.php
  m10/annuaire-classes/src/EvenementManager.php
  m10/annuaire-classes/liste.php
  m10/annuaire-classes/fiche.php

Le dossier data/ et ce README ne font pas partie de l’arbre à
rendre. Ils permettent d’ouvrir l’application sans MySQL.

Ouvrez d’abord projets/02-annuaire-classes/1-COURS.pdf, puis
projets/02-annuaire-classes/2-ATELIER.pdf.

Si vous avez déjà le projet 01, vous pouvez le copier ici puis
ajouter src/. Sinon, partez de ce dossier.

Depuis ce dossier :

  php -S localhost:8080

Puis ouvrez http://localhost:8080/liste.php

La liste est vide tant que tous() ne fabrique pas d’objets.
C’est normal.

Pour passer sur MySQL : copiez config.exemple.php vers
config.php, créez la table du schéma ci-dessous, puis
remplissez trois lignes.

  CREATE TABLE evenements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    ville VARCHAR(80) NOT NULL,
    periode VARCHAR(40) NOT NULL
  );
