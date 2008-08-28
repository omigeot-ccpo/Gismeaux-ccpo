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
define('GIS_ROOT', '../..');
include_once(GIS_ROOT . '/inc/common.php');
gis_session_start();

$insee = $_SESSION['profil']->insee;
$appli = $_SESSION['profil']->appli;

if ((!$_SESSION['profil']->acces_ssl) || !in_array ("Prg Immobilier", $_SESSION['profil']->liste_appli)){
	die("Point d'entrée réglementé.<br> Accès interdit. <br>Veuillez vous connecter via <a href=\"https://".$_SERVER['HTTP_HOST']."\">serveur carto</a><SCRIPT language=javascript>setTimeout(\"window.location.replace('https://".$_SERVER['HTTP_HOST']."')\",5000)</SCRIPT>");
}
function sinul($ch){
	if (strlen($ch)>0){
		return $ch;
	}else{
		return "null";
	}
}
if ($_GET['obj_keys']){
	$sql="select * from urba.program where gid =".$_GET['obj_keys'];
	$result= $DB->tab_result($sql);
}
//traitement des permis de construire multiple
$gpc=$_GET['pc'];
if ($_GET['mpc']=="1"){
	$lpc="{";
	for ($p=0;$p<count($gpc);$p++){
		$lpc.=$gpc[$p].",";
		$lrpc.=$gpc[$p]."','";
	}
	$lpc=substr($lpc,0,strlen($lpc)-1)."}";
	$lrpc=substr($lrpc,0,-3);
}else{
	$lpc=$gpc[0];
	$lrpc=$gpc[0];
}
if (count($gpc)>0){
	$pct="array['";
	for ($p=0;$p<count($gpc);$p++){
		$pct.=$gpc[$p]."','";
	}
	$pct=substr($pct,0,strlen($pct)-2)."]";
}else{
	$pct="array['']";
}
//traitement des permis de construire multiple
$gpd=$_GET['num_PD'];
if ($_GET['mpd']=="1"){
	$lpd="{";
	for ($p=0;$p<count($gpd);$p++){
		$lpd.=$gpd[$p].",";
		$lrpd.=$gpd[$p]."','";
	}
	$lpd=substr($lpd,0,strlen($lpd)-1)."}";
	$lrpd=substr($lrpd,0,-3);
}else{
	$lpd=$gpd[0];
	$lrpd=$gpd[0];
}
if(count($gpd)>0){
	$pdt="array['";
	for ($p=0;$p<count($gpd);$p++){
		$pdt.=$gpd[$p]."','";
	}
	$pdt=substr($pdt,0,strlen($pdt)-2)."]";
}else{
	$pdt="array['']";
}

if (($_GET['gid']) and ($_GET['act']!="sup") and ($_GET['act']!="ins")){
	if ($_GET['act']=="ins_pc"){
		$sql2="update urba.program set pc='".$lpc."', pct=".$pct.", nbre_lgt=(select sum(nb_log_individuel)+sum(nb_log_collectif) from afi.hppc where dossier in ('".$lrpc."')), nbre_ind=(select sum(nb_log_individuel) from afi.hppc where dossier in ('".$lrpc."')), nbre_coll=(select sum(nb_log_collectif) from afi.hppc where dossier in ('".$lrpc."')) where gid=".$_GET['gid'];
	}elseif($_GET['act']=="ins_pd"){
		$sql2="update urba.program set num_pd='".$lpd."', pdt=".$pdt.", nbre_logt_demol=(select sum(nb_logement) from afi.hppd where dossier in ('".$lrpd."')) where gid=".$_GET['gid'];
	}elseif ($_GET['act']=="mod"){
		$sql2="update urba.program set num=".sinul($_GET['num']).", nom='".$_GET['nom']."', nbre_lgt=".sinul($_GET['nbre_lgt']).", typ_lgt='".$_GET['typ_lgt']."', nbre_ind=".sinul($_GET['nbre_ind']).", nbre_coll=".sinul($_GET['nbre_coll']).", annee=".sinul($_GET['annee']).", pc='".$lpc."',pct=".$pct.",  commentaire='".$_GET['commentaire']."', nbre_logt_demol='".sinul($_GET['nbre_logt_demol'])."', date_demol=".datedmy2sql($_GET['date_demol']).", date_livraison=".datedmy2sql($_GET['date_livraison']).", num_pd='".$lpd."', pdt=".$pdt.", mpc='".$_GET['mpc']."', mpd='".$_GET['mpd']."' where gid=".$_GET['gid'];
	}
	//echo $sql2;
	$DB->exec($sql2);
	$sql="select * from urba.program where gid =".$_GET['gid'];
	$result=$DB->tab_result($sql);
}
if ($_GET['act']=="sup"){
	$sql2="delete from urba.program where gid=".$_GET['gid'];
	$DB->exec($sql2);
}
if ($_GET['act']=='ins'){
		$sql2="insert into urba.program (num,nom,nbre_lgt,typ_lgt,nbre_ind,nbre_coll,annee,pc,commentaire,the_geom,nbre_logt_demol,date_demol,date_livraison,mpc,mpd,num_pd) values (".sinul($_GET['num']).",'".$_GET['nom']."',".sinul($_GET['nbre_lgt']).",'".$_GET['typ_lgt']."',".sinul($_GET['nbre_ind']).",".sinul($_GET['nbre_coll']).",".sinul($_GET['annee']).",'".$lpc."','".$_GET['commentaire']."',GeometryFromtext('POLYGON((".$_GET['polygo']."))',$projection),".sinul($_GET['nbre_logt_demol']).",".datedmy2sql($_GET['date_demol']).",".datedmy2sql($_GET['date_livraison']).",'".$_GET['mpc']."','".$_GET['mpd']."','".$lpd."' )";
		//echo $sql2;
		$DB->exec($sql2);
		$sql="select * from urba.program where gid =currval('urba.program_gid_seq')";
		$result=$DB->tab_result($sql);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<title>programme immobilier</title>
</head>
<body>
<form method="get" action="program_immo.php" name="form1">
<input type="hidden" name="gid" value="<?php echo $result[0]['gid']; ?>">
<input type="hidden" name="polygo" value="<?php echo $_GET['polygo'];?>">
<input type="hidden" id="act" name="act" value="">
<table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
<tr style="text-align: center;">
	<td>Construction</td>
	<td>D�molition</td>
</tr>
<tr>
	<td style="width: 50%;">
		<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
			<tr>
				<td style="width: 50%;">Num�ro du programme</td>
				<td><input type="text" name="num" value="<?php echo $result[0]['num']; ?>"></td>
			</tr>
			<tr>
				<td style="width: 50%;">Nom du programme</td>
				<td><input type="text" name="nom" value="<?php echo $result[0]['nom']; ?>"></td>
			</tr>
			<tr>
				<td>Nombre de logement</td>
				<td><input type="text" name="nbre_lgt" value="<?php echo $result[0]['nbre_lgt']; ?>"></td>
			</tr>
			<tr>
				<td>Type de logement</td>
				<td><select name="typ_lgt">
						<option value="C" <?php if ($result[0]['typ_lgt']=="C"){echo "selected";} ?>>Collectif</option>
						<option value="I" <?php if ($result[0]['typ_lgt']=="I"){echo "selected";} ?>>Individuel</option>
						<option value="M" <?php if ($result[0]['typ_lgt']=="M"){echo "selected";} ?>>Mixte</option></select></td>
			</tr>
			<tr>
				<td>Nombre de logement individuel</td>
				<td><input type="text" name="nbre_ind" value="<?php echo $result[0]['nbre_ind']; ?>"></td>
			</tr>
			<tr>
				<td>Nombre de logement collectif</td>
				<td><input type="text" name="nbre_coll" value="<?php echo $result[0]['nbre_coll']; ?>"></td>
			</tr>
			<tr>
				<td>Numero Permis de Construire<br><input name="mpc" type="checkbox" value="1" <?php if($result[0]['mpc']=='1'){echo 'checked';}?>>&nbsp;multiples</td>
<?php
if (($result[0]['pc']=="") and (($_GET['gid']) or ($_GET['obj_keys'])) and ($_GET['act']!="sup")){
	$sql1="select distinct(dossier) from cadastre.hppc_parcelle where distance(the_geom,'".$result[0]['the_geom']."')=0 and substr(dossier,1,2)='PC'";
	$r_pc=$DB->tab_result($sql1);
	if (count($r_pc)>0){
		if (count($r_pc)==1){
			echo '<td><input type="text" name="pc[]" value="'.$r_pc[0]['dossier'].'">&nbsp;<input type="button" value="Valider" onclick="document.getElementById(\'act\').value=\'ins_pc\';submit();"></td>';
		}else{
			if ($result[0]['mpc']==1){
				echo '<td><select name="pc[]" multiple>';
			}else{
				echo '<td><select name="pc[]">';
			}
			for ($u=0;$u<count($r_pc);$u++){
				echo '<option value="'.$r_pc[$u]['dossier'].'">'.$r_pc[$u]['dossier'].'</option>';
			}
			echo '</select>&nbsp;<input type="button" value="Valider" onclick="document.getElementById(\'act\').value=\'ins_pc\';submit();"></td>';
		}
	}else{
		echo '<td><input type="text" name="pc[]" value=""></td>';
	}
}else{
	if (($result[0]['mpc']==1) and (($_GET['gid']) or ($_GET['obj_keys'])) and ($_GET['act']!="sup")){
			$sql2="select distinct(dossier) from cadastre.hppc_parcelle where distance(the_geom,'".$result[0]['the_geom']."')=0 and substr(dossier,1,2)='PC'";
			$r_pc=$DB->tab_result($sql2);
			$tab_permis=explode(",",substr($result[0]['pc'],1,-1));
			echo '<td><select name="pc[]" multiple>';
			for ($o=0;$o<count($r_pc);$o++){
				if (array_search($r_pc[$o]['dossier'],$tab_permis)===false ){ 
					echo '<option value="'.$r_pc[$o]['dossier'].'">'.$r_pc[$o]['dossier'].'</option>';
				}else{
					echo '<option value="'.$r_pc[$o]['dossier'].'" selected>'.$r_pc[$o]['dossier'].'</option>';
				}
			}
			echo '</select>&nbsp;<input type="button" value="Valider" onclick="document.getElementById(\'act\').value=\'ins_pc\';submit();"></td>';
	}else{
		echo '<td><input type="text" name="pc[]" value="'.$result[0]['pc'].'"></td>';
	}
}
?>
			</tr>
			<tr>
				<td>Annee de livraison pr�vue</td>
				<td><input type="text" name="annee" value="<?php echo $result[0]['annee']; ?>"></td>
			</tr>
			<tr>
				<td>Date de livraison</td>
				<td><input type="text" name="date_livraison" value="<?php echo datesql2dmy($result[0]['date_livraison']); ?>"></td>
			</tr>
			<tr>
				<td>Commentaire</td>
				<td><input type="text" name="commentaire" value="<?php echo $result[0]['commentaire']; ?>"></td>
			</tr>
		</table>
	</td>
	<td>
		<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
			<tr>
				<td style="width: 50%;">Nombre de logement demoli</td>
				<td><input type="text" name="nbre_logt_demol" value="<?php echo $result[0]['nbre_logt_demol']; ?>"></td>
			</tr>
			<tr>
				<td>Numero Permis de demolir<br><input name="mpd" type="checkbox" value="1" <?php if($result[0]['mpd']=='1'){echo 'checked';}?>>&nbsp;multiples</td>
<?php
if (($result[0]['num_pd']=="") and (($_GET['gid']) or ($_GET['obj_keys'])) and ($_GET['act']!="sup")){
	$sql3="select distinct(dossier) from cadastre.hppc_parcelle where distance(the_geom,'".$result[0]['the_geom']."')=0 and substr(dossier,1,2)='PD'";
	$r_pd=$DB->tab_result($sql3);
	if (count($r_pd)>0){
		if (count($r_pd)==1){
			echo '<td><input type="text" name="num_PD[]" value="'.$r_pd[0]['dossier'].'">&nbsp;<input type="button" value="Valider" onclick="document.getElementById(\'act\').value=\'ins_pd\';submit();"></td>';
		}else{
			if ($result[0]['mpd']==1){
				echo '<td><select name="num_PD[]" multiple>';
			}else{
				echo '<td><select name="num_PD[]">';
			}
			for ($u=0;$u<count($r_pd);$u++){
				echo '<option value="'.$r_pd[$u]['dossier'].'">'.$r_pd[$u]['dossier'].'</option>';
			}
			echo '</select>&nbsp;<input type="button" value="Valider" onclick="document.getElementById(\'act\').value=\'ins_pd\';submit();"></td>';
		}
	}else{
		echo '<td><input type="text" name="num_PD[]" value=""></td>';
	}
}else{
	if (($result[0]['mpd']=="1") and (($_GET['gid']) or ($_GET['obj_keys'])) and ($_GET['act']!="sup")){
		$sql3="select distinct(dossier) from cadastre.hppc_parcelle where distance(the_geom,'".$result[0]['the_geom']."')=0 and substr(dossier,1,2)='PD'";
		$r_pd=$DB->tab_result($sql3);
		$tab_pd=explode(",",substr($result[0]['num_pd'],1,-1));
		echo '<td><select name="num_PD[]" multiple>';
		for ($j=0;$j<count($r_pd);$j++){
			if (array_search($r_pd[$j]['dossier'],$tab_pd)===false){
				echo '<option value="'.$r_pd[$j]['dossier'].'">'.$r_pd[$j]['dossier'].'</option>';
			}else{
				echo '<option value="'.$r_pd[$j]['dossier'].'" selected>'.$r_pd[$j]['dossier'].'</option>';
			}
		}
		echo '</select>&nbsp;<input type="button" value="Valider" onclick="document.getElementById(\'act\').value=\'ins_pd\';submit();"></td>';
	}else{	
		echo '<td><input type="text" name="num_PD[]" value="'.$result[0]['num_pd'].'"></td>';
	}
}
?>
			</tr>
			<tr>
				<td>Date de demolition</td>
				<td><input type="text" name="date_demol" value="<?php echo datesql2dmy($result[0]['date_demol']); ?>"></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<?php
if (($_GET['obj_keys']) or ($_GET['gid'])){
echo '<input type="button" value="Modifier" onclick="document.getElementById(\'act\').value=\'mod\';submit();">';
echo '&nbsp;';
echo '<input type="button" value="Supprimer" onclick="document.getElementById(\'act\').value=\'sup\';submit();">';
}elseif ($_GET['polygo']){
echo '<input type="button" value="Ins�rer" onclick="document.getElementById(\'act\').value=\'ins\';submit();">';
}
?>
</form>
</body>
</html>