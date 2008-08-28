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

$insee = $_SESSION['profil']->insee;
$appli = $_SESSION['profil']->appli;

if ((!$_SESSION['profil']->acces_ssl) || !in_array ("Ravalement", $_SESSION['profil']->liste_appli)){
	die("Point d'entrée réglementé.<br> Accès interdit. <br>Veuillez vous connecter via <a href=\"https://".$_SERVER['HTTP_HOST']."\">serveur carto</a><SCRIPT language=javascript>setTimeout(\"window.location.replace('https://".$_SERVER['HTTP_HOST']."')\",5000)</SCRIPT>");
}
$aph=4; //espace avant paragraphe
$alig=5; //espace entre ligne
$acel=2; //espace entre deux ligne dans la meme cellule
define('FPDF_FONTPATH',GIS_ROOT . '/fpdf/font/');
require(GIS_ROOT . '/fpdf/afpdf.php');

$nocoche="deco.png";
$coche="okcoche.png";

$q="SELECT  nom_voie,dnuvoi,dindic,identifian,prop1 FROM cadastre.parcelle1 WHERE identifian in ('";
$wh=""; $q2="";
    $p=explode(",",$_GET['obj_keys']);
	$nb_obj=0;
   // $codeinsee='770'.substr($p[$nb_obj],0,3);
	//pr�paration des requetes utilisant les obj_keys
	while ($p[$nb_obj]) {
		$a1=substr($p[$nb_obj],6,2);
		$b1=str_pad(substr($p[$nb_obj],8,4),4,"0",STR_PAD_LEFT);
		$q2.=substr($p[0],0,6).$a1.$b1."','";
		$wh.="(a.identifian='".substr($p[0],0,6).$a1.$b1."' and Intersects(a.the_geom,b.the_geom)) or ";
		$nb_obj++;
	}

$q2=substr($q2,0,strlen($q2)-3);
$q.=$q2."') order by identifian";
$wh=substr($wh,0,strlen($wh)-4);
$an=number_format(date(Y));


$rq = $DB->tab_result($q);

$requete_propri="select ddenom,dqualp,dnomlp,dprnlp,dlign3,dlign4,dlign5,dlign6 from cadastre.propriet where prop1='".$rq[0]['prop1']."'";
$propri = $DB->tab_result($requete_propri);
/*if (count($rq)==0){header("Location: ./messparcel.php");}
else{*/
	$reqq="SELECT * from urba.ravalement where gid IN(".$_GET['gid'].")";
	$retou=$DB->tab_result($reqq);
    $q1="select nom,tel_urba,horaire_urba,plu_pos,approb,modif,larg_logo from admin_svg.commune where idcommune = '".$insee."'";
     $r1=$DB->tab_result($q1);
      //creation du fichier pdf
     $pdf=new TPDF();
     $pdf->Open();
     //cr�ation page
     $pdf->AddPage();
     //s�lection de la police par d�faut
     $pdf->SetFont('times','',11);
     //fixe la marge gauche des textes qui suivent apr�s un retour � la ligne(<br>)
     $pdf->SetLeftMargin(15);
     //supprime le saut de page
     $pdf->Setautopagebreak(0,0);
     //fixe la marge droite des textes qui suivent
     $pdf->SetrightMargin(5);
     $larg=(70*$r1[0]['larg_logo'])/100;
     $pdf->Image(GIS_ROOT . "/logo/".$_SESSION['code_insee'].".png",10,8,$larg,'20','','');
     //on se positionne � 130mm du bord gauche et � 15 mm du bord haut
     $pdf->Setxy(120,10);
	 $pdf->MultiCell(60,5,$r1[0]['nom'].', le '.date("d").' '.moix(date("m")).' '.date("Y")) ;
     //$pdf->Write(5,$r1[0]['nom'].', le '.date("d").' '.moix(date("m")).' '.date("Y"));
     $pdf->SetFont('','B',13);
     $pdf->SetFont('','',11);
     //cr�ation de l'encart Adresse
     $pdf->Setxy(85,25);
     $pdf->SetFillColor(225);
     $pdf->cell(15,5,'Section',1,1,'C',1);
     $pdf->Setxy(100,25);
     $pdf->SetFillColor(225);
     $pdf->cell(15,5,'Parcelle',1,1,'C',1);
     $pdf->Setxy(115,25);
     $pdf->SetFillColor(225);
     $pdf->cell(85,5,'Adresse Parcellaire',1,1,'C',1);
     for ($i=0; $i<count($rq); $i++){
         $adresse1=number_format($rq[$i][1]).' '.$rq[$i][2].', '.ucwords(strtolower($rq[$i][0]));
         $y=$pdf->GetY();
         $pdf->Setxy(85,$y);
         $pdf->SetFillColor(255);
         $pdf->cell(15,5,strtoupper(substr($rq[$i][3],6,2)),1,1,'C',1);
         $pdf->Setxy(100,$y);
         $pdf->SetFillColor(255);
         $pdf->cell(15,5,substr($rq[$i][3],8,4),1,1,'C',1);
         $pdf->Setxy(115,$y);
         $pdf->SetFillColor(255);
         $pdf->cell(85,5,$adresse1,1,1,'C',1);
        
     }
$pdf->Setxy(25,45);
if(str_replace(' ','',$propri[0]['dnomlp'])=="")
{
$pdf->Write(3,'Propri�taire : '.$propri[0]['ddenom']);	
}
else
{
$prenom=explode(" ",$propri[0]['dprnlp']);
$pdf->Write(3,'Propri�taire : '.str_replace(' ','',$propri[0]['dqualp'])." ".str_replace(' ','',$propri[0]['dnomlp'])." ".$prenom[0]);	
}
$pdf->Setxy(25,55);
$pdf->Write(3,'Adresse : ');
if(str_replace(' ','',$propri[0]['dlign3'])!="")
{
$pdf->Write(3,$propri[0]['dlign3']);
}
else
{
$pdf->Write(3,$propri[0]['dlign4']);
}
$pdf->Setxy(41,60);
if(str_replace(' ','',$propri[0]['dlign3'])!="")
{
$pdf->Write(3,$propri[0]['dlign4']);
}
else
{
if(str_replace(' ','',$propri[0]['dlign5'])!="")
{
$pdf->Write(3,$propri[0]['dlign5']);
$pdf->Setxy(41,65);
$pdf->Write(3,$propri[0]['dlign6']);
}
else
{
$pdf->Write(3,$propri[0]['dlign6']);
}
}


$pdf->Setxy(25,75);
$pdf->Write(3,'Etat de la facade :');
if($retou[0]['etat']=="a")
{
$etat=" bon �tat";
}
elseif($retou[0]['etat']=="b")
{
$etat=" �tat moyen";
}
else
{
$etat=" mauvais �tat";
}
$pdf->Write(3,$etat);
if($retou[0]['surveiller']=="1")
{
$pdf->Setx(120);
$pdf->Settextcolor(255,0,0);
$pdf->Write(3,'facade � surveiller');
}
$pdf->Settextcolor(0,0,0);
$pdf->Setxy(25,80);
$pdf->Write(3,'Date de mise � jour : '.$retou[0]['date']);
$pdf->Setxy(25,85);
$pdf->Write(3,'Ann�e du dernier ravalement : '.$retou[0]['date_rav']);
$pdf->Setxy(25,90);
$pdf->Write(3,'Observation: ');
$pdf->Setxy(25,95);
$pdf->Setfillcolor(225);
$pdf->MultiCell(160,30,$retou[0]['observation'], 1,"L",1) ;
$sql="SELECT * from urba.photo_ravalement where id_ravalement IN(".$_GET['gid'].")";
$col=$DB->tab_result($sql);
if(count($col)>0)
{
$posiy=140;
for($i=0;$i<count($col);$i++)
{
$tableau = GetImageSize("photo/".$col[$i]['id_photo'].".JPG");
		$width = 150;
		$height = 100;
		$ratio_orig = $tableau[0]/$tableau[1];

		if ($width/$height > $ratio_orig) {
   		$width= $height*$ratio_orig;
		} else {
   		$height= $width/$ratio_orig;
		}
		if(($height+$posiy)>280)
		{
		$pdf->AddPage();
		$posiy=10;
		}
		$pdf->Image("photo/".$col[$i]['id_photo'].".JPG",(210-$width)/2,$posiy,$width,$height,'','');
		$posiy=$posiy+10+$height;
}
}
$pdf->Output();
//}
?>
