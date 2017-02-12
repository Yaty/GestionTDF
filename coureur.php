<?php include 'header.php';
require "traitementPhp/fonc_oracle.php";
$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
$reqCoureur = 'SELECT * FROM tdf_coureur order by nom';
$curCoureur = PreparerRequete($conn,$reqCoureur);
$resCoureur = ExecuterRequete($curCoureur);
$nbLignesCoureur = LireDonnees1($curCoureur,$tabCoureur);  
  
$reqCodeTdf = 'SELECT code_tdf, nom FROM tdf_pays order by code_tdf';
$curCodeTdf = PreparerRequete($conn, $reqCodeTdf);
$resCodeTdf = ExecuterRequete($curCodeTdf);
$nbLignesCodeTdf = LireDonnees1($curCodeTdf, $tabCodeTdf);
?>

<div class="lmsCoureurs" style="border-style: solid">
      <center>
        <h2> Coureurs </h2>
        <!-- LISTE DES COUREURS -->
        <div class="divConteneur" id="conteneurTableauCoureur">
		<table class="defaut" id="tableauCoureur">
			<thead>
				<tr>
					<th style="display:none;">Numéro Coureur</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date de naissance</th>
					<th>Nationalité</th>
					<th>Premier TDF</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// On ajoute chaque coureur dans le tableau
				for ($i=0;$i<$nbLignesCoureur;$i++) {
					echo '<tr class="defaut" onclick="SelectLigne(this);">'; // Au clic on lance SelectLigne en passant la ligne en paramètre
					echo '<td style="display:none;" class="defaut">'.$tabCoureur[$i]["N_COUREUR"].'</td>';
					echo '<td class="defaut">'.$tabCoureur[$i]["NOM"].'</td>';
					echo '<td class="defaut">'.$tabCoureur[$i]["PRENOM"].'</td>';
					echo '<td class="defaut">'.$tabCoureur[$i]["ANNEE_NAISSANCE"].'</td>';
					echo '<td class="defaut">'.$tabCoureur[$i]["CODE_TDF"].'</td>';
					echo '<td class="defaut">'.$tabCoureur[$i]["ANNEE_PREM"].'</td></tr>';
				}
				?>
			</tbody>
		</table>
        </div>
        <div style="margin-top: 10px;">
			<input id="modifierCoureur" class="ui-button ui-corner-all ui-widget" type="button" value="Modifier la sélection" onclick="loadFormModification();">
			<input type="button" class="ui-button ui-corner-all ui-widget" value="Supprimer la sélection" onclick="supprimerSelectionCoureur();">
			<br/>
			<input id="buttonAjouterCoureur" class="ui-button ui-corner-all ui-widget" type="button" value="Ajouter un coureur"> <!-- On gère le clic via Jquery (cf bas de la page) -->
        </div>
      </center>
    </div>
    <!-- FIN DE LA LISTE DES COUREURS -->
    
    <!-- LISTE DES PARTICIPATION D'UN COUREUR -->
	<!-- Tableau qu'on remplit via Ajax qui demande à du PHP quand la personne aura cliquer sur un coureur -->
    <div class="lmsCoureurs" style="border-style: solid">
      <center>
        <h2> Participations Coureur </h2>
        Sélectionnez une participation pour avoir le détail.
        <div class="divConteneur" id="conteneurTableauParticipation">
			<table class="defaut" id="tableauParticipation">
				<thead>
					<tr>
						<th style="display:none;">Numéro Coureur</th>
						<th>Année</th>
						<th>Equipe/Sponsor</th>
						<th>Dossard</th>
						<th>Classement</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
        </div>
        <div style="margin-top: 10px;">
			<div id="ajoutParticipation">
				Ajouter une participation dans la base :
				<form action="traitementPhp/actionParticipation.php" method="post">
				</form>
			</div>
        </div>
      </center>
    </div>  
    <!-- FIN DE LA LISTE DES PARTICIPATIONS D'UN COUREUR -->
    
    <!-- LISTE DES EPREUVES D'UNE PARTICIPATION D'UN COUREUR -->
    <div class="lmsCoureurs" style="border-style: solid">
      <center>
        <h2> Epreuves Coureur </h2>
        Sélectionnez une participation pour accèder aux épreuves.
        <div class="divConteneur" id="conteneurTableauEpreuves">
			<table class="defaut" id="tableauEpreuves">
				<thead>
					<tr>
					  <th style="display:none;">Numéro Epreuve</th>
					  <th>Ville départ</th>
					  <th>Ville arrivé</th>
					  <th>Distance</th>
					  <th>Moyenne</th>
					  <th>Jour</th>
					  <th>Catégorie</th>
					  <th>Durée</th>
					  <th>Rang</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
        </div>
      </center>
    </div>
    <!-- FIN DE LA LISTE DES EPREUVES D'UNE PARTICIPATION D'UN COUREUR -->
    
    <!-- Formulaire modification d'un coureur, envoi via Ajax à un traitement Php. Il s'affichera lors de l'appuie sur le bouton modifier -->
    <div id="dialog-modifierCoureur" title="Modifier le coureur" style="display: none;">
      <p class="validateTips">Les champs précédés d'un * sont obligatoires !</p>
      
      <form>
        <fieldset>
          <table id="formModifCoureur">
            <tr>
              <td><input required type="hidden" name="ncoureur" id="ncoureur" class="text ui-widget-content ui-corner-all"></td>
            </tr>
            <tr>
              <td><label for="nom">* Nom</label></td>
              <td><input required type="text" name="nom" id="nom" class="text ui-widget-content ui-corner-all"></td>
            </tr>
            <tr>
              <td><label for="prenom">* Prénom</label></td>
              <td><input required type="text" name="prenom" id="prenom" class="text ui-widget-content ui-corner-all"></td>
            </tr>
            <tr>
              <td><label for="ddn">Date de naissance</label></td>
              <td><input type="number" name="ddn" id="ddn" class="text ui-widget-content ui-corner-all"></td>
            </tr>
            <tr>
              <td><label for="codePays">* Pays</label></td>
              <td>
                <select required id="codePays">
					<?php
						for($i=0;$i<$nbLignesCodeTdf;$i++) {
							echo '<option value="' . $tabCodeTdf[$i]['CODE_TDF'] . '">' . $tabCodeTdf[$i]['NOM'] . '</option>'; 
						}  
					?>
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="ddn">Date du premier TDF</label></td>
              <td><input type="text" name="datePremierTdf" id="datePremierTdf" class="text ui-widget-content ui-corner-all"></td>
            </tr>            
          </table>
        </fieldset>
      </form>
    </div>
    
    
    <!-- Formulaire ajout d'un coureur, envoi en Ajax pour traitement Php. Il s'ouvre lorsque le bouton ajouter est utilisé -->
    <div id="dialog-ajouterCoureur" title="Ajouter un coureur" style="display: none;">
      <p class="validateTips">Les champs précédés d'un * sont obligatoires !</p>
      <form>
        <fieldset>
          <table id="formAjouterCoureur">
            <tr>
              <td><label for="nomAjoutCoureur">* Nom</label></td>
              <td><input type="text" name="nomAjoutCoureur" id="nomAjoutCoureur" class="text ui-widget-content ui-corner-all"></td>
            </tr>
            <tr>
              <td><label for="prenomAjoutCoureur">* Prenom</label></td>
              <td><input type="text" name="prenomAjoutCoureur" id="prenomAjoutCoureur" class="text ui-widget-content ui-corner-all"></td>
            </tr>
            <tr>
              <td><label for="codePaysAjout">* Pays</label></td>
              <td>
                <select id="codePaysAjout">
					<?php
						for($i=0;$i<$nbLignesCodeTdf;$i++) {
							echo '<option value="' . $tabCodeTdf[$i]['CODE_TDF'] . '">' . $tabCodeTdf[$i]['NOM'] . '</option>'; 
						}  
					?>
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="ddnAjoutCoureur"> Date de naissance</label></td>
              <td><input type="number" name="ddnAjoutCoureur" id="ddnAjoutCoureur" class="text ui-widget-content ui-corner-all"></td>
            </tr>
            <tr>
              <td><label for="dptAjoutCoureur"> Date premier tour</label></td>
              <td><input type="number" name="dptAjoutCoureur" id="dptAjoutCoureur" class="text ui-widget-content ui-corner-all"></td>
            </tr>
          </table>
        </fieldset>
      </form>
    </div>
    
	<!-- Liste des dialogues que l'ont affiche en cas de notification. -->
    <div id="dialogCoureurSuppr1" title="Opération réussie" style="display:none;">
      <p>Le coureur a été supprimé de la base avec succès.</p>
    </div>
    
    <div id="dialogCoureurSuppr2" title="Erreur" style="display:none;">
      <p>Il est interdit de supprimer un coureur ayant des participations.</p>
    </div>
    
    <div id="dialogRienSelec" title="Erreur" style="display:none;">
      <p>Vous devez sélectionner un coureur pour réaliser cette action.</p>
    </div>
    
    <div id="dialogCourreurAjoute" title="Opération réussie" style="display:none;">
      <p id="pDialogCourreurAjoute"></p>
    </div>
    
    <div id="dialogErreurAjoutCoureur" title="Erreur" style="display:none;">
      <p id="pDialogErreurAjoutCoureur"></p>
    </div>
    
     <div id="dialogCoureurModif" title="Opération réussie" style="display:none;">
      <p id="pDialogCoureurModif"> </p>
    </div>
    
	<!-- Gestion des tableaux ci-dessous -->
    <script>   
      $(document).ready(function() {
        var idParticipation = 0, idEpreuve = 0, idLigneCoureur = 0;
        $('#conteneurTableauCoureur').show();
        $('#conteneurTableauParticipation').show();
        $('#conteneurTableauEpreuves').show();
        $('#tableauCoureur').DataTable( {  
          "scrollY":        "250px",
          "scrollCollapse": true,
          "paging":         false,
          "order": [[ 0, "desc" ]],
          "createdRow": function( row, data, dataIndex ) {
            // Pour chaque ligne qu'on va ajouté :
            $(row).addClass( 'defaut' ); // Ajout de la classe defaut
            idLigneCoureur++;
            $(row).attr('id', 'ajoutLigneCoureur' + idLigneCoureur); // Attribution de l'ID
            $(row).children("*").addClass( 'defaut' ); // On ajoute à tous les enfants defaut
            $(row).children(":first").hide(); // On cache
          }
        });
        $('#tableauParticipation').DataTable( {
          "scrollY":        "250px",
          "scrollCollapse": true,
          "paging":         false,
          "order": [[ 1, "desc" ]],
          "createdRow": function( row, data, dataIndex ) {
            // Pour chaque ligne qu'on va ajouté :
            $(row).addClass( 'defaut' );
            idParticipation++;
            $(row).attr('id', 'participation' + idParticipation); // Attribution de l'ID
            $(row).attr('onclick', 'SelectLigne2(this);');
            $(row).children("*").addClass( 'defaut' );
            $(row).children(":first").hide();
          }
        });       
        $('#tableauEpreuves').DataTable( {
          "scrollY":        "250px",
          "scrollCollapse": true,
          "paging":         false,
          "order": [[ 0, "desc" ]],
          "createdRow": function( row, data, dataIndex ) {
            // Pour chaque ligne qu'on va ajouté :
            $(row).addClass( 'defaut' );
            idEpreuve++;
            $(row).attr('id', 'epreuve' + idEpreuve); // Attribution de l'ID
            //$(row).attr('onclick', 'SelectLigne2(this);');
            $(row).children("*").addClass( 'defaut' );
            $(row).children(":first").hide();
          }
        });   
      } );  
      
      $( function() {
        var dialogueModifierCoureur,
            ncoureur = $( "#ncoureur" ),
            nom = $( "#nom" ),
            prenom = $( "#prenom" ),
            ddn = $( "#ddn" ),
            codePays = $( "#codePays" ),
            datePremierTour = $( "#datePremierTdf" );
			allFields = $( [] ).add( ncoureur ).add( nom ).add( prenom ).add( ddn ).add( codePays ).add(datePremierTour);
			var dialogueAjouterCoureur,
            nomAjoutCoureur = $( "#nomAjoutCoureur" ),
            allFields2 = $( [] ).add(nomAjoutCoureur);
        
        dialogueModifierCoureur = $( "#dialog-modifierCoureur" ).dialog({
          autoOpen: false,
          height: 400,
          width: 350,
          modal: true,
          buttons: {
            Modifier: function() { // Quand on clic sur modifier on execute ça :
              allFields.removeClass( "ui-state-error" );
              // Fonction qui va modifier dans la BDD et le tableau via Ajax et PHP, gère aussi les erreur via PHP
              changerValeurCoureur(ncoureur.val(), nom.val(), prenom.val(), ddn.val(), codePays.val(), datePremierTour.val());
              dialogueModifierCoureur.dialog( "close" );          
            },
            Annuler: function() {
              dialogueModifierCoureur.dialog( "close" );
            }
          },
          close: function() {
            dialogueModifierCoureur.dialog("close");
            allFields.removeClass( "ui-state-error" );
          }
        });
    
        // Ajout onclick sur le boutton pour modifier un coureur, ça va ouvrir le dialog 1
        $( "#modifierCoureur" ).button().on( "click", function() {
          if (ObjSelec != null) {
            dialogueModifierCoureur.dialog( "open" );
          }
        }); 
        
        
    
        dialogueAjouterCoureur = $( "#dialog-ajouterCoureur" ).dialog({
          autoOpen: false,
          height: 400,
          width: 350,
          modal: true,
          buttons: {
            Ajouter: function() {
				ajouterCoureur();
				dialogueAjouterCoureur.dialog( "close" );          
            },
            Cancel: function() {
				dialogueAjouterCoureur.dialog( "close" );
            }
          },
          close: function() {
			dialogueAjouterCoureur.dialog("close");
			allFields.removeClass( "ui-state-error" );
          }
        });
        
        // On ouvre dialogueAjouterCoureur
        $( "#buttonAjouterCoureur" ).button().on( "click", function() {
          dialogueAjouterCoureur.dialog( "open" );
        });
      });
    </script>
  </body>
</html>