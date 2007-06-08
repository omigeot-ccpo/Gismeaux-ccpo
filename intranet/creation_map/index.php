<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Création du map</title>
</head>

<body>
<?php
include("../connexion/deb.php");

echo "<form action=\"generemap.php\" method=\"post\">";
echo "<p>Choix de l'application ?:";
echo "<select name=\"appli\">";
$libappli="select idapplication,application.libelle_appli from admin_svg.application";
$res=tab_result($pgx,$libappli);
for ($z=0;$z<count($res);$z++)
{
echo "<option value=\"".$res[$z]['idapplication']."\">".$res[$z]['libelle_appli']."</option>";
}
echo "</select>";
echo "</p>";

echo "<input name=\"Generer\" type=\"button\" value=\"Generer\" onClick=\"submit()\">";

?>

</body>
</html>
