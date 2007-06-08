<?php 
session_start();
$pgx=pg_connect("dbname=meaux host=localhost user=postgres");
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
		return "février";
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
		return "décembre";
	}
}

?>
