<?php
$Connection = mysql_connect("localhost","root","");
mysql_select_db(plu, $Connection);
$sql = "SELECT  ccosec,dnupla 
FROM parcel 
WHERE dnuvoi='$nter' AND dindic='$cpter' AND ccoriv LIKE '$code'";

$result = mysql_query($sql) ;

while($row = mysql_fetch_row($result))
	{
	$section1=$row[0];
	$parcelle1=$row[1];
	
}
mysql_close;
if($parcelle1=="")
{
//print("aucune parcelle trouvée à cette adresse, nous vous conseillons de faire une recherche sur la carte. Cliquez <a href='../svg/'> ici</a>"); 
print("aucune parcelle trouvée à cette adresse.<a href='ru.php'>retour</a>");
}
else
{
header("Location: ./ru.php?parcelle1=$parcelle1&section1=$section1");
}
?> 
