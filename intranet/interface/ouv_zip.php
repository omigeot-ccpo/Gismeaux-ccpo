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
    require_once('zip.lib.php');
    // nom du fichier � ajouter dans l'archive
$fileattente = '../tmp/'.$_GET['nom'].'.ok';
    $filename = '../tmp/'.$_GET['nom'].'.svg';
	$filename1="";
	//$filename1 ="../tmp/".$_SESSION['image'];
	$fich='nok';
	$fich1='nok';
	// attente construction fichier
	if($_GET['nom']!="")
	{
	while($fich!='ok')
	{
	if (file_exists($fileattente)) 
	{
	$fich='ok';
	}
	}}
	if($_GET['image']=="ok")
	{
	$filename1= '../tmp/'.$_GET['nom'].'.tmp';
	while($fich1!='ok')
	{
	if (file_exists($filename1)) 
	{
	$fp = fopen ('../tmp/'.$_GET['nom'].'.tmp', 'r');
    $conte = fread($fp, filesize('../tmp/'.$_GET['nom'].'.tmp'));
    fclose ($fp);
	$conte1=explode('.',$conte);
	$_GET['image']=$conte1[0];
	$fich1='ok';
	}
	}}
// cr�ation d'un objet 'zipfile'
    $zip = new zipfile();
    // contenu du fichier
	if ($_GET['nom']!="") 
	{
	$fp = fopen ($filename, 'r');
    $content = fread($fp, filesize($filename));
    fclose ($fp);
	// ajout du fichier dans cet objet
    $zip->addfile($content,'carte.svg');
	}
       if ($filename1!="") 
	{
	$fp = fopen ("../tmp/".$_GET['image'].".jpg", 'r');
    $content1 = fread($fp, filesize("../tmp/".$_GET['image'].".jpg"));
    fclose ($fp);
    $zip->addfile($content1,$_GET['image'].".jpg");
	}
    // production de l'archive' Zip
    $archive = $zip->file();
    // ent�tes HTTP
    header('Content-Type: application/x-zip');
    // force le t�l�chargement
    header('Content-Disposition: inline; filename=carte_svg.zip');
    // envoi du fichier au navigateur
    echo $archive;
?>
