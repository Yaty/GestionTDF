## GestionTDF

La base est dans le fichier tdf_db.dmp. Base créée par E. Porcq.

Le site possède 3 fichiers principaux :
- sponsor.php
- tdf.php
- coureur.php
Ces fichiers correspondent aux pages Sponsor/Equipe, Tour et Coureur.

Tous les traitements Php (ajouts, suppressions, modifications, lecture de la base) sont dans le dossier traitementPhp.
Le javascript est dans fonction.js
Nous avons souhaité réaliser un site qui ne nécessite pas de recharger la page pour avoir plus d'informations.
Pour cela nous avons utiliser Ajax et Jquery ainsi que Datatables pour les tableaux et JQueryUI pour les dialogues.

Fonctionnement de coureur.php :
1) On charge le tableau coureur
2) On detecte le clic sur ce tableau, si c'est le cas on lance une fonction JS qui utilisera Ajax pour lancer un traitement PHP et récuperer
les participations du coureur sélectionné.
3) On affiche les participations du coureur
4) On attend le clic sur ce tableau et on fait la même chose (Ajax, Php, on récupère et on affiche dynamiquement)
Détection du clic -> Javascript -> Ajax -> Php -> BDD -> Php -> Ajax -> Javascript -> Mis à jour sur le site

Tdf et sponsor fonctionne de la même manière.

Hugo Da Roit.
Benjamin Lévêque.