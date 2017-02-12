<?php
	include("./regexNomCoureur.php");
	require "fonc_oracle.php";
	if(isset($_POST['prenom']) && !empty($_POST['prenom'])) {
		$prenom=strVerificationPrenom($_POST['prenom']);
		if(isset($_POST['nom']) && !empty($_POST['nom'])) {
			$nom = strVerificationNom($_POST['nom']);
			if($prenom != "Interdit" && $nom != "Interdit" && isset($_POST['code_tdf']) && !empty($_POST['code_tdf']) ) {
				$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
				// On échappe les ' avec des ' pour que ça passe dans la requete sans problème
				$nom = str_replace("'", "''", $nom);
				$prenom = str_replace("'", "''", $prenom);
				
				// Dans le cas où l'utilisateur rentre aussi la date de naissance et du premier tour
				if(isset($_POST['annee_naissance']) && !empty($_POST['annee_naissance']) && isset($_POST['annee_premiere']) && !empty($_POST['annee_premiere'])) {
					$reqCoureur = "insert into tdf_coureur (NOM,PRENOM,CODE_TDF,N_COUREUR,ANNEE_NAISSANCE,ANNEE_PREM,COMPTE_ORACLE,DATE_INSERT) values ('".$nom."','".$prenom."','".$_POST['code_tdf']."',(select max(n_coureur)+1 from tdf_coureur),'".$_POST['annee_naissance']."','".$_POST['annee_premiere']."','ETU2_64','".date("d-m-y")."')";
					$retour = array(str_replace("''", "'", $nom), str_replace("''", "'", $prenom), $_POST['code_tdf'], $_POST['annee_naissance'], $_POST['annee_premiere']);
					if($_POST['annee_premiere'] - $_POST['annee_naissance'] < 18) {
						$retour = "Le coureur n'est pas majeur, il ne peut pas participer au tour de france";
						echo json_encode($retour);
						exit(); 
					}
				}
				// Dans le cas ou il ne rentre que l'année de naissance en plus des champs obligatoires
				else if(isset($_POST['annee_naissance']) && !empty($_POST['annee_naissance'])) {
					$reqCoureur = "insert into tdf_coureur (NOM,PRENOM,CODE_TDF,N_COUREUR,ANNEE_NAISSANCE,COMPTE_ORACLE,DATE_INSERT) values ('".$nom."','".$prenom."','".$_POST['code_tdf']."',(select max(n_coureur)+1 from tdf_coureur),'".$_POST['annee_naissance']."','ETU2_64','".date("d-m-y")."')";
					$retour = array(str_replace("''", "'", $nom), str_replace("''", "'", $prenom), $_POST['code_tdf'], $_POST['annee_naissance'], "");
				}
				
				// Dans le cas ou il ne rentre que l'année du premier tour en plus des champs obligatoires
				else if(isset($_POST['annee_premiere']) && !empty($_POST['annee_premiere'])) {
					$reqCoureur = "insert into tdf_coureur (NOM,PRENOM,CODE_TDF,N_COUREUR,ANNEE_PREM,COMPTE_ORACLE,DATE_INSERT) values ('".$nom."','".$prenom."','".$_POST['code_tdf']."',(select max(n_coureur)+1 from tdf_coureur),'".$_POST['annee_premiere']."','ETU2_64','".date("d-m-y")."')";
					$retour = array(str_replace("''", "'", $nom), str_replace("''", "'", $prenom), $_POST['code_tdf'], "", $_POST['annee_premiere']);
				
				} else { // Dans le cas ou il ne rentre que les champs obligatoires
					$reqCoureur = "insert into tdf_coureur (NOM,PRENOM,CODE_TDF,N_COUREUR,COMPTE_ORACLE,DATE_INSERT) values ('".$nom."','".$prenom."','".$_POST['code_tdf']."',(select max(n_coureur)+1 from tdf_coureur),'ETU2_64','".date("d-m-y")."')";
					$retour = array(str_replace("''", "'", $nom), str_replace("''", "'", $prenom), $_POST['code_tdf'], "", "");
				}

				$curCoureur = PreparerRequete($conn,$reqCoureur);
				$resCoureur = ExecuterRequete($curCoureur);

				$reqCoureur = "COMMIT";
				$curCoureur = PreparerRequete($conn,$reqCoureur);
				$resCoureur = ExecuterRequete($curCoureur);
				FermerConnexion($conn);
			} else {
			$retour = "Nom ou prenom invalide."; // nom ou prénom invalide
			}
		} else {
		$retour = "Vous devez entrer un nom"; // pas de nom
		}    
	} else {
	$retour = "Vous devez entrer un prenom"; // pas de prénom
	}
	echo json_encode($retour);
?>