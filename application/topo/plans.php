<?php
session_start();
include('../connexion/deb.php');
$q="select * from public.geomet where intersects(the_geom,GeometryFromtext('POLYGON((".$_GET["polygo"]."))',-1))";
if ( $_SESSION['code_insee'] != '770000'){$q .= " and code_insee='".$_SESSION['code_insee']."'";}
$resultat = tab_result($pgx, $q);

//--------------------------------
//script java de gestion des div
echo "<head>\n";
echo '<script language="JavaScript" type="text/JavaScript">';
echo "\nfunction effacediv(i){document.getElementById(i).style.visibility='hidden';}";
echo "\nfunction affichediv(i){document.getElementById(i).style.visibility='visible';}";
echo "\nfunction nom_fichier(inc){";
echo "\n  slatche=inc.lastIndexOf('\\\\');";
echo "\n  poin= inc.lastIndexOf(\".\");";
//echo "\n  document.getElementById('fich_ins').value=inc.substring(0, poin)+'.dwg'; ";
echo "\n  document.forms['f1'].elements[13].value=inc.substring(slatche+1, poin);}";
//echo "\n  document.forms['f1'].elements[14].value=inc.substring(0,poin)+'.dwf';}";
/*echo "\nfunction control(){ ";
echo "\n  if ((popup.fich.value != '') && (popup.fich.value.length<9)) { ";
echo "\n    return true; ";
echo "\n  }else{ ";
echo "\n    if (popup.fich.value == '') { ";
echo "\n      alert('Le champ fichier ne peut-etre nul!'); ";
echo "\n      return false;";
echo "\n    }else{ ";
echo "\n      alert('Le nom du fichier est trop long!');";
echo "\n      return false;";
echo "\n    }";
echo "\n  }";
echo "\n}";*/
echo "\n</script>";
echo "\n</head>";
echo "\n<body>";

//--------------------------------
//Bouton d'insertion d'un plan topo
echo "\n<INPUT  type='button' value='Insérer un plan' onclick='effacediv(\"pl\");affichediv(\"ins\");'>";
echo "\n<div id='ins' style='visibility:hidden'> ";
    echo "\n<div align='center'>Insertion d'un plan géomètre</div><br>";
    echo "\n<table width='600'><form id='f1' method='post' action='ins_topo.php' onsubmit='control()' enctype='multipart/form-data'>";
	echo "\n <tr><th>Boite : <input name='boite' type='text' value='' size='5' maxlength='2'><br></th><td>";
	echo "\n indice de class. :<input name='disk' type='text' value='' size='5' maxlength='5'><br></td></tr>";
    echo "\n<tr><th>  Géomètre : </th><td><input name='geometre' type='text' size='10' maxlength='10'>
		\n<select name='geom' onChange='geometre.value=this.value' >";
    $q1="SELECT distinct(GEOMETRE) as geometre FROM public.GEOMETRE_SSQL";
    $r1=tab_result($pgx,$q1);
    for ($p=0;$p<count($r1);$p++){
		 echo "\n <option value='".$r1[$p]['geometre']."'>".$r1[$p]['geometre'];
    }
    echo "\n</select></td></tr>";
	echo "\n<tr><th>	Date : </th><td><input name='plan_dat' type='text' value='".date('d/m/Y')."'></td></tr>";
	echo "\n<tr><th>	Service : </th><td><input name='servi' type='text' size='10' maxlength='10'>
			\n<select name='servic' onChange='servi.value=this.value' >";
    $q2="SELECT distinct(SERVICE) as service FROM public.GEOMETRE_SSQL";
    if ( $_SESSION['code_insee'] != '770000'){$q2 .= " and code_insee='".$_SESSION['code_insee']."'";}
    $r2=tab_result($pgx,$q2);
    for ($o=0;$o<count($r2);$o++){
		echo "\n<option value='".$r2[$o]['service']."'>".$r2[$o]['service'];
    }
    echo "\n</select></td></tr> ";
    echo "\n<tr><th colspan=2>	Contenu : <br><input name='ass' type='checkbox' value=''> Assainissement
			\n<input name='aep' type='checkbox' value=''> Adduction d'Eau Potable
			\n<input name='ep' type='checkbox' value=''> Eclairage Public <br>
			\n<input name='recol' type='radio' value='1'> Recolement
			\n<input name='recol' type='radio' value='0' checked> Topo <br><br></th></tr>";
    echo "\n<tr><th>	Fichier dwg : </th><td><input id='fich_ins' name='fich_ins' type='file' onChange='nom_fichier(this.value)' value=''></td></tr>
		 \n<input name='fich' id='fich' type='hidden' value='' size='10' maxlength='8'>";
    echo "\n<tr><th>	Fichier dwf : </th><td><input name=\"dwf_ins\" type=\"file\" value=\"\"></td></tr>
	     \n<tr><td colspan=2>	Le fichier dwf nécessaire à l'affichage sur intranet doit porter le même nom!<br>
		 \nLe nom est limité à 8 caractères</td></tr> \n<input name='polygo' type='hidden' value='".$_GET["polygo"]."'>";
	echo "\n<tr><td colspan=2>	<input name='bt_reg' type='submit' value='Enregistrer'></td></tr>";

    echo "\n</table></form>";
echo "\n</div>";

//--------------------------------
//Affichage de la liste de plans
echo "<div id='pl' style='position: absolute;top : 50px;left: 15px;visibility:visible'> ";
if (count($resultat)==0){
    echo '<script language="JavaScript" type="text/JavaScript">';
    echo 'affichediv("ins");';
    echo "</script>";
    echo 'Pas de plan topo dans le périmètre sélectionné';
}elseif (count($resultat)==1){
    $ch= "http://".$_SERVER["SERVER_NAME"]."/topo/topo.php?dess=./".$_SESSION['code_insee']."/dwf/".strtolower($resultat[0]['local1'])."/".$resultat[0]['fichier'];
    include($ch);
}else{
      echo '<table><tr><th>Fichier</th><th>Date</th><th>Assainissement</th><th>AEP</th>';
      echo '<th>EP</th><th>Recolement</th><th>Géomètre</th></tr>';
      for ($j=0;$j<count($resultat);$j++){
	     echo '<tr><td><a href="http://'.$_SERVER["SERVER_NAME"].'/topo/topo.php?dess=./'.$_SESSION['code_insee'].'/dwf/'.strtolower($resultat[$j]['local1']).'/'.$resultat[$j]['fichier'].'">'.$resultat[$j]['fichier'].'.dwg</a></td>';
		 echo '<td>'.$resultat[$j]['dat'].'</td>';
		 echo '<td>'.$resultat[$j]['ass'].'</td>';
		 echo '<td>'.$resultat[$j]['aep'].'</td>';
		 echo '<td>'.$resultat[$j]['ep'].'</td>';
		 echo '<td>'.$resultat[$j]['recol'].'</td>' ;
		 echo '<td>'.$resultat[$j]['geometre'].'</td></tr>' ;
      }
}
echo "</div>";
?>
</body>
</html>
