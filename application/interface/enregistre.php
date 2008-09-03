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
define('GIS_ROOT', '..');
include_once(GIS_ROOT . '/inc/common.php');
gis_session_start();
ini_set("memory_limit" , "24M");
set_time_limit(0);
$extra_url = "&user=".$DB->db_user."&password=".$DB->db_passwd."&dbname=".$DB->db_name."&host=".$DB->db_host;
/*if($SERVER_PORT!=443)
{
ini_set('session.gc_maxlifetime', 3600);
session_start();
}*/
//else
//{
//$_SESSION['xini']=& $_GET['xini'];
//$_SESSION['yini']=& $_GET['yini'];
//$_SESSION['code_insee']=& $code_insee;
//}
//$_SESSION['zoommm'] =& $_GET['zoom'];
//$_SESSION['boitex'] =& $_GET['x'];
//$_SESSION['boitey'] =& $_GET['y'];
//$_SESSION['boitelarg'] =& $_GET['lar'];
//$_SESSION['boitehaut'] =& $_GET['hau'];
$countlayer=1;
$serv=$_SERVER["SERVER_NAME"];
$placeid=explode(",",$_GET['parce']);
$xm=$_GET['x'] + $_GET['xini'];
$xma=($_GET['x']+$_GET['lar']) + $_GET['xini'];
$yma= $_GET['yini'] - $_GET['y'];
$ym= $_GET['yini'] - ($_GET['y']+$_GET['hau']);
$filename = "../tmp/".$_GET['nom'].".svg";
$myFile = fopen($filename, "w");  
$str1="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>";
$str1.="<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.0//EN\" \"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd\">";
$str1.="<svg 
   xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
   xmlns:cc=\"http://web.resource.org/cc/\"
   xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
   xmlns:svg=\"http://www.w3.org/2000/svg\"
   xmlns=\"http://www.w3.org/2000/svg\"
   xmlns:xlink=\"http://www.w3.org/1999/xlink\"
   xmlns:sodipodi=\"http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd\"
   xmlns:inkscape=\"http://www.inkscape.org/namespaces/inkscape\"
   inkscape:output_extension=\"org.inkscape.output.svg.inkscape\"
   id=\"svg2\"
   width=\"1052.36px\"
   height=\"744.09px\"
   >";
$str1.="<sodipodi:namedview
     inkscape:current-layer=\"layer1\"
	 width=\"1052.36px\"
     height=\"744.09px\"
	 borderlayer=\"true\"
     inkscape:showpageshadow=\"false\"
	 />";
$str1.="<svg  id=\"cartographie\" x=\"92\" y=\"8\" width=\"868\" height=\"728\" viewBox=\"".$_GET['x']." ".$_GET['y']." ".$_GET['lar']." ".$_GET['hau']."\">\n";
$str1.="<metadata
       id=\"metadata8\">
      <rdf:RDF>
        <cc:Work
           rdf:about=\"\">
          <dc:format>image/svg+xml</dc:format>
          <dc:type
             rdf:resource=\"http://purl.org/dc/dcmitype/StillImage\" />
        </cc:Work>
      </rdf:RDF>
    </metadata>";
$str1.="<defs>";
$str1.="   <marker
         id=\"debut_mesure\"
         markerWidth=\"5\"
         markerHeight=\"10\"
         orient=\"auto\"
         refX=\"0\"
         refY=\"5\">
        <path
           pointer-events=\"none\"
           fill=\"none\"
           stroke=\"red\"
           d=\"M 5 7.5 0 5 5 2.5\"
           id=\"path13\" />
      </marker>
      <marker
         id=\"fin_mesure\"
         markerWidth=\"5\"
         markerHeight=\"10\"
         orient=\"auto\"
         refX=\"5\"
         refY=\"5\">
        <path
           pointer-events=\"none\"
           fill=\"none\"
           stroke=\"red\"
           d=\"M 0 7.5 5 5 0 2.5\"
           id=\"path16\" />
      </marker></defs>";
fputs($myFile, $str1);

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
	$sql_app="select supp_chr_spec(libelle_appli) as libelle_appli from admin_svg.application where idapplication='".$_SESSION['profil']->appli."'";
$app=$DB->tab_result($sql_app);
$application=$app[0]['libelle_appli'];
$application=str_replace(" ","_",$application);
		
		
if(substr($_SESSION['profil']->insee, -3)=='000')
	{
				
$url="http://".$serv."/cgi-bin/mapserv?map=".$fs_root."capm/".$application.".map&map_imagetype=jpeg&insee=".substr($_SESSION['profil']->insee,0,3)."&layer=".$raster."&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&parce=('')".$extra_url;
}
else
{
$url="http://".$serv."/cgi-bin/mapserv?map=".$fs_root."capm/".$application.".map&map_imagetype=jpeg&insee=".$_SESSION['profil']->insee."&layer=".$raster."&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&parce=('')".$extra_url;
}
		$contenu=file($url);
       		while (list($ligne,$cont)=each($contenu)){
			$numligne[$ligne]=$cont;
		}
		$texte=$numligne[$ms_dbg_line];
		$image=explode('/',$texte);
		$conte1=explode('.',$image[4]);
		$image=$conte1[0];
		
	error_reporting ($erreur);
		$textq.="<g inkscape:groupmode=\"layer\"
     id=\"layer".$countlayer."\"
     inkscape:label=\"raster\">\n";
	 $countlayer=$countlayer+1;
	 $textq.="<image y='".$_GET['y']."' x='".$_GET['x']."' height='".$_GET['hau']."' width='".$_GET['lar']."' sodipodi:absref='".$image.".jpg' xlink:href='".$image.".jpg' />";
		$textq.="</g>\n";
		fputs($myFile, $textq);
}

if($_GET['svg']!="")
{
$rast=$_GET['svg'];

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

$sql="select theme.libelle_them,appthe.idappthe,appthe.ordre,theme.schema,theme.tabl,col_theme.colonn,col_theme.valeur_mini,col_theme.valeur_maxi,col_theme.valeur_texte,sinul(col_theme.fill, style.fill) as fill,sinul(col_theme.stroke_rgb, style.stroke_rgb) as stroke_rgb,sinul(col_theme.symbole,style.symbole) as symbole,sinul(col_theme.opacity,style.opacity) as opacity,sinul(col_theme.font_familly,style.font_familly) as font_familly,sinul(col_theme.font_size,style.font_size) as font_size,appthe.mouseover,appthe.mouseout,appthe.click,appthe.idtheme,theme.partiel,sinul(col_theme.stroke_width,style.stroke_width) as stroke_width from admin_svg.appthe join admin_svg.theme on appthe.idtheme=theme.idtheme left outer join  admin_svg.col_theme on appthe.idappthe=col_theme.idappthe left outer join  admin_svg.style on appthe.idtheme=style.idtheme where appthe.idapplication='".$_SESSION['profil']->appli."' group by theme.libelle_them,appthe.idappthe,appthe.ordre,theme.schema,theme.tabl,col_theme.colonn,col_theme.valeur_mini,col_theme.valeur_maxi,col_theme.valeur_texte,sinul(col_theme.fill, style.fill),sinul(col_theme.stroke_rgb, style.stroke_rgb),sinul(col_theme.symbole,style.symbole),sinul(col_theme.opacity,style.opacity),sinul(col_theme.font_familly,style.font_familly),sinul(col_theme.font_size,style.font_size),appthe.mouseover,appthe.mouseout,appthe.click,appthe.idtheme,theme.partiel,sinul(col_theme.stroke_width,style.stroke_width) order by appthe.ordre desc";
$cou=$DB->tab_result($sql);

for ($l=0;$l<count($cou);$l++){
if(in_array($cou[$l]['idappthe'],$id))
{

$rotation='false';
$d="select * from admin_svg.col_sel where idtheme='".$cou[$l]['idtheme']."'";
		$col=$DB->tab_result($d);
		$f="select distinct ";
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
		
            if (substr($_SESSION['profil']->insee, -3) == "000" || $cou[$l]['schema']=="bd_topo"){
                $f.=" where ".$geometrie." && box'(".$xm.",".$ym.",".$xma.",".$yma.")'";
            }else{
                $f.=" where (code_insee like '".$_SESSION['profil']->insee."%' or code_insee is null) and ".$geometrie." && box'(".$xm.",".$ym.",".$xma.",".$yma.")'";
            }
			
       $j="select * from admin_svg.col_where where idtheme='".$cou[$l]['idtheme']."'";
		$whr=$DB->tab_result($j);
		if (count($whr)>0){
			$f.=" and ".str_replace("VALEUR","'".$_SESSION['profil']->insee."'",$whr[0]['clause']);
			}
		$type_geo="";	
		if($geometrie!="")
		{
		$dd="select distinct geometrytype(".$geometrie.") as geome from ".$cou[$l]['schema'].".".$cou[$l]['tabl'];
		$geo=$DB->tab_result($dd);
		if($geo[0]['geome']=="POINT" AND $cou[$l]['symbole']!="") //si symbole
		{
			$type_geo="symbole";
		}
		else if($geo[0]['geome']=="POINT") //si texte
		{
		$type_geo="texte";
		}
		else if($geo[0]['geome']=="MULTILINESTRING" AND $cou[$l]['font_familly']!='') //si texte avec chemin
		{
		$type_geo="texte_chemin";
		}
		}
	 
		if($cou[$l]['valeur_mini']!='')
		{
			$f.=" and ".$cou[$l]['colonn'].">=".$cou[$l]['valeur_mini']." and ".$cou[$l]['colonn']."<=".$cou[$l]['valeur_maxi'];
		}
		else
		{
			if($cou[$l]['colonn']!='')
			{
			$f.=" and ".$cou[$l]['colonn']."='".$cou[$l]['valeur_texte']."'";
			}
		}
		$res=$DB->tab_result($f);
$styl="";
if($cou[$l]['fill']!=''&&$cou[$l]['fill']!='none')
{$styl="fill:rgb(".$cou[$l]['fill'].");";}else{$styl="fill:none;";}
if($cou[$l]['stroke_rgb']!='')
{if($cou[$l]['stroke_rgb']=='none'){$styl.="stroke:none;";}else{$styl.="stroke:rgb(".$cou[$l]['stroke_rgb'].");";}}
if($cou[$l]['opacity']!='')
{$styl.="fill-opacity:".$cou[$l]['opacity'].";";}
if($cou[$l]['font_familly']!='' && $type_geo!='')
{$styl.="font-familly:".$cou[$l]['font_familly'].";";}
if($cou[$l]['font_size']!='' && $type_geo!='')
{
if($type_geo=="texte_chemin")
{
$styl.="font-size:".($cou[$l]['font_size']/1.5).";startOffset:10%;spacing:auto;";
}
else
{
$styl.="font-size:".$cou[$l]['font_size'].";";
}
}
if($cou[$l]['stroke_width']!='' && ($cou[$l]['stroke_rgb']!='none' || $cou[$l]['stroke_rgb']!=''))
{$styl.="stroke-width:".$cou[$l]['stroke_width'].";";}
if($type_geo=="texte" && $rotation=="false")
{
$styl.="text-anchor:middle;";
}
if($cou[$l]['valeur_texte']=='')
{
$label=$cou[$l]['libelle_them'];
}
else
{
$label=$cou[$l]['valeur_texte'];
}
if($type_geo!="texte_chemin")
{	 
$textq="<g inkscape:groupmode=\"layer\"
     id=\"layer".$countlayer."\"
     inkscape:label=\"".$label."\" style=\"".$styl."\" ";

$textq.=">\n";
fputs($myFile, $textq);
$countlayer=$countlayer+1;
}
	
	if($type_geo=="symbole")
				{
				for ($e=0;$e<count($res);$e++)
					{
					$textq="<text ".$res[$e]['geom']." style=\"font-family:svg\">".$cou[$l]['symbole']."</text>\n";
					fputs($myFile, $textq);
					}
				}
	elseif($type_geo=="texte")
				{
				for ($e=0;$e<count($res);$e++)
					{
					if($rotation=="true")
					{
					$posi=explode(" ",$res[$e]['geom']);
				$textq="<text ".$res[$e]['geom']." transform='rotate(-".$res[$e]['rotation'].",".substr($posi[0],3,-1).",".substr($posi[1],3,-1).")'>".$res[$e]['ad']."</text>\n";
					fputs($myFile, $textq);
					}
					else
					{
					$textq="<text ".$res[$e]['geom']." >".$res[$e]['ad']."</text>\n";
					fputs($myFile, $textq);
					}
					}
				}
	elseif($type_geo=="texte_chemin")
				{
				$textq="<g inkscape:groupmode=\"layer\"
     			id=\"layer".$countlayer."\" inkscape:label=\"troncon_voirie\" style=\"opacity:0;display:none\" ";
				$textq.=">\n";
				fputs($myFile, $textq);
				$countlayer=$countlayer+1;
				for ($e=0;$e<count($res);$e++)
					{
					$textq="<path opacity='0' id='path_text".$e."' d='".$res[$e]['geom']."'/>\n";
					fputs($myFile, $textq);
					}
				
				$textq="</g><g inkscape:groupmode=\"layer\" id=\"layer".$countlayer."\" inkscape:label=\"".$label."\" style=\"".$styl."\" >\n";
				fputs($myFile, $textq);
				$countlayer=$countlayer+1;
				for ($f=0;$f<count($res);$f++)
					{
					$textq="<text><textPath xlink:href='#path_text".$f."'><tspan dy='2'>".$res[$f]['ad']."</tspan></textPath></text>\n";
					fputs($myFile, $textq);
					}
				$textq="</g>";
				fputs($myFile, $textq);	
				}
	
	else
				{
				
				for ($e=0;$e<count($res);$e++)
					{
					if(in_array($res[$e]['ident'],$placeid) && $res[$e]['ident']!='')
					{
					$textq="<path fill='rgb(150,254,150)' fill-opacity='0.7' d='".$res[$e]['geom']."'/>\n";
					fputs($myFile, $textq);
					}
					else
					{
					$textq="<path d='".$res[$e]['geom']."'/>\n";
					fputs($myFile, $textq);
					}
					}
				
				}
if($type_geo!="texte_chemin")
{				
$textq="</g>";
fputs($myFile, $textq);
}
}
}
}
$str3="<g inkscape:groupmode=\"layer\"
     id=\"layer".$countlayer."\"
     inkscape:label=\"bordure\" style='fill:white;stroke:none'>";
$countlayer=$countlayer+1;	 
$str3.="<rect y='".($_GET['y']-600)."' x='".($_GET['x']-1200)."' height='".($_GET['hau']+600)."' width='1200' />";
$str3.="<rect y='".($_GET['y']-600)."' x='".($_GET['x']+$_GET['lar'])."' height='".($_GET['hau']+600)."' width='1200' style='fill:white;stroke:none'/>";
$str3.="<rect y='".($_GET['y']-600)."' x='".($_GET['x']-1200)."' height='600' width='".($_GET['lar']+1800)."' style='fill:white;stroke:none'/>";
$str3.="<rect y='".($_GET['y']+$_GET['hau'])."' x='".($_GET['x']-600)."' height='600' width='".($_GET['lar']+1800)."' style='fill:white;stroke:none'/>";
$str3.="</g>";
$str3.="</svg>";
$str3.="<rect style='fill:white;stroke:blue' width='126' height='18' x='100' y='694'/>";
$str3.="<rect y='703' x='100' height='9' width='63' style='fill:blue'/>";
$str3.="<rect y='694' x='163' height='9' width='63' style='fill:blue'/>";
$str3.="<text y='723' x='163' style='stroke:black;text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1'>KM</text>";
$str3.="<text id='gauche' y='692' x='100' style='stroke:black;text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1'>0</text>";
$str3.="<text id='centre' y='692' x='163' style='stroke:black;text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1'>".$_GET['centre']."</text>";
$str3.="<text id='droite' y='692' x='226' style='stroke:black;text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1'>".$_GET['droite']."</text>";
$dess="<g inkscape:groupmode=\"layer\"
     id=\"layer".$countlayer."\"
     inkscape:label=\"cotation\" stroke-width=\"0.2\">";
$tableau=$_SESSION['cotation'];
for($ij=0;$ij<count($tableau);$ij++)
{
$coor=explode("|",$tableau[$ij]);
$dess.="<line id=\"cotation".($ij+1)."\" x1=\"".$coor[0]."\" y1=\"".$coor[1]."\" x2=\"".$coor[2]."\" y2=\"".$coor[3]."\" marker-start=\"url(#debut_mesure)\" marker-end=\"url(#fin_mesure)\" stroke-width=\"0.5\" fill=\"blue\" stroke=\"blue\"/><text fill=\"red\" id=\"texcotation".($ij+1)."\" font-size=\"3\" x=\"".$coor[5]."\" y=\"".$coor[6]."\" transform=\"rotate(".$coor[4].",".$coor[5].",".$coor[6].")\" text-anchor=\"middle\" startOffset=\"0\">".$coor[7]."</text>";
}	
$dess.="</g>";
$cota=$dess;
$data=$cota.$str3."</svg>";
fputs($myFile, $data);
fclose($myFile);
$ch_image="";
if ($_GET['raster']!=''){$ch_image=" ".$fs_root."tmp/".$image.".jpg";}
$da=date("His");
exec("mv ".$fs_root."tmp/".$_GET['nom'].".svg ".$fs_root."tmp/carte".$da.".svg");
exec("zip -j ".$fs_root."tmp/carte".$da.".zip ".$fs_root."tmp/carte".$da.".svg".$ch_image);
header("Location: http://".$_SERVER['HTTP_HOST']."/tmp/carte".$da.".zip");
?> 
