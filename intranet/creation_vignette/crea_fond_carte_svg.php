<?php
include("../connexion/deb.php");
//$code_insee=771000;
if($type=="A")
	{
$insee=substr($codeinsee,0,3);
}
else
{
$insee=$codeinsee;
}

$sql="select idcommune as code_insee,nom from admin_svg.commune where commune.idcommune like '".$insee."%'";
		$res=tab_result($pgx,$sql);
for ($z=0;$z<count($res);$z++)
{		
		
$req2="select commune.xmi as xini,commune.xma as xmax, commune.yma as yini ,commune.ymi as ymin from admin_svg.commune where commune.idcommune like '".$res[$z]['code_insee']."'";
		$vu=tab_result($pgx,$req2);
$xm=$vu[0][xini];
$xma=$vu[0][xmax];
$yma=$vu[0][yini];
$ym=$vu[0][ymin];

$x1=$xm;
$y1=$ym;
$x2=$xma;
$y2=$yma;
$lar=$xma-$xm;
$hau=$yma-$ym;

if($hau*(302/254)>$lar)
{
$largeur=254*($lar/$hau);
$hauteur=254;
}
else
{
$largeur=302;
$hauteur=302*($hau/$lar);
}

$serv=$_SERVER["SERVER_NAME"];
$sql="select distinct code_insee from cadastre.batiment where code_insee=".$res[$z]['code_insee'];
$result=tab_result($pgx,$sql);
if(count($result)>0)
{
$laybat="batiment";
}
else
{
$laybat="bati";
}
if(substr($res[$z]['code_insee'], -3)=='000')
{
$code=substr($res[$z]['code_insee'],0,3);
}
else
{
$code=$res[$z]['code_insee'];
}
if($bd=="o")
{
$url="http://".$serv."/cgi-bin/mapserv?map=/home/sig/intranet/capm/vignette.map&layer=commune&layer=".$laybat."&layer=surface_eau&layer=troncon_route&insee=".$code."&ID=".$res[$z]['code_insee']."&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&mapsize=".$largeur."%20".$hauteur;
}
else
{
$url="http://".$serv."/cgi-bin/mapserv?map=/home/sig/intranet/capm/vignette.map&layer=commune&layer=batiment&insee=".$code."&ID=".$res[$z]['code_insee']."&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&mapsize=".$largeur."%20".$hauteur;
}
$contenu=file($url);
       		while (list($ligne,$cont)=each($contenu)){
			$numligne[$ligne]=$cont;
		}
		$texte=$numligne[1];
		$image=explode('/',$texte);
		
		$conte1=explode('.',$image[4]);
		$image=$conte1[0];
//echo $lar." ".$hau." ".$largeur." ".$hauteur;
if(@rename("/home/sig/intranet/interface/communes/tt".$res[$z]['code_insee'].".jpg","/home/sig/intranet/interface/communes/".$res[$z]['code_insee'].".JPG"))
{
echo $res[$z]['code_insee'].",".$res[$z]['nom']." ok<br>";
}
}
echo "<a href=\"./index.php\" target=\"_parent\">Retour</a>";
//echo $code_insee;
?>