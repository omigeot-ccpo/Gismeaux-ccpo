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
if (isset($_POST['polygo'])){$polygo=$_POST['polygo'];}else{$polygo=$_GET['polygo'];}	
	if ($_POST['secteur']=='1'){
		$q="insert into esvert.sector (libel,the_geom,creepar,creele) values('".$_POST['libel']."',GeometryFromtext('MULTIPOLYGON(((".$polygo.")))',$projection),'".$_SERVER['PHP_AUTH_USER']."',current_date)";
		$r=$DB->tab_result($q);
	}
	if ($_POST['pelouse']=='1'){
		$q="insert into esvert.pelouse (arrosage,the_geom,creepar,creele) values('".$_POST['arrosage']."',GeometryFromtext('MULTIPOLYGON(((".$polygo.")))',$projection),'".$_SERVER['PHP_AUTH_USER']."',current_date)";
		$r=$DB->tab_result($q);
	}
	if ($_POST['fleurie']=='1'){
		$q="insert into esvert.fleurie (arrosage,the_geom,date_pose,type_plant,creepar,creele) values('".$_POST['arrosage']."',GeometryFromtext('MULTIPOLYGON(((".$polygo.")))',$projection),".datedmy2sql($_POST['date_pose']).",'".$_POST['type_plant']."','".$_SERVER['PHP_AUTH_USER']."',current_date)";
		$r=$DB->tab_result($q);
	}
	
	echo "<head><script LANGUAGE=\"JavaScript\">function suite(visib,cach){
	if (visib != ''){
		lchen=0;
		lchen1=visib.indexOf(\";\",lchen);
		while (chen=visib.substring(lchen,lchen1)) {
			var vid = document.getElementById(chen);
			vid.style.visibility='visible';
			lchen=lchen1+1;
			lchen1=visib.indexOf(\";\",lchen);
			if (lchen1==-1) break
		}
	}
	if (cach != ''){
		lchen=0;
		lchen1=cach.indexOf(\";\",lchen);
		while (chen=cach.substring(lchen,lchen1)) {
			var vid = document.getElementById(chen);
			vid.style.visibility='hidden';
			lchen=lchen1+1;
			lchen1=cach.indexOf(\";\",lchen);
			if (lchen1==-1) break
		}
	}
	}</script></head>";
//----------------
//test pour d�terminer si une geometrie contient la nouvelle
$qs="select contains(the_geom,GeometryFromtext('MULTIPOLYGON(((".$polygo.")))',$projection)) as ip from esvert.sector";
$rs=$DB->tab_result($qs);
$qp="select contains(the_geom,GeometryFromtext('MULTIPOLYGON(((".$polygo.")))',$projection)) as ip from esvert.pelouse";
$rp=$DB->tab_result($qp);
$qf="select contains(the_geom,GeometryFromtext('MULTIPOLYGON(((".$polygo.")))',$projection)) as ip from esvert.fleurie";
$rf=$DB->tab_result($qf);
//----------------
	print_r ($rs);
	print_r ($rp);
	print_r ($rf);
	echo "<div>";
		echo "\n<form id=f1 method='post' action='ins.php'>";
			echo "\n<input name='polygo' type='hidden' value='".$polygorosier."'>";
			echo "<div id='chx' style='position: relative;visibility:visible'>";
			if ($rs[0]["ip"]=="f"){
			echo "\n<input name='secteur' type='checkbox' value='1' onclick=\"suite('dsecteur;dvalid;','chx;dpelouse;dfleurie;')\"> Insertion d'un secteur<br>";}
			if ($rp[0]["ip"]=="f"){
			echo "\n<input name='pelouse' type='checkbox' value='1' onclick=\"suite('dpelouse;dvalid;','chx;dsecteur;dfleurie;')\"> Insertion d'une pelouse<br>";}
			if ($rf[0]["ip"]=="f"){
			echo "\n<input name='fleurie' type='checkbox' value='1' onclick=\"suite('dfleurie;dvalid;','chx;dsecteur;dpelouse;')\"> Insertion d'un massif fleurie<br>";}
			echo "\n</div>";
			echo "\n<div id='dsecteur' style='position: absolute;top:0px;visibility:hidden'>";
				echo "\nNom du secteur : <input name='libel' type='text' value=''>";
			echo "\n</div>";
			echo "\n<div id='dpelouse' style='position: absolute;top:0px;visibility:hidden'>";
				echo "\nArrosage : <input name='arrosage' type='radio' value='M'> Manuel <input name='arrosage' type='radio' value='A'> Automatique <input name='arrosage' type='radio' value='U'> Aucun<br>";
			echo "\n</div>";
			echo "\n<div id='dfleurie' style='position: absolute;top:0px;visibility:hidden'>";
				echo "\nArrosage : <input name='arrosage' type='radio' value='M'> Manuel <input name='arrosage' type='radio' value='A'> Automatique <input name='arrosage' type='radio' value='U'> Aucun<br>";
				echo "\nType de plantation <input name='type_plant' type='text'><br>";
				echo "\nDate de cr�ation <input name='date_pose' type='text'><br>";
			echo "\n</div>";
			echo "<div id='dvalid' style='position: absolute;top:50px;visibility:hidden'>";
			echo "\n<input name='btn' type='submit' value='Valider'>";
			echo "\n</div>";
		echo "\n</form>";
	echo "\n</div>";
?>