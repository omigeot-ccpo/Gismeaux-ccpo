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
header("Content-type: image/svg+xml");
if($SERVER_PORT!=443)
{
ini_set('session.gc_maxlifetime', 3600);
session_start();
}
else
{
$_SESSION['xini']=& $_GET['xini'];
$_SESSION['yini']=& $_GET['yini'];
$_SESSION['code_insee']=& $code_insee;
}
$_SESSION['zoommm'] =& $_GET['zoom'];
$_SESSION['boitex'] =& $_GET['x'];
$_SESSION['boitey'] =& $_GET['y'];
$_SESSION['boitelarg'] =& $_GET['lar'];
$_SESSION['boitehaut'] =& $_GET['hau'];
include("../connexion/deb.php");
$placeid=explode(",",$_GET['parce']);
//$str2=$_SERVER['HTTP_RAW_POST_DATA'];
$xm=$_GET['x'] + $_GET['xini'];
$xma=($_GET['x']+$_GET['lar']) + $_GET['xini'];
$yma= $_GET['yini'] - $_GET['y'];
$ym= $_GET['yini'] - ($_GET['y']+$_GET['hau']);
$str1 ="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>";
$str1 .="<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.0//EN\" \"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd\">";
$str1 .="<svg  x='0' y='0' width='620' height='620' viewBox='0 0 620 620'>\n";
$str1 .="<svg  x='0' y='0' width='620' height='520' viewBox='".$_GET['x']." ".$_GET['y']." ".$_GET['lar']." ".$_GET['hau']."' xmlns:sodipodi='http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd' xmlns:inkscape='http://www.inkscape.org/namespaces/inkscape' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns='http://www.w3.org/2000/svg'>\n";
$str1 .="<defs>";
$str1 .="<marker id='debut_mesure' markerWidth='5' markerHeight='10' orient='auto' refX='0' refY='5'><path pointer-events='none' fill='none' stroke='red' d='M 5 7.5 0 5 5 2.5' /></marker><marker id='fin_mesure' markerWidth='5' markerHeight='10' orient='auto' refX='5' refY='5'><path pointer-events='none' fill='none' stroke='red' d='M 0 7.5 5 5 0 2.5' /></marker>";
$textq="";
	if ($_GET['raster']!=''){
	$raster=str_replace("chr(224)","à",$_GET['raster']);
$raster=str_replace("chr(233)","é",$raster);
$raster=str_replace("chr(232)","è",$raster);
$raster=str_replace("chr(234)","ê",$raster);
$raster=str_replace("chr(226)","â",$raster);
$raster=str_replace("chr(231)","ç",$raster);
$raster=str_replace("chr(244)","ô",$raster);
$raster=str_replace("chr(238)","î",$raster);
$raster=str_replace("chr(251)","û",$raster);
$raster=str_replace("chr(95)","_",$raster);
	$rastx=explode(";",$raster);
	$raster="";
	for($i=0;$i<count($rastx);$i++)
	{
	$ras=explode(".",$rastx[$i]);
	$raster.=$ras[1].";";
	}
	$raster=substr($raster,0,strlen($raster)-1);
	$raster=str_replace(";","&layer=",$raster);
	$erreur=error_reporting ();
	error_reporting (1);
	$sql_app="select supp_chr_spec(libelle_appli) as libelle_appli from admin_svg.application where idapplication=".$_SESSION['appli'];
$app=tab_result($pgx,$sql_app);
$application=$app[0]['libelle_appli'];
$application=str_replace(" ","_",$application);
		//$serv="126.2.0.101"; //mettre pays pour slinsig01 ou l'ip pour le serveur de test
		//$serv=$HTTP_HOST;
		//$serv="pays";
		//$serv=$_SERVER["SERVER_ADDR"];
		$serv=$_SERVER["SERVER_NAME"];
if(substr($_SESSION['code_insee'], -3)=='000')
	{
				
$url="http://".$serv."/cgi-bin/mapserv?map=/home/sig/intranet/capm/".$application.".map&map_imagetype=jpeg&insee=".substr($_SESSION['code_insee'],0,3)."&layer=".$raster."&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&mapsize=1240%201040&parce=('')";
}
else
{
$url="http://".$serv."/cgi-bin/mapserv?map=/home/sig/intranet/capm/".$application.".map&map_imagetype=jpeg&insee=".$_SESSION['code_insee']."&layer=".$raster."&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&mapsize=1240%201040&parce=('')";
}
		$contenu=file($url);
       		while (list($ligne,$cont)=each($contenu)){
			$numligne[$ligne]=$cont;
		}
		$texte=$numligne[1];
		$image=explode('/',$texte);
		$fp = fopen("../tmp/".$_GET['nom'].".tmp", "w");
		fwrite($fp,$image[4]);
		fclose ($fp);
		$conte1=explode('.',$image[4]);
		$image=$conte1[0];
	error_reporting ($erreur);
		$str1 .="<pattern id=\"imag\" height=\"".$_GET['hau']."\" width=\"".$_GET['lar']."\" patternUnits=\"userSpaceOnUse\" inkscape:collect=\"always\" patternTransform=\"translate(".$_GET['x'].",".$_GET['y'].")\">";
$str1 .="<image height='".$_GET['hau']."'  width='".$_GET['lar']."' sodipodi:absref='c:\\carte\\".$image.".jpg' xlink:href='c:\\carte\\".$image.".jpg' /></pattern>";
		$textq.="<g>\n";
		$textq.="<rect y='".$_GET['y']."' x='".$_GET['x']."' height='".$_GET['hau']."' width='".$_GET['lar']."' style='fill:url(#imag);stroke:none'/>";
		$textq.="</g>\n";
}
echo $_GET['svg'];
if($_GET['svg']!="")
{
$rast=$_GET['svg'];
/*if($nav=="2")
{
$rast=utf8_decode($svg);
}*/
if($_GET['nav']!="0")
{
$rast=str_replace("chr(224)","à",$_GET['svg']);
$rast=str_replace("chr(233)","é",$rast);
$rast=str_replace("chr(232)","è",$rast);
$rast=str_replace("chr(234)","ê",$rast);
$rast=str_replace("chr(226)","â",$rast);
$rast=str_replace("chr(231)","ç",$rast);
$rast=str_replace("chr(244)","ô",$rast);
$rast=str_replace("chr(238)","î",$rast);
$rast=str_replace("chr(251)","û",$rast);
$rast=str_replace("chr(60)","<",$rast);
$rast=str_replace("chr(62)",">",$rast);
$rast=str_replace("chr(63)","?",$rast);
$rast=str_replace("chr(34)",'"',$rast);
$rast=str_replace("chr(39)","'",$rast);
$rast=str_replace("chr(35)","#",$rast);
$rast=str_replace("chr(33)","!",$rast);
$rast=str_replace("chr(95)","_",$rast);
}
$ras=explode(",",$rast);
for($i=0;$i<count($ras);$i++)
{
	$ra=explode(".",$ras[$i]);
	$rast[$i]=$ra[1];
	$id[$i]=$ra[0];
}	
//$rast="'".$rast."'";
//$rast=str_replace(",","','",$rast);
$sql="select appthe.idappthe,theme.schema,theme.tabl,col_theme.colonn,col_theme.valeur_mini,col_theme.valeur_maxi,col_theme.valeur_texte,sinul(col_theme.fill, style.fill) as fill,sinul(col_theme.stroke_rgb, style.stroke_rgb) as stroke_rgb,sinul(col_theme.symbole,style.symbole) as symbole,sinul(col_theme.opacity,style.opacity) as opacity,sinul(col_theme.font_familly,style.font_familly) as font_familly,sinul(col_theme.font_size,style.font_size) as font_size,appthe.mouseover,appthe.mouseout,appthe.click,appthe.idtheme,theme.partiel,sinul(col_theme.stroke_width,style.stroke_width) as stroke_width from admin_svg.appthe join admin_svg.theme on appthe.idtheme=theme.idtheme left outer join  admin_svg.col_theme on appthe.idappthe=col_theme.idappthe left outer join  admin_svg.style on appthe.idtheme=style.idtheme where appthe.idapplication=".$_SESSION['appli']." group by appthe.idappthe,theme.schema,theme.tabl,col_theme.colonn,col_theme.valeur_mini,col_theme.valeur_maxi,col_theme.valeur_texte,sinul(col_theme.fill, style.fill),sinul(col_theme.stroke_rgb, style.stroke_rgb),sinul(col_theme.symbole,style.symbole),sinul(col_theme.opacity,style.opacity),sinul(col_theme.font_familly,style.font_familly),sinul(col_theme.font_size,style.font_size),appthe.mouseover,appthe.mouseout,appthe.click,appthe.idtheme,theme.partiel,sinul(col_theme.stroke_width,style.stroke_width) order by appthe.idappthe desc";
$cou=tab_result($pgx,$sql);
//$essai="";
for ($l=0;$l<count($cou);$l++){
if(in_array($cou[$l]['idappthe'],$id))
{
//$essai.=" ".$cou[$l]['idappthe'];
$rotation='false';
$d="select * from admin_svg.col_sel where idtheme='".$cou[$l]['idtheme']."'";
		$col=tab_result($pgx,$d);
		$f="select ";
		$geometrie="";
		for ($z=0;$z<count($col);$z++){
		if($col[$z]['nom_as']=='rotation')
		{
		$rotation='true';
		}
			if($col[$z]['nom_as']=='geom')
			{
			$geometrie=$col[$z]['appel'];
			$f.="assvg(Translate(".$col[$z]['appel'].",-".$_SESSION['xini'].",-".$_SESSION['yini'].",0),1,6) as ".$col[$z]['nom_as'].",";}
			else
			{
			$f.="(".$col[$z]['appel'].") as ".$col[$z]['nom_as'].",";
			}
		}
		$f=substr($f,0,-1)." from ".$cou[$l]['schema'].".".$cou[$l]['tabl'];
		//if ($cou[0]['partiel']==1){
            if (substr($_SESSION['code_insee'], -3) == "000" || $cou[$l]['schema']=="bd_topo"){
                $f.=" where ".$geometrie." && box'(".$xm.",".$ym.",".$xma.",".$yma.")'";
            }else{
                $f.=" where (code_insee like '".$_SESSION['code_insee']."%' or code_insee is null) and ".$geometrie." && box'(".$xm.",".$ym.",".$xma.",".$yma.")'";
            }
       /* }
		else
		{
		 if (substr($_SESSION['code_insee'], -3) == "000"){
                $f.=" where code_insee like '".substr($_SESSION['code_insee'],0,3)."%'";
            }else{
                $f.=" where code_insee = '".$_SESSION['code_insee']."'";
            }
		}*/
		
		$j="select * from admin_svg.col_where where idtheme='".$cou[$l]['idtheme']."'";
		$whr=tab_result($pgx,$j);
		if (count($whr)>0){
			/*if ($cou[0]['partiel']==1)
			{
				$f.=" and ";
			}
			else
			{
				if (substr($_SESSION['code_insee'], -3) == "000"){
                    $f.=" where code_insee like '".substr($_SESSION['code_insee'],0,3)."%' and ";
                }else{
                    $f.=" where code_insee = '".$_SESSION['code_insee']."' and ";
                }
			}*/
			
			
			$f.=" and ".str_replace("VALEUR","'".$_SESSION['code_insee']."'",$whr[0]['clause']);
			}
		$type_geo="";	
		if($geometrie!="")
		{
		$dd="select distinct geometrytype(".$geometrie.") as geome from ".$cou[$l]['schema'].".".$cou[$l]['tabl'];
		$geo=tab_result($pgx,$dd);
		if($geo[0]['geome']=="POINT" AND $cou[$l]['symbole']!="") //si symbole
		{
			$type_geo="symbole";
		}
		else if($geo[0]['geome']=="POINT") //si texte
		{
		$type_geo="texte";
		}
		}
	 
		if($cou[$l]['valeur_mini']!='')
		{
			/*if($cou[$l]['partiel']!=1)
			{
			$f.=" where ".$cou[$l]['colonn'].">=".$cou[$l]['valeur_mini']." and ".$cou[$l]['colonn']."<=".$cou[$l]['valeur_maxi'];
			}
			else
			{*/
			$f.=" and ".$cou[$l]['colonn'].">=".$cou[$l]['valeur_mini']." and ".$cou[$l]['colonn']."<=".$cou[$l]['valeur_maxi'];
			//}
		}
		else
		{
			if($cou[$l]['colonn']!='')
			{
			/*if($cou[$l]['partiel']!=1)
			{
			$f.=" where ".$cou[$l]['colonn']."='".$cou[$l]['valeur_texte']."'";
			}
			else
			{*/
			$f.=" and ".$cou[$l]['colonn']."='".$cou[$l]['valeur_texte']."'";
			//}
			}
		}
		$res=tab_result($pgx,$f);
$styl="";
if($cou[$l]['fill']!=''&&$cou[$l]['fill']!='none')
{$styl="fill:rgb(".$cou[$l]['fill'].");";}else{$styl="fill:none;";}
if($cou[$l]['stroke_rgb']!='')
{if($cou[$l]['stroke_rgb']=='none'){$styl.="stroke:none;";}else{$styl.="stroke:rgb(".$cou[$l]['stroke_rgb'].");";}}
if($cou[$l]['opacity']!='')
{$styl.="fill-opacity:".$cou[$l]['opacity'].";";}
if($cou[$l]['font_familly']!='')
{$styl.="font-familly:".$cou[$l]['font_familly'].";";}
if($cou[$l]['font_size']!='')
{$styl.="font-size:".$cou[$l]['font_size'].";";}
if($cou[$l]['stroke_width']!='')
{$styl.="stroke-width:".$cou[$l]['stroke_width'].";";}else{$styl.="stroke-width:1;";}
if($type_geo=="texte" && $rotation=="false")
{
$styl.="text-anchor:middle;";
}
$textq.="<g style=\"".$styl."\" ";

$textq.=">\n";
	
	if($type_geo=="point")
				{
				for ($e=0;$e<count($res);$e++)
					{
					$textq.="<use ".$res[$e]['geom']." xlink:href='#".$cou[$l]['symbole']."'/>\n";
					}
				}
	elseif($type_geo=="texte")
				{
				for ($e=0;$e<count($res);$e++)
					{
					if($rotation=="true")
					{
					$posi=explode(" ",$res[$e]['geom']);
				$textq.="<text ".$res[$e]['geom']." transform='rotate(-".$res[$e]['rotation'].",".substr($posi[0],3,-1).",".substr($posi[1],3,-1).")'>".$res[$e]['ad']."</text>\n";
					}
					else
					{
					$textq.="<text ".$res[$e]['geom']." >".$res[$e]['ad']."</text>\n";
					}
					}
				}
	else
				{
				for ($e=0;$e<count($res);$e++)
					{
					if(in_array($res[$e]['ident'],$placeid) && $res[$e]['ident']!='')
					{
					$textq.="<path fill='rgb(150,254,150)' fill-opacity='0.7' d='".$res[$e]['geom']."'/>\n";
					}
					else
					{
					$textq.="<path d='".$res[$e]['geom']."'/>\n";
					}
					}
				}
$textq.="</g>";
}
}
}
$str3="<rect y='".($_GET['y']-600)."' x='".($_GET['x']-1200)."' height='".($_GET['hau']+600)."' width='1200' style='fill:white;stroke:none'/>";
$str3.="<rect y='".($_GET['y']-600)."' x='".($_GET['x']+$_GET['lar'])."' height='".($_GET['hau']+600)."' width='1200' style='fill:white;stroke:none'/>";
$str3.="<rect y='".($_GET['y']-600)."' x='".($_GET['x']-1200)."' height='600' width='".($_GET['lar']+1800)."' style='fill:white;stroke:none'/>";
$str3.="<rect y='".($_GET['y']+$_GET['hau'])."' x='".($_GET['x']-600)."' height='600' width='".($_GET['lar']+1800)."' style='fill:white;stroke:none'/>";
$str3.="</svg>";
$str3.="<rect style='fill:white;stroke:blue' width='90' height='18' x='50' y='546'/>";
$str3.="<rect y='555' x='50' height='9' width='45' style='fill:blue'/>";
$str3.="<rect y='546' x='95' height='9' width='45' style='fill:blue'/>";
$str3.="<text y='577' x='87' style='stroke:black;stroke-width:0.5;font-size:12px;fill-opacity:1'>KM</text>";
$str3.="<text id='gauche' y='544' x='50' style='stroke:black;text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1'>0</text>";
$str3.="<text id='centre' y='544' x='95' style='stroke:black;text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1'>".$_GET['centre']."</text>";
$str3.="<text id='droite' y='544' x='140' style='stroke:black;text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1'>".$_GET['droite']."</text>";
//if($_GET['nav']!="0")
//{
$dess="<g id=\"dess\" stroke-width=\"0.2\">";
$tableau=$_SESSION['cotation'];
for($ij=0;$ij<count($tableau);$ij++)
{
$coor=explode("|",$tableau[$ij]);
$dess.="<line id=\"cotation".($ij+1)."\" x1=\"".$coor[0]."\" y1=\"".$coor[1]."\" x2=\"".$coor[2]."\" y2=\"".$coor[3]."\" marker-start=\"url(#debut_mesure)\" marker-end=\"url(#fin_mesure)\" stroke-width=\"0.5\" fill=\"blue\" stroke=\"blue\"/><text fill=\"red\" id=\"texcotation".($ij+1)."\" font-size=\"3\" x=\"".$coor[5]."\" y=\"".$coor[6]."\" transform=\"rotate(".$coor[4].",".$coor[5].",".$coor[6].")\" text-anchor=\"middle\" startOffset=\"0\">".$coor[7]."</text>";
}	
$dess.="</g>";
$cota=$dess;
//}
//else
//{
//$cota=$str2;
//}

$data=$str1."</defs>".$essai.$textq.$cota.$str3."</svg>";
$filename = "../tmp/".$_GET['nom'].".svg";
$myFile = fopen($filename, "w");  
fputs($myFile, $data);
fclose($myFile);
$fp = fopen("../tmp/".$_GET['nom'].".ok", "w");
fwrite($fp,"ok");
fclose ($fp);
?> 
