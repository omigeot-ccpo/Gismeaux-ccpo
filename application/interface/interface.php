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
/*define('GIS_ROOT', '..');
include_once('../inc/common.php');
gis_session_start();*/
//ob_start('ob_gzhandler');
/*ini_set('session.gc_maxlifetime', 3600);
session_start();
header('Cache-Control: public');
header("Pragma:");*/

define('GIS_ROOT', '..');
include_once(GIS_ROOT . '/inc/common.php');
gis_session_start();

//include("test.php");
//print_r($_SESSION['profil']);
$protocol='https';
if($_SERVER['SERVER_PORT']!=443)
{
$protocol='http';
}

//session_start();

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
//header("Content-type: image/svg+xml");
$nav="2";//mozilla
}
$os = "";
if (ereg("Linux", getenv("HTTP_USER_AGENT"))) 
  $os = "Linux"; 

$sessi=session_id();
//include("../connexion/deb.php");

if($_SESSION['profil']->idutilisateur)
{
//$menu_query="select * from admin_svg.apputi inner join admin_svg.application on admin_svg.apputi.idapplication=admin_svg.application.idapplication join admin_svg.utilisateur on admin_svg.apputi.idutilisateur=admin_svg.utilisateur.idutilisateur where apputi.idutilisateur=".$_SESSION["utcleunik"]." order by application.type_appli asc";
$menu_query="select apputi.idapplication,utilisateur.nom,utilisateur.prenom,apputi.droit,application.type_appli,application.url,application.libelle_appli from admin_svg.apputi inner join admin_svg.application on admin_svg.apputi.idapplication=admin_svg.application.idapplication join admin_svg.utilisateur on admin_svg.apputi.idutilisateur=admin_svg.utilisateur.idutilisateur where apputi.idutilisateur=".$_SESSION['profil']->idutilisateur." order by application.type_appli asc";
$mn=$DB->tab_result($menu_query);

if($_SESSION["profil"]->appli=="")
{
$_SESSION["profil"]->appli=$mn[0]['idapplication'];
$_SESSION["profil"]->droit_appli=$mn[0]['droit'];
}
else
{
$sql_droit="select apputi.droit from admin_svg.apputi where apputi.idutilisateur=".$_SESSION["profil"]->idutilisateur." and apputi.idapplication=".$_SESSION["profil"]->appli;
$mn_droit=$DB->tab_result($sql_droit);
$_SESSION["profil"]->droit_appli=$mn_droit[0]['droit'];
}
}

$reqcom="select (commune.xma::real - commune.xmi::real) as largeur,commune.xmi as xini, (commune.yma::real - commune.ymi::real) as hauteur ,commune.yma as yini, (commune.xmi::real + (commune.xma::real - commune.xmi::real)/2) as xcenter,(commune.ymi::real + (commune.yma::real - commune.ymi::real)/2) as ycentre,logo,idagglo from admin_svg.commune where commune.idcommune like '".$_SESSION["profil"]->insee."'";
		$vu=$DB->tab_result($reqcom);
$query_agglo="SELECT * FROM admin_svg.commune where idcommune like '".$vu[0]['idagglo']."'";
$row_agglo = $DB->tab_result($query_agglo);	
$taillecom = GetImageSize($vu[0]['logo']);
$tailleaglo = GetImageSize($row_agglo[0]['logo']);	
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

$req="select appthe.zoommax,appthe.zoommin,appthe.idtheme from admin_svg.appthe join admin_svg.theme on appthe.idtheme=theme.idtheme where theme.groupe in(select theme.groupe from admin_svg.theme group by theme.groupe having count(theme.groupe)>1) and appthe.idapplication=".$_SESSION["profil"]->appli." and theme.groupe<>'' order by appthe.idtheme asc";
$grou=$DB->tab_result($req);
$zoom_groupe_max=0;
$zoom_groupe_min=10000;
$id_groupe="";
if(count($grou)>1)
{
for ($c=0;$c<count($grou);$c++)
{
if($grou[$c]['zoommax']>$zoom_groupe_max)
{
$zoom_groupe_max=$grou[$c]['zoommax'];
}
if($zoom_groupe_min>$grou[$c]['zoommin'])
{
$zoom_groupe_min=$grou[$c]['zoommin'];
}
if($c==0)
{
$id_selec_groupe=$grou[$c]['idtheme'];
}
if($c>0)
{
$id_groupe.="'".$grou[$c]['idtheme']."',";
}
}

$id_groupe=substr($id_groupe,0,strlen($id_groupe)-1);
}
$req2="select theme.idtheme,theme.libelle_them as nom_theme,theme.schema,theme.tabl,appthe.idappthe,col_theme.colonn,admin_svg.v_fixe(col_theme.valeur_texte),appthe.raster,sinul(appthe.zoommin::character varying,theme.zoommin::character varying) as zoommin,sinul(appthe.zoommax::character varying,theme.zoommax::character varying) as zoommax,sinul(appthe.zoommaxraster::character varying,theme.zoommax_raster::character varying) as zoommax_raster,theme.raster as testraster,application.zoom_min as zoom_min_appli,application.zoom_max as zoom_max_appli,application.zoom_ouverture as zoom_ouverture_appli,sinul(appthe.partiel,theme.partiel) as partiel,sinul(appthe.vu_initial,theme.vu_initial) as vu_initial,style.idstyle,style.fill as style_fill,style.symbole as style_symbole,style.opacity  as style_opacity,style.font_size  as style_fontsize,style.stroke_rgb  as style_stroke,style.stroke_width  as style_strokewidth,application.btn_polygo,application.libelle_btn_polygo,theme.groupe,application.libelle_appli,appthe.force_chargement from admin_svg.appthe join admin_svg.theme on appthe.idtheme=theme.idtheme join admin_svg.application on appthe.idapplication=application.idapplication left outer join  admin_svg.col_theme on appthe.idappthe=col_theme.idappthe left outer join admin_svg.style on appthe.idtheme=style.idtheme where appthe.idapplication=".$_SESSION["profil"]->appli;
if($id_groupe!="")
{
$req2.=" and appthe.idtheme not in(".$id_groupe.")";
}
$req2.=" group by theme.idtheme,theme.libelle_them,appthe.ordre,theme.schema,theme.tabl,col_theme.colonn,admin_svg.v_fixe(col_theme.valeur_texte),appthe.raster,theme.zoommin,appthe.zoommin,theme.zoommax,appthe.zoommax,theme.zoommax_raster,appthe.zoommaxraster,theme.raster,application.zoom_min,application.zoom_max,application.zoom_ouverture,appthe.partiel,theme.partiel,appthe.vu_initial,theme.vu_initial,style.idstyle,style.fill,style.symbole,style.opacity,style.font_size,style.stroke_rgb,style.stroke_width,appthe.idappthe,application.btn_polygo,application.libelle_btn_polygo,theme.groupe,application.libelle_appli,appthe.force_chargement order by appthe.ordre asc";
$cou=$DB->tab_result($req2);
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

}
else
{
$zoomouv=$zoommin;
}
$posi=525;
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
	$req1.=" join ".$cou[$c]['schema'].".".$cou[$c]['tabl']." on col_theme.valeur_texte=".$cou[$c]['tabl'].".".$cou[$c]['colonn']." where 					appthe.idapplication=".$_SESSION["profil"]->appli." and theme.libelle_them='".$cou[$c]['nom_theme']."'";
	if(substr($_SESSION["profil"]->insee, -3)!='000' && $cou[$c]['schema']!="bd_topo")
	{
	$req1.=" and (".$cou[$c]['tabl'].".code_insee like '".$_SESSION["profil"]->insee."'  or code_insee is null) ";
	 }
	 $req1.=" order by col_theme.ordre asc";
	}
	
	else
	{
	$req1.=" where appthe.idapplication=".$_SESSION["profil"]->appli." and theme.libelle_them='".$cou[$c]['nom_theme']."' order by col_theme.ordre asc";
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
				$tab_layer.="zlayer['".$cou[$c]['idappthe'].".".$couch[$r]['intitule_legende']."']=new glayer('".$z.codalpha($r+1)."','FALSE','','',".$zz.",'".$cou[$c]['partiel']."','".$cou[$c]['force_chargement']."');";
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
	if($cou[$c]['idtheme']==$id_selec_groupe)
	{
	$leg=$cou[$c]['idappthe'].".".$cou[$c]['groupe'];
	}
	else
	{
	$leg=$cou[$c]['idappthe'].".".$cou[$c]['nom_theme'];
	}
	
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
		$tab_layer.="zlayer['".$cou[$c]['idappthe'].".".$cou[$c]['nom_theme']."']=new glayer('".$z."','FALSE','','',".$zz.",'".$cou[$c]['partiel']."','".$cou[$c]['force_chargement']."');";
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
if(ereg("[^befghijnYZ]",$couch[$w]['symbole']) || ereg("[^abefghijlmnYZ]",$cou[$c]['style_symbole']))
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
if($cou[$c]['idtheme']==$id_selec_groupe)
	{
	$legende.="<text id=\"text".$z."\" x=\"674\" y=\"".($y+8)."\" class=\"fillfonce\">".$cou[$c]['groupe']."</text>\n";
	$lay.="controllay[".$c."]=new ylayer(".$zoom_groupe_min.",".$zoom_groupe_max.",'".$typ."');";
	}
	else
	{
		if($cou[$c]['groupe']!="")
		{
		$legende.="<text id=\"text".$z."\" x=\"674\" y=\"".($y+8)."\" class=\"fillfonce\">".$cou[$c]['groupe']."</text>\n";
		}
		else
		{
		$legende.="<text id=\"text".$z."\" x=\"674\" y=\"".($y+8)."\" class=\"fillfonce\">".$cou[$c]['nom_theme']."</text>\n";
		}
	$lay.="controllay[".$c."]=new ylayer(".$cou[$c]['zoommin'].",".$cou[$c]['zoommax'].",'".$typ."');";
	}

//$legende.="<text id=\"text".$z."\" x=\"662\" y=\"".($y+8)."\" class=\"fillfonce\">".$cou[$c]['nom_theme']."</text>\n";

	if(count($couch)>0)
	{
		$legende.="<rect id=\"deroul".$z."\" x=\"640\" y=\"".($y+1)."\" width=\"6\" height=\"6\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','white','','')\" onclick=\"Javascript:derouler(evt,".$z.",".count($couch).")\"/>\n";
		$legende.="<line x1=\"641\" x2=\"645\" y1=\"".($y+4)."\" y2=\"".($y+4)."\" pointer-events=\"none\"/><line id=\"d".$z."\" x1=\"643\" x2=\"643\" y1=\"".($y+6)."\" y2=\"".($y+2)."\" visibility=\"visible\" pointer-events=\"none\"/>\n";
		$legende.="</g>\n";
		$legende.="<g id=\"soustheme".$z."\" visibility=\"hidden\">\n";
		$y1=$y+12;
		for ($w=0;$w<count($couch);$w++)
		{
			
			if($couch[$w]['symbole']!="" and ereg("[^abefghijlmnYZ]",$couch[$w]['symbole']))
			{
			$texte=fopen("./","r");
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
if (file_exists("../doc_commune/".$_SESSION["profil"]->insee."/css_interface/interface.css"))
{
$data.="<?xml-stylesheet href=\"../doc_commune/".$_SESSION["profil"]->insee."/css_interface/interface.css\" type=\"text/css\" ?>";
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
$data.="nav=".$nav.";\n";
$data.=$tab_layer."\n";
$data.=$layer."\n";
$data.=$lay."\n"; 
$data.="function glayer(controle,visible,zoom_charge,position,zoomraster,partiel,force_charge){
	this.svg_controle=controle;
	this.svg_visible=visible;
	this.svg_zoom_charge=zoom_charge;
	this.svg_position=position;
	this.svg_zoomraster=zoomraster;
	this.svg_partiel=partiel;
	this.svg_force_charge=force_charge;
	
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
$data.="xcenterini=".($vu[0]['xcenter']-$vu[0]['xini']).";\n";
$data.="ycenterini=".($vu[0]['yini']-$vu[0]['ycentre']).";\n";
$data.="intervale=".$intervale.";\n";
$data.="theZoom=".$zoomouv.";\n";
$data.="zoomVal=".$zoomouv.";\n";
$data.="appli=".$_SESSION["profil"]->appli."; \n";
$data.="sessionid='".$sessi."';\n";
$data.="sessionname='".session_name()."';\n";
$data.="code_insee=".$_SESSION["profil"]->insee.";\n";
$data.="xini=".$vu[0]['xini'].";\n";
$data.="yini=".$vu[0]['yini'].";\n";
$data.="largeurini=".$_SESSION['large'].";\n";
$data.="hauteurini=".$_SESSION['haute'].";\n";
$data.="va_appli='".count($mn)."';\n";
$data.="serveur='".$_SERVER['HTTP_HOST']."';\n";
$data.="protocol='".$protocol."';\n";
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
<glyph unicode=\"l\" glyph-name=\"l\" horiz-adv-x=\"1825\" d=\"M913 1480L1653 740H173L913 1480Z\" />
<glyph unicode=\"m\" glyph-name=\"m\" horiz-adv-x=\"1825\" d=\"M913 0L173 740H1653L913 0Z\" />
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
   <g><svg id=\"logcommune\"  x=\"0\" y=\"0\" width=\"75\" height=\"35\" viewBox=\"0 0 ".$taillecom[0]." ".$taillecom[1]."\">
	   <image x=\"0\" y=\"0\" width=\"".$taillecom[0]."\" height=\"".$taillecom[1]."\" xlink:href=\"".$vu[0]['logo']."\"/>
	   </svg><svg id=\"logaglo\"  x=\"725\" y=\"0\" width=\"75\" height=\"35\" viewBox=\"0 0 ".$tailleaglo[0]." ".$tailleaglo[1]."\">
	   <image x=\"0\" y=\"0\" width=\"".$tailleaglo[0]."\" height=\"".$tailleaglo[1]."\" xlink:href=\"".$row_agglo[0]['logo']."\"/>
	   </svg><path
       d=\"M 0,35 L 70,35 C 88,35 70,1 88,1 L 320,1 C 342,1 320,35 342,35 L 459,35 C 479,35 459,1 479,1 L 712,1 C 730,1 712,35 730,35 L 800,35 L 0,35 z \"
       style=\"fill:rgb(235,228,225);stroke:rgb(235,228,225);stroke-width:1px\" />
	   
	  </g>
    <g id=\"layer1\" class=\"defaut\">
   	
    <svg id=\"mapid\"  x=\"10\" y=\"44\" width=\"780\" height=\"546\" viewBox=\"0 0 ".$_SESSION['large']." ".$_SESSION['haute']."\"  onmousedown=\"createcircle(evt);\" onmouseup=\"fintrait(evt)\" onmousemove=\"bougetrait(evt)\"  onmouseover=\"desinib_use();\">
	<g id=\"enregistrement\">
<rect id=\"desrect\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" stroke=\"none\" fill=\"white\" pointer-events=\"none\"/>
	<g id=\"dessin\">";
$data.=$controle;
if($_SESSION["profil"]->droit_appli!='v')
	{
	$finsession="<a id='lifinsession'><tspan id=\"finsession\" stroke=\"red\" dx=\"3\" dy=\"0\" font-family=\"fontsvg\" stroke-width=\"3.5\" font-size=\"7\" onmouseover=\"showinfotip(evt,'Se d&#233;connecter')\" onmouseout=\"hideinfotip(evt)\" onclick=\"fin_session()\">q</tspan></a><tspan fill=\"white\" dx=\"-5.25\" dy=\"0.25\" font-size=\"7\">X</tspan>";
	}	
$data.="</g>
<g id='dess' stroke-width='0.2'>
	
	</g>
</g>
	</svg>
	<g id=\"cardinal\" style=\"stroke:none\">
	<rect x=\"2\" y=\"44\" width=\"8\" height=\"546\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','ouest')\" onmouseout=\"switchColor(evt,'fill','none','','ouest')\" onclick=\"goWest();\"/>
	<path id=\"ouest\" pointer-events=\"none\" d=\"M 2 295 10 224 10 366z\" />
	<text pointer-events=\"none\" x=\"2.25\" y=\"297\" class=\"coulblanc\" font-size=\"8\">W</text>
	<rect x=\"790\" y=\"44\" width=\"8\" height=\"546\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','est')\" onmouseout=\"switchColor(evt,'fill','none','','est')\" onclick=\"goEast();\"/>
	<path id=\"est\" pointer-events=\"none\" d=\"M 798 295 790 224 790 366z\" />
	<text pointer-events=\"none\" x=\"791\" y=\"297\" class=\"coulblanc\" font-size=\"8\">E</text>
	<rect x=\"10\" y=\"36\" width=\"780\" height=\"8\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','nord')\" onmouseout=\"switchColor(evt,'fill','none','','nord')\" onclick=\"goNorth();\"/>
	<path id=\"nord\" pointer-events=\"none\" d=\"M 400 36 329 44 471 44z\" />
	<text pointer-events=\"none\" x=\"399\" y=\"43\" class=\"coulblanc\" font-size=\"8\">N</text>
	<rect x=\"10\" y=\"590\" width=\"780\" height=\"8\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','sud')\" onmouseout=\"switchColor(evt,'fill','none','','sud')\" onclick=\"goSouth();\"/>
	<path id=\"sud\" pointer-events=\"none\" d=\"M 400 598 329 590 471 590z\" />
	<text pointer-events=\"none\" x=\"399\" y=\"597\" class=\"coulblanc\" font-size=\"8\">S</text>
	</g>
	<text x=\"400\" y=\"7\" class=\"fillfonce\" text-anchor=\"middle\" font-size=\"6\">Connect&#233;:".$mn[0]['nom']." ".$mn[0]['prenom'].$finsession."</text>";
	/*if($_SESSION["profil"]->droit_appli!='v')
	{
	$data.="<a id='lifinsession'><rect id=\"finsession\" class=\"fillfonce\" width=\"8\" height=\"6\" x=\"0\" y=\"37\" ry=\"1.2\" rx=\"1.2\" onclick=\"fin_session()\"/></a>
    <text x=\"2\" y=\"42\" style=\"font-size:5.5px;font-family:Arial\" class=\"fillclair\" pointer-events=\"none\">X</text>";
	}*/
	$data.="<rect id=\"map\" width=\"780\" height=\"546\" x=\"10\" y=\"44\" fill=\"none\" pointer-events=\"none\"/>
	<rect x=\"540\" y=\"571\" width=\"245\" height=\"14\" class=\"fillclair\" style=\"opacity:0.6\" pointer-events=\"none\"/>
	<text pointer-events=\"none\" x=\"13\" y=\"70\" class=\"fillfonce\" style=\"font-size:30px;font-family:fontsvg\">a</text>
	<text pointer-events=\"none\" x=\"545\" y=\"581\" class=\"fillfonce\" font-size=\"8\">Source: direction g&#233;n&#233;rale des imp&#244;ts - cadastre;mise &#224; jour:2008</text>
	<g id=\"message_box\" visibility=\"hidden\" >
	<rect x=\"241\" y=\"264\" width=\"160\" height=\"18\" class=\"fillclair\" style=\"opacity:0.6\" pointer-events=\"none\" />
	<text pointer-events=\"none\" x=\"250\" y=\"277\" font-size=\"15\" class=\"fillfonce\">Veuillez patienter</text>
	<g id=\"anim\">
	<path fill-opacity=\"0\" d=\"M 380 268 A 5 5 0 1 1 375 273 M 374 275.5 375 273 377.5 274z\"/>
	<animateTransform id=\"anim_arr\" attributeName=\"transform\" begin=\"indefinite\"  attributeType=\"XML\" type=\"rotate\" from=\"0 380 273\" to=\"2160 380 273\" dur=\"10s\" repeatDur=\"indefinite\"/>
	</g>
	</g>
	
	<path d=\"M 785,56 785,46 L 730,46 C 720,46 730,56 720,56 L 785,56 z \" class=\"fillclair\"/>
	<text pointer-events=\"none\" x=\"735\" y=\"53\" class=\"fillfonce\" style=\"stroke:url(#survol);stroke-width:0.3;font-size:8px\">Navigation</text>
	<rect id=\"na\" x=\"775\" y=\"46\" width=\"10\" height=\"10\" class=\"fillfonce\" onclick=\"javascript:navigation()\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','url(#survol)','','')\"/>
	<text id=\"t_navigation\" x=\"775.5\" y=\"56\" style=\"font-size:10px;font-family:fontsvg\" class=\"fillclair\"  pointer-events=\"none\" >l</text>
	<g id=\"navigation\" opacity=\"1\" pointer-events=\"visible\" >
	<rect width=\"157\" height=\"109.9\" x=\"628\" y=\"56\" fill=\"rgb(255,255,255)\" pointer-events=\"none\"/>
	<svg id=\"overviewmap\" x=\"628\" y=\"56\" width=\"157\" height=\"109.9\" viewBox=\"0 0 ".$_SESSION['large']." ".$_SESSION['haute']."\" onmouseover=\"inib_use();\" >";
    if (file_exists("./communes/".$_SESSION["profil"]->insee."_16_9.JPG"))
	{
	$data.="<image id=\"fond\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"./communes/".$_SESSION["profil"]->insee."_16_9.JPG\"/>";
	}
	else
	{
	
	$url="https://".$_SERVER['HTTP_HOST']."/interface/crea_fond_carte_svg.php?codeinsee=".$_SESSION["profil"]->insee;
	$contenu=file($url);
       		while (list($ligne,$cont)=each($contenu)){
			$numligne[$ligne]=$cont;
		}
		$img=$contenu[0];
	//$data.="<image id=\"fond\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"./communes/default.JPG\"/>";
	$data.="<image id=\"fond\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"".$img."\"/>";
	}
	$data.="<use id=\"retour_map\" x=\"0\" y=\"0\" width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" xlink:href=\"#dessin\" />
	<g onmousedown=\"beginPan(evt)\" onmousemove=\"doPan(evt)\" onmouseup=\"endPan(evt)\" onmouseout=\"endPan(evt)\">
	<rect id=\"Rect1\" cursor=\"move\" style=\"fill:rgb(255,0,0);stroke-width:20;stroke:rgb(0,0,0);fill-opacity:0.4\" x=\"0\" y=\"0\"
 width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\" visibility=\"hidden\" />
 <rect id=\"lin1\" stroke-width=\"20\" fill=\"rgb(0,0,0)\" x=\"0\" y=\"".($_SESSION['haute']/2)."\" width=\"".$_SESSION['large']."\" height=\"20\" visibility=\"hidden\" pointer-events=\"none\"/>
 <rect id=\"lin2\" stroke-width=\"20\" fill=\"rgb(0,0,0)\" x=\"".($_SESSION['large']/2)."\" y=\"0\" width=\"20\" height=\"".$_SESSION['haute']."\" visibility=\"hidden\" pointer-events=\"none\"/>

	<rect id=\"locationRect\" cursor=\"move\" class=\"fillfonce\" style=\"fill-opacity:0.5\" x=\"0\" y=\"0\"
 width=\"".$_SESSION['large']."\" height=\"".$_SESSION['haute']."\"  visibility=\"hidden\" />
 </g>	
	
	</svg><rect id=\"locamap\" width=\"157\" height=\"109.9\" x=\"628\" y=\"56\" fill=\"none\" pointer-events=\"none\"/></g>
	
    <g class=\"fillclair\">
<a id=\"aide\"><use x=\"291\" y=\"6\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Aide en ligne')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:aide()\"/></a>
<text x=\"298\" y=\"21\" font-weight=\"normal\" font-family=\"Arial\" font-size=\"16\" class=\"fillfonce\" pointer-events=\"none\" >?</text>
<text x=\"294\" y=\"28\" font-weight=\"normal\" font-family=\"Arial\" font-size=\"8\" class=\"fillfonce\" pointer-events=\"none\" >Aide</text></g>
<g class=\"fillclair\">
			<a id=\"contacte\"><use x=\"263.5\" y=\"6\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Nous contacter')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:contacte()\" class=\"fillclair\"/></a>
        	<text x=\"264.5\" y=\"24\" style=\"font-size:18px;font-family:fontsvg\" class=\"fillfonce\" pointer-events=\"none\">Y</text></g>
    	<g id=\"zoomin\">
      		<g id=\"graduation\" class=\"fillclair\">
        	<g id=\"moins\"><rect x=\"513.5\" y=\"10\" width=\"9\" height=\"15\" ry=\"3.7\" rx=\"3\" onmouseover=\"switchColor(evt,'fill','url(clair)','','');\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\" onclick=\"Zoomless(evt);\"/>
			<text x=\"516\" y=\"21\" font-size=\"12px\" pointer-events=\"none\" class=\"fillfonce\">-</text></g>
        	<g id=\"plus\"><rect x=\"611\" y=\"10\" width=\"9\" height=\"15\" rx=\"3\" ry=\"3.7\" onmouseover=\"switchColor(evt,'fill','url(clair)','','');\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\" onclick=\"Zoommore(evt);\"/>			
     		<text x=\"612\" y=\"22\" font-size=\"12px\" pointer-events=\"none\" class=\"fillfonce\">+</text></g>
			<path id=\"zoomcursor\" transform=\"translate(523.75,24)\" d=\"M2.5 0 5 5 0 5Z\"/>";



for ($int=0;$int<19;$int++)
{
$data.="<rect id=\"rectzoom".$min."\" x=\"".$posi."\" y=\"12.5\" width=\"2.5\" height=\"10\" onmouseover=\"switchColor(evt,'fill','url(#survol)','','');showinfotip(evt,'".$min." %')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','');hideinfotip(evt)\" onclick=\"Zoomto(evt,".$min.");\"/>";
$min=$min+$intervale;
$posi=$posi+4.5;
}
$data.="</g><rect id=\"releasezoom\" x=\"6\" y=\"11\" width=\"124\" height=\"124\" style=\"fill:rgb(255,0,0);opacity:0;visibility:hidden;\" pointer-events=\"none\" onclick=\"releaseZoom(evt);\"/>
<rect id=\"rectevt\" x=\"0\" y=\"0\" width=\"0\" height=\"0\" style=\"opacity:0.5;visibility:hidden\" pointer-events=\"none\"/>
<rect id=\"bgrectevt\"  cursor=\"crosshair\" x=\"10\" y=\"44\" width=\"780\" height=\"546\"  style=\"fill:rgb(255,0,255);opacity:0;visibility:hidden\" onmousedown=\"beginResize(evt)\" onmouseup=\"endResize(evt)\" onmousemove=\"doResize(evt)\" pointer-events=\"none\"/>
      		<g id=\"loupe\" class=\"fillclair\" onclick=\"Javascript:Zoomin(evt);\" onmouseover=\"showinfotip(evt,'Zoom dans la carte')\" onmouseout=\"hideinfotip(evt)\">
			
			<use x=\"485.5\" y=\"6\" ry=\"3\" rx=\"3\" xlink:href=\"#boutonvierge\"/>
			
        	<text x=\"473.5\" y=\"11\" transform=\"rotate(40,470.5,26)\" style=\"font-size:25px;font-family:fontsvg\" pointer-events=\"none\" class=\"fillfonce\" >f</text>
      		</g>
      	</g>
    
    	<g id=\"outil\" class=\"fillclair\" font-family=\"fontsvg\">
      		<g id=\"impression\">
       		<a id=\"liprint\"><use id=\"boutonprint\" x=\"95\" y=\"6\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Impression')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:impression()\"/></a>
	   		<text x=\"95\" y=\"25\" style=\"font-size:22px;stroke:url(#survol);stroke-opacity:1;stroke-width:0.4\" pointer-events=\"none\">e</text>
     		</g>
      		<g id=\"simple\" >
	  		<use id=\"boutonselect\" x=\"123.5\" y=\"6\" stroke='red' xlink:href=\"#boutonvierge\" onclick=\"selectunique(evt);changecolor('boutonselect')\" onmouseover=\"showinfotip(evt,'Selection simple')\" onmouseout=\"hideinfotip(evt)\"/>
	  		<text id=\"sel\" x=\"127\" y=\"25\" style=\"stroke:url(#survol);font-size:20px;stroke-width:0.3\" pointer-events=\"none\">g</text>
			
			<g id=\"valmultiple\"  visibility='hidden'>
     		<a id='livalide'><rect id='valid' class=\"fillfonce\" width=\"15\" height=\"6\" x=\"127.5\" y=\"29\" ry=\"1.2\" rx=\"1.2\" onclick='validmulti()'/></a>
        	<text id='textvalid' x=\"131\" y=\"34\" style=\"font-size:5.5px;font-family:Arial\" class=\"fillclair\" pointer-events=\"none\">OK</text>
			</g></g>
			<g id=\"visible_legende\">
       		<a id=\"lileg\"><use id=\"boutonleg\" x=\"235.5\" y=\"6\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Rendre (in)visible la l&#x00E9;gende')\" onmouseout=\"hideinfotip(evt)\" onclick=\"javascript:legende()\"/></a>
			<path id=\"txt_vileg\" opacity=\"1\" transform=\"translate(237,8)\" pointer-events=\"none\" class=\"fillfonce\" d=\"M3 3 4 3 4 4 3 4Z M5 6 6 6 6 7 5 7Z M5 9 6 9 6 10 5 10Z M3 12 4 12 4 13 3 13Z M3 15 4 15 4 16 3 16Z M6 3.5 14 3.5 M8 6.5 16 6.5 M8 9.5 16 9.5 M6 12.5 14 12.5 M6 15.5 14 15.5\"/>
	   		
     		</g>
			<g id=\"select_appli\" >
			<rect y=\"10.5\" x=\"335\" height=\"15\" width=\"130\" ry=\"3\" rx=\"3\" class=\"fillclair\"/>
			<rect class=\"fillfonce\" width=\"15\" height=\"15\" x=\"450\" y=\"10.5\" ry=\"3\" rx=\"3\" onclick=\"selec_appli()\"/>
			<text style=\"font-size:10;pointer-events:none\" x=\"453\" y=\"21\" class=\"fillclair\">m</text>
			<text x=\"392.5\" y=\"21\" style=\"font-size:8px;font-family:Arial;pointer-events:none;text-anchor:middle\" class=\"fillfonce\">".$cou[0]['libelle_appli']."</text>
			</g>";
			$st="";
			if($_SESSION["profil"]->droit_appli=='v')
{
$st="pointer-events=\"none\" opacity=\"0.4\"";
 } 
    		
      		$data.="<g id=\"regle\" ".$st.">
			<use id=\"boutonregle\" x=\"151.5\" y=\"6\" xlink:href=\"#boutonvierge\" onclick=\"activetrait(evt);changecolor('boutonregle');\" onmouseover=\"showinfotip(evt,'Mesure de distance')\" onmouseout=\"hideinfotip(evt)\"/>
		  	<text id=\"outi\" x=\"155.5\" y=\"23\" style=\"font-size:15px\" pointer-events=\"none\" class=\"fillfonce\" >j</text> 
            <g id=\"effacemesure\" visibility='hidden' onclick=\"effacetrait(evt)\">
			<rect class=\"fillfonce\" width=\"22\" height=\"6\" x=\"152\" y=\"29\" ry=\"1.2\" rx=\"1.2\"/>
        	<text x=\"153.5\" y=\"34\" style=\"font-size:5.5px;font-family:Arial\" class=\"fillclair\" pointer-events=\"none\">Effacer</text></g>
			<g id=\"effacesurface\" visibility='hidden' onclick=\"effacetrait(evt)\">
			<rect class=\"fillfonce\" width=\"10\" height=\"6\" x=\"152.5\" y=\"29\" ry=\"1.2\" rx=\"1.2\"/>
        	<text x=\"155\" y=\"34\" style=\"font-size:5.5px;font-family:Arial\" class=\"fillclair\" pointer-events=\"none\">X</text></g>
			<g id=\"validesurface\" visibility='hidden' onclick=\"validesurface(evt)\">
			<rect class=\"fillfonce\" width=\"10\" height=\"6\" x=\"163.5\" y=\"29\" ry=\"1.2\" rx=\"1.2\"/>
        	<text x=\"165\" y=\"34\" style=\"font-size:5.5px;font-family:Arial\" class=\"fillclair\" pointer-events=\"none\">OK</text></g>
			
			</g>";
if($_SESSION["profil"]->droit_appli=='v' || $_SESSION["profil"]->droit_appli=='c')
{
$st1="pointer-events=\"none\" opacity=\"0.4\"";
 } 
			if($cou[0]['btn_polygo']!="")
			{
      		$data.="<g id=\"polygo\" ".$st1." >
			<use id=\"boutonpolygo\" x=\"179.5\" y=\"6\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'".$cou[0]['libelle_btn_polygo']."')\" onmouseout=\"hideinfotip(evt)\" onclick=\"activpoly(evt);changecolor('boutonpolygo');\"/>
        	<text x=\"181\" y=\"25\" style=\"fill:none;stroke:url(#survol);font-size:22px;stroke-width:0.8\" pointer-events=\"none\">n</text>
			<g id=\"validepolygo\" visibility='hidden'  onclick=\"validepoly(evt)\">
			<a id='livalidepolygo'><rect class=\"fillfonce\" width=\"10\" height=\"6\" x=\"180.5\" y=\"29\" ry=\"1.2\" rx=\"1.2\"/></a>
        	<text x=\"182\" y=\"34\" style=\"font-size:5.5px;font-family:Arial\" pointer-events=\"none\" class=\"fillclair\">OK</text></g>
			<g id=\"effacepolygo\" visibility='hidden' onclick=\"effacetrait(evt)\">
			<rect class=\"fillfonce\" width=\"10\" height=\"6\" x=\"191.5\" y=\"29\" ry=\"1.2\" rx=\"1.2\"/>
			<text x=\"194\" y=\"34\" style=\"font-size:5.5px;font-family:Arial\" pointer-events=\"none\" class=\"fillclair\">X</text></g>
        	</g>
    	</g>";
		}
		else
		{
		$data.="</g>";
		}
$data.="<g id=\"enregistre\" ".$st.">
			<a id=\"lienre\"><use id=\"enre\" x=\"207.5\" y=\"6\" xlink:href=\"#boutonvierge\" onmouseover=\"showinfotip(evt,'Enregistrement')\" onmouseout=\"hideinfotip(evt)\" onclick=\"enregistre()\" class=\"fillclair\"/></a>
        	<text x=\"209\" y=\"25\" style=\"font-size:22px;font-family:fontsvg\" class=\"fillfonce\" pointer-events=\"none\">Z</text>
";
//}
     	
		$data.="</g><g id=\"recherche\">
		<rect y=\"10.5\" x=\"625\" height=\"15\" width=\"80\" ry=\"3\" rx=\"3\" onclick=\"entre_dim(evt)\" class=\"fillclair\"/>
		<text id=\"x_rect\" x=\"626\" y=\"21\" style=\"font-size:8px;fill-opacity:0.4;pointer-events:none\" class=\"fillfonce\">Recherche</text>
     	<text id=\"cligno1\" x=\"626\" y=\"20\" style=\"font-size:8px;fill-opacity:1;pointer-events:none;visibility:hidden;stroke:1\">|</text>
		
      	<rect class=\"fillfonce\" width=\"15\" height=\"15\" x=\"690\" y=\"10.5\" ry=\"3\" rx=\"3\" onclick=\"recherche()\"/>
      	<text style=\"font-size:8;font-family:Arial;pointer-events:none\" x=\"692\" y=\"21\" class=\"fillclair\">Go</text>
      	</g>
    	
		<g id=\"echelle\" class=\"fillfonce\" > 
		<text y=\"585\" x=\"45\" style=\"text-anchor:middle;font-size:8px\">M&#x00E9;tres</text>
		<text id=\"gauche\" y=\"569\" x=\"20\" style=\"text-anchor:middle;font-size:8px\">0</text>
		<text id=\"droite\" y=\"569\" x=\"70\" style=\"text-anchor:middle;font-size:8px\">2</text>
		<path d=\"M 20,570 20,575 L 70,575 70,570  \" fill=\"none\"/>
  		</g>
		
		
		<g id=\"legende\" transform=\"translate(0,0)\" opacity=\"1\" pointer-events=\"visible\" >
		<path d=\"M 785,181 785,171 L 730,171 C 720,171 730,181 720,181 L 785,181 z \" class=\"fillclair\"/>
		<text x=\"735\" y=\"179\" style=\"stroke:url(#survol);stroke-width:0.3;font-size:8px\" class=\"fillfonce\">L&#x00E9;gende</text>
		<svg id=\"leg\"  x=\"628\" y=\"181\" height=\"305\" width=\"157\" viewBox=\"636 279 157 305\" >
		
		<rect width=\"157\" height=\"305\" x=\"636\" y=\"279\" fill-opacity=\"0.5\"/>
		<rect width=\"12\" height=\"305\" x=\"781\" y=\"279\" />
		<g id=\"curseur\">
    	<rect id=\"scroll_cursor\" class=\"fillclair\" width=\"12\" height=\"281\" x=\"781\" y=\"291\" onmouseup=\"liste_glisse_click(evt,'false')\" onmousedown=\"liste_glisse_click(evt,'true')\" onmousemove=\"liste_glisse(evt)\" onmouseout=\"liste_glisse_click(evt,'false')\"/>
		</g>
    	<rect width=\"12\" height=\"12\" x=\"781\" y=\"279\" class=\"fillfonce\" onclick=\"liste_scrolling(-1)\"/>
    	<rect y=\"572\" x=\"781\" height=\"12\" width=\"12\" class=\"fillfonce\" onclick=\"liste_scrolling(1)\"/>
		<path transform=\"translate(784.5,282.5)\" pointer-events=\"none\" class=\"fillclair\" d=\"M2.5 0 5 5 0 5Z\"/>
		<path transform=\"translate(784.5,575.5)\" pointer-events=\"none\" class=\"fillclair\" d=\"M2.5 5 5 0 0 0Z\"/>
		<g id=\"layer\">";
$data.=$legende;

$data.="</g></svg>
		</g>
		
<g id=\"message\" visibility=\"hidden\" class=\"fillfonce\" font-size=\"14\" opacity=\"0.8\">
<rect id=\"idcont\" width=\"292\" height=\"100\" x=\"170\" y=\"66\" fill=\"url(#clair)\"/> 
<rect width=\"292\" height=\"18\" x=\"170\" y=\"66\" class=\"fillclair\"/>
<text pointer-events=\"none\" x=\"316\" y=\"80\" text-anchor=\"middle\" >Information</text>
<g id=\"idmessage\" style=\"font-size:10;\">
</g>
<rect id='fermealerte' x=\"291\" y=\"140\" rx=\"1.5\" ry=\"1.5\" width=\"50\" height=\"15\" class=\"fillclair\" onclick=\"hidealert();clear('idmessage')\" onmouseover=\"switchColor(evt,'fill','white','','')\" onmouseout=\"switchColor(evt,'fill','url(#hors)','','')\"/>
<text id=\"idok\" pointer-events=\"none\" text-anchor=\"middle\" x=\"316\" y=\"152.5\">OK</text>
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
<g id=\"deroul_appli\" visibility=\"hidden\" class=\"defaut\">
<rect id=\"rect_deroul\" width=\"115\" height=\"".(10+(10*count($mn)))."\" x=\"335\" y=\"25.5\"></rect>";
$positex=35.5;
for ($c=0;$c<count($mn);$c++)
{
$data.="<a id=\"liappli".$c."\"><text id=\"ap".$c."\" x=\"392.5\" y=\"".$positex."\" class=\"fillfonce\" style=\"font-family:'Arial';font-size:8;text-anchor:middle\" onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','url(#survol)','','')\" onclick=\"ouv_appli(".$c.",".$mn[$c]['idapplication'].",".$mn[$c]['type_appli'].",'".$mn[$c]['url']."')\">".$mn[$c]['libelle_appli']."</text></a>";
$positex=$positex+10;
}
$data.="</g></svg>";
//$data=gzcompress("$data",9);
echo $data;
//ob_end_flush();
?>
