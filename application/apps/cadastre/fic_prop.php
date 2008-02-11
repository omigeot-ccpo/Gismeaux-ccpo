<?php 
if (! $PHP_AUTH_USER || ! $PHP_AUTH_PW){
	header('status: 401 Unauthorized');
	header('HTTP/1.0 401 Unauthorized');
	header("WWW-authenticate: Basic realm=\"Veuillez vous identifier\"");
}else{
	include("../connexion/deb.php");
	$oten="select * from general.utilisateur2 inner join general.apli_util2 on general.utilisateur2.utcleunik=general.apli_util2.utcleunik where login='".$PHP_AUTH_USER."' and psw='".$PHP_AUTH_PW."' and id_appli='2'";
	$ot=tab_result($pgx,$oten);
	if (count($ot)>0) {
		//$commune=$ot[0]['commune'];
        if (!isset($commune)){$commune=$_SESSION["code_insee"];}
        $titre='Recherche cadastrale - Fiche propriétaire';
		include('./head_cad.php');
		$fprop_query = "SELECT * FROM cadastre.propriet WHERE prop1 = '".$prop1."' and commune = '".$commune."'";
		$fprop_row = tab_result($pgx,$fprop_query);
		echo '<table width="100%" align="center"><tr class="tt1">';
		echo '<td colspan="4" align="center">Fiche propriétaire</td></tr>';
		for($f=0;$f<count($fprop_row);$f++) {
			echo '<tr class="tt4"><td>N°:&nbsp;'.$fprop_row[$f]['dnulp'].'</td>';
			echo '<td>';
			if ($fprop_row[$f]['ccodro'] == "P"){ echo "Propriétaire";
			}elseif ($fprop_row[$f]['ccodro'] == "U"){ echo "Usufruitier";
			}elseif ($fprop_row[$f]['ccodro'] == "N"){ echo "Nu-propriétaire";
			}elseif ($fprop_row[$f]['ccodro'] == "B"){ echo "Bailleur à construction";
			}elseif ($fprop_row[$f]['ccodro'] == "R"){ echo "Preneur à construction";
			}elseif ($fprop_row[$f]['ccodro'] == "F"){ echo "Foncier";
			}elseif ($fprop_row[$f]['ccodro'] == "T"){ echo "Tenuyer";
			}elseif ($fprop_row[$f]['ccodro'] == "D"){ echo "Domanier";
			}elseif ($fprop_row[$f]['ccodro'] == "V"){ echo "Bailleur d'un bail à réhabilitation";
			}elseif ($fprop_row[$f]['ccodro'] == "W"){ echo "Preneur d'un bail à réhabilitation";
			}elseif ($fprop_row[$f]['ccodro'] == "A"){ echo "Locataire-attributaire";
			}elseif ($fprop_row[$f]['ccodro'] == "E"){ echo "Emphytéote";
			}elseif ($fprop_row[$f]['ccodro'] == "K"){ echo "Antichrésiste";
			}elseif ($fprop_row[$f]['ccodro'] == "L"){ echo "Fonctionnaire logé";
			}elseif ($fprop_row[$f]['ccodro'] == "G"){ echo "Gérant, mandataire, gestionnaire";
			}elseif ($fprop_row[$f]['ccodro'] == "H"){ echo "Associé d'une transparence fiscale";
			}elseif ($fprop_row[$f]['ccodro'] == "S"){ echo "Syndic de copropriété";}
			echo '&nbsp;';
			if ($fprop_row[$f]['ccodem'] == "C"){ echo "Un des copropriétaire";
			}elseif ($fprop_row[$f]['ccodem'] == "S"){ echo "Succession de";
			}elseif ($fprop_row[$f]['ccodem'] == "V"){ echo "La veuve ou les héritiers de ";
			}elseif ($fprop_row[$f]['ccodem'] == "I"){ echo "Indivision simple";
			}elseif ($fprop_row[$f]['ccodem'] == "L"){ echo "Litige";}
			echo '</td><td>';
			if ($fprop_row[$f]['dnatpr'] == "DOM"){ echo "Propriétaire occupant";
			}elseif ($fprop_row[$f]['dnatpr'] == "FNL"){ echo "Fonctionnaire logé";
			}elseif ($fprop_row[$f]['dnatpr'] == "CLL"){ echo "Collectivité local";
			}elseif ($fprop_row[$f]['dnatpr'] == "HLM"){ echo "Office HLM";
			}elseif ($fprop_row[$f]['dnatpr'] == "CAA"){ echo "Caisse assurance agricole";
			}elseif ($fprop_row[$f]['dnatpr'] == "TGV"){ echo "SNCF";}
			echo '</td><td>';
			if ($fprop_row[$f]['ccogrm'] == "0"){ echo "Personne morale non remarquable";
			}elseif ($fprop_row[$f]['ccogrm'] == "1"){ echo "Etat";
			}elseif ($fprop_row[$f]['ccogrm'] == "2"){ echo "Région";
			}elseif ($fprop_row[$f]['ccogrm'] == "3"){ echo "Département";
			}elseif ($fprop_row[$f]['ccogrm'] == "4"){ echo "Commune";
			}elseif ($fprop_row[$f]['ccogrm'] == "5"){ echo "Office HLM";
			}elseif ($fprop_row[$f]['ccogrm'] == "6"){ echo "Société d'économie mixte";
			}elseif ($fprop_row[$f]['ccogrm'] == "7"){ echo "Copropriétaire";
			}elseif ($fprop_row[$f]['ccogrm'] == "8"){ echo "Associé de transparence fiscale";}
			echo '</td></tr><tr class="tt3"><td>Nom:</td>';
			echo '<td colspan="3">'.$fprop_row[$f]['ddenom'].'</td>';
			echo '</tr><tr><td rowspan=4>Adresse:</td>';
			echo '<td colspan="3">'. $fprop_row[$f]['dlign3'].'</td>';
			echo '</tr><tr><td colspan="3">'.$fprop_row[$f]['dlign4'].'</td></tr>';
			echo '<tr><td colspan="3">'.$fprop_row[$f]['dlign5'].'</td></tr>';
			echo '<tr><td colspan="3">'.$fprop_row[$f]['dlign6'].'</td></tr>';
			echo '<tr><td>Né(e) le:&nbsp;'.$fprop_row[$f]['jdatnss'].'</td>';
			echo '<td>A:&nbsp;'.$fprop_row[$f]['dldnss'].'</td>';
			echo '<td colspan=2>'.$fprop_row[$f]['epxnee']." ".$fprop_row[$f]['dnomcp']." ".$fprop_row[$f]['dprncp'].'</td></tr>';
		}
		echo '</table>';
		include('pied_cad.php');
	}
}
?>
