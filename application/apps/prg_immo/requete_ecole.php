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
$aph=4; //espace avant paragraphe
$alig=5; //espace entre ligne
$acel=2; //espace entre deux ligne dans la meme cellule
define('FPDF_FONTPATH',GIS_ROOT.'/fpdf/font/');
require(GIS_ROOT.'/fpdf/afpdf.php');
$q="select * from scolaire.contour2007";
$rq = $DB->tab_result($q);
//creation du fichier pdf
$pdf=new TPDF();
$pdf->Open();
//sÈlection de la police par dÈfaut
$pdf->SetFont('times','',11);
//fixe la marge gauche des textes qui suivent aprËs un retour ‡ la ligne(<br>)
$pdf->SetLeftMargin(15);
//supprime le saut de page
$pdf->Setautopagebreak(1,0);
//fixe la marge droite des textes qui suivent
$pdf->SetrightMargin(5);
for ($y=0;$y<count($rq);$y++){
	$sql2="select * from urba.program where distance(the_geom,'".$rq[$y]['the_geom']."')=0 and date_livraison is null order by annee";
	$rsql2=$DB->tab_result($sql2);
	$sql3="select fill from admin_svg.col_theme where idappthe=50 and valeur_texte='".$rq[$y]['nom']."'";
	$rsql3=$DB->tab_result($sql3);
	$rgb=explode(",",$rsql3[0]['fill']);
	//crÈation page
	$pdf->AddPage();
	//on se positionne ‡ 130mm du bord gauche et ‡ 15 mm du bord haut
	$pdf->Setxy(130,10);
	$pdf->Write(5,'Le '.date("d").' '.moix(date("m")).' '.date("Y"));
	$pdf->SetFont('','B',13);
	$pdf->Setxy(15,20);
	$pdf->Write(6,'PrÈvisions - Livraisons de programmes de logements pour le secteur '.$rq[$y]['type_ecole'].' '.$rq[$y]['nom']);
	$pdf->Setxy(15,40);
	$pdf->SetFillColor($rgb[0],$rgb[1],$rgb[2]);//prendre couleur du thËme
	$pdf->SetFont('','',7);
	$pdf->cell(10,10,'NumÈro',1,0,'C',1);
	$pdf->cell(60,10,'Programme',1,0,'C',1);
	$pdf->cell(20,10,'Nombre de logts',1,0,'C',1);
	$pdf->cell(20,10,'Collectif/Individuel',1,0,'C',1);
	$pdf->cell(20,10,'Logts sociaux',1,0,'C',1);
	$pdf->ln();
	
	$y_ini=$pdf->gety();$tot_lgt=0;$tot_coll=0;$tot_ind=0;$tot_soc=0;
	$an_ini=$rsql2[0]['annee'];
	for ($i=0;$i<count($rsql2);$i++){
		if ($an_ini!=$rsql2[$i]['annee']){
			$yav=$pdf->gety();
			$yht=$yav-$y_ini;
			$pdf->SetFillColor(225);
			$pdf->setxy(5,$y_ini);
			$pdf->cell(10,$yht,$an_ini,1,0,'C',1);
			$an_ini=$rsql2[$i]['annee'];
			$y_ini=$yav;
			$pdf->SetFillColor($rgb[0],$rgb[1],$rgb[2]);
			$pdf->setxy(5,$yav);
			$pdf->cell(10,8,'Totaux',1,0,'C',1);
			$pdf->cell(10,8,'',1,0,'C',1);
			$pdf->cell(60,8,'',1,0,'',1);
			$pdf->cell(20,8,$tot_lgt,1,0,'C',1);
			$pdf->cell(20,8,$tot_coll."/".$tot_ind,1,0,'C',1);
			$pdf->cell(20,8,$tot_soc,1,0,'C',1);
			$tot_lgt=0;$tot_coll=0;$tot_ind=0;$tot_soc=0;
			$pdf->ln();
			$pdf->ln();
			$y_ini=$pdf->gety();
		}
		$pdf->cell(10,8,$rsql2[$i]['num'],1,0,'C',0);
		$pdf->cell(60,8,$rsql2[$i]['nom'],1,0,'',0);
		$pdf->cell(20,8,$rsql2[$i]['nbre_lgt'],1,0,'C',0);$tot_lgt=$tot_lgt+$rsql2[$i]['nbre_lgt'];
		$pdf->cell(20,8,$rsql2[$i]['nbre_coll']."/".$rsql2[$i]['nbre_ind'],1,0,'C',0);$tot_coll=$tot_coll+$rsql2[$i]['nbre_coll'];$tot_ind=$tot_ind+$rsql2[$i]['nbre_ind'];
		$pdf->cell(20,8,$rsql2[$i]['logt_sociaux'],1,0,'C',0);$tot_soc=$tot_soc+$rsql2[$i]['logt_sociaux'];
		$pdf->ln();
	}
	$yav=$pdf->gety();
	$yht=$yav-$y_ini;
	$pdf->setxy(5,$y_ini);
	$pdf->cell(10,$yht,$an_ini,1,0,'C',1);
	$an_ini=$rsql2[$i]['annee'];
	$y_ini=$yav;
	$pdf->setxy(5,$yav);
	$pdf->cell(10,8,'Totaux',1,0,'C',1);
	$pdf->cell(10,8,'',1,0,'C',1);
	$pdf->cell(60,8,'',1,0,'',1);
	$pdf->cell(20,8,$tot_lgt,1,0,'C',1);
	$pdf->cell(20,8,$tot_coll."/".$tot_ind,1,0,'C',1);
	$pdf->cell(20,8,$tot_soc,1,0,'C',1);
}
$pdf->Output();
?>
