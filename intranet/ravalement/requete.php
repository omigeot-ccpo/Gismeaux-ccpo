<?php
session_start();
$aph=4; //espace avant paragraphe
$alig=5; //espace entre ligne
$acel=2; //espace entre deux ligne dans la meme cellule
define('FPDF_FONTPATH','../fpdf/font/');
require('../fpdf/fpdf.php');
class PDF extends FPDF {
  var $B;
  var $I;
  var $U;
  var $HREF;

  function PDF($orientation='P',$unit='mm',$format='A4'){
      //Appel au constructeur parent
      $this->FPDF($orientation,$unit,$format);
      //Initialisation
      $this->B=0;
      $this->I=0;
      $this->U=0;
      $this->
      $this->HREF='';
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
      //Modifie le style et sélectionne la police correspondante
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

$nocoche="deco.png";
$coche="okcoche.png";

$q="SELECT  nom_voie,dnuvoi,dindic,identifian,prop1 FROM cadastre.parcelle1 WHERE identifian in ('";
$wh=""; $q2="";
    $p=explode(",",$_GET['obj_keys']);
	$nb_obj=0;
   // $codeinsee='770'.substr($p[$nb_obj],0,3);
	//préparation des requetes utilisant les obj_keys
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


$rq = tab_result($pgx,$q);

$requete_propri="select ddenom,dqualp,dnomlp,dprnlp,dlign3,dlign4,dlign5,dlign6 from cadastre.propriet where prop1='".$rq[0]['prop1']."'";
$propri = tab_result($pgx,$requete_propri);
if (count($rq)==0){header("Location: ./messparcel.php");}
else{
    $q1="select nom,tel_urba,horaire_urba,plu_pos,approb,modif,larg_logo from admin_svg.commune where idcommune = '".$_SESSION['code_insee']."'";
     $r1=tab_result($pgx,$q1);
      //creation du fichier pdf
     $pdf=new PDF();
     $pdf->Open();
     //création page
     $pdf->AddPage();
     //sélection de la police par défaut
     $pdf->SetFont('times','',11);
     //fixe la marge gauche des textes qui suivent après un retour à la ligne(<br>)
     $pdf->SetLeftMargin(15);
     //supprime le saut de page
     $pdf->Setautopagebreak(0,0);
     //fixe la marge droite des textes qui suivent
     $pdf->SetrightMargin(5);
     $larg=(70*$r1[0]['larg_logo'])/100;
     $pdf->Image("../logo/".$_SESSION['code_insee'].".png",10,8,$larg,'20','','');
     //on se positionne à 130mm du bord gauche et à 15 mm du bord haut
     $pdf->Setxy(120,10);
	 $pdf->MultiCell(60,5,$r1[0]['nom'].', le '.date("d").' '.moix(date("m")).' '.date("Y")) ;
     //$pdf->Write(5,$r1[0]['nom'].', le '.date("d").' '.moix(date("m")).' '.date("Y"));
     $pdf->SetFont('','B',13);
     $pdf->SetFont('','',11);
     //création de l'encart Adresse
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
$pdf->Write(3,'Propriétaire : '.$propri[0]['ddenom']);	
}
else
{
$prenom=explode(" ",$propri[0]['dprnlp']);
$pdf->Write(3,'Propriétaire : '.str_replace(' ','',$propri[0]['dqualp'])." ".str_replace(' ','',$propri[0]['dnomlp'])." ".$prenom[0]);	
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
if($_GET['type']=="a")
{
$etat=" bon état";
}
elseif($_GET['type']=="b")
{
$etat=" état moyen";
}
else
{
$etat=" mauvais état";
}
$pdf->Write(3,$etat);
if($_GET['surveiller']=="1")
{
$pdf->Setx(120);
$pdf->Settextcolor(255,0,0);
$pdf->Write(3,'facade à surveiller');
}
$pdf->Settextcolor(0,0,0);
$pdf->Setxy(25,80);
$pdf->Write(3,'Date de mise à jour : '.$_GET['date']);
$pdf->Setxy(25,85);
$pdf->Write(3,'Observation: ');
$pdf->Setxy(25,90);
$pdf->Setfillcolor(225);
$pdf->MultiCell(160,30,$_GET['observation'], 1,"L",1) ;
$sql="SELECT * from geotest.photo_ravalement where id_ravalement IN(".$_GET['gid'].")";
$col=tab_result($pgx,$sql);
if(count($col)>0)
{
$posiy=135;
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
}
?>
