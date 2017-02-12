<?php
// E.Porcq  fonc_oracle.php  12/10/2009 

//---------------------------------------------------------------------------------------------
function OuvrirConnexion($session,$mdp,$instance)
{
  $conn = oci_connect($session, $mdp,$instance,"AL32UTF8"); // "AL32UTF8" ou "WE8ISO8859P15"
  if (!$conn) //si pas de connexion retourne une erreur
  {  
  $e = oci_error();
  exit;
  }
  return $conn;
}

function PreparerRequete($conn,$req)
{
  $cur = oci_parse($conn, $req);
  
  if (!$cur) 
  {  
  $e = oci_error($conn);  
  print htmlentities($e['message']);  
  exit;
  }
  return $cur;
}
//---------------------------------------------------------------------------------------------
function ExecuterRequete($cur)
{
  $r = oci_execute($cur, OCI_DEFAULT);
  //echo "<br>résultat de la requête: $r<br />";
  if (!$r) 
  {  
  $e = oci_error();  
  echo htmlentities($e['message']);  
  exit;
  }
  return $r;
}
  

//---------------------------------------------------------------------------------------------
function FermerConnexion($conn)
{
  oci_close($conn);
}
//---------------------------------------------------------------------------------------------
function LireDonnees1($cur,&$tab)
{
  $nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
  return $nbLignes;
}

function AfficherDonnee1($tab,$nbLignes)
{
  if ($nbLignes > 0) 
  {
    echo "<table border=\"1\">\n";
    echo "<tr>\n";
    foreach ($tab as $key => $val)  // lecture des noms de colonnes
    {
      echo "<th>$key</th>\n";
    }
    echo "</tr>\n";
    for ($i = 0; $i < $nbLignes; $i++) // balayage de toutes les lignes
    {
      echo "<tr>\n";
      foreach ($tab as $data) // lecture des enregistrements de chaque colonne
    {
        echo "<td>$data[$i]</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</table>\n";
  } 
  else 
  {
    echo "Pas de ligne<br />\n";
  } 
  echo "$nbLignes Lignes lues<br />\n";
}
?>



