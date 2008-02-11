<?php
session_start();
include('../connexion/deb.php');

//Vérifier l'existance d'un fichier de même nom dans le répertoire
$filname= '/home/sig/intranet/topo/'.$_SESSION['code_insee'].'/dwf/'.$_POST["boite"].$_POST["$disk"].'/'.$_POST["fich"].'.dwg';
if ( @opendir('/home/sig/intranet/topo/'.$_SESSION['code_insee'].'/dwf/'.$_POST["boite"].$_POST["disk"])){
    $i=1;
    while ( file_exists($filname)){
         $filname= '/home/sig/intranet/topo/'.$_SESSION['code_insee'].'/dwf/'.$_POST["boite"].$_POST["disk"].'/'.$_POST["fich"].'_'.$i.'.dwg';$i++;
    }
}else{
    if (! @opendir('/home/sig/intranet/topo/'.$_SESSION['code_insee'])){mkdir('/home/sig/intranet/topo/'.$_SESSION['code_insee'],0775);}
    if (! @opendir('/home/sig/intranet/topo/'.$_SESSION['code_insee'].'/dwf/')){mkdir('/home/sig/intranet/topo/'.$_SESSION['code_insee'].'/dwf/',0775);}
    mkdir('/home/sig/intranet/topo/'.$_SESSION['code_insee'].'/dwf/'.$_POST["boite"].$_POST["disk"],0775);
}
$filname1=substr($filname,0,-4).'.dwf';

//Insérer dans geometre_ssql les données en réglant le spa_id sur nextval
$q1="begin;insert into public.geometre_ssql (boite,disquet,fichier,service,local1,dat,ass,aep,ep,recol,geometre) values('";
$q1.=$_POST["boite"]."','".$_POST["disk"]."','".$_POST["fich"]."','".$_POST["servi"]."','".$_POST["boite"].$_POST["disk"]."','".$_POST["plan_dat"]."','".$_POST["ass"]."','".$_POST["aep"]."','".$_POST["ep"]."','".$_POST["recol"]."','".$_POST["geometre"]."');";

//Inserer dans geometre les données en réglant l'id sur currentval
$q1.="insert into public.geometre(the_geom,code_insee) values(GeometryFromtext('POLYGON((".$_POST["polygo"]."))',-1),'".$_SESSION['code_insee']."');";
$q1.="commit;";
pg_exec($pgx,$q1);

//Charger les fichiers distants
if (is_uploaded_file($_POST["fich_ins"])){copy($_POST["fich_ins"], $_POST["filname"]);}
if (is_uploaded_file($_POST["dwf_ins"])){copy($_POST["dwf_ins"], $_POST["filname1"]);}
?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN"> 
<html>
<head>
       <title>Title here!</title>
</head>
<body>

</body>
</html>
