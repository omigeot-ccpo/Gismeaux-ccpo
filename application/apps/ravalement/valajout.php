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
define('GIS_ROOT', '../..');
include_once(GIS_ROOT . '/inc/common.php');
gis_session_start();

$insee = $_SESSION['profil']->insee;
$appli = $_SESSION['profil']->appli;

if ((!$_SESSION['profil']->acces_ssl) || !in_array ("Ravalement", $_SESSION['profil']->liste_appli)){
	die("Point d'entrée réglementé.<br> Accès interdit. <br>Veuillez vous connecter via <a href=\"https://".$_SERVER['HTTP_HOST']."\">serveur carto</a><SCRIPT language=javascript>setTimeout(\"window.location.replace('https://".$_SERVER['HTTP_HOST']."')\",5000)</SCRIPT>");
}
ini_set("memory_limit" , "24M");
set_time_limit(0);
$sql="insert into urba.ravalement (date,observation,etat,the_geom,surveiller,annee_dernier_ravalement) values ('".$_POST['date']."','".$_POST['observation']."','".$_POST['type']."','".$_POST['polygo']."','".$_POST['surveiller']."','".$_POST['date_raval']."')";
$DB->tab_result($sql);
$requete="select last_value from urba.ravalement_gid_seq";
$col=$DB->tab_result($requete);
$id_ravalement=$col[0]['last_value'];
$requete="select last_value from urba.photo_ravalement_id_photo_seq";
$col=$DB->tab_result($requete);
$lastvaleur=$col[0]['last_value'];
    for($i=0;$i<5;$i++)
	{
		if( is_uploaded_file($_FILES['photo']['tmp_name'][$i]) )
    	{
		$lastvaleur=$lastvaleur+1;
     	$sql="insert into urba.photo_ravalement (id_ravalement) values (".$id_ravalement.")";
		$DB->tab_result($sql);
		$extention=explode(".",$_FILES['photo']['name'][$i]);
		move_uploaded_file($_FILES['photo']['tmp_name'][$i], "./photo/".$lastvaleur.".".$extention[1]);

		$tableau = GetImageSize("./photo/".$lastvaleur.".".$extention[1]);
		$width = 400;
		$height = 400;
		$width_vignette = 100;
		$height_vignette = 100;
		$ratio_orig = $tableau[0]/$tableau[1];

		if ($width_vignette/$height_vignette > $ratio_orig) {
   		$width_vignette = $height_vignette*$ratio_orig;
		} else {
   		$height_vignette = $width_vignette/$ratio_orig;
		}
		if ($width/$height > $ratio_orig) {
   		$width = $height*$ratio_orig;
		} else {
   		$height = $width/$ratio_orig;
		}
		$image_p = imagecreatetruecolor($width, $height);
		$image_p_vignette = imagecreatetruecolor($width_vignette, $height_vignette);
		if($extention[1]=="jpg" ||$extention[1]=="JPG" )
		{
		$image = imagecreatefromjpeg("./photo/".$lastvaleur.".".$extention[1]);
		}
		if($extention[1]=="png" || $extention[1]=="PNG")
		{
		$image = imagecreatefrompng("./photo/".$lastvaleur.".".$extention[1]);
		}
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $tableau[0], $tableau[1]);
		imagejpeg($image_p,"./photo/".$lastvaleur.".JPG", 100);
		imagecopyresampled($image_p_vignette, $image, 0, 0, 0, 0, $width_vignette, $height_vignette, $tableau[0], $tableau[1]);
		imagejpeg($image_p_vignette,"./photo/vignette/".$lastvaleur.".JPG", 100);
		if($extention[1]=="png")
		{
		unlink("./photo/".$lastvaleur.".png");
		}
		if($extention[1]=="jpg")
		{
		unlink("./photo/".$lastvaleur.".jpg");
		}

    	}
		else {break;}
}
?>
<html>
<body onLoad="close()">
</body>
</html>

