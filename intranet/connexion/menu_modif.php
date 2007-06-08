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
avertis possédant  des  connaissances  informatiques approfondies.  Les
utilisateurs sont donc invités à charger  et  tester  l'adéquation  du
logiciel à leurs besoins dans des conditions permettant d'assurer la
sécurité de leurs systèmes et ou de leurs données et, plus généralement, 
à l'utiliser et l'exploiter dans les mêmes conditions de sécurité. 

Le fait que vous puissiez accéder à cet en-tête signifie que vous avez 
pris connaissance de la licence CeCILL-C, et que vous en avez accepté les termes.*/

require_once('connexion.php');
if ($supdoss != ""){
	$quer="select b.id from testmenu as a left join testmenu as b on a.id=b.id_pere where a.id=$supdoss";
	$rec=mysql_query($quer);
	$rw=mysql_fetch_assoc($rec);
	if (mysql_num_rows($rec)>0){
		$q=$rw['id'];
		while ($rw=mysql_fetch_assoc($rec)) {
			$q.=",".$rw['id'];
		} 
		$q2="select b.id from testmenu as a left join testmenu as b on a.id=b.id_pere where a.id in (".$q.")";
		$rec2=mysql_query($q2,$travaux);
		$rw2=mysql_fetch_assoc($rec2);
		if (mysql_num_rows($rec2)>0){
			do {
				if ($rw2['id'] != null){$q.=",".$rw2['id'];}
			} while($rw2=mysql_fetch_assoc($rec2));
		}
		$q.=",".$supdoss;
		$q1="delete from testmenu where id in (".$q.")";
	}else{
		$q1="delete from testmenu where id=$supdoss";
	}
	$r1=mysql_query($q1);
}
if ($suprub != ""){
	$quer="select b.id from testmenu as a left join testmenu as b on a.id=b.id_pere where a.id=$suprub";
	$rec=mysql_query($quer);
	$rw=mysql_fetch_assoc($rec);
	if (mysql_num_rows($rec)>0){
		$q=$rw['id'];
		while ($rw=mysql_fetch_assoc($rec)) {
			$q.=",".$rw['id'];
		} 
		$q.=",".$suprub;
		$q1="delete from testmenu where id in (".$q.")";
	}else{
		$q1="delete from testmenu where id=$suprub";
	}
	$r1=mysql_query($q1);
}
if ($supart != ""){
	$q1="delete from testmenu where id=$supart";
	$r1=mysql_query($q1);
}
if ($lib){
	$quer="insert into testmenu (libelle) values('".$lib."')";
	$rec=mysql_query($quer);
}
if($libsd){
	$quer="insert into testmenu (id_pere,libelle,page) values('".$dossi."','".$libsd."','".$pgsd."')";
	$rec=mysql_query($quer,$travaux);
}
if($libar){
	$quer="insert into testmenu (id_pere,libelle,page) values('".$ssdossi."','".$libar."','".$pgar."')";
	$rec=mysql_query($quer);
}
$query_Recordset1 = "SELECT * FROM testmenu";
$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Document sans titre</title>
<?php 
echo '<script language="JavaScript" type="text/JavaScript">';
echo 'var menu = new Array();';
echo 'menu[0] = new Array();';
echo 'menu[1] = new Array();';
echo 'menu[2] = new Array();';
echo 'menu[3] = new Array();';
echo 'menu[4] = new Array();';
$j=0;
do {
	echo 'menu[0]['.$j.']="'.$row_Recordset1['id'].'";';
	echo 'menu[1]['.$j.']="'.$row_Recordset1['id_pere'].'";';
	echo 'menu[2]['.$j.']="'.$row_Recordset1['libelle'].'";';
	echo 'menu[3]['.$j.']="'.$row_Recordset1['page'].'";';
	echo 'menu[4]['.$j.']="'.$row_Recordset1['entete'].'";';
	$j++;
}while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
echo '</script>';
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function ch_div() {
	document.getElementById("l1").style.visibility = "hidden";
	document.getElementById("l4").style.visibility = "hidden";
	document.getElementById("l3").style.visibility = "hidden";
	document.getElementById("l2").style.visibility = "visible";
} 
function ch_div2() {
	document.getElementById("l3").style.visibility = "hidden";
	document.getElementById("l5").style.visibility = "visible";
} 
function ch_div3() {
	document.getElementById("l4").style.visibility = "hidden";
	document.getElementById("l6").style.visibility = "visible";
} 

function aff_ssdossier(idpere){
	document.getElementById("l3").style.visibility = "hidden";
	document.getElementById("l4").style.visibility = "hidden";
	document.getElementById("l5").style.visibility = "hidden";
	document.getElementById("l6").style.visibility = "hidden";
	document.getElementById("l2").style.visibility = "hidden";
	content = 'Rubrique<br><select name="ssdossier" onChange="aff_article(this.value)">';
	content += '<option>&nbsp;</option>';
	for (m = 0 ; m < <?php echo $totalRows_Recordset1?> ; m++){
		if (menu[1][m] == idpere){
			content += '<option value='+menu[0][m]+'>'+menu[2][m]+'</option>';
		}
		if (menu[0][m] == idpere){
			nomdoss=menu[2][m]
		}
	}
	content += '</select><input name="btn_ssdossier" type="button" onClick="ch_div2()" value="Nouveau">';
  	content += '<div id="l31" style="position:absolute; left:200px; top:20px; z-index:1; visibility:hidden">';
	content += '<input name="suprub" type="hidden" value="">';
	content += '<input name="btn_suprub" type="button" id="btn_suprub" onClick="this.form.suprub.value=this.form.ssdossier.value;this.form.submit()">';
  	content += '</div>';
	document.getElementById("l3").innerHTML = content;
	document.getElementById("l3").style.visibility = "visible";
	document.getElementById("dossi").value = idpere;
	document.getElementById("btn_supdoss").value = "Effacer dossier "+nomdoss+" et les rubriques attenantes";
	document.getElementById("l11").style.visibility = "visible";
}
function aff_article(idpere){
	document.getElementById("l4").style.visibility = "hidden";
	content = 'Article<br><select name="article" onChange="aff_rien(this.value)">';
	content += '<option>&nbsp;</option>';
	for (k = 0 ; k < <?php echo $totalRows_Recordset1?> ; k++){
		if (menu[1][k] == idpere){
			content += '<option value='+menu[0][k]+'>'+menu[2][k]+'</option>';
		}
		if (menu[0][k] == idpere){
			nomdoss=menu[2][k]
		}
	}
	content += '</select><input name="btn_article" type="button" onClick="ch_div3()" value="Nouveau">';
  	content += '<div id="l41" style="position:absolute; left:200px; top:20px; z-index:1; visibility:hidden">';
	content += '<input name="supart" type="hidden" value="">';
	content += '<input name="btn_supart" type="button" id="btn_supart" onClick="this.form.supart.value=this.form.article.value;this.form.submit()">';
  	content += '</div>';
	document.getElementById("l4").innerHTML = content;
	document.getElementById("l4").style.visibility = "visible";
	document.getElementById("ssdossi").value = idpere;
	document.getElementById("btn_suprub").value = "Effacer rubriques "+nomdoss+"\n et les articles attenants";
	l=200+(5*nomdoss.length);
	document.getElementById("l31").style.left=l;
	document.getElementById("l31").style.visibility = "visible";
}
function aff_rien(idpere){
	for (k = 0 ; k < <?php echo $totalRows_Recordset1?> ; k++){
		if (menu[0][k] == idpere){
			nomdoss=menu[2][k]
		}
	}
	document.getElementById("btn_supart").value = "Effacer article "+nomdoss+" ";
	l=200+(5*nomdoss.length);
	document.getElementById("l41").style.left=l;
	document.getElementById("l41").style.visibility = "visible";
}
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body>
<form action="menu_modif.php" method="post">
<div id="l1" name="l1" style="position:absolute; top:10px; left:10px; visibility:visible">
    <input name="dossi" type="hidden" id="dossi" value="">
    <input name="ssdossi" type="hidden" id="ssdossi" value="">
	<select name="dossier" onChange="aff_ssdossier(this.value)">
    <script language="JavaScript" type="text/JavaScript">
	document.write ("<option>&nbsp;</option>");
		for (j = 0 ; j < <?php echo $totalRows_Recordset1?> ; j++){
			if (menu[1][j] == ""){
				document.write ("<option value="+menu[0][j]+">"+menu[2][j]+"</option>");
			}
		}
	</script>
    </select>
    <input name="btn_dossier" type="button" onClick="ch_div()" value="Nouveau">
	<div id="l11" style="position:absolute; top:0px; left:200px; visibility:hidden">
		<input name="supdoss" type="hidden" value="">
		<input name="btn_supdoss" type="button" id="btn_supdoss" onClick="this.form.supdoss.value=this.form.dossier.value;this.form.submit()">
	</div>
</div>

<div id="l2" name="l2" style="position:absolute; top:10px; left:10px; visibility:hidden">
  Libelle : <input name="lib" type="text">&nbsp;<input name="val1" type="button" onClick="this.form.submit()" value="Ajouter">
</div>
<div id="l3" style="position:absolute; left:131px; top:37px; width:259px; height:427px; z-index:1; visibility:hidden">
</div>
<div id="l5" style="position:absolute; left:131px; top:37px; width:259px; height:427px; z-index:1; visibility:hidden">
  Sous menu<br>
    Libelle : <input name="libsd" type="text"><br>
	Page �r�erencer : <input name="pgsd" type="text">&nbsp;<input name="val2" type="button" onClick="this.form.submit()" value="Ajouter">
</div>
<div id="l4" style="position:absolute; left:300px; top:107px; width:259px; height:427px; z-index:1; visibility:hidden">
</div>
<div id="l6" style="position:absolute; left:300px; top:107px; width:259px; height:427px; z-index:1; visibility:hidden">
  Sous sous menu<br>
    Libelle : <input name="libar" type="text"><br>
	Page �r�erencer : <input name="pgar" type="text">&nbsp;<input name="val2" type="button" onClick="this.form.submit()" value="Ajouter">
</div>
</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
