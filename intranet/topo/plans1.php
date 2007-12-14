<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Document sans titre</title>
</head>

<body>
<?php
//session_start();
include('../connexion/deb.php');
$q="select * from public.geomet where intersects(the_geom,GeometryFromtext('POLYGON((".$polygo."))',-1))";
$resultat = tab_result($pgx, $q);
if (count($resultat)==1){
    $ch= "location:http://mapsig/dwf/topo.cfm?dess=".$resultat[0]['local1']."/".$resultat[$j]['fichier'].".dwf";
    header($ch);
}else{
      echo '<table><tr><th>Fichier</th><th>Date</th><th>Assainissement</th><th>AEP</th>';
      echo '<th>EP</th><th>Recolement</th><th>Géomètre</th></tr>';
      for ($j=0;$j<count($resultat);$j++){
	     echo '<tr><td><a href="http://mapsig/dwf/topo.cfm?dess='.$resultat[$j]['local1'].'/'.$resultat[$j]['fichier'].'.dwf">'.$resultat[$j]['fichier'].'.dwg</a></td>';
		 echo '<td>'.$resultat[$j]['dat'].'</td>';
		 echo '<td>'.$resultat[$j]['ass'].'</td>';
		 echo '<td>'.$resultat[$j]['aep'].'</td>';
		 echo '<td>'.$resultat[$j]['ep'].'</td>';
		 echo '<td>'.$resultat[$j]['recol'].'</td>' ;
		 echo '<td>'.$resultat[$j]['geometre'].'</td></tr>' ;
      }
}
?>
</body>
</html>
