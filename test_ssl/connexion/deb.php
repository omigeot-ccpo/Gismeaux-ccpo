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
session_start();
$pgx=pg_connect("dbname=meaux host=localhost user=postgres password=passpg");
function tab_result($pgx,$quest){
	$resultat = pg_exec($pgx, $quest);
	$num=pg_numrows($resultat);
	for ($i=0; $i<$num; $i++){
		$arr[$i]=pg_fetch_array($resultat,$i);
	}
	return $arr;
}
function list_result($pgx,$quest){
	$resultat = pg_exec($pgx, $quest);
	$num=pg_numrows($resultat);
    $l="'";
	for ($i=0; $i<$num; $i++){
		$arr=pg_fetch_array($resultat,$i);
        $l.=$arr[0]."','";
	}
    $l=substr($l,0,-2);
    return $l;
}

function ch2dat($ch){
    $annee=substr($ch,0,4);
    $mois=substr($ch,4,2);
    $jour=substr($ch,6,2);
    $dat=$jour."/".$mois."/".$annee;
    return $dat;
}
function dmy2datesql($ch){
    ereg_replace("/|-","",$ch);
    $annee=substr($ch,4,4);
    $mois=substr($ch,2,2);
    $jour=substr($ch,0,2);
    $dat=$annee."-".$mois."-".$jour;
    return $dat;
}
function datesql2dmy($ch){
     ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$ch,$regs);
     return "$regs[3]/$regs[2]/$regs[1]";
}
function moix($mm){
	if ($mm=='01'){
		return "janvier";
	}elseif ($mm=='02'){
		return "f�rier";
	}elseif ($mm=='03'){
		return "mars";
	}elseif ($mm=='04'){
		return "avril";
	}elseif ($mm=='05'){
		return "mai";
	}elseif ($mm=='06'){
		return "juin";
	}elseif ($mm=='07'){
		return "juillet";
	}elseif ($mm=='08'){
		return "aout";
	}elseif ($mm=='09'){
		return "septembre";
	}elseif ($mm=='10'){
		return "octobre";
	}elseif ($mm=='11'){
		return "novembre";
	}elseif ($mm=='12'){
		return "d�embre";
	}
}

?>
