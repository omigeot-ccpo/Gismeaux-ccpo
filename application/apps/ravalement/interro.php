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
if ($_POST["act"]=="edit"){
//header("Location:../ravalement/test.php?obj_keys=".$_POST["obj_keys"]."&gid=".$_POST["gid"]."&type=".$_POST["type"]."&surveiller=".$_POST["surveiller"]."&observation=".$_POST["observation"]."&date=".$_POST["date"]."&date_raval=".$_POST["date_raval"]);
header("Location:test.php?obj_keys=".$_POST["obj_keys"]."&gid=".$_POST["gid"]);
}
if($_POST["act"]=="supp")
{
$sql="delete from urba.ravalement where gid=".$_POST["gid"];
$DB->tab_result($sql);
$sql="select id_photo from urba.photo_ravalement where id_ravalement=".$_POST["gid"];
$col=$DB->tab_result($sql);
for($i=0;$i<count($col);$i++)
{

exec("rm ".$fs_root."ravalement/photo/".$col[$i]['id_photo'].".JPG ");
exec("rm ".$fs_root."ravalement/photo/vignette/".$col[$i]['id_photo'].".JPG ");
}
$sql="delete from urba.photo_ravalement where id_ravalement=".$_POST["gid"];
$DB->tab_result($sql);
echo "<html>";
echo "<body onload='close()'>";
echo "</body>";
echo "</html>";
//header("Location:./supp.php?gid=".$_GET["gid"]);
}
if($_POST["act"]=="suppphoto")
{
$sql="delete from urba.photo_ravalement where id_photo=".$_POST["pho"];
$DB->tab_result($sql);
exec("rm ".$fs_root."ravalement/photo/".$_POST["pho"].".JPG ");
exec("rm ".$fs_root."ravalement/photo/vignette/".$_POST["pho"].".JPG ");
$_GET['obj_keys']=$_POST['gid'];
}
if($_POST["act"]=="mod")
{
$sql="update urba.ravalement set etat='".$_POST["type"]."',date='".$_POST["date"]."',observation='".$_POST["observation"]."',surveiller='".$_POST["surveiller"]."',annee_dernier_ravalement='".$_POST["date_raval"]."' where gid=".$_POST["gid"]." ";
$DB->tab_result($sql);
$_GET['obj_keys']=$_POST['gid'];
}
if($_POST["act"]=="ajout")
{
$requete="select last_value from urba.photo_ravalement_id_photo_seq";
$col=$DB->tab_result($requete);
$lastvaleur=$col[0]['last_value'];
if( is_uploaded_file($_FILES['phot']['tmp_name'][0]) )
    	{
		$lastvaleur=$lastvaleur+1;
     	$sql="insert into urba.photo_ravalement (id_ravalement) values (".$_POST["gid"].")";
		$DB->tab_result($sql);
		$extention=explode(".",$_FILES['phot']['name'][0]);
		move_uploaded_file($_FILES['phot']['tmp_name'][0], "./photo/".$lastvaleur.".".$extention[1]);

		$tableau = GetImageSize("./photo/".$lastvaleur.".".$extention[1]);
		$width = 400;
		$height = 400;
		$width_vignette = 100;
		$height_vignette = 100;
		$ratio_orig = $tableau[0]/$tableau[1];

		if ($width_vignette/$height_vignette > $ratio_orig) {
   		$width_vignette = $height_vignette*$ratio_orig;
		} else {
   		$height_vignette = $width_vignette/$ratio_orig;
		}
		if ($width/$height > $ratio_orig) {
   		$width = $height*$ratio_orig;
		} else {
   		$height = $width/$ratio_orig;
		}
		$image_p = imagecreatetruecolor($width, $height);
		$image_p_vignette = imagecreatetruecolor($width_vignette, $height_vignette);
		if($extention[1]=="jpg" ||$extention[1]=="JPG" )
		{
		$image = imagecreatefromjpeg("./photo/".$lastvaleur.".".$extention[1]);
		}
		if($extention[1]=="png" || $extention[1]=="PNG")
		{
		$image = imagecreatefrompng("./photo/".$lastvaleur.".".$extention[1]);
		}
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $tableau[0], $tableau[1]);
		imagejpeg($image_p,"./photo/".$lastvaleur.".JPG", 100);
		imagecopyresampled($image_p_vignette, $image, 0, 0, 0, 0, $width_vignette, $height_vignette, $tableau[0], $tableau[1]);
		imagejpeg($image_p_vignette,"./photo/vignette/".$lastvaleur.".JPG", 100);
		if($extention[1]=="jpg")
		{
		unlink("./photo/".$lastvaleur.".jpg");
		}
		if($extention[1]=="png")
		{
		unlink("./photo/".$lastvaleur.".png");
		}


    	}
		else {
		echo "tata";
		//break;
		}
$_GET['obj_keys']=$_POST['gid'];		
}
?>
<html>
<body>
<?php
$sql="SELECT * from urba.ravalement where gid IN(".$_GET['obj_keys'].")";
$col=$DB->tab_result($sql);
$requete="select a.identifian from cadastre.parcelle as a ,urba.ravalement as b where Distance(a.the_geom,centroid(b.the_geom))=0 and b.gid=".$_GET['obj_keys'];
$coul=$DB->tab_result($requete);
?>

<form method="post" action="interro.php" enctype="multipart/form-data">
<input type="hidden" id="act" name="act" value="">
<input type="hidden" id="pho" name="pho" value="">
<p>Etat de la facade:<select name="type">
      <option value="a" <?php if($col[0]["etat"]=="a"){echo "selected";}?> >bon �tat</option>
      <option value="b" <?php if($col[0]["etat"]=="b"){echo "selected";}?> >�tat moyen</option>
      <option value="c" <?php if($col[0]["etat"]=="c"){echo "selected";}?> >mauvais �tat</option>
	  </select>
</p>
 <p>Facade � surveiller:<input name="surveiller" type="checkbox" value="1"" size="10" <?php if($col[0]["surveiller"]=="1"){echo "checked";}?> >
 </p>
<p>Date:
    <input name="date" type="text" value=" <?php echo $col[0]["date"]; ?> ">
</p>
<p>Ann&eacute;e dernier ravalement:
    <input name="date_raval" type="text" size="2" value=" <?php echo $col[0]["annee_dernier_ravalement"]; ?> ">
</p>
<p>
observation:
</p>
<p>
<textarea name="observation" cols="30" rows="5" ><?php echo $col[0]["observation"]; ?></textarea>
</p> 
<input name="obj_keys" type="hidden" value="<?php echo $coul[0]["identifian"];?>">
<input name="gid" type="hidden" value="<?php echo $_GET['obj_keys'];?>">
<input name="Editer" type="button" value="Editer" onClick="document.getElementById('act').value='edit';submit()"><input name="supp" type="button" value="supprimer" onClick="document.getElementById('act').value='supp';submit()"><input name="mod" type="button" value="modifier" onClick="document.getElementById('act').value='mod';submit()">
<?php
$sql="SELECT * from urba.photo_ravalement where id_ravalement IN(".$_GET['obj_keys'].")";
$col=$DB->tab_result($sql);
for($i=0;$i<count($col);$i++)
{
echo "<p>";
echo "<a href=\"./photo/".$col[$i]['id_photo'].".JPG\"><img name='photo".$i."' src='./photo/vignette/".$col[$i]['id_photo'].".JPG' alt=''></a><input name=\"suppho\" type=\"button\" value=\"supprimer\" onClick=\"document.getElementById('pho').value='".$col[$i]['id_photo']."';document.getElementById('act').value='suppphoto';submit()\">";
echo "</p>";
}
?>
<p>
Ajouter une photo:</p>
<p><INPUT name="phot[]" type=file></p><input name="Ajouter" type="button" value="Ajouter" onClick="document.getElementById('act').value='ajout';submit()"></form>
</body>
</html>
