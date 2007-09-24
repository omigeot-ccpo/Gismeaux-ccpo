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
    require_once('zip.lib.php');
    // nom du fichier à ajouter dans l'archive
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
// création d'un objet 'zipfile'
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
    // entêtes HTTP
    header('Content-Type: application/x-zip');
    // force le téléchargement
    header('Content-Disposition: inline; filename=carte_svg.zip');
    // envoi du fichier au navigateur
    echo $archive;
?>
