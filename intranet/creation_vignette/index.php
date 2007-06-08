<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Document sans titre</title>
</head>

<body>
<?php
include("../connexion/deb.php");
if($type=="")
{
echo "<form action=\"index.php\" method=\"post\">";
echo "<p>Creation des vignettes d'une aglomération ou d'une commune ?:";
echo "<select name=\"type\">";
echo "<option value=\"A\">Aglomération</option>";
echo "<option value=\"C\">Commune</option>";
echo "</select>";
echo "</p>";
echo "<p>Possédez vous Les données Bd_topo de l'aglomération ou commune sur votre base?:";
echo "<select name=\"bd\">";
echo "<option value=\"n\">non</option>";
echo "<option value=\"o\">oui</option>";
echo "</select>";
echo "</p>";
echo "<input name=\"Suivant\" type=\"button\" value=\"Suivant\" onClick=\"submit()\">";
}
else
{
echo "<form action=\"crea_fond_carte_svg.php?type=".$type."&bd=".$bd."\" method=\"post\">";
echo "<p>Votre choix ?:";
echo "<select name=\"codeinsee\">";
if($type=="A")
{
$sql="select nom,idcommune from admin_svg.commune where commune.idcommune like '%000'";
}
else
{
$sql="select distinct nom,idcommune from admin_svg.commune";
}
$res=tab_result($pgx,$sql);
for ($z=0;$z<count($res);$z++)
{
echo "<option value=\"".$res[$z]['idcommune']."\">".$res[$z]['nom']."</option>";
}
echo "</select>";
echo "</p>";

echo "<input name=\"Suivant\" type=\"button\" value=\"Suivant\" onClick=\"submit()\">";
}
?>

</body>
</html>
