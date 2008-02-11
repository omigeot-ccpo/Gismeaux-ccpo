<?php
define('GIS_ROOT', '../..');
include_once(GIS_ROOT . '/inc/common.php');
gis_init();
check_auth();

echo $_SESSION['user']->login;

$_SESSION['appli']=$_GET["vale"];
if ($_GET["act"]=="descrip"){
  header("Location:./back_office.php");
 }
if ($_GET["act"]=="ajout"){
  $sql="insert into admin_svg.application (libelle_appli,btn_polygo,libelle_btn_polygo,zoom_ouverture,zoom_min,zoom_max,url) values ('".$_GET["lib"]."','".$_GET["bp"]."','".$_GET["lb"]."',".$_GET["zo"].",".$_GET["zn"].",".$_GET["zm"].",'".$_GET["url"]."')";
  pg_exec($pgx,$sql);
  $act="";
 }
if ($_GET["act"]=="supp"){
  $sql="delete from admin_svg.appthe where idapplication=".$_GET["vale"];
  pg_exec($pgx,$sql);
  $sql="delete from admin_svg.application where idapplication=".$_GET["vale"];
  pg_exec($pgx,$sql);
  $act="";     
 }
if($_GET["act"]=="mod")
  {
    $req="UPDATE admin_svg.application SET libelle_appli='".$_GET["lib"]."',btn_polygo='".$_GET["bp"]."',libelle_btn_polygo='".$_GET["lb"]."',zoom_ouverture=".$_GET["zo"].",zoom_min=".$_GET["zn"].",zoom_max=".$_GET["zm"].",url='".$_GET["url"]."' where idapplication=".$_GET["vale"];
    pg_exec($pgx,$req);
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Document sans titre</title>
</head>
<script>
var selection='';
function select_ligne(z,r,ap)
{
document.getElementById('vale').value=ap
selection=z
for(i=0;i<r;i++)
{
document.getElementById('lib'+i).disabled=true;
document.getElementById('url'+i).disabled=true;
document.getElementById('zo'+i).disabled=true;
document.getElementById('zm'+i).disabled=true;
document.getElementById('zn'+i).disabled=true;
document.getElementById('lb'+i).disabled=true;
document.getElementById('bp'+i).disabled=true;
}
document.forms["ges_appli"]['lib'+z].disabled=false;
//document.getElementById('lib'+z).disabled=false;
document.getElementById('url'+z).disabled=false;
document.getElementById('zo'+z).disabled=false;
document.getElementById('zm'+z).disabled=false;
document.getElementById('zn'+z).disabled=false;
document.getElementById('lb'+z).disabled=false;
document.getElementById('bp'+z).disabled=false;
}
function ajout()
{
TxtMessage="";
var reg=new RegExp("(carto.php)","g");
if (reg.test(document.getElementById('ajouturl').value))
 { 
	if (document.getElementById('ajoutlib').value == "")
	TxtMessage = TxtMessage + " - Le nom application.\n";
	if (document.getElementById('ajouturl').value == "")
	TxtMessage = TxtMessage + " - url application.\n";
	if (document.getElementById('ajoutzo').value == "")
	TxtMessage = TxtMessage + " - Le zoom ouverture.\n";
	if (document.getElementById('ajoutzm').value == "")
	TxtMessage = TxtMessage + " - Le zoom max.\n";
	if (document.getElementById('ajoutzn').value == "")
	TxtMessage = TxtMessage + " - Le zoom min.\n";
	}
	if (TxtMessage != "") {
		TxtMessage="Vous n'avez pas saisi les informations suivantes :\n" + TxtMessage;
		alert (TxtMessage);
        document.getElementById('act').value='';
		}
	else {
document.getElementById('act').value='ajout'
document.getElementById('lib').value=document.getElementById('ajoutlib').value
document.getElementById('url').value=document.getElementById('ajouturl').value
if(document.getElementById('ajoutzo').value=="")
{
document.getElementById('zo').value='null'
}
else
{
document.getElementById('zo').value=document.getElementById('ajoutzo').value
}
if(document.getElementById('ajoutzm').value=="")
{
document.getElementById('zm').value='null'
}
else
{
document.getElementById('zm').value=document.getElementById('ajoutzm').value
}
if(document.getElementById('ajoutzn').value=="")
{
document.getElementById('zn').value='null'
}
else
{
document.getElementById('zn').value=document.getElementById('ajoutzn').value
}
document.getElementById('lb').value=document.getElementById('ajoutlb').value
document.getElementById('bp').value=document.getElementById('ajoutbp').value
}
}
function supp()
{
 if (confirm("Etes-vous sur de vouloir supprimer l'application?")) { 
 document.getElementById('act').value='supp';
 //alert(document.getElementById('act').value)
           document.forms["ges_appli"].submit();
       }
	   else
	   {
	   document.getElementById('act').value='';
	   }
}
function attt()
{
var reg=new RegExp("(carto.php)","g");
if (reg.test(document.getElementById('url'+selection).value))
 { 
 document.getElementById('act').value='descrip';
 //alert(document.getElementById('act').value)
           document.forms["ges_appli"].submit();
       }
	   else
	   {
	   alert("Cette application n'est pas gérer par la cartographie");
	   }
}
function modif()
{
 TxtMessage="";
	
	if (document.getElementById('lib'+selection).value == "")
	TxtMessage = TxtMessage + " - Le nom application.\n";
	if (document.getElementById('url'+selection).value == "")
	TxtMessage = TxtMessage + " - url application.\n";
	if (document.getElementById('zo'+selection).value == "")
	TxtMessage = TxtMessage + " - Le zoom ouverture.\n";
	if (document.getElementById('zm'+selection).value == "")
	TxtMessage = TxtMessage + " - Le zoom max.\n";
	if (document.getElementById('zn'+selection).value == "")
	TxtMessage = TxtMessage + " - Le zoom min.\n";
	if (TxtMessage != "") {
		TxtMessage="Vous n'avez pas saisi les informations suivantes :\n" + TxtMessage;
		alert (TxtMessage);
        document.getElementById('act').value='';
		}
	else {
		document.getElementById('act').value='mod';
document.getElementById('lib').value=document.getElementById('lib'+selection).value
document.getElementById('url').value=document.getElementById('url'+selection).value
document.getElementById('zo').value=document.getElementById('zo'+selection).value
document.getElementById('zm').value=document.getElementById('zm'+selection).value
document.getElementById('zn').value=document.getElementById('zn'+selection).value
document.getElementById('lb').value=document.getElementById('lb'+selection).value
document.getElementById('bp').value=document.getElementById('bp'+selection).value
           document.forms["ges_appli"].submit();
		}
}
function sur(z)
{
document.getElementById(z).style.background="#FF0000";
}
function hors(z)
{
document.getElementById(z).style.background="#FFFFFF";
}
</script>
<table border="0" cellspacing="0" cellpadding="0" align="center" >
<tr><td colspan="7" align="center" ><img name="ges" src="./headbackoffice.PNG"/></td><tr>
<tr><td colspan="7" align="center">&nbsp;</td><tr>
<form name="ges_appli" action="index.php" method="get" >
<input type="hidden" id="act" name="act" value="">
<input type="hidden" id="vale" name="vale" value="">
<input type="hidden" name="lib" id="lib" value=""></td>
<input type="hidden" name="url" id="url" value=""></td>
<input type="hidden" name="zo" id="zo" value=""></td>
<input type="hidden" name="zm" id="zm" value=""></td>
<input type="hidden" name="zn" id="zn" value=""></td>
<input type="hidden" name="lb" id="lb" value=""></td>
<input type="hidden" name="bp" id="bp" value=""></td>

<tr align="center">
    <td>Application</td>
    <td>url de l'application</td>
    <td>zoom_ouverture</td>
    <td>zoom_max</td>
    <td>zoom_min</td>
    <td>texte_bouton</td>
    <td>bouton_polygone</td>
  </tr>	
  
<?php
$d="select * from admin_svg.application order by idapplication asc";
$col=$DB->tab_result($d);
for ($z=0;$z<count($col);$z++)
{
echo"  <tr id=\"".$z."\">
    <td><input type=\"text\" name=\"\" value=\"".$col[$z]['libelle_appli']."\" disabled=\"true\" id=\"lib".$z."\"></td>
    <td><input type=\"text\" name=\"\" value=\"".$col[$z]['url']."\" disabled=\"true\"  id=\"url".$z."\"></td>
    <td><input type=\"text\" name=\"\" value=\"".$col[$z]['zoom_ouverture']."\" disabled=\"true\"  id=\"zo".$z."\"></td>
    <td><input type=\"text\" name=\"\" value=\"".$col[$z]['zoom_max']."\" disabled=\"true\"  id=\"zm".$z."\"></td>
    <td><input type=\"text\" name=\"\" value=\"".$col[$z]['zoom_min']."\" disabled=\"true\"  id=\"zn".$z."\"></td>
    <td><input type=\"text\" name=\"\" value=\"".$col[$z]['libelle_btn_polygo']."\" disabled=\"true\"  id=\"lb".$z."\"></td>
    <td><input type=\"text\" name=\"\" value=\"".$col[$z]['btn_polygo']."\" disabled=\"true\"  id=\"bp".$z."\"></td>
    <td><input type=\"radio\" name=\Groupe\" value=\"\" / onclick=\"select_ligne(".$z.",".count($col).",'".$col[$z]['idapplication']."')\"></td>
  </tr>		";	
}
echo"  <tr id=\"ajoutid\" style=\"visibility:hidden\">
    <td><input type=\"text\" name=\"ajoutlib\" id=\"ajoutlib\" value=\"\"></td>
    <td><input type=\"text\" name=\"ajouturl\" id=\"ajouturl\" value=\"\"></td>
    <td><input type=\"text\" name=\"ajoutzo\" id=\"ajoutzo\" value=\"\"></td>
    <td><input type=\"text\" name=\"ajoutzm\" id=\"ajoutzm\" value=\"\"></td>
    <td><input type=\"text\" name=\"ajoutzn\" id=\"ajoutzn\" value=\"\"></td>
    <td><input type=\"text\" name=\"ajoutlb\" id=\"ajoutlb\" value=\"\"></td>
    <td><input type=\"text\" name=\"ajoutbp\" id=\"ajoutbp\" value=\"\"></td>
    <td ><input type=\"button\" value=\"Valider\" onclick=\"ajout();submit();\"></td>
  </tr>		";
?>


<?php
echo '<tr><td colspan="7" align="center"><input type="button" value="Description" onclick="attt();"><input type="button" value="Modifier" onclick="modif()">';
echo '<input type="button" value="Supprimer" onclick="supp();">';
echo '<input type="button" value="Insérer" onclick="document.getElementById(\'ajoutid\').style.visibility=\'visible\';"></td></tr>';
?>
</form>
</html>
