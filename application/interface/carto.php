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
$nav="1";//opÃ©ra
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