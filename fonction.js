/* ******************************************************************************************** */
// Les fonctons SelectX permettent de changer la classe d'une ligne pour la selectionné
// Elle permettent aussi de flancer des actions en conséquences (remplir un autre tableau etc.)

// Gestion des clic sur les lignes du tableau qui liste les coureurs
ObjSelec = null;
function SelectLigne(obj) {
	var idLigne=obj.id;
	obj.className="selection"; // Va apparaitre selectionné
	if (ObjSelec!=null) {
		ObjSelec.className = "defaut"; // Va apparaitre non selec
		ObjSelec = obj;
	} else {
		ObjSelec = obj;
	}
	viderTableauEpreuves();
	remplirTableauParticipation(idLigne);
}

// Gestion des clic sur les lignes du tableau participation dans Coureur
ObjSelec2 = null;
function SelectLigne2(obj) {
	var idLigne2=obj.id;
	obj.className="selection";
	if (ObjSelec2!=null) {
		ObjSelec2.className = "defaut";
		ObjSelec2 = obj;
	} else {
		ObjSelec2 = obj;
	}
	remplirTableauEpreuves(idLigne2);
}

// Gestion des clic sur les lignes du tableau listant les sponsors
ObjSelec3 = null;
function SelectSponsor(obj) {
	var idLigneSponsor=obj.id;
	obj.className="selection";
	if (ObjSelec3!=null) {
		ObjSelec3.className = "defaut";
		ObjSelec3 = obj;
	} else {
		ObjSelec3 = obj;
	}
	remplirTableauParticipationsSponsors(idLigneSponsor);
}

// Gestion des clic sur les lignes du tableau listant les tours
ObjSelec4 = null;
function SelectTdf(obj) {
	var idLigneTdf=obj.id;
	obj.className="selection";
	if (ObjSelec4!=null) {
		ObjSelec4.className = "defaut";
		ObjSelec4 = obj;
	} else {
		ObjSelec4 = obj;
	}
	remplirTableauClassementEpreuveTdf(idLigneTdf);
}

/* ******************************************************************************************** */

function viderTableauEpreuves() {
	$(function() {
		table = $('#tableauEpreuves').DataTable();
		table.clear().draw();  
	});
}

// Fonction qui va lancer un traitement Php via Ajax, récuperer le résultat et l'afficher dans le tableau
function remplirTableauClassementEpreuveTdf(idLigne) {
	rowEpreuveTdf = document.getElementById(idLigne);
	// On recupere les éléments
	nepreuve = rowEpreuveTdf.cells[0].innerHTML;
	annee = rowEpreuveTdf.cells[1].innerHTML;
	// On va récuperer les épreuves via AJAX qui va lancer un traitement PHP puis on ajoute dans le tableau
	$.ajax({
		url : 'traitementPhp/getEpreuvesTdf.php', // On lance ce traitement PHP
		type : 'GET',
		data : 'nepreuve=' + nepreuve + "&annee=" + annee,
		dataType : 'json',
		success : function(data){
			table = $('#tableauEpreuvesTourClassement').DataTable();
			table.clear().draw();
			// On récupère les champs qui nous intéresse dans le json que php nous renvoie et on affiche dans table
			$.each(data, function( key, value ) {
				$.each(value, function( key2, value2) {
					switch(key2) {
						case "NOM": nom = value2; break;
						case "PRENOM": prenom = value2; break;
						case "TOTAL_SECONDE": total_seconde = value2; break;
						case "RANG_ARRIVEE": rang = value2; break;
					}
				});
				table.row.add([nom, prenom, new Date(total_seconde * 1000).toISOString().substr(11, 8), rang]).draw(); // On écrit dans le tableau
				nom= prenom= total_seconde= rang= "";
			});
		},
		error : function(data){
			alert("Erreur AJAX 1" + JSON.stringify(data));
		}
	});   
}

// Fonction qui va lancer un traitement Php via Ajax, récuperer le résultat et l'afficher dans le tableau
function remplirTableauParticipationsSponsors(idLigne) {
	rowSponsor = document.getElementById(idLigne);
	nequipe = rowSponsor.cells[0].innerHTML;
	nsponsor= rowSponsor.cells[1].innerHTML;
	$.ajax({
		url : 'traitementPhp/getParticipationSponsor.php',
		type : 'GET',
		data : 'nequipe=' + nequipe + "&nsponsor=" + nsponsor,
		dataType : 'json',
		success : function(data){
			table = $('#tableauParticipationSponsor').DataTable();
			table.clear().draw();
			// On récupère les champs qui nous intéresse dans le json que php nous renvoie et on affiche dans table
			$.each(data, function( key, value ) {
				$.each(value, function( key2, value2) {
					switch(key2) {
						case "ANNEE": annee = value2; break;
						case "NOM_DIR": nom_dir = value2; break;
						case "PRENOM_DIR": prenom_dir = value2; break;
						case "NOM_CO": nom_co = value2; break;
						case "PRENOM_CO": prenom_co = value2; break;
						case "NUMERO_ORDRE": numero_ordre = value2; break;  
						case "SCORE": score = value2; break; 
					}
				});
				table.row.add([annee, nom_dir, prenom_dir, nom_co, prenom_co, numero_ordre, score]).draw();
				annee = nom_dir = prenom_dir = nom_co = prenom_co = numero_ordre = score= "";
			});
		},
		error : function(data){
			alert("Erreur AJAX 1" + JSON.stringify(data));
		}
	});  
}

// Fonction qui va lancer un traitement Php via Ajax, récuperer le résultat et l'afficher dans le tableau
function ajouterCoureur() {
	allFields.removeClass( "ui-state-error" );
	nom = $( "#nomAjoutCoureur" ),
	prenom = $( "#prenomAjoutCoureur" ),
	ddn = $( "#ddnAjoutCoureur" ),
	dpt = $( "#dptAjoutCoureur" ),
	codePays = $( "#codePaysAjout" );

	$.ajax({
		url : 'traitementPhp/actionCoureur.php',
		type : 'POST',
		data : 'nom=' + nom.val() + '&prenom=' + prenom.val() + '&code_tdf=' + codePays.val() + '&annee_naissance=' + ddn.val() + '&annee_premiere=' + dpt.val(),
		dataType : 'json',
		success : function(data){
			if(data.length > 5) { // Si tableau alors il y a 5 élement, sinon c'est des msg avec plus que 5 caractères
				$("#pDialogErreurAjoutCoureur").text("Erreur lors l'ajout : " + JSON.stringify(data));
				$("#dialogErreurAjoutCoureur").dialog();                    
			} else {
				nom = prenom = code_tdf = naissance = premier_tdf = "";
				// On récupère les champs qui nous intéresse dans le json que php nous renvoie et on affiche dans table
				$.each(data, function(i, item) {
					switch(i) {
						case 0: nom = item; break;
						case 1: prenom = item; break;
						case 2: code_tdf = item; break;
						case 3: naissance = item; break;
						case 4: premier_tdf = item; break;
					}
				});
				table = $('#tableauCoureur').DataTable();
				ncoureur = table.column(0).data().sort().reverse()[0] + 1; // N_coureur max + 1, ça rechargera mieux prochaine fois
				table.row.add([ncoureur, nom, prenom, naissance, code_tdf, premier_tdf]).draw();
				$("#pDialogCourreurAjoute").text("Ajout d'un coureur : " + nom + " " + prenom + " " + code_tdf + " " + naissance + " " + premier_tdf);
				$("#dialogCourreurAjoute").dialog();
			}
		},
		error : function(xhr, ajaxOptions, thrownError){
			// Affichage des messages d'erreurs
			if(xhr.responseText.indexOf("ORA-00001") >= 0) { // Si contient
				$("#pDialogErreurAjoutCoureur").text("Erreur lors l'ajout : coureur déjà existant !");
				$("#dialogErreurAjoutCoureur").dialog();
			} else if (xhr.responseText.indexOf("ORA-12899") >= 0) {
				$("#pDialogErreurAjoutCoureur").text("Coureur non ajouté : nom ou prénom trop long !");
				$("#dialogErreurAjoutCoureur").dialog();
			} else if (xhr.responseText.indexOf("ORA-00917") >= 0) {
				$("#pDialogErreurAjoutCoureur").text("Coureur non ajouté : problème avec les escapes de quote !");
				$("#dialogErreurAjoutCoureur").dialog();                   
			} else {
				$("#pDialogErreurAjoutCoureur").text("Erreur lors l'ajout : " + xhr.responseText);
				$("#dialogErreurAjoutCoureur").dialog();
			}
		}
	});	
}

// Fonction qui va lancer un traitement Php via Ajax, récuperer le résultat et l'afficher dans le tableau
function remplirTableauParticipation(idLigne) {
	rowCoureur = document.getElementById(idLigne);
	ncoureur = rowCoureur.cells[0].innerHTML;
	$.ajax({
		url : 'traitementPhp/getParticipation.php',
		type : 'GET',
		data : 'ncoureur=' + ncoureur,
		dataType : 'json',
		success : function(data){
			table = $('#tableauParticipation').DataTable();
			table.clear().draw();
			// On récupère les champs qui nous intéresse dans le json que php nous renvoie et on affiche dans table
			$.each(data, function( key, value ) {
				$.each(value, function( key2, value2) {
					switch(key2) {
						case "N_COUREUR": ncoureur = value2; break;
						case "ANNEE": annee = value2; break;
						case "NOM": nom = value2; break;
						case "N_DOSSARD": n_dossard = value2; break;  
					}
				});
				table.row.add([ncoureur, annee, nom, n_dossard, "0"]).draw();
				ncoureur = annee = nom = n_dossard = "";
			});
		},
		error : function(data){
			alert("Erreur AJAX 2");
		}
	});
}

// Fonction qui va lancer un traitement Php via Ajax, récuperer le résultat et l'afficher dans le tableau
function remplirTableauEpreuves(idLigne) {
	ligne = document.getElementById(idLigne);
	ncoureur = ligne.cells[0].innerHTML;
	annee = ligne.cells[1].innerHTML;
	$.ajax({
		url : 'traitementPhp/getEpreuves.php',
		type : 'GET',
		data : 'ncoureur=' + ncoureur + '&annee=' + annee,
		dataType : 'json',
		success : function(data){
			table = $('#tableauEpreuves').DataTable();
			table.clear().draw();
			// On récupère les champs qui nous intéresse dans le json que php nous renvoie et on affiche dans table
			$.each(data, function( key, value ) {
				$.each(value, function( key2, value2) {
					switch(key2.toUpperCase()) {
						case "N_EPREUVE": n_epreuve = value2; break;
						case "VILLE_A": ville_a = value2; break;
						case "VILLE_D": ville_d = value2; break;  
						case "DISTANCE": distance = value2; break;  
						case "MOYENNE": moyenne = value2; break;
						case "JOUR": jour = value2; break;
						case "CAT_CODE": cat_code = value2; break;  
						case "TOTAL_SECONDE": total = value2; break;  
						case "RANG_ARRIVEE": rang = value2; break;  
					}
				});
				table.row.add([n_epreuve, ville_d, ville_a, distance, moyenne, jour, cat_code, new Date(total * 1000).toISOString().substr(11, 8), rang]).draw();
				n_epreuve = ville_d = ville_a = distance = moyenne = jour = cat_code = total = rang = "";
			});
		},
		error : function(data){
			alert("Erreur AJAX 3");
		}
	});
}

// Fonction qui va lancer un traitement Php via Ajax, récuperer le résultat et l'afficher dans le tableau
function changerValeurCoureur(ncoureur, nom, prenom, ddn, codePays, datePremierTour) {
	// On va envoyer les données au PHP via AJAX, on aura une réponse positive ou négative avec une précision de pourquoi ça à annuler l'action.

	if(ddn.length == 0)
		ddn = "";

	if(datePremierTour.length == 0)
		datePremierTour = "";

	$.ajax({
		url : 'traitementPhp/modifierCoureur.php',
		type : 'POST',
		data : 'ncoureur=' + ncoureur + '&nom=' + nom + '&prenom=' + prenom + '&ddn=' + ddn + '&codetdf=' + codePays + '&premiertdf=' + datePremierTour,
		dataType : 'text',
		success : function(data){
			if(data.indexOf("ok") >= 0) { // Si ok dans le retour
				$("#pDialogCoureurModif").text("Coureur modifié !");
				$("#dialogCoureurModif").dialog();
				row = document.getElementById(objId); // Colonne selectionné
				row.cells[0].innerHTML = ncoureur;
				row.cells[1].innerHTML = nom;
				row.cells[2].innerHTML = prenom;
				row.cells[3].innerHTML = ddn;
				row.cells[4].innerHTML = codePays;
				row.cells[5].innerHTML = datePremierTour;
			} else {
				if(data.indexOf("ORA-00001") >= 0) { // Si contient
					$("#pDialogErreurAjoutCoureur").text("Erreur lors l'ajout : coureur déjà existant !");
					$("#dialogErreurAjoutCoureur").dialog();
				} else if (data.indexOf("ORA-12899") >= 0) {
					$("#pDialogErreurAjoutCoureur").text("Coureur non modifié : nom ou prénom trop long !");
					$("#dialogErreurAjoutCoureur").dialog();
				} else {
					$("#pDialogErreurAjoutCoureur").text("Erreur lors l'ajout : " + data);
					$("#dialogErreurAjoutCoureur").dialog();
				}     
			}
		},
		error : function(data){
			if(data.indexOf("ORA-00001") >= 0) { // Si contient
				$("#pDialogErreurAjoutCoureur").text("Erreur lors l'ajout : coureur déjà existant !");
				$("#dialogErreurAjoutCoureur").dialog();
			} else if (data.indexOf("ORA-12899") >= 0) {
				$("#pDialogErreurAjoutCoureur").text("Coureur non modifié : nom ou prénom trop long !");
				$("#dialogErreurAjoutCoureur").dialog();
			} else {
				$("#pDialogErreurAjoutCoureur").text("Erreur lors l'ajout : " + data);
				$("#dialogErreurAjoutCoureur").dialog();
			}
		}
	});  
}

// Fonction qui va pré-remplir le formulaire de modification de coureur
function loadFormModification() {
	if(ObjSelec != null) {
		objId = ObjSelec.id;
		row = document.getElementById(objId);
		document.getElementById("ncoureur").value = row.cells[0].innerHTML;
		document.getElementById("nom").value = row.cells[1].innerHTML;
		document.getElementById("prenom").value = row.cells[2].innerHTML;
		document.getElementById("ddn").value = row.cells[3].innerHTML;
		options = document.getElementById("codePays").options;
		for (i=0; i<options.length; i++) {
			if (options[i].value==row.cells[4].innerHTML) {
				options[i].selected= true;
				break;
			}
		} 
		document.getElementById("datePremierTdf").value = row.cells[5].innerHTML;
	} else {
		$( function() {
			$( "#dialogRienSelec" ).dialog();
		} );
	}
}

// Suppresion de "row" et affichage d'un message
function removeHtml(data, row) {
	if(data == 1) {
		row.parentNode.removeChild(row); 
		$( function() {
			$( "#dialogCoureurSuppr1" ).dialog();
		} );
	} else {
		$( function() {
			$( "#dialogCoureurSuppr2" ).dialog();
		} );
	}
}

// Fonction qui va lancer un traitement Php via Ajax, si la réponse de PHP est positive on le supprime du tableau sinon on affiche une erreur
function supprimerSelectionCoureur() {
	if (ObjSelec != null) {
		objId = ObjSelec.id;
		row = document.getElementById(objId);
		if(row != null) {
			// Suppression sur la BDD
			$.ajax({
				url : 'traitementPhp/supprimerCoureur.php',
				type : 'GET',
				data : 'ncoureur=' + row.childNodes[0].innerHTML,
				dataType : 'text',
				success : function(data){     
					removeHtml(data, row);
				},
				error : function(data){
					alert("Erreur AJAX 1");
				}
			});
		} else {
			$( function() {
				$( "#dialogRienSelec" ).dialog();
			} );      
		}
	} else {
		$( function() {
			$( "#dialogRienSelec" ).dialog();
		} );
	}  
}

// Fonction qui va lancer un traitement Php via Ajax, récuperer le résultat et l'afficher dans le tableau
function chargementTour(){
	var element = document.getElementById("listeTour");
	var valeurTour = element.options[element.selectedIndex].value;
	$.ajax({
		url : 'traitementPhp/tdfChargement.php',
		type : 'GET',
		data : 'valeurTour=' + valeurTour,
		dataType : 'json',
		success : function(data){       
			$.each(data, function( key, value ) {
				$.each(value, function( key2, value2) {
					switch(key2.toUpperCase()) {
						case "ANNEE": annee = value2; break;
						case "N_EPREUVE": n_epreuve = value2; break;
						case "VILLE_D": ville_d = value2; break;  
						case "VILLE_A": ville_a = value2; break;  
						case "DISTANCE": distance = value2; break;
						case "MOYENNE": moyenne = value2; break;
						case "JOUR": jour = value2; break;  
						case "CAT_CODE": cat_code = value2; break;
					}
				});
				table = $('#tableauEpreuves2').DataTable();
				table.row.add([n_epreuve, annee, ville_d, ville_a, distance, moyenne, jour, cat_code]).draw();
				n_epreuve = annee = ville_d = ville_a = distance = moyenne = jour = cat_code = "";
			});
		},
		error : function(data){
			alert("Erreur AJAX4");
		}
	});
	$.ajax({
		url : 'traitementPhp/getClassement.php',
		type : 'GET',
		data : 'annee=' + valeurTour,
		dataType : 'json',
		success : function(data){       
			$.each(data, function( key, value ) {
				$.each(value, function( key2, value2) {
					switch(key2.toUpperCase()) {
						case "NOM": nom = value2; break;
						case "PRENOM": prenom = value2; break;
						case "CLASSEMENT": classement = value2; break;  
						case "TEMPS_TOTAL": temps = value2; break;  
					}
				});
				table = $('#tableauClassementGeneral').DataTable();
				table.row.add([nom, prenom, classement, secondsToString(temps)]).draw();
				nom = prenom = classement = temps = "";
			});
		},
		error : function(data){
			alert("Erreur AJAX4");
		}
	});
}

// Convertit des secondes en jour/heures/minutes/secondes en string
function secondsToString(seconds) {
	var numdays = Math.floor(seconds / 86400);
	var numhours = Math.floor((seconds % 86400) / 3600);
	var numminutes = Math.floor(((seconds % 86400) % 3600) / 60);
	var numseconds = ((seconds % 86400) % 3600) % 60;
	return numdays + "j " + numhours + "h " + numminutes + "m " + numseconds + "s";
}