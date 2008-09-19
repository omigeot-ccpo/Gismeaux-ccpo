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
define('GIS_ROOT', '../..');
include_once(GIS_ROOT . '/inc/common.php');
gis_session_start();
if (eregi('MSIE', $_SERVER['HTTP_USER_AGENT']))
{    
$nav="0";// Internet Explorer 
}
elseif (eregi('Opera', $_SERVER['HTTP_USER_AGENT']))
{ 
$nav="1";//opÃ©ra
}
else
{
$nav="2";//mozilla
}
$os = "";
if (ereg("Linux", getenv("HTTP_USER_AGENT"))) 
  $os = "Linux"; 
$sessi=session_id();
//include("../../connexion/deb.php");
$reqcom="select (commune.xma::real - commune.xmi::real) as largeur,commune.xmi as xini, (commune.yma::real - commune.ymi::real) as hauteur ,commune.yma as yini, (commune.xmi::real + (commune.xma::real - commune.xmi::real)/2) as xcenter,(commune.ymi::real + (commune.yma::real - commune.ymi::real)/2) as ycentre from admin_svg.commune where commune.idcommune like '".$_SESSION["profil"]->insee."'";

		$vu=$DB->tab_result($reqcom);
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
$req2="select theme.idtheme,theme.libelle_them as nom_theme,theme.schema,theme.tabl,appthe.idappthe,col_theme.colonn,admin_svg.v_fixe(col_theme.valeur_texte),appthe.raster,sinul(appthe.zoommin::character varying,theme.zoommin::character varying) as zoommin,sinul(appthe.zoommax::character varying,theme.zoommax::character varying) as zoommax,sinul(appthe.zoommaxraster::character varying,theme.zoommax_raster::character varying) as zoommax_raster,theme.raster as testraster,application.zoom_min as zoom_min_appli,application.zoom_max as zoom_max_appli,application.zoom_ouverture as zoom_ouverture_appli,sinul(appthe.partiel,theme.partiel) as partiel,sinul(appthe.vu_initial,theme.vu_initial) as vu_initial,style.idstyle,style.fill as style_fill,style.symbole as style_symbole,style.opacity  as style_opacity,style.font_size  as style_fontsize,style.stroke_rgb  as style_stroke,style.stroke_width  as style_strokewidth,application.btn_polygo,application.libelle_btn_polygo from admin_svg.appthe join admin_svg.theme on appthe.idtheme=theme.idtheme join admin_svg.application on appthe.idapplication=application.idapplication left outer join  admin_svg.col_theme on appthe.idappthe=col_theme.idappthe left outer join admin_svg.style on appthe.idtheme=style.idtheme where appthe.idapplication='".$_SESSION["appli"]."' group by theme.idtheme,theme.libelle_them,appthe.ordre,theme.schema,theme.tabl,col_theme.colonn,admin_svg.v_fixe(col_theme.valeur_texte),appthe.raster,theme.zoommin,appthe.zoommin,theme.zoommax,appthe.zoommax,theme.zoommax_raster,appthe.zoommaxraster,theme.raster,application.zoom_min,application.zoom_max,application.zoom_ouverture,appthe.partiel,theme.partiel,appthe.vu_initial,theme.vu_initial,style.idstyle,style.fill,style.symbole,style.opacity,style.font_size,style.stroke_rgb,style.stroke_width,appthe.idappthe,application.btn_polygo,application.libelle_btn_polygo order by appthe.ordre asc";
$cou=$DB->tab_result($req2);
if($cou[0]['zoom_min_appli']=="")
{
$sqql="select zoom_min as zoom_min_appli,application.zoom_max as zoom_max_appli,application.zoom_ouverture as zoom_ouverture_appli from admin_svg.application where idapplication='".$_SESSION["appli"]."'";
$cou1=tab_result($pgx,$sqql);
$zoommin=$cou1[0]['zoom_min_appli'];
$zoommax=$cou1[0]['zoom_max_appli'];
$zoomouv=$cou1[0]['zoom_ouverture_appli'];
}
else
{
$zoommin=$cou[0]['zoom_min_appli'];
$zoommax=$cou[0]['zoom_max_appli'];
$zoomouv=$cou[0]['zoom_ouverture_appli'];
}
$min=$zoommin;
$intervale=round(($zoommax-$zoommin)/18);
$zoommax=$zoommin+(18*$intervale);
if($_SESSION['zoommm'])
	{
	if($_SESSION['zoommm']>=$zoommin)
	{ 
	$zo=$_SESSION['zoommm'];
	}
	else
	{
	$zo=$zoommin;
	}
	if($_SESSION['zoommm']>=$zoommax)
	{ 
	$zo=$zoommin;
	}
	$xc=$_SESSION['cx'];
	$yc=$_SESSION['cy'];
	}
else
{
$zo=$zoomouv;
	$xc=0;
	$yc=0;
}
if($zo!=$zoommin)
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
$req1="select distinct (col_theme.intitule_legende) as intitule_legende,col_theme.idappthe,col_theme.fill,col_theme.stroke_rgb,col_theme.stroke_width,col_theme.symbole,col_theme.font_size,col_theme.font_familly,col_theme.opacity,col_theme.ordre from admin_svg.appthe join admin_svg.col_theme on appthe.idappthe=col_theme.idappthe join admin_svg.theme on appthe.idtheme=theme.idtheme ";
	if($cou[$c]['v_fixe']=='1' and $cou[$c]['colonn']<>'')
	{
	$req1.=" join ".$cou[$c]['schema'].".".$cou[$c]['tabl']." on col_theme.valeur_texte=".$cou[$c]['tabl'].".".$cou[$c]['colonn']." where 					appthe.idapplication='".$_SESSION["appli"]."' and theme.libelle_them='".$cou[$c]['nom_theme']."'";
	if($cou[$c]['schema']!="bd_topo")
	{
		if(substr($_SESSION["profil"]->insee, -3)!='000' )
				{$req1.=" and (".$cou[$c]['tabl'].".code_insee like '".$_SESSION["profil"]->insee."'  or code_insee is null) ";}
		else{$req1.=" and (".$cou[$c]['tabl'].".code_insee like '".substr($_SESSION["profil"]->insee,0,3)."%'  or code_insee is null) ";}
	 }
	 $req1.=" order by col_theme.ordre asc";
	}
	
	else
	{
	$req1.=" where appthe.idapplication='".$_SESSION["appli"]."' and theme.libelle_them='".$cou[$c]['nom_theme']."' order by col_theme.ordre asc";
	}
	$couch=$DB->tab_result($req1);
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
$legende.="<a><text id=\"coul".$z."\" x=\"660\" y=\"".($y+8)."\" font-size=\"12\" stroke=\"none\" font-family=\"fontsvg\" fill=\"rgb(".$cou[$c]['style_fill'].")\" onclick=\"affiche_gestion(evt,'".$cou[$c]['style_fill']."','".$cou[$c]['style_opacity']."','".$cou[$c]['style_stroke']."','".$cou[$c]['style_strokewidth']."','style','".$cou[$c]['style_fontsize']."','".$cou[$c]['style_symbole']."','".$cou[$c]['idstyle']."','".$z."','".$cou[$c]['idtheme']."')\">".$cou[$c]['style_symbole']."</text></a>\n";
}
else
{
	if($cou[$c]['style_stroke']!="" && ($cou[$c]['style_fill']==""||$cou[$c]['style_fill']=="none"))
	{
	$legende.="<a><rect id=\"coul".$z."\" x=\"662\" y=\"".$y."\" width=\"8\" height=\"8\" n=\"ok\" fill=\"rgb(".$cou[$c]['style_stroke'].")\" onclick=\"affiche_gestion(evt,'".$cou[$c]['style_fill']."','".$cou[$c]['style_opacity']."','".$cou[$c]['style_stroke']."','".$cou[$c]['style_strokewidth']."','style','".$cou[$c]['style_fontsize']."','".$cou[$c]['style_symbole']."','".$cou[$c]['idstyle']."','".$z."','".$cou[$c]['idtheme']."')\"/></a>\n";
	}
	else
	{
	$legende.="<a><rect id=\"coul".$z."\" x=\"662\" y=\"".$y."\" width=\"8\" height=\"8\" n=\"ok\" fill-opacity=\"".$cou[$c]['style_opacity']."\" fill=\"rgb(".$cou[$c]['style_fill'].")\" onclick=\"affiche_gestion(evt,'".$cou[$c]['style_fill']."','".$cou[$c]['style_opacity']."','".$cou[$c]['style_stroke']."','".$cou[$c]['style_strokewidth']."','style','".$cou[$c]['style_fontsize']."','".$cou[$c]['style_symbole']."','".$cou[$c]['idstyle']."','".$z."','".$cou[$c]['idtheme']."')\"/></a>\n";
	}
}
}
$legende.="<a onclick=\"gest_control_choix(evt,".$cou[$c]['idappthe'].")\"><text id=\"text".$z."\" x=\"674\" y=\"".($y+8)."\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','url(#survol)','','')\"  class=\"fillfonce\">".$cou[$c]['nom_theme']."</text></a>\n";
$posilegend.="posilegend[".$z."]='".$cou[$c]['idappthe'].".".$cou[$c]['nom_theme']."';";
$posilegendini.="posilegendini[".$z."]='".$cou[$c]['idappthe'].".".$cou[$c]['nom_theme']."';";
$txt_ordre_legend.="<text id=\"".$cou[$c]['idappthe'].".".$cou[$c]['nom_theme']."\" x=\"327\" y=\"".(80+($z*15))."\" text-anchor=\"middle\" font-size='10' onmouseover=\"sur(evt,'red')\" onmouseout=\"hors(evt)\" onclick=\"sel_txt_legende(evt,'".$z."')\">".$cou[$c]['nom_theme']."</text>\n";
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
			$legende.="<a><text id=\"coul".$z.codalpha($w+1)."\" x=\"672\" y=\"".($y1+8)."\" font-size=\"12\" stroke=\"none\" font-family=\"fontsvg\" fill=\"rgb(".$coucoul.")\" onclick=\"affiche_gestion(evt,'".$couch[$w]['fill']."','".$couch[$w]['opacity']."','".$couch[$w]['stroke_rgb']."','".$couch[$w]['stroke_width']."','theme','".$couch[$w]['font_size']."','".$couch[$w]['symbole']."','".$cou[$c]['idappthe'].".".$couch[$w]['intitule_legende']."','".$z.codalpha($w+1)."','".$cou[$c]['idtheme']."')\">".$couch[$w]['symbole']."</text></a>\n";
			}
			else
			{
			
			$legende.="<a><rect id=\"coul".$z.codalpha($w+1)."\" x=\"674\" y=\"".$y1."\" width=\"8\" height=\"8\" fill=\"rgb(".$coucoul.")\" onclick=\"affiche_gestion(evt,'".$couch[$w]['fill']."','".$couch[$w]['opacity']."','".$couch[$w]['stroke_rgb']."','".$couch[$w]['stroke_width']."','theme','".$couch[$w]['fontsize']."','".$couch[$w]['symbole']."','".$cou[$c]['idappthe'].".".$couch[$w]['intitule_legende']."','".$z.codalpha($w+1)."','".$cou[$c]['idtheme']."')\"/></a>\n";
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
if (file_exists("../../doc_commune/".$_SESSION["profil"]->insee."/css_interface/interface.css"))
{
$data.="<?xml-stylesheet href=\"../../doc_commune/".$_SESSION["profil"]->insee."/css_interface/interface.css\" type=\"text/css\" ?>";
}
else
{
$data.="<?xml-stylesheet href=\"../css/default.css\" type=\"text/css\" ?>";
}
$data.="<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.0//EN\" \"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd\">";
$data.="<svg id=\"svg2\" xmlns:xlink='http://www.w3.org/1999/xlink' width=\"100%\" heigth=\"100%\" viewBox=\"0 0 800 600\" onkeypress=\"tape(evt)\" onload=\"init(evt);".$extraction."\" onmousemove=\"determ_ratio()\">";
$data.="<rect id=\"rectdefond\" width=\"100%\" height=\"100%\" x=\"0\" y=\"0\" fill=\"white\"/>"; 
$data.="<image x=\"156.25\" y=\"0\" width=\"487.5\" height=\"32.25\" xlink:href=\"./headbackoffice.PNG\"/>";
$data.="<script xlink:href=\"script.js\" language=\"JavaScript\"></script>";
$data.="<script><![CDATA[\n";
$data.="nav=".$nav."\n";
$data.="var posilegend=new Array;".$posilegend."\n";
$data.="var posilegendini=new Array;".$posilegendini."\n";
$gestion_ordre="<g id=\"gestion_ordre\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_ordre\" width=\"250\" height=\"".(70+($z*15))."\" x=\"202\" y=\"66\" class=\"defaut\"/> 
<rect width=\"249\" height=\"18\" x=\"202.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"327\" y=\"80\" text-anchor=\"middle\" >Ordre des couches</text>
<rect id=\"up_txt_legende\" pointer-events=\"none\" opacity=\"0.5\" x=\"300\" y=\"".(85+($z*15))."\" rx=\"1.5\" ry=\"1.5\" width=\"15\" height=\"15\" class=\"fillfonce\" onclick=\"up_txt_legende(evt)\" onmouseover=\"switchColor(evt,'fill','red','','tri_txt_legende')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','tri_txt_legende')\"/> 
<rect id=\"down_txt_legende\" pointer-events=\"none\" opacity=\"0.5\" x=\"339\" y=\"".(85+($z*15))."\" rx=\"1.5\" ry=\"1.5\" width=\"15\" height=\"15\" class=\"fillfonce\" onclick=\"down_txt_legende(evt)\" onmouseover=\"switchColor(evt,'fill','red','','tri1_txt_legende')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','tri1_txt_legende')\"/>
<path id=\"tri_txt_legende\" transform=\"translate(305,".(90+($z*15)).")\" pointer-events=\"none\" class=\"fillclair\" d=\"M2.5 0 5 5 0 5Z\" />
<path id=\"tri1_txt_legende\" transform=\"translate(344,".(90+($z*15)).")\" pointer-events=\"none\" class=\"fillclair\" d=\"M2.5 5 5 0 0 0Z\" />
<rect id=\"ferme_panneau_ordre\" x=\"265\" y=\"".(110+($z*15))."\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'gestion_ordre')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"290\" y=\"".(122.5+($z*15))."\">Fermer</text>
<a id=\"liordre\"><rect id=\"valide_panneau_ordre\" x=\"339\" y=\"".(110+($z*15))."\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"valide_ordre(evt)\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"364\" y=\"".(122.5+($z*15))."\">Valider</text>
";
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
$data.="function actualise(){
	window.location.reload()
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
$data.="appli=".$_SESSION["appli"]."; \n";
$data.="sessionid='".$sessi."';\n";
$data.="sessionname='".session_name()."';\n";
$data.="code_insee=".$_SESSION["profil"]->insee.";\n";
$data.="xini=".$vu[0]['xini'].";\n";
$data.="yini=".$vu[0]['yini'].";\n";
$data.="largeurini=".$_SESSION['large'].";\n";
$data.="hauteurini=".$_SESSION['haute'].";\n";
$data.="serveur='".$_SERVER['HTTP_HOST']."';\n";
$data.="zoom_init=100;\n";
//$data.="";
$data.="]]></script>
  <defs>
  <path id=\"chemin_sur\" d=\"M 226 190 465 190 M 226 200 465 200 M 226 210 465 210 M 226 220 465 220\" style=\"stroke:black;fill:none\" />
  <path id=\"chemin_lien\" d=\"M 226 240 465 240 M 226 250 465 250 M 226 260 465 260 M 226 270 465 270\" style=\"stroke:black;fill:none\" />
  <path id=\"chemin_hors\" d=\"M 226 290 465 290 M 226 300 465 300 M 226 310 465 310 M 226 320 465 320\" style=\"stroke:black;fill:none\" />
  <path id=\"chemin_mess1\" d=\"M 197 142.5 436 142.5 M 197 152.5 436 152.5 M 197 162.5 436 162.5 M 197 172.5 436 172.5\" style=\"stroke:black;fill:none\" />
  <path id=\"chemin_mess2\" d=\"M 197 202.5 436 202.5 M 197 212.5 436 212.5 M 197 222.5 436 222.5 M 197 232.5 436 232.5\" style=\"stroke:black;fill:none\" />
  <path id=\"chemin_clause\" d=\"M 176 265 456 265 M 176 275 456 275 M 176 285 456 285 M 176 295 456 295\" style=\"stroke:black;fill:none\" />
  <path id=\"chemin_requete\" d=\"M 176 125 456 125 M 176 135 456 135 M 176 145 456 145 M 176 155 456 155\" style=\"stroke:black;fill:none\" />
  <path id=\"chemin_r_thematique\" d=\"M 186 110 447 110 M 186 120 447 120 M 186 130 447 130 M 186 140 447 140\" style=\"stroke:black;fill:none\" />
  <font id=\"perso\" horiz-adv-x=\"1539\" ><font-face
    font-family=\"fontsvg\"
    units-per-em=\"2048\"
    panose-1=\"2 0 0 0 0 0 0 0 0 0\"
    ascent=\"1854\"
    descent=\"-434\"
    alphabetic=\"0\" />
<missing-glyph horiz-adv-x=\"1536\" d=\"M256 0V1280H1280V0H256ZM288 32H1248V1248H288V32Z\" />";
$texte=fopen("../police.svg","r");
			$contents = fread($texte, filesize ("../police.svg"));
			$data1=explode("<",$contents);
			$tab_symbol=array();
				for ($i=1;$i<count($data1);$i++)
				{
					$pos1=0;$pos2=0;
					if(ereg('unicode="',$data1[$i]) && ereg('d="',$data1[$i]))
					{ 
					$symbol.="<".$data1[$i]."\n";
					$pos1 = strpos($data1[$i], 'unicode="');
					$pos2 = strpos($data1[$i],'"',$pos1+9);
					$lettre=substr($data1[$i], $pos1+9, (($pos1+9)-($pos2-2)));
					$textsymbol.="<text id=\"".$lettre."\" font-family=\"fontsvg\" fill=\"rgb(0,0,0)\" font-size=\"10\" >".$lettre."</text>"; 
					array_push($tab_symbol,$lettre);
					}
				}
			fclose($texte);
$nb_symbol=count($tab_symbol);
$nbligne_symbol=ceil($nb_symbol/16);
$ysymbole=87;
$xsymbole=152;
$cont_symbol=1;
for ($i=0;$i<($nbligne_symbol*16);$i++)
				{
				
				if($i<$nb_symbol)
				{
				$des_symbol.="<a><rect id=\"cont_symb".$i."\" width=\"20\" height=\"20\" x=\"".$xsymbole."\" y=\"".$ysymbole."\" class=\"defaut\" onmouseover=\"switchColor(evt,'fill','red','','cont_symb".$i."')\" onmouseout=\"switchColor(evt,'fill','none','','cont_symb".$i."')\" onclick=\"select_symb('".$tab_symbol[$i]."')\"/></a>";
				$des_symbol.="<text id=\"sym".$tab_symbol[$i]."\" font-family=\"fontsvg\" fill=\"rgb(0,0,0)\" font-size=\"15\" pointer-events=\"none\" x=\"".($xsymbole+9)."\" y=\"".($ysymbole+15)."\" text-anchor=\"middle\" >".$tab_symbol[$i]."</text>";
				}
				else
				{
				$des_symbol.="<rect id=\"cont_symb".$i."\" width=\"20\" height=\"20\" x=\"".$xsymbole."\" y=\"".$ysymbole."\" class=\"defaut\"/>";
				}
				if($cont_symbol==16)
				{
				$ysymbole=$ysymbole+22;
				$xsymbole=152;
				$cont_symbol=1;
				}
				else
				{
				$cont_symbol=$cont_symbol+1;
				$xsymbole=22+$xsymbole;
				}
				
				}
$tableau_symbol="<g id=\"gestion_symbol\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_symbol\" width=\"354\" height=\"".(21+($nbligne_symbol*22))."\" x=\"150\" y=\"66\" class=\"defaut\"/> 
<rect width=\"353.5\" height=\"18\" x=\"150.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"327\" y=\"80\" text-anchor=\"middle\" >Choix du symbole</text>";
$data.=$symbol;
$data.="</font>";
if($os=="Linux")
{
$texte=fopen("../linux_arial.svg","r");
$contents = fread($texte, filesize ("../linux_arial.svg"));
$data.=$contents;
fclose($texte);
}
$data.=$textsymbol;
$data.=$lettre;
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
   <svg id=\"mapid\"  x=\"11\" y=\"45\" width=\"620\" height=\"520\" viewBox=\"0 0 ".$_SESSION['large']." ".$_SESSION['haute']."\"    onmouseover=\"desinib_use();\">
<g id=\"enregistrement\">
<rect id=\"desrect\" x=\"0\" y=\"45\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" stroke=\"none\" fill=\"white\" pointer-events=\"none\"/>	
	<g id=\"dessin\">";
$data.=$controle;	
$data.="</g>
<g id='dess' stroke-width='0.2'>
	</g>
</g>
	</svg>
	<g id=\"cardinal\" style=\"stroke:none\">
	<rect x=\"0\" y=\"45\" width=\"11\" height=\"554\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','ouest')\" onmouseout=\"switchColor(evt,'fill','none','','ouest')\" onclick=\"goWest();\"/>
	<path id=\"ouest\" pointer-events=\"none\" d=\"M 0 305 11 234 11 376z\" />
	<text pointer-events=\"none\" x=\"2\" y=\"307\" class=\"coulblanc\" font-size=\"8\">W</text>
	<rect x=\"631\" y=\"45\" width=\"11\" height=\"554\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','est')\" onmouseout=\"switchColor(evt,'fill','none','','est')\" onclick=\"goEast();\"/>
	<path id=\"est\" pointer-events=\"none\" d=\"M 642 305 631 234 631 376z\" />
	<text pointer-events=\"none\" x=\"633\" y=\"307\" class=\"coulblanc\" font-size=\"8\">E</text>
	<rect x=\"11\" y=\"34\" width=\"620\" height=\"11\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','nord')\" onmouseout=\"switchColor(evt,'fill','none','','nord')\" onclick=\"goNorth();\"/>
	<path id=\"nord\" pointer-events=\"none\" d=\"M 321 34 250 44 392 44z\"/>
	<text pointer-events=\"none\" x=\"318\" y=\"43\" class=\"coulblanc\" font-size=\"8\">N</text>
	<rect x=\"11\" y=\"566\" width=\"620\" height=\"11\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','sud')\" onmouseout=\"switchColor(evt,'fill','none','','sud')\" onclick=\"goSouth();\"/>
	<path id=\"sud\" pointer-events=\"none\" d=\"M 321 576 250 566 392 566z\" />
	<text pointer-events=\"none\" x=\"318\" y=\"574\" class=\"coulblanc\" font-size=\"8\">S</text>
	</g>
	<rect id=\"map\" width=\"620\" height=\"520\" x=\"11\" y=\"45\" fill=\"none\" pointer-events=\"none\"/>
	<text pointer-events=\"none\" x=\"20\" y=\"549\" class=\"fillfonce\" style=\"font-size:50px;font-family:fontsvg\">a</text>
	<g id=\"message_box\" visibility=\"hidden\" >
	<rect x=\"241\" y=\"264\" width=\"160\" height=\"18\" class=\"fillclair\" style=\"opacity:0.6\" pointer-events=\"none\" />
	<text pointer-events=\"none\" x=\"250\" y=\"277\" font-size=\"15\" class=\"fillfonce\">Veuillez patienter</text>
	<g id=\"anim\">
	<path fill-opacity=\"0\" d=\"M 380 268 A 5 5 0 1 1 375 273 M 374 275.5 375 273 377.5 274z\" />
	<animateTransform id=\"anim_arr\" attributeName=\"transform\" begin=\"indefinite\"  attributeType=\"XML\" type=\"rotate\" from=\"0 380 273\" to=\"2160 380 273\" dur=\"10s\" repeatDur=\"indefinite\"/>
	</g>
	</g>
	<svg id=\"overviewmap\" x=\"645\" y=\"45\" width=\"150\" height=\"126\" viewBox=\"0 0 ".$_SESSION['large']." ".$_SESSION['haute']."\" onmouseover=\"inib_use();\" >";
    if (file_exists("../communes/".$_SESSION["profil"]->insee.".JPG"))
	{
	$data.="<image id=\"fond\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"../communes/".$_SESSION["profil"]->insee.".JPG\"/>";
	}
	else
	{
	$data.="<image id=\"fond\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"../communes/default.JPG\"/>";
	}
	$data.="<use x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"#dessin\"/>
	<g onmousedown=\"beginPan(evt)\" onmousemove=\"doPan(evt)\" onmouseup=\"endPan(evt)\" onmouseout=\"endPan(evt)\">
	<rect id=\"Rect1\" cursor=\"move\" style=\"fill:rgb(255,0,0);stroke-width:20;stroke:rgb(0,0,0);fill-opacity:0.4\" x=\"0\" y=\"0\"
 width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" visibility=\"hidden\" />
 <rect id=\"lin1\" stroke-width=\"20\" fill=\"rgb(0,0,0)\" x=\"0\" y=\"".($_SESSION['haute']/2)."\" width=\"".$_SESSION['large']."\" height=\"20\" visibility=\"hidden\"/>
 <rect id=\"lin2\" stroke-width=\"20\" fill=\"rgb(0,0,0)\" x=\"".($_SESSION['large']/2)."\" y=\"0\" width=\"20\" height=\"".$_SESSION['haute']."\" visibility=\"hidden\"/>

	<rect id=\"locationRect\" cursor=\"move\" class=\"fillfonce\" style=\"fill-opacity:0.5\" x=\"0\" y=\"0\"
 width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\"  visibility=\"hidden\"/>
 </g></svg>
	<rect id=\"locamap\" width=\"150\" height=\"126\" x=\"645\" y=\"45\" fill=\"none\" pointer-events=\"none\"/>
    
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
$data.="</g></g>
    <g id=\"outil\" class=\"fillclair\" font-family=\"fontsvg\">
      		<g id=\"retour\">
       		<a id=\"liretour\"><use id=\"boutonretour\" x=\"645\" y=\"215\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Accueil')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:retour()\"/></a>
	   		<text x=\"645\" y=\"234\" style=\"font-size:22px;stroke:url(#survol);stroke-opacity:1;stroke-width:0.4\" pointer-events=\"none\">p</text>
     		</g>
			<g id=\"ajout_theme\">
       		<a id=\"li_ajout_theme\"><use id=\"boutonajout_theme\" x=\"675\" y=\"215\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Ajouter un th&#x00E8;me a l&#x00B4;application')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:ajout_the()\"/></a>
	   		<text x=\"676.5\" y=\"232.5\" style=\"font-size:22px;stroke:url(#survol);stroke-opacity:1;stroke-width:0.4\" pointer-events=\"none\">h</text>
     		</g>
			<g id=\"creer_theme\">
       		<a id=\"li_creer_theme\"><use id=\"boutoncreer_theme\" x=\"705\" y=\"215\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Creer un th&#x00E8;me')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:affiche_script('gestion_creation_theme')\"/></a>
	   		<text x=\"707.5\" y=\"234\" style=\"font-size:22px;stroke:url(#survol);stroke-opacity:1;stroke-width:0.4\" pointer-events=\"none\">g</text>
     		</g>
			<g id=\"mod_theme\">
       		<a id=\"li_mod_theme\"><use id=\"boutonmod_theme\" x=\"735\" y=\"215\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Modifier un th&#x00E8;me')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:mod_the()\"/></a>
	   		<text x=\"736.5\" y=\"234\" style=\"font-size:22px;stroke:url(#survol);stroke-opacity:1;stroke-width:0.4\" pointer-events=\"none\">v</text>
     		</g>";
      	$data.="</g>
    	<g id=\"legende\" transform=\"translate(0,0)\">
		<text x=\"645\" y=\"273\" style=\"stroke:url(#survol);stroke-width:0.5;font-size:12px\" class=\"fillfonce\">L&#x00E9;gende</text>
		<svg id=\"leg\"  x=\"645\" y=\"279\" height=\"305\" width=\"148\" viewBox=\"636 279 148 305\">
		
		<rect width=\"148\" height=\"305\" x=\"636\" y=\"279\" ry=\"0\"/>
		<rect width=\"12\" height=\"305\" x=\"772\" y=\"279\" />
		<g id=\"curseur\">
    	<rect id=\"scroll_cursor\" pointer-events=\"visible\" class=\"fillclair\" width=\"12\" height=\"281\" x=\"772\" y=\"291\" onmouseup=\"liste_glisse_click(evt,'false')\" onmousedown=\"liste_glisse_click(evt,'true')\" onmousemove=\"liste_glisse(evt)\" onmouseout=\"liste_glisse_click(evt,'false')\"/>
		</g>
		<a><rect width=\"8\" height=\"8\" x=\"761\" y=\"282\" class=\"fillclair\" onclick=\"ouvre_mod_legend(evt)\"/></a>
		<text x=\"762\" y=\"287\" class=\"fillfonce\" pointer-events=\"none\">..</text>
    	<rect width=\"12\" height=\"12\" x=\"772\" y=\"279\" class=\"fillfonce\" onclick=\"liste_scrolling(-1)\"/>
    	<rect y=\"572\" x=\"772\" height=\"12\" width=\"12\" class=\"fillfonce\" onclick=\"liste_scrolling(1)\"/>
		<path transform=\"translate(775.5,282.5)\" pointer-events=\"none\" class=\"fillclair\" d=\"M2.5 0 5 5 0 5Z\"/>
		<path transform=\"translate(775.5,575.5)\" pointer-events=\"none\" class=\"fillclair\" d=\"M2.5 5 5 0 0 0Z\"/>
		<g id=\"layer\">";
$data.=$legende;
$data.="</g></svg>
		</g>
</g> 
<g id=\"infotips\">
	<rect id=\"infotipRect\" x=\"20\" y=\"0\" width=\"100\" height=\"14\" rx=\"3\" ry=\"3\" class=\"fillclair\" stroke-width=\"1\" stroke=\"rgb(0,0,0)\" opacity=\"0.8\" pointer-events=\"none\" visibility=\"hidden\"></rect> 
	<text id=\"infotip\" x=\"25\" y=\"11\"  style=\"font-weight:normal;font-family:'Arial';font-size:8;text-anchor:left;pointer-events:none\" visibility=\"hidden\" >!</text>
</g>
<g id=\"info\"  visibility=\"hidden\">
	<rect id=\"inforect\" x=\"20\" y=\"0\" width=\"50\" height=\"25\" class=\"fillclair\" stroke-width=\"1\" stroke=\"rgb(0,0,0)\" opacity=\"0.8\"></rect> 
<a><text id=\"infote1\" x=\"25\" y=\"10\"  style=\"font-weight:normal;font-family:'Arial';font-size:8;text-anchor:left;\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','black','','')\">Modifier</text></a>
<a id=\"li_info_supp\"><text id=\"infote2\" x=\"25\" y=\"20\"  style=\"font-weight:normal;font-family:'Arial';font-size:8;text-anchor:left;\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','black','','')\">Supprimer</text></a>
</g>
<g id=\"gestion_style\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_gestion\" width=\"292\" height=\"180\" x=\"170\" y=\"66\" class=\"defaut\"/> 
<rect width=\"291\" height=\"18\" x=\"170.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Gestion du style</text>
<g id=\"gestion_coul_fond\" opacity=\"1\">
<rect width=\"128\" height=\"90\" x=\"184\" y=\"100\" class=\"defaut\"/>
<text pointer-events=\"none\" x=\"248\" y=\"115\" text-anchor=\"middle\">Remplissage</text>
<rect width=\"20\" height=\"15\" x=\"194\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fill')\"/>
<text id=\"r_fill\" pointer-events=\"none\" x=\"204\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"204\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">R</text>
<rect width=\"20\" height=\"15\" x=\"238\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'g_fill')\"/>
<text id=\"g_fill\" pointer-events=\"none\" x=\"248\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"248\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">G</text>
<rect width=\"20\" height=\"15\" x=\"282\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'b_fill')\"/>
<text id=\"b_fill\" pointer-events=\"none\" x=\"292\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"292\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">B</text>
<rect width=\"20\" height=\"15\" x=\"282\" y=\"150\" class=\"defaut\" onclick=\"entre_dim(evt,'opa')\"/>
<text id=\"opa\" pointer-events=\"none\" x=\"292\" y=\"160\" text-anchor=\"middle\" font-size=\"8\">1</text>
<text pointer-events=\"none\" x=\"230\" y=\"160\" font-size=\"8\">Transparence</text>
<rect id=\"palette_fill\" x=\"223\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"affiche_panneau_couleur(evt,'fill','')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"248\" y=\"182.5\">Palette</text>
</g>
<rect width=\"10\" height=\"10\" x=\"282\" y=\"200\" class=\"defaut\" onclick=\"no(evt,'remplissage')\"/>
<text id=\"remplissage\" x=\"282\" y=\"210\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"210\" y=\"207.5\" font-size=\"8\">Pas de remplissage</text>
<g id=\"gestion_coul_contour\" opacity=\"1\" pointer-events=\"visible\">
<rect width=\"128\" height=\"90\" x=\"316\" y=\"100\" class=\"defaut\"/>
<text pointer-events=\"none\" x=\"380\" y=\"115\" text-anchor=\"middle\" >Contour</text>
<rect width=\"20\" height=\"15\" x=\"326\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'r_stroke')\"/>
<text id=\"r_stroke\" pointer-events=\"none\" x=\"336\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"336\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">R</text>
<rect width=\"20\" height=\"15\" x=\"370\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'g_stroke')\"/>
<text id=\"g_stroke\" pointer-events=\"none\" x=\"380\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"380\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">G</text>
<rect width=\"20\" height=\"15\" x=\"414\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'b_stroke')\"/>
<text id=\"b_stroke\" pointer-events=\"none\" x=\"424\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"424\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">B</text>
<rect width=\"20\" height=\"15\" x=\"414\" y=\"150\" class=\"defaut\" onclick=\"entre_dim(evt,'larg_stroke')\"/>
<text id=\"larg_stroke\" pointer-events=\"none\" x=\"424\" y=\"160\" text-anchor=\"middle\" font-size=\"8\">1</text>
<text pointer-events=\"none\" x=\"397\" y=\"160\" text-anchor=\"middle\" font-size=\"8\">Largeur</text>
<rect id=\"palette_stroke\" x=\"355\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"affiche_panneau_couleur(evt,'stroke','')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"380\" y=\"182.5\">Palette</text>
</g>
<rect width=\"10\" height=\"10\" x=\"414\" y=\"200\" class=\"defaut\" onclick=\"no(evt,'contour')\"/>
<text id=\"contoure\" x=\"414\" y=\"210\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"358\" y=\"207.5\" font-size=\"8\">Pas de contour</text>
<rect id=\"ferme_gestion_style\" x=\"223\" y=\"220\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'gestion_style')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"248\" y=\"232.5\">Fermer</text>
<a id=\"listyle\"><rect id=\"valide_gestion_style\" x=\"355\" y=\"220\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\"  onclick=\"valide_style(evt)\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"380\" y=\"232.5\">Valider</text></a>
</g>
<g id=\"gestion_ecriture\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_gestion_ecriture\" width=\"292\" height=\"180\" x=\"170\" y=\"66\" class=\"defaut\"/> 
<rect width=\"291\" height=\"18\" x=\"170.5\" y=\"66.5\" class=\"fillclair\"/>
<text id=\"titre_sym\" pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Gestion du symbole</text>
<g id=\"gestion_coul_police\" opacity=\"1\">
<rect width=\"128\" height=\"90\" x=\"184\" y=\"100\" class=\"defaut\"/>
<text pointer-events=\"none\" x=\"248\" y=\"115\" text-anchor=\"middle\">Couleur</text>
<rect width=\"20\" height=\"15\" x=\"194\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'r_ecri')\"/>
<text id=\"r_ecri\" pointer-events=\"none\" x=\"204\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"204\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">R</text>
<rect width=\"20\" height=\"15\" x=\"238\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'g_ecri')\"/>
<text id=\"g_ecri\" pointer-events=\"none\" x=\"248\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"248\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">G</text>
<rect width=\"20\" height=\"15\" x=\"282\" y=\"120\" class=\"defaut\" onclick=\"entre_dim(evt,'b_ecri')\"/>
<text id=\"b_ecri\" pointer-events=\"none\" x=\"292\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">255</text>
<text pointer-events=\"none\" x=\"292\" y=\"145\" text-anchor=\"middle\" font-size=\"8\">B</text>
<rect width=\"20\" height=\"15\" x=\"282\" y=\"150\" class=\"defaut\" onclick=\"entre_dim(evt,'opa_ecri')\"/>
<text id=\"opa_ecri\" pointer-events=\"none\" x=\"292\" y=\"160\" text-anchor=\"middle\" font-size=\"8\">1</text>
<text pointer-events=\"none\" x=\"230\" y=\"160\" font-size=\"8\">Transparence</text>
<rect id=\"palette_fill\" x=\"223\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"affiche_panneau_couleur(evt,'fill','symbo')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"248\" y=\"182.5\">Palette</text>
</g>
<g id=\"gestion_choix_symbole\" opacity=\"1\" pointer-events=\"visible\">
<rect width=\"128\" height=\"90\" x=\"316\" y=\"100\" class=\"defaut\"/>
<text id=\"ecri_sym\" pointer-events=\"none\" x=\"380\" y=\"115\" text-anchor=\"middle\" >Symbole</text>
<a><text id=\"symbo\" font-family=\"fontsvg\" font-size=\"10\" x=\"380\" y=\"165\" text-anchor=\"middle\" onclick=\"affiche_list_symbol()\">A</text></a>
<text pointer-events=\"none\" x=\"405\" y=\"180\" text-anchor=\"middle\" font-size=\"8\">Size</text>
<rect width=\"20\" height=\"15\" x=\"415\" y=\"170\" class=\"defaut\" onclick=\"entre_dim(evt,'r_size')\"/>
<text id=\"r_size\" pointer-events=\"none\" x=\"425\" y=\"180\" text-anchor=\"middle\" font-size=\"8\">10</text>
</g>
<rect id=\"ferme_gestion_ec\" x=\"223\" y=\"220\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'gestion_ecriture')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"248\" y=\"232.5\">Fermer</text>
<a id=\"lisym\"><rect id=\"valide_gestion_ecr\" x=\"355\" y=\"220\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\"  onclick=\"valide_ecriture(evt)\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"380\" y=\"232.5\">Valider</text></a>
</g>
";
$data.=$tableau_symbol.$des_symbol."</g>";
$data.=$gestion_ordre.$txt_ordre_legend."</g>";
$data.="<g id=\"gestion_modif_couche\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_gestion_modif_couche\" width=\"312\" height=\"300\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Modification des proprietes de la couche</text>
<text pointer-events=\"none\" x=\"250\" y=\"110\" text-anchor=\"end\" font-size=\"8\">Uniquement vectoriel</text>
<rect width=\"10\" height=\"10\" x=\"255\" y=\"102.5\" class=\"defaut\" onclick=\"bascule(evt,'unique_v')\"/>
<text id=\"unique_v\" x=\"255\" y=\"112.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"320\" y=\"110\" text-anchor=\"end\" font-size=\"8\">Vu partielle</text>
<rect width=\"10\" height=\"10\" x=\"325\" y=\"102.5\" class=\"defaut\" onclick=\"bascule(evt,'partiel')\"/>
<text id=\"partiel\" x=\"325\" y=\"112.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"430\" y=\"110\" text-anchor=\"end\" font-size=\"8\">Force le rechargement</text>
<rect width=\"10\" height=\"10\" x=\"435\" y=\"102.5\" class=\"defaut\" onclick=\"bascule(evt,'force')\"/>
<text id=\"force\" x=\"435\" y=\"112.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"290\" y=\"130\" text-anchor=\"end\" font-size=\"8\">Zoom mini</text>
<rect width=\"25\" height=\"10\" x=\"295\" y=\"122.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_zoom_min_v')\"/>
<text id=\"r_zoom_min_v\" pointer-events=\"none\" x=\"307.5\" y=\"130\" text-anchor=\"middle\" font-size=\"8\">!</text>
<text pointer-events=\"none\" x=\"400\" y=\"130\" text-anchor=\"end\" font-size=\"8\">Vu initiale</text>
<rect width=\"10\" height=\"10\" x=\"405\" y=\"122.5\" class=\"defaut\" onclick=\"bascule(evt,'initial')\"/>
<text id=\"initial\" x=\"405\" y=\"132.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"290\" y=\"150\" text-anchor=\"end\" font-size=\"8\">Zoom max</text>
<rect width=\"25\" height=\"10\" x=\"295\" y=\"142.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_zoom_max_v')\"/>
<text id=\"r_zoom_max_v\" pointer-events=\"none\" x=\"307.5\" y=\"150\" text-anchor=\"middle\" font-size=\"8\">!</text>
<text pointer-events=\"none\" x=\"400\" y=\"150\" text-anchor=\"end\" font-size=\"8\">Objet s&#x00E9;lectionnable</text>
<rect width=\"10\" height=\"10\" x=\"405\" y=\"142.5\" class=\"defaut\" onclick=\"bascule(evt,'select')\"/>
<text id=\"select\" x=\"405\" y=\"152.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"290\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Zoom max raster</text>
<rect width=\"25\" height=\"10\" x=\"295\" y=\"162.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_zoom_max_rast')\"/>
<text id=\"r_zoom_max_rast\" pointer-events=\"none\" x=\"307.5\" y=\"170\" text-anchor=\"middle\" font-size=\"8\">!</text>
<text pointer-events=\"none\" x=\"400\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Objet principal</text>
<rect width=\"10\" height=\"10\" x=\"405\" y=\"162.5\" class=\"defaut\" onclick=\"bascule(evt,'princip')\"/>
<text id=\"princip\" x=\"405\" y=\"172.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<a><text id=\"txtsurover\" x=\"220\" y=\"190\" text-anchor=\"end\" font-size=\"8\" onclick=\"affiche_script('script_mouseover')\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','url(#survol)','','')\">Sur mouseover</text></a>
<rect width=\"240\" height=\"40\" x=\"225\" y=\"182.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mouseover')\"/>
<text pointer-events=\"none\" x=\"226\" y=\"190\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_sur\" id=\"r_mouseover\">!</textPath></text>
<a><text id=\"txtonclick\" x=\"220\" y=\"240\" text-anchor=\"end\" font-size=\"8\" onclick=\"affiche_script('script_mouseclick')\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','url(#survol)','','')\">Sur onclick</text></a>
<rect width=\"240\" height=\"40\" x=\"225\" y=\"232.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_click')\"/>
<text pointer-events=\"none\" x=\"226\" y=\"240\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_lien\" id=\"r_click\">!</textPath></text>
<text pointer-events=\"none\" x=\"220\" y=\"290\" text-anchor=\"end\" font-size=\"8\">Sur mouseout</text>
<rect width=\"240\" height=\"40\" x=\"225\" y=\"282.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mouseout')\"/>
<text pointer-events=\"none\" x=\"226\" y=\"290\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_hors\" id=\"r_mouseout\" >!</textPath></text>
<rect id=\"ferme_gestion_modif_couche\" x=\"230.5\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'gestion_modif_couche')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"352.5\">Fermer</text>
<a id=\"li_pro\"><rect id=\"suivant_gestion_modif_couche\" x=\"351\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"test_update_propriete_theme()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"352.5\">Suivant</text>
</g>
<g id=\"script_mouseover\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_gestion_script_mouseover\" width=\"312\" height=\"230\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >G&#x00E9;n&#x00E9;ration du script du mouseover</text>
<a><text id=\"txtsurvol\" x=\"300\" y=\"110\" text-anchor=\"end\" font-size=\"8\" onclick=\"affiche_panneau_couleur(evt,'','r_survol')\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','url(#survol)','','')\">Couleur de survol</text></a>
<rect width=\"75\" height=\"10\" x=\"305\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_survol')\"/>
<text id=\"r_survol\" pointer-events=\"none\" x=\"306\" y=\"110\" text-anchor=\"start\" font-size=\"8\">!</text>
<text x=\"196\" y=\"130\" text-anchor=\"start\" font-size=\"8\">Message a affich&#x00E9; sur la s&#x00E9;lection unique</text>
<rect width=\"240\" height=\"40\" x=\"196\" y=\"135\" class=\"defaut\" onclick=\"entre_dim(evt,'r_message1')\"/>
<text pointer-events=\"none\" x=\"197\" y=\"145\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_mess1\" id=\"r_message1\">!</textPath></text>
<text x=\"196\" y=\"190\" text-anchor=\"start\" font-size=\"8\">Message a affich&#x00E9; sur la s&#x00E9;lection multiple</text>
<rect width=\"240\" height=\"40\" x=\"196\" y=\"195\" class=\"defaut\" onclick=\"entre_dim(evt,'r_message2')\"/>
<text pointer-events=\"none\" x=\"197\" y=\"205\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_mess2\" id=\"r_message2\">!</textPath></text>
<rect id=\"ferme_script_mouseover\" x=\"230.5\" y=\"260\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'script_mouseover')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"272.5\">Fermer</text>
<rect id=\"valide_script_mouseover\" x=\"351\" y=\"260\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"genere_script('mouseover')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"272.5\">Valider</text>
</g>
<g id=\"script_mouseclick\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_gestion_script_mouseclick\" width=\"312\" height=\"230\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >G&#x00E9;n&#x00E9;ration du script du click</text>
<text x=\"300\" y=\"110\" text-anchor=\"end\" font-size=\"8\" >Nom de l&#x00B4;objet</text>
<rect width=\"75\" height=\"10\" x=\"305\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_nomobjet')\"/>
<text id=\"r_nomobjet\" pointer-events=\"none\" x=\"306\" y=\"110\" text-anchor=\"start\" font-size=\"8\">!</text>
<text x=\"330\" y=\"130\" text-anchor=\"end\" font-size=\"8\">Page securis&#x00E9;e</text>
<rect width=\"10\" height=\"10\" x=\"335\" y=\"122.5\" class=\"defaut\" onclick=\"bascule(evt,'securise')\"/>
<text id=\"securise\" x=\"335\" y=\"132.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text x=\"300\" y=\"150\" text-anchor=\"end\" font-size=\"8\">Serveur</text>
<rect width=\"75\" height=\"10\" x=\"305\" y=\"142.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_serveur')\"/>
<text pointer-events=\"none\" x=\"306\" y=\"150\" text-anchor=\"start\" font-size=\"8\" id=\"r_serveur\">serveur</text>
<text x=\"270\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Chemin de la page</text>
<rect width=\"150\" height=\"10\" x=\"275\" y=\"162.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_chemin')\"/>
<text pointer-events=\"none\" x=\"276\" y=\"170\" text-anchor=\"start\" font-size=\"8\" id=\"r_chemin\">!</text>
<text x=\"300\" y=\"190\" text-anchor=\"end\" font-size=\"8\">Variable</text>
<rect width=\"75\" height=\"10\" x=\"305\" y=\"182.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_variable')\"/>
<text pointer-events=\"none\" x=\"306\" y=\"190\" text-anchor=\"start\" font-size=\"8\" id=\"r_variable\">obj_keys</text>
<text x=\"300\" y=\"210\" text-anchor=\"end\" font-size=\"8\">S&#x00E9;lection multiple</text>
<rect width=\"10\" height=\"10\" x=\"305\" y=\"202.5\" class=\"defaut\" onclick=\"bascule(evt,'multip')\"/>
<text id=\"multip\" x=\"305\" y=\"212.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text x=\"320\" y=\"210\" text-anchor=\"start\" font-size=\"8\">limit&#x00E9;e &#x00E0;</text>
<rect width=\"20\" height=\"10\" x=\"355\" y=\"202.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_limite')\"/>
<text pointer-events=\"none\" x=\"356\" y=\"210\" text-anchor=\"start\" font-size=\"8\" id=\"r_limite\">2</text>
<rect id=\"ferme_script_mouseclick\" x=\"230.5\" y=\"260\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'script_mouseclick')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"272.5\">Fermer</text>
<rect id=\"valide_script_mouseclick\" x=\"351\" y=\"260\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"genere_script('click')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"272.5\">Valider</text>
</g>
<g id=\"ajout_ad_id\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_ajout_ad_id\" width=\"312\" height=\"230\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Ajout de la colonne libell&#x00E9; et r&#x00E9;f&#x00E9;rence</text>
<text pointer-events=\"none\" x=\"316\" y=\"110\" text-anchor=\"middle\" font-size=\"8\" >La colonne libell&#x00E9; et ou r&#x00E9;f&#x00E9;rence optionnelles lors de cr&#x00E9;ation du</text>
<text pointer-events=\"none\" x=\"316\" y=\"120\" text-anchor=\"middle\" font-size=\"8\" >th&#x00E8;me sont obligatoires pour affecter le script de survol et du click</text>
<text x=\"300\" y=\"140\" text-anchor=\"end\" font-size=\"8\" >Colonne r&#x00E9;f&#x00E9;rence</text>
<rect id=\"rect_r_id\" width=\"75\" height=\"10\" x=\"305\" y=\"132.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_id')\"/>
<text id=\"r_id\" pointer-events=\"none\" x=\"306\" y=\"140\" text-anchor=\"start\" font-size=\"8\">!</text>
<rect width=\"10\" height=\"11\" x=\"380\" y=\"132\" onclick=\"appel_deroulant(evt,'r_id')\"/>
<text x=\"300\" y=\"160\" text-anchor=\"end\" font-size=\"8\" >Colonne libell&#x00E9;</text>
<rect id=\"rect_r_ad\" width=\"75\" height=\"10\" x=\"305\" y=\"152.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_ad')\"/>
<text id=\"r_ad\" pointer-events=\"none\" x=\"306\" y=\"160\" text-anchor=\"start\" font-size=\"8\">!</text>
<rect width=\"10\" height=\"11\" x=\"380\" y=\"152\" onclick=\"appel_deroulant(evt,'r_ad')\"/>
<rect id=\"ferme_script_mouseclick\" x=\"230.5\" y=\"260\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'ajout_ad_id')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"272.5\">Fermer</text>
<rect id=\"valide_script_mouseclick\" x=\"351\" y=\"260\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"get_insert_ad_id()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"272.5\">Valider</text>
</g>
<g id=\"ajout_the\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_ajout_theme\" width=\"312\" height=\"90\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Ajout d'un th&#x00E8;me</text>
<text x=\"250\" y=\"110\" text-anchor=\"end\" font-size=\"8\" >th&#x00E8;me</text>
<rect id=\"rect_r_theme\" width=\"125\" height=\"10\" x=\"255\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_theme')\"/>
<text id=\"r_theme\" pointer-events=\"none\" x=\"256\" y=\"110\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"380\" y=\"102\" onclick=\"appel_deroulant(evt,'r_theme','contenu')\"/>
<rect id=\"ferme_ajout_theme\" x=\"230.5\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'ajout_the')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"142.5\">Fermer</text>
<a id=\"li_th\"><rect id=\"valide_ajout_theme\" x=\"351\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"verif_theme()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"142.5\">Valider</text>
</g>
<g id=\"app_style_thema\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_app_style_thema\" width=\"312\" height=\"90\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Appliquer un style ou une th&#x00E8;matique au th&#x00E8;me</text>
<text x=\"250\" y=\"110\" text-anchor=\"end\" font-size=\"8\" >Choix</text>
<rect id=\"rect_r_style_thema\" pointer-events=\"none\" width=\"125\" height=\"10\" x=\"255\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_style_thema')\"/>
<text id=\"r_style_thema\" pointer-events=\"none\" x=\"256\" y=\"110\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect id=\"rect1_style\" pointer-events=\"none\" width=\"10\" height=\"11\" x=\"380\" y=\"102\" onclick=\"appel_deroulant(evt,'r_style_thema','appel')\"/>
<rect id=\"ferme_style_thema\" x=\"230.5\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'app_style_thema')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"142.5\">Fermer</text>
<a id=\"li_style_theme\"><rect id=\"valide_ajout_theme\" x=\"351\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"valid_style_them()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"142.5\">Valider</text>
</g>
<g id=\"gestion_creation_theme\" style=\"visibility:hidden\" class=\"fillfonce\">
<rect id=\"contour_gestion_creation_theme\" width=\"312\" height=\"300\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Cr&#x00E9;ation d&#x00B4;un th&#x00E8;me</text>
<text pointer-events=\"none\" x=\"275\" y=\"110\" text-anchor=\"end\" font-size=\"8\">Nom du th&#x00E8;me</text>
<rect width=\"100\" height=\"10\" x=\"280\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_theme_nom')\"/>
<text id=\"r_theme_nom\" pointer-events=\"none\" x=\"330\" y=\"110\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text x=\"215\" y=\"130\" text-anchor=\"end\" font-size=\"8\" >Sch&#x00E9;ma</text>
<rect id=\"rect_r_crea_schema\" width=\"85\" height=\"10\" x=\"220\" y=\"122.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_schema')\"/>
<text id=\"r_crea_schema\" pointer-events=\"none\" x=\"221\" y=\"130\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"305\" y=\"122\" onclick=\"appel_deroulant(evt,'r_crea_schema','schema')\"/>
<g id=\"g_tabl\" opacity=\"0.2\" pointer-events=\"none\">
<text id=\"txt_table\" x=\"340\" y=\"130\" text-anchor=\"end\" font-size=\"8\" >Table</text>
<rect id=\"rect_r_crea_table\" width=\"75\" height=\"10\" x=\"345\" y=\"122.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_table')\"/>
<text id=\"r_crea_table\" pointer-events=\"none\" x=\"346\" y=\"130\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect id=\"rec_table\" width=\"10\" height=\"11\" x=\"420\" y=\"122\" onclick=\"appel_deroulant(evt,'r_crea_table','table')\"/>
</g>
<text pointer-events=\"none\" x=\"200\" y=\"150\" text-anchor=\"end\" font-size=\"8\">Shapefile</text>
<rect width=\"150\" height=\"10\" x=\"205\" y=\"142.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_shp')\"/>
<text id=\"r_crea_shp\" pointer-events=\"none\" x=\"380\" y=\"150\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text pointer-events=\"none\" x=\"390\" y=\"150\" text-anchor=\"end\" font-size=\"8\">Groupe</text>
<rect width=\"70\" height=\"10\" x=\"395\" y=\"142.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_groupe')\"/>
<text id=\"r_crea_groupe\" pointer-events=\"none\" x=\"430\" y=\"150\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text pointer-events=\"none\" x=\"230\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Vu partielle</text>
<rect width=\"10\" height=\"10\" x=\"235\" y=\"162.5\" class=\"defaut\" onclick=\"bascule(evt,'crea_partiel')\"/>
<text id=\"crea_partiel\" x=\"235\" y=\"172.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"310\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Vu initiale</text>
<rect width=\"10\" height=\"10\" x=\"315\" y=\"162.5\" class=\"defaut\" onclick=\"bascule(evt,'crea_initial')\"/>
<text id=\"crea_initial\" x=\"315\" y=\"172.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"410\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Force le rechargement</text>
<rect width=\"10\" height=\"10\" x=\"415\" y=\"162.5\" class=\"defaut\" onclick=\"bascule(evt,'crea_force')\"/>
<text id=\"crea_force\" x=\"415\" y=\"172.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"220\" y=\"190\" text-anchor=\"end\" font-size=\"8\">Zoommin</text>
<rect width=\"25\" height=\"10\" x=\"225\" y=\"182.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_zmin')\"/>
<text id=\"r_crea_zmin\" pointer-events=\"none\" x=\"237.5\" y=\"190\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text pointer-events=\"none\" x=\"300\" y=\"190\" text-anchor=\"end\" font-size=\"8\">Zoommax</text>
<rect width=\"25\" height=\"10\" x=\"305\" y=\"182.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_zmax')\"/>
<text id=\"r_crea_zmax\" pointer-events=\"none\" x=\"317.5\" y=\"190\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text pointer-events=\"none\" x=\"400\" y=\"190\" text-anchor=\"end\" font-size=\"8\">Zoommax_raster</text>
<rect width=\"25\" height=\"10\" x=\"405\" y=\"182.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_zmr')\"/>
<text id=\"r_crea_zmr\" pointer-events=\"none\" x=\"417.5\" y=\"190\" text-anchor=\"middle\" font-size=\"8\"> </text>
<g id=\"g_ref\" opacity=\"0.2\" pointer-events=\"none\">
<text x=\"230\" y=\"210\" text-anchor=\"end\" font-size=\"8\" >Colonne r&#x00E9;f&#x00E9;rence</text>
<rect id=\"rect_r_crea_ref\" width=\"50\" height=\"10\" x=\"235\" y=\"202.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_ref')\"/>
<text id=\"r_crea_ref\" pointer-events=\"none\" x=\"236\" y=\"210\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"285\" y=\"202\" onclick=\"appel_deroulant(evt,'r_crea_ref')\"/>
</g>
<g id=\"g_geom\" opacity=\"0.2\" pointer-events=\"none\">
<text x=\"375\" y=\"210\" text-anchor=\"end\" font-size=\"8\" >Colonne g&#x00E9;om&#x00E9;trique</text>
<rect id=\"rect_r_crea_geom\" width=\"80\" height=\"10\" x=\"380\" y=\"202.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_geom')\"/>
<text id=\"r_crea_geom\" pointer-events=\"none\" x=\"381\" y=\"210\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"460\" y=\"202\" onclick=\"appel_req('r_crea_geom')\"/>
</g>
<g id=\"g_lib\" opacity=\"0.2\" pointer-events=\"none\">
<text x=\"240\" y=\"230\" text-anchor=\"end\" font-size=\"8\" >Colonne libell&#x00E9;</text>
<rect id=\"rect_r_crea_lib\" width=\"190\" height=\"10\" x=\"245\" y=\"222.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_lib')\"/>
<text id=\"r_crea_lib\" pointer-events=\"none\" x=\"246\" y=\"230\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"435\" y=\"222\" onclick=\"appel_req('r_crea_lib')\"/>
</g>
<text x=\"175\" y=\"250\" text-anchor=\"start\" font-size=\"8\" >Clause</text>
<rect id=\"rect_r_crea_clause\" width=\"280\" height=\"50\" x=\"175\" y=\"255\" class=\"defaut\" onclick=\"entre_dim(evt,'r_crea_clause')\"/>
<text pointer-events=\"none\" x=\"176\" y=\"265\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_clause\" id=\"r_crea_clause\"> </textPath></text>
<rect id=\"ferme_gestion_creation_theme\" x=\"230.5\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'gestion_creation_theme')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"352.5\">Fermer</text>
<a id=\"li_creation_theme\"><rect id=\"suivant_gestion_modif_couche\" x=\"351\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"creer_theme()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"352.5\">Suivant</text>
</g>
<g id=\"gestion_thematique\" style=\"visibility:hidden\" class=\"fillfonce\">
<rect id=\"contour_gestion_thematique\" width=\"312\" height=\"300\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Cr&#x00E9;ation d&#x00B4;une th&#x00E8;matique</text>
<a><text x=\"175\" y=\"110\" id=\"txtcolonne\" text-anchor=\"start\" font-size=\"8\" onclick=\"appel_req('r_requete')\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','url(#survol)','','')\">Colonne</text></a>
<rect id=\"rect_r_requete\" width=\"280\" height=\"50\" x=\"175\" y=\"115\" class=\"defaut\" onclick=\"entre_dim(evt,'r_requete')\"/>
<text pointer-events=\"none\" x=\"176\" y=\"125\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_requete\" id=\"r_requete\"> </textPath></text>
<a id=\"li_soumettre\"><rect id=\"sousmettre_gestion_thematique\" x=\"320\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"sousmettre_thematique()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text x=\"345\" y=\"180\" text-anchor=\"middle\" font-size=\"8\" pointer-events=\"none\" >Soumettre</text>
<rect id=\"rect_r_applique\" width=\"125\" height=\"10\" x=\"175\" y=\"172.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_applique')\"/>
<text id=\"r_applique\" pointer-events=\"none\" x=\"176\" y=\"180\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect id=\"rect1_choix_pt\" width=\"10\" height=\"11\" x=\"300\" y=\"172\" onclick=\"appel_deroulant(evt,'r_applique','appel')\"/>
<g id=\"fixe\" style=\"visibility:hidden\" class=\"fillfonce\">
<rect width=\"100\" height=\"10\" x=\"216\" y=\"202.5\" class=\"defaut\"/>
<text pointer-events=\"none\" x=\"266\" y=\"210\" text-anchor=\"middle\" font-size=\"8\">filtre</text>
<rect width=\"100\" height=\"10\" x=\"316\" y=\"202.5\" class=\"defaut\"/>
<text pointer-events=\"none\" x=\"366\" y=\"210\" text-anchor=\"middle\" font-size=\"8\">l&#x00E9;gende</text>
<rect x=\"216\" y=\"212.5\" height=\"120\" width=\"200\" class=\"defaut\"/>
<svg id=\"svgfixe\"  x=\"216\" y=\"212.5\" height=\"120\" width=\"212\" viewBox=\"216 212.5 212 120\">
<rect width=\"12\" height=\"120\" x=\"416\" y=\"212.5\"/>
<g id=\"curseur1\">
<rect id=\"scroll_cursor1\" pointer-events=\"visible\" class=\"fillclair\" width=\"12\" height=\"120\" x=\"416\" y=\"212.5\" onmouseup=\"liste_glisse_click(evt,'false')\" onmousedown=\"liste_glisse_click(evt,'true')\" onmousemove=\"liste_glisse(evt,'scroll_cursor1')\" onmouseout=\"liste_glisse_click(evt,'false')\"/>
<rect pointer-events=\"none\" width=\"12\" height=\"120\" x=\"416\" y=\"212.5\" fill=\"none\" stroke=\"url(#survol)\"/>
</g>
<g id=\"valfixe\" class=\"fillfonce\">
</g>
</svg>
</g>
<g id=\"fourchette\" style=\"visibility:hidden\" class=\"fillfonce\">
<rect x=\"214\" y=\"212.5\" height=\"120\" width=\"204\" class=\"defaut\"/>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"202.5\" class=\"defaut\"/>
<text pointer-events=\"none\" x=\"248\" y=\"210\" text-anchor=\"middle\" font-size=\"8\">Mini</text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"202.5\" class=\"defaut\"/>
<text pointer-events=\"none\" x=\"316\" y=\"210\" text-anchor=\"middle\" font-size=\"8\">Maxi</text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"202.5\" class=\"defaut\"/>
<text pointer-events=\"none\" x=\"384\" y=\"210\" text-anchor=\"middle\" font-size=\"8\">L&#x00E9;gende</text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"212.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche0_mini')\"/>
<text id=\"r_fourche0_mini\" pointer-events=\"none\" x=\"248\" y=\"220\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"212.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche0_maxi')\"/>
<text id=\"r_fourche0_maxi\" pointer-events=\"none\" x=\"316\" y=\"220\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"212.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche0_leg')\"/>
<text id=\"r_fourche0_leg\" pointer-events=\"none\" x=\"384\" y=\"220\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"222.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche1_mini')\"/>
<text id=\"r_fourche1_mini\" pointer-events=\"none\" x=\"248\" y=\"230\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"222.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche1_maxi')\"/>
<text id=\"r_fourche1_maxi\" pointer-events=\"none\" x=\"316\" y=\"230\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"222.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche1_leg')\"/>
<text id=\"r_fourche1_leg\" pointer-events=\"none\" x=\"384\" y=\"230\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"232.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche2_mini')\"/>
<text id=\"r_fourche2_mini\" pointer-events=\"none\" x=\"248\" y=\"240\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"232.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche2_maxi')\"/>
<text id=\"r_fourche2_maxi\" pointer-events=\"none\" x=\"316\" y=\"240\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"232.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche2_leg')\"/>
<text id=\"r_fourche2_leg\" pointer-events=\"none\" x=\"384\" y=\"240\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"242.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche3_mini')\"/>
<text id=\"r_fourche3_mini\" pointer-events=\"none\" x=\"248\" y=\"250\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"242.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche3_maxi')\"/>
<text id=\"r_fourche3_maxi\" pointer-events=\"none\" x=\"316\" y=\"250\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"242.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche3_leg')\"/>
<text id=\"r_fourche3_leg\" pointer-events=\"none\" x=\"384\" y=\"250\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"252.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche4_mini')\"/>
<text id=\"r_fourche4_mini\" pointer-events=\"none\" x=\"248\" y=\"260\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"252.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche4_maxi')\"/>
<text id=\"r_fourche4_maxi\" pointer-events=\"none\" x=\"316\" y=\"260\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"252.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche4_leg')\"/>
<text id=\"r_fourche4_leg\" pointer-events=\"none\" x=\"384\" y=\"260\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"262.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche5_mini')\"/>
<text id=\"r_fourche5_mini\" pointer-events=\"none\" x=\"248\" y=\"270\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"262.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche5_maxi')\"/>
<text id=\"r_fourche5_maxi\" pointer-events=\"none\" x=\"316\" y=\"270\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"262.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche5_leg')\"/>
<text id=\"r_fourche5_leg\" pointer-events=\"none\" x=\"384\" y=\"270\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"272.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche6_mini')\"/>
<text id=\"r_fourche6_mini\" pointer-events=\"none\" x=\"248\" y=\"280\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"272.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche6_maxi')\"/>
<text id=\"r_fourche6_maxi\" pointer-events=\"none\" x=\"316\" y=\"280\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"272.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche6_leg')\"/>
<text id=\"r_fourche6_leg\" pointer-events=\"none\" x=\"384\" y=\"280\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"282.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche7_mini')\"/>
<text id=\"r_fourche7_mini\" pointer-events=\"none\" x=\"248\" y=\"290\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"282.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche7_maxi')\"/>
<text id=\"r_fourche7_maxi\" pointer-events=\"none\" x=\"316\" y=\"290\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"282.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche7_leg')\"/>
<text id=\"r_fourche7_leg\" pointer-events=\"none\" x=\"384\" y=\"290\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"292.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche8_mini')\"/>
<text id=\"r_fourche8_mini\" pointer-events=\"none\" x=\"248\" y=\"300\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"292.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche8_maxi')\"/>
<text id=\"r_fourche8_maxi\" pointer-events=\"none\" x=\"316\" y=\"300\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"292.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche8_leg')\"/>
<text id=\"r_fourche8_leg\" pointer-events=\"none\" x=\"384\" y=\"300\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"302.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche9_mini')\"/>
<text id=\"r_fourche9_mini\" pointer-events=\"none\" x=\"248\" y=\"310\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"302.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche9_maxi')\"/>
<text id=\"r_fourche9_maxi\" pointer-events=\"none\" x=\"316\" y=\"310\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"302.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche9_leg')\"/>
<text id=\"r_fourche9_leg\" pointer-events=\"none\" x=\"384\" y=\"310\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"312.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche10_mini')\"/>
<text id=\"r_fourche10_mini\" pointer-events=\"none\" x=\"248\" y=\"320\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"312.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche10_maxi')\"/>
<text id=\"r_fourche10_maxi\" pointer-events=\"none\" x=\"316\" y=\"320\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"312.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche10_leg')\"/>
<text id=\"r_fourche10_leg\" pointer-events=\"none\" x=\"384\" y=\"320\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"214\" y=\"322.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche11_mini')\"/>
<text id=\"r_fourche11_mini\" pointer-events=\"none\" x=\"248\" y=\"330\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"282\" y=\"322.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche11_maxi')\"/>
<text id=\"r_fourche11_maxi\" pointer-events=\"none\" x=\"316\" y=\"330\" text-anchor=\"middle\" font-size=\"8\"> </text>
<rect width=\"68\" height=\"10\" x=\"350\" y=\"322.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_fourche11_leg')\"/>
<text id=\"r_fourche11_leg\" pointer-events=\"none\" x=\"384\" y=\"330\" text-anchor=\"middle\" font-size=\"8\"> </text>
</g>
<rect id=\"rec_att\" style=\"visibility:hidden\" x=\"266\" y=\"280\" rx=\"1.5\" ry=\"1.5\" width=\"100\" height=\"20\" class=\"fillclair\" />
<text id=\"txt_att\" font-size=\"15\" pointer-events=\"none\" style=\"visibility:hidden\" text-anchor=\"middle\" x=\"316\" y=\"297.5\">Patienter...</text>
<rect id=\"ferme_gestion_thematique\" x=\"230.5\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'gestion_thematique')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"352.5\">Fermer</text>
<a><rect id=\"appliquer_gestion_thematique\" x=\"351\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"creer_thematique()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"352.5\">Appliquer</text>
<a id=\"li_thematique\"><rect id=\"terminer_gestion_thematique\" x=\"351\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" visibility=\"hidden\" class=\"fillclair\" onclick=\"termine_thematique()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text id=\"txt_termine\" pointer-events=\"none\" visibility=\"hidden\" text-anchor=\"middle\" x=\"376\" y=\"352.5\">Suivant</text>
</g>
<g id=\"mod_the\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_mod_theme\" width=\"312\" height=\"90\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Modification d&#x00B4;un th&#x00E8;me</text>
<text x=\"250\" y=\"110\" text-anchor=\"end\" font-size=\"8\" >th&#x00E8;me</text>
<rect id=\"rect_r_mod_theme\" width=\"125\" height=\"10\" x=\"255\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_theme')\"/>
<text id=\"r_mod_theme\" pointer-events=\"none\" x=\"256\" y=\"110\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"380\" y=\"102\" onclick=\"appel_deroulant(evt,'r_mod_theme','contenu')\"/>
<rect id=\"ferme_mod_theme\" x=\"230.5\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'mod_the')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"142.5\">Fermer</text>
<a id=\"li_th\"><rect id=\"valide_mod_theme\" x=\"351\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"modif_theme()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"142.5\">Valider</text>
</g>
<g id=\"gestion_mod_theme\" style=\"visibility:hidden\" class=\"fillfonce\">
<rect id=\"contour_gestion_mod_theme\" width=\"312\" height=\"300\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Modification d&#x00B4;un th&#x00E8;me</text>
<text pointer-events=\"none\" x=\"275\" y=\"110\" text-anchor=\"end\" font-size=\"8\">Nom du th&#x00E8;me</text>
<rect width=\"100\" height=\"10\" x=\"280\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_theme_nom')\"/>
<text id=\"r_mod_theme_nom\" pointer-events=\"none\" x=\"330\" y=\"110\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text x=\"215\" y=\"130\" text-anchor=\"end\" font-size=\"8\" >Sch&#x00E9;ma</text>
<rect id=\"rect_r_mod_schema\" width=\"85\" height=\"10\" x=\"220\" y=\"122.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_schema')\"/>
<text id=\"r_mod_schema\" pointer-events=\"none\" x=\"221\" y=\"130\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"305\" y=\"122\" onclick=\"appel_deroulant(evt,'r_mod_schema','schema')\"/>
<text x=\"340\" y=\"130\" text-anchor=\"end\" font-size=\"8\" >Table</text>
<rect id=\"rect_r_mod_table\" width=\"75\" height=\"10\" x=\"345\" y=\"122.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_table')\"/>
<text id=\"r_mod_table\" pointer-events=\"none\" x=\"346\" y=\"130\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"420\" y=\"122\" onclick=\"appel_deroulant(evt,'r_mod_table','table')\"/>
<text pointer-events=\"none\" x=\"200\" y=\"150\" text-anchor=\"end\" font-size=\"8\">Shapefile</text>
<rect width=\"150\" height=\"10\" x=\"205\" y=\"142.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_shp')\"/>
<text id=\"r_mod_shp\" pointer-events=\"none\" x=\"280\" y=\"150\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text pointer-events=\"none\" x=\"390\" y=\"150\" text-anchor=\"end\" font-size=\"8\">Groupe</text>
<rect width=\"70\" height=\"10\" x=\"395\" y=\"142.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_groupe')\"/>
<text id=\"r_mod_groupe\" pointer-events=\"none\" x=\"430\" y=\"150\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text pointer-events=\"none\" x=\"230\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Vu partielle</text>
<rect width=\"10\" height=\"10\" x=\"235\" y=\"162.5\" class=\"defaut\" onclick=\"bascule(evt,'mod_partiel')\"/>
<text id=\"mod_partiel\" x=\"235\" y=\"172.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"310\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Vu initiale</text>
<rect width=\"10\" height=\"10\" x=\"315\" y=\"162.5\" class=\"defaut\" onclick=\"bascule(evt,'mod_initial')\"/>
<text id=\"mod_initial\" x=\"315\" y=\"172.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"410\" y=\"170\" text-anchor=\"end\" font-size=\"8\">Force le rechargement</text>
<rect width=\"10\" height=\"10\" x=\"415\" y=\"162.5\" class=\"defaut\" onclick=\"bascule(evt,'mod_force')\"/>
<text id=\"mod_force\" x=\"415\" y=\"172.5\" class=\"fillfonce\" pointer-events=\"none\" style=\"font-size:12px;font-family:fontsvg;visibility:hidden\">b</text>
<text pointer-events=\"none\" x=\"220\" y=\"190\" text-anchor=\"end\" font-size=\"8\">Zoommin</text>
<rect width=\"25\" height=\"10\" x=\"225\" y=\"182.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_zmin')\"/>
<text id=\"r_mod_zmin\" pointer-events=\"none\" x=\"237.5\" y=\"190\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text pointer-events=\"none\" x=\"300\" y=\"190\" text-anchor=\"end\" font-size=\"8\">Zoommax</text>
<rect width=\"25\" height=\"10\" x=\"305\" y=\"182.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_zmax')\"/>
<text id=\"r_mod_zmax\" pointer-events=\"none\" x=\"317.5\" y=\"190\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text pointer-events=\"none\" x=\"400\" y=\"190\" text-anchor=\"end\" font-size=\"8\">Zoommax_raster</text>
<rect width=\"25\" height=\"10\" x=\"405\" y=\"182.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_zmr')\"/>
<text id=\"r_mod_zmr\" pointer-events=\"none\" x=\"417.5\" y=\"190\" text-anchor=\"middle\" font-size=\"8\"> </text>
<text x=\"230\" y=\"210\" text-anchor=\"end\" font-size=\"8\" >Colonne r&#x00E9;f&#x00E9;rence</text>
<rect id=\"rect_r_mod_ref\" width=\"50\" height=\"10\" x=\"235\" y=\"202.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_ref')\"/>
<text id=\"r_mod_ref\" pointer-events=\"none\" x=\"236\" y=\"210\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"285\" y=\"202\" onclick=\"appel_deroulant(evt,'r_mod_ref')\"/>
<text x=\"375\" y=\"210\" text-anchor=\"end\" font-size=\"8\" >Colonne g&#x00E9;om&#x00E9;trique</text>
<rect id=\"rect_r_mod_geom\" width=\"80\" height=\"10\" x=\"380\" y=\"202.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_geom')\"/>
<text id=\"r_mod_geom\" pointer-events=\"none\" x=\"381\" y=\"210\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"460\" y=\"202\" onclick=\"appel_req('r_mod_geom')\"/>
<text x=\"240\" y=\"230\" text-anchor=\"end\" font-size=\"8\" >Colonne libell&#x00E9;</text>
<rect id=\"rect_r_mod_lib\" width=\"190\" height=\"10\" x=\"245\" y=\"222.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_lib')\"/>
<text id=\"r_mod_lib\" pointer-events=\"none\" x=\"246\" y=\"230\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect width=\"10\" height=\"11\" x=\"435\" y=\"222\" onclick=\"appel_req('r_mod_lib')\"/>
<text x=\"175\" y=\"250\" text-anchor=\"start\" font-size=\"8\" >Clause</text>
<rect id=\"rect_r_mod_clause\" width=\"280\" height=\"50\" x=\"175\" y=\"255\" class=\"defaut\" onclick=\"entre_dim(evt,'r_mod_clause')\"/>
<text pointer-events=\"none\" x=\"176\" y=\"265\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_clause\" id=\"r_mod_clause\"> </textPath></text>
<rect id=\"ferme_gestion_mod_theme\" x=\"230.5\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'gestion_mod_theme')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"352.5\">Fermer</text>
<a id=\"li_mod_theme\"><rect x=\"351\" y=\"340\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"modification_theme()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"352.5\">Suivant</text>
</g>
<g id=\"requete_thematique\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_requete_thematique\" width=\"312\" height=\"180\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Cr&#x00E9;ation d&#x00B4;une th&#x00E8;matique</text>
<rect id=\"requete_thema\" width=\"10\" height=\"11\" x=\"447\" y=\"130\" onclick=\"appel_deroulant(evt,'r_requete_thematique')\"/>
<rect id=\"rect_r_requete_thematique\" width=\"262\" height=\"40\" x=\"185\" y=\"100\" class=\"defaut\" onclick=\"entre_dim(evt,'r_requete_thematique')\"/>
<text pointer-events=\"none\" x=\"186\" y=\"110\" text-anchor=\"start\" font-size=\"8\"><textPath xlink:href=\"#chemin_r_thematique\" id=\"r_requete_thematique\"> </textPath></text>
<rect id=\"surface_thematique\" x=\"211\" y=\"150\" rx=\"1.5\" ry=\"1.5\" width=\"60\" height=\"15\" class=\"fillclair\" onclick=\"surface_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"241\" y=\"162.5\">Surface</text>
<rect id=\"longueur_thematique\" x=\"281\" y=\"150\" rx=\"1.5\" ry=\"1.5\" width=\"60\" height=\"15\" class=\"fillclair\" onclick=\"longueur_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"311\" y=\"162.5\">longueur</text>
<rect id=\"souschaine_thematique\" x=\"351\" y=\"150\" rx=\"1.5\" ry=\"1.5\" width=\"60\" height=\"15\" class=\"fillclair\" onclick=\"souschaine_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"381\" y=\"162.5\">Sous-chaine</text>
<rect id=\"plus_thematique\" x=\"211\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"20\" height=\"15\" class=\"fillclair\" onclick=\"plus_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"221\" y=\"182.5\">+</text>
<rect id=\"moins_thematique\" x=\"241\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"20\" height=\"15\" class=\"fillclair\" onclick=\"moins_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"251\" y=\"182.5\">-</text>
<rect id=\"multipli_thematique\" x=\"271\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"20\" height=\"15\" class=\"fillclair\" onclick=\"multiple_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"281\" y=\"182.5\">*</text>
<rect id=\"divise_thematique\" x=\"301\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"20\" height=\"15\" class=\"fillclair\" onclick=\"divise_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"311\" y=\"182.5\">/</text>
<rect id=\"concate_thematique\" x=\"331\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"20\" height=\"15\" class=\"fillclair\" onclick=\"concate_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"341\" y=\"182.5\">||</text>
<rect id=\"x_thematique\" x=\"361\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"20\" height=\"15\" class=\"fillclair\" onclick=\"x_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"371\" y=\"182.5\">X</text>
<rect id=\"y_thematique\" x=\"391\" y=\"170\" rx=\"1.5\" ry=\"1.5\" width=\"20\" height=\"15\" class=\"fillclair\" onclick=\"y_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"401\" y=\"182.5\">Y</text>
<rect id=\"centre_thematique\" x=\"211\" y=\"190\" rx=\"1.5\" ry=\"1.5\" width=\"60\" height=\"15\" class=\"fillclair\" onclick=\"centre_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" font-size=\"10\" text-anchor=\"middle\" x=\"241\" y=\"202.5\">Centre</text>


<rect id=\"ferme_requete_thematique\" x=\"230.5\" y=\"220\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'requete_thematique')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"232.5\">Fermer</text>
<a id=\"li_requete_thematique\"><rect id=\"valide_requete_thematique\" x=\"351\" y=\"220\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"appli_req_thema()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"232.5\">Valider</text>
</g>
<g id=\"choix_pt\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_choix_pt\" width=\"312\" height=\"90\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Appliquer un texte ou un symbole</text>
<text x=\"250\" y=\"110\" text-anchor=\"end\" font-size=\"8\" >Choix</text>
<rect id=\"rect_r_choix_pt\" width=\"125\" height=\"10\" x=\"255\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_choix_pt')\"/>
<text id=\"r_choix_pt\" pointer-events=\"none\" x=\"256\" y=\"110\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect id=\"rect1_choix_pt\" width=\"10\" height=\"11\" x=\"380\" y=\"102\" onclick=\"appel_deroulant(evt,'r_choix_pt','appel')\"/>
<rect id=\"ferme_choix_pt\" x=\"230.5\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'choix_pt')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"142.5\">Fermer</text>
<a id=\"li_choix_pt\"><rect id=\"valide_choix_pt\" x=\"351\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"valid_choix_pt()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"142.5\">Valider</text>
</g>
<g id=\"choix_lg\" visibility=\"hidden\" class=\"fillfonce\">
<rect id=\"contour_choix_lg\" width=\"312\" height=\"90\" x=\"160\" y=\"66\" class=\"defaut\"/> 
<rect width=\"311\" height=\"18\" x=\"160.5\" y=\"66.5\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Appliquer le trac&#x00E9; ou le libell&#x00E9; au chemin</text>
<text x=\"250\" y=\"110\" text-anchor=\"end\" font-size=\"8\" >Choix</text>
<rect id=\"rect_r_choix_lg\" width=\"125\" height=\"10\" x=\"255\" y=\"102.5\" class=\"defaut\" onclick=\"entre_dim(evt,'r_choix_lg')\"/>
<text id=\"r_choix_lg\" pointer-events=\"none\" x=\"256\" y=\"110\" text-anchor=\"start\" font-size=\"8\"> </text>
<rect id=\"rect1_choix_lg\" width=\"10\" height=\"11\" x=\"380\" y=\"102\" onclick=\"appel_deroulant(evt,'r_choix_lg','appel')\"/>
<rect id=\"ferme_choix_lg\" x=\"230.5\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'choix_lg')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"255.5\" y=\"142.5\">Fermer</text>
<a id=\"li_choix_lg\"><rect id=\"valide_choix_lg\" x=\"351\" y=\"130\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"valid_choix_lg()\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/></a>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"376\" y=\"142.5\">Valider</text>
</g>
<g id=\"panneau_couleur\" visibility=\"hidden\" onmouseup=\"lacher(evt)\" onmousemove=\"bouger(evt)\" onmousedown=\"choisir(evt)\" class=\"fillfonce\">
<rect x=\"150\" y=\"0\" width=\"250\" height=\"250\" class=\"defaut\"/>
<rect id=\"test\" x=\"170\" y=\"20\" width=\"210\" height=\"50\" style=\"fill:#808080;fill-opacity:1.0\"/>
<text x=\"175\" y=\"100\" style=\"text-anchor:middle\">Rouge</text>
<text x=\"175\" y=\"140\" style=\"text-anchor:middle\">Vert</text>
<text x=\"175\" y=\"180\" style=\"text-anchor:middle\">Bleu</text>
<rect x=\"200\" y=\"90\" width=\"160\" height=\"10\" style=\"fill:red\"/>
<rect x=\"200\" y=\"130\" width=\"160\" height=\"10\" style=\"fill:green\"/>
<rect x=\"200\" y=\"170\" width=\"160\" height=\"10\" style=\"fill:blue\"/>
<rect id=\"rouge\" x=\"275\" y=\"85\" width=\"10\" height=\"20\" class=\"fillclair\"/>
<rect id=\"vert\" x=\"275\" y=\"125\" width=\"10\" height=\"20\" class=\"fillclair\"/>
<rect id=\"bleu\" x=\"275\" y=\"165\" width=\"10\" height=\"20\" class=\"fillclair\"/>
<g id=\"control_opa\" visibility=\"hidden\">
<text x=\"175\" y=\"220\" style=\"text-anchor:middle\">Opacit&#x00E9;</text>
<rect x=\"200\" y=\"210\" width=\"160\" height=\"10\" style=\"fill:yellow\"/>
<rect id=\"opaque\" x=\"355\" y=\"205\" width=\"10\" height=\"20\" class=\"fillclair\"/>
</g>
<rect id=\"ferme_panneau_couleur\" x=\"200\" y=\"230\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"ferme_fenetre(evt,'panneau_couleur')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"225\" y=\"242.5\">Fermer</text>
<rect id=\"valide_panneau_couleur\" x=\"300\" y=\"230\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"valide_coul(evt)\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text pointer-events=\"none\" text-anchor=\"middle\" x=\"325\" y=\"242.5\">Valider</text>
</g>
<g id=\"deroul\" style=\"visibility:hidden\" class=\"defaut\">

<svg id=\"rect_deroul\"  x=\"645\" y=\"279\" height=\"305\" width=\"148\" viewBox=\"636 279 148 305\">
<rect id=\"rect_de\" width=\"645\" height=\"279\" x=\"0\" y=\"0\"/>		
<rect id=\"rect_de1\" width=\"10\" height=\"279\" x=\"0\" y=\"0\"/>		
		<g id=\"curseurderoul\">
    	<rect id=\"scroll_cursor_deroul\" pointer-events=\"visible\" class=\"fillclair\" width=\"10\" height=\"281\" x=\"772\" y=\"291\" onmouseup=\"liste_glisse_click(evt,'false')\" onmousedown=\"liste_glisse_click(evt,'true')\" onmousemove=\"liste_glisse(evt,'scroll_cursor_deroul')\" onmouseout=\"liste_glisse_click(evt,'false')\"/>
		</g>
		<g id=\"tderoul\" >
		</g>
		</svg></g>";
//<rect id=\"rect_deroul\" width=\"100\" height=\"100\" x=\"280\" y=\"200\"/>
//</g>";
$data.="<g id=\"message\" visibility=\"hidden\" class=\"fillfonce\" font-size=\"14\" opacity=\"0.8\">
<rect id=\"idcont\" width=\"292\" height=\"100\" x=\"170\" y=\"66\" class=\"defaut\"/> 
<rect width=\"292\" height=\"18\" x=\"170\" y=\"66\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Information</text>
<g id=\"idmessage\" style=\"font-size:10;\">
</g>
<rect id='fermealerte' x=\"291\" y=\"140\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"hidealert();clear('idmessage')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text id=\"idok\" pointer-events=\"none\" text-anchor=\"middle\" x=\"316\" y=\"152.5\">OK</text>
</g>
</svg>";
//$data=gzcompress("$data",9);
echo $data;
?>