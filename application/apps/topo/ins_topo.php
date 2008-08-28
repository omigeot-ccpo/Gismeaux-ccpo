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

if ((!$_SESSION['profil']->acces_ssl) || !in_array ("Plan geometre", $_SESSION['profil']->liste_appli)){
	die("Point d'entrée réglementé.<br> Accès interdit. <br>Veuillez vous connecter via <a href=\"https://".$_SERVER['HTTP_HOST']."\">serveur carto</a><SCRIPT language=javascript>setTimeout(\"window.location.replace('https://".$_SERVER['HTTP_HOST']."')\",5000)</SCRIPT>");
}
//session_start();
//include('../connexion/deb.php');

//V�rifier l'existance d'un fichier de m�me nom dans le r�pertoire
$filname= '/home/sig/gis/application/doc_commune/'.$insee.'/dwf/'.$_POST['boite'].$_POST['disk'].'/'.strtolower($_POST['fich']).'.dwg';
if ( @opendir('/home/sig/gis/application/doc_commune/'.$insee.'/dwf/'.$_POST['boite'].$_POST['disk'])){
    $i=1;
    while ( file_exists($filname)){
         $filname= '/home/sig/gis/application/doc_commune/'.$insee.'/dwf/'.$_POST['boite'].$_POST['disk'].'/'.strtolower($_POST['fich']).'_'.$i.'.dwg';$i++;
    }
}else{
    if (! @opendir('/home/sig/gis/application/doc_commune/'.$insee)){mkdir('/home/sig/gis/application/doc_commune/'.$insee,0775);}
    if (! @opendir('/home/sig/gis/application/doc_commune/'.$insee.'/dwf/')){mkdir('/home/sig/gis/application/doc_commune/'.$insee.'/dwf/',0775);}
    mkdir('/home/sig/gis/application/doc_commune/'.$insee.'/dwf/'.$_POST['boite'].$_POST['disk'],0775);
}
$filname1=substr($filname,0,-4).'.dwf';

//Ins�rer dans geometre_ssql les donn�es en r�glant le spa_id sur nextval
$q1="begin;insert into public.geometre_ssql (boite,disquet,fichier,service,local1,dat,ass,aep,ep,recol,geometre) values('";
$q1.=$_POST['boite']."','".$_POST['disk']."','".strtolower($_POST['fich'])."','".$_POST['servi']."','".$_POST['boite'].$_POST['disk']."','".$_POST['plan_dat']."','".$_POST['ass']."','".$_POST['aep']."','".$_POST['ep']."','".$_POST['recol']."','".$_POST['geometre']."');";

//Inserer dans geometre les donn�es en r�glant l'id sur currentval
$q1.="insert into public.geometre(the_geom,code_insee) values(GeometryFromtext('POLYGON((".$_POST['polygo']."))',$projection),'".$insee."');";
$q1.="commit;";
$DB->exec($q1);

//Charger les fichiers distants
move_uploaded_file($_FILES['fich_ins']['tmp_name'], $filname);
move_uploaded_file($_FILES['dwf_ins']['tmp_name'], $filname1);
?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN"> 
<html>
<head>
       <title>Title here!</title>
</head>
<body>
<?php
?>
</body>
</html>
