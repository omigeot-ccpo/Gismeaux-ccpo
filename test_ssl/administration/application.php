<?php 
$ora=pg_connect("dbname=meaux host=localhost user=postgres");
if ($util){
 	$q2="select idapplication from admin_svg.application";
	$r2=pg_exec($ora,$q2);
	$num=pg_numrows($r2);
	$ins="delete from admin_svg.apputi where idutilisateur='".$util."'";
	$aa=pg_exec($ora,$ins);$j=1;
	for ($i=0; $i<$num; $i++){
		$tes2 = pg_fetch_array($r2);
		if (${$tes2["idapplication"]}=='0'){
			$in2="insert into admin_svg.apputi (idutilisateur,idapplication,ordre) values('".$util."','".$tes2["idapplication"]."','".$j."')";
			$a2=pg_exec($ora,$in2); $j++;
		}
	} 
	$ident=$util; 
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Document sans titre</title>
</head>

<body>
<form name="f1" action="application.php" method="get">
<input name="util" type="hidden" value="<?php echo $ident ?>">
<table>
<?php 
$quer="select * from admin_svg.application where idapplication in (select idapplication from admin_svg.apputi where idutilisateur='".$ident."')";
$que2="select * from admin_svg.application where idapplication not in (select idapplication from admin_svg.apputi where idutilisateur='".$ident."')";
$tab=pg_exec($ora,$quer);
$ta2=pg_exec($ora,$que2);
$num2=pg_numrows($tab);
$num3=pg_numrows($ta2);
for ($i=0; $i<$num2; $i++){
	$ff[$i]=pg_fetch_array($tab, $i);
   echo '<tr><td><input name="'.$ff[$i]['idapplication'].'" type="checkbox" value="0" onchange="if(this.value==\'0\'){this.value=\'1\';}else{this.value=\'0\';}" checked></td><td>'.$ff[$i]['libelle_appli'].'</td><td>'.$ff[$i]['url'].'</td><td>'.$ff[$i]['divers'].'</td></tr>'; 
 }
 for ($i=0; $i<$num3; $i++){
	$ff[$i]=pg_fetch_array($ta2, $i);
   echo '<tr><td><input name="'.$ff[$i]['idapplication'].'" type="checkbox" value="1" onchange="if(this.value==\'0\'){this.value=\'1\';}else{this.value=\'0\';}"></td><td>'.$ff[$i]['libelle_appli'].'</td><td>'.$ff[$i]['url'].'</td><td>'.$ff[$i]['divers'].'</td></tr>'; 
 }
 ?>
</table>
<input name="btn_valid" type="button" onClick="document.f1.submit()" value="Enregistrer">
<input name="btn_annul" type="button" onClick="window.close()" value="Annuler">
</form>
</body>
</html>
