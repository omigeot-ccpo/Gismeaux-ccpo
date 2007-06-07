<?php
//$session = file( "http://mapsig/intranet/php.cfm" );
//if($session="")
//{
$nommachine=gethostbyaddr($_SERVER['REMOTE_ADDR']);
switch($nommachine)
{
case "wxpsig02.meaux.priv":
$ident="jldecham";
break;
case "mairie-fecaf2f5":
$ident="urba6";
break;
case "wxpurba08.meaux.priv":
$ident="urba2";
break;
case "wxpurba05.meaux.priv":
$ident="urba5";
break;
case "wxpurba07.meaux.priv":
$ident="urba7";
break;
case "sig1.meaux.priv":
$ident="sig1";
break;
case "wxpgip02.meaux.priv":
$ident="gpv02";
break;
case "wxpgpv01.meaux.priv":
$ident="gpv";
break;
case "wxpurba04.meaux.priv":
$ident="urba1";
break;
case "wxpurba02.meaux.priv":
$ident="ggameiro";
break;
}
//}
//else
//{
//$ident=$session;
//}
$connexion = pg_connect("host=localhost dbname=meaux user=postgres");
if($HTTP_REFERER=="")
{
pg_query($connexion,"INSERT INTO general.stat(ident,lien_prec,lien_open) values(
'$ident','carte','$REQUEST_URI')");
}
else
{
pg_query($connexion,"INSERT INTO general.stat(ident,lien_prec,lien_open) values(
'$ident','$HTTP_REFERER','$REQUEST_URI')");
}
pg_close($connexion);



?>