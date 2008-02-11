<?php
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

define('GIS_ROOT', '..');
include_once(GIS_ROOT . '/inc/common.php');
gis_init();
check_auth();
//include('../mainconfig.php');

if (eregi('MSIE', $HTTP_USER_AGENT))
{    
$nav="0";// Internet Explorer 
}
elseif (eregi('Opera', $HTTP_USER_AGENT))
{ 
$nav="1";//opéra
}
else
{
//header("Content-type: image/svg+xml");
$nav="2";//mozilla
}
?>
<?php cartoHeader("Serveur de tests");?>
<p align="center">Pour utiliser la cartographie</p>
<p align="center"> Vous devez installer le plugin <a href="http://<?php if($nav==2){echo get_root_url()."/SVGView6.exe";}else
{echo get_host_url()."/SVGView303.exe";}?>">Adobe SVGviewer</a></p>
</div>
<div>
<script>
var stringEmbedsvg='<embed src="<?php echo get_root_url();?>/interface/interface.php?".session_name()."=".session_id(); ?>" width="100%" height="100%"  pluginspage="www.adobe.com/svg/install.htm" name="svgmap" type="image/svg+xml" id="svgmeaux"></embed>';
Writeembedsvg(stringEmbedsvg);
</script>
<?php cartoFooter();?>