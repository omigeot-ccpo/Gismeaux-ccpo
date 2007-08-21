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
//session_start();
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
//include("../rrrr.php");
include("../connexion/deb.php");

$nocoche="deco.png";
$coche="okcoche.png";

$q="SELECT  nom_voie,dnuvoi,dindic,identifian FROM cadastre.parcelle WHERE identifian in ('";
$wh=""; $q2="";
if ($OBJ_KEYS){	$obj_keys=$OBJ_KEYS;}
if ($obj_keys){
    $p=explode(",",$obj_keys);
	$nb_obj=0;
    $codeinsee='770'.substr($p[$nb_obj],0,3);
	//préparation des requetes utilisant les obj_keys
	while ($p[$nb_obj]) {
		$a1=substr($p[$nb_obj],6,2);
		$b1=str_pad(substr($p[$nb_obj],8,4),4,"0",STR_PAD_LEFT);
		$q2.=substr($p[0],0,6).$a1.$b1."','";
		$wh.="(a.identifian='".substr($p[0],0,6).$a1.$b1."' and Intersects(a.the_geom,b.the_geom)) or ";
		$nb_obj++;
	}
}elseif($section1){
   $po=1;
   $pi="parcelle";$pu="section";$codeins=substr($codeinsee,3,3)."000";
   while(${$pi.$po}!=""){
     $b1=str_pad(${$pi.$po},4,"0",STR_PAD_LEFT);
     $q2.=$codeins.strtoupper(${$pu.$po}).$b1."','";
	 $wh.="(a.identifian='".$codeins.strtoupper(${$pu.$po}).$b1."' and Intersects(a.the_geom,b.the_geom)) or ";
     $po++;
   }
}else{
    header("Location: ./ru.php");
}
$q2=substr($q2,0,strlen($q2)-3);
$q.=$q2."') order by identifian";
$wh=substr($wh,0,strlen($wh)-4);
$an=number_format(date(Y));
if (file_exists("./".$codeinsee."/compteur.txt"))
{
$fp=fopen("./".$codeinsee."/compteur.txt","r");
while (!feof($fp)){
      $ligne=fgets($fp,255);
      $liste=explode(";",$ligne);
      $n=number_format($liste[0]);
      $annee=number_format($liste[1]);
}
fclose($fp);
}
if($an>$annee){
    $n=1;
    $annee=date(Y);
}else{
    $n++;
    $annee=date(Y);
}
$fp=fopen("./".$codeinsee."/compteur.txt","w");
fputs($fp,"$n;$annee");
$comp=$n;
fclose($fp);

$rq = tab_result($pgx,$q);
if (count($rq)==0){header("Location: ./messparcel.php");}
else{
     $q1="select nom,tel_urba,horaire_urba,plu_pos,approb,modif,larg_logo from admin_svg.commune where idcommune = '".$codeinsee."'";
     $r1=tab_result($pgx,$q1);
     /*$qq="select larg_logo from cadastre.commune where cod_comm = '".$codeinsee."'";
     $rr1=tab_result($pgx,$qq);*/
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
     $pdf->Image("../logo/".$codeinsee.".png",10,8,$larg,'20','','');
     //on se positionne à 130mm du bord gauche et à 15 mm du bord haut
     $pdf->Setxy(130,10);
     $pdf->Write(5,$r1[0]['nom'].', le '.date("d").' '.moix(date("m")).' '.date("Y"));
     $pdf->SetFont('','B',13);
     $pdf->Setxy(85,17);
     $pdf->Write(5,'Renseignement d'.chr(180).'Urbanisme N° I'.$comp.'/'.$annee);
     $pdf->SetFont('','',11);
     $pdf->Setxy(15,45);
     $pdf->Write(5,'Pour contacter le service Urbanisme:');
     $pdf->Setxy(15,50);
     $pdf->Write(5,$r1[0]['tel_urba'].' ('.$r1[0]['horaire_urba'].')');
     $pdf->Setxy(10,60);
     $pdf->SetFont('','B',11);
     $pdf->Write(5,'le terrain est concerné par les rubriques cochées d'.chr(180).'une croix:');
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
         if ($numa1!="okcoche.png"){
             if ($rq[$i][1]!=""){
                 $numa2="deco.png";
                 $numa1="okcoche.png";
             }else{
                 $numa1="deco.png";
                 $numa2="okcoche.png";
             }
         }
     }

     $table=array("zonebruit","zoneinondable","zoneboise","lisiere","ppmh","sidobre","emplreserve","z_archeo","siteinscrit","servradioelectrik","zononaedificandi","alignement","halage","cimetiere","lotissement","ligne_ht","ppri");
     $tabl=array("bruit","idinond","bois","lisiere","ppmh","sidobre","empl","archeo","inscrit","radio","sncf","alignement","halage","cimet","idlotiss","lg_ht","ppri");

     $nb=count($table);
     $cinon=0;
     for($j=0;$j<=$nb-1;$j++){
         $q="select Intersects(a.the_geom,b.the_geom),b.gid,area(intersection(a.the_geom,b.the_geom)) from cadastre.parcelle as a ,geotest.$table[$j] as b where ".$wh;//." limit 1";
     $result = pg_exec($pgx,$q);
	    $num = pg_numrows($result);
	    for ($i=0; $i<$num; $i++){
		   $r = pg_fetch_row($result, $i);
		   if ($tabl[$j]=="alignement" or $tabl[$j]=="halage"){
		       if($r[0]){
                    $$tabl[$j]="true";
                    if($tabl[$j]=="idlotiss"){$idlotis=$r[1];}
               }
           }else{
               if($r[2]>1){
		             $$tabl[$j]="true";
		             if($tabl[$j]=="idlotiss"){$idlotis=$r[1];}
		             if($tabl[$j]=="idinond"){$zinond[$cinon]=$r[1];$cinon++;}
		             if($tabl[$j]=="empl"){$emplreserv=$r[1];}
               }
           }
		}
	}
//récupération du zonage pour droit de préemption
$n=0;
$toto= "SELECT b.zone,a.identifian,area(intersection(a.the_geom,b.the_geom)) FROM cadastre.parcelle as a ,geotest.zonage as b group by b.zone,a.identifian,a.the_geom,b.the_geom having ".$wh." order by b.zone";
//echo $toto;
$result = pg_exec($pgx,$toto);
$num = pg_numrows($result);
for ($i=0; $i<$num; $i++){
    $r = pg_fetch_row($result, $i);
    if (($r[2]>1)and($zone[$n-1]!=$r[0])){
       $zone[$n]=$r[0];
       if ($simple!="okcoche.png"){
          if($r[0]=="N" or $r[0]=="NL"){
               $simple="deco.png";
          }else{
               $simple="okcoche.png";
          }
       }
       $n=$n+1;
    }
}
$beneficaire="ville de Meaux";
$toto= "SELECT b.gid,a.identifian,area(intersection(a.the_geom,b.the_geom)) FROM cadastre.parcelle as a ,geotest.zac as b group by b.gid,a.identifian,a.the_geom,b.the_geom having ".$wh." order by b.gid";
//echo $toto;
$result = pg_exec($pgx,$toto);
$num = pg_numrows($result);
for ($i=0; $i<$num; $i++){
    $r = pg_fetch_row($result, $i);
    
          if($r[2]>2 and ($r[0]=="12" or $r[0]=="13")){
		  $simple="okcoche.png";
          $beneficaire="AFTRP";   
          }
       
    
}

//création de l'encart A-Droit de Préemption
$pdf->Setxy(8,70);
$pdf->SetFillColor(225);
$pdf->Cell(195,5,'A-Droit de Préemption ',1,1,'L',1);
$y=$pdf->GetY()+$aph;
$pdf->Setxy(9,$y);
$pdf->Write(3,'Droit de Préemption Urbain');
$pdf->Image($simple,60,$y,3,3,'','');
$pdf->Setx(65);
$pdf->Write(3,'Simple');
$pdf->Image($nocoche,80,$y,3,3,'','');
$pdf->Setx(85);
$pdf->Write(3,'Renforcé');
$pdf->Setx(105);
$pdf->Write(3,'Bénéficiaire du droit:');
$pdf->Setx(140);
$pdf->Write(3,$beneficaire);
$pdf->Ln();
$y=$pdf->GetY()+$alig;
//pas de données sur Zone d'Aménagement Différé donc nocoche par defaut à remplacer par un test si on possede la donnée
$pdf->Image($nocoche,10,$y,3,3,'','');
$pdf->Setxy(15,$y);//$pdf->Setxy(15,90);
$pdf->Write(3,'Zone d'.chr(180).'Aménagement Différé');
//pas de données sur Espaces Naturels Sensibles donc nocoche par defaut à remplacer par un test si on possede la donnée
$pdf->Image($nocoche,120,$y,3,3,'','');
$pdf->Setx(125);
$pdf->Write(3,'Espaces Naturels Sensibles');

//création de l'encart B-Nature des dispositions d'.chr(180).'urbanisme applicables au terrain
$pdf->Ln();
$y=$pdf->GetY()+$aph+$alig;
$pdf->Setxy(8,$y);
$pdf->SetFillColor(225);
$pdf->Cell(195,5,'B-Nature des dispositions d'.chr(180).'urbanisme applicables au terrain ',1,1,'L',1);
$y1=$pdf->GetY()+$aph;
if($idlotiss=="true"){
	$idlotiss="okcoche.png";
    $r = tab_result($pgx,"SELECT  nom,date,gid FROM geotest.lotissement WHERE gid=$idlotis");
    $nomlotiss=$r[0][0];
    $datlotiss=$r[0][1];
    $pattern = "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})";
   // Découpe la chaîne
   ereg($pattern,$datlotiss,$regs);
      // Permute les éléments
    $mois=$regs[2];
	$jour=$regs[1];
	$annee=$regs[3];
    $timestamp = mktime(0,0,0,$mois,$jour,$annee);
    // -- DATE ACTUELLE --
    $d2 = time();
    // -- CALCUL --
    $diff = $d2 - $timestamp;
    $ecart_annee = floor($diff / 31557600);
    if($ecart_annee<10){
         $plu="deco.png";
         $dloti=" ".$nomlotiss." approuvé le ".$datlotiss;
         $simple="okcoche.png";
    }else{
          $plu="okcoche.png";
          $approu="(Approuvé le ".$r1[0]['approb'];
          if ($r1[0]['modif']!=""){$approu.=" - Modifié le ".$r1[0]['modif'];}
          $approu.=")";
    }
    $y=$y+$alig+$acel;
}else{
    for($p=0;$p<$n;$p++){
       ereg("[A-Z]{1,}",$zone[$p],$vr);
       $pdf->Setxy(135+(15*$p),$y1);
       $pdf->PutLink("./".$codeinsee."/".$vr[0].".pdf",$zone[$p]);
    }
	$idlotiss="deco.png";
    $plu="okcoche.png";
    $approu="(Approuvé le ".$r1[0]['approb'];
    if ($r1[0]['modif']!=""){$approu.=" - Modifié le ".$r1[0]['modif'];}
    $approu.=")";
    //création de l'encart Secteur
    $pdf->Setxy(122,$y1);
    $pdf->Write(3,'Secteur:');
    $pdf->SetFont('','I',8);
    $pdf->Ln();
	$y=$pdf->GetY()+$acel;
	$pdf->Setxy(135,$y);
    $pdf->Write(3,'(cliquer pour voir le réglement du secteur)');
    $pdf->Setxy(15,$y);
    $pdf->Write(3,'(cliquer');
    $pdf->Setxy(25,$y);
    $pdf->SetFont('','IU',8);
    $pdf->SetTextColor(0,0,255);
    $pdf->Write(3,'ici','./'.$codeinsee.'/REGLEMENT.pdf');
    $pdf->SetFont('','I',8);
    $pdf->SetTextColor(0);
    $pdf->Setxy(29,$y);
    if ($r1[0]['plu_pos']=='1'){
          $pdf->Write(3,'pour voir le réglement complet du PLU)');
    }else{
        $pdf->Write(3,'pour voir le réglement complet du POS)');
    }
    $pdf->SetFont('','',11);
}
$pdf->Image($plu,10,$y1,3,3,'','');
$pdf->Setxy(15,$y1);
if ($r1[0]['plu_pos']=='1'){
    $pdf->Write(3,'Plan local d'.chr(180).'urbanisme ');
}else{
    $pdf->Write(3,'Plan d'.chr(180).'occupation des sols ');
}
$pdf->SetFont('','I',8);
$pdf->Write(3.5,$approu);
$pdf->SetFont('','',11);
$pdf->SetY($y);
$pdf->Ln();
$y=$pdf->GetY()+$alig;
$pdf->Image($idlotiss,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Lotissement '.$dloti);

//création de l'encart c-nature des servitudes d'utilité publiques applicables au terrain
$pdf->Ln();
$y=$pdf->GetY()+$aph+$alig;
$pdf->Setxy(8,$y);
$pdf->SetFillColor(225);
$pdf->Cell(195,5,'C-Nature des servitudes d'.chr(180).'utilité publiques applicables au terrain ',1,1,'L',1);
$y=$pdf->GetY()+$aph;
if($ppmh=="true"){$ppmh="okcoche.png";}else{$ppmh="deco.png";}
//1 ere ligne
$pdf->Image($ppmh,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Périmètre de Protection');
//pas de données sur Zone Protection Patrimoine donc nocoche par defaut à remplacer par un test si on possede la donnée
$pdf->Image($nocoche,75,$y,3,3,'','');
$pdf->Setxy(80,$y);
$pdf->Write(3,'Zone Protection Patrimoine');
if($alignement=="true"){
	$align=$alignement;
	$alignement="okcoche.png";
}else{$alignement="deco.png";}
$pdf->Image($alignement,140,$y,3,3,'','');
$pdf->Setxy(145,$y);
$pdf->Write(3,'Plan d'.chr(180).'alignement');
//2eme ligne
$pdf->Ln();
$y=$pdf->GetY()+$acel;
$pdf->Setxy(15,$y);
$pdf->Write(3,'d'.chr(180).'un Monument Historique');
$pdf->Setxy(80,$y);
$pdf->Write(3,'Architectural et Urbain');
$pdf->Ln();
$y=$pdf->GetY()+$alig;
if($inscrit=="true"){$inscrit="okcoche.png";}else{$inscrit="deco.png";}
$pdf->Image($inscrit,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Site Inscrit');
if($idinond=="true"){
    $inond="okcoche.png";
    if ($cinon==2){
        $zoninon=$zinond[0].",".$zinond[1];
    }else{$zoninon=$zinond[0];}
    $q="select distinct(zone) from geotest.zoneinondable where gid in (".$zoninon.");";
    $rinon=list_result($pgx,$q);
     //echo $rinon;
    $rinon=str_replace("'","",$rinon);
    $rinon=str_replace(","," et ",$rinon);
    $pdf->Setxy(90,155);
    $pdf->Write(3,$rinon);
}else{$inond="deco.png";}
$pdf->Image($inond,75,$y,3,3,'','');
$pdf->Setxy(80,$y);
$pdf->Write(3,'Concerné par une zone inondable ');
if($sncf=="true"){$sncf="okcoche.png";}else{$sncf="deco.png";}
$pdf->Image($sncf,140,$y,3,3,'','');
$pdf->Setxy(145,$y);
$pdf->Write(3,'Zone non aedificanti');
//3eme ligne
$pdf->Ln();
$y=$pdf->GetY()+$alig;
//pas de données sur perimetre Site Classé donc nocoche par defaut à remplacer par un test si on possede la donnée
$pdf->Image($nocoche,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Site Classé');
if($ppri=="true"){$zppri="okcoche.png";}else{$zppri="deco.png";}
$pdf->Image($zppri,75,$y,3,3,'','');
$pdf->Setxy(80,$y);
$pdf->Write(3,'Concernée par un projet de Plan de');
if($sidobre=="true"){$sidobre="okcoche.png";}else{$sidobre="deco.png";}
$pdf->Image($sidobre,140,$y,3,3,'','');
$pdf->Setxy(145,$y);
$pdf->Write(3,'Périmètre de Protection de Risque');
//4eme ligne
$pdf->Ln();
$y=$pdf->GetY()+$acel;
$pdf->Setxy(80,$y);
$pdf->Write(3, 'Prévention de Risque Naturel');
$pdf->Setxy(145,$y);
$pdf->Write(3, 'Technologique');
//5eme ligne
$pdf->Ln();
$y=$pdf->GetY()+$alig;
if($lg_ht=="true"){$lht="okcoche.png";}else{$lht="deco.png";}
$pdf->Image($lht,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Surplomb ligne haute tension');
if($archeo=="true"){$archeo="okcoche.png";}else{$archeo="deco.png";}
$pdf->Image($archeo,75,$y,3,3,'','');
$pdf->Setxy(80,$y);
$pdf->Write(3,'Zone de risque archéologique');

//création de l'encart D-Opérations concernant le terrain
$pdf->Ln();
$y=$pdf->GetY()+$aph+$alig;
$pdf->Setxy(8,$y);
$pdf->SetFillColor(225);
$pdf->Cell(195,5,'D-Opérations concernant le terrain ',1,1,'L',1);
$y=$pdf->GetY()+$aph;
if($empl=="true"){$empl="okcoche.png";}else{$empl="deco.png";}
$pdf->Image($empl,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Emplacement Réservé pour équipement public');
//pas de données sur perimetre de rénovaton urbaine donc nocoche par defaut à remplacer par un test si on possede la donnée
$pdf->Image($nocoche,115,$y,3,3,'','');
$pdf->Setxy(120,$y);
$pdf->Write(3,'Périmètre de Rénovation Urbaine');
$pdf->Ln();
$y=$pdf->GetY()+$alig;
//pas de données sur perimetre de Déclaration d'Utilité Publique donc nocoche par defaut à remplacer par un test si on possede la donnée
$pdf->Image($nocoche,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Périmètre de Déclaration d'.chr(180).'Utilité Publique');
//pas de données sur Périmètre de Restauration Immobilière donc nocoche par defaut à remplacer par un test si on possede la donnée
$pdf->Image($nocoche,115,$y,3,3,'','');
$pdf->Setxy(120,$y);
$pdf->Write(3,'Périmètre de Restauration Immobilière');
$pdf->Ln();
$y=$pdf->GetY()+$alig;
//idem pas de donnée
$pdf->Image($nocoche,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Secteur Sauvegardé');
//idem pas de donnée
$pdf->Image($nocoche,115,$y,3,3,'','');
$pdf->Setxy(120,$y);
$pdf->Write(3,'Périmètre de Résorption Habitat Insalubre');
$pdf->Ln();
$y=$pdf->GetY()+$alig;
//idem pas de donnée
$pdf->Image($nocoche,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Zone d'.chr(180).'Aménagement Concerté');

//création de l'encart E-Divers
$pdf->Ln();
$y=$pdf->GetY()+$aph+$alig;
$pdf->Setxy(8,$y);
$pdf->SetFillColor(225);
$pdf->Cell(195,5,'E-Divers ',1,1,'L',1);
$y=$pdf->GetY()+$aph;
//idem pas de donnée
$pdf->Image($nocoche,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Interdiction d'.chr(180).'habiter');
if($bois=="true"){$bois="okcoche.png";}else{$bois="deco.png";}
$pdf->Image($bois,75,$y,3,3,'','');
$pdf->Setxy(80,$y);
$pdf->Write(3,'Espace boisé classé');
//idem pas de donnée
$pdf->Image($nocoche,140,$y,3,3,'','');
$pdf->Setxy(145,$y);
$pdf->Write(3,'Zone de carrière');
$pdf->Ln();
$y=$pdf->GetY()+$alig;
//idem pas de donnée
$pdf->Image($nocoche,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Arrêté de péril');
if($cimet=="true"){$cimet="okcoche.png";}else{$cimet="deco.png";}
$pdf->Image($cimet,75,$y,3,3,'','');
$pdf->Setxy(80,$y);
$pdf->Write(3,'Voisinage cimetière');
if($bruit=="true"){$bruit="okcoche.png";}else{$bruit="deco.png";}
$pdf->Image($bruit,140,$y,3,3,'','');
$pdf->Setxy(145,$y);
$pdf->Write(3,'Secteur de nuisances de bruit');

if($lisiere=="true"){
	$lisiere="okcoche.png";
}else{
	$lisiere="deco.png";
}
if($radio=="true"){
	$radio="okcoche.png";
}else{
	$radio="deco.png";
}
if($halage=="true"){
	$halage="okcoche.png";
}else{
	$halage="deco.png";
}
if($edf=="true"){
	$edf="okcoche.png";
}else{
	$edf="deco.png";
}
if($carriere=="true"){
	$carriere="okcoche.png";
}else{
	$carriere="deco.png";
}

//création de l'encart F-Numérotage
$pdf->Ln();
$y=$pdf->GetY()+$aph+$alig;
$pdf->Setxy(8,$y);
$pdf->SetFillColor(225);
$pdf->Cell(195,5,'F-Numérotage ',1,1,'L',1);
$y=$pdf->GetY()+$aph;
$pdf->Image($numa1,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Le terrain porte le numéro de voirie indiqué dans le cadre en-tête');
$pdf->Ln();
$y=$pdf->GetY()+$alig;
$pdf->Image($numa2,10,$y,3,3,'','');
$pdf->Setxy(15,$y);
$pdf->Write(3,'Le terrain est situé en bordure d'.chr(180).'une voie sans numérotage');


$pdf->SetFont('times','B',8);
$pdf->Settextcolor(255,0,0);
$pdf->SetLeftMargin(21);
$pdf->Setxy(14,290);
$pdf->Write(3,'P.S : La présente notice d'.chr(180).'urbanisme fait état des renseignements connus à ce jour par l'.chr(180).'autorité indiquée ci-contre. Elle constitue un simple document d'.chr(180).'information et ne peut en aucun cas être considérée comme une autorisation administrative quelconque, ni un certificat d'.chr(180).'urbanisme.');


/*$sql = "SELECT datealign,alignement
FROM alignement WHERE alignement='$align'";

$result = mysql_query($sql) ;
while($row = mysql_fetch_row($result))
	{
	$dalign=$row[0];
	}*/
/*$result = pg_exec($pgx,"SELECT b.zone from cadastre.parcelle as a ,geotest.zoneinondable as b where ".$wh." order by b.zone ASC limit 1");

$num = pg_numrows($result);
for ($i=0; $i<$num; $i++)
{  
$r = pg_fetch_row($result, $i);
$lettreinond=$r[0];
}*/



//}

//if (($alignement=="okcoche.png")  and ($_SESSION['code_insee']=='770284'))
if (($alignement=="okcoche.png"))//  and ($codeinsee=='770284'))
{


$result = pg_exec($pgx,"select XMIN(the_geom),YMIN(the_geom),XMAX(the_geom),Ymax(the_geom) from cadastre.parcelle where identifian in ('$q2')");
$num = pg_numrows($result);
for ($i=0; $i<$num; $i++)
{  
$r = pg_fetch_row($result, $i);
$xmin=$r[0];
	$ymin=$r[1];
	$xmax=$r[2];
	$ymax=$r[3];
}

$xm=$xmin-45;
$xma=$xmax+45;
$yma=$ymax+45;
$ym=$ymin-45;
$h=($yma-$ym);
$l=($xma-$xm);
if($l>$h*(400/326))
{
$yma=$ym+($l/(400/326));
}
else
{
$xma=$xm+($h*(400/326));
}
$idpar = str_replace(",", "','",$obj_keys);
$erreur=error_reporting ();
	error_reporting (1);
	$serv=$_SERVER["SERVER_NAME"];
	$url="http://".$serv."/cgi-bin/mapserv?map=/home/sig/intranet/capm/RU.map&insee=".$code_insee."&parce=('".$idpar."')&layer=alignement&layer=num_voie&layer=nom_voie&layer=parcelle&layer=batiment&minx=".$xm."&miny=".$ym."&maxx=".$xma."&maxy=".$yma."&mapsize=1240%201040";

        $contenu=file($url);
       		while (list($ligne,$cont)=each($contenu)){
			$numligne[$ligne]=$cont;
		}
		$texte=$numligne[1];
	
	$couche=explode("/",$texte);
	$cou=explode(".",$couche[4]);
	$imag='../tmp/'.$cou[0];
	error_reporting ($erreur);


$pdf->AddPage();
  $pdf->SetFont('times','',11);

//fixe la marge gauche des textes qui suivent après un retour à la ligne(<br>)
  $pdf->SetLeftMargin(15);
//supprime le saut de page
  $pdf->Setautopagebreak(0,0);
//fixe la marge droite des textes qui suivent
  $pdf->SetrightMargin(5);
$pdf->SetTextColor(0);
$pdf->Image("../logo/".$codeinsee.".png",10,8,$lar,'20','','');
//on se positionne à 130mm du bord gauche et à 15 mm du bord haut
$pdf->Setxy(130,10);
     $pdf->Write(5,$r1[0]['nom'].', le '.date("d").' '.moix(date("m")).' '.date("Y"));
$pdf->SetFont('','B',13);
$pdf->Setxy(85,17);
     $pdf->Write(5,'Renseignement d'.chr(180).'Urbanisme N° I'.$comp.'/'.$annee);
$pdf->SetFont('','',11);
$pdf->Setxy(15,45);
$pdf->Write(5,'Pour contacter le service Urbanisme:');
$pdf->Setxy(15,50);
     $pdf->Write(5,$r1[0]['tel_urba'].' ('.$r1[0]['horaire_urba'].')');
$pdf->Image($imag.".jpg",10,80,'190','154,7','','');
$pdf->Image("legende.PNG",10,250,'40','32','','');
$pdf->Setxy(85,30);
$pdf->SetFillColor(225,225,225);
$pdf->Multicell(115,5,'Le présent document graphique est délivré à titre indicatif. Il ne peut en aucun cas se substituer au plan d'.chr(180).'alignement approuvé, consultable en Mairie. ',1,1,'L',1);
}
pg_close($pgx);

$pdf->Output();

}
?>
