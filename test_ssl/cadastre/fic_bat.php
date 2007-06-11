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
if (! $PHP_AUTH_USER || ! $PHP_AUTH_PW){
	header('status: 401 Unauthorized');
	header('HTTP/1.0 401 Unauthorized');
	header("WWW-authenticate: Basic realm=\"Veuillez vous identifier\"");
}else{
	include("../connexion/deb.php");
	$oten="select * from general.utilisateur2 inner join general.apli_util2 on general.utilisateur2.utcleunik=general.apli_util2.utcleunik where login='".$PHP_AUTH_USER."' and psw='".$PHP_AUTH_PW."' and id_appli='2'";
	$ot=tab_result($pgx,$oten);
	if (count($ot)>0) {
		$commune=$ot[0]['commune'];
		$titre='Recherche cadastrale - Fiche batiment';
		include('./head_cad.php');
		$qbat="SELECT b.INVAR, b.DNUBAT, b.DESCA, b.DNIV, b.DPOR, a.DNUPRO, a.JDATA, a.DTELOC, a.CCOPLC, a.CCONLC, a.DVLTRT,"; 
		$qbat.="a.CCOAPE, a.CC48LC, a.DLOY48A, a.DNATIC, a.CCHPR, a.JANNAT, a.DNBNIV, a.HMSEM, b.ccosec, b.dnupla, b.dnvoiri, b.dindic,"; 
		$qbat.="c.nom_voie FROM cadastre.B_DESDGI as a , cadastre.BATIDGI as b , cadastre.voies as c ";
		$qbat.="WHERE b.INVAR = a.INVAR AND b.ccoriv=c.code_voie AND c.commune=b.commune AND b.invar=".$invar1;
		$row_bat=tab_result($pgx,$qbat);
		$qbat2="SELECT dnupev FROM cadastre.B_SUBDGI where invar=".$invar1;
		$row_bat2=tab_result($pgx,$qbat2);
		$qbat3="Select libape from eco.ape where codeape='".$row_bat[0]['ccoape']."'";
		$row_bat3=tab_result($pgx,$qbat3);
		echo '<table width="100%" border="2" bgcolor="EAC88C">';
		for ($i=0;$i<count($row_bat);$i++){
			echo '<tr class="th1">';
			echo '<td colspan="2">Parcelle : '.$row_bat[$i]['ccosec'].$row_bat[$i]['dnupla'].'</td>';
			echo '<td colspan="2">'.$row_bat[$i]['dnvoiri'].$row_bat[$i]['dindic'].','.$row_bat[$i]['nom_voie'].'</td>';
			echo '</tr><tr class="th1">';
    		echo '<td>Bâtiment : '.$row_bat[$i]['dnubat'].'</td>';
			echo '<td>Escalier : '.$row_bat[$i]['desca'].'</td>';
			echo '<td>Niveau : '.$row_bat[$i]['dniv'].'</td>';
			echo '<td>Porte : '.$row_bat[$i]['dpor'].'</td>';
			echo '</tr><tr>';
			echo '<td colspan="2">Compte propriétaire : '.$row_bat[$i]['dnupro'].'</td>';
			echo '<td>Date de l\'acte : '.$row_bat[$i]['jdata'].'</td>';
			echo '<td>Valeur locative : '.$row_bat[$i]['dvltrt'].'</td>';
			echo '</tr><tr><td>';
			$ch_ch=array('1','2','3','4','5','6','7');
			$ch_rp=array('Maison','Appartement','Dépendances','Local commercial ou industriel','DOM :Maison sans descriptif','Dom :Appartement sans descriptif','Dom :Dépendances sans descriptif');
			echo str_replace($ch_ch,$ch_rp,$row_bat[$i]['dteloc']).'</td><td>';
			$ch_c2=array('U','V','W','X','Y','Z','R');
			$ch_r2=array('Chute d\'eau, barrage','Construction sur domaine public','Construction sous domaine public','Voies ferrées dont l\'assise ne forme pas parcelle','Construction sous le domaine cadastré','Construction sur le sol d\'autrui','Construction classée sur le sol d\'autrui');
			echo str_replace($ch_c2,$ch_r2,$row_bat[$i]['ccoplc']).'</td><td>';
			$ch_c3=array('MA','AP','DE','LC','CM','CA','CD','CB','ME','MP','SM','AU','CH','U','US','UE','UG','U1','U2','U3','U4','U5','U6','U7','U8','U9');
			$ch_r3=array('Maison','Appartement','Dépendance bâtie isolée','Local commun','Commerce avec boutique','Commerce sans boutique','Dépendance commerciale','Local divers','Maison exeptionnelle','Maison partagée par une limite territoriale','Sol de construction sur sol d\'autrui','Autoroute','Chantier','Etablissement industriel','Etablissement industriel','Transformateur électrique','Appareil à gaz','Gare','Gare : Triage','Gare : Atelier matériel','Gare : Atelier magasin','Gare : Dépôt','Gare : Dépôt','Gare : Matériel transport','Gare : Entretien','Gare : Station');
			echo str_replace($ch_c3,$ch_r3,$row_bat[$i]['cconlc']).'</td><td>'.$row_bat3[0]['libAPE'].'</td>';
			echo '</tr><tr>';
			echo '<td>Catégorie loi de 48 : '.$row_bat[$i]['cc48lc'].'</td>';
			echo '<td>Loyer loi de 48 : '.$row_bat[$i]['dloy48a'].'</td>';
			echo '<td>Occupation : ';
			$ch_c4=array('D','V','P','L','T');
			$ch_r4=array('Habitation principale occupé par le propriétaire','Vacant','Occupé par le propriétaire','Location','Location soumise à TVA');
			echo str_replace($ch_c4,$ch_r4,$row_bat[$i]['dnatic']).'</td><td>'.$row_bat[$i]['cchpr'].'</td></tr><tr>';
			echo '<td>Année de construction : '.$row_bat[$i]['jannat'].'</td>';
			echo '<td>Nombre de niveau : '.$row_bat[$i]['dnbniv'].'</td>';
			echo '<td>HLM ou SEM : '.$row_bat[$i]['hmsem'].'</td>';
			echo '<td></td>';
			echo '</tr>';
		//<cfinclude template="fic_hab.cfm">
			for ($k=0;$k<count($row_bat2);$k++){
			echo '<tr><td>'.$row_bat2[$k]['dnupev'].'</td><td>'.$row_bat[$i]['invar'].'</td></tr>';
				$qhab="select * from cadastre.b_habdgi where invar='".$row_bat[$i]['invar']."' and dnupev='".$row_bat2[$k]['dnupev']."'";
				$row_hab=tab_result($pgx,$qhab);
				for ($j=0;$j<count($row_hab);$j++){
					echo '<tr class="th1">';
					echo '<th colspan="4">Descriptif d\'habitation</th></tr>';
					echo '<tr><td>Garage de : '.$row_hab[$j]['dsueicga'].'m²</td>';
					echo '<td>Cave de : '.$row_hab[$j]['dsueiccv'].'m²</td>';
					echo '<td>Grenier de : '.$row_hab[$j]['dsueicgr'].'m²</td>';
					echo '<td>Terrasse de : '.$row_hab[$j]['dsueictr'].'m²</td></tr>';
					echo '<tr class="th1">';
					echo '<th colspan="4">Eléments de confort</th></tr><tr><td>';
					if ($row_hab[$j]['geaulc']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox"> Présence d\'eau</td><td>';
					if ($row_hab[$j]['gelelc']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox"> Présence d\'électricité</td><td>';
					if ($row_hab[$j]['gesclc']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox"> Escalier de service</td><td>';
					if ($row_hab[$j]['ggazlc']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox"> Présence de gaz</td></tr><tr><td>';
					if ($row_hab[$j]['gasclc']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox"> Présence d\'ascenseur</td><td>';
					if ($row_hab[$j]['gchclc']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox">Chauffage central</td><td>';
					if ($row_hab[$j]['GVORLC']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox">Présence de vide ordure</td><td>';
					if ($row_hab[$j]['gteglc']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox">Tout à l\'égout</td></tr><tr>';
					echo '<td>'.$row_hab[$j]['dnbbai'].' baignoire(s)</td>';
					echo '<td>'.$row_hab[$j]['dnbdou'].' douche(s)</td>';
					echo '<td>'.$row_hab[$j]['dnblav'].' lavabo(s)</td>';
					echo '<td>'.$row_hab[$j]['dnbwc'].' WC(s)</td></tr>';
					echo '<tr class="th1"><th colspan="4">Composition habitation</th></tr><tr>';
					echo '<td>'.$row_hab[$j]['dnbppr'].' pièces principales :</td></tr><tr>';
					echo '<td>'.$row_hab[$j]['dnbsam'].' salles à manger</td>';
					echo '<td>'.$row_hab[$j]['dnbcha'].' chambres</td>';
					echo '<td>'.$row_hab[$j]['dnbsea'].' salle d\'eau</td><td>';
					if ($row_hab[$j]['dnbcu8']=='00'){echo $row_hab[$j]['dnbcu9'].' cuisine sup.à 9m²';}else{echo $row_hab[$j]['dnbcu8'].' cuisine inf.à 9m²';}
					echo '</td></tr><tr><td>'.$row_hab[$j]['dnbann'].' pièces annexes</td>';
					echo '<td>soit au total '.$row_hab[$j]['dnbpdc'].' pièces</td><td>de '.$row_hab[$j]['dsupdc'].'m²</td><td></td></tr><tr class="th1">';
					$ch_c5=array('0','1','2','3','4','5','6','9');
					$ch_r5=array('indéterminé','pierre','meulière','béton','briques','aggloméré','bois','autres');
					echo '<th colspan="4">Caractéristique générales</th></tr><tr><td>Matériaux des gros murs : '.str_replace($ch_c4,$ch_r4,$row_hab[$j]['dmatgm']);
					echo '</td><td>Matériaux des toitures : '.$row_hab[$j]['dmatto'].' </td>';
					echo '<td>Année d\'achèvement : '.$row_hab[$j]['jannat'].' </td>';
					echo '<td>Etat d\'entretien : '.$row_hab[$j]['detent'].' </td></tr>';
				}
				//<cfinclude template="fic_pro.cfm">
				$q_pro="SELECT * FROM cadastre.B_PRODGI where invar='".$row_bat[$i]['invar']."' and dnupev='".$row_bat2[$k]['dnupev']."'";
				$row_pro=tab_result($pgx,$q_pro);
				for ($l=0;$l<count($row_pro);$l++){
					echo '<tr class="th1">';
					echo '<th colspan="4">Descriptif professionnel</th></tr>';
					echo '<tr><td>Surface réelle totale : '.$row_pro[$l]['VSURZT'].'m²</td>';
					echo '<td></td><td></td><td></td></tr>';
				}
				//<cfinclude template="fic_dep.cfm">
				$q_dep="SELECT * FROM cadastre.B_DEPDGI where invar='".$row_bat[$i]['invar']."' and dnupev='".$row_bat2[$k]['dnupev']."'";
				$row_dep=tab_result($pgx,$q_dep);
				for ($m=0;$m<count($row_dep);$m++){
					echo '<tr class="th1"><th colspan="4">Descriptif de dépendance</th></tr>';
					echo '<tr><td>Surface réelle : '.$row_dep[$m]['DSUDEP'].'m²</td>';
					$ch_c6=array('GA','CV','GR','TR','GP','GC','BX','PK','CL','BD','BC','RS','TT','PI','PA','CD','DC','JH','PS','SR');
					$ch_r6=array('Garage','Cave','Grenier','Terrasse','Garage/parking','Grenier/cave','Box','Parking','Cellier','Buanderie','Bûcher','Remise','Toiture-terrasse','Pièce indépendante','Elément de pur agrément','Chambre de domestique','Dépendance de local commun','Jardin d\'hiver','Piscine','Serre');
					echo '<td>Nature : '.str_replace($ch_c6,$ch_r6,$row_dep[$m]['CCONAD']).'</td>';
					echo '<td>Matériaux gros murs :'.$row_dep[$m]['DMATGM'].'</td>';
					echo '<td>Matériaux toitures : '.$row_dep[$m]['DMATTO'].'</td>';
					echo '</tr><tr><td>Etat d\'entretien : '.$row_dep[$m]['DETENT'].'</td><td>';
					if ($row_dep[$m]['geaulc']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox"> Présence d\'eau</td><td>';
					if ($row_dep[$m]['GELELC']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox"> Présence d\'électricité</td><td>';
					if ($row_dep[$m]['GCHCLC']=='O'){$va="checked";}else{$va="";}
					echo '<input '.$va.' type="checkbox">Chauffage central</td></tr><tr>';
					echo '<td>'.$row_dep[$m]['DNBBAI'].' baignoire(s)</td>';
					echo '<td>'.$row_dep[$m]['DNBDOU'].' douche(s)</td>';
					echo '<td>'.$row_dep[$m]['DNBLAV'].' lavabo(s)</td>';
					echo '<td>'.$row_dep[$m]['DNBWC'].' WC(s)</td></tr>';
				}
			}
		}
		echo '</table>';
		include('pied_cad.php');
	}
}
?>


