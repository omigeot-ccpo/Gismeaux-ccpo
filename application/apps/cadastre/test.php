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
?>
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="plages_022.jpg" text="#33FF00">
<?php 
if ($vmysql=='mysql') {
	set_time_limit(120);
	$lk=mysql_pconnect($bdmysql,$umysql,$pswmysql)
		or die ("Connexion impossible � MYSQL");
	if (mysql_create_db("cadastre")){
		/* Puisque cr�ation de la base, cr�ation des tables */
		print ("Base de donn�es cr��e\n"); ?> <BR> <?php 
		mysql_select_db("cadastre",$lk);
		/* D�pendance des b�timents cenr=60 */
		mysql_query("CREATE TABLE b_depdgi (commune varchar(6) default NULL,invar varchar(10) default NULL,dnupev char(3) default NULL,dnudes char(3) default NULL,
						  dsudep varchar(6) default NULL,cconad char(2) default NULL,asitet varchar(6) default NULL,dmatgm char(2) default NULL,
						  dmatto char(2) default NULL,detent char(1) default NULL,geaulc char(1) default NULL,gelelc char(1) default NULL,
						  gchclc char(1) default NULL,dnbbai char(2) default NULL,dnbdou char(2) default NULL,dnblav char(2) default NULL,
						  dnbwc char(2) default NULL,deqtlc char(3) default NULL,dcimlc char(2) default NULL,dcetde char(3) default NULL,
						  dcspde char(3) default NULL,KEY commune (commune))TYPE=MyISAM;");
		print ("Table des d�pendances cr��e\n"); ?> <BR> <?php 
		/* Description sommaire des locaux cenr=10 */
		mysql_query("CREATE TABLE b_desdgi (commune varchar(6) default NULL,invar varchar(10) default NULL,gpdl char(1) default NULL,dsrpar char(1) default NULL,
						  dnupro varchar(6) default NULL,jdata varchar(8) default NULL,dnufnl varchar(6) default NULL,ccoeva char(1) default NULL,
						  dteloc char(1) default NULL,gtauom char(2) default NULL,dcomrd char(3) default NULL,ccoplc char(1) default NULL,
						  cconlc char(2) default NULL,dvltrt varchar(9) default NULL,ccoape varchar(4) default NULL,cc48lc char(2) default NULL,
						  dloy48a varchar(9) default NULL,top48 char(1) default NULL,dnatic char(1) default NULL,cchpr char(1) default NULL,
						  jannat varchar(4) default NULL,dnbniv char(2) default NULL,hmsem char(1) default NULL,postel char(1) default NULL,
						  cbtabt varchar(2) default NULL,jdtabt varchar(4) default NULL,jrtabt varchar(4) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table de description cr��e\n"); ?> <BR> <?php 
		/* Locaux �xon�r�s, pas toujours servi par DGI cenr=30 */
		mysql_query("CREATE TABLE b_exodgi (commune varchar(6) default NULL,invar varchar(10) default NULL,dnupev char(3) default NULL,dnuord char(3) default NULL,ccolloc char(2) default NULL,
						  pexb varchar(5) default NULL,gnextl char(2) default NULL,jandeb varchar(4) default NULL,janimp varchar(4) default NULL,
						  dvldif2 varchar(9) default NULL,dvldif2a varchar(9) default NULL,fcexb2 varchar(9) default NULL,fcexba2 varchar(9) default NULL,
						  rcexba2 varchar(9) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table �xon�ration cr��e\n"); ?> <BR> <?php 
		/* Description d�taill�e des locaux cenr=40 */
		mysql_query("CREATE TABLE b_habdgi (commune varchar(6) default NULL,invar varchar(10) default NULL,dnupev char(3) default NULL,dnudes char(3) default NULL,cconadga char(2) default NULL,
						  dsueicga varchar(6) default NULL,dcimei char(2) default NULL,cconadcv char(2) default NULL,dsueiccv varchar(6) default NULL,
						  dcimeicv char(2) default NULL,cconadgr char(2) default NULL,dsueicgr varchar(6) default NULL,dcimeia char(2) default NULL,
						  cconadtr char(2) default NULL,dsueictr varchar(6) default NULL,dcimeitr char(2) default NULL,geaulc char(1) default NULL,
						  gelelc char(1) default NULL,gesclc char(1) default NULL,ggazlc char(1) default NULL,gasclc char(1) default NULL,
						  gchclc char(1) default NULL,gvorlc char(1) default NULL,gteglc char(1) default NULL,dnbbai char(2) default NULL,
						  dnbdou char(2) default NULL,dnblav char(2) default NULL,dnbwc char(2) default NULL,deqdha char(3) default NULL,
						  dnbppr char(2) default NULL,dnbsam char(2) default NULL,dnbcha char(2) default NULL,dnbcu8 char(2) default NULL,
						  dnbcu9 char(2) default NULL,dnbsea char(2) default NULL,dnbann char(2) default NULL,dnbpdc char(2) default NULL,
						  dsupdc varchar(6) default NULL,dmatgm char(2) default NULL,dmatto char(2) default NULL,jannat varchar(4) default NULL,
						  detent char(1) default NULL,dnbniv char(2) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table description des habitations cr��e\n"); ?> <BR> <?php 
		/* Description des locaux professionnels cenr=50 */
		mysql_query("CREATE TABLE b_prodgi (commune varchar(6) default NULL,invar varchar(10) default NULL,dnupev char(3) default NULL,dnudes char(3) default NULL,vsurzt varchar(9) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table professionnelle cr��e\n"); ?> <BR> <?php 
		/* Subdivision des locaux cenr=21 */
		mysql_query("CREATE TABLE b_subdgi (commune varchar(6) default NULL,invar varchar(10) default NULL,dnupev char(3) default NULL,ccoaff char(1) default NULL,ccostb char(1) default NULL,
						  dcapec char(2) default NULL,dcetlc char(3) default NULL,dcsplc char(3) default NULL,dsupot varchar(6) default NULL,
						  dvlper varchar(9) default NULL,dvlpera varchar(9) default NULL,gnexpl char(2) default NULL,ccthp char(1) default NULL,
						  retimp char(1) default NULL,dnuref char(3) default NULL,gnidom char(1) default NULL,dcsglc char(3) default NULL,
						  dvltpe varchar(9) default NULL,dcralc varchar(3) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table de subdivision des locaux cr��e\n"); ?> <BR> <?php 
		/* Taxation du b�ti, pas toujours servi cenr=36 */
		mysql_query("CREATE TABLE b_taxdgi (commune varchar(6) default NULL,invar varchar(10) default NULL,dnupev char(3) default NULL,vlbaic varchar(9) default NULL,vlbaiac varchar(9) default NULL,
						  bipeviac varchar(9) default NULL,vlbaid varchar(9) default NULL,vlbaiad varchar(9) default NULL,bipeviad varchar(9) default NULL,
						  vlbair varchar(9) default NULL,vlbaiar varchar(9) default NULL,bipeviar varchar(9) default NULL,vlbaigc varchar(9) default NULL,
						  vlbaiagc varchar(9) default NULL,bipeviagc varchar(9) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table de la taxation cr��e\n"); ?> <BR> <?php 
		/* Identification des locaux cenr=00 */
		mysql_query("CREATE TABLE batidgi (commune varchar(6) default NULL,invar varchar(10) default NULL,ccopre char(3) default NULL,ccosec char(2) default NULL,dnupla varchar(4) default NULL,
						  dnubat char(2) default NULL,desca char(2) default NULL,dniv char(2) default NULL,
						  dpor varchar(5) default NULL,ccoriv varchar(4) default NULL,ccovoi varchar(5) default NULL,dnvoiri varchar(4) default NULL,
						  dindic char(1) default NULL,ccocif varchar(4) default NULL,dvoilib varchar(30) default NULL, 
						  KEY invar (invar),KEY commune (commune)) TYPE=MyISAM;");
		print ("Table des locaux cr��e\n"); ?> <BR> <?php 
		/* Exon�ration du non b�ti cenr=30 */
		mysql_query("CREATE TABLE p_exoner (commune varchar(6) default NULL,ccosec char(2) default NULL,dnupla varchar(4) default NULL,ccosub char(2) default NULL,muexn char(2) default NULL,
						  vecexn varchar(10) default NULL,ccolloc char(2) default NULL,pexn varchar(5) default NULL,gnexts char(2) default NULL,
						  jandeb varchar(4) default NULL,jfinex varchar(4) default NULL,rcexnba varchar(10) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table �xon�ration parcelle cr��e\n"); ?> <BR> <?php 
		/* Subdivision fiscale du non b�ti cenr=21 */
		mysql_query("CREATE TABLE p_subdif (commune varchar(6) default NULL,ccosec char(2) default NULL,dnupla varchar(4) default NULL,ccosub char(2) default NULL,dcntsf varchar(9) default NULL,
						  cgroup char(1) default NULL,dnumcp varchar(5) default NULL,gnexps char(2) default NULL,drcsub varchar(10) default NULL,
						  drcsuba varchar(10) default NULL,ccostn char(1) default NULL,cgmum char(2) default NULL,dsgrpf char(2) default NULL,
						  dclssf char(2) default NULL,cnatsp varchar(5) default NULL,drgpos char(1) default NULL,ccoprel char(3) default NULL,
						  ccosecl char(2) default NULL,dnuplal varchar(4) default NULL,dnupdl char(3) default NULL,dnulot varchar(7) default NULL,
						  topja char(1) default NULL,datja varchar(8) default NULL,postel char(1) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table de subdivision des parcelles cr��e\n"); ?> <BR> <?php 
		/* Taxation du non b�ti cenr=36 */
		mysql_query("CREATE TABLE p_taxat (commune varchar(6) default NULL,ccosec char(2) default NULL,dnupla varchar(4) default NULL,ccosub char(2) default NULL,
						  majposac varchar(10) default NULL,bisufadc varchar(10) default NULL,majposad varchar(10) default NULL,
						  bisufadd varchar(10) default NULL,majposar varchar(10) default NULL,bisufadr varchar(10) default NULL,
						  majposagc varchar(10) default NULL,bisufadgc varchar(10) default NULL,KEY commune (commune)) TYPE=MyISAM;");
		print ("Table de taxation des parcelles cr��e\n"); ?> <BR> <?php 
		/* Identification des parcelles cenr=10 */
		mysql_query("CREATE TABLE parcel (commune varchar(6) default NULL,ccosec char(2) default NULL,dnupla varchar(4) default NULL,dcntpa varchar(9) default NULL,dsrpar char(1) default NULL,
						  cgroup char(1) default NULL,dnumcp varchar(5) default NULL,jdatat varchar(8) default NULL,dreflf varchar(5) default NULL,
						  gpdl char(1) default NULL,cprsecr char(3) default NULL,ccosecr char(2) default NULL,dnuplar varchar(4) default NULL,
						  dnupdl char(3) default NULL,gurbpa char(1) default NULL,dparpi varchar(4) default NULL,ccoarp char(1) default NULL,
						  gparnf char(1) default NULL,gparbat char(1) default NULL,dnuvoi varchar(4) default NULL,
						  dindic char(1) default NULL,ccovoi varchar(5) default NULL,ccoriv varchar(4) default NULL,ccocif varchar(4) default NULL,
						  par1 varchar(6) NOT NULL default '',prop1 varchar(6) NOT NULL default '',KEY par1 (par1),KEY prop1 (prop1),KEY commune (commune)) TYPE=MyISAM;");
		print ("Table des parcelles cr��e\n"); ?> <BR> <?php 
		/* Table des propri�taires */
		mysql_query("CREATE TABLE propriet (commune varchar(6) default NULL,cgroup char(1) default NULL,dnumcp varchar(5) default NULL,dnulp char(2) default NULL,ccocif varchar(4) default NULL,
						  dnuper varchar(6) default NULL,ccodro char(1) default NULL,ccodem char(1) default NULL,gdesip char(1) default NULL,
						  gtoper char(1) default NULL,ccoqua char(1) default NULL,dnatpr char(3) default NULL,ccogrm char(2) default NULL,
						  dsglpm varchar(10) default NULL,dforme varchar(7) default NULL,ddenom varchar(60) default NULL,dlign3 varchar(30) default NULL,
						  dlign4 varchar(36) default NULL,dlign5 varchar(30) default NULL,dlign6 varchar(32) default NULL,ccopay char(3) default NULL,
						  dqualp char(3) default NULL,dnomlp varchar(30) default NULL,dprnlp varchar(15) default NULL,jdatnss varchar(10) default NULL,
						  dldnss varchar(58) default NULL,epxnee char(3) default NULL,dnomcp varchar(30) default NULL,dprncp varchar(15) default NULL,
						  dsiren varchar(10) default NULL,prop1 varchar(6) NOT NULL default '',KEY prop1 (prop1),KEY commune (commune)) TYPE=MyISAM;");
		print ("Table des propri�taires cr��e\n"); ?> <BR> <?php 
		/* Table des voies, code RIVOLI */
		mysql_query("CREATE TABLE voies (commune varchar(6) default NULL,code_voie varchar(4) NOT NULL default '',nom_voie varchar(56) NOT NULL default '',
							caract varchar(1) default NULL,typ varchar(1) default NULL,KEY code_voie (code_voie),KEY commune (commune)) TYPE=MyISAM;");
		print ("Table des voies cr��e\n"); ?> <BR> <?php 
		/* Table des communes */
		mysql_query("CREATE TABLE commune(cod_comm varchar(6) NOT NULL default '',lib_com varchar(50) default NULL,logo varchar(50) default '',key cod_commune (cod_comm)) TYPE=MyISAM;");
		print ("Table des communes cr��e\n"); ?> <BR> <?php 
	}else{
		/* Base cadastre existante, on renomme les tables pour historique, et on cr�� les nouvelles */
		function rename_table($name_tb,$ann){
			$dx=strval($ann-1);
			$dx=substr($dx,2,2); echo $dx;
			if (mysql_query("create table ".substr($name_tb,0,-2).$dx." as select * from ".$name_tb.";")){
				print ("Table ".$name_tb." renomm�e");
				mysql_query("delete from ".$name_tb.";");
				echo "Table ".$name_tb." renomm�e en ".substr($name_tb,0,-2).$dx." et table ".$name_tb." cr��e\n"; ?> <BR> <?php 
			}else{
				echo "Erreur lors du changement de nom ".mysql_error();
			}
		}
		echo "Erreur lors de la cr�ation de la base: ".mysql_error();
		echo "Changement de nom des tables en cours\n";
		mysql_select_db("cadastre",$lk);
		rename_table("b_depdgi",$ann_ref);
		rename_table("b_desdgi",$ann_ref);
		rename_table("b_exodgi",$ann_ref);
		rename_table("b_habdgi",$ann_ref);
		rename_table("b_prodgi",$ann_ref);
		rename_table("b_subdgi",$ann_ref);
		rename_table("b_taxdgi",$ann_ref);
		rename_table("batidgi",$ann_ref);
		rename_table("p_exoner",$ann_ref);
		rename_table("p_subdif",$ann_ref);
		rename_table("p_taxat",$ann_ref);
		rename_table("parcel",$ann_ref);
		rename_table("propriet",$ann_ref);
	}
}
include("test_ora.php");
include("test_pg.php");
$feror=fopen($ferr,"a");
if ($nbat!=""){
	$fp=fopen($nbat,"r"); /* Ouverture du fichier du non b�ti et chargement des tables*/
	if ($voracle=='oracle') {
		$ctab=fopen($rep_f."cree_tab.sql",a);				/* Int�gration dans le script sql de l'insertion des donn�es */
		fwrite($ctab,"start ".$rep_f."parcel.ora;\n");		/* Donn�es de la table parcelle */
		fwrite($ctab, "commit;\n");
		fwrite($ctab,"start ".$rep_f."psubd.ora;\n");			/* Donn�es de la table subdivision */
		fwrite($ctab, "commit;\n");
		fwrite($ctab,"start ".$rep_f."pexo.ora;\n");			/* Donn�es de la table exon�ration */
		fwrite($ctab, "commit;\n");
		fwrite($ctab,"start ".$rep_f."ptaxat.ora;\n");		/* Donn�es de la table taxation */
		fwrite($ctab, "commit;\n");
		fclose($ctab);
		$parc_ora=fopen($rep_f."parcel.ora",w);				/* Ouverture en �criture des fichiers de donn�es */
		$psubd_ora=fopen($rep_f."psubd.ora",w);
		$pexo_ora=fopen($rep_f."pexo.ora",w);
		$ptaxat_ora=fopen($rep_f."ptaxat.ora",w);
	}
	$section=" ";$i=0;$j=0;$k=0;$l=0;$v="','";
	$linfo=fgets($fp);
	$code_commune=substr($linfo,0,6);
	if ($vmysql=='mysql') {	
		$r=mysql_query("select * from commune where cod_comm = '".$code_commune."';");
		if (mysql_num_rows($r)==0) { 
			mysql_query("insert into commune (cod_comm,lib_com) values ('".$code_commune.$v.$nom_comm."');");
		}
	}
	if ($vpg=='pgsql') {
		$r=pg_exec($pgx,"select * from cadastre.commune where cod_comm = '".$code_commune."';");
		if (pg_numrows($r)==0) { 
			pg_exec($pgx,"insert into cadastre.commune (cod_comm,lib_com) values ('".$code_commune.$v.$nom_comm."');");
		}
	}
	while (!feof($fp)){
		$code_commune=substr($linfo,0,6);
		$ccosec=substr($linfo,9,2);
		$dnupla=substr($linfo,11,4);
		$cenr=substr($linfo,19,2); 
		if ($cenr==10){
			++$i;
			$dcntpa=substr($linfo,21,9);
			$dsrpar=substr($linfo,30,1);
			$dnupro=substr($linfo,31,6);
			$jdata=substr($linfo,37,8);
			$dreflf=substr($linfo,45,5);
			$gpdl=substr($linfo,50,1);
			$cprsecr=substr($linfo,51,3);
			$ccosecr=substr($linfo,54,2);
			$dnuplar=substr($linfo,56,4);
			$dnupdl=substr($linfo,60,3);
			$gurbpa=substr($linfo,63,1);
			$dparpi=substr($linfo,64,4);
			$ccoarp=substr($linfo,68,1);
			$gparnf=substr($linfo,69,1);
			$gparbat=substr($linfo,70,1);
			$parrev=substr($linfo,71,12);
			$gpardp=substr($linfo,83,1);
			$fviti=substr($linfo,84,1);
			$dnvoiri=substr($linfo,85,4);
			$dindic=substr($linfo,89,1);
			$ccovoi=substr($linfo,90,5);
			$ccoriv=substr($linfo,95,4);
			$ccocif=substr($linfo,99,4);
			$gpafpd=substr($linfo,103,1);
			$comma10="insert into cadastre.parcel (commune, ccosec, dnupla, dcntpa, dsrpar, cgroup, dnumcp, jdatat, dreflf,
						  gpdl, cprsecr, ccosecr, dnuplar, dnupdl, gurbpa, dparpi, ccoarp, gparnf, gparbat, dnuvoi, 
						  dindic, ccovoi, ccoriv, ccocif, par1, prop1) values ('";
			$comma10.=$code_commune.$v.$ccosec.$v.$dnupla.$v.$dcntpa.$v.$dsrpar.$v.substr($dnupro,0,1).$v.substr($dnupro,1).$v.$jdata.$v.$dreflf.$v.$gpdl.$v.
						$cprsecr.$v.$ccosecr.$v.$dnuplar.$v.$dnupdl.$v.$gurbpa.$v.$dparpi.$v.$ccoarp.$v.$gparnf.$v.$gparbat.$v.
						$dnvoiri.$v.$dindic.$v.$ccovoi.$v.$ccoriv.$v.$ccocif.$v.$ccosec.ltrim($dnupla,"\0x00").$v.$dnupro."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma10,$lk))){
					fwrite($feror,$comma10);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma10))) { 
					fwrite($feror,$comma10);
				}
			}
			if ($voracle=='oracle') {
				fwrite($parc_ora,$comma10."\n");
			}
		}elseif ($cenr==21){
			++$j;
			$ccosub=substr($linfo,15,2);
			$dcntsf=substr($linfo,21,9);
			$dnupro=substr($linfo,30,6);
			$gnexps=substr($linfo,36,2);
			$drcsub=substr($linfo,38,10);
			$drcsuba=substr($linfo,48,10);
			$ccostn=substr($linfo,58,1);
			$cgmum=substr($linfo,59,2);
			$dsgrpf=substr($linfo,61,2);
			$dclssf=substr($linfo,63,2);
			$cnatsp=substr($linfo,65,5);
			$drgpos=substr($linfo,70,1);
			$ccoprel=substr($linfo,71,3);
			$ccosecl=substr($linfo,74,2);
			$dnuplal=substr($linfo,76,4);
			$dnupdl=substr($linfo,80,3);
			$dnulot=substr($linfo,83,7);
			$rclsi=substr($linfo,90,46);
			$gnidom=substr($linfo,136,1);
			$topja=substr($linfo,137,1);
			$datja=substr($linfo,138,8);
			$postel=substr($linfo,146,1);
			$comma21="insert into cadastre.p_subdif (commune, ccosec, dnupla, ccosub, dcntsf, cgroup, dnumcp, gnexps, drcsub, drcsuba, ccostn, cgmum, 
						dsgrpf, dclssf, cnatsp, drgpos, ccoprel,ccosecl, dnuplal, dnupdl, dnulot, topja, datja, postel) values ('";
			$comma21.=$code_commune.$v.$ccosec.$v.$dnupla.$v.$ccosub.$v.$dcntsf.$v.substr($dnupro,0,1).$v.substr($dnupro,1).$v.$gnexps.$v.$drcsub.$v.
						$drcsuba.$v.$ccostn.$v.$cgmum.$v.$dsgrpf.$v.$dclssf.$v.$cnatsp.$v.$drgpos.$v.$ccoprel.$v.$ccosecl.$v.$dnuplal.$v.
						$dnupdl.$v.$dnulot.$v.$topja.$v.$datja.$v.$postel."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma21,$lk))){
					fwrite($feror,$comma21);}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma21))) { 
					fwrite($feror,$comma21);
				}
			}
			if ($voracle=='oracle') {
				fwrite($psubd_ora,$comma21."\n");
			}
		}elseif ($cenr==30){
			++$k;
			$ccosub=substr($linfo,15,2);
			$muexn=substr($linfo,17,2);
			$vecexn=substr($linfo,21,10);
			$ccolloc=substr($linfo,31,2);
			$pexn=substr($linfo,33,5);
			$gnexts=substr($linfo,38,2);
			$jandeb=substr($linfo,40,4);
			$jfinex=substr($linfo,44,4);
			$fcexn=substr($linfo,48,10);
			$fcexna=substr($linfo,58,10);
			$rcexna=substr($linfo,68,10);
			$rcexnba=substr($linfo,78,10);
			$mpexnba=substr($linfo,89,10);
			$comma30="insert into cadastre.p_exoner (commune, ccosec, dnupla, ccosub, muexn, vecexn, ccolloc, pexn, gnexts, jandeb, jfinex, rcexnba) values ('";
			$comma30.=$code_commune.$v.$ccosec.$v.$dnupla.$v.$ccosub.$v.$muexn.$v.$vecexn.$v.$ccolloc.$v.$pexn.$v.$gnexts.$v.$jandeb.$v.$jfinex.$v.$rcexnba."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma30,$lk))){
					fwrite($feror,$comma30);}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma30))) { 
					fwrite($feror,$comma30);
				}
			}
			if ($voracle=='oracle') {
				fwrite($pexo_ora,$comma30."\n");
			}
		}elseif ($cenr==36){
			++$l;
			$ccosub=substr($linfo,15,2);
			$majposac=substr($linfo,22,10);
			$bisufadc=substr($linfo,33,10);
			$majposad=substr($linfo,44,10);
			$bisufadd=substr($linfo,55,10);
			$majposar=substr($linfo,66,10);
			$bisufadr=substr($linfo,77,10);
			$majposagc=substr($linfo,88,10);
			$bisufadgc=substr($linfo,99,10);
			$comma36="insert into cadastre.p_taxat (commune, ccosec, dnupla, ccosub, majposac, bisufadc, majposad,
						  bisufadd, majposar, bisufadr, majposagc, bisufadgc) values ('";
			$comma36.=$commune.$v.$ccosec.$v.$dnupla.$v.$ccosub.$v.$majposac.$v.$bisufadc.$v.$majposad.$v.
						$bisufadd.$v.$majposar.$v.$bisufadr.$v.$majposagc.$v.$bisufadgc."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma36,$lk))){
					fwrite($feror,$comma36);}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma36))) { 
					fwrite($feror,$comma36);
				}
			}
			if ($voracle=='oracle') {
				fwrite($ptaxat_ora,$comma36."\n");
			}
		}
	/* Affiche � l'�cran le traitement d�j� effectu� */
		if ($section!=$ccosec) {
		$section=$ccosec;
		?><br>Traitement du non b�tie de la section : <?php echo $ccosec; }
		$linfo=fgets($fp);
	}
fclose($fp);
pg_exec($pgx,"update cadastre.parcel set ind = substr(commune,4,3)||'000'||ccosec||dnupla where oid=oid");
if ($voracle=='oracle') {
	fclose($parc_ora);
	fclose($psubd_ora);
	fclose($pexo_ora);
	fclose($ptaxat_ora);
}
}
/* set_time_limit(120); */
if ($bat !=""){
	$fp=fopen($bat,"r"); /* Ouverture du fichier du b�ti et chargement des tables*/
	if ($voracle=='oracle') {
		$ctab=fopen($rep_f."cree_tab.sql",a);				/* Int�gration dans le script sql de l'insertion des donn�es */
		fwrite($ctab,"start ".$rep_f."batidgi.ora;\n");		/* Donn�es de la table batidgi */
		fwrite($ctab, "commit;");
		fwrite($ctab,"start ".$rep_f."bdes.ora;\n");			/* Donn�es de la table description */
		fwrite($ctab, "commit;");
		fwrite($ctab,"start ".$rep_f."bsub.ora;\n");			/* Donn�es de la table subdivision */
		fwrite($ctab, "commit;");
		fwrite($ctab,"start ".$rep_f."bexo.ora;\n");			/* Donn�es de la table �xon�ration */
		fwrite($ctab, "commit;");
		fwrite($ctab,"start ".$rep_f."btax.ora;\n");			/* Donn�es de la table taxation */
		fwrite($ctab, "commit;");
		fwrite($ctab,"start ".$rep_f."bhab.ora;\n");			/* Donn�es de la table descriptif d'habitation */
		fwrite($ctab, "commit;");
		fwrite($ctab,"start ".$rep_f."bpro.ora;\n");			/* Donn�es de la table descriptif de local professionnel */
		fwrite($ctab, "commit;");
		fwrite($ctab,"start ".$rep_f."bdep.ora;\n");			/* Donn�es de la table descriptif de d�pendance */
		fwrite($ctab, "commit;");
		fclose($ctab);
		$batidgi_ora=fopen($rep_f."batidgi.ora",w);			/* Ouverture en �criture des fichiers de donn�es */
		$bdes_ora=fopen($rep_f."bdes.ora",w);
		$bsubd_ora=fopen($rep_f."bsub.ora",w);
		$bexo_ora=fopen($rep_f."bexo.ora",w);
		$btax_ora=fopen($rep_f."btax.ora",w);
		$bhab_ora=fopen($rep_f."bhab.ora",w);
		$bpro_ora=fopen($rep_f."bpro.ora",w);
		$bdep_ora=fopen($rep_f."bdep.ora",w);
	}
	$section=" ";$i=0;$j=0;$k=0;$l=0;$v="','";
	echo "Traitement du bati en cours!";
	while (!feof($fp)){
		set_time_limit(3);
		$linfo=fgets($fp);
		$code_commune=substr($linfo,0,6);
		$invar=substr($linfo,6,10);
		$cenr=substr($linfo,30,2); 
		if ($cenr==00){
			$ccopre=substr($linfo,35,3);
			$ccosec=substr($linfo,38,2);
			$dnupla=substr($linfo,40,4);
			$dnubat=substr($linfo,45,2);
			$desca=substr($linfo,47,2);
			$dniv=substr($linfo,49,2);
			$dpor=substr($linfo,51,5);
			$ccoriv=substr($linfo,56,4);
			$ccovoi=substr($linfo,61,5);
			$dnvoiri=substr($linfo,66,4);
			$dindic=substr($linfo,70,1);
			$ccocif=substr($linfo,71,4);
			$dvoilib=addslashes(substr($linfo,75,30));
			$comma00="insert into cadastre.batidgi (commune,invar,ccopre,ccosec,dnupla,dnubat,desca,dniv,dpor,ccoriv,ccovoi,dnvoiri,
							dindic,ccocif,dvoilib ) values ('";
			$comma00.=$code_commune.$v.$invar.$v.$ccopre.$v.$ccosec.$v.$dnupla.$v.$dnubat.$v.$desca.$v.$dniv.$v.$dpor.$v.$ccoriv.$v.
						$ccovoi.$v.$dnvoiri.$v.$dindic.$v.$ccocif.$v.$dvoilib."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma00,$lk))){
					fwrite($feror,$comma00);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma00))) { 
					fwrite($feror,$comma00);
				}
			}
			if ($voracle=='oracle') {
				fwrite($batidgi_ora,$comma00."\n");
			}
		}elseif ($cenr==10){
			$gpdl=substr($linfo,35,1);
			$dsrpar=substr($linfo,36,1);
			$dnupro=substr($linfo,37,6);
			$jdata=substr($linfo,43,8);
			$dnufnl=substr($linfo,51,6);
			$ccoeva=substr($linfo,57,1);
			$ccitlv=substr($linfo,58,1);
			$dteloc=substr($linfo,59,1);
			$gtauom=substr($linfo,60,2);
			$dcomrd=substr($linfo,62,3);
			$ccoplc=substr($linfo,65,1);
			$cconlc=substr($linfo,66,2);
			$dvltrt=substr($linfo,68,9);
			$ccoape=substr($linfo,77,4);
			$cc48lc=substr($linfo,81,2);
			$dloy48a=substr($linfo,83,9);
			$top48=substr($linfo,92,1);
			$dnatic=substr($linfo,93,1);
			$dnupas=substr($linfo,94,8);
			$gnexcf=substr($linfo,102,2);
			$dtaucf=substr($linfo,104,3);
			$cchpr=substr($linfo,107,1);
			$jannat=substr($linfo,108,4);
			$dnbniv=substr($linfo,112,2);
			$hmsem=substr($linfo,114,1);
			$postel=substr($linfo,115,1);
			$dnatcg=substr($linfo,116,2);
			$jdatcgl=substr($linfo,118,8);
			$dnutbx=substr($linfo,126,6);
			$dvltla=substr($linfo,132,9);
			$janloc=substr($linfo,141,4);
			$ccsloc=substr($linfo,145,2);
			$fburx=substr($linfo,147,1);
			$gimtom=substr($linfo,148,1);
			$cbtabt=substr($linfo,149,2);
			$jdtabt=substr($linfo,151,4);
			$jrtabt=substr($linfo,155,4);
			$comma10="insert into cadastre.b_desdgi (commune,invar,gpdl,dsrpar,dnupro,jdata,dnufnl,ccoeva,dteloc,gtauom,dcomrd,ccoplc,
							  cconlc,dvltrt,ccoape,cc48lc,dloy48a,top48,dnatic,cchpr,jannat,dnbniv,hmsem,postel,cbtabt,jdtabt,jrtabt) values ('";
			$comma10.=$code_commune.$v.$invar.$v.$gpdl.$v.$dsrpar.$v.$dnupro.$v.$jdata.$v.$dnufnl.$v.$ccoeva.$v.$dteloc.$v.$gtauom.$v.$dcomrd.$v.
						$ccoplc.$v.$cconlc.$v.$dvltrt.$v.$ccoape.$v.$cc48lc.$v.$dloy48a.$v.$top48.$v.$dnatic.$v.$cchpr.$v.$jannat.$v.$dnbniv.$v.
						$hmsem.$v.$postel.$v.$cbtabt.$v.$jdtabt.$v.$jrtabt."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma10,$lk))){
					fwrite($feror,$comma10."\n");
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma10))) { 
					fwrite($feror,$comma10);
				}
			}
			if ($voracle=='oracle') {
				fwrite($bdes_ora,$comma10);
			}
		}elseif ($cenr==21){
			$dnupev=substr($linfo,27,3);
			$ccoaff=substr($linfo,35,1);
			$ccostb=substr($linfo,36,1);
			$dcapec=substr($linfo,37,2);
			$dcetlc=substr($linfo,39,3);
			$dcsplc=substr($linfo,42,3);
			$dsupot=substr($linfo,45,6);
			$dvlper=substr($linfo,51,9);
			$dvlpera=substr($linfo,60,9);
			$gnexpl=substr($linfo,69,2);
			$libocc=substr($linfo,71,30);
			$ccthp=substr($linfo,101,1);
			$retimp=substr($linfo,102,1);
			$dnuref=substr($linfo,103,3);
			$rclsst=substr($linfo,106,32);
			$gnidom=substr($linfo,138,1);
			$dcsglc=substr($linfo,139,3);
			$ccogrb=substr($linfo,142,1);
			$cocdi=substr($linfo,143,4);
			$cosatp=substr($linfo,147,3);
			$gsatp=substr($linfo,150,1);
			$clocv=substr($linfo,151,1);
			$dvltpe=substr($linfo,152,9);
			$dcralc=substr($linfo,161,3);
			$comma21="insert into cadastre.b_subdgi (commune,invar,dnupev,ccoaff,ccostb,dcapec,dcetlc,dcsplc,dsupot,
							  dvlper,dvlpera,gnexpl,ccthp,retimp,dnuref,gnidom,dcsglc,dvltpe,dcralc) values ('";
			$comma21.=$code_commune.$v.$invar.$v.$dnupev.$v.$ccoaff.$v.$ccostb.$v.$dcapec.$v.$dcetlc.$v.$dcsplc.$v.$dsupot.$v.
							  $dvlper.$v.$dvlpera.$v.$gnexpl.$v.$ccthp.$v.$retimp.$v.$dnuref.$v.$gnidom.$v.$dcsglc.$v.$dvltpe.$v.$dcralc."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma21,$lk))){
					fwrite($feror,$comma21);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma21))) { 
					fwrite($feror,$comma21);
				}
			}
			if ($voracle=='oracle') {
				fwrite($bsubd_ora,$comma21."\n");
			}
		}elseif ($cenr==30){
			$dnupev=substr($linfo,27,3);
			$janbil=substr($linfo,23,4);
			$dnuord=substr($linfo,32,3);
			$ccolloc=substr($linfo,35,2);
			$pexb=substr($linfo,37,5);
			$gnextl=substr($linfo,42,2);
			$jandeb=substr($linfo,44,4);
			$janimp=substr($linfo,48,4);
			$dvldif2=substr($linfo,102,9);
			$dvldif2a=substr($linfo,112,9);
			$fcexb2=substr($linfo,122,9);
			$fcexba2=substr($linfo,132,9);
			$rcexba2=substr($linfo,142,9);
			$comma30="insert into cadastre.b_exodgi (commune,invar,dnupev,dnuord,ccolloc,pexb,gnextl,jandeb,janimp,
							  dvldif2,dvldif2a,fcexb2,fcexba2,rcexba2) values ('";
			$comma30.=$code_commune.$v.$invar.$v.$dnupev.$v.$dnuord.$v.$ccolloc.$v.$pexb.$v.$gnextl.$v.$jandeb.$v.$janimp.$v.
						$dvldif2.$v.$dvldif2a.$v.$fcexb2.$v.$fcexba2.$v.$rcexba2."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma30,$lk))){
					fwrite($feror,$comma30);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma30))) { 
					fwrite($feror,$comma30);
				}
			}
			if ($voracle=='oracle') {
				fwrite($bexo_ora,$comma30."\n");
			}
		}elseif ($cenr==36){
			$dnupev=substr($linfo,27,3);
			$janbil=substr($linfo,23,4);
			$lbaic=substr($linfo,35,9);
			$vlbaiac=substr($linfo,45,9);
			$bipeviac=substr($linfo,55,9);
			$vlbaid=substr($linfo,65,9);
			$vlbaiad=substr($linfo,75,9);
			$bipeviad=substr($linfo,85,9);
			$vlbair=substr($linfo,95,9);
			$vlbaiar=substr($linfo,105,9);
			$bipeviar=substr($linfo,115,9);
			$vlbaigc=substr($linfo,125,9);
			$vlbaiagc=substr($linfo,135,9);
			$bipeviagc=substr($linfo,145,9);
			$comma36="insert into cadastre.b_taxdgi (commune,invar,dnupev,vlbaic,vlbaiac,bipeviac,vlbaid,vlbaiad,bipeviad,
							  vlbair,vlbaiar,bipeviar,vlbaigc,vlbaiagc,bipeviagc) values ('";
			$comma36.=$code_commune.$v.$invar.$v.$dnupev.$v.$vlbaic.$v.$vlbaiac.$v.$bipeviac.$v.$vlbaid.$v.$vlbaiad.$v.$bipeviad.$v.
						$vlbair.$v.$vlbaiar.$v.$bipeviar.$v.$vlbaigc.$v.$vlbaiagc.$v.$bipeviagc."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma36,$lk))){
					fwrite($feror,$comma36);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma36))) { 
					fwrite($feror,$comma36);
				}
			}
			if ($voracle=='oracle') {
				fwrite($btax_ora,$comma36."\n");
			}
		}elseif ($cenr==40){
			$dnupev=substr($linfo,27,3);
			$dnudes=substr($linfo,32,3);
			$cconadga=substr($linfo,35,2);
			$dsueicga=substr($linfo,37,6);
			$dcimei=substr($linfo,43,2);
			$cconadcv=substr($linfo,45,2);
			$dsueiccv=substr($linfo,47,6);
			$dcimeicv=substr($linfo,53,2);
			$cconadgr=substr($linfo,55,2);
			$dsueicgr=substr($linfo,57,6);
			$dcimeia=substr($linfo,63,2);
			$cconadtr=substr($linfo,65,2);
			$dsueictr=substr($linfo,67,6);
			$dcimeitr=substr($linfo,73,2);
			$geaulc=substr($linfo,75,1);
			$gelelc=substr($linfo,76,1);
			$gesclc=substr($linfo,77,1);
			$ggazlc=substr($linfo,78,1);
			$gasclc=substr($linfo,79,1);
			$gchclc=substr($linfo,80,1);
			$gvorlc=substr($linfo,81,1);
			$gteglc=substr($linfo,82,1);
			$dnbbai=substr($linfo,83,2);
			$dnbdou=substr($linfo,85,2);
			$dnblav=substr($linfo,87,2);
			$dnbwc=substr($linfo,89,2);
			$deqdha=substr($linfo,91,3);
			$dnbppr=substr($linfo,94,2);
			$dnbsam=substr($linfo,96,2);
			$dnbcha=substr($linfo,98,2);
			$dnbcu8=substr($linfo,100,2);
			$dnbcu9=substr($linfo,102,2);
			$dnbsea=substr($linfo,104,2);
			$dnbann=substr($linfo,106,2);
			$dnbpdc=substr($linfo,108,2);
			$dsupdc=substr($linfo,110,6);
			$dmatgm=substr($linfo,116,2);
			$dmatto=substr($linfo,118,2);
			$jannat=substr($linfo,120,4);
			$detent=substr($linfo,124,1);
			$dnbniv=substr($linfo,125,2);
			$comma40="insert into cadastre.b_habdgi (commune,invar,dnupev,dnudes,cconadga,dsueicga,dcimei,cconadcv,dsueiccv,
							  dcimeicv,cconadgr,dsueicgr,dcimeia,cconadtr,dsueictr,dcimeitr,geaulc,
							  gelelc,gesclc,ggazlc,gasclc,gchclc,gvorlc,gteglc,dnbbai,
							  dnbdou,dnblav,dnbwc,deqdha,dnbppr,dnbsam,dnbcha,dnbcu8,
							  dnbcu9,dnbsea,dnbann,dnbpdc,dsupdc,dmatgm,dmatto,jannat,detent,dnbniv) values ('";
			$comma40.=$code_commune.$v.$invar.$v.$dnupev.$v.$dnudes.$v.$cconadga.$v.$dsueicga.$v.$dcimei.$v.$cconadcv.$v.$dsueiccv.$v.
							$dcimeicv.$v.$cconadgr.$v.$dsueicgr.$v.$dcimeia.$v.$cconadtr.$v.$dsueictr.$v.$dcimeitr.$v.$geaulc.$v.
							$gelelc.$v.$gesclc.$v.$ggazlc.$v.$gasclc.$v.$gchclc.$v.$gvorlc.$v.$gteglc.$v.$dnbbai.$v.$dnbdou.$v.$dnblav.$v.
							$dnbwc.$v.$deqdha.$v.$dnbppr.$v.$dnbsam.$v.$dnbcha.$v.$dnbcu8.$v.$dnbcu9.$v.$dnbsea.$v.$dnbann.$v.$dnbpdc.$v.
							$dsupdc.$v.$dmatgm.$v.$dmatto.$v.$jannat.$v.$detent.$v.$dnbniv."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma40,$lk))){
					fwrite($feror,$comma40);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma40))) { 
					fwrite($feror,$comma40);
				}
			}
			if ($voracle=='oracle') {
				fwrite($bhab_ora,$comma40."\n");
			}
		}elseif ($cenr==50){
			$dnupev=substr($linfo,27,3);
			$dnudes=substr($linfo,32,3);
			$vsupot=substr($linfo,35,9);
			$vsurz1=substr($linfo,44,9);
			$vsurz2=substr($linfo,53,9);
			$vsurz3=substr($linfo,62,9);
			$vsurzt=substr($linfo,71,9);
			$vsurb1=substr($linfo,81,9);
			$vsurb2=substr($linfo,90,9);
			$comma50="insert into cadastre.b_prodgi (commune,invar,dnupev,dnudes,vsurzt) values ('";
			$comma50.=$code_commune.$v.$invar.$v.$dnupev.$v.$dnudes.$v.$vsurzt."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma50,$lk))){
					fwrite($feror,$comma50);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma50))) { 
					fwrite($feror,$comma50);
				}
			}
			if ($voracle=='oracle') {
				fwrite($bpro_ora,$comma50."\n");
			}
		}elseif ($cenr==60){
			$dnupev=substr($linfo,27,3);
			$dnudes=substr($linfo,32,3);
			$dsudep=substr($linfo,35,6);
			$cconad=substr($linfo,41,2);
			$asitet=substr($linfo,43,6);
			$dmatgm=substr($linfo,49,2);
			$dmatto=substr($linfo,51,2);
			$detent=substr($linfo,53,1);
			$geaulc=substr($linfo,54,1);
			$gelelc=substr($linfo,55,1);
			$gchclc=substr($linfo,56,1);
			$dnbbai=substr($linfo,57,2);
			$dnbdou=substr($linfo,59,2);
			$dnblav=substr($linfo,61,2);
			$dnbwc=substr($linfo,63,2);
			$deqtlc=substr($linfo,65,3);
			$dcimlc=substr($linfo,68,2);
			$dcetde=substr($linfo,70,3);
			$dcspde=substr($linfo,73,3);
			$comma60="insert into cadastre.b_depdgi (commune,invar,dnupev,dnudes,dsudep,cconad,asitet,dmatgm,dmatto,detent,geaulc,gelelc,
							  gchclc,dnbbai,dnbdou,dnblav,dnbwc,deqtlc,dcimlc,dcetde,dcspde) values ('";
			$comma60.=$code_commune.$v.$invar.$v.$dnupev.$v.$dnudes.$v.$dsudep.$v.$cconad.$v.$asitet.$v.$dmatgm.$v.$dmatto.$v.$detent.$v.$geaulc.$v.
						$gelelc.$v.$gchclc.$v.$dnbbai.$v.$dnbdou.$v.$dnblav.$v.$dnbwc.$v.$deqtlc.$v.$dcimlc.$v.$dcetde.$v.$dcspde."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma60,$lk))){
					fwrite($feror,$comma60);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma60))) { 
					fwrite($feror,$comma60);
				}
			}
			if ($voracle=='oracle') {
				fwrite($bdep_ora,$comma60."\n");
			}
		}
	}
	fclose($fp);
	if ($voracle=='oracle') {
		fclose($batidgi_ora);			/* Fermeture des fichiers de donn�es */
		fclose($bdes_ora);			
		fclose($bsubd_ora);			
		fclose($bexo_ora);			
		fclose($btax_ora);			
		fclose($bhab_ora);			
		fclose($bpro_ora);			
		fclose($bdep_ora);
	}
}
set_time_limit(20);
if ($prop !=""){
	$fp=fopen($prop,"r"); /* Ouverture du fichier du propri�taire et chargement des tables*/
	if ($voracle=='oracle') {
		$ctab=fopen($rep_f."cree_tab.sql",a);				/* Int�gration dans le script sql de l'insertion des donn�es */
		fwrite($ctab,"start ".$rep_f."propri.ora;\n");		/* Donn�es de la table Propri�taire */
		fwrite($ctab, "commit;\n");
		$prop_ora=fopen($rep_f."propri.ora",w);
	}
	$section=" ";$i=0;$j=0;$k=0;$l=0;$v="','";
	echo "Traitement des propri�taires en cours!";
	while (!feof($fp)){
		set_time_limit(2);
		$linfo=fgets($fp);
		$code_commune=substr($linfo,0,6);
		$cgroup=substr($linfo,6,1);
		$dnumcp=substr($linfo,7,5);
		$dnulp=substr($linfo,12,2);
		$ccocif=substr($linfo,14,4);
		$dnuper=substr($linfo,18,6);
		$ccodro=substr($linfo,24,1);
		$ccodem=substr($linfo,25,1);
		$gdesip=substr($linfo,26,1);
		$gtoper=substr($linfo,27,1);
		$ccoqua=substr($linfo,28,1);
		$gnexcf=substr($linfo,29,2);
		$dtaucf=substr($linfo,31,3);
		$dnatpr=substr($linfo,34,3);
		$ccogrm=substr($linfo,37,2);
		$dsglpm=substr($linfo,39,10);
		$dforme=substr($linfo,49,7);
		$ddenom=addslashes(substr($linfo,56,60));
		$dlign3=addslashes(substr($linfo,120,30));
		$dlign4=addslashes(substr($linfo,150,36));
		$dlign5=addslashes(substr($linfo,186,30));
		$dlign6=addslashes(substr($linfo,216,32));
		$ccopay=addslashes(substr($linfo,248,3));
		$dqualp=substr($linfo,286,3);
		$dnomlp=addslashes(substr($linfo,289,30));
		$dprnlp=addslashes(substr($linfo,319,15));
		$jdatnss=substr($linfo,334,10);
		$dldnss=addslashes(substr($linfo,344,58));
		$epxnee=substr($linfo,402,3);
		$dnomcp=addslashes(substr($linfo,405,30));
		$dprncp=addslashes(substr($linfo,435,15));
		$dsiren=substr($linfo,466,10);
		$comma="insert into cadastre.propriet (commune,cgroup,dnumcp,dnulp,ccocif,dnuper,ccodro,ccodem,gdesip,gtoper,ccoqua,dnatpr,ccogrm,
						  dsglpm,dforme,ddenom,dlign3,dlign4,dlign5,dlign6,ccopay,dqualp,dnomlp,dprnlp,jdatnss,
						  dldnss,epxnee,dnomcp,dprncp,dsiren,prop1) values ('";
		$comma.=$code_commune.$v.$cgroup.$v.$dnumcp.$v.$dnulp.$v.$ccocif.$v.$dnuper.$v.$ccodro.$v.$ccodem.$v.$gdesip.$v.$gtoper.$v.
					$ccoqua.$v.$dnatpr.$v.$ccogrm.$v.$dsglpm.$v.$dforme.$v.$ddenom.$v.$dlign3.$v.$dlign4.$v.$dlign5.$v.$dlign6.$v.
					$ccopay.$v.$dqualp.$v.$dnomlp.$v.$dprnlp.$v.$jdatnss.$v.$dldnss.$v.$epxnee.$v.$dnomcp.$v.$dprncp.$v.$dsiren.$v.$cgroup.$dnumcp."');";
		if ($vmysql=='mysql') {
			if (!(mysql_query($comma,$lk))){
				fwrite($feror,$comma);
			}
		}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma))) { 
					fwrite($feror,$comma);
				}
			}
			if ($voracle=='oracle') {
				$comma=str_replace('\\','\'',$comma);
				fwrite($prop_ora,$comma."\n");
			}
	}
	fclose($fp);
	if ($voracle=='oracle') {
		fclose($prop_ora);			/* Fermeture des fichiers de donn�es */
	}
}
set_time_limit(20);
if ($fant !=""){
	$fp=fopen($fant,"r"); /* Ouverture du fichier fantoir et chargement de la table */
	if ($voracle=='oracle') {
		$ctab=fopen($rep_f."cree_tab.sql",a);				/* Int�gration dans le script sql de l'insertion des donn�es */
		fwrite($ctab,"start ".$rep_f."voie.ora;\n");			/* Donn�es de la table Voies */
		fwrite($ctab, "commit;\n");
		$voie_ora=fopen($rep_f."voie.ora",w);
	}
	$section=" ";$i=0;$j=0;$k=0;$l=0;$v="','";
	echo "Traitement des voies en cours!";
	while (!feof($fp)){
		set_time_limit(2);
		$linfo=fgets($fp);
		$code_commune=substr($linfo,0,6);
		$code=substr($linfo,6,4);
		$nat=substr($linfo,11,4);
		$lib=substr($linfo,15,26);
		$caract=substr($linfo,48,1);
		$typ=substr($linfo,108,1);
		if ($code!=""){
			if ($nat=="AER "){$libel="A�rodrome ";}
			elseif ($nat=="AERG"){$libel="A�rogare ";}
			elseif ($nat=="AGL "){$libel="Agglom�ration ";}
			elseif ($nat=="ALL "){$libel="All�e ";}
			elseif ($nat=="ACH "){$libel="Ancien chemin ";}
			elseif ($nat=="ART "){$libel="Ancienne route ";}
			elseif ($nat=="ANGL"){$libel="Angle ";}
			elseif ($nat=="ARC "){$libel="Arcade ";}
			elseif ($nat=="AUT "){$libel="Autoroute  ";}
			elseif ($nat=="AV  "){$libel="Avenue ";}
			elseif ($nat=="BRE "){$libel="Barri�re ";}
			elseif ($nat=="BSN "){$libel="Bassin ";}
			elseif ($nat=="BER "){$libel="Berge ";}
			elseif ($nat=="BD  "){$libel="Boulevard ";}
			elseif ($nat=="BRG "){$libel="Bourg ";}
			elseif ($nat=="BRTL"){$libel="Bretelle ";}
			elseif ($nat=="CALL"){$libel="Calle, callada  ";}
			elseif ($nat=="CAMI"){$libel="Camin ";}
			elseif ($nat=="CPG "){$libel="Camping ";}
			elseif ($nat=="CAN "){$libel="Canal ";}
			elseif ($nat=="CAR "){$libel="Carrefour ";}
			elseif ($nat=="CAE "){$libel="Carriera ";}
			elseif ($nat=="CARE"){$libel="Carri�re ";}
			elseif ($nat=="CASR"){$libel="Caserne ";}
			elseif ($nat=="CTRE"){$libel="Centre ";}
			elseif ($nat=="CHP "){$libel="Champ ";}
			elseif ($nat=="CHA "){$libel="Chasse ";}
			elseif ($nat=="CHT "){$libel="Chateau ";}
			elseif ($nat=="CHS "){$libel="Chauss�e ";}
			elseif ($nat=="CHE "){$libel="Chemin ";}
			elseif ($nat=="CHEM"){$libel="Cheminement ";}
			elseif ($nat=="CC  "){$libel="Chemin communal ";}
			elseif ($nat=="CD  "){$libel="Chemin d�partemental ";}
			elseif ($nat=="CR  "){$libel="Chemin rural ";}
			elseif ($nat=="CF  "){$libel="Chemin forestier ";}
			elseif ($nat=="CHV "){$libel="Chemin vicinal ";}
			elseif ($nat=="CTR "){$libel="Contour ";}
			elseif ($nat=="COR "){$libel="Corniche ";}
			elseif ($nat=="CORO"){$libel="Coron ";}
			elseif ($nat=="CLR "){$libel="Couloir ";}
			elseif ($nat=="CRS "){$libel="Cours ";}
			elseif ($nat=="CIVE"){$libel="Coursive ";}
			elseif ($nat=="CRX "){$libel="Croix ";}
			elseif ($nat=="DARS"){$libel="Darse ";}
			elseif ($nat=="DSC "){$libel="Descente ";}
			elseif ($nat=="DEVI"){$libel="D�viation ";}
			elseif ($nat=="DIG "){$libel="Digue ";}
			elseif ($nat=="DOM "){$libel="Domaine ";}
			elseif ($nat=="DRA "){$libel="Draille ";}
			elseif ($nat=="ECA "){$libel="Ecart ";}
			elseif ($nat=="ECL "){$libel="Ecluse ";}
			elseif ($nat=="EMBR"){$libel="Embranchement ";}
			elseif ($nat=="EMP "){$libel="Emplacement ";}
			elseif ($nat=="ENV "){$libel="Enclave ";}
			elseif ($nat=="ENC "){$libel="Enclos ";}
			elseif ($nat=="ESC "){$libel="Escalier ";}
			elseif ($nat=="ESPA"){$libel="Espace ";}
			elseif ($nat=="ESP "){$libel="Esplanade ";}
			elseif ($nat=="ETNG"){$libel="Etang ";}
			elseif ($nat=="FG  "){$libel="Faubourg ";}
			elseif ($nat=="FRM "){$libel="Ferme ";}
			elseif ($nat=="FD  "){$libel="Fond ";}
			elseif ($nat=="FON "){$libel="Fontaine ";}
			elseif ($nat=="FOR "){$libel="For�t ";}
			elseif ($nat=="FOS "){$libel="Fosse ";}
			elseif ($nat=="GAL "){$libel="Galerie ";}
			elseif ($nat=="GBD "){$libel="Grand boulevard ";}
			elseif ($nat=="GPL "){$libel="Grand place ";}
			elseif ($nat=="GR  "){$libel="Grande rue ";}
			elseif ($nat=="GREV"){$libel="Gr�ve ";}
			elseif ($nat=="HAB "){$libel="Habitation ";}
			elseif ($nat=="HLG "){$libel="Halage ";}
			elseif ($nat=="HLE "){$libel="Halle ";}
			elseif ($nat=="HAM "){$libel="Hameau ";}
			elseif ($nat=="HTR "){$libel="Hauteur ";}
			elseif ($nat=="HIP "){$libel="Hippodrome ";}
			elseif ($nat=="IMP "){$libel="Impasse ";}
			elseif ($nat=="JARD"){$libel="Jardin ";}
			elseif ($nat=="JTE "){$libel="Jet�e ";}
			elseif ($nat=="LEVE"){$libel="Lev�e ";}
			elseif ($nat=="LIGN"){$libel="Ligne ";}
			elseif ($nat=="LOT "){$libel="Lotissement ";}
			elseif ($nat=="MAIS"){$libel="Maison ";}
			elseif ($nat=="MAR "){$libel="March� ";}
			elseif ($nat=="MRN "){$libel="Marina ";}
			elseif ($nat=="MTE "){$libel="Mont�e ";}
			elseif ($nat=="MNE "){$libel="Morne ";}
			elseif ($nat=="NTE "){$libel="Nouvelle route ";}
			elseif ($nat=="PKG "){$libel="Parking ";}
			elseif ($nat=="PRV "){$libel="Parvis ";}
			elseif ($nat=="PAS "){$libel="Passage ";}
			elseif ($nat=="PLE "){$libel="Passerelle ";}
			elseif ($nat=="PCH "){$libel="Petit chemin ";}
			elseif ($nat=="PTA "){$libel="Petite all�e ";}
			elseif ($nat=="PAE "){$libel="Petite avenue ";}
			elseif ($nat=="PRT "){$libel="Petite route ";}
			elseif ($nat=="PTR "){$libel="Petite rue ";}
			elseif ($nat=="PHAR"){$libel="Phare ";}
			elseif ($nat=="PIST"){$libel="Piste ";}
			elseif ($nat=="PLA "){$libel="Placa ";}
			elseif ($nat=="PL  "){$libel="Place ";}
			elseif ($nat=="PTTE"){$libel="Placette ";}
			elseif ($nat=="PLCI"){$libel="Placis ";}
			elseif ($nat=="PLAG"){$libel="Plage ";}
			elseif ($nat=="PLN "){$libel="Plaine ";}
			elseif ($nat=="PLT "){$libel="Plateau ";}
			elseif ($nat=="PNT "){$libel="Pointe ";}
			elseif ($nat=="PCHE"){$libel="Porche ";}
			elseif ($nat=="PTE "){$libel="Porte ";}
			elseif ($nat=="POST"){$libel="Poste ";}
			elseif ($nat=="POT "){$libel="Poterne ";}
			elseif ($nat=="PROM"){$libel="Promenade ";}
			elseif ($nat=="QUA "){$libel="Quartier ";}
			elseif ($nat=="RAC "){$libel="Raccourci ";}
			elseif ($nat=="RPE "){$libel="Rampe ";}
			elseif ($nat=="RVE "){$libel="Ravine ";}
			elseif ($nat=="REM "){$libel="Rempart ";}
			elseif ($nat=="RES "){$libel="R�sidence ";}
			elseif ($nat=="ROC "){$libel="Rocade ";}
			elseif ($nat=="RPT "){$libel="Rond-point ";}
			elseif ($nat=="RTD "){$libel="Rotonde ";}
			elseif ($nat=="RTE "){$libel="Route ";}
			elseif ($nat=="D   "){$libel="Route d�partementale ";}
			elseif ($nat=="N   "){$libel="Route nationale ";}
			elseif ($nat=="RLE "){$libel="Ruelle ";}
			elseif ($nat=="RULT"){$libel="Ruellette ";}
			elseif ($nat=="RUET"){$libel="Ruette ";}
			elseif ($nat=="RUIS"){$libel="Ruisseau ";}
			elseif ($nat=="SEN "){$libel="Sentier ";}
			elseif ($nat=="SQ  "){$libel="Square ";}
			elseif ($nat=="STDE"){$libel="Stade ";}
			elseif ($nat=="TRN "){$libel="Terrain ";}
			elseif ($nat=="TSSE"){$libel="Terrasse ";}
			elseif ($nat=="TER "){$libel="Terre ";}
			elseif ($nat=="TPL "){$libel="Terre-plein ";}
			elseif ($nat=="TRT "){$libel="Tertre ";}
			elseif ($nat=="TRAB"){$libel="Traboule ";}
			elseif ($nat=="TRA "){$libel="Traverse ";}
			elseif ($nat=="TUN "){$libel="Tunnel ";}
			elseif ($nat=="VALL"){$libel="Vall�e ";}
			elseif ($nat=="VEN "){$libel="Venelle ";}
			elseif ($nat=="VIAD"){$libel="Viaduc ";}
			elseif ($nat=="VTE "){$libel="Vieille route ";}
			elseif ($nat=="VCHE"){$libel="Vieux chemin ";}
			elseif ($nat=="VLA "){$libel="Villa ";}
			elseif ($nat=="VGE "){$libel="Village ";}
			elseif ($nat=="VIL "){$libel="Ville ";}
			elseif ($nat=="VC  "){$libel="Voie communale ";}
			elseif ($nat=="VOIR"){$libel="Voirie ";}
			elseif ($nat=="VOUT"){$libel="Voute ";}
			elseif ($nat=="VOY "){$libel="Voyeul ";}
			else {$libel=$nat." ";
			}
			$libel.=$lib;
			$comma="insert into cadastre.voies (commune, code_voie, nom_voie, caract, typ ) values ('";
			$comma.=$code_commune.$v.$code.$v.$libel.$v.$caract.$v.$typ."');";
			if ($vmysql=='mysql') {
				if (!(mysql_query($comma,$lk))){
					fwrite($feror,$comma);
				}
			}
			if ($vpg=='pgsql') {
				if (!(pg_exec($pgx,$comma))) { 
					fwrite($feror,$comma);
				}
			}
			if ($voracle=='oracle') {
				fwrite($voie_ora,$comma."\n");
			}
		}
	}
	fclose($fp);
	if ($voracle=='oracle') {
		fclose($voie_ora);			/* Fermeture des fichiers de donn�es */
	}
}
fclose($feror);
	if ($voracle=='oracle') {
		echo "Lancez SQLPLUS puis la commande :'start ".$rep_f."cree_tab.sql; ' pour r�aliser la cr�ation et l'insertion des donn�es dans Oracle.";
	}

 ?>
</body>
</html>
