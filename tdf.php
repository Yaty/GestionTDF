<?php include 'header.php'; ?>
    Sélectionnez le tour qui vous intéresse :
    <select onchange="chargementTour();" id="listeTour"> <!-- Au clic on execute chargementTour() qui chargera la tableau des tours qu'on récupère via Ajax qui demande à du PHP -->
    
    <?php
	  require "traitementPhp/fonc_oracle.php";
	  $conn = OuvrirConnexion('X', 'X', 'instance'); // Your ID here, and the instance
	  $reqTour = 'SELECT annee FROM tdf_annee order by annee desc';
	  $curTour = PreparerRequete($conn,$reqTour);
	  $resTour = ExecuterRequete($curTour);
	  $nbLignesTour = LireDonnees1($curTour,$tabTour);  
	  for ($i=0;$i<$nbLignesTour;$i++) {
		echo '<option>'.$tabTour[$i]["ANNEE"].'</option>';
	  }
	?>
    </select>
    
    <div class="lmsCoureurs" style="border-style: solid">
      <center>
        <h2> Epreuves </h2>
    <!-- LISTE DES EPREUVES DU TOUR SELECTIONNER -->
	<!-- On remplit ce tableau quand la personne sélectionne un tour dans la liste -->
        <div class="divConteneur" id="conteneurTableauTour">
          <table class="defaut" id="tableauEpreuves2">
            <thead><tr>
              <th>Numéro Epreuve</th>
              <th>Année</th>
              <th>Ville départ</th>
              <th>Ville arrivé</th>
              <th>Distance</th>
              <th>Moyenne</th>
              <th>Jour</th>
              <th>Catégorie de l'épreuve</th>
              </tr></thead>
            <tbody>
            </tbody></table>
        </div>
      </center>
    </div>
    
  
    <!-- CLASSEMENT EPREUVE -->
    <div class="lmsCoureurs" style="border-style: solid">
      <center>
        <h2>Classement</h2>
        <!-- Tableau vide qu'on remplira dès que la personne aura cliqué sur une epreuve -->
        <div class="divConteneur" id="conteneurTableauTourClassementEpreuve">
          <table class="defaut" id="tableauEpreuvesTourClassement">
            <thead><tr>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Total en seconde</th>
              <th>Rang</th>
              </tr></thead>
            <tbody>
            </tbody></table>
        </div>
      </center>
    </div>

    <!-- CLASSEMENT GENERAL -->
    <div class="lmsCoureurs" style="border-style: solid">
      <center>
        <h2>Classement général</h2>
        <!-- Tableau vide qu'on remplira dès que la personne aura choisis un tour -->
        <div class="divConteneur" id="conteneurTableauClassementGeneral">
          <table class="defaut" id="tableauClassementGeneral">
            <thead><tr>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Rang</th>
              <th>Temps total</th>
              </tr></thead>
            <tbody>
            </tbody></table>
        </div>
      </center>
    </div>
    
  <script>
    var idEpreuve = 0;
    $('#conteneurTableauTour').show();
    $('#conteneurTableauClassementGeneral').show();
    $('#conteneurTableauTourClassementEpreuve').show();
    var idEpreuve = idCoureur = idCoureur2 = 0;
       $('#tableauEpreuves2').DataTable( {
          "scrollY":        "250px",
          "scrollCollapse": true,
          "paging":         false,
          "order": [[ 0, "asc" ]],
          "createdRow": function( row, data, dataIndex ) {
            // Pour chaque ligne qu'on va ajouté :
            $(row).addClass( 'defaut' );
            idEpreuve++;
            $(row).attr('id', 'epreuve' + idEpreuve); // Attribution de l'ID
            $(row).attr('onclick', 'SelectTdf(this);');
            $(row).children("*").addClass( 'defaut' );
          }
        }); 
           $('#tableauEpreuvesTourClassement').DataTable( {
          "scrollY":        "250px",
          "scrollCollapse": true,
          "paging":         false,
          "order": [[ 0, "asc" ]],
          "createdRow": function( row, data, dataIndex ) {
            // Pour chaque ligne qu'on va ajouté :
            $(row).addClass( 'defaut' );
            idCoureur++;
            $(row).attr('id', 'coureur' + idCoureur); // Attribution de l'ID
            //$(row).attr('onclick', 'SelectLigne2(this);');
            $(row).children("*").addClass( 'defaut' );
          }
        }); 
               $('#tableauClassementGeneral').DataTable( {
          "scrollY":        "250px",
          "scrollCollapse": true,
          "paging":         false,
          "order": [[ 2, "asc" ]],
          "createdRow": function( row, data, dataIndex ) {
            // Pour chaque ligne qu'on va ajouté :
            $(row).addClass( 'defaut' );
            idCoureur2++;
            $(row).attr('id', 'coureur2' + idCoureur2); // Attribution de l'ID
            //$(row).attr('onclick', 'SelectLigne2(this);');
            $(row).children("*").addClass( 'defaut' );
          }
        }); 
  </script>
  </body>
</html>