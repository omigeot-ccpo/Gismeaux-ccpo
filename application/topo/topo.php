<?php
session_start();
include('../connexion/deb.php');
if ($_GET["obj_keys"]){
   $q1="select * from public.geometre_ssql where spa_id ='".$_GET["obj_keys"]."'";
   $r1=tab_result($pgx,$q1);
   $dess="./".$_SESSION['code_insee']."/dwf/".strtolower($r1[0]['local1'])."/".$r1[0]['fichier'];
} ?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
		<title>Plan topographique</title>
	</head>

	<body>
 		<p onclick="window.open('charge_topo.php?dess=<?php echo $dess.'.dwg' ?>','','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbar=no,resizable=no,copyhistory=no,width=250,height=250,left=100,top=100,screenX=0,screenY=0');">Charger le fichier DWG </p> <br> <br>
<div>  <object id = "viewer"

classid = "clsid:A662DA7E-CCB7-4743-B71A-D817F6D575DF"

CODEBASE="https://126.2.0.101/prog/DwfViewerSetup.cab"
border = "1"
width = "90%"
height = "90%">

<param name = "Src" value="<?php echo $dess.'.dwf' ?>">

</object> </div>

	</body>

</html>
