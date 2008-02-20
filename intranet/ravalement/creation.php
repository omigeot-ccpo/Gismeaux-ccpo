	<?php 
include("../connexion/deb.php");	
if($_GET['polygo']!="0 0,1 1")
{

$pol= explode(",",$_GET['polygo']);
//if (count($pol)!=2)
//{
  $polygo=$pol[0];
  /*for ($i=1;$i<count($pol)-1;$i++)
  {
      $polygo.=",".$pol[$i];
  }*/
//}
$requete="SELECT the_geom FROM cadastre.batiment 
WHERE
       Distance(GeometryFromtext('POINT(".$polygo.")',-1),batiment.the_geom)=0
";
$col=tab_result($pgx,$requete);
if($col[0]['the_geom']=="")
{
$requete="SELECT the_geom FROM bd_topo.batiment 
WHERE
       Distance(GeometryFromtext('POINT(".$polygo.")',-1),batiment.the_geom)=0
";
$col=tab_result($pgx,$requete);
$polygo=$col[0]['the_geom'];
}
else
{
$polygo=$col[0]['the_geom'];
}
}
	
	?>
<html>
<body>

<form method="post" action="valajout.php" enctype="multipart/form-data">
 <p>Etat de la facade:<select name="type">
      <option value="a" >bon état</option>
      <option value="b" >état moyen</option>
      <option value="c" >mauvais état</option>
	  </select>
</p>
 <p>Facade à surveiller:<input name="surveiller" type="checkbox" value="1"></p>
<p>Date:
    <input name="date" type="text" value="<?php echo date("d").'/'.date("m").'/'.date("Y"); ?>" size="10">
</p>
<p>
Photos:</p>
<p><INPUT name="photo[]" type=file></p>
<p><INPUT name="photo[]" type=file> </p>
<p><INPUT name="photo[]" type=file> </p>
<p><INPUT name="photo[]" type=file></p> 
<p><INPUT name="photo[]" type=file></p>
<p>
observation:
</p>
<p>
<textarea name="observation" cols="30" rows="5"></textarea>
</p>   
<input name="polygo" type="hidden" value="<?php echo $polygo;?>">
<input name="Ajouter" type="submit" value="Ajouter">
</form>
</body>
</html>
