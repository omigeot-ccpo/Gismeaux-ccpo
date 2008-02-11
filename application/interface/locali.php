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
ini_set('session.gc_maxlifetime', 3600);
session_start();
include("../connexion/deb.php");
$parcelle=$_GET['parcelle'];
if($parcelle=='')
{
$numero1=$_GET['numero'];
$numero='0000'.$_GET['numero'];
$numero=substr($numero,-4);
$cod_insee=substr($_GET['code'],0,6);
$ccoriv=substr($_GET['code'],6);
$q= "SELECT ccosec,dnupla FROM cadastre.parcel WHERE dnuvoi='$numero' AND ccoriv='$ccoriv' AND commune='$cod_insee'";
$cou=tab_result($pgx,$q);
$section1=ltrim($cou[0]['ccosec']);
$section1=str_pad($section1, 2, "0", STR_PAD_LEFT);
$parcelle1=$cou[0]['dnupla'];

$parcelle=substr($cod_insee,3).'000'.$section1.$parcelle1;

	if(count($cou)==0)
	{
	$result ="SELECT X(centroid(Translate(the_geom,-".$_SESSION['xini'].",-".$_SESSION['yini']."))) as x,Y(centroid(Translate(the_geom,-".$_SESSION['xini'].",-".$_SESSION['yini']."))) as y FROM eco.adresse WHERE numero='$numero1' AND rivoli='$ccoriv' AND code_insee='$cod_insee'";
	$cou1=tab_result($pgx,$result);
	$xmin=$cou1[0]['x'];
	$ymin=$cou1[0]['y'];

		if(count($cou1)>0)
		{
		$parcelle='point';
		}
		else
		{
		$result ="SELECT ccosec,dnupla FROM cadastre.parcel WHERE ccoriv='$ccoriv' AND commune='$cod_insee' order by dnuvoi asc";
		$cou2=tab_result($pgx,$result);
		$nb=round(count($cou2)/2);
		$section1=ltrim($cou2[$nb]['ccosec']);
		$section1=str_pad($section1, 2, "0", STR_PAD_LEFT);
		$parcelle1=$cou2[$nb]['dnupla'];
		$parcelle=substr($cod_insee,3).'000'.$section1.$parcelle1;
			if(count($cou2)>0)
			{
			$parcelle=strtoupper($parcelle);
			$qs1="select X(centroid(Translate(the_geom,-".$_SESSION['xini'].",-".$_SESSION['yini']."))) as x,Y(centroid(Translate(the_geom,-".$_SESSION['xini'].",-".$_SESSION['yini']."))) as y from 			cadastre.parcelle where identifian='$parcelle'";
			$cou4=tab_result($pgx,$qs1);
			$xmin=$cou4[0]['x'];
			$ymin=$cou4[0]['y'];
			$parcelle='point';
			}
			else
			{
			$parcelle='nonok';
			}
		}
	}

}
if($parcelle!='point' && strlen($parcelle)!=6)
{
$parcelle=strtoupper($parcelle);
$qs="select X(centroid(Translate(the_geom,-".$_SESSION['xini'].",-".$_SESSION['yini']."))) as x,Y(centroid(Translate(the_geom,-".$_SESSION['xini'].",-".$_SESSION['yini']."))) as y from cadastre.parcelle where identifian='$parcelle'";
$cou3=tab_result($pgx,$qs);
	$xmin=$cou3[0]['x'];
	$ymin=$cou3[0]['y'];
}

print("$parcelle;$xmin;$ymin;$zoom;$xm;$ym;");


?>

