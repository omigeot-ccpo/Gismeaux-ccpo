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
session_start();
$xi=$x;
$orientation='P';
$unit='mm';
$aph=4; //espace avant paragraphe
$alig=5; //espace entre ligne
$acel=2; //espace entre deux ligne dans la meme cellule
$rastx=explode(";",$raster);
	$raster="";
	for($i=0;$i<count($rastx);$i++)
	{
	$ras=explode(".",$rastx[$i]);
	$raster.=$ras[1].";";
	}
	$raster=substr($raster,0,strlen($raster)-1);

$raste=str_replace("_"," ",$raster);
$raste=explode(";",$raste);
$raster=str_replace(";","&layer=",$raster);
define('FPDF_FONTPATH','../fpdf/font/');
require('../fpdf/fpdf.php');
class PDF extends FPDF {
  var $B;
  var $I;
  var $U;
  var $HREF;
  var $angle=0;
var $extgstates;


function SetAlpha($alpha, $bm='Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_out('<</Type /ExtGState');
            foreach ($this->extgstates[$i]['parms'] as $k=>$v)
                $this->_out('/'.$k.' '.$v);
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate)
            $this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_out('>>');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }


function Rotate($angle,$x=-1,$y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}

function _endpage()
{
    if($this->angle!=0)
    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
}

function RotatedText($x,$y,$txt,$angle)
{
    //Rotation du texte autour de son origine
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}

function RotatedImage($file,$x,$y,$w,$h,$angle)
{
    //Rotation de l'image autour du coin sup�rieur gauche
    $this->Rotate($angle,$x,$y);
    $this->Image($file,$x,$y,$w,$h);
    $this->Rotate(0);
}

function RotatedCell($angle,$x,$y,$w,$h,$txt,$bor,$ln,$aling)
{
    //Rotation de la celulle autour du coin sup�rieur gauche
    $this->Rotate($angle,$x,$y);
	$this->Cell($w,$h,$txt,$bor,$ln,$aling); 
    $this->Rotate(0);
}

  function PutLink($URL,$txt){
      //Place un hyperlien
      $this->SetTextColor(0,0,255);
      $this->SetStyle('U',true);
      $this->Write(3,$txt,$URL);
      $this->SetStyle('U',false);
      $this->SetTextColor(0);
  }

  function SetStyle($tag,$enable)
  {
      //Modifie le style et s�lectionne la police correspondante
      $this->$tag+=($enable ? 1 : -1);
      $style='';
      $this->SetFont('times','',11);
      foreach(array('7','8','9','10','11','12','13','14','24') as $p)
          if($this->$p>0)
              $police.=$p;
      foreach(array('B','I','U') as $s)
          if($this->$s>0)
              $style.=$s;
      $this->SetFont('',$style,$police);

  }
}

include("../connexion/deb.php");
$sql="select a.nom,a.logo,a.larg_logo,b.logo as logo_communaute,b.larg_logo as larg_communaute from admin_svg.commune as a join admin_svg.commune as b on  a.idagglo=b.idcommune where a.idcommune='".$_SESSION['code_insee']."'";
	$retour=tab_result($pgx,$sql);
	if($retour[0]['larg_logo']!="")
	{
$larglogo=($retour[0]['larg_logo']/2.5);
$logo=$retour[0]['logo'];
}
else
{
$larglogo=($retour[0]['larg_communaute']/2.5);
$logo=$retour[0]['logo_communaute'];
}
$xm=$x + $xini;
$xma=($x+$lar) + $xini;
$yma= $yini - $y;
$ym= $yini - ($y+$hau);
$mapsize="";

$tableau=$_SESSION['cotation'];
if(is_array($tableau))
{
		for($ij=0;$ij<count($tableau);$ij++)
{
$coor=explode("|",$tableau[$ij]);
$x1=$xini+$coor[0];
$y1=$yini-$coor[1];
$x2=$xini+$coor[2];
$y2=$yini-$coor[3];

$lo=2;

$angl="";
if($x1<=$x2 && $y2<=$y1)
{
$cosfl=($x2-$x1)/$coor[7];
$anglfl=rad2deg(acos($cosfl));
$dx=$x1+($lo*cos(deg2rad($anglfl+30)));
$dy=$y1-($lo*sin(deg2rad($anglfl+30)));
$dx1=$x1+($lo*cos(deg2rad($anglfl-30)));
$dy1=$y1-($lo*sin(deg2rad($anglfl-30)));
$dx2=$x2+($lo*cos(deg2rad($anglfl+210)));
$dy2=$y2-($lo*sin(deg2rad($anglfl+210)));
$dx3=$x2+($lo*cos(deg2rad($anglfl+150)));
$dy3=$y2-($lo*sin(deg2rad($anglfl+150)));

}
else if($x1<=$x2 && $y2>=$y1)
{
$cosfl=($x2-$x1)/$coor[7];
$anglfl=rad2deg(acos($cosfl));
$dx=$x1+($lo*cos(deg2rad($anglfl+30)));
$dy=$y1+($lo*sin(deg2rad($anglfl+30)));
$dx1=$x1+($lo*cos(deg2rad($anglfl-30)));
$dy1=$y1+($lo*sin(deg2rad($anglfl-30)));
$dx2=$x2+($lo*cos(deg2rad($anglfl+210)));
$dy2=$y2+($lo*sin(deg2rad($anglfl+210)));
$dx3=$x2+($lo*cos(deg2rad($anglfl+150)));
$dy3=$y2+($lo*sin(deg2rad($anglfl+150)));

}
else if($x1>=$x2 && $y2<=$y1)
{
$cosfl=($x2-$x1)/$coor[7];
$anglfl=rad2deg(acos($cosfl));
$dx=$x1+($lo*cos(deg2rad($anglfl+30)));
$dy=$y1-($lo*sin(deg2rad($anglfl+30)));
$dx1=$x1+($lo*cos(deg2rad($anglfl-30)));
$dy1=$y1-($lo*sin(deg2rad($anglfl-30)));
$dx2=$x2+($lo*cos(deg2rad($anglfl+210)));
$dy2=$y2-($lo*sin(deg2rad($anglfl+210)));
$dx3=$x2+($lo*cos(deg2rad($anglfl+150)));
$dy3=$y2-($lo*sin(deg2rad($anglfl+150)));

}
else
{
$cosfl=($x2-$x1)/$coor[7];
$anglfl=rad2deg(acos($cosfl));
$dx=$x1+($lo*cos(deg2rad($anglfl+30)));
$dy=$y1+($lo*sin(deg2rad($anglfl+30)));
$dx1=$x1+($lo*cos(deg2rad($anglfl-30)));
$dy1=$y1+($lo*sin(deg2rad($anglfl-30)));
$dx2=$x2+($lo*cos(deg2rad($anglfl+210)));
$dy2=$y2+($lo*sin(deg2rad($anglfl+210)));
$dx3=$x2+($lo*cos(deg2rad($anglfl+150)));
$dy3=$y2+($lo*sin(deg2rad($anglfl+150)));

}



$polygo=$x1." ".$y1.",".$x2." ".$y2;
$sql="insert into admin_svg.temp_cotation (the_geom,valeur,session_temp,type) values(GeometryFromtext('MULTILINESTRING((".$polygo."))',-1),'".$coor[7]."','".session_id()."','line')";
pg_exec($pgx,$sql);
$sql="insert into admin_svg.temp_cotation (the_geom,session_temp,type) values(GeometryFromtext('MULTILINESTRING((".$x1." ".$y1.",".$dx." ".$dy."),(".$x1." ".$y1.",".$dx1." ".$dy1."),(".$x2." ".$y2.",".$dx2." ".$dy2."),(".$x2." ".$y2.",".$dx3." ".$dy3."))',-1),'".session_id()."','fleche')";
pg_exec($pgx,$sql);
	}
}
if($legende==1 || $legende==2)
{
	$vision=pay;
	$angle=90;
	if($format=='A4')
	{
	$px=20;
	$py=280;
	$rect=3;
	$posiximage=15;
	$posiyimage=233;
	$posixrosa=195;
	$posiyrosa=233;
	$sizerosa=40;
	$posixlogo=8;
	$posiylogo=280;
	$larlogo=$larglogo;
	$haulogo=11;
	$posixechelle=203;
	$posiyechelle=220;
	$sizeechelle=12;
	$xtitre=144;
	$ytitre=149;
	$wtitre=265;
	$htitre=10;
	$sizetitre=20;
	$larimage=220;
	$hauimage=184.5;
	$mapsize='&mapsize=1240%201040';
	$ratioechelle=2;
	}
	elseif($format=='A3')
	{
	$posiximage=15*(29.7/21);
	$posiyimage=233*(29.7/21);
	$larimage=220*(29.7/21);
	$hauimage=184.5*(29.7/21);
	$px=20*(29.7/21);
	$py=280*(29.7/21);
	$rect=3;
	$posixrosa=195*(29.7/21);
	$posiyrosa=233*(29.7/21);
	$sizerosa=50;
	$posixlogo=8*(29.7/21);
	$posiylogo=280*(29.7/21);
	$larlogo=$larglogo*(29.7/21);
	$haulogo=11*(29.7/21);
	$posixechelle=203*(29.7/21);
	$posiyechelle=220*(29.7/21);
	$sizeechelle=14;
	$xtitre=144*(29.7/21);
	$ytitre=149*(29.7/21);
	$wtitre=265*(29.7/21);
	$htitre=10*(29.7/21);
	$sizetitre=25;
	$mapsize='&mapsize=1753.7%201470.9';
	$ratioechelle=2;
	}
	elseif($format=='A2')
	{
	$posiximage=15*2;
	$posiyimage=233*2;
	$larimage=220*2;
	$hauimage=184.5*2;
	$px=20*2;
	$py=280*2;
	$rect=3;
	$posixrosa=195*2;
	$posiyrosa=233*2;
	$sizerosa=55;
	$posixlogo=8*2;
	$posiylogo=280*2;
	$larlogo=$larglogo*2;
	$haulogo=11*2;
	$posixechelle=203*2;
	$posiyechelle=220*2;
	$sizeechelle=18;
	$xtitre=144*2;
	$ytitre=149*2;
	$wtitre=265*2;
	$htitre=10*2;
	$sizetitre=30;
	$ratioechelle=4;
	}
	elseif($format=='A1')
	{
	$posiximage=15*2*(29.7/21);
	$posiyimage=233*2*(29.7/21);
	$larimage=220*2*(29.7/21);
	$hauimage=184.5*2*(29.7/21);
	$px=20*2*(29.7/21);
	$py=280*2*(29.7/21);
	$rect=3;
	$posixrosa=195*2*(29.7/21);
	$posiyrosa=233*2*(29.7/21);
	$sizerosa=60;
	$posixlogo=8*2*(29.7/21);
	$posiylogo=280*2*(29.7/21);
	$larlogo=$larglogo*2*(29.7/21);
	$haulogo=11*2*(29.7/21);
	$posixechelle=203*2*(29.7/21);
	$posiyechelle=220*2*(29.7/21);
	$sizeechelle=22;
	$xtitre=144*2*(29.7/21);
	$ytitre=149*2*(29.7/21);
	$wtitre=265*2*(29.7/21);
	$htitre=10*2*(29.7/21);
	$sizetitre=35;
	
	$ratioechelle=2*(29.7/21);
	}
	else
	{
	$posiximage=15*4;
	$posiyimage=233*4;
	$larimage=220*4;
	$hauimage=184.5*4;
	$px=20*4;
	$py=280*4;
	$rect=3;
	$posixrosa=195*4;
	$posiyrosa=233*4;
	$sizerosa=70;
	$posixlogo=8*4;
	$posiylogo=280*4;
	$larlogo=$larglogo*4;
	$haulogo=11*4;
	$posixechelle=203*4;
	$posiyechelle=220*4;
	$sizeechelle=25;
	$xtitre=144*4;
	$ytitre=149*4;
	$wtitre=265*4;
	$htitre=10*4;
	$sizetitre=40;
	$ratioechelle=2;
	}
}
else
{
	$vision=por;
	$angle=0;
	if($format=='A4')
	{
	$px=20;
	$py=200;
	$rect=3;
	$sizerosa=40;
	$sizeechelle=12;
	$sizetitre=20;
	$posiximage=17.5;
	$posiyimage=30;
	$posixrosa=178;
	$posiyrosa=172;
	$posixlogo=5;
	$posiylogo=10;
	$larlogo=$larglogo;
	$haulogo=11;
	$posixechelle=25;
	$posiyechelle=185;
	$xtitre=0;
	$ytitre=0;
	$wtitre=180;
	$htitre=10;
	$larimage=175;
	$hauimage=146.77;
	$mapsize='&mapsize=1240%201040';
	$ratioechelle=2/0.8;
	}
	elseif($format=='A3')
	{
	$px=20*(29.7/21);
	$py=200*(29.7/21);
	$rect=3;
	$sizerosa=50;
	$sizeechelle=14;
	$sizetitre=25;
	$posixrosa=178*(29.7/21);
	$posiyrosa=172*(29.7/21);
	$posixlogo=5*(29.7/21);
	$posiylogo=10*(29.7/21);
	$larlogo=$larglogo*(29.7/21);
	$haulogo=11*(29.7/21);
	$posixechelle=25*(29.7/21);
	$posiyechelle=185*(29.7/21);
	$xtitre=0;
	$ytitre=0;
	$wtitre=180*(29.7/21);
	$htitre=10*(29.7/21);
	$posiximage=17.5*(29.7/21);
	$posiximage=17.5*(29.7/21);
	$posiyimage=30*(29.7/21);
	$larimage=247.5;
	$hauimage=207.57;
	$mapsize='&mapsize=1753.7%201470.9';
	$ratioechelle=2*1.19;
	}
	
}

$ech="&mapxy=".($xm+($xma-$xm)/2).'%20'.($ym+($yma-$ym)/2)."&SCALE=".$echelle/$ratioechelle;
//$raster=str_replace(";","&layer=",$raster);
	$erreur=error_reporting ();
	error_reporting (1);
		$serv=$_SERVER["SERVER_NAME"];
if(substr($_SESSION['code_insee'], -3)=='000')
	{
	$code_insee=substr($_SESSION['code_insee'],0,3);
	}
	else
	{
	$code_insee=$_SESSION['code_insee'];
	}
$sql_app="select supp_chr_spec(libelle_appli) as libelle_appli from admin_svg.application where idapplication=".$_SESSION['appli'];
$app=tab_result($pgx,$sql_app);
$application=$app[0]['libelle_appli'];
//$application=str_replace(" ","_",$application);
$url='http://'.$serv.'/cgi-bin/mapserv?map=/home/sig/intranet/capm/'.$application.'.map&map_imagetype=jpeg&insee='.$code_insee.'&sess='.session_id().'&parce='.stripslashes($parce).'&layer=cotation&layer='.$raster.$ech.$mapsize;
		$contenu=file($url);
       		while (list($ligne,$cont)=each($contenu)){
			$numligne[$ligne]=$cont;
		}
		$texte=$numligne[1];
		$text = explode("/", $texte);
		$tex=explode(".",$text[4]);
		
	
	
$sql="delete from admin_svg.temp_cotation where session_temp='".session_id()."'";
pg_exec($pgx,$sql);
		
//variable
//creation du fichier pdf
  $pdf=new PDF();
   $pdf->Open();
 $pdf->FPDF($orientation,$unit,$format);
//cr�ation page
$pdf->AddFont('font1','','font1.php');
  $pdf->AddPage();
  
$pdf->RotatedImage('../tmp/'.$tex[0].'.jpg',$posiximage,$posiyimage,$larimage,$hauimage,$angle);
$pdf->SetFont('font1','',$sizerosa);
$pdf->RotatedText($posixrosa,$posiyrosa,'a',$angle);
//$pdf->SetFont('arial','',12);
//$pdf->SetAlpha(0.5);
//$pdf->RotatedImage('rosa.jpg',$posixrosa,$posiyrosa,15,15,$angle);
//$pdf->SetAlpha(1);
$pdf->RotatedImage($logo,$posixlogo,$posiylogo,$larlogo,$haulogo,$angle);
$pdf->SetFont('arial','',$sizetitre);
$pdf->RotatedCell($angle,$xtitre,$ytitre,$wtitre,$htitre,$titre,0,0,'C'); 
$pdf->SetFont('arial','',$sizeechelle);
if($echelle==0)
{
$pdf->RotatedText($posixechelle,$posiyechelle,'Sans �chelle',$angle);
}
else
{
$pdf->RotatedText($posixechelle,$posiyechelle,'1/'.$echelle.' �me',$angle);
}
$pdf->SetDrawColor(0);
$pdf->SetFont('arial','',10);
if($legende==1 || $legende==3)
{
$legende="";

//$pyy=250;
$idprov="";
for($i=0;$i<count($rastx);$i++)
{
	$ra=explode(".",$rastx[$i]);
	$id=$ra[0];
	$lib=str_replace("_"," ",$ra[1]);;
	if($id!="")
	{
$req1="select col_theme.intitule_legende as intitule_legende,theme.libelle_them,col_theme.fill,col_theme.symbole,col_theme.opacity::real,col_theme.ordre,col_theme.stroke_rgb,style.fill as style_fill,style.symbole as style_symbole,style.opacity::real  as style_opacity,style.font_size  as style_fontsize,style.stroke_rgb  as style_stroke from admin_svg.appthe left outer join admin_svg.col_theme on appthe.idappthe=col_theme.idappthe join admin_svg.theme on appthe.idtheme=theme.idtheme left outer join admin_svg.style on appthe.idtheme=style.idtheme where appthe.idappthe=".$id."  and (intitule_legende='".$lib."' or libelle_them='".$lib."')";

	$couch=tab_result($pgx,$req1);
		
			if($couch[0]['intitule_legende']=="")
			{
			$legend=$couch[0]['libelle_them'];
			if($couch[0]['style_fill']!="" && $couch[0]['style_fill']!="none")
			{
			$couleu=$couch[0]['style_fill'];
			}
			else
			{
			$couleu=$couch[0]['style_stroke'];
			}
			if($couch[0]['style_opacity']!=0 && $couch[0]['style_opacity']!="")
			{
			$apocit=$couch[0]['style_opacity'];
			}
			else
			{
			$apocit=1;
			}
			}
			else
			{
			$legend=$couch[0]['intitule_legende'];
			//$couleu=$couch[0]['fill'];
			if($couch[0]['fill']!="" && $couch[0]['fill']!="none")
			{
			$couleu=$couch[0]['fill'];
			}
			else
			{
			$couleu=$couch[0]['stroke_rgb'];
			}
			if($couch[0]['opacity']!=0)
			{
			$apocit=$couch[0]['opacity'];
			}
			else
			{
			$apocit=1;
			}
			}
			
			if(in_array($legend,$raste) and $couleu!="")
			{
			if($idprov!=$id && $idprov!="")
			{
			if($vision=="pay")
			{	
			$px=$px+2;
			}
			else
			{
			$py=$py+2;
			}
			}
			if($couch[0]['intitule_legende']!="" && $couch[0]['libelle_them']!="" && $idprov!=$id)
			{
			if($vision=="pay")
			{	
			$px=$px+3;
			$pdf->RotatedText($px,$py+3,$couch[0]['libelle_them'],$angle);
			}
			else
			{
			$py=$py+3;
			$pdf->RotatedText($px,$py,$couch[0]['libelle_them'],$angle);
			}
			
			$idprov=$id;
			if($vision=="pay")
			{
			$px=$px+2;
			}
			else
			{
			$py=$py+2;
			}
			}
			
			$coul = explode(",", $couleu);
			$pdf->SetAlpha($apocit);
			$pdf->SetFillColor($coul[0],$coul[1],$coul[2]);
			$pdf->Rect($px,$py,$rect,$rect,F);
			$pdf->SetAlpha(1);
			$pdf->Rect($px,$py,$rect,$rect,D);
			//$pdf->Text(8,$pyy,$legend);
			if($vision=="pay")
			{
			$pdf->RotatedText($px+3,$py-2,$legend,$angle);
			}
			else
			{
			$pdf->RotatedText($px+5,$py+3,$legend,$angle);
			}
			//$pyy=$pyy+3;
			if($vision=="pay")
			{
			$px=$px+5;
			}
			else
			{
			$py=$py+5;
			if($format=='A4' && $py>280)
			{
			$py=200;
			$px=$px+50;
			}
			if($format=='A3' && $py>395)
			{
			$py=200*(29.7/21);
			$px=$px+50;
			}
			}
			
			
			}
		
	}
}
}
$pdf->Output();
?> 
