<?php
$code_insee='770284';
if (eregi('MSIE', $HTTP_USER_AGENT))
{    
$nav="0";// Internet Explorer 
}
elseif (eregi('Opera', $HTTP_USER_AGENT))
{ 
$nav="1";//opÃ©ra
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
<meta name="keywords" content="SVG, Scalable Vector Graphic, SIG, GIS, Mapserver, Postgis, Postgresql, MapInfo, géographie, géomatique, carte, cartographie, map, XML">
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
	echo "si vous n'arrivez pas à ouvrir la cartographie en  svg sur ce site<br>";
	echo "<a href=\"http://pilat.free.fr/ie7/index.htm\">Voici la solution</a>";
	echo "</td>";*/
	echo "<td align=\"left\">";
	echo "Utilisateur d'Internet Explorer<br>";
	echo "Pour utiliser la cartographie en  svg sur ce site<br>";
	echo "Installez le plugin abode SvgViewer 3 fourni ci-dessous<br>";
	//echo "Une fois installé,allez avec l'explorer windows sur C:\Program Files\Fichiers communs\Adobe\SVG Viewer 3.0\<br>";
	//echo "Sélectionnez et Copiez les 2 fichiers (NPSVG3.dll,NPSVG3.zip) dans C:\Program Files\Opera\program\plugins<br>";
	echo "<a href=\"http://".$HTTP_HOST."/SVGView303.exe\">le plugin abode SvgViewer 3</a>";
	echo "</td>";
	}
	elseif($nav=="1")
	{
	echo "<td align=\"left\">";
	echo "Utilisateur d'opera<br>";
	echo "Pour utiliser la cartographie en  svg sur ce site<br>";
	echo "Installez le plugin abode SvgViewer 3 fourni ci-dessous<br>";
	echo "Une fois installé,allez avec l'explorer windows sur C:\Program Files\Fichiers communs\Adobe\SVG Viewer 3.0\<br>";
	echo "Sélectionnez et Copiez les 2 fichiers (NPSVG3.dll,NPSVG3.zip) dans C:\Program Files\Opera\program\plugins<br>";
	echo "<a href=\"http://".$HTTP_HOST."/SVGView303.exe\">le plugin abode SvgViewer 3</a>";
	echo "</td>";
	}
	else
	{
	echo "<td align=\"left\">";
	echo "Utilisateur de Firefox<br>";
	echo "Pour utiliser la cartographie en  svg sur ce site<br>";
	echo "Installez le plugin abode SvgViewer 6 fourni ci-dessous<br>";
	echo "Une fois installé,allez avec l'explorer windows sur C:\Program Files\Fichiers communs\Adobe\SVG Viewer 6.0\Plugins<br>";
	echo "Sélectionnez et Copiez les 2 fichiers (NPSVG6.dll,NPSVG6.zip) dans C:\Program Files\Mozilla Firefox\plugins<br>";
	echo "Si les 2 fichiers (NPSVG3.dll,NPSVG3.zip) sont présents dans C:\Program Files\Mozilla Firefox\plugins<br>";
	echo "Supprimez les pour éviter un mauvais fonctionnement.<br>";
	echo "Lancez Firefox ,puis tapez \"about:config\" dans la barre d'adresse<br>";
	echo "Dans la case filtre,tapez \"svg\"<br>";
	echo "Dans la colonne \"valeur\" faite un double click gauche sur \"true\" pour passer à \"false\".<br>";
	echo "Relancez Firefox.<br>";
	echo "<a href=\"http://".$HTTP_HOST."/SVGView6.exe\">le plugin abode SvgViewer 6</a>";
	echo "</td>";
	}
?>
  </tr>
</table>

</body>
</html>
