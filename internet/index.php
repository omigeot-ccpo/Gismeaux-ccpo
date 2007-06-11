<?php
/*Copyright Ville de Meaux 2004-2007
contributeur: jean-luc Dechamp - robert Leguay 
sig@meaux.fr

Ce logiciel est un programme informatique fournissant une interface cartographique WEB communale. 

Ce logiciel est r�gi par la licence CeCILL-C soumise au droit fran�ais et
respectant les principes de diffusion des logiciels libres. Vous pouvez
utiliser, modifier et/ou redistribuer ce programme sous les conditions
de la licence CeCILL-C telle que diffus�e par le CEA, le CNRS et l'INRIA 
sur le site "http://www.cecill.info".

En contrepartie de l'accessibilit� au code source et des droits de copie,
de modification et de redistribution accord�s par cette licence, il n'est
offert aux utilisateurs qu'une garantie limit�e.  Pour les m�mes raisons,
seule une responsabilit� restreinte p�se sur l'auteur du programme,  le
titulaire des droits patrimoniaux et les conc�dants successifs.

A cet �gard  l'attention de l'utilisateur est attir�e sur les risques
associ�s au chargement,  � l'utilisation,  � la modification et/ou au
d�veloppement et � la reproduction du logiciel par l'utilisateur �tant 
donn� sa sp�cificit� de logiciel libre, qui peut le rendre complexe � 
manipuler et qui le r�serve donc � des d�veloppeurs et des professionnels
avertis poss�dant  des connaissances  informatiques approfondies.  Les
utilisateurs sont donc invit�s � charger  et  tester  l'ad�quation  du
logiciel � leurs besoins dans des conditions permettant d'assurer la
s�curit� de leurs syst�mes et ou de leurs donn�es et, plus g�n�ralement, 
� l'utiliser et l'exploiter dans les m�mes conditions de s�curit�. 

Le fait que vous puissiez acc�der � cet en-t�te signifie que vous avez 
pris connaissance de la licence CeCILL-C, et que vous en avez accept� les 
termes.*/
$code_insee='770284';
if (eregi('MSIE', $HTTP_USER_AGENT))
{    
$nav="0";// Internet Explorer 
}
elseif (eregi('Opera', $HTTP_USER_AGENT))
{ 
$nav="1";//opéra
}
else
{
//header("Content-type: image/svg+xml");
$nav="2";//mozilla
}
?>
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<STYLE type="text/css">
<!---@IMPORT url(./ru/styl.css);  --->
</STYLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="SVG, Scalable Vector Graphic, SIG, GIS, Mapserver, Postgis, Postgresql, MapInfo, g�ographie, g�omatique, carte, cartographie, map, XML">
<meta name="description" content="Carte SVG - renseignement d'urbanisme.">
<meta name="Author" content="sig-meaux">
<meta name="Identifier-URL" content="http://www.meaux.carto.fr">
<meta name="Date-Creation-yyyymmdd" content="20070424">
<meta name="Reply-to" content="sig@meaux.fr">
</head>

<body>
<table width="100%"  border="0">
  <tr> 
  <td colspan="2"><img src="../logo/770284.png" ></td>
    <td>&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td width="10%"><?php include('menu.php')?></td>
<?php
    if($nav=="0")
	{
	/*echo "<td align=\"left\">";
	echo "Utilisateur de Internet Explorer 7<br>";
	echo "si vous n'arrivez pas �ouvrir la cartographie en  svg sur ce site<br>";
	echo "<a href=\"http://pilat.free.fr/ie7/index.htm\">Voici la solution</a>";
	echo "</td>";*/
	echo "<td align=\"left\">";
	echo "Utilisateur d'Internet Explorer<br>";
	echo "Pour utiliser la cartographie en  svg sur ce site<br>";
	echo "Installez le plugin abode SvgViewer 3 fourni ci-dessous<br>";
	//echo "Une fois install�allez avec l'explorer windows sur C:\Program Files\Fichiers communs\Adobe\SVG Viewer 3.0\<br>";
	//echo "S�ectionnez et Copiez les 2 fichiers (NPSVG3.dll,NPSVG3.zip) dans C:\Program Files\Opera\program\plugins<br>";
	echo "<a href=\"http://".$HTTP_HOST."/SVGView303.exe\">le plugin abode SvgViewer 3</a>";
	echo "</td>";
	}
	elseif($nav=="1")
	{
	echo "<td align=\"left\">";
	echo "Utilisateur d'opera<br>";
	echo "Pour utiliser la cartographie en  svg sur ce site<br>";
	echo "Installez le plugin abode SvgViewer 3 fourni ci-dessous<br>";
	echo "Une fois install�, allez avec l'explorer windows sur C:\Program Files\Fichiers communs\Adobe\SVG Viewer 3.0\<br>";
	echo "S�lectionnez et Copiez les 2 fichiers (NPSVG3.dll,NPSVG3.zip) dans C:\Program Files\Opera\program\plugins<br>";
	echo "<a href=\"http://".$HTTP_HOST."/SVGView303.exe\">le plugin abode SvgViewer 3</a>";
	echo "</td>";
	}
	else
	{
	echo "<td align=\"left\">";
	echo "Utilisateur de Firefox<br>";
	echo "Pour utiliser la cartographie en  svg sur ce site<br>";
	echo "Installez le plugin abode SvgViewer 6 fourni ci-dessous<br>";
	echo "Une fois install�, allez avec l'explorer windows sur C:\Program Files\Fichiers communs\Adobe\SVG Viewer 6.0\Plugins<br>";
	echo "S�lectionnez et Copiez les 2 fichiers (NPSVG6.dll,NPSVG6.zip) dans C:\Program Files\Mozilla Firefox\plugins<br>";
	echo "Si les 2 fichiers (NPSVG3.dll,NPSVG3.zip) sont pr�sents dans C:\Program Files\Mozilla Firefox\plugins<br>";
	echo "Supprimez les pour �viter un mauvais fonctionnement.<br>";
	echo "Lancez Firefox ,puis tapez \"about:config\" dans la barre d'adresse<br>";
	echo "Dans la case filtre,tapez \"svg\"<br>";
	echo "Dans la colonne \"valeur\" faite un double click gauche sur \"true\" pour passer � \"false\".<br>";
	echo "Relancez Firefox.<br>";
	echo "<a href=\"http://".$HTTP_HOST."/SVGView6.exe\">le plugin abode SvgViewer 6</a>";
	echo "</td>";
	}
?>
  </tr>
</table>

</body>
</html>
