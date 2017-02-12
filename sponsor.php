<?php include 'header.php'; ?>
<div class="lmsCoureurs" style="border-style: solid">
  <center>
    <h2> Sponsors/Equipes </h2>
    <!-- LISTE DES SPONSORS -->
    <div class="divConteneur" id="conteneurTableauSponsor">
      <table class="defaut" id="tableauSponsors">
        <thead><tr><th style="display:none;">Numéro equipe</th><th style="display:none;">Numéro sponsor</th><th>Nom</th><th>NA</th><th>Pays</th><th>Année création</th><th>Année disparition</th></tr>
        </thead>
        <tbody>
          <?php
				// On récupère les sponsors et on affiche dans un tableau
			  require "traitementPhp/fonc_oracle.php";
			  $conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
			  $reqSponsor = 'select n_equipe, n_sponsor, (select nom from tdf_pays where tdf_pays.code_tdf = tdf_sponsor.code_tdf) as code_tdf, nom, na_sponsor, annee_sponsor, annee_creation, annee_disparition from tdf_sponsor join tdf_equipe using(n_equipe)';
			  $curSponsor = PreparerRequete($conn,$reqSponsor);
			  $resSponsor = ExecuterRequete($curSponsor);
			  $nbLignesSponsor = LireDonnees1($curSponsor,$tabSponsor);  
			  for ($i=0;$i<$nbLignesSponsor;$i++) {
				echo '<tr onclick="SelectSponsor(this);">'; // Dès qu'il y a un clic on lance Select Sponsor en passant la ligne en paramètre
				echo '<td style="display: none;">' . $tabSponsor[$i]['N_EQUIPE'] . '</td>';
				echo '<td style="display: none;">' . $tabSponsor[$i]['N_SPONSOR'] . '</td>';
				echo '<td>' . $tabSponsor[$i]['NOM'] . '</td>';
				echo '<th>' . $tabSponsor[$i]['NA_SPONSOR'] . '</th>';
				echo '<th>' . $tabSponsor[$i]['CODE_TDF'] . '</th>';
				echo '<th>' . $tabSponsor[$i]['ANNEE_CREATION'] . '</th>';
				echo '<th>' . $tabSponsor[$i]['ANNEE_DISPARITION'] . '</th>';
				echo '</tr>';
			  }      
		  ?> 
        </tbody></table>
    </div>
  </center>
</div>


<!-- LISTE DES PARTICIPATIONS DU SPONSOR -->
<div class="lmsCoureurs" style="border-style: solid">
  <center>
    <h2> Participations </h2>
    <!-- Tableau vide qu'on remplira une fois que la personne aura cliqué sur un sponsor -->
    <div class="divConteneur" id="conteneurTableauParticipationSponsor">
      <table class="defaut" id="tableauParticipationSponsor">
        <thead>
          <tr>
            <th>Année</th><th>Nom directeur</th><th>Prénom directeur</th><th>Nom codirecteur</th><th>Prénom codirecteur</th><th>Classement</th><th>Score</th>
          </tr>
        </thead>
        <tbody>
        </tbody></table>
    </div>
  </center>
</div>

<script>
  var idSponsor = 0;
  $('#conteneurTableauSponsor').show();
  $('#conteneurTableauParticipationSponsor').show();
  $('#tableauSponsors').DataTable( {
    "scrollY":        "250px",
    "scrollCollapse": true,
    "paging":         false,
    "order": [[ 0, "asc" ]],
    "createdRow": function( row, data, dataIndex ) {
      // Pour chaque ligne qu'on va ajouté :
      $(row).addClass( 'defaut' );
      idSponsor++;
      $(row).attr('id', 'sponsor' + idSponsor); // Attribution de l'ID
      $(row).children("*").addClass( 'defaut' );
    }
  }); 
  $('#tableauParticipationSponsor').DataTable( {
    "scrollY":        "250px",
    "scrollCollapse": true,
    "paging":         false,
    "order": [[ 0, "asc" ]],
    "createdRow": function( row, data, dataIndex ) {
      // Pour chaque ligne qu'on va ajouté :
      $(row).addClass( 'defaut' );
      $(row).children("*").addClass( 'defaut' );
    }
  }); 
</script>
</body>
</html>