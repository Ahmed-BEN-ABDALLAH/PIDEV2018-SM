<?php 

$serveur="localhost";
$util="root";
$motpasse="" ;
$base="kids";

$conx=mysqli_connect($serveur, $util, $motpasse,$base);
if (!$conx) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$url=$_GET["image"];
$type=$_GET["Type"];


$req="update  medecin_specialiste set image='$url' WHERE id='$type';";

$etat=mysqli_query($conx,$req);

 
 
if (!$etat ) {
  die ("NOK erreur dinsertion:" . mysqli_error($conx));
}

echo "OK" ;



?>
