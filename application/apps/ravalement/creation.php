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
if($_GET['polygo']!="0 0,1 1")
{

$pol= explode(",",$_GET['polygo']);
  $polygo=$pol[0];

$requete="SELECT the_geom FROM cadastre.batiment 
WHERE
       Distance(GeometryFromtext('POINT(".$polygo.")',$projection),batiment.the_geom)=0
";

$col=$DB->tab_result($requete);
if($col[0]['the_geom']=="")
{
$requete="SELECT the_geom FROM cadastre.batiment 
WHERE
       Distance(batiment.the_geom,(SELECT the_geom FROM cadastre.parcelle 
WHERE
       Distance(GeometryFromtext('POINT(".$polygo.")',$projection),parcelle.the_geom)=0))=0 and area(batiment.the_geom)<0 and batiment.constructi='Bati dur'
";
$col=$DB->tab_result($requete);

}
if($col[0]['the_geom']=="")
{
$requete="SELECT the_geom FROM bd_topo.batiment 
WHERE
       Distance(GeometryFromtext('POINT(".$polygo.")',$projection),batiment.the_geom)=0
";
$col=$DB->tab_result($requete);
$polygo=$col[0]['the_geom'];
}
else
{
$polygo=$col[0]['the_geom'];
}
}
	
	?>
<html>
<body>

<form method="post" action="valajout.php" enctype="multipart/form-data">
 <p>Etat de la facade:<select name="type">
      <option value="a" >bon �tat</option>
      <option value="b" >�tat moyen</option>
      <option value="c" >mauvais �tat</option>
	  </select>
</p>
 <p>Facade � surveiller:<input name="surveiller" type="checkbox" value="1"></p>
<p>Date:
    <input name="date" type="text" value="<?php echo date("d").'/'.date("m").'/'.date("Y"); ?>" size="10">
</p>
<p>Ann&eacute;e dernier ravalement:
    <input name="date_raval" type="text" size="2">
</p>
<p>
Photos:</p>
<p><INPUT name="photo[]" type=file></p>
<p><INPUT name="photo[]" type=file> </p>
<p><INPUT name="photo[]" type=file> </p>
<p><INPUT name="photo[]" type=file></p> 
<p><INPUT name="photo[]" type=file></p>
<p>
observation:
</p>
<p>
<textarea name="observation" cols="30" rows="5"></textarea>
</p>   
<input name="polygo" type="hidden" value="<?php echo $polygo;?>">
<input name="Ajouter" type="submit" value="Ajouter">
</form>
</body>
</html>
