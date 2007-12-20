<?php 
/*Copyright Ville de Meaux 2004-2007
contributeur: jean-luc Dechamp - robert Leguay 
sig@meaux.fr

Ce logiciel est un programme informatique fournissant une interface cartographique WEB communale. 

Ce logiciel est régi par la licence CeCILL-C soumise au droit français et
respectant les principes de diffusion des logiciels libres. Vous pouvez
utiliser, modifier et/ou redistribuer ce programme sous les conditions
de la licence CeCILL-C telle que diffusée par le CEA, le CNRS et l'INRIA 
sur le site "http://www.cecill.info".

En contrepartie de l'accessibilité au code source et des droits de copie,
de modification et de redistribution accordés par cette licence, il n'est
offert aux utilisateurs qu'une garantie limitée.  Pour les mêmes raisons,
seule une responsabilité restreinte pèse sur l'auteur du programme,  le
titulaire des droits patrimoniaux et les concédants successifs.

A cet égard  l'attention de l'utilisateur est attirée sur les risques
associés au chargement,  à l'utilisation,  à la modification et/ou au
développement et à la reproduction du logiciel par l'utilisateur étant 
donné sa spécificité de logiciel libre, qui peut le rendre complexe à 
manipuler et qui le réserve donc à des développeurs et des professionnels
avertis possédant  des connaissances  informatiques approfondies.  Les
utilisateurs sont donc invités à charger  et  tester  l'adéquation  du
logiciel à leurs besoins dans des conditions permettant d'assurer la
sécurité de leurs systèmes et ou de leurs données et, plus généralement, 
à l'utiliser et l'exploiter dans les mêmes conditions de sécurité. 

Le fait que vous puissiez accéder à cet en-tête signifie que vous avez 
pris connaissance de la licence CeCILL-C, et que vous en avez accepté les 
termes.*/
include("../connexion/deb.php");
if ($_GET["util"]){
 	$q2="select idapplication from admin_svg.application";
	$r2=pg_exec($pgx,$q2);
	$num=pg_numrows($r2);
	$ins="delete from admin_svg.apputi where idutilisateur='".$_GET["util"]."'";
	$aa=pg_exec($pgx,$ins);$j=1;
	for ($i=0; $i<$num; $i++){
		$tes2 = pg_fetch_array($r2);
		if (array_key_exists("'".$tes2["idapplication"]."'",$_GET["app"])){
			$in2="insert into admin_svg.apputi (idutilisateur,idapplication,ordre) values('".$_GET["util"]."','".$tes2["idapplication"]."','".$j."')";
			$a2=pg_exec($pgx,$in2); $j++;
		}
	} 
	$ident=$_GET["util"]; 
}else{$ident=$_GET["ident"];}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Document sans titre</title>
</head>

<body>
<form name="f1" action="application.php" method="get">
<input name="util" type="hidden" value="<?php echo $ident ?>">
<table>
<?php 
$quer="select * from admin_svg.application where idapplication in (select idapplication from admin_svg.apputi where idutilisateur='".$ident."')";
$que2="select * from admin_svg.application where idapplication not in (select idapplication from admin_svg.apputi where idutilisateur='".$ident."')";
$tab=pg_exec($pgx,$quer);
$ta2=pg_exec($pgx,$que2);
$num2=pg_numrows($tab);
$num3=pg_numrows($ta2);
for ($i=0; $i<$num2; $i++){
	$ff[$i]=pg_fetch_array($tab, $i);
   echo '<tr><td><input name="app[\''.$ff[$i]['idapplication'].'\']" type="checkbox" value="0" onchange="if(this.value==\'0\'){this.value=\'1\';}else{this.value=\'0\';}" checked></td><td>'.$ff[$i]['libelle_appli'].'</td><td>'.$ff[$i]['url'].'</td><td>'.$ff[$i]['divers'].'</td></tr>'; 
 }
 for ($i=0; $i<$num3; $i++){
	$ff[$i]=pg_fetch_array($ta2, $i);
   echo '<tr><td><input name="app[\''.$ff[$i]['idapplication'].'\']" type="checkbox" value="1" onchange="if(this.value==\'0\'){this.value=\'1\';}else{this.value=\'0\';}"></td><td>'.$ff[$i]['libelle_appli'].'</td><td>'.$ff[$i]['url'].'</td><td>'.$ff[$i]['divers'].'</td></tr>'; 
 }
 ?>
</table>
<input name="btn_valid" type="button" onClick="document.f1.submit()" value="Enregistrer">
<input name="btn_annul" type="button" onClick="window.close()" value="Annuler">
</form>
</body>
</html>
