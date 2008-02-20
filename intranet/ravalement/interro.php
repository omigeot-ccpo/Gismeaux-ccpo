<html>
<body>
<?php
include("../connexion/deb.php");
$sql="SELECT * from geotest.ravalement where gid IN(".$_GET['obj_keys'].")";
$col=tab_result($pgx,$sql);
$requete="select a.identifian from cadastre.parcelle as a ,geotest.ravalement as b where Distance(a.the_geom,centroid(b.the_geom))=0 and b.gid=".$_GET['obj_keys'];
$coul=tab_result($pgx,$requete);
?>
<form method="get" action="requete.php">
<p>Etat de la facade:<select name="type">
      <option value="a" <?php if($col[0]["etat"]=="a"){echo "selected";}?> >bon état</option>
      <option value="b" <?php if($col[0]["etat"]=="b"){echo "selected";}?> >état moyen</option>
      <option value="c" <?php if($col[0]["etat"]=="c"){echo "selected";}?> >mauvais état</option>
	  </select>
</p>
 <p>Facade à surveiller:<input name="surveiller" type="checkbox" value="1"" size="10" <?php if($col[0]["surveiller"]=="1"){echo "checked";}?> >
 </p>
<p>Date:
    <input name="date" type="text" value=" <?php echo $col[0]["date"]; ?> ">
</p>
<p>
observation:
</p>
<p>
<textarea name="observation" cols="30" rows="5" ><?php echo $col[0]["observation"]; ?></textarea>
</p> 
<input name="obj_keys" type="hidden" value="<?php echo $coul[0]["identifian"];?>">
<input name="gid" type="hidden" value="<?php echo $_GET['obj_keys'];?>">
<input name="Editer" type="submit" value="Editer">
<?php
$sql="SELECT * from geotest.photo_ravalement where id_ravalement IN(".$_GET['obj_keys'].")";
$col=tab_result($pgx,$sql);
for($i=0;$i<count($col);$i++)
{
echo "<p>";
echo "<a href=\"photo/".$col[$i]['id_photo'].".JPG\"<input name='".$i."' type='image' src='photo/vignette/".$col[$i]['id_photo'].".JPG'></a>";
echo "</p>";
}
?>

</body>
</html>
