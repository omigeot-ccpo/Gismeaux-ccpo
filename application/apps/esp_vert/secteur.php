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

if ((!$_SESSION['profil']->acces_ssl) || !in_array ("espVerts", $_SESSION['profil']->liste_appli)){
	die("Point d'entrée réglementé.<br> Accès interdit. <br>Veuillez vous connecter via <a href=\"https://".$_SERVER['HTTP_HOST']."\">serveur carto</a><SCRIPT language=javascript>setTimeout(\"window.location.replace('https://".$_SERVER['HTTP_HOST']."')\",5000)</SCRIPT>");
}
	$q="select *,round(area(the_geom)) as surf from esvert.sector where gid=".$_GET["obj_keys"];
	$r_sect=$DB->tab_result($q);
	$q1="select count(*) as nbre_arbre from esvert.arbre where distance(geopt,(select the_geom from esvert.sector where gid=".$_GET["obj_keys"]."))=0";
	$r_arbre=$DB->tab_result($q1);
	$q2="select round(sum(area(the_geom))) as surf_pelouse from esvert.pelouse where distance(the_geom,(select the_geom from esvert.sector where gid=".$_GET["obj_keys"]."))=0";
	$r_pelouse=$DB->tab_result($q2);
	$q3="select round(sum(area(the_geom))) as surf_pelouse,arrosage from esvert.pelouse where distance(the_geom,(select the_geom from esvert.sector where gid=".$_GET["obj_keys"]."))=0 group by arrosage";
	$r_arrosage=$DB->tab_result($q3);
	echo "<div style=\"text-align: center; font-weight: bold;\">Secteur ".$r_sect[0]["libel"]."</div><br>";
	echo "Superficie : ".$r_sect[0]["surf"]." m&sup2;<br>";
	echo $r_arbre[0]["nbre_arbre"]." arbres dans le secteur<br>";
	echo $r_pelouse[0]["surf_pelouse"]." m&sup2; de pelouse<br>";
	for ($u=0;$u<count($r_arrosage);$u++){
		if ($r_arrosage[$u]["arrosage"]=='U'){
			$arro="sans arrosage.";
		}elseif ($r_arrosage[$u]["arrosage"]=='A'){
			$arro="arrosage automatique.";
		}elseif ($r_arrosage[$u]["arrosage"]=='M'){
			$arro="arrosage manuel.";
		}else{
			$arro="arrosage non d�fini";
		}
		echo " - ".$r_arrosage[$u]["surf_pelouse"]." m&sup2; de pelouse ".$arro."<br>";
	}
?>