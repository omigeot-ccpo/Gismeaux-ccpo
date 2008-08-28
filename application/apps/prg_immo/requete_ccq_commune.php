<?php
/*Copyright Ville de Meaux 2004-2007
contributeur: jean-luc Dechamp - robert Leguay 
sig@meaux.fr

Ce logiciel est un programme informatique fournissant une interface cartographique WEB communale. 

Ce logiciel est rÈgi par la licence CeCILL-C soumise au droit franÁais et
respectant les principes de diffusion des logiciels libres. Vous pouvez
utiliser, modifier et/ou redistribuer ce programme sous les conditions
de la licence CeCILL-C telle que diffusÈe par le CEA, le CNRS et l'INRIA 
sur le site "http://www.cecill.info".

En contrepartie de l'accessibilitÈ au code source et des droits de copie,
de modification et de redistribution accordÈs par cette licence, il n'est
offert aux utilisateurs qu'une garantie limitÈe.  Pour les mÍmes raisons,
seule une responsabilitÈ restreinte pËse sur l'auteur du programme,  le
titulaire des droits patrimoniaux et les concÈdants successifs.

A cet Ègard  l'attention de l'utilisateur est attirÈe sur les risques
associÈs au chargement,  ‡ l'utilisation,  ‡ la modification et/ou au
dÈveloppement et ‡ la reproduction du logiciel par l'utilisateur Ètant 
donnÈ sa spÈcificitÈ de logiciel libre, qui peut le rendre complexe ‡ 
manipuler et qui le rÈserve donc ‡ des dÈveloppeurs et des professionnels
avertis possÈdant  des connaissances  informatiques approfondies.  Les
utilisateurs sont donc invitÈs ‡ charger  et  tester  l'adÈquation  du
logiciel ‡ leurs besoins dans des conditions permettant d'assurer la
sÈcuritÈ de leurs systËmes et ou de leurs donnÈes et, plus gÈnÈralement, 
‡ l'utiliser et l'exploiter dans les mÍmes conditions de sÈcuritÈ. 

Le fait que vous puissiez accÈder ‡ cet en-tÍte signifie que vous avez 
pris connaissance de la licence CeCILL-C, et que vous en avez acceptÈ les 
termes.*/
define('GIS_ROOT', '../..');
include_once(GIS_ROOT . '/inc/common.php');
gis_session_start();

$insee = $_SESSION['profil']->insee;
$appli = $_SESSION['profil']->appli;

if ((!$_SESSION['profil']->acces_ssl) || !in_array ("Prg Immobilier", $_SESSION['profil']->liste_appli)){
	die("Point d'entr√©e r√©glement√©.<br> Acc√®s interdit. <br>Veuillez vous connecter via <a href=\"https://".$_SERVER['HTTP_HOST']."\">serveur carto</a><SCRIPT language=javascript>setTimeout(\"window.location.replace('https://".$_SERVER['HTTP_HOST']."')\",5000)</SCRIPT>");
}
define('FPDF_FONTPATH',GIS_ROOT.'/fpdf/font/');
require(GIS_ROOT.'/fpdf/afpdf.php');
$sq="select max(cid)as ht,annee from (SELECT
	COUNT(program.gid) as cid ,annee as annee
FROM
	urba.program program INNER JOIN GENERAL.quartier quartier 
		ON distance(program.the_geom , quartier.the_geom )=0 
WHERE
	(program.date_livraison IS NULL) 
GROUP BY
	quartier.nom,program.annee) as foo group by annee order by annee asc";
$rsq=$DB->tab_result($sq);
$q="select * from general.quartier order by gid";
$rq = $DB->tab_result($q);
//creation du fichier pdf
$pdf=new TPDF('L','mm','A3');
$pdf->Open();
//crÈation page
$pdf->AddPage();
//sÈlection de la police par dÈfaut
$pdf->SetFont('arial','',11);
//fixe la marge gauche des textes qui suivent aprËs un retour ‡ la ligne(<br>)
$pdf->SetLeftMargin(15);
//supprime le saut de page
$pdf->Setautopagebreak(0,0);
//fixe la marge droite des textes qui suivent
$pdf->SetrightMargin(5);
//on se positionne ‡ 130mm du bord gauche et ‡ 15 mm du bord haut
$pdf->Setxy(300,10);
$pdf->Write(5,'Le '.date("d").' '.moix(date("m")).' '.date("Y"));
$pdf->SetFont('','B',13);
$pdf->Setxy(15,20);
$pdf->Write(6,'PrÈvisions - Livraisons de programmes de logements (selon quartiers)');
//dÈfinition des variables de prÈsentation
$ht=5; //hauteur cellule
//traitement colonne annee
$pdf->SetLeftMargin(5);
$pdf->Setxy(5,30+(($ht+2)*2));
$pdf->SetFillColor(225);
$pdf->SetFont('','',$ht-1);
for ($p=0;$p<count($rsq);$p++){
	$pdf->cell(6,$ht*$rsq[$p]['ht'],$rsq[$p]['annee'],1,0,'C',1);
	$pdf->ln();
	$pdf->cell(6,$ht,'Totaux',1,0,'C',1);
	$pdf->ln();
	$pdf->ln();
}
//traitement par ccq
$pdf->SetLeftMargin(11);
for ($o=0;$o<count($rq);$o++){
	$sql2="select * from urba.program where distance(the_geom,'".$rq[$o]['the_geom']."')=0 and date_livraison is null order by annee";
	$rsql2=$DB->tab_result($sql2);
	$sql3="select fill from admin_svg.col_theme where idappthe=(select idappthe from admin_svg.appthe where idtheme=(select idtheme from admin_svg.theme where libelle_them='quartier')  and idapplication='18') and valeur_texte='".$rq[$o]['nom']."'";
	$rsql3=$DB->tab_result($sql3);
	$rgb=explode(",",$rsql3[0]['fill']);
	$pdf->SetLeftMargin(11+(40*$o));
	$pdf->SetFont('','',$ht+2);
	$pdf->Setxy(11+(40*$o),30);
	$pdf->cell(40,$ht+2,$rq[$o]['nom'],1,0,'C',0);
	$pdf->ln();
	$pdf->SetFillColor($rgb[0],$rgb[1],$rgb[2]);//prendre couleur du thËme
	$pdf->SetFont('','B',$ht-1);
	$pdf->cell(5,$ht+2,'Num',1,0,'C',1);
	$pdf->cell(16,$ht+2,'Programme',1,0,'C',1);
	$pdf->cell(6,$ht+2,'Nb logts',1,0,'C',1);
	$pdf->cell(7,$ht+2,'Coll/Ind',1,0,'C',1);
	$pdf->cell(6,$ht+2,'Sociaux',1,0,'C',1);
	$pdf->ln();
	
	$y_ini=$pdf->gety();$tot_lgt=0;$tot_coll=0;$tot_ind=0;$tot_soc=0;
//parcours des annees
	$k=0;
	for ($j=0;$j<count($rsq);$j++){
		if ($rsq[$j]['annee']!=$rsql2[$k]['annee']){
			for ($l=0;$l<$rsq[$j]['ht'];$l++){
				$pdf->cell(5,$ht,'',1,0,'C',0);
				$pdf->cell(16,$ht,'',1,0,'',0);
				$pdf->cell(6,$ht,'',1,0,'C',0);
				$pdf->cell(7,$ht,'',1,0,'C',0);
				$pdf->cell(6,$ht,'',1,0,'c',0);
				$pdf->ln();
			}
			$pdf->cell(5,$ht,'',1,0,'C',1);
			$pdf->cell(16,$ht,'',1,0,'',1);
			$pdf->cell(6,$ht,'',1,0,'C',1);
			$pdf->cell(7,$ht,'',1,0,'C',1);
			$pdf->cell(6,$ht,'',1,0,'c',1);
			$pdf->ln();
			$pdf->ln();
		}else{
			for ($i=$k;$i<count($rsql2);$i++){
				if ($rsq[$j]['annee']!=$rsql2[$i]['annee']){break;}
				$pdf->cell(5,$ht,$rsql2[$i]['num'],1,0,'C',0);
				$pdf->cell(16,$ht,$rsql2[$i]['nom'],1,0,'',0);
				$pdf->cell(6,$ht,$rsql2[$i]['nbre_lgt'],1,0,'C',0);$tot_lgt=$tot_lgt+$rsql2[$i]['nbre_lgt'];
				$pdf->cell(7,$ht,$rsql2[$i]['nbre_coll']."/".$rsql2[$i]['nbre_ind'],1,0,'C',0);
				$tot_coll=$tot_coll+$rsql2[$i]['nbre_coll'];$tot_ind=$tot_ind+$rsql2[$i]['nbre_ind'];
				$pdf->cell(6,$ht,$rsql2[$i]['logt_sociaux'],1,0,'c',0);$tot_soc=$tot_soc+$rsql2[$i]['logt_sociaux'];
				$pdf->ln();
			}
			for ($l=($i-$k);$l<$rsq[$j]['ht'];$l++){
				$pdf->cell(5,$ht,'',1,0,'C',0);
				$pdf->cell(16,$ht,'',1,0,'',0);
				$pdf->cell(6,$ht,'',1,0,'C',0);
				$pdf->cell(7,$ht,'',1,0,'C',0);
				$pdf->cell(6,$ht,'',1,0,'c',0);
				$pdf->ln();
			}
			$pdf->cell(5,$ht,'',1,0,'C',1);
			$pdf->cell(16,$ht,'',1,0,'',1);
			$pdf->cell(6,$ht,$tot_lgt,1,0,'C',1);
			$pdf->cell(7,$ht,$tot_coll.'/'.$tot_ind,1,0,'C',1);
			$pdf->cell(6,$ht,$tot_soc,1,0,'c',1);
			$pdf->ln();
			$pdf->ln();
			$k=$i;$tot_lgt=0;$tot_coll=0;$tot_ind=0;$tot_soc=0;
		}
	}
	$an_ini=$rsql2[0]['annee'];
}
$pdf->Output();
?>
