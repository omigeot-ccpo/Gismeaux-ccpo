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
if (! $PHP_AUTH_USER || ! $PHP_AUTH_PW){
	header('status: 401 Unauthorized');
	header('HTTP/1.0 401 Unauthorized');
	header("WWW-authenticate: Basic realm=\"Veuillez vous identifier\"");
}else{
    if (isset($_SESSION["code_insee"])){
	include("../connexion/deb.php");
//	$oten="select * from general.utilisateur2 inner join general.apli_util2 on general.utilisateur2.utcleunik=general.apli_util2.utcleunik where login='".$PHP_AUTH_USER."' and psw='".$PHP_AUTH_PW."' and id_appli='2'";
//	$ot=tab_result($pgx,$oten);
//	if (count($ot)>0) {
//		$commune=$ot[0]['commune'];
        $commune=$_SESSION["code_insee"];
		$titre='Recherche cadastrale - Fiche parcelle';
        if (isset($par1)){$ind=substr($_SESSION["code_insee"],3)."000".substr($par1,0,2).str_pad(substr($par1,2),4,'0',STR_PAD_LEFT);}
		include('./head_cad.php');
		/* Lecture de la fiche parcelle */
		$query_Recordset1 = "SELECT ccosec, dnupla, dcntpa, jdatat, gparbat, dnuvoi, dindic, ccoriv, prop1, commune FROM cadastre.parcel WHERE ind = '";
		$query_Recordset1.= $ind."';";
		$row_Recordset1 = tab_result($pgx,$query_Recordset1);
		/* Recherche des propri�taires */
		$prop=$row_Recordset1[0]['prop1'];
        $insee=$row_Recordset1[0]['commune'];
		$query_Recordset2 = "SELECT cgroup, ddenom, dlign3, dlign4, dlign5, dlign6, ccopay FROM cadastre.propriet WHERE prop1 = '";
		$query_Recordset2.= $prop."' and commune='".$insee."' order by dnulp asc;";
		$row_Recordset2 = tab_result($pgx,$query_Recordset2);
		/* Si gparbat=1 alors terrain batie */
		if ($row_Recordset1[0]['gparbat']==1){
			$ccos=$row_Recordset1[0]['ccosec'];
			$dnup=$row_Recordset1[0]['dnupla'];
			$query_Recordset3 = "SELECT invar, dnubat, desca, dniv, dpor FROM cadastre.batidgi where ccosec='".$ccos."' and dnupla = '".$dnup."' and commune='".$insee."'";
			$row_Recordset3 = tab_result($pgx,$query_Recordset3);
		}
		/* Recherche du nom de la voie */
		$cod_voie=$row_Recordset1[0]['ccoriv'];
		$query_voie="SELECT nom_voie from cadastre.voies where code_voie like '".$cod_voie."' and commune='".$insee."';";
		$row_voie=tab_result($pgx,$query_voie);
		echo '<table width="100%"  align="center">';
		if ($row_Recordset1[0]['gparbat']==1){
			echo '<tr><td colspan="4" align="center" class="tt1">Fiche d\'une parcelle b�tie ';
		}else{
			echo '<tr><td colspan="4" align="center" class="tt1">Fiche d\'une parcelle non b�tie '; 
		}
		echo '</td></tr><tr><td>Section</td>';
		echo '<td>'.$row_Recordset1[0]['ccosec'].'</td><td>Numero</td>';
		echo '<td>'.$row_Recordset1[0]['dnupla'].'</td></tr><tr><td>Adresse</td>';
		echo '<td colspan="3">'.$row_Recordset1[0]['dnuvoi'].$row_Recordset1[0]['dindic'].', '.$row_voie[0]['nom_voie'].'</td>';
		echo '</tr><tr><td>Contenance</td><td>'.$row_Recordset1[0]['dcntpa'].'</td>';
		echo '<td>Date de l\'acte</td><td>'.$row_Recordset1[0]['jdatat'].'</td></tr><tr>';
		echo '<td colspan="4" align="center" class="tt2">Propri�taire</td></tr>';
		for($q=0;$q<count($row_Recordset2);$q++) {
			echo '<tr><td>Nom</td><td colspan="3">'.$row_Recordset2[$q]['ddenom'].'</td>';
			echo '</tr><tr><td rowspan="5">Adresse</td>';
			echo '<td colspan="3">'.$row_Recordset2[$q]['dlign3'].'</td>';
			echo '</tr><tr><td colspan="3">'.$row_Recordset2[$q]['dlign4'].'</td>';
			echo '</tr><tr><td colspan="3">'.$row_Recordset2[$q]['dlign5'].'</td>';
			echo '</tr><tr><td colspan="3">'.$row_Recordset2[$q]['dlign6'].'</td>';
			echo '</tr><tr><td colspan="3">'.$row_Recordset2[$q]['ccopay'].'</td></tr>';
		} 
		if ($row_Recordset1[0]['gparbat']==1){
			echo '<tr><td colspan="4" align="center" class="tt2">';
			if (substr($row_Recordset1[0]['prop1'],0,1)=='*') {
				echo "Co-propri�aire"; 
			}else{
				echo "Locaux"; 
			} 
			echo '</td></tr>';
			for($s=0;$s<count($row_Recordset3);$s++) { 
				echo '<tr class="tt4"><td>';
				if ($_SESSION["droit"]=='AD'){
                     echo '<a href="fic_bat.php?invar1='.$row_Recordset3[$s]['invar'].'">Batiment : </a>';
                }else{
                    echo 'Batiment : ';
                }
				echo $row_Recordset3[$s]['dnubat'].'</td>';
				echo '<td>Escalier : '.$row_Recordset3[$s]['desca'].'</td>';
				echo '<td>Niveau : '.$row_Recordset3[$s]['dniv'].'</td>';
				echo '<td>Local : '.$row_Recordset3[$s]['dpor'].'</td></tr>';
				if (substr($row_Recordset1[0]['prop1'],0,1)=='*') {
					$req="select dnupro from cadastre.b_desdgi where invar = '".$row_Recordset3[$s]['invar']."';";
					$lrow=tab_result($pgx,$req);
					$preq="select * from cadastre.propriet where prop1='".$lrow[0]['dnupro']."' order by dnulp asc;";
					$bprow=tab_result($pgx,$preq);
					for ($d=0;$d<count($bprow);$d++) { 
						echo '<tr><td>Nom</td><td colspan="3">';
						if ($bprow[$d]['gtoper']==1){ echo $bprow[$d]['dqualp'];}else{echo $bprow[$d]['dforme'];}
						echo " ".$bprow[$d]['ddenom'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong> ';
						if ($bprow[$d]['ccodro'] == "P"){ echo "Propri�taire";
						}elseif ($bprow[$d]['ccodro'] == "U"){ echo "Usufruitier";
						}elseif ($bprow[$d]['ccodro'] == "N"){ echo "Nu-propri�taire";
						}elseif ($bprow[$d]['ccodro'] == "B"){ echo "Bailleur � construction";
						}elseif ($bprow[$d]['ccodro'] == "R"){ echo "Preneur � construction";
						}elseif ($bprow[$d]['ccodro'] == "F"){ echo "Foncier";
						}elseif ($bprow[$d]['ccodro'] == "T"){ echo "Tenuyer";
						}elseif ($bprow[$d]['ccodro'] == "D"){ echo "Domanier";
						}elseif ($bprow[$d]['ccodro'] == "V"){ echo "Bailleur d'un bail � r�habilitation";
						}elseif ($bprow[$d]['ccodro'] == "W"){ echo "Preneur d'un bail � r�habilitation";
						}elseif ($bprow[$d]['ccodro'] == "A"){ echo "Locataire-attributaire";
						}elseif ($bprow[$d]['ccodro'] == "E"){ echo "Emphyt�ote";
						}elseif ($bprow[$d]['ccodro'] == "K"){ echo "Antichr�siste";
						}elseif ($bprow[$d]['ccodro'] == "L"){ echo "Fonctionnaire log�";
						}elseif ($bprow[$d]['ccodro'] == "G"){ echo "G�rant, mandataire, gestionnaire";
						}elseif ($bprow[$d]['ccodro'] == "H"){ echo "Associ� d'une transparence fiscale";
						}elseif ($bprow[$d]['ccodro'] == "S"){ echo "Syndic de copropri�t�";} ; 
						echo '</strong></td></tr><tr><td rowspan="5">Adresse</td>';
						echo '<td colspan="3">'.$bprow[$d]['dlign3'].'</td></tr><tr>';
						echo '<td colspan="3">'.$bprow[$d]['dlign4'].'</td></tr><tr>';
						echo '<td colspan="3">'.$bprow[$d]['dlign5'].'</td></tr><tr>';
						echo '<td colspan="3">'.$bprow[$d]['dlign6'].'</td></tr><tr>';
						echo '<td colspan="3">'.$bprow[$d]['ccopay'].'</td></tr>';
					}
				}
			}
		}
		echo '</table>';
		include('pied_cad.php');
	}
}?>


