<?php
	require "fonc_oracle.php";
	require "regexNomCoureur.php";
	// On va modifier un coureur
	if(isset($_POST['ncoureur']) && !empty($_POST['ncoureur'])) $ncoureur = $_POST['ncoureur'];
	if(isset($_POST['nom']) && !empty($_POST['nom'])) $nom = $_POST['nom'];  
	if(isset($_POST['prenom']) && !empty($_POST['prenom'])) $prenom = $_POST['prenom'];
	if(isset($_POST['ddn'])) $ddn = $_POST['ddn'];
	if(isset($_POST['codetdf']) && !empty($_POST['codetdf'])) $codetdf = $_POST['codetdf'];
	if(isset($_POST['premiertdf'])) $premiertdf = $_POST['premiertdf'];
	if(isset($ncoureur) && isset($nom) && isset($prenom) && isset($ddn) && isset($codetdf) && isset($premiertdf)) {
		$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
		$reqCodeTdf = 'SELECT code_tdf, nom FROM tdf_pays order by code_tdf';
		$curCodeTdf = PreparerRequete($conn, $reqCodeTdf);
		$resCodeTdf = ExecuterRequete($curCodeTdf);
		$nbLignesCodeTdf = LireDonnees1($curCodeTdf, $tabCodeTdf);
		# Test de validité des champs
		$nom = strVerificationNom($nom);
		$prenom = strVerificationPrenom($prenom);
		if($nom === "Interdit") {
			echo "Nom interdit";
			exit();
		}
		if($prenom === "Interdit") {
			echo "Prénom interdit";
			exit();
		}

		if(!preg_match("#\d{4}#", $ddn) && $ddn != "") {
			echo "Date de naissance interdite";
			exit();
		}

		if(!preg_match("#\d{4}#", $premiertdf) && $premiertdf != "") {
			echo "Date du premier tour de france interdite";
			exit();
		}  

		if($ddn == "NULL") $ddn ="";
		if($premiertdf == "NULL" ) $premiertdf="";
		
		// test codetdf
		$codeTdfExiste = false;
		for($i = 0 ; $i<$nbLignesCodeTdf ; $i++) {
			if($tabCodeTdf[$i]["CODE_TDF"] == $codetdf) {
				$codeTdfExiste = true;
		    }
		}
		
		if(!$codeTdfExiste) {
			echo "Code TDF invalide";
			exit();
		}

		if(preg_match("#\d{4}#", $premiertdf) && preg_match("#\d{4}#", $ddn)) {
			if($premiertdf - $ddn < 18) {
				echo "Le coureur ne peut pas être mineur.";
				exit();
			}
		}
		# Fin des tests !

		// Si on est ici tout est valide et on peut ajouter dans la base
		$nom = str_replace("'", "''", $nom); // On échappe les apostrophe pour la requete
		$prenom = str_replace("'", "''", $prenom);
		
		$reqModifierCoureur = "UPDATE tdf_coureur SET annee_naissance ='" . $ddn . "', annee_prem = '" . $premiertdf . "', nom = '" . $nom . "', prenom = '" . $prenom . "', code_tdf = '" . $codetdf . "' WHERE n_coureur = " . $ncoureur;
		$curModifierCoureur = PreparerRequete($conn, $reqModifierCoureur);
		$resModifierCoureur = ExecuterRequete($curModifierCoureur);

		$reqCommit = "COMMIT";
		$curCommit = PreparerRequete($conn, $reqCommit);
		ExecuterRequete($curCommit);
		FermerConnexion($conn);
		echo "ok";
	} else {
	echo "Un des champs obligatoires n'a pas été renseigné";
	}
?>
