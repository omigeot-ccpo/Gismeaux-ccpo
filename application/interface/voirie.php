<?php
include("../connexion/deb.php");
$xm= $_GET["x"]+  $_GET["xini"];
$xma=($_GET["x"]+ $_GET["lar"]) +  $_GET["xini"];
$yma=  $_GET["yini"] -  $_GET["y"];
$ym=  $_GET["yini"] - ($_GET["y"] +  $_GET["hau"]);
$requete="select texte,texte2,texte3,texte4,texte5,texte6,texte7,texte8,texte9,texte10 from cadastre.nomvoie where the_geom && box'($xm,$ym,$xma,$yma)' group by texte,texte2,texte3,texte4,texte5,texte6,texte7,texte8,texte9,texte10";
$col=tab_result($pgx,$requete);
for ($z=0;$z<count($col);$z++)
{
echo $col[$z]['texte']." ".$col[$z]['texte2']." ".$col[$z]['texte3']." ".$col[$z]['texte4']." ".$col[$z]['texte5']." ".$col[$z]['texte6']." ".$col[$z]['texte7']." ".$col[$z]['texte8']." ".$col[$z]['texte9']." ".$col[$z]['texte10']." "."<br>";
}
?>
