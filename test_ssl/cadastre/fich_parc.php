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
session_start();
if (! $_SERVER["PHP_AUTH_USER"] || ! $_SERVER["PHP_AUTH_PW"]){
	header('status: 401 Unauthorized');
	header('HTTP/1.0 401 Unauthorized');
	header("WWW-authenticate: Basic realm=\"Veuillez vous identifier\"");
}else{
    if (isset($_SESSION["code_insee"])){
	include("../connexion/deb.php");
//	$oten="select * from general.utilisateur2 where login='".$PHP_AUTH_USER."' and psw='".$PHP_AUTH_PW."'";
//	$ot=tab_result($pgx,$oten);
//	if (count($ot)>0) {
       if (isset($_GET["commune"])){$commune=$_GET["commune"];}else{$commune=$_SESSION["code_insee"];}
//		$commune=$ot[0]['commune'];
      if ($_GET["obj_keys"]){
			$comma1="select * from cadastre.parcel where ind in ('".str_replace(",","','",$_GET["obj_keys"])."')";// and commune like '".$commune."'";
            //echo $comma1;
		}else{
			if ($_GET["sect"]!="" and $_GET["num"]==""){
				$comma1="select * from cadastre.parcel where ";
				$comma1.="ccosec like '".$_GET["sect"]."'";
			}elseif ($_GET["sect"]!="" and $_GET["num"]!=""){
				$comma1="select * from cadastre.parcel where ";
				$comma1.="par1 like '".$_GET["sect"].$_GET["num"]."'";
			}elseif ($_GET["pnprop"]!=""){
				$comma2="select prop1 from cadastre.propriet where ddenom like '%".$_GET["pnprop"]."%' and commune like '".$commune."'";
				$result=tab_result($pgx,$comma2);
				$comma1="select * from cadastre.parcel where prop1 in('";
				for ($i=0;$i<count($result);$i++){
					$comma1.=$result[$i][0]."','";
				}
				$comma1.="')";
			}
			if ($commune) {
				$comma1.=" and commune like '".$commune."'";
			}
		}
		if ($_GET["sect"]!="" or $_GET["num"]!="" or $_GET["pnprop"]!="" or $_GET["obj_keys"]!=""){
			$rowparc=tab_result($pgx,$comma1);
			$nbr_par=count($rowparc);
			//	$comm=$rowparc['commune'];
		}
		if ($_GET["noprop"]!=""){
			$comma2="select * from cadastre.propriet where ddenom like '%".$_GET["noprop"]."%' ";
			if ($commune) {
				$comma2.=" and commune = '".$commune."'";
			}
			$pprow=tab_result($pgx,$comma2);
			$nbr_par=0;
		//	$comm=$pprow['commune'];
		}
		set_time_limit(120);
		if ($nbr_par==1){
			/* fiche parcelle */
			$par1=$rowparc[0]['ind'];
			header("Location:./fic_parc2.php?ind=$par1&".session_name()."=".session_id());
		}elseif ($nbr_par==0){
			$titre='Recherche cadastrale';
			include('head_cad.php');
			$presult=count($pprow);
			if ($presult==0){
				echo "Crit�res ne correspondant � aucun �l�ments de la table ".$_GET["obj_keys"];
			}elseif ($presult==1){
				/* fiche propri�taire */
				$prop1=$pprow[0]['prop1'];
				include ("fic_prop.php");
			}elseif ($presult>1){
				/* table propri�taire */
				//echo $presult." enregistrements.";
				echo '<table width="100%" align="center">';
				echo '<tr><td>Nom</td><td>Adresse</td></tr>';
			 	for ($j=0;$j<$presult;$j++) { 
				echo "<tr><td rowspan=\"5\"><a href=\"fic_prop.php?prop1=".$pprow[$j]['prop1']."&".session_name()."=".session_id()."\" target=\"_self\">".$pprow[$j]['ddenom']."</a></td>";
				echo "<td>".$pprow[$j]['dlign3']."</td></tr><tr>";
				echo "<td>".$pprow[$j]['dlign4']."</td></tr><tr>";
				echo "<td>".$pprow[$j]['dlign5']."</td></tr><tr>";
				echo "<td>".$pprow[$j]['dlign6']."</td></tr><tr>";
				echo "<td>".$pprow[$j]['ccopay']."</td></tr>";
				} 
				echo '</table>';
			}
		}elseif ($nbr_par>1){
			/* table des parcelle */
			$titre='Recherche cadastrale';
			include('head_cad.php');
			//echo $nbr_par." enregistrements.";
			echo '<table width="100%" align="center">';
			echo '<tr><td>Num�ro</td><td>Contenance</td><td>adresse</td><td>Propri�taire</td><td>Adresse Propri�taire</td></tr>';
			for($k=0;$k<$nbr_par;$k++) { 
 				$rvoie=tab_result($pgx,"select nom_voie from cadastre.voies where code_voie='".$rowparc[$k]['ccoriv']."' and commune ='".$rowparc[$k]['commune']."';" );
				$rowvoie=$rvoie[0][0];
 				$rprop=tab_result($pgx,"select ddenom,dlign3,dlign4,dlign5,dlign6 from cadastre.propriet where prop1='".$rowparc[$k]['prop1']."' and gdesip='1' and commune='".$rowparc[$k]['commune']."';") ;
				echo '<tr>';
					echo '<td><a href="fic_parc2.php?ind='.$rowparc[$k]['ind'].'&'.session_name().'='.session_id().'" target="_self">'.$rowparc[$k]['par1'].'</a></td>';
					echo '<td>'.$rowparc[$k]['dcntpa'].'</td>';
					echo '<td>'.$rowparc[$k]['dnuvoi'].$rowparc[$k]['dindic'].", ".$rowvoie.'</td>';
					echo '<td>'.$rprop[0]['ddenom'].'</td>';
					echo '<td>'.$rprop[0]['dlign3'].$rprop[0]['dlign4'].$rprop[0]['dlign5'].$rprop[0]['dlign6'].'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		include('pied_cad.php');
	}
}
?>
