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
?>
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
