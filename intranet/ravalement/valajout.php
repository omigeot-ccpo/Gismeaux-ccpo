<?php
include("../connexion/deb.php");
$sql="insert into geotest.ravalement (date,observation,etat,the_geom,surveiller) values ('".$_POST['date']."','".$_POST['observation']."','".$_POST['type']."','".$_POST['polygo']."','".$_POST['surveiller']."')";
pg_exec($pgx,$sql);
$requete="select last_value from geotest.ravalement_gid_seq";
$col=tab_result($pgx,$requete);
$id_ravalement=$col[0]['last_value'];
$requete="select last_value from geotest.photo_ravalement_id_photo_seq";
$col=tab_result($pgx,$requete);
$lastvaleur=$col[0]['last_value'];
    for($i=0;$i<5;$i++)
	{
		if( is_uploaded_file($_FILES['photo']['tmp_name'][$i]) )
    	{
		$lastvaleur=$lastvaleur+1;
     	$sql="insert into geotest.photo_ravalement (id_ravalement) values (".$id_ravalement.")";
		pg_exec($pgx,$sql);
		$extention=explode(".",$_FILES['photo']['name'][$i]);
		move_uploaded_file($_FILES['photo']['tmp_name'][$i], "./photo/".$lastvaleur.".".$extention[1]);

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
		if($extention[1]=="png")
		{
		unlink("./photo/".$lastvaleur.".png");
		}


    	}
		else {break;}
}
?>
<html>
<body onLoad="close()">
</body>
</html>

