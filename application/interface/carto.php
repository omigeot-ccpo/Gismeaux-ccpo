<?php
//phpinfo();
/*Copyright Ville de Meaux 2004-2007
contributeur: jean-luc Dechamp - robert Leguay 
sig@meaux.fr

Ce logiciel est un programme informatique fournissant une interface cartographique WEB communale. 

Ce logiciel est r�gi par la licence CeCILL-C soumise au droit fran�ais et
respectant les principes de diffusion des logiciels libres. Vous pouvez
utiliser, modifier et/ou redistribuer ce programme sous les conditions
de la licence CeCILL-C telle que diffus�e par le CEA, le CNRS et l'INRIA 
sur le site "http://www.cecill.info".

En contrepartie de l'accessibilit� au code source et des droits de copie,
de modification et de redistribution accord�s par cette licence, il n'est
offert aux utilisateurs qu'une garantie limit�e.  Pour les m�mes raisons,
seule une responsabilit� restreinte p�se sur l'auteur du programme,  le
titulaire des droits patrimoniaux et les conc�dants successifs.

A cet �gard  l'attention de l'utilisateur est attir�e sur les risques
associ�s au chargement,  � l'utilisation,  � la modification et/ou au
d�veloppement et � la reproduction du logiciel par l'utilisateur �tant 
donn� sa sp�cificit� de logiciel libre, qui peut le rendre complexe � 
manipuler et qui le r�serve donc � des d�veloppeurs et des professionnels
avertis poss�dant  des connaissances  informatiques approfondies.  Les
utilisateurs sont donc invit�s � charger  et  tester  l'ad�quation  du
logiciel � leurs besoins dans des conditions permettant d'assurer la
s�curit� de leurs syst�mes et ou de leurs donn�es et, plus g�n�ralement, 
� l'utiliser et l'exploiter dans les m�mes conditions de s�curit�. 

Le fait que vous puissiez acc�der � cet en-t�te signifie que vous avez 
pris connaissance de la licence CeCILL-C, et que vous en avez accept� les 
termes.*/
//$default_insee = 770284;
//$default_appli = 18;

//ini_set('session.gc_maxlifetime', 3600);
//session_start();
/*if($_SESSION['code_insee']=='')
{
$_SESSION['code_insee']=$default_insee;
}
if($_GET["appli"]=='')
{
$_GET["appli"]=$default_appli;
}*/
define('GIS_ROOT', '..');
include_once(GIS_ROOT . '/inc/common.php');
gis_init();
check_auth();
// FIXME : a enlever
//if (eregi('MSIE', $HTTP_USER_AGENT))
//{    
//$nav="0";// Internet Explorer 
//}
//elseif (eregi('Opera', $HTTP_USER_AGENT))
//{ 
//$nav="1";//opéra
//}
//else
//{
////header("Content-type: image/svg+xml");
//$nav="2";//mozilla
//}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!---->
<html><head>
<script type="text/javascript" src="../connexion/media.js"></script>
<title>Cartographie</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="SVG, Scalable Vector Graphic, SIG, GIS, Mapserver, Postgis, Postgresql, MapInfo, g�ographie, g�omatique, carte, cartographie, map, XML">
<meta name="description" content="Carte SVG - renseignement d'urbanisme.">
<meta name="Author" content="sig-meaux">
<meta name="Identifier-URL" content="http://www.carto.meaux.fr">
<meta name="Date-Creation-yyyymmdd" content="20070424">
<meta name="Reply-to" content="sig@meaux.fr">
</head>
<body>
<div style="position:absolute;left:300px;top:290px;width:200px;height:200px;">
<p align="center">Pour utiliser la cartographie</p>
<p align="center"> Vous devez installer le plugin <a href="http://<?php if($nav==2){echo $_SERVER['HTTP_HOST']."/addons/SVGView6.exe";}else
{echo $_SERVER["HTTP_HOST"]."/addons/SVGView303.exe";}?>">Adobe SVGviewer</a></p>
</div>
<div>
<script>
var stringEmbedsvg='<embed src="/interface/interface.php?<?php echo session_name()."=".session_id(); ?>" width="100%" height="100%"  pluginspage="www.adobe.com/svg/install.htm" name="svgmap" type="image/svg+xml" id="svgmeaux"></embed>';
Writeembedsvg(stringEmbedsvg);
</script>
</div>
</body>
</html>
