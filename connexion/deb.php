<?php 
$pgx=pg_connect("dbname=meaux host=localhost user=postgres");
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
