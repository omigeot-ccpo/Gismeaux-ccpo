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
include("../connexion/deb.php");
function supp_acc($texte)
{
$nom=str_replace("à","a",$texte);
$nom=str_replace("é","e",$nom);
$nom=str_replace("è","e",$nom);
$nom=str_replace("ê","e",$nom);
$nom=str_replace("â","a",$nom);
$nom=str_replace("ç","c",$nom);
$nom=str_replace("ô","o",$nom);
$nom=str_replace("î","i",$nom);
$nom=str_replace("û","u",$nom);
$nom=str_replace(" ","_",$nom);
return $nom;
}
$libappli="select application.libelle_appli from admin_svg.application where idapplication='".$appli."'";
$lib_appli=tab_result($pgx,$libappli);

$mapcotation="LAYER \n";
$mapcotation.="CONNECTIONTYPE postgis \n";
$mapcotation.="NAME \"cotation\" group \"cotation\" \n";
$mapcotation.="CONNECTION \"user=postgres dbname=meaux\" \n";
$mapcotation.="DATA \"the_geom FROM admin_svg.temp_cotation using unique the_geom using SRID=-1\" \n";
$mapcotation.="STATUS on \n";
$mapcotation.="TYPE line \n";
$mapcotation.="FILTER \"session_temp like '%sess%'\" \n";
$mapcotation.="LABELITEM valeur \n";
$mapcotation.="CLASS \n";
$mapcotation.="EXPRESSION ('[type]' eq 'line') \n";
$mapcotation.="LABEL \n";
$mapcotation.="COLOR 255 0 0 \n";
$mapcotation.="ANGLE auto \n";
$mapcotation.="TYPE truetype \n";
$mapcotation.="ANTIALIAS true \n";
$mapcotation.="FONT \"arial\" \n";
$mapcotation.="POSITION uc \n";
$mapcotation.="MINSIZE 10 \n";
$mapcotation.="MAXSIZE 15 \n";
$mapcotation.="END \n";
$mapcotation.="OUTLINECOLOR  0 0 255 \n";
$mapcotation.="END \n";
$mapcotation.="CLASS \n";
$mapcotation.="EXPRESSION ('[type]' eq 'fleche') \n";
$mapcotation.="OUTLINECOLOR 255 0 0 \n";
$mapcotation.="END \n";
$mapcotation.="END \n";


$map="MAP \n";
$map.="NAME ".supp_acc($lib_appli[0]['libelle_appli'])." \n";
$map.="STATUS on \n";
$map.="SIZE 3100 2600 \n";
$map.="UNITS meters \n";
$map.="IMAGECOLOR 255 255 255 \n";
$map.="FONTSET\"fonts/fontset.txt\" \n";
$map.="IMAGETYPE jpeg \n";
$map.="OUTPUTFORMAT \n";
$map.="NAME jpeg \n";
$map.="DRIVER \"GD/JPEG\" \n";
$map.="MIMETYPE \"image/jpeg\" \n";
$map.="IMAGEMODE RGB \n";
$map.=" EXTENSION \"jpg\" \n";
$map.="END \n";
$map.="WEB \n";
	$map.="TEMPLATE capm_svg.php \n";
	$map.="IMAGEPATH \"/home/sig/intranet/tmp/\" \n";
	$map.="IMAGEURL \"/tmp/\" \n";
$map.="END \n";

$map.="#BIBLIO_SYMBOLE# \n";
$map.="SYMBOL \n";
$map.="NAME 'circle' \n";
$map.="TYPE ELLIPSE \n";
$map.="POINTS 1 1 ";
$map.="END \n";
$map.="FILLED false \n";
$map.="END \n";
$map.="SYMBOL \n";
$map.="NAME 'agrille' \n";
$map.="TYPE vector \n";
$map.="POINTS \n";
$map.="#rect \n";
$map.="0.1 0 \n";
$map.="0.1 0.65 \n";
$map.="0.9 0.65 \n";
$map.="0.9 0 \n";
$map.="0.1 0 \n";
$map.="-99 -99 \n";
$map.="#cercle \n";
$map.="0.5 0.05 \n";
$map.="0.4 0.075 \n";
$map.="0.35 0.125 \n";
$map.="0.325 0.225 \n";
$map.="0.35 0.325 \n";
$map.="0.4 0.375 \n";
$map.="0.5 0.4 \n";
$map.="0.6 0.375 \n";
$map.="0.625 0.325 \n";
$map.="0.675 0.225 \n";
$map.="0.6 0.075 \n";
$map.="0.5 0.05 \n";
$map.="-99 -99 \n";
$map.="#demi cercle \n";
$map.="0.1 0.65 \n";
$map.="0.25 0.55 \n";
$map.="0.5 0.5 \n";
$map.="0.75 0.55 \n";
$map.="0.9 0.65 \n";
$map.="-99 -99 \n";
$map.="#rect grille \n";
$map.="0.3 0.65 \n";
$map.="0.3 1 \n";
$map.="0.7 1 \n";
$map.="0.7 0.65 \n";
$map.="-99 -99 \n";
$map.="#ligne grille \n";
$map.="0.325 0.725 \n";
$map.="0.675 0.725 \n";
$map.="-99 -99 \n";
$map.="0.325 0.825 \n";
$map.="0.675 0.825 \n";
$map.="-99 -99 \n";
$map.="0.325 0.925 \n";
$map.="0.675 0.925 \n";
$map.="-99 -99 \n";
$map.="END \n";
$map.="FILLed false \n";
$map.="end \n";
$map.="SYMBOL \n";
$map.="NAME 'avaloir' \n";
$map.="TYPE vector \n";
$map.="POINTS \n";
$map.="0 0 \n";
$map.="0 1 \n";
$map.="1 1 \n";
$map.="1 0 \n";
$map.="0 0 \n";
$map.="-99 -99 \n";
$map.="0 1  \n";
$map.="0.25 0.87 \n";
$map.="0.5 0.80 \n";
$map.="0.75 0.87 \n";
$map.="1 1 \n";
$map.="-99 -99 \n";

$map.="0.5 0.1 \n";
$map.="0.35 0.15 \n";
$map.="0.28 0.25 \n";
$map.="0.25 0.35 \n";
$map.="0.28 0.45 \n";
$map.="0.35 0.55 \n";
$map.="0.5 0.6 \n";
$map.="0.65 0.55 \n";
$map.="0.72 0.45 \n";
$map.="0.75 0.35 \n";
$map.="0.72 0.25 \n";
$map.="0.65 0.15 \n";
$map.="0.5 0.1 \n";
$map.="END \n";
$map.="FILLED false \n";
$map.="END \n";
$map.="SYMBOL \n";
$map.="NAME 'carre' \n";
$map.="TYPE vector \n";
$map.="POINTS \n";
$map.="0 0 \n";
$map.="0 1 \n";
$map.="1 1 \n";
$map.="1 0 \n";
$map.="0 0 \n";
$map.="END \n";
$map.="FILLed false \n";
$map.="end \n";
$map.="SYMBOL \n";
$map.="NAME 'croix' \n";
$map.="TYPE vector \n";
$map.="POINTS \n";
$map.="0 0 \n";
$map.="1 1 \n";
$map.="-99 -99 \n";
$map.="0 1 \n";
$map.="1 0 \n";
$map.="END \n";
$map.="end \n";
$map.="SYMBOL \n";
$map.="NAME 'grille' \n";
$map.="TYPE vector \n";
$map.="POINTS \n";
$map.="0 0 \n";
$map.="0 1 \n";
$map.="1 1 \n";
$map.="1 0 \n";
$map.="0 0 \n";
$map.="-99 -99  \n";
$map.="0 0.75 \n";
$map.="1 0.75 \n";
$map.="-99 -99 \n";
$map.="0 0.5 \n";
$map.="1 0.5 \n";
$map.="-99 -99 \n";
$map.="0 0.25 \n";
$map.="1 0.25 \n";
$map.="END \n";
$map.="end  \n";
$map.="SYMBOL \n";
$map.="NAME 'regard' \n";
$map.="TYPE ELLIPSE \n";
$map.="POINTS 1 1 END \n";
$map.="FILLED TRUE \n";
$map.="END \n";

$req2="select appthe.idtheme,theme.libelle_them as nom_theme,theme.schema,theme.tabl,appthe.idappthe,col_theme.colonn,admin_svg.v_fixe(col_theme.valeur_texte),theme.raster as raster,style.fill as style_fill,style.symbole as style_symbole,style.opacity  as style_opacity,style.font_size  as style_fontsize,style.stroke_rgb  as style_stroke,appthe.objprincipal,appthe.objrecherche from admin_svg.appthe join admin_svg.theme on appthe.idtheme=theme.idtheme join admin_svg.application on appthe.idapplication=application.idapplication left outer join  admin_svg.col_theme on appthe.idappthe=col_theme.idappthe left outer join admin_svg.style on appthe.idtheme=style.idtheme where appthe.idapplication=".$appli." group by appthe.idtheme,theme.libelle_them,appthe.ordre,theme.schema,theme.tabl,col_theme.colonn,admin_svg.v_fixe(col_theme.valeur_texte),theme.raster,style.fill,style.symbole,style.opacity,style.font_size,style.stroke_rgb,appthe.idappthe,appthe.objprincipal,appthe.objrecherche order by appthe.ordre desc";

$cou=tab_result($pgx,$req2);
$mapprincipal="";
$maprecherche="";
$clause="";
for ($c=0;$c<count($cou);$c++)
{
$clause="";


$d="select appel from admin_svg.col_sel where idtheme='".$cou[$c]['idtheme']."' and nom_as='geom'";
		$col=tab_result($pgx,$d);
$dd="select appel from admin_svg.col_sel where idtheme='".$cou[$c]['idtheme']."' and nom_as='ad'";
$co=tab_result($pgx,$dd);
$dd1="select appel from admin_svg.col_sel where idtheme='".$cou[$c]['idtheme']."' and nom_as='ident'";
$co1=tab_result($pgx,$dd1);
if(count($col)>0)
{
$dd="select distinct geometrytype(".$col[0]['appel'].") as geome from ".$cou[$c]['schema'].".".$cou[$c]['tabl'];
		$geo=tab_result($pgx,$dd);
}
$j="select * from admin_svg.col_where where idtheme='".$cou[$c]['idtheme']."'";
		$whr=tab_result($pgx,$j);
		if (count($whr)>0)
		{
			$clause=" ".str_replace("VALEUR","'%insee%%'",$whr[0]['clause']);
		}
			
if($geo[0]['geome']=="MULTIPOINT" || $geo[0]['geome']=="POINT" )
{
$mapp="mappoint";
$type="point";
}
elseif($geo[0]['geome']=="MULTILINESTRING" || $geo[0]['geome']=="LINESTRING")
{
$mapp="mapline";
$type="line";
}
else
{
$type="polygon";
$mapp="mappolygone";
}

$req1="select distinct (col_theme.intitule_legende) as intitule_legende,col_theme.valeur_texte,col_theme.fill,col_theme.stroke_rgb,col_theme.symbole,col_theme.font_size,col_theme.font_familly,col_theme.opacity,col_theme.ordre from admin_svg.appthe join admin_svg.col_theme on appthe.idappthe=col_theme.idappthe join admin_svg.theme on appthe.idtheme=theme.idtheme where appthe.idapplication=".$appli." and appthe.idtheme='".$cou[$c]['idtheme']."'";

	$couch=tab_result($pgx,$req1);
	
	if(count($couch)>0)
		{
			for ($r=0;$r<count($couch);$r++)
			{
			if($cou[$c]['objrecherche']=="t")
			{
			$maprecherche.="LAYER \n";
			$maprecherche.="CONNECTIONTYPE postgis \n";
			$maprecherche.="NAME \"".str_replace(" ","_",$couch[$r]['intitule_legende'])."\" group \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" \n";
			$maprecherche.="CONNECTION \"user=postgres dbname=meaux\" \n";
			if($clause=="")
			{
			$maprecherche.="DATA \"".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." using unique ".$col[0]['appel']." using SRID=-1\" \n";
			}
			else
			{
			$maprecherche.="DATA \"".$col[0]['appel']." from (select ".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." where ".$clause." and ".$cou[$c]['tabl'].".code_insee like '%insee%%') as neo using unique ".$col[0]['appel']." using SRID=-1\" \n";
			}
			$maprecherche.="STATUS on \n";
			$maprecherche.="TYPE ".$type." \n";
			if($clause=="")
			{
    		$maprecherche.="FILTER \"".$co1[0]['appel']." in %parce%\" \n";
			}
			$maprecherche.="LABELITEM ".$co1[0]['appel']." \n";
			$maprecherche.="CLASS \n";
			$maprecherche.="COLOR 150 254 150 \n";
			$maprecherche.="LABEL \n";
			$maprecherche.="ANTIALIAS true \n";
			$maprecherche.="FONT \"arial\" \n";
			$maprecherche.="POSITION cc \n";
			$maprecherche.="size 10 \n";
			$maprecherche.="END \n";
			$maprecherche.="END \n";
			$maprecherche.="TRANSPARENCY 70 \n";
			$maprecherche.="END \n \n";
			}
			
			
			if($cou[$c]['objprincipal']=="t")
			{
			
			$mapprincipal.="LAYER \n";
	$mapprincipal.="CONNECTIONTYPE postgis \n";
	$mapprincipal.="NAME \"".str_replace(" ","_",$couch[$r]['intitule_legende'])."\" group \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" \n";
	$mapprincipal.="CONNECTION \"user=postgres dbname=meaux\" \n";
	if($clause=="")
	{
	$mapprincipal.="DATA \"".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." using unique ".$col[0]['appel']." using SRID=-1\" \n";
	}
	else
	{
	$mapprincipal.="DATA \"".$col[0]['appel']." from (select ".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." where ".$clause." and ".$cou[$c]['tabl'].".code_insee like '%insee%%') as neo using unique ".$col[0]['appel']." using SRID=-1\" \n";
	}
	$mapprincipal.="STATUS on \n";
	$mapprincipal.="TYPE ".$type." \n";
    if($clause=="" && $cou[$c]['schema']!="bd_topo")
	{
    $mapprincipal.="FILTER \"code_insee like '%insee%%' or code_insee is null \" \n";
	}
	$mapprincipal.="CLASS \n";
		$mapprincipal.="EXPRESSION ('[".$cou[$c]['colonn']."]' eq '".$couch[$r]['valeur_texte']."') \n";
		$mapprincipal.="OUTLINECOLOR ".str_replace(","," ",$couch[$r]['stroke_rgb'])." \n";
		$mapprincipal.="END \n";
	$mapprincipal.="END \n \n";
	
			}
			$$mapp.="LAYER \n";
	$$mapp.="CONNECTIONTYPE postgis \n";
	$$mapp.="NAME \"".str_replace(" ","_",$couch[$r]['intitule_legende'])."\" group \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" \n";
	$$mapp.="CONNECTION \"user=postgres dbname=meaux\" \n";
	if($clause=="")
	{
	$$mapp.="DATA \"".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." using unique ".$col[0]['appel']." using SRID=-1\" \n";
	}
	else
	{
	$$mapp.="DATA \"".$col[0]['appel']." from (select ".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." where ".$clause." and ".$cou[$c]['tabl'].".code_insee like '%insee%%') as neo using unique ".$col[0]['appel']." using SRID=-1\" \n";
	}
	$$mapp.="STATUS on \n";
	if($type=="point")
	{
	$$mapp.="TYPE annotation \n";
	if($clause=="" && $cou[$c]['schema']!="bd_topo")
	{
    $$mapp.="FILTER \"code_insee like '%insee%%' or code_insee is null \" \n";
	}
	$$mapp.="CLASS \n";
		if($couch[$r]['symbole'] !="")
		{
		$$mapp.="text ('".$couch[$r]['symbole']."') \n";
		}
		else
		{
		$$mapp.="text ('".$co[0]['appel']."') \n";
		}
		$$mapp.="label \n";
		$$mapp.="angle auto \n";
		$$mapp.="type truetype \n";
		if($couch[$r]['symbole'] !="")
		{
		$$mapp.="font \"svg\" \n";
		}
		else
		{
		$$mapp.="font \"arial\" \n";
		}
		$$mapp.="position cc \n";
		$$mapp.="size ".$couch[$r]['font_size']." \n";
		$$mapp.="COLOR ".str_replace(","," ",$couch[$r]['fill'])." \n";
		$$mapp.="END \n";
				
	$$mapp.="END \n";
	$$mapp.="END \n \n";
	}
	
	else
	{
	$$mapp.="TYPE ".$type." \n";
    if($clause=="" && $cou[$c]['schema']!="bd_topo")
	{
    $$mapp.="FILTER \"code_insee like '%insee%%' or code_insee is null \" \n";
	}
	$$mapp.="CLASS \n";
		$$mapp.="EXPRESSION ('[".$cou[$c]['colonn']."]' eq '".$couch[$r]['valeur_texte']."') \n";
		if($couch[$r]['fill']!='' && $couch[$r]['fill']!='none' )
		{
		$$mapp.="COLOR ".str_replace(","," ",$couch[$r]['fill'])." \n";
		}
		if($couch[$r]['stroke_rgb']!='' && $couch[$r]['stroke_rgb']!='none' )
		{
		$$mapp.="OUTLINECOLOR ".str_replace(","," ",$couch[$r]['stroke_rgb'])." \n";
		}
				
	$$mapp.="END \n";
	if($couch[$r]['opacity']!='' && $couch[$r]['opactity']!='none' )
		{
		$$mapp.="TRANSPARENCY ".($couch[$r]['opactity']*100)." \n";
		}
$$mapp.="END \n \n";
			}
		}
		}
	elseif($cou[$c]['raster']=='')
	{
		
		if($cou[$c]['objrecherche']=="t")
			{
			$maprecherche.="LAYER \n";
			$maprecherche.="CONNECTIONTYPE postgis \n";
			$maprecherche.="NAME \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" group \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" \n";
			$maprecherche.="CONNECTION \"user=postgres dbname=meaux\" \n";
			if($clause=="")
			{
			$maprecherche.="DATA \"".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." using unique ".$col[0]['appel']." using SRID=-1\" \n";
			}
			else
			{
			$maprecherche.="DATA \"".$col[0]['appel']." from (select ".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." where ".$clause." and ".$cou[$c]['tabl'].".code_insee like '%insee%%') as neo using unique ".$col[0]['appel']." using SRID=-1\" \n";
			}
			$maprecherche.="STATUS on \n";
			$maprecherche.="TYPE ".$type." \n";
			if($clause=="")
			{
    		$maprecherche.="FILTER \"".$co1[0]['appel']." in %parce%\" \n";
			}
			$maprecherche.="LABELITEM ".$co1[0]['appel']." \n";
			$maprecherche.="CLASS \n";
			$maprecherche.="COLOR 150 254 150 \n";
			$maprecherche.="LABEL \n";
			$maprecherche.="ANTIALIAS true \n";
			$maprecherche.="FONT \"arial\" \n";
			$maprecherche.="POSITION cc \n";
			$maprecherche.="size 10 \n";
			$maprecherche.="END \n";
			$maprecherche.="END \n";
			$maprecherche.="TRANSPARENCY 70 \n";
			$maprecherche.="END \n \n";
			}
		
		if($cou[$c]['objprincipal']=="t")
			{
			
			$mapprincipal.="LAYER \n";
			$mapprincipal.="CONNECTIONTYPE postgis \n";
			$mapprincipal.="NAME \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" group \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" \n";
			$mapprincipal.="CONNECTION \"user=postgres dbname=meaux\" \n";
			if($clause=="")
			{
			$mapprincipal.="DATA \"".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." using unique ".$col[0]['appel']." using SRID=-1\" \n";
			}
			else
			{
			$mapprincipal.="DATA \"".$col[0]['appel']." from (select ".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." where ".$clause." and ".$cou[$c]['tabl'].".code_insee like '%insee%%') as neo using unique ".$col[0]['appel']." using SRID=-1\" \n";
			}
			$mapprincipal.="STATUS on \n";
			$mapprincipal.="TYPE ".$type." \n";
			if($clause=="" && $cou[$c]['schema']!="bd_topo")
			{
    		$mapprincipal.="FILTER \"code_insee like '%insee%%' or code_insee is null \" \n";
			}
			$mapprincipal.="CLASS \n";
			$mapprincipal.="OUTLINECOLOR ".str_replace(","," ",$cou[$c]['style_stroke'])." \n";
			$mapprincipal.="END \n";
			$mapprincipal.="END \n \n";
			}
		$$mapp.="LAYER \n";
	$$mapp.="CONNECTIONTYPE postgis \n";
	$$mapp.="NAME \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" group \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" \n";
	$$mapp.="CONNECTION \"user=postgres dbname=meaux\" \n";
	if($clause=="")
	{
	$$mapp.="DATA \"".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." using unique ".$col[0]['appel']." using SRID=-1\" \n";
	}
	else
	{
	$$mapp.="DATA \"".$col[0]['appel']." from (select ".$col[0]['appel']." from ".$cou[$c]['schema'].".".$cou[$c]['tabl']." where ".$clause." and ".$cou[$c]['tabl'].".code_insee like '%insee%%') as neo using unique ".$col[0]['appel']." using SRID=-1\" \n";
	}
	$$mapp.="STATUS on \n";
	
	if($cou[$c]['style_symbole'] !="")
	{
	$$mapp.="TYPE annotation \n";
	if($clause=="" && $cou[$c]['schema']!="bd_topo")
	{
    $$mapp.="FILTER \"code_insee like '%insee%%' or code_insee is null \" \n";
	}
	$$mapp.="CLASS \n";
		
		$$mapp.="text ('".$cou[$c]['style_symbole']."') \n";
		$$mapp.="label \n";
		$$mapp.="angle auto \n";
		$$mapp.="type truetype \n";
		$$mapp.="font \"svg\" \n";
		$$mapp.="position cc \n";
		$$mapp.="size ".$cou[$c]['style_fontsize']." \n";
		$$mapp.="COLOR ".str_replace(","," ",$cou[$c]['style_fill'])." \n";
		$$mapp.="END \n";
				
	$$mapp.="END \n";
	$$mapp.="END \n \n";
	}
	elseif($cou[$c]['style_fontsize'] !="" && $cou[$c]['style_symbole'] =="")
	{
	$$mapp.="TYPE annotation \n";
	if($clause=="" && $cou[$c]['schema']!="bd_topo")
	{
    $$mapp.="FILTER \"code_insee like '%insee%%' or code_insee is null \" \n";
	}
	$$mapp.="CLASS \n";
		
		$$mapp.="text ([".str_replace("||","] [",$co[0]['appel'])."]) \n";
		$$mapp.="label \n";
		$$mapp.="angle auto \n";
		$$mapp.="type truetype \n";
		$$mapp.="font \"arial\" \n";
		$$mapp.="position cc \n";
		$$mapp.="size ".$cou[$c]['style_fontsize']." \n";
		$$mapp.="COLOR ".str_replace(","," ",$cou[$c]['style_fill'])." \n";
		$$mapp.="END \n";
				
	$$mapp.="END \n";
	$$mapp.="END \n \n";
	}
	else
	{
	$$mapp.="TYPE ".$type." \n";
	if($clause=="" && $cou[$c]['schema']!="bd_topo")
	{
    $$mapp.="FILTER \"code_insee like '%insee%%' or code_insee is null \" \n";
	}
	$$mapp.="CLASS \n";
		if($cou[$c]['style_fill']!='' && $cou[$c]['style_fill']!='none' )
		{
		$$mapp.="COLOR ".str_replace(","," ",$cou[$c]['style_fill'])." \n";
		}
		if($cou[$c]['style_stroke']!='' && $cou[$c]['style_stroke']!='none' )
		{
		$$mapp.="OUTLINECOLOR ".str_replace(","," ",$cou[$c]['style_stroke'])." \n";
		}
	$$mapp.="END \n";
	if($cou[$c]['style_opacity']!='' && $cou[$c]['style_opactity']!='none' )
		{
		$$mapp.="TRANSPARENCY ".($cou[$c]['style_opacity']*100)." \n";
		}
$$mapp.="END \n \n";
}
	}
	else
	{
	$mapraster="LAYER \n";
	$mapraster.="NAME \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" group \"".str_replace(" ","_",$cou[$c]['nom_theme'])."\" \n";
	$mapraster.="STATUS on \n";
	$mapraster.="TYPE raster \n";
   	$mapraster.="TILEINDEX \"".$cou[$c]['raster']."\" \n";
	$mapraster.="TILEITEM \"location\" \n";
	$mapraster.="END \n \n";
	}

}

$data=$map.$mapraster.$mappolygone.$mapline.$mappoint.$maprecherche.$mapprincipal.$mapcotation."END";

$filename = "../capm/".supp_acc($lib_appli[0]['libelle_appli']).".map";
$myFile = fopen($filename, "w");  
fputs($myFile, $data);
fclose($myFile);
echo "Le fichier MAP de l'application ".$lib_appli[0]['libelle_appli']." a été généré avec success.<br>";
echo "<a href=\"./index.php\" target=\"_parent\">Retour</a>";

?> 
