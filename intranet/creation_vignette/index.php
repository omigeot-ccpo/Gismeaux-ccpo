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
echo "<p>Creation des vignettes d'une aglom�ation ou d'une commune ?:";
echo "<select name=\"type\">";
echo "<option value=\"A\">Aglom�ation</option>";
echo "<option value=\"C\">Commune</option>";
echo "</select>";
echo "</p>";
echo "<p>Poss�ez vous Les donn�s Bd_topo de l'aglom�ation ou commune sur votre base?:";
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
