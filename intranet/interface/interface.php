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
$os = "";
if (ereg("Linux", getenv("HTTP_USER_AGENT"))) 
  $os = "Linux"; 
ini_set('session.gc_maxlifetime', 3600); 
session_start();
$sessi=session_id();
if($_GET["appli"]=='')
{
$_SESSION['code_insee']=770284;
$_SESSION['appli']=13;
}
else
{
$_SESSION['appli']=$_GET["appli"];
}
include("../connexion/deb.php");

$reqcom="select (commune.xma::real - commune.xmi::real) as largeur,commune.xmi as xini, (commune.yma::real - commune.ymi::real) as hauteur ,commune.yma as yini, (commune.xmi::real + (commune.xma::real - commune.xmi::real)/2) as xcenter,(commune.ymi::real + (commune.yma::real - commune.ymi::real)/2) as ycentre from admin_svg.commune where commune.idcommune like '".$_SESSION['code_insee']."'";

		$vu=tab_result($pgx,$reqcom);
		$_SESSION['large'] =$vu[0]['largeur'];
		$_SESSION['haute'] =$vu[0]['hauteur'];
		$_SESSION['xini'] =& $vu[0]['xini'];
		$_SESSION['yini'] =& $vu[0]['yini'];
		$_SESSION['xcenter'] =& $vu[0]['xcenter'];
		$_SESSION['ycenter'] =& $vu[0]['ycentre'];
		$_SESSION['image'] ="";
		$_SESSION['cotation'] ="";
		
$legende="";
$controle="";
$chaine="";
$symbol="";
$textsymbol="";
$extraction="";
$req2="select theme.libelle_them as nom_theme,theme.schema,theme.tabl,appthe.idappthe,col_theme.colonn,admin_svg.v_fixe(col_theme.valeur_texte),appthe.raster,sinul(appthe.zoommin::character varying,theme.zoommin::character varying) as zoommin,sinul(appthe.zoommax::character varying,theme.zoommax::character varying) as zoommax,sinul(appthe.zoommaxraster::character varying,theme.zoommax_raster::character varying) as zoommax_raster,theme.raster as testraster,application.zoom_min as zoom_min_appli,application.zoom_max as zoom_max_appli,application.zoom_ouverture as zoom_ouverture_appli,theme.partiel,theme.vu_initial,style.fill as style_fill,style.symbole as style_symbole,style.opacity  as style_opacity,style.font_size  as style_fontsize,style.stroke_rgb  as style_stroke,application.btn_polygo,application.libelle_btn_polygo from admin_svg.appthe join admin_svg.theme on appthe.idtheme=theme.idtheme join admin_svg.application on appthe.idapplication=application.idapplication left outer join  admin_svg.col_theme on appthe.idappthe=col_theme.idappthe left outer join admin_svg.style on appthe.idtheme=style.idtheme where appthe.idapplication=".$_SESSION['appli']." group by theme.libelle_them,appthe.ordre,theme.schema,theme.tabl,col_theme.colonn,admin_svg.v_fixe(col_theme.valeur_texte),appthe.raster,theme.zoommin,appthe.zoommin,theme.zoommax,appthe.zoommax,theme.zoommax_raster,appthe.zoommaxraster,theme.raster,application.zoom_min,application.zoom_max,application.zoom_ouverture,theme.partiel,theme.vu_initial,style.fill,style.symbole,style.opacity,style.font_size,style.stroke_rgb,appthe.idappthe,application.btn_polygo,application.libelle_btn_polygo order by appthe.ordre asc";
$cou=tab_result($pgx,$req2);
$zoommin=$cou[0]['zoom_min_appli'];
$zoommax=$cou[0]['zoom_max_appli'];
$min=$zoommin;
$intervale=round(($zoommax-$zoommin)/18);
$zoommax=$zoommin+(18*$intervale);
if($_SESSION['zoommm'])
	{
	if($_SESSION['zoommm']>=$cou[0]['zoom_min_appli'])
	{ 
	$zo=$_SESSION['zoommm'];
	}
	else
	{
	$zo=$cou[0]['zoom_min_appli'];
	}
	if($_SESSION['zoommm']>=$cou[0]['zoom_max_appli'])
	{ 
	$zo=$cou[0]['zoom_min_appli'];
	}
	$xc=$_SESSION['cx'];
	$yc=$_SESSION['cy'];
	}
else
{
$zo=$cou[0]['zoom_ouverture_appli'];
	$xc=0;
	$yc=0;
}
if($zo!=$cou[0]['zoom_min_appli'])
{
$debut=$zoommin;
$debut1=$debut+$intervale;
		for ($i=0;$i<17;$i++)
		{
			if ($zo < $debut1 && $zo >= $debut) 
			{
			$zoomouv=$debut1;
			}
			$debut=$debut+$intervale;
			$debut1=$debut1+$intervale;
		}
//$deter_zoom_ouv=round($cou[0]['zoom_ouverture_appli']/$intervale);
//$zoomouv=$zoommin+($intervale*$deter_zoom_ouv);
}
else
{
$zoomouv=$zoommin;
}
$posi=688;
$y=290;
$tab_layer="var zlayer=new Array;";
$layer="var layer=new Array;";
$lay="var controllay=new Array;";
$j=0;
for ($c=0;$c<count($cou);$c++)
{
$req1="select distinct (col_theme.intitule_legende) as intitule_legende,col_theme.fill,col_theme.stroke_rgb,col_theme.symbole,col_theme.font_size,col_theme.font_familly,col_theme.opacity,col_theme.ordre from admin_svg.appthe join admin_svg.col_theme on appthe.idappthe=col_theme.idappthe join admin_svg.theme on appthe.idtheme=theme.idtheme";
	if($cou[$c]['v_fixe']=='1' and $cou[$c]['colonn']<>'')
	{
	$req1.=" join ".$cou[$c]['schema'].".".$cou[$c]['tabl']." on col_theme.valeur_texte=".$cou[$c]['tabl'].".".$cou[$c]['colonn']." where 					appthe.idapplication=".$appli." and theme.libelle_them='".$cou[$c]['nom_theme']."'";
	if(substr($_SESSION['code_insee'], -3)!='000' && $cou[$c]['schema']!="bd_topo")
	{
	$req1.=" and ".$cou[$c]['tabl'].".code_insee like '".$_SESSION['code_insee']."' or code_insee is null ";
	 }
	 $req1.=" order by col_theme.ordre asc";
	}
	
	else
	{
	$req1.=" where appthe.idapplication=".$appli." and theme.libelle_them='".$cou[$c]['nom_theme']."'";
	}
	$couch=tab_result($pgx,$req1);
	$z=$c+1;
	$chaine.="<g id='control".$z."' visibility='hidden'>\n";
	$legende.="<g id='theme".$z."' so=\"".count($couch)."\">\n";
	$cous="";
	$leg="";
	$typ='';
	if(count($couch)>0)
		{
			for ($r=0;$r<count($couch);$r++)
			{
			$cous.=$z.codalpha($r+1).";";
			$leg.=$cou[$c]['idappthe'].".".$couch[$r]['intitule_legende']."|";
				if($cou[$c]['testraster']=='')
				{
				$typ='';
					if($cou[$c]['raster']=='f')
					{
					$zz=$cou[$c]['zoommin'];
					}
					else
					{
					$zz=$cou[$c]['zoommax_raster'];
					}
				$tab_layer.="zlayer['".$cou[$c]['idappthe'].".".$couch[$r]['intitule_legende']."']=new glayer('".$z.codalpha($r+1)."','FALSE','','',".$zz.",'".$cou[$c]['partiel']."');";
				$layer.="layer[$j]='".$cou[$c]['idappthe'].".".$couch[$r]['intitule_legende']."';";
				$j=$j+1;
				}
				else
				{
				$typ='raster';
				}
			}
		$cous = substr($cous,0,strlen($cous)-1);
		$leg = substr($leg,0,strlen($leg)-1);
	}
	else
	{
	$leg=$cou[$c]['idappthe'].".".$cou[$c]['nom_theme'];
		if($cou[$c]['testraster']=='')
		{
		$typ='';
		if($cou[$c]['zoommax']<$cou[$c]['zoommax_raster'])
		{
		$typ='raster';
		}
		if($cou[$c]['raster']=='f')
		{
		$zz=$cou[$c]['zoommin'];
		}
		else
		{
		$zz=$cou[$c]['zoommax_raster'];
		}
		$tab_layer.="zlayer['".$cou[$c]['idappthe'].".".$cou[$c]['nom_theme']."']=new glayer('".$z."','FALSE','','',".$zz.",'".$cou[$c]['partiel']."');";
		$layer.="layer[$j]='".$cou[$c]['idappthe'].".".$cou[$c]['nom_theme']."';";
		$j=$j+1;
		}
		else
		{
		$typ='raster';
		}
	$cous=$z;
	
	}
	if($cou[$c]['vu_initial']==1)
	{
	$extraction.="extract('".$leg."','".$cous."','','".$typ."');";
	}
$legende.="<g id=\"com".$z."\">\n";
$legende.="<rect id=\"coche".$z."\" x=\"650\" y=\"".$y."\" width=\"8\" height=\"8\" onclick=\"extract('".$leg."','".$cous."','','".$typ."')\"/>\n";
$legende.="<text id=\"tra".$z."\" x=\"650\" y=\"".($y+8)."\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>\n";
if(count($couch)<1 && ($cou[$c]['style_fill']!="" || $cou[$c]['style_stroke']!=""))
{
if($cou[$c]['style_symbole']!="" || $couch[$w]['symbole']!="")
{
if(ereg("[^befghijnYZ]",$couch[$w]['symbole']) || ereg("[^abefghijnYZ]",$cou[$c]['style_symbole']))
			{
			$texte=fopen("./police.svg","r");
			$contents = fread($texte, filesize ("./police.svg"));
			$data=explode("<",$contents);
				for ($i=1;$i<count($data);$i++)
				{
					if(ereg('unicode="'.$cou[$c]['style_symbole'].'"',$data[$i]))
					{ 
					$symbol.="<".$data[$i]."\n";
					$textsymbol.="<text id=\"".$cou[$c]['style_symbole']."\" font-family=\"fontsvg\" fill=\"rgb(".$cou[$c]['style_fill'].")\" font-size=\"".$cou[$c]['style_fontsize']."\" >".$cou[$c]['style_symbole']."</text>";
					}
				}
			fclose($texte);
}

$legende.="<text x=\"660\" y=\"".($y+8)."\" font-size=\"12\" stroke=\"none\" font-family=\"fontsvg\" fill=\"rgb(".$cou[$c]['style_fill'].")\">".$cou[$c]['style_symbole']."</text>\n";
//$legende.="<rect x=\"662\" y=\"".$y."\" width=\"8\" height=\"8\" n=\"".$cou[$c]['style_symbole']."\" fill-opacity=\"".$cou[$c]['style_opacity']."\" fill=\"rgb(".$cou[$c]['style_fill'].")\"/>\n";
}
elseif($cou[$c]['style_fontsize']=="")
{
	if($cou[$c]['style_stroke']!="" && ($cou[$c]['style_fill']==""||$cou[$c]['style_fill']=="none"))
	{
	$legende.="<rect x=\"662\" y=\"".$y."\" width=\"8\" height=\"8\" n=\"ok\" fill=\"rgb(".$cou[$c]['style_stroke'].")\"/>\n";
	}
	else
	{
	$legende.="<rect x=\"662\" y=\"".$y."\" width=\"8\" height=\"8\" n=\"ok\" fill-opacity=\"".$cou[$c]['style_opacity']."\" fill=\"rgb(".$cou[$c]['style_fill'].")\"/>\n";
	}
}
}
$legende.="<text id=\"text".$z."\" x=\"674\" y=\"".($y+8)."\" class=\"fillfonce\">".$cou[$c]['nom_theme']."</text>\n";
//$legende.="<text id=\"text".$z."\" x=\"662\" y=\"".($y+8)."\" class=\"fillfonce\">".$cou[$c]['nom_theme']."</text>\n";
$lay.="controllay[".$c."]=new ylayer(".$cou[$c]['zoommin'].",".$cou[$c]['zoommax'].",'".$typ."');";
	if(count($couch)>0)
	{
		$legende.="<rect id=\"deroul".$z."\" x=\"640\" y=\"".($y+1)."\" width=\"6\" height=\"6\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','white','','')\" onclick=\"Javascript:derouler(evt,".$z.",".count($couch).")\"/>\n";
		$legende.="<line x1=\"641\" x2=\"645\" y1=\"".($y+4)."\" y2=\"".($y+4)."\" pointer-events=\"none\"/><line id=\"d".$z."\" x1=\"643\" x2=\"643\" y1=\"".($y+6)."\" y2=\"".($y+2)."\" visibility=\"visible\" pointer-events=\"none\"/>\n";
		$legende.="</g>\n";
		$legende.="<g id=\"soustheme".$z."\" visibility=\"hidden\">\n";
		$y1=$y+12;
		for ($w=0;$w<count($couch);$w++)
		{
			
			if($couch[$w]['symbole']!="" and ereg("[^abefghijnYZ]",$couch[$w]['symbole']))
			{
			$texte=fopen("./police.svg","r");
			$contents = fread($texte, filesize ("./police.svg"));
			$data=explode("<",$contents);
				for ($i=1;$i<count($data);$i++)
				{
					if(ereg('unicode="'.$couch[$w]['symbole'].'"',$data[$i]))
					{ 
					$symbol.="<".$data[$i]."\n";
					$textsymbol.="<text id=\"".$couch[$w]['symbole']."\" font-family=\"fontsvg\" fill=\"rgb(".$couch[$w]['fill'].")\" font-size=\"".$couch[$w]['font_size']."\" >".$couch[$w]['symbole']."</text>";
					}
				}
			fclose($texte);
			} 
			
		$chaine.="<g id='control".$z.codalpha($w+1)."' visibility='hidden'></g>\n";
		$legende.="<rect id=\"coche".$z.codalpha($w+1)."\" x=\"662\" y=\"".$y1."\" width=\"8\" height=\"8\" onclick=\"extract('".$cou[$c]['idappthe'].".".$couch[$w]['intitule_legende']."','".$z.codalpha($w+1)."','','".$typ."')\"/>\n";
    		if($couch[$w]['stroke_rgb']!="" && ($couch[$w]['fill']==""||$couch[$w]['fill']=="none"))
			{
			$coucoul=$couch[$w]['stroke_rgb'];
			}
			else
			{
			$coucoul=$couch[$w]['fill'];
			}
			if($coucoul=="")
			{
			$couch[$w]['fill']="0,0,0";
			}
			if($couch[$w]['symbole']!="")
			{
			$legende.="<text id=\"coul".$z.codalpha($w+1)."\" x=\"672\" y=\"".($y1+8)."\" font-size=\"12\" stroke=\"none\" font-family=\"fontsvg\" fill=\"rgb(".$coucoul.")\">".$couch[$w]['symbole']."</text>\n";
			}
			else
			{
			
			$legende.="<rect id=\"coul".$z.codalpha($w+1)."\" x=\"674\" y=\"".$y1."\" width=\"8\" height=\"8\" fill=\"rgb(".$coucoul.")\"/>\n";
			}
			$y1=$y1+12;
    		
		}
		
				$legende.="<g><g id=\"tr".$z."\" font-size=\"12px\" pointer-events=\"none\" font-family=\"fontsvg\" class=\"fillfonce\" visibility=\"hidden\">\n";
				$y2=$y+20;
		for ($w=0;$w<count($couch);$w++)
		{
						$legende.="<text id=\"tra".$z.codalpha($w+1)."\" x=\"662\" y=\"".$y2."\">b</text>\n";
						$y2=$y2+12;
		}
						$legende.="</g>\n";
				$y3=$y+20;		
		for ($w=0;$w<count($couch);$w++)
		{
						$legende.="<text id=\"text".$z.codalpha($w+1)."\" x=\"686\" y=\"".$y3."\" class=\"fillfonce\">".$couch[$w]['intitule_legende']."</text>\n";
						$y3=$y3+12;
		}
					$legende.="</g>\n";
			$legende.="</g>\n";  
	}
	else
	{
	$legende.="</g>\n";
	}
	$chaine.="</g>\n";
	$controle=$chaine.$controle;
	$chaine="";
	$y=$y+12;
}
$controle="<g id='controlraster' visibility='hidden'></g>\n".$controle;
for ($c=0;$c<count($cou);$c++)
{
$legende.="</g>\n";
}
$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>";
if (file_exists("./css/".$_SESSION['code_insee'].".css"))
{
$data.="<?xml-stylesheet href=\"./css/".$_SESSION['code_insee'].".css\" type=\"text/css\" ?>";
}
else
{
$data.="<?xml-stylesheet href=\"./css/default.css\" type=\"text/css\" ?>";
}
$data.="<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.0//EN\" \"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd\">";
$data.="<svg id=\"svg2\" xmlns:xlink='http://www.w3.org/1999/xlink' width=\"100%\" heigth=\"100%\" viewBox=\"0 0 800 600\" onkeypress=\"tape(evt);tapenum(evt)\" onload=\"init(evt);".$extraction."\" onmousemove=\"determ_ratio()\">";
$data.="<rect id=\"rectdefond\" width=\"100%\" height=\"100%\" x=\"0\" y=\"0\" fill=\"white\"/>"; 
$data.="<script xlink:href=\"script.js\" language=\"JavaScript\"></script>";
$data.="<script><![CDATA[\n";
$data.="nav=".$nav."\n";
$data.=$tab_layer."\n";
$data.=$layer."\n";
$data.=$lay."\n"; 
$data.="function glayer(controle,visible,zoom_charge,position,zoomraster,partiel){
	this.svg_controle=controle;
	this.svg_visible=visible;
	this.svg_zoom_charge=zoom_charge;
	this.svg_position=position;
	this.svg_zoomraster=zoomraster;
	this.svg_partiel=partiel;
	
}\n";
$data.=" function ylayer(zmin,zmax,typ){
	this.zoommin=zmin;
	this.zoommax=zmax;
	this.type=typ;
}\n";

$data.="nblayer=".count($cou).";\n";
$data.="url_polygo='".$cou[0]['btn_polygo']."';\n";
$data.="zoommin=".$zoommin.";\n";
$data.="zoommax=".$zoommax.";\n";
$data.="cx=".$xc.";\n";
$data.="cy=".$yc.";\n";
$data.="intervale=".$intervale.";\n";
$data.="theZoom=".$zoomouv.";\n";
$data.="zoomVal=".$zoomouv.";\n";
$data.="appli=".$appli."; \n";
$data.="sessionid='".$sessi."';\n";
$data.="sessionname='".session_name()."';\n";
$data.="code_insee=".$_SESSION['code_insee'].";\n";
$data.="xini=".$vu[0]['xini'].";\n";
$data.="yini=".$vu[0]['yini'].";\n";
$data.="largeurini=".$_SESSION['large'].";\n";
$data.="hauteurini=".$_SESSION['haute'].";\n";
$data.="serveur='".$HTTP_HOST."';\n";
$data.="zoom_init=100;\n";
//$data.="";
$data.="]]></script>
  <defs>
  <marker id=\"debut_mesure\" markerWidth=\"5\" markerHeight=\"10\" orient=\"auto\" refX=\"0\" refY=\"5\">
  	
	<path pointer-events=\"none\" fill=\"none\" stroke=\"red\" d=\"M 5 7.5 0 5 5 2.5\" />
</marker>
<marker id=\"fin_mesure\" markerWidth=\"5\" markerHeight=\"10\" orient=\"auto\" refX=\"5\" refY=\"5\">
  	<path pointer-events=\"none\" fill=\"none\" stroke=\"red\" d=\"M 0 7.5 5 5 0 2.5\" />
	
</marker>
  <font id=\"perso\" horiz-adv-x=\"1539\" ><font-face
    font-family=\"fontsvg\"
    units-per-em=\"2048\"
    panose-1=\"2 0 0 0 0 0 0 0 0 0\"
    ascent=\"1854\"
    descent=\"-434\"
    alphabetic=\"0\" />
<missing-glyph horiz-adv-x=\"1536\" d=\"M256 0V1280H1280V0H256ZM288 32H1248V1248H288V32Z\" />
<glyph unicode=\"a\" glyph-name=\"a\" horiz-adv-x=\"2048\" d=\"M2032 508L2013 414H1772V428Q1794 429 1802 437T1810 467V653Q1810 681 1803 689T1774 698V707H2011V623H2003Q1998 663 1977 679T1909 696Q1891 696 1885 691T1878 666V573H1884Q1910 573 1923 588T1944
639H1954V494H1944Q1940 530 1927 544T1884 559H1878V461Q1878 445 1883 440T1907 434H1919Q1956 434 1981 453T2019 508H2032ZM1230 1544Q1209 1542 1203 1536T1196 1505V1257H1184L999 1487V1313Q999 1290 1007 1283T1036 1274V1264H944L945 1274Q967 1276 974
1283T981 1313V1511L967 1528Q965 1529 962 1533Q954 1542 945 1544L944 1554H1030L1178 1372V1505Q1178 1527 1171 1534T1139 1544V1554H1231L1230 1544ZM1743 553L1225 416L1085 -102L946 416L428 553L946 690L1085 1210L1225 690L1743 553ZM1122 -295Q1156 -312
1170 -332T1184 -385Q1184 -427 1157 -451T1083 -475Q1064 -475 1041 -468T1016 -461Q1010 -461 1007 -464T1001 -475H991V-371H1003Q1013 -417 1032 -439T1085 -461Q1110 -461 1123 -448T1137 -412Q1137 -397 1130 -387T1104 -367L1065 -346Q1026 -326 1011 -306T995
-256Q995 -218 1018 -195T1079 -172Q1094 -172 1141 -184Q1147 -185 1150 -186Q1155 -185 1158 -182T1163 -172H1176V-264H1166Q1152 -226 1132 -206T1085 -186Q1065 -186 1054 -196T1042 -225Q1042 -243 1049 -252T1081 -274L1122 -295ZM411 696Q392 691 383 665Q382
662 381 659L299 414H289L219 612L141 414H129L43 667Q37 684 31 689T13 696L12 707H135V696H129Q119 696 115 692T111 674Q111 668 112 663T115 653L162 514L209 641L201 671Q199 678 197 682T190 690Q187 693 174 695Q167 696 164 696V707H295V696H287Q279 696
274 692T266 680L270 664L317 522L358 651Q360 654 361 660T364 675Q363 687 357 691T336 696V707H412L411 696ZM1206 678L1087 1110V563L1206 678ZM1659 553H1094L1210 432L1659 553ZM1077 559L961 674L532 559H1077ZM1083 -4V549L965 428L1083 -4Z\" />
<glyph unicode=\"b\" glyph-name=\"b\" horiz-adv-x=\"1609\" d=\"M1500 1568L1535 1518Q1321 1357 1059 1022T659 396L585 346Q493 282 460 251Q447 298 403 405L375 470Q315 610 264 677T148 766Q256 880 346 880Q423 880 517 671L548 601Q717 886 982 1155T1500 1568Z\" />
<glyph unicode=\"e\" glyph-name=\"e\" horiz-adv-x=\"2048\" d=\"M1760 1011Q1760 900 1659 797H1758V40H1708V0H334V40H288V797H491Q654 933 654 1121Q654 1267 554 1329H1565Q1641 1300 1700 1202T1760 1011ZM1734 1020Q1734 1096 1682 1186Q1627 1279 1560 1309H616Q679
1243 679 1119Q679 939 539 797H1622Q1734 894 1734 1020ZM1728 286V767H318V286H1728ZM1728 53V260H1405V217H553V260H318V53H1728ZM1712 347H1003V742H1712V347ZM1686 373V716H1029V373H1686ZM1639 585H1608V686H1639V585ZM1622 467H1472V501H1622V467ZM1622
401H1472V436H1622V401ZM1563 578H1062V686H1563V578ZM1437 467H1288V501H1437V467ZM1437 401H1288V436H1437V401ZM1253 467H1104V501H1253V467ZM1253 401H1104V436H1253V401Z\" />
<glyph unicode=\"f\" glyph-name=\"f\" horiz-adv-x=\"2048\" d=\"M1360 1028Q1360 867 1277 767Q1188 659 1030 659Q865 659 760 802Q664 932 664 1102Q664 1450 985 1450Q1143 1450 1255 1316Q1360 1190 1360 1028ZM1086 488L1052 501L1037 615H1021L1032 507H1020Q990
507 948 487Q902 466 893 443L858 610Q732 616 633 730Q531 848 531 1002Q531 1150 584 1230Q614 1277 747 1389Q636 1270 636 1105Q636 929 730 794Q836 643 1005 643Q1030 643 1065 643L1086 488ZM1178 -157Q1178 -191 1149 -217T1083 -243Q1044 -243 1013 -214Q986
-189 983 -163L901 409Q896 441 942 465Q985 488 1038 488Q1074 488 1092 455L1167 -85L1178 -157ZM1328 1031Q1328 1178 1231 1292Q1129 1413 985 1413Q695 1413 695 1098Q695 943 783 825Q879 695 1028 695Q1172 695 1253 793Q1328 884 1328 1031ZM1306 1020Q1306
808 1115 722Q1235 858 1235 1010Q1235 1158 1142 1255T902 1358Q946 1377 998 1377Q1128 1377 1220 1262Q1306 1154 1306 1020ZM1140 1052Q1140 968 1058 895Q1068 974 1068 1007Q1068 1064 1029 1125Q1014 1148 950 1226Q1025 1223 1081 1175Q1140 1124 1140
1052ZM832 867Q791 881 765 952Q743 1010 743 1064Q743 1130 777 1169Q813 1037 832 867Z\" />
<glyph unicode=\"g\" glyph-name=\"g\" horiz-adv-x=\"1431\" d=\"M1259 1480V0H173V1480H1259ZM1209 1431H222V49H1209V1431ZM370 247V296H1061V247H370ZM370 444V493H1061V444H370ZM370 641V691H1061V641H370ZM370 839V888H1061V839H370ZM370 1036V1086H1061V1036H370ZM370
1234V1283H1061V1234H370Z\" />
<glyph unicode=\"h\" glyph-name=\"h\" horiz-adv-x=\"1826\" d=\"M1456 -197H1259V-395H173V1086H370V1283H568V1480H1653V0H1456V-197ZM617 1431V1283H1456V49H1604V1431H617ZM420 1234V1086H1259V-148H1407V1234H420ZM1209 1036H222V-346H1209V1036ZM370 -148V-99H1061V-148H370ZM370
49V99H1061V49H370ZM370 247V296H1061V247H370ZM370 444V493H1061V444H370ZM370 641V691H1061V641H370ZM370 839V888H1061V839H370Z\" />
<glyph unicode=\"i\" glyph-name=\"i\" horiz-adv-x=\"1135\" d=\"M173 740L543 1110V814H765V666H543V370L173 740ZM864 814H1061V666H864V814ZM1160 814H1357V666H1160V814ZM1456 814H1653V666H1456V814Z\" />
<glyph unicode=\"j\" glyph-name=\"j\" horiz-adv-x=\"1135\" d=\"M1653 740L1283 1110V814H1061V666H1283V370L1653 740ZM962 814H765V666H962V814ZM666 814H469V666H666V814ZM370 814H173V666H370V814Z\" />
<glyph unicode=\"n\" glyph-name=\"n\" horiz-adv-x=\"1825\" d=\"M209 969L913 1480L1617 969L1348 141H478L209 969Z\" />
<glyph unicode=\"Y\" glyph-name=\"Y\" horiz-adv-x=\"2319\" d=\"M878 740L222 1396V84L878 740ZM1407 705L1230 529Q1201 499 1160 499Q1119 499 1089 529L913 705L257 49H2062L1407 705ZM1442 740L2097 84V1396L1442 740ZM257 1431L1125 563Q1139 549 1160 549Q1181
549 1195 563L2063 1431H257ZM173 1480H2147V0H173V1480Z\" />
<glyph unicode=\"Z\" glyph-name=\"Z\" horiz-adv-x=\"1826\" d=\"M173 197V1382Q173 1423 202 1451T272 1480H1555Q1596 1480 1624 1452T1653 1382V99Q1653 58 1625 29T1555 0H370L173 197ZM864 493H617V49H864V493ZM1259 49H1555Q1580 49 1592 61T1604 98V1382Q1604
1431 1555 1431H1407V839Q1407 798 1378 769T1308 740H518Q477 740 449 769T420 839V1431H272Q222 1431 222 1382V217L390 49H568V493Q568 543 617 543H1209Q1259 543 1259 493V49ZM1357 1431H469V839Q469 815 481 803T518 790L1308 789Q1357 789 1357 839V1431Z\"
/>";
$data.=$symbol;
$data.="</font>";
if($os=="Linux")
{
$texte=fopen("./linux_arial.svg","r");
$contents = fread($texte, filesize ("./linux_arial.svg"));
$data.=$contents;
fclose($texte);
}
$data.=$textsymbol;
$data.="<rect id=\"boutonvierge\" width=\"23\" height=\"23\" x=\"0\" y=\"0\" ry=\"3\" rx=\"3\" /> 
    <linearGradient id=\"gra1\" x1=\"1\" y1=\"0\" x2=\"0\" y2=\"0\">
	<stop offset=\"0\" class=\"coulfonce\"/>
	<stop offset=\"1\" class=\"coulblanc\"/>
</linearGradient>
 <linearGradient id=\"gra2\" x1=\"0\" y1=\"0\" x2=\"1\" y2=\"0\">
	<stop offset=\"0\" class=\"coulfonce\"/>
	<stop offset=\"1\" class=\"coulblanc\"/>
</linearGradient>
<linearGradient id=\"survol\">
	<stop offset=\"0\" class=\"coulfonce\"/>
</linearGradient>
<linearGradient id=\"zoomselect\">
	<stop offset=\"0\" class=\"coulselzoom\"/>
</linearGradient>
<linearGradient id=\"clair\">
	<stop offset=\"0\" class=\"coulblanc\"/>
</linearGradient>
<linearGradient id=\"hors\">
	<stop offset=\"0\" class=\"coulclair\"/>
</linearGradient>

   </defs>
   
    <g id=\"layer1\" class=\"defaut\">
   
    <svg id=\"mapid\"  x=\"11\" y=\"11\" width=\"620\" height=\"520\" viewBox=\"0 0 ".$_SESSION['large']." ".$_SESSION['haute']."\"  onmousedown=\"createcircle(evt);\" onmouseup=\"fintrait(evt)\" onmousemove=\"bougetrait(evt)\"  onmouseover=\"desinib_use();\">
	<g id=\"enregistrement\">
<rect id=\"desrect\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" stroke=\"none\" fill=\"white\" pointer-events=\"none\"/>	
	<g id=\"dessin\">";
$data.=$controle;	
$data.="</g>
<g id='dess' stroke-width='0.2'>
	
	</g>

</g>
	</svg>
	
	<g id=\"cardinal\" style=\"stroke:none\">
	<rect x=\"0\" y=\"11\" width=\"11\" height=\"520\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','ouest')\" onmouseout=\"switchColor(evt,'fill','none','','ouest')\" onclick=\"goWest();\"/>
	<path id=\"ouest\" pointer-events=\"none\" d=\"M 0 271 11 200 11 342z\" />
	<text pointer-events=\"none\" x=\"2\" y=\"273\" class=\"coulblanc\" font-size=\"8\">W</text>
	<rect x=\"631\" y=\"11\" width=\"11\" height=\"520\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','est')\" onmouseout=\"switchColor(evt,'fill','none','','est')\" onclick=\"goEast();\"/>
	<path id=\"est\" pointer-events=\"none\" d=\"M 642 271 631 200 631 342z\" />
	<text pointer-events=\"none\" x=\"633\" y=\"273\" class=\"coulblanc\" font-size=\"8\">E</text>
	<rect x=\"11\" y=\"0\" width=\"620\" height=\"11\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','nord')\" onmouseout=\"switchColor(evt,'fill','none','','nord')\" onclick=\"goNorth();\"/>
	<path id=\"nord\" pointer-events=\"none\" d=\"M 321 0 250 10 392 10z\" />
	<text pointer-events=\"none\" x=\"318\" y=\"9\" class=\"coulblanc\" font-size=\"8\">N</text>
	<rect x=\"11\" y=\"531\" width=\"620\" height=\"11\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','sud')\" onmouseout=\"switchColor(evt,'fill','none','','sud')\" onclick=\"goSouth();\"/>
	<path id=\"sud\" pointer-events=\"none\" d=\"M 321 542 250 532 392 532z\" />
	<text pointer-events=\"none\" x=\"318\" y=\"540\" class=\"coulblanc\" font-size=\"8\">S</text>
	</g>
	
	<rect id=\"map\" width=\"620\" height=\"520\" x=\"11\" y=\"11\" fill=\"none\" pointer-events=\"none\"/>
	<rect x=\"380\" y=\"513\" width=\"245\" height=\"14\" class=\"fillclair\" style=\"opacity:0.6\" pointer-events=\"none\"/>
	<text pointer-events=\"none\" x=\"20\" y=\"515\" class=\"fillfonce\" style=\"font-size:50px;font-family:fontsvg\">a</text>
	<text pointer-events=\"none\" x=\"385\" y=\"523\" class=\"fillfonce\" font-size=\"8\">Source: direction g&#233;n&#233;rale des imp&#244;ts - cadastre;mise &#224; jour:2007</text>
	<g id=\"message_box\" visibility=\"hidden\" >
	<rect x=\"241\" y=\"264\" width=\"160\" height=\"18\" class=\"fillclair\" style=\"opacity:0.6\" pointer-events=\"none\" />
	<text pointer-events=\"none\" x=\"250\" y=\"277\" font-size=\"15\" class=\"fillfonce\">Veuillez patienter</text>
	<g id=\"anim\">
	<path fill-opacity=\"0\" d=\"M 380 268 A 5 5 0 1 1 375 273 M 374 275.5 375 273 377.5 274z\" />
	<animateTransform id=\"anim_arr\" attributeName=\"transform\" begin=\"indefinite\"  attributeType=\"XML\" type=\"rotate\" from=\"0 380 273\" to=\"2160 380 273\" dur=\"10s\" repeatDur=\"indefinite\"/>
	</g>
	</g>
	
	<svg id=\"overviewmap\" x=\"645\" y=\"11\" width=\"150\" height=\"126\" viewBox=\"0 0 ".$_SESSION['large']." ".$_SESSION['haute']."\" onmouseover=\"inib_use();\" >";
    if (file_exists("./communes/".$_SESSION['code_insee'].".JPG"))
	{
	$data.="<image id=\"fond\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"./communes/".$_SESSION['code_insee'].".JPG\"/>";
	}
	else
	{
	$data.="<image id=\"fond\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"./communes/default.JPG\"/>";
	}
	$data.="<use x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"#dessin\"/>
	<g onmousedown=\"beginPan(evt)\" onmousemove=\"doPan(evt)\" onmouseup=\"endPan(evt)\" onmouseout=\"endPan(evt)\">
	<rect id=\"Rect1\" cursor=\"move\" style=\"fill:rgb(255,0,0);stroke-width:20;stroke:rgb(0,0,0);fill-opacity:0.4\" x=\"0\" y=\"0\"
 width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" visibility=\"hidden\" />
 <rect id=\"lin1\" stroke-width=\"20\" fill=\"rgb(0,0,0)\" x=\"0\" y=\"".($_SESSION['haute']/2)."\" width=\"".$_SESSION['large']."\" height=\"20\" visibility=\"hidden\"/>
 <rect id=\"lin2\" stroke-width=\"20\" fill=\"rgb(0,0,0)\" x=\"".($_SESSION['large']/2)."\" y=\"0\" width=\"20\" height=\"".$_SESSION['haute']."\" visibility=\"hidden\"/>

	<rect id=\"locationRect\" cursor=\"move\" class=\"fillfonce\" style=\"fill-opacity:0.5\" x=\"0\" y=\"0\"
 width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\"  visibility=\"hidden\"/>
 </g>	
	
	</svg>
	<rect id=\"locamap\" width=\"150\" height=\"126\" x=\"645\" y=\"11\" fill=\"none\" pointer-events=\"none\"/>
    <g class=\"fillclair\">
<a id=\"aide\"><use x=\"593\" y=\"545\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Aide en ligne')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:aide()\"/></a>
<text x=\"600\" y=\"560\" font-weight=\"normal\" font-family=\"Arial\" font-size=\"16\" class=\"fillfonce\" pointer-events=\"none\" >?</text>
<text x=\"596\" y=\"568\" font-weight=\"normal\" font-family=\"Arial\" font-size=\"8\" class=\"fillfonce\" pointer-events=\"none\" >Aide</text></g>
<g class=\"fillclair\">
			<a id=\"contacte\"><use x=\"563\" y=\"545\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Nous contactez')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:contacte()\" class=\"fillclair\"/></a>
        	<text x=\"564\" y=\"563\" style=\"font-size:18px;font-family:fontsvg\" class=\"fillfonce\" pointer-events=\"none\">Y</text></g>
    	<g id=\"zoomin\">
      		<g id=\"graduation\" class=\"fillclair\">
        	<g id=\"moins\"><rect x=\"675\" y=\"185\" width=\"9\" height=\"15\" ry=\"3.7\" rx=\"3\" onmouseover=\"switchColor(evt,'fill','url(clair)','','');\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\" onclick=\"Zoomless(evt);\"/>
			<text x=\"677.5\" y=\"196\" font-size=\"12px\" pointer-events=\"none\" class=\"fillfonce\">-</text></g>
        	<g id=\"plus\"><rect x=\"775.5\" y=\"185\" width=\"9\" height=\"15\" rx=\"3\" ry=\"3.7\" onmouseover=\"switchColor(evt,'fill','url(clair)','','');\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\" onclick=\"Zoommore(evt);\"/>			
     		<text x=\"776.5\" y=\"197\" font-size=\"12px\" pointer-events=\"none\" class=\"fillfonce\">+</text></g>
			<path id=\"zoomcursor\" transform=\"translate(686.75,199)\" d=\"M2.5 0 5 5 0 5Z\"/>";



for ($int=0;$int<19;$int++)
{
$data.="<rect id=\"rectzoom".$min."\" x=\"".$posi."\" y=\"187.5\" width=\"2.5\" height=\"10\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','');showinfotip(evt,'".$min." %')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','');hideinfotip(evt)\" onclick=\"Zoomto(evt,".$min.");\"/>";
$min=$min+$intervale;
$posi=$posi+4.5;
}
$data.="</g><rect id=\"releasezoom\" x=\"6\" y=\"11\" width=\"124\" height=\"124\" style=\"fill:rgb(255,0,0);opacity:0;visibility:hidden;\" pointer-events=\"none\" onclick=\"releaseZoom(evt);\"/>
<rect id=\"rectevt\" x=\"0\" y=\"0\" width=\"0\" height=\"0\" style=\"opacity:0.5;visibility:hidden\" pointer-events=\"none\"/>
<rect id=\"bgrectevt\"  cursor=\"crosshair\" x=\"6\" y=\"11\" width=\"620\" height=\"520\"  style=\"fill:rgb(255,0,255);opacity:0;visibility:hidden\" onmousedown=\"beginResize(evt)\" onmouseup=\"endResize(evt)\" onmousemove=\"doResize(evt)\" pointer-events=\"none\"/>
      		<g id=\"loupe\" class=\"fillclair\" onclick=\"Javascript:Zoomin(evt);\" onmouseover=\"showinfotip(evt,'Zoom dans la carte')\" onmouseout=\"hideinfotip(evt)\">
			
			<use x=\"645\" y=\"181\" ry=\"3\" rx=\"3\" xlink:href=\"#boutonvierge\"/>
			
        	<text x=\"633\" y=\"185\" transform=\"rotate(40,630,200)\" style=\"font-size:25px;font-family:fontsvg\" pointer-events=\"none\" class=\"fillfonce\" >f</text>
      		</g>
      	</g>
    
    	<g id=\"outil\" class=\"fillclair\" font-family=\"fontsvg\">
      		<g id=\"impression\">
       		<a id=\"liprint\"><use id=\"boutonprint\" x=\"645\" y=\"215\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Impression')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:impression()\"/></a>
	   		<text x=\"645\" y=\"234\" style=\"font-size:22px;stroke:url(#survol);stroke-opacity:1;stroke-width:0.4\" pointer-events=\"none\">e</text>
     		</g>
      		<g id=\"simple\" onclick=\"selectunique();changecolor('boutonselect')\" onmouseover=\"showinfotip(evt,'Selection simple')\" onmouseout=\"hideinfotip(evt)\">
	  		<use id=\"boutonselect\" x=\"675\" y=\"215\" stroke='red' xlink:href=\"#boutonvierge\"/>
	  		<text id=\"sel\" x=\"678.5\" y=\"234\" style=\"stroke:url(#survol);font-size:20px;stroke-width:0.3\" pointer-events=\"none\">g</text>
			</g>
			<g id=\"valmultiple\"  visibility='hidden'>
     		<a id='livalide'><rect id='valid' class=\"fillclair\" width=\"22\" height=\"10\" x=\"675\" y=\"241\" ry=\"1.2\" rx=\"1.2\" onclick='validmulti()'/></a>
        	<text id='textvalid' x=\"676.5\" y=\"248.5\" style=\"font-size:6px;font-family:Arial\" class=\"fillfonce\" pointer-events=\"none\">Valider</text>
			</g>";
			if($_SESSION["utcleunik"]!='')
{
      		
      		$data.="<g id=\"regle\">
			<use id=\"boutonregle\" x=\"705\" y=\"215\" xlink:href=\"#boutonvierge\" onclick=\"activetrait(evt);changecolor('boutonregle');\" onmouseover=\"showinfotip(evt,'Mesure de distance')\" onmouseout=\"hideinfotip(evt)\"/>
		  	<text id=\"outi\" x=\"709\" y=\"232\" style=\"font-size:15px\" pointer-events=\"none\" class=\"fillfonce\" >j</text> 
            <g id=\"effacemesure\" visibility='hidden' onclick=\"effacetrait(evt)\">
			<rect class=\"fillclair\" width=\"22\" height=\"10\" x=\"705\" y=\"241\" ry=\"1.2\" rx=\"1.2\"/>
        	<text x=\"706.5\" y=\"248.5\" style=\"font-size:6px;font-family:Arial\" class=\"fillfonce\" pointer-events=\"none\">Effacer</text></g>
			<g id=\"effacesurface\" visibility='hidden' onclick=\"effacetrait(evt)\">
			<rect class=\"fillclair\" width=\"10\" height=\"10\" x=\"717\" y=\"241\" ry=\"1.2\" rx=\"1.2\"/>
        	<text x=\"719\" y=\"248.5\" style=\"font-size:6px;font-family:Arial\" class=\"fillfonce\" pointer-events=\"none\">X</text></g>
			<g id=\"validesurface\" visibility='hidden' onclick=\"validesurface(evt)\">
			<rect class=\"fillclair\" width=\"10\" height=\"10\" x=\"705\" y=\"241\" ry=\"1.2\" rx=\"1.2\"/>
        	<text x=\"705.5\" y=\"248.5\" style=\"font-size:6px;font-family:Arial\" class=\"fillfonce\" pointer-events=\"none\">OK</text></g>
			
			</g>";
			if($cou[0]['btn_polygo']!="")
			{
      		$data.="<g id=\"polygo\">
			<use id=\"boutonpolygo\" x=\"735\" y=\"215\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'".$cou[0]['libelle_btn_polygo']."')\" onmouseout=\"hideinfotip(evt)\" onclick=\"activpoly(evt);changecolor('boutonpolygo');\"/>
        	<text x=\"736.5\" y=\"234\" style=\"fill:none;stroke:url(#survol);font-size:22px;stroke-width:0.8\" pointer-events=\"none\">n</text>
			<g id=\"validepolygo\" visibility='hidden'  onclick=\"validepoly(evt)\">
			<a id='livalidepolygo'><rect class=\"fillclair\" width=\"10\" height=\"10\" x=\"735\" y=\"241\" ry=\"1.2\" rx=\"1.2\"/></a>
        	<text x=\"735.5\" y=\"248.5\" style=\"font-size:6px;font-family:Arial\" pointer-events=\"none\" class=\"fillfonce\">OK</text></g>
			<g id=\"effacepolygo\" visibility='hidden' onclick=\"effacetrait(evt)\">
			<rect class=\"fillclair\" width=\"10\" height=\"10\" x=\"747\" y=\"241\" ry=\"1.2\" rx=\"1.2\"/>
			<text x=\"749\" y=\"249.5\" style=\"font-size:9px;font-family:Arial\" pointer-events=\"none\" class=\"fillfonce\">X</text></g>
        	</g>
    	</g>";}else{$data.="</g>";}
$data.="<g id=\"enregistre\">
			<a id=\"lienre\"><use id=\"enre\" x=\"765\" y=\"215\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Enregistrement')\" onmouseout=\"hideinfotip(evt)\" onclick=\"enregistre()\" class=\"fillclair\"/></a>
        	<text x=\"766.5\" y=\"234\" style=\"font-size:22px;font-family:fontsvg\" class=\"fillfonce\" pointer-events=\"none\">Z</text>
";
}
     	
		$data.="</g><g id=\"recherche\">
		<rect y=\"155\" x=\"645\" height=\"15\" width=\"98\" onclick=\"entre_dim(evt)\"/>
		<text id=\"x_rect\" x=\"646\" y=\"167\" style=\"font-size:10px;fill-opacity:1;pointer-events:none\" class=\"fillfonce\">?</text>
     	<text id=\"cligno1\" x=\"646\" y=\"165\" style=\"font-size:10px;fill-opacity:1;pointer-events:none;visibility:hidden;stroke:2\">|</text>
		<text y=\"152\" x=\"645\" style=\"stroke:url(#survol);stroke-width:0.5;font-size:12px;fill-opacity:1\" class=\"fillfonce\">Recherche</text>
      	<rect class=\"fillclair\" width=\"15\" height=\"15\" x=\"747\" y=\"155\" ry=\"3.7406483\" rx=\"3.7406483\" onclick=\"recherche()\"/>
      	<text style=\"stroke:url(#survol);stroke-width:0.5;font-size:8.5px;font-family:Arial;pointer-events:none\" x=\"748\" y=\"166\" class=\"fillfonce\">OK</text>
      	</g>
    	
		<g id=\"echelle\" class=\"fillfonce\" > 
		<rect style=\"fill:white\" id=\"rect2206\" width=\"90\" height=\"18\" x=\"50\" y=\"555\"/>
    	<rect y=\"564\" x=\"50\" height=\"9\" width=\"45\" id=\"rect2208\" style=\"fill:url(#gra1)\"/>
    	<rect y=\"555\" x=\"95\" height=\"9\" width=\"45\" id=\"rect3996\" style=\"fill:url(#gra2)\"/>
		<text y=\"586\" x=\"87\" style=\"stroke:url(#survol);stroke-width:0.5;font-size:12px;fill-opacity:1\">KM</text>
		<text id=\"gauche\" y=\"553\" x=\"50\" style=\"stroke:url(#survol);text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1\">0</text>
		<text id=\"centre\" y=\"553\" x=\"95\" style=\"stroke:url(#survol);text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1\">1</text>
		<text id=\"droite\" y=\"553\" x=\"140\" style=\"stroke:url(#survol);text-anchor:middle;stroke-width:0.5;font-size:12px;fill-opacity:1\">2</text>
  		</g>
		
		
		<g id=\"legende\" transform=\"translate(0,0)\">
		<text x=\"645\" y=\"273\" style=\"stroke:url(#survol);stroke-width:0.5;font-size:12px\" class=\"fillfonce\">Legende</text>
		<svg id=\"leg\"  x=\"645\" y=\"279\" height=\"305\" width=\"148\" viewBox=\"636 279 148 305\">
		
		<rect width=\"148\" height=\"305\" x=\"636\" y=\"279\" ry=\"0\"/>
		<rect width=\"12\" height=\"305\" x=\"772\" y=\"279\" />
		<g id=\"curseur\">
    	<rect id=\"scroll_cursor\" pointer-events=\"visible\" class=\"fillclair\" width=\"12\" height=\"281\" x=\"772\" y=\"291\" onmouseup=\"liste_glisse_click(evt,'false')\" onmousedown=\"liste_glisse_click(evt,'true')\" onmousemove=\"liste_glisse(evt)\" onmouseout=\"liste_glisse_click(evt,'false')\"/>
		</g>
    	<rect width=\"12\" height=\"12\" x=\"772\" y=\"279\" class=\"fillfonce\" onclick=\"liste_scrolling(-1)\"/>
    	<rect y=\"572\" x=\"772\" height=\"12\" width=\"12\" class=\"fillfonce\" onclick=\"liste_scrolling(1)\"/>
		<path transform=\"translate(775.5,282.5)\" pointer-events=\"none\" class=\"fillclair\" d=\"M2.5 0 5 5 0 5Z\"/>
		<path transform=\"translate(775.5,575.5)\" pointer-events=\"none\" class=\"fillclair\" d=\"M2.5 5 5 0 0 0Z\"/>
		<g id=\"layer\">";
$data.=$legende;

$data.="</g></svg>
		</g>
		
		
<g id=\"inforecherche\" visibility=\"hidden\" class=\"fillfonce\" font-size=\"14\" opacity=\"0.8\">
<rect width=\"260\" height=\"400\" x=\"186\" y=\"66\" fill=\"url(#clair)\"/> 
<text pointer-events=\"none\" x=\"316\" y=\"100\" text-anchor=\"middle\" >Resultat de la recherche</text>

<g id=\"controlrecherche\" style=\"font-size:10;\" >
</g>

<rect id='closerect' x=\"291\" y=\"440\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"hideAbout();clear('controlrecherche')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"316\" y=\"452.5\">Fermer</text>

<g id=\"numer\" visibility=\"hidden\" font-size=\"10\">
<text x=\"239\" y=\"130\" stroke=\"none\">N&#176;</text>

<text id=\"x_rectnum\" x=\"231\" y=\"140\" style=\"font-size:8;font-family:Arial;\">?</text>
<rect onclick=\"entre_dimnum(evt)\" x=\"230\" y=\"131\" width=\"30\" height=\"12\" fill-opacity=\"0\"/>
<text id=\"adress\" x=\"261\" y=\"140\" style=\"font-size:10;font-family:Arial;\">?</text>
<text id=\"adre\" x=\"200\" y=\"11\" style=\"font-size:8;font-family:Arial;fill:black;visibility:hidden\" >?</text>
<text id=\"cligno2\" x=\"231\" y=\"139\" style=\"font-size:10px;fill-opacity:1;pointer-events:none;visibility:hidden;stroke:2\">|</text>
<rect x=\"309\"  y=\"148\" width=\"15\" height=\"15\" rx=\"1.5\" ry=\"1.5\" class=\"fillclair\" onclick=\"recher()\"/>
<text  x=\"316\" y=\"159\" text-anchor=\"middle\" pointer-events=\"none\">ok</text>
</g>

</g>	
		
</g> 
<g id=\"infotips\">
	<rect id=\"infotipRect\" x=\"20\" y=\"0\" width=\"100\" height=\"14\" rx=\"3\" ry=\"3\" class=\"fillclair\" stroke-width=\"1\" stroke=\"rgb(0,0,0)\" opacity=\"0.8\" pointer-events=\"none\" visibility=\"hidden\"></rect> 
	<text id=\"infotip\" x=\"25\" y=\"11\"  style=\"font-weight:normal;font-family:'Arial';font-size:8;text-anchor:left;pointer-events:none\" visibility=\"hidden\" >!</text>
</g>
</svg>";
//$data=gzcompress("$data",9);
echo $data;
?>