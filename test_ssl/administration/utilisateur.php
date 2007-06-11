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
session_start();
if (! $PHP_AUTH_USER || ! $PHP_AUTH_PW){
	header("WWW-authenticate: Basic realm=\"Veuillez vous identifier\"");
	header('HTTP/1.0 401 Unauthorized');
	//header('status: 401 Unauthorized');
}else{
$ora=pg_connect("dbname=meaux host=localhost user=postgres");
if ($act=="sup"){
	$quer="delete from admin_svg.utilisateur where idutilisateur='".$ide."'";
	pg_exec($ora,$quer);
}elseif ($act=="ins"){
	$quer="insert into admin_svg.utilisateur (idutilisateur,login,psw,droit,idcommune) values(nextval('general.util2'),'".$log."','".$psw."','".$droitu."','".$commune."')";
	pg_exec($ora,$quer);
}elseif ($act=="mod"){
	$quer="update admin_svg.utilisateur set login='".$log."', psw='".$psw."', droit='".$droitu."', idcommune='".$commune."' where idutilisateur='".$ide."'";
	pg_exec($ora,$quer);
}
if ($PHP_AUTH_USER=='sig1'){
   $tab=pg_exec($ora,"select * from admin_svg.utilisateur order by login");
}else{
   $tab=pg_exec($ora,"select * from admin_svg.utilisateur where idcommune like '".substr($_SESSION["code_insee"],0,3)."%' order by login");
}
$list_commune=pg_exec($ora,"select idcommune,nom from admin_svg.commune where idcommune like '".substr($_SESSION["code_insee"],0,3)."%' order by nom");
$num=pg_numrows($tab);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Administration</title>
<script language="JavaScript" type="text/JavaScript">
function neo_util(){
	document.f1.act.value='ins';
	document.getElementById("Layer1").style.visibility = "visible";
}
function neo_aba(){
	document.getElementById("Layer1").style.visibility = "hidden";
}
function getRandomNum(lbound, ubound) {
    return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
}
function getRandomChar(number, lower, upper, other, extra) {
    var numberChars = "0123456789";
    var lowerChars = "abcdefghjkmnopqrstuvwxyz";
    var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var otherChars = "`~!@#$%^&*()-_=+[{]}\\|;:'\",<.>/? ";
    var charSet = extra;
    if (number == true)
        charSet += numberChars;
    if (lower == true)
        charSet += lowerChars;
    if (upper == true)
        charSet += upperChars;
    if (other == true)
        charSet += otherChars;
    return charSet.charAt(getRandomNum(0, charSet.length));
}
function getPassword(length, extraChars, firstNumber, firstLower, firstUpper, firstOther,
    latterNumber, latterLower, latterUpper, latterOther) {
    var rc = "";
    if (length > 0)
        rc = rc + getRandomChar(firstNumber, firstLower, firstUpper, firstOther, extraChars);
    for (var idx = 1; idx < length; ++idx) {
        rc = rc + getRandomChar(latterNumber, latterLower, latterUpper, latterOther, extraChars);
    }
    return rc;
}
</script>
<style type="text/css">
<!--
.layer1 {
	font-family: Arial, Helvetica, sans-serif; position:absolute;
	left:151px;	top:40px; width:269px; height:129px;
	visibility:hidden; z-index:1; background-color: #FFCC99;
}
-->
</style>
</head>

<body>
<form action="utilisateur.php" method="get" name="f1">
<input name="cre_util" type="button" onClick="neo_util()" value="Nouveau">
<input name="act" type="hidden" value=""><input name="ide" type="hidden" value="">
<div id="Layer1" class="layer1" >
	<table><tr><td>Login</td><td><input name="log" type="text"></td></tr>
	<tr><td>Mot de passe</td><td><input name="psw" type="text">&nbsp<input type=button value="..." onclick="psw.value=getPassword(8, false, true, true, true, false,
true, true, true, false);"></td></tr>
	<tr><td>Commune</td>
	<!---<td><input name="commune" type="text"></td>--->
	<td><select name="commune">
<?php for ($o=0; $o<pg_numrows($list_commune); $o++){
	$lcom[$o]=pg_fetch_array($list_commune,$o);
	echo "<option value=".$lcom[$o]['idcommune'].">".$lcom[$o]['nom']."</option>";
}?>
	</select></td>
	</tr>
    <tr><td>Droit</td><td><input name="droitu" type="text"></td></tr></table>
	<input name="ins_util" type="button" value="Insï¿½er" onclick="document.f1.submit();">
	<input name="anu_util" type="button" value="Annuler" onclick="neo_aba()">
</div>
<table width="200" height="15" border="1">
  <tr>
    <th scope="col">Identifiant</th>
    <th scope="col">Login</th>
    <th scope="col">Commune</th>
    <th scope="col">Droit</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
  </tr>
<?php for ($i=0; $i<$num; $i++){
$nh++;
$vh=40+($nh*27);
$gg[$i]=pg_fetch_array($tab,$i);?>
  <tr>
    <td><?php echo $gg[$i]['idutilisateur']; ?></td>
    <td><?php echo $gg[$i]['login']; ?></td>
    <td><?php echo $gg[$i]['idcommune']; ?></td>
    <td><?php echo $gg[$i]['droit']; ?></td>
    <td><?php echo '<input name="mod_util" type="button" onclick="ide.value=\''.$gg[$i]['idutilisateur'].'\';log.value=\''.$gg[$i]['login'].'\';droitu.value=\''.$gg[$i]['droit'].'\';commune.value=\''.$gg[$i]['idcommune'].'\';act.value=\'mod\';document.getElementById(\'Layer1\').style.top = \''.$vh.'\' ;document.getElementById(\'Layer1\').style.visibility = \'visible\';" value="Modifier">'; ?></td>
    <td><?php echo '<input name="sup_util" type="button" onclick="act.value=\'sup\';ide.value=\''.$gg[$i]['idutilisateur'].'\';document.f1.submit();" value="Supprimer">'; ?></td>
    <td><?php echo '<input name="aut_util" type="button" onclick="window.open(\'application.php?ident='.$gg[$i]['idutilisateur'].'\')" value="Autoriser">'; ?></td>
<?php }
 ?>
</table>
</form>

</body>
</html>
<?php }
 ?>
