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
//ini_set('session.gc_maxlifetime', 3600);
//session_start();
include("../../connexion/deb.php");
if($_GET['indice']=='propri')
{
$result ="select appthe.mouseover,appthe.click,appthe.mouseout,appthe.raster,appthe.zoommin,appthe.zoommax,appthe.zoommaxraster,appthe.objselection,appthe.objprincipal,appthe.partiel,appthe.vu_initial,appthe.idtheme,theme.schema,theme.tabl,appthe.idappthe from admin_svg.appthe join admin_svg.theme on appthe.idtheme=theme.idtheme where appthe.idappthe='".$_GET['objkey']."'";
	$cou=tab_result($pgx,$result);
	
$d="select * from admin_svg.col_sel where idtheme='".$cou[0]['idtheme']."'";
		$col=tab_result($pgx,$d);
			for ($z=0;$z<count($col);$z++){
		
		if($col[$z]['nom_as']=='ident')
		{
		$ident=$col[$z]['appel'];
		}
		if($col[$z]['nom_as']=='ad')
		{
		$ad=$col[$z]['appel'];
		}
		}	
echo $cou[0]['mouseover']."#".$cou[0]['click']."#".$cou[0]['mouseout']."#".$cou[0]['raster']."#".$cou[0]['zoommin']."#".$cou[0]['zoommax']."#".$cou[0]['zoommaxraster']."#".$cou[0]['objselection']."#".$cou[0]['objprincipal']."#".$cou[0]['partiel']."#".$cou[0]['vu_initial']."#".$ident."#".$ad."#".$cou[0]['schema']."#".$cou[0]['tabl']."#".$cou[0]['idtheme']."#".$cou[0]['idappthe']."#";
}
if($_GET['indice']=='raster')
{
$res="select admin_svg.v_fixe(raster) from admin_svg.theme where idtheme=".$_GET['objkey'];
$col=tab_result($pgx,$res);
$d1="select schema,tabl from admin_svg.theme where idtheme=".$_GET['objkey'];
$col1=tab_result($pgx,$d1);
$retour="true";
if($col[0]['v_fixe']==0)
{
$retour="false";
}

echo $retour."#".$col1[0]['schema']."#".$col1[0]['tabl'];

}
?>