<?php 
/*Copyright Ville de Meaux 2004-2007
contributeur: jean-luc Dechamp - robert Leguay 
sig@meaux.fr

Ce logiciel est un programme informatique fournissant une interface cartographique WEB communale. 

Ce logiciel est r�i par la licence CeCILL-C soumise au droit fran�is et
respectant les principes de diffusion des logiciels libres. Vous pouvez
utiliser, modifier et/ou redistribuer ce programme sous les conditions
de la licence CeCILL-C telle que diffus� par le CEA, le CNRS et l'INRIA 
sur le site "http://www.cecill.info".

En contrepartie de l'accessibilit�au code source et des droits de copie,
de modification et de redistribution accord� par cette licence, il n'est
offert aux utilisateurs qu'une garantie limit�.  Pour les m�es raisons,
seule une responsabilit�restreinte p�e sur l'auteur du programme,  le
titulaire des droits patrimoniaux et les conc�ants successifs.

A cet �ard  l'attention de l'utilisateur est attir� sur les risques
associ� au chargement,  �l'utilisation,  �la modification et/ou au
d�eloppement et �la reproduction du logiciel par l'utilisateur �ant 
donn�sa sp�ificit�de logiciel libre, qui peut le rendre complexe �
manipuler et qui le r�erve donc �des d�eloppeurs et des professionnels
avertis poss�ant  des connaissances  informatiques approfondies.  Les
utilisateurs sont donc invit� �charger  et  tester  l'ad�uation  du
logiciel �leurs besoins dans des conditions permettant d'assurer la
s�urit�de leurs syst�es et ou de leurs donn�s et, plus g��alement, 
�l'utiliser et l'exploiter dans les m�es conditions de s�urit� 

Le fait que vous puissiez acc�er �cet en-t�e signifie que vous avez 
pris connaissance de la licence CeCILL-C, et que vous en avez accept�les 
termes.*/
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
