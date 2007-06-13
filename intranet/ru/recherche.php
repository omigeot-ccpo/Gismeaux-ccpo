<?php
$nter=substr('000'.$nter,-4);
print("<form name='form1' method='post' action='rech_adresse.php'>
<table><tr><td colspan='4'>Sélectionner la rue souhaitée:</td><tr>
<td valign='top'>
<input name='nter' type='text' id='nter' size='4' maxlength='4' value='$nter' valign='top'> &nbsp;</td>
<td valign='top'>
<input name='cpter' type='text' id='cpter' size='4' maxlength='4' value='$cpter'> &nbsp;</td>
<td valign='top'>
<select name='code' size='4'>");
 
  
$Connection = mysql_connect("localhost","root","");
mysql_select_db(plu, $Connection);
$sql = "SELECT code_voie,nom_voie
FROM voies 
WHERE nom_voie 
LIKE '%$libelleter%' ";

$result = mysql_query($sql) ;
$n=1;
while($row = mysql_fetch_row($result))
	{
	$code=$row[0];
	$nom=$row[1];
if($n==1)
{
	print("<option value='$code' selected>$nom</option>");
	$n++;
}
else
{
print("<option value='$code'>$nom</option>");
}
}
print("</select></td>");
mysql_close;
if($code=="")
{

die('<meta http-equiv="refresh" content="0;url=./message.php">');
}

print("<td valign='top'><input type='submit' name='Submit' value='Envoyer'></td></tr>");

print("</table></form>");
//}

?>
