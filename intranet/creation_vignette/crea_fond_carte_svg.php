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
include("../connexion/deb.php");
//$code_insee=771000;
if($type=="A")
	{
$insee=substr($codeinsee,0,3);
}
else
{
$insee=$codeinsee;
}

$sql="select idcommune as code_insee,nom from admin_svg.commune where commune.idcommune like '".$insee."%'";
		$res=tab_result($pgx,$sql);
for ($z=0;$z<count($res);$z++)
{		
		
$req2="select commune.xmi as xini,commune.xma as xmax, commune.yma as yini ,commune.ymi as ymin from admin_svg.commune where commune.idcommune like '".$res[$z]['code_insee']."'";
		$vu=tab_result($pgx,$req2);
$xm=$vu[0][xini];
$xma=$vu[0][xmax];
$yma=$vu[0][yini];
$ym=$vu[0][ymin];

$x1=$xm;
$y1=$ym;
$x2=$xma;
$y2=$yma;
$lar=$xma-$xm;
$hau=$yma-$ym;

if($hau*(302/254)>$lar)
{
$largeur=254*($lar/$hau);
$hauteur=254;
}
else
{
$largeur=302;
$hauteur=302*($hau/$lar);
}

$serv=$_SERVER["SERVER_NAME"];
$sql="select distinct code_insee from cadastre.batiment where code_insee=".$res[$z]['code_insee'];
$result=tab_result($pgx,$sql);
if(count($result)>0)
{
$laybat="batiment";
}
else
{
$laybat="bati";
}
if(substr($res[$z]['code_insee'], -3)=='000')
{
$code=substr($res[$z]['code_insee'],0,3);
}
else
{
$code=$res[$z]['code_insee'];
}
if($bd=="o")
{
$url="http://".$serv."/cgi-bin/mapserv?map=/home/sig/intranet/capm/vignette.map&layer=commune&layer=".$laybat."&layer=surface_eau&layer=troncon_route&insee=".$code."&ID=".$res[$z]['code_insee']."&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&mapsize=".$largeur."%20".$hauteur;
}
else
{
$url="http://".$serv."/cgi-bin/mapserv?map=/home/sig/intranet/capm/vignette.map&layer=commune&layer=batiment&insee=".$code."&ID=".$res[$z]['code_insee']."&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&mapsize=".$largeur."%20".$hauteur;
}
$contenu=file($url);
       		while (list($ligne,$cont)=each($contenu)){
			$numligne[$ligne]=$cont;
		}
		$texte=$numligne[1];
		$image=explode('/',$texte);
		
		$conte1=explode('.',$image[4]);
		$image=$conte1[0];
//echo $lar." ".$hau." ".$largeur." ".$hauteur;
if(@rename("/home/sig/intranet/interface/communes/tt".$res[$z]['code_insee'].".jpg","/home/sig/intranet/interface/communes/".$res[$z]['code_insee'].".JPG"))
{
echo $res[$z]['code_insee'].",".$res[$z]['nom']." ok<br>";
}
}
echo "<a href=\"./index.php\" target=\"_parent\">Retour</a>";
//echo $code_insee;
?>
