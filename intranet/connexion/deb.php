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
// Quelques reglages (omigeot) 
include("../../config.php");
//$db_host = "localhost";
//$db_name = "sig";
//$db_user = "sig";
//$db_passwd = "sig";
//$db_params = "dbname=".$db_name." host=".$db_host." user=".$db_user." password=".$db_passwd;
//$ms_dbg_line = 0;
$fs_root = "/home/sig/gismeaux/intranet";
// Fin des reglages

$pgx=pg_connect($db_params);
function tab_result($pgx,$quest){
	$resultat = pg_exec($pgx, $quest);
	if (!$resultat){
		pg_errormessage();
		echo $quest;
	}
	$num=pg_numrows($resultat);
//	if ($num>0){
		for ($i=0; $i<$num; $i++){
			$arr[$i]=pg_fetch_array($resultat,$i);
		}
//	}else{
//		$arr='0';
//	}
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

function codalpha($ch){
         $tx=base_convert($ch,10,26);
         if ($ch>259){
             $txt=chr(96+ord(substr($tx,0,2))-87);
             $txt.=chr(65+($ch-(26*substr($tx,0,2))));
         }else if (($ch>26)and($ch<260)){
               $txt=chr(96+substr($tx,0,1));
               $txt.=chr(65+($ch-(26*substr($tx,0,1))));
         }else{
               $txt=chr(64+$ch);
         }
         if ($ch==0){$txt="";}
         return $txt;
}
function ch2dat($ch){
    $annee=substr($ch,0,4);
    $mois=substr($ch,5,2);
    $jour=substr($ch,8,2);
    $dat=$jour." ".moix($mois)." ".$annee;
    return $dat;
}
function datesql2dmy($ch){
     ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$ch,$regs);
     if (($regs[1]!='')){
        return "$regs[3]/$regs[2]/$regs[1]";
     }else{
         return "&nbsp;";
     }
}
function moix($mm){
	if ($mm=='01'){
		return "janvier";
	}elseif ($mm=='02'){
		return "fï¿½rier";
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
		return "dï¿½embre";
	}
}
?>
