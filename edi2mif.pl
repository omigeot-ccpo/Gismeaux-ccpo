#!/usr/bin/perl
#
# mï¿½tadonnï¿½es pour l'ï¿½diteur utilisï¿½ (gvim)
# vim: tabstop=4 sw=4
#
# edi2mif.pl version 1.1 (C) Michel WURTZ 15/7/2006
# Convertisseur simple EDIGEO PCI vers MIF/MID
#
# Utilisation : perl edi2mif.pl <rï¿½pertoire contenant les fichiers EdiGEO> <rï¿½pertoire pour les fichiers MIF/MID>
#
#----------------------------------------------------------------------------
# ajout du traitement pour Postgrï¿½SQL+PostGis @robert Leguay 20/2/2007
#
#Utilisation : perl edi2mif.pl <rï¿½pertoire contenant le fichier EdiGEO> <rï¿½pertoire pour les fichiers SQL> <Schï¿½ma Postgrï¿½SQL d'insertion> <dï¿½partement(2c)+agglo(1c)> <interrupteur de crï¿½ation des tables> <projection>
#
# ---------------------------------------------------------------------------
# Ce logiciel est diffusï¿½ sous les termes et conditions de la licence CECILL
# Voir le fichier joint Licence_CeCILL_V2-fr.txt pour plus de dï¿½tails
# ---------------------------------------------------------------------------
#
# Simple = pas de vï¿½rification de l'ï¿½change et rï¿½sultats imprï¿½visibles en cas
# de donnï¿½es non cohï¿½rentes en entrï¿½e... rï¿½alisï¿½ essentiellement d'aprï¿½s de
# vagues reminiscences (12 ans) sur EDIGï¿½O, la lecture des fichiers DGI et
# quelques coups d'oeil sur un exemplaire de la norme NF Z 52000 retrouvï¿½
# dans mes archives et datï¿½ de novembre 1998 (donc avant son introduction
# officielle), probablement le draft final du groupe de travail CEN/TC 287
#
# Simple = un seul format de sortie : MIF/MID
#
# Simple = surement pas trï¿½s optimisï¿½ pour la rapiditï¿½ et gournand en mï¿½moire
#
# Simple = ï¿½crit en perl basique pour la portabilitï¿½ et en perl parce que
# finalement ce n'est que du traitement de texte un peu sophistiquï¿½ pour
# passer du format EDIGï¿½O au format MIF/MID
#
# Bon, maintenant, c'est du logiciel "libre" (Cf ci-dessus), et donc si vous
# voulez mieux, ï¿½ vous de vous retrousser les manches...
#
# Pour des messages en cas de problï¿½me, mettre debug_on ï¿½ une valeur > 0

# TODO list : 
# - ajouter une interface permettant la conversion d'un ensemble de rï¿½peroires
# - Internationaliser ?
# - ajouter une interface graphique ?

# -- Dï¿½but des paramï¿½tres modifiables -----------------------------------------
# mettre $midmif ï¿½ 0 pour ne pas extraire au format mapinfo
$midmif=0;

# mettre $postgres ï¿½ 0 pour ne pas executer le traitement
$postgres=1;

# mettre $debug_on ï¿½ 1 ou plus pour des infos supplï¿½mentaires dans le log
$debug_on = 3;

# mettre $mif2tab ï¿½ 0 pour ne pas convertir les mif/mid au format natif MapInfo
# Si $mif2tab est ï¿½ 1, il est nï¿½cessaire d'installer le programme tab2tab
# (voir http://mitab.maptools.org)
$mif2tab = 0;

# Pour modifier la taille des ï¿½critures ï¿½ l'ï¿½cran (1.3 => + gros)
$echelle_texte = 1.0;

# Quelques tables (hash) utiles, ï¿½ventuellement modifiables :

# table des projections (ï¿½ complï¿½ter si nï¿½cessaire avec MAPINFOW.PRJ et
# la doc EDIGï¿½O).  Les valeurs de Bounds permettent de conserver la rï¿½solution
# des coordonnï¿½es PCI (cm ?) : ces valeurs donnent 1 mm, par dï¿½faut MapInfo ne
# donnat qu'environ 12 cm
%tproj=(
	'LAMB1', '3, 1002, 7, 0, 49.5, 48.59852278,50.39591167, 600000, 200000 Bounds (0.0, 0.0) (2000000.0, 2000000.0)',
	'LAMB2', '3, 1002, 7, 0, 46.8, 45.89891889,47.69601444, 600000, 200000 Bounds (0.0, 0.0) (2000000.0, 2000000.0)',
	'LAMB3', '3, 1002, 7, 0, 44.1, 43.19929139,44.99609389, 600000, 200000 Bounds (0.0, 0.0) (2000000.0, 2000000.0)',
	'LAMB4', '3, 1002, 7, 0, 42.165, 41.56038778, 42.76766333,234.358, 185861.369 Bounds (0.0, 0.0) (2000000.0, 2000000.0)',
	'LAMBE', '3, 1002, 7, 0, 46.8, 45.89891889,47.69601444, 600000, 2200000 Bounds (0.0, 1000000.0) (2000000.0, 3000000.0)'
);

# table des couches MapInfo / objets EDIGï¿½O PCI...
# Le nom des tables peut ï¿½tre ajustï¿½ si besoin...
#
# Les ï¿½critures sont rï¿½cupï¿½rï¿½es dans la table associï¿½e ï¿½ 'Z_1_2_2' sous forme
# d'objets texte si le nom est diffï¿½rent de '-' (ici on a pris 'ECRITURES')

%tfimi=(
	'Z_1_2_2', 'ECRITURES',
	'Z_1_0_2', 'T_LINE',
	'I_3_0_0', 'POINT_CST',
	'H_11_8_0', 'NUM_VOIE',
	'A_1_0_0', 'TRONC_ROUTE',
	'H_11_2_0', 'SUB_SECTION',
	'H_1_7_0', 'LIEU_DIT',
	'D_1_0_8', 'RIVIERES',
	'Z_1_0_1', 'SYMB_LIM',
	'H_11_1_0', 'SECTION',
	'Z_1_0_1', 'T_POINT',
	'H_11_4_0', 'PARC',
	'I_1_0_0', 'PT_CANEVAS',
	'Z_1_0_3', 'T_SURF',
	'H_11_7_0', 'ROUTES',
	'E_9_3_1', 'CIMETIERE',
	'H_1_6_0', 'COMMUNE',
	'I_2_4_0', 'BORNE_PARC',
	'H_11_5_0', 'SUBD_FISC',
	'E_2_1_0', 'BATI',
	'H_11_6_0', 'T_CHARGE',
	'A_1_0_5', 'ZONE_COMMUNIC'
);

# table des symboles ï¿½ utiliser pour les diffï¿½rents thï¿½mes.
# A complï¿½ter si besoin...  Il faut mettre la ou les lignes ï¿½ utiliser
# dans le .mif, y compris la fin de ligne (\n)

%tsymbol=(
	'D_1_0_8', "    Pen (1,2,255)\n    Brush (48,255)\n",
	'I_1_0_0', "    Symbol (42,14680288,12)\n",
	'I_2_4_0', "    Symbol (38,9445631,9)\n",
	'E_2_1_0', "    Pen (1,2,16711680)\n    Brush (17,16711680)\n"
);
# -- Fin des paramï¿½tres modifiables -------------------------------------------

# -- Programme principal ------------------------------------------------------

# Paramï¿½trage minimal...
die "Usage : edi2mif <rï¿½pertoire source> <rï¿½pertoire destination>\n" if ($ARGV >= 1);

$dirbase=@ARGV[0];
$dirdest=@ARGV[1];
$schemapg=@ARGV[2];
$commune=@ARGV[3];
$inter=@ARGV[4];
$projection=@ARGV[5];
if (length($schemapg)==0){$schemapg=cadastre;}

# Ouverture du rï¿½pertoire d'entrï¿½e, crï¿½ation de la liste des ï¿½changes (.THF)
# et crï¿½ation du rï¿½pertoire de sortie
opendir (IN, $dirbase) || die "$dirbase n'est pas un rï¿½pertoire valide\n";
@liste = readdir( IN);

# if (substr($liste[2],0,6)=="feuille"){
# 	foreach (@liste){
# 		opendir(ON,$_);
# 		$rp=$_;
# 		@pist = readdir(ON);
# 		print $rp.":".$pist[2];
# 		foreach (@pist) {
# 			push(@thf, $rp."/".$_) if (/.THF$/);
# 		}
# 	}
# }else{
	foreach (@liste) {
		push(@thf, $_) if (/.THF$/);
	}
#}
die "$dirbase ne contient pas d'ï¿½change EDIGï¿½O\n" if ($#thf < 0);
if (! -d $dirdest) {
	mkdir ($dirdest) || die "Impossible de crï¿½er $dirdest\n";
}

# En thï¿½orie, il faudrait crï¿½er autant de groupes de fichiers mif/mid qu'il y
# a de .THF ou plutï¿½t de groupes LONSA dans les fichiers .THF (par exemple
# dans n rï¿½pertoires...  Comme la structuration des donnï¿½es DGI est plutï¿½t
# stables, on fait la supposition (hardie ?) que le schï¿½ma de donnï¿½es et le
# dictionaire sont toujours les mï¿½mes, et donc qu'on n'a besoin de lire que
# le premier, qui restera valable pour tous les autres...
# => on ï¿½vite de dupliquer la crï¿½ation du modï¿½le...

$creermodele=1;

foreach (@thf) {
	open(THF, "$dirbase/$_") || die "Ouverture de $dirbase/$_ impossible\n";
	lirethf();
	close(THF);
}

# Tout le travail a ï¿½tï¿½ fait par la fonction lirethf().
# Il reste ï¿½ nettoyer les fichiers vides en fin de travail
# (si le .MID est vide, il n'y a pas de donnï¿½es)
# La conversion des .mif en .tab est confiï¿½e ï¿½ tab2tab.exe
# (voir http://mitab.maptools.org/)

if ($mif2tab == 1) {
	print "Suppression des fichiers vides et crï¿½ation des .TAB\n";
} else {
	print "Suppression des fichiers vides\n";
}

while (($fic,$nommid) = each (%tficmid)) {
	$nommif=$tficmif{$fic};
	if ( -z $nommid ) {
		unlink $nommid, $nommif;
	} elsif ($mif2tab == 1) {
		$nomtab = $nommif;
		$nomtab =~ s/MIF/TAB/;
		print "crï¿½ation de $nomtab\n";
		system ("tab2tab $nommif $nomtab");
	}
}

print "------------------------------\n";
print "Importation terminï¿½e\n";
exit;

# -- Fonction lirethf() -------------------------------------------------------

# On ne retire de l'entï¿½te que les noms des fichiers utiles : .DIC, .GEO,
# .SCD et .VEC.  Sont en particulier ignorï¿½s les fichiers .GEN (gï¿½nï¿½ralitï¿½s),
# .REL (relation), .QAL (qualitï¿½) et .MAT (donnï¿½es raster)
sub lirethf()
{
	print "lecture $_ :\n";
	while(<THF>) {
		split(/:/);
		$_=substr(@_[0],0,5);
		$taille=int(substr(@_[0],5,2));
		if (/^LONSA/) {
			# plusieurs sï¿½ries dans un seul thf...
			# utile en thï¿½orie. Pas utilisï¿½ par la DGI ?
			if (length($nombase) != 0) {
				if ($creermodele == 1 ) {
					extractmodele();
				}
				extractvect();
			}
			$nombase=substr(@_[1],0,$taille);
		} elsif (/^GONSA/) {
			$nomgeo=$nombase . substr(@_[1],0,$taille) . ".GEO";
		} elsif (/^GOISA/) {
			$idgeo = substr(@_[1],0,$taille);
		} elsif (/^DINSA/) {
			$nomdic=$nombase . substr(@_[1],0,$taille) . ".DIC";
		} elsif (/^DIISA/) {
			$iddic = substr(@_[1],0,$taille);
		} elsif (/^SCNSA/) {
			$nomscd=$nombase . substr(@_[1],0,$taille) . ".SCD";
		} elsif (/^SCISA/) {
			$idscd = substr(@_[1],0,$taille);
		} elsif (/^GDNSA/) {
			$nomvec=$nombase . substr(@_[1],0,$taille) . ".VEC";
			push(@listvec, $nomvec);
		} elsif (/^GDISA/) {
			push(@idvec, substr(@_[1],0,$taille));
		} elsif (/^TRLST/) {
			print "Contenu : @_[1]";
		} elsif (/^CSET /) {
			print "Jeu de caractï¿½re : @_[1]";
		} elsif (/^ADRST/) {
			print "Origine : @_[1]";
		} elsif (/^TDASD/) {
			$date=substr(@_[1],6,2) . '/' . substr(@_[1],4,2) . '/' . substr(@_[1],0,4);
			print "Date : $date\n";
		} elsif (/^INFST/) {
			print "Titre : @_[1]";
		}
	}
	if ( $creermodele == 1 ) {
		extractmodele();
	}
	extractvect();
}

# -- Fonction extractmodele() -------------------------------------------------

# extraction des donnï¿½es d'un ï¿½change EDIGeO
sub extractmodele()
{
	# lecture projection ï¿½ utiliser dans le .GEO
	open(GEO,"$dirbase/$nomgeo") || die ("$dirbase/$nomgeo introuvable");
	while(<GEO>) {
		if (/^RELSA/) {
			$taille=int(substr($_,5,2));
			split(/:/);
			$projid=substr(@_[1], 0, $taille);
			$proj=$tproj{$projid};
			last;
		}
	}
	if ($debug_on >= 2) {
		print "$nomgeo - $idgeo - $proj\n";
	}
	close(GEO);

	# lecture dictionaire dans le .DIC - on se contente du nom et du type...
	open(DIC,"$dirbase/$nomdic") || die ("$dirbase/$nomdic introuvable");
	$idic="";
	%tdic=();
	%tsymb=();
	while(<DIC>) {
		if (/^RTYSA/) {
			if (length($idic) > 0 ) {
				if (length($typdic) > 0) {
					$tdic{$idic}=join(' ',$nmdic, $typdic);
				} else {
					$tdic{$idic}=$nmdic;
				}
				$tsymb{$idic}=$symbol;
			}
			$idic='';
		} elsif (/^RIDSA/) {
			# index pour la recherche
			$taille=int(substr($_,5,2));
			split(/:/);
			$idic=substr(@_[1], 0, $taille);
		} elsif (/^LABSA/) {
			# nom et symbole ï¿½ utiliser
			$taille=int(substr($_,5,2));
			split(/:/);
			$nmdic=substr(@_[1], 0, $taille);
			if (length($tfimi{$nmdic}) > 0) {
				$symbol=$tsymbol{$nmdic};
				$nmdic=$tfimi{$nmdic};
			}
		} elsif (/^TYPSA/) {
			# Type des donnï¿½es (seuls types utiles ?)
			$taille=int(substr($_,5,2));
			split(/:/);
			$typ=substr(@_[1], 0, $taille);
			if ($typ=~/A/) {
				$typdic="char";
			} elsif ($typ=~/I/) {
				$typdic="integer";
			} elsif ($typ=~/N/) {
				$typdic="integer";
			} elsif ($typ=~/P/) {
				$typdic="char";
			} elsif ($typ=~/R/) {
				$typdic="float";
			} elsif ($typ=~/T/) {
				$typdic="char";
			}
		}
	}
	if (length($idic) > 0 ) {
		if (length($typdic) > 0) {
			$tdic{$idic}=join(' ',$nmdic, $typdic);
		} else {
			$tdic{$idic}=$nmdic;
		}
		$tsymb{$idic}=$symbol;
	}
	print "==============================\n";
	print "lecture dic: $nomdic - $iddic\n";
	if ($debug_on >= 3) {
		while (($c,$v) = each (%tdic)) {
			print "$c = $v\n";
		}
	}
	print "------------------------------\n";
	close(DIC);

	# lecture schï¿½ma de donnï¿½es pour le contenu attributaire des tables
	# et les attributs (remplis avec le dictionnaire de donnï¿½es) : .SCD
	$typatt{"IDENT"}="GB_IDENT char (40)";
	$typatt{"IDNUM"}="GB_IDNUM integer";
	@tatt=("IDENT","IDNUM");
	open(SCD,"$dirbase/$nomscd") || die ("$dirbase/$nomscd introuvable");
	while(<SCD>) {
		if (/^RTYSA/) {
			if ($itypescd=~/OBJ/ ) {
				$tobj{$iscd}=join(':', $nmobj, $tdic{$nmobj}, $typobj, join(';', @tatt));
				@tatt=("IDENT","IDNUM");
			} elsif ($itypescd=~/ATT/ ) {
				if ($tdic{$nmobj} =~ /char/) {
					if ($size > 254) {
						$size = 254;
					}
					$typatt{$iscd}=join(' ', $tdic{$nmobj}, "($size)");
				} else {
					$typatt{$iscd}=$tdic{$nmobj};
				}
			}
			$tsymbol{$iscd} = $tsymb{$nmobj};
			$taille=int(substr($_,5,2));
			split(/:/);
			$itypescd=substr(@_[1], 0, $taille);

		} elsif (/^RIDSA/) {
			$taille=int(substr($_,5,2));
			split(/:/);
			$iscd=substr(@_[1], 0, $taille);
		} elsif (/^CANSN/) {
			$taille=int(substr($_,5,2));
			split(/:/);
			$size=int(@_[1]);
		} elsif (/^DIPCP/) {
			$taille=int(substr($_,5,2));
			split(/:/);
			$_=substr(@_[1], 0, $taille);
			split(/;/);
			$nmobj=@_[-1];
		} elsif (/^AAPCP/) {
			$taille=int(substr($_,5,2));
			split(/:/);
			$_=substr(@_[1], 0, $taille);
			split(/;/);
			push(@tatt, @_[-1]);
		} elsif (/^KNDSA/) {
			$taille=int(substr($_,5,2));
			split(/:/);
			$typ=substr(@_[1], 0, $taille);
			# Type d'objet
			if ($typ=~/ARE/) {
				$typobj="Region";
			} elsif ($typ=~/LIN/) {
				$typobj="Pline";
			} elsif ($typ=~/PCT/) {
				$typobj="Point";
			}
		}
	}
	if ($itypescd=~/OBJ/ ) {
		$tobj{$iscd}=join(':', $nmobj, $tdic{$nmobj}, $typobj, join(';', @tatt));
	} elsif ($itypescd=~/ATT/ ) {
		$typatt{$iscd}=join(' ', $tdic{$nmobj}, "($size)");
	}
	$tsymbol{$iscd} = $tsymb{$nmobj};
	close(SCD);
#	print "==============================\n";
	print "lecture scd: $nomscd - $idscd\n";
	print "------------------------------\n";
	if ($debug_on >= 3) {
		print "-- contenu de tobj ---------->\n";
		while (($c,$v) = each (%tobj)) {
			print "$c = $v\n";
		}
		print "-- contenu de typatt -------->\n";
		while (($c,$v) = each (%typatt)) {
			print "$c = $v\n";
		}
		print "-- contenu de tsymbol ------->\n";
		while (($c,$v) = each (%tsymbol)) {
			print "Symbol $c = $v\n";
		}
		print "------------------------------\n";
	}

	# creation des fichiers MIF/MID nï¿½cessaires
	#
	if ($midmif==1){
		while (($c,$v) = each (%tobj)) {
		split(/:/,$v);
		next if (@_[1] eq '-'); # si on ne veut pas des ï¿½critures
		$indfic{$c} = @_[1];
		$objatt{$c} = @_[-1];

		$nommid="$dirdest/@_[1].MID";
		open($fmid,">$nommid");
		$tficmid{@_[1]} = $nommid;
		close($fmid);

		$nommif="$dirdest/@_[1].MIF";
		open($fmif,">$nommif");
		$tficmif{@_[1]} = $nommif;

		if ($debug_on >= 4) {
			print "Crï¿½ation du fichier $dirdest/$nommif ($c) :\n";
			print "------------------------------\n";
		}
		print $fmif "Version 300\n";
		print $fmif "Charset \"WindowsLatin1\"\n";
		print $fmif "CoordSys Earth Projection $proj\n";
		@lst = split(/;/,@_[-1]);
		$nbchamps = $#lst + 1;
		print $fmif "Columns $nbchamps\n";
		for $i (@lst) {
			print $fmif $typatt{$i} . "\n";
		}
		print $fmif "Data\n";
		close($fmif);
	}
	}
	if ($postgres==1){
		while (($c,$v) = each (%tobj)) {
		split(/:/,$v);
		next if (@_[1] eq '-'); # si on ne veut pas des ï¿½critures
		$indfic{$c} = @_[1];
		$objatt{$c} = @_[-1];

		$nommid="$dirdest/@_[1].sql";
		if ($inter==0) {open($fsql, ">$nommid");}else{open($fsql, ">>$nommid");};
		$tficsql{@_[1]} = $nommid;
		@lst = split(/;/,@_[-1]);
			print $fsql "Set client_encoding ='ISO-8859-1';" ;
		if ($inter==0) {
			print $fsql "Create table $schemapg.".lc(@_[1])." (" ;
		}
		$txt="";
		for $i (@lst) {
#			print $fsql $typatt{$i} . ",";
			$txt .= $typatt{$i} . ",";
		}
		$txt=substr($txt,0,length($txt)-1);
		if (@_[2]=~/Region/){
			$txttyp=MULTIPOLYGON;
		}elsif  (@_[2]=~/Pline/) {
			$txttyp=MULTILINESTRING;
		}elsif  (@_[2]=~/Point/) {
			$txttyp=MULTIPOINT;
		}
		if ($inter==0){print $fsql $txt.",code_insee character varying(6));\nSELECT AddGeometryColumn('$schemapg','".lc(@_[1])."','the_geom','".$projection."','".$txttyp."',2);\n";}
		# "COPY cadastre.@_[1] (";
		$txt="insert into $schemapg.".lc(@_[1])." (";
		for $i (@lst) {
			@part=split(/ /,$typatt{$i});
			$txt .= $part[0] . ",";
		}
#		$txt=substr($txt,0,length($txt)-1);
		$txt.="code_insee,the_geom) values(";
		$chene{@_[1]}=$txt;
		#print $fsql $txt.") FROM stdin;\n";
		close($fsql);

		if ($debug_on >= 4) {
			print "Crï¿½ation du fichier $dirdest/$nommid ($c) :\n";
			print "------------------------------\n";
		}
	}
	}
	$creermodele = 0;
#	print "==============================\n";

}

# -- Fonction extractvect() ---------------------------------------------------

# lecture des fichiers vecteurs .VEC
sub extractvect()
{
	for ($i=0; $i <= $#listvec; $i++) {
		print "---> @listvec[$i] - @idvec[$i]\n";
		#open (VEC,'<:encoding(iso-8859-1)', $dirbase.'/'.@listvec[$i]) || die "@listvec[$i] Impossible ï¿½ ouvrir\n";
		#open (SOR, '>:encoding(utf-8)', $dirbase.'/'.@listvec[$i].'.utf') || die "@listvec[$i] Impossible ï¿½ ouvrir\n";
		#while (<VEC>){ 
		#	print SOR $_;
		#}
		#close(VEC);
		#close(SOR);
		open (VEC, $dirbase.'/'.@listvec[$i]) || die "@listvec[$i] Impossible ï¿½ ouvrir\n";
		lirevec();	# il vaut mieux faire cela ï¿½ part !
		close(VEC);
	}

	# vidage de la liste des .vec avant le prochain groupe de fichiers
	# (un .THF peut en contenir plusieurs)
	@listvec=();
	print "==============================\n";
}

# -- Fonction lirevec() -------------------------------------------------------

# lecture des fichiers vecteurs et remplissage des mif/mid
# et lï¿½, c'est le bazar.  S'attendre ï¿½ bouffer de la mï¿½moire...
# Les seuls objets ï¿½ traiter directement sont les FEA (objets gï¿½ographiques)
# Pour les objets ponctuels, les coordonnï¿½es sont dans le FEA (cas simple).
# Par le biais des objets LNK (liens), ils font rï¿½fï¿½rence ï¿½ des lignes dans le
# cas des objets linï¿½aires et des faces dans le cas des objets surfaciques
# Pour les objets surfaciques, les faces font rï¿½fï¿½rences ï¿½ des arcs qu'il faut
# chaï¿½ner pour obtenir une "rï¿½gion" MapInfo.  Le centroï¿½de est ï¿½ rï¿½cupï¿½rer dans
# le FEA du toponyme associï¿½ (non fait pour le moment)
# Enfin, il faut dispatcher les objets dans les tables crï¿½ï¿½es au dï¿½part...
#
sub lirevec()
{
	# initialisation des tables
	%tabfea=();
	%tabtxt=();
	%tabfeafea=();
	%tabfeatpn=();
	%tabfeapar=();
	%tabfeapfe=();
	%tabfeapno=();
	%tabpfepar=();
	%tabarcdeb=();
	%tabarcfin=();
	%tabcoor=();
	%tabpno=();

	# A priori, tous les blocs commencent par RTYSA et se terminent par QACSN
	while(<VEC>) {
		$long=int(substr($_,5,2));
		split(/:/);
		$val=substr(@_[1],0,$long);
		if (/^RTYSA/) {
			$typobj=$val;
			if ($debug_on >= 6) {
				print "--------------------------\n";
				print "Type objet = \"$typobj\"\n";
			}
			if ($val =~/LNK/) {
				$lnkfirst=0;
				@lnklst=();
			} elsif ($val =~/FEA/) {
				@attrib=();
				$ptrtxtatt='';
			} elsif ($val =~/PAR/ || $val =~/PNO/) {
				@coord=();
			}
		} elsif (/^RIDSA/) {
			$nomobj=$val;
			if ($debug_on >= 6) {
				print "Nom objet = \"$nomobj\"\n";
			}
		} elsif (/^PTCSN/) {
#			print "Region 1\n";
#			print "$val\n";
			$npts=$val;
		} elsif (/^CORCC/) {
			$val =~ s/\+//g;
			@xy=split(/;/,$val);
			push(@coord,"@xy[0] @xy[1]");
#			print "@xy[0] @xy[1]\n";
		} elsif (/^ATPCP/) {
			@index=split(/;/,$val);
			$nmatt=@index[-1];
		} elsif (/^ATVCP/) {
			@index=split(/;/,$val);
			$ptrtxtatt=@index[-1];
			push(@attrib, "$nmatt;$ptrtxtatt");
			if ($debug_on >= 6) {
				print "ptr ï¿½tiquette = \"$ptrtxtatt\"\n";
			}
		} elsif (/^ATVS/) {
			$valatt=$val;
			# On corrige les problï¿½mes ï¿½ventuels en ï¿½liminant les caractï¿½res
			# causant des soucis (solution a priori brutale, mais efficace)...
			$valatt=~s/"//g;
			$valatt=~s/\\//g;
			push(@attrib, "$nmatt;$valatt");
			if ($debug_on >= 6) {
				print "Attribut \"$nmatt\" = \"$valatt\"\n";
			}
		} elsif (/^SCPCP/) {
			@index=split(/;/,$val);
			$idobj=@index[-1];
			if ($debug_on >= 6) {
				print "Rï¿½fï¿½rence = @index[2] : $idobj\n";
			}
		} elsif (/^FTPCP/) {
			@index=split(/;/,$val);
			if ($debug_on >= 6) {
				print "Lien = @index[2] : @index[-1]\n";
			}
			if ($lnkfirst == 0) {
				$lnkobj = @index[-1];
				$lnktyp = @index[2];
				$lnkfirst = 1;
			} else {
				$lnktyp .= @index[2];
				push(@lnklst, @index[-1]);
			}
		} elsif (/^QACSN/) {
			if ($typobj=~/FEA/) {
				# objet gï¿½ographique trouvï¿½ : enregistrement des paramï¿½tres...
				$attstring=join(';',@attrib);
				# Les objets Z_1_2_2 sont transformï¿½s en textes ssi le nom du
				# fichier associï¿½ est diffï¿½rent de '-'
				@tst=split(/:/,$tobj{$idobj});
				if ($tdic{@tst[0]} ne '-') {
					$tabfea{$nomobj} = join(';', $idobj, @attrib);
					$tabtxt{$nomobj} = $ptrtxtatt;
					if ($debug_on >= 6) {
						print "FEA : $nomobj --> $tabfea{$nomobj}\n";
					}
				}
			} elsif ($typobj=~/LNK/) {
				# liens objets/faces, faces/arcs, arcs/noeuds, objets/toponyme
				# et toponyme/localisation...
				$lnkdest = join(';',@lnklst);
				if ($lnktyp=~/^FEAFEA/ && $idobj=~/_IWW$/) {
					# Les seuls liens traitï¿½s sont les toponymes pour crï¿½er
					# des tables textes (multiples localisation si le toponyme
					# se trouve dans plusieurs champs TEXn).
					# $tabfeatpn{$lnkdest} = $lnkobj;
					$tabfeafea{$lnkobj} = $lnkdest;
				} elsif ($lnktyp=~/^FEAPAR/) {
					$tabfeapar{$lnkobj} = $lnkdest;
				} elsif ($lnktyp=~/^FEAPFE/) {
					$tabfeapfe{$lnkobj} = $lnkdest;
				} elsif ($lnktyp=~/^FEAPNO/) {
					$tabfeapno{$lnkobj} = $lnkdest;
				} elsif ($lnktyp=~/^PARPFE/) {
					# on indique si la face est ï¿½ gauche ou ï¿½ droite...
					$tflink = $lnkobj . ":" . substr($idobj,13,1) . ";";
					$tabpfepar{$lnkdest} .= $tflink;
				} elsif ($lnktyp=~/^PARPNO/) {
					if ($idobj =~ /_INI$/) {
						$tabarcdeb{$lnkobj} = $lnkdest;
					} elsif ($idobj =~ /_FIN$/) {
						$tabarcfin{$lnkobj} = $lnkdest;
					}
				}
				if ($debug_on >= 6) {
					print "LNK : type $idobj : $lnkobj ($lnktyp) -> ($lnkdest)\n";
				}
			} elsif ($typobj=~/PAR/) {
				# descripteur des arcs et mise en mï¿½moire de leur longueur
				$tabcoor{$nomobj} = join(':', @coord);
				$arcsize{$nomobj} = $#coord;
				if ($debug_on >= 6) {
					print "PAR : $nomobj ($npts points) = $#coord\n";
				}
			} elsif ($typobj=~/PNO/) {
				# "centroï¿½de" dans le cas des Regions MapInfo
				$co=pop(@coord);
				$tabpno{$nomobj} = $co;
				if ($debug_on >= 6) {
					@xy=split(/;/,$co);
					print "PNO : $nomobj = @xy[0] @xy[1]\n";
				}
			} # on ignore le reste (en particulier les enregistrements PFE)
		}
	}

	# On recrï¿½e les objets geographiques en bouclant sur les FEA
	if ($midmif==1){
		while (($obj,$fea) = each (%tabfea)) {
			@index = split(/;/, $fea);
			# fichiers cibles
			$ific = shift(@index);
			$fic = $indfic{$ific};
			$symbol = $tsymbol{$ific};
	
			open( $fmid, ">>$tficmid{$fic}");
			open( $fmif, ">>$tficmif{$fic}");
	
			# ï¿½criture des attributs
			@lst = split(/;/,$objatt{$ific});
			%att=@index;
			@attlst=();
			$attident = join(' ', $att{TEX_id}, $att{TEX2_id}, $att{TEX3_id},
				$att{TEX4_id}, $att{TEX5_id}, $att{TEX6_id}, $att{TEX7_id},
				$att{TEX8_id}, $att{TEX9_id}, $att{TEX10_id});
			$attident =~ s/ +$//;
			if ($attident =~ /^[0-9]+$/) {
				$att{'IDNUM'} =int($attident);
			}
			if (length($attident) > 0) {
				$att{'IDENT'} = $attident;
			} else {
				$att{'IDENT'} = $obj;
			}
			for $j (@lst) {
				if ($typatt{$j} =~ /char/) {
					push(@attlst, "\"$att{$j}\"");
				} else {
					push(@attlst, $att{$j});
				}
			}
			$attstring = join("\t",@attlst);
			print $fmid "$attstring\n";
	
			# ï¿½criture de la gï¿½omï¿½trie
			# on fait en fonction du type d'objet...
			$objpnt = $tabfeapno{$obj};
			$objpar = $tabfeapar{$obj};
			$objpfe = $tabfeapfe{$obj};
			$objlie = $tabfeafea{$obj};
	
			if (length($objlie) > 0) {
				# Texte associï¿½ ï¿½ un autre objet : pointeur sur attribut ï¿½
				# afficher et lien vers un point ï¿½ l'endroit du toponyme...
				# on rï¿½cupï¿½re d'abord les caractï¿½ristiques du texte..
				while (($c,$v) = each (%att)) {
					if ($c =~ /_FON$/) {
						$font=$v;
					} elsif ($c =~ /_HEI$/) {
						$taille = $v;
						$taille =~ s/\+//;
					} elsif ($c =~ /_DI3$/) {
						$dx=$v;
					} elsif ($c =~ /_DI4$/) {
						$dy=$v;
					}
				}
				$angle = atan2($dy,$dx) * 360 / 6.283;
				@index = split(/;/, $tabfea{$objlie});
				shift(@index);
				%att = @index;
				if ($debug_on >= 6) {
					print "DEBUG objlie=$objlie ($tabfea{$objlie})\n";
				}
				$ptrtxtatt = $tabtxt{$obj};
				$texte = $att{$ptrtxtatt};
				# Si la taille affichï¿½e ne plait pas, on peut la modifier
				$taille *= $echelle_texte;
				# la bibliothï¿½que mitab a un problï¿½me si le texte est de
				# longueur nulle...
				if (length($texte) == 0) {
					$texte = " ";
				}
				@pos = split(/ /, $tabpno{$objpnt});
				$x1=@pos[0];
				$x2=@pos[0] + $taille;
				$y1=@pos[1];
				$y2=@pos[1] + $taille;
	
				if ($debug_on >= 6) {
					print "ETIQUETTE : $obj ($ptrtxtatt=$texte) -> $objlie ($objpnt:$xy)\n";
				}
				print $fmif "Text\n    \"$texte\"\n";
				print $fmif "    $x1 $y1 $x2 $y2\n";
				print $fmif "    Font (\"$font\", 0, 0, 0)\n";
				print $fmif "    Angle $angle\n";
			} elsif (length($objpnt) > 0) {
				# objet ponctuel ... le plus simple...
				$xy = $tabpno{$objpnt};
				print $fmif "Point $xy\n";
			} elsif (length($objpar) > 0) {
				# objet linï¿½aire ... il peut y en avoir plusieurs
				@lin = split(/;/, $objpar);
				$nblin = $#lin + 1;
				if ($nblin > 1) {
					print $fmif "Pline Multiple $nblin\n";
				} else {
					print $fmif "Pline\n";
				}
				for $j (@lin) {
					@coord = split(/:/,$tabcoor{$j});
					$nbpts = $#coord + 1;
					print $fmif "$nbpts\n";
					for $co (@coord) {
						print $fmif "$co\n";
					}
				}
			} elsif (length($objpfe) > 0) {
				# Ah, des polygones... un peu plus complexe : il peut y avoir
				# plusieurs rï¿½gions (au sens MapInfo), chacune formï¿½e par plusieurs
				# arcs.  Il faut donc refaire le chaï¿½nage des arcs pour un contour
				# fermï¿½...
				#
				# Dans le cas des parcelles et autres gros objets, on peut faire
				# les liens avec les noeuds de dï¿½but et de fin d'arc...
				# mais ï¿½a ne marche pas avec tous les polygones (p.ex. bï¿½timents) !
				@reg = split(/;/,$objpfe);
				$nbreg = $#reg + 1;
	#			print "DEBUG Region $nbreg ($objpfe)\n";
	
				# On ne peut pas ï¿½crire la ligne "Region R" maintenant, car les
				# trous dans les objets sont gï¿½rï¿½s au niveau de la liste d'arcs
				# dans EDIGï¿½O, mais sont inclus dans le compte des contours dans
				# les rï¿½gions MapInfo... donc R peut augmenter...
				# Pour le dï¿½bug du chaï¿½nage, on ne l'imprime qu'en cas de soucis
				# ï¿½ la fin du travail pour ï¿½viter de faire grossir les logs...
				$nbreg=-1;
				@tchain=();
				@tdirec=();
				%tabarc=();
				for $j (@reg) {
					@tabarcdir=split(/;/,$tabpfepar{$j});
					$debug_list = "DEBUG $tabpfepar{$j}\n";
					# Dans certains cas, un arc est prï¿½sent 2, voire 3 fois pour
					# la mï¿½me face [Ceci n'est pas normal : bug du programme de
					# crï¿½ation de l'ï¿½change ?].  On l'ï¿½limine donc (sinon erreur
					# de chaï¿½nage) si le nombre d'occurence est pair...
					for $a (@tabarcdir) {
						($arc,$dir) = split(/:/, $a);
						if ($tabarc{$arc} ne "") {
							if ($tabarc{$arc} ne "#") {
								print "Doublon d'arc EDIGï¿½O dï¿½tectï¿½ pour $obj ($j) : $arc\n";
								$tabarc{$arc} = "#";
							} else {
								print "Erreur corrigï¿½e : Arc $arc restaurï¿½\n";
								$tabarc{$arc} = "@";
							}
						} else {
							$tabarc{$arc} = $dir;
						}
					}
					@tarc=keys(%tabarc);
					for ($a = $#tarc; $a >= 0; $a--) {
						$arc = @tarc[$a];
						if ($tabarc{$arc} eq "#" ) {
							splice (@tarc, $a, 1);
							print "Erreur corrigï¿½e : Arc $arc ï¿½liminï¿½\n";
						}
						$debug_list .= "DEBUG $arc $tabarc{$arc} [$arcsize{$arc}] $tabarcdeb{$arc} $tabarcfin{$arc} \n";
					}
					# Il arrive que le chaï¿½nage soit incohï¿½rent (arc manquant) [Lï¿½
					# aussi, bug du programme de crï¿½ation de l'ï¿½change ?]
					# Dans ce cas, certains noeuds ne sont citï¿½s qu'une seule fois.
					# On recherche alors un ï¿½ventuel arc les joignants...
					# TODO : amï¿½liorer l'algorithme de correction du chaï¿½nage)
					# On en profite pour fabriquer les ï¿½lï¿½ments de chaï¿½nage dans le
					# cas des ï¿½lï¿½ments ne possï¿½dant pas une topologie complï¿½te...
					%tverif=();
					@bad=();
					for $arc (@tarc) {
						if ( $tabarcdeb{$arc} eq "" ) {
							@coord = split(/:/,$tabcoor{$arc});
							$tabarcdeb{$arc} = @coord[0];
							$tabarcfin{$arc} = @coord[$arcsize{$arc}];
						}
						$tverif{$tabarcdeb{$arc}}++;
						$tverif{$tabarcfin{$arc}}++;
					}
					while (($k, $v) = each (%tverif)) {
						if ( $v == 1 ) {
							push(@bad, $k);
						}
						$debug_list .= "DEBUG Noeud $k = $v\n";
					}
					if ($#bad >= 0) {
						$b=join(";",@bad);
						print "Erreur de chaï¿½nage ï¿½ cause de noeuds isolï¿½s : $b\n";
						AJOUT: while (($a, $v) = each (%tabarcdeb)) {
							if (($v eq @bad[0] && $tabarcfin{$a} eq @bad[1]) ||
								($v eq @bad[1] && $tabarcfin{$a} eq @bad[0]) ) {
								unshift(@tarc, $a);
								print "Tentative de correction du chaï¿½nage par ajout de l'arc $a\n";
								last AJOUT;
							}
						}
					}
					# on recalcule chaï¿½nage et nombre de rï¿½gions
					$arc=pop(@tarc);
					push (@tchain, $arc);
					push (@tdirec, -1);
					$nbreg++;
					@regsize[$nbreg] = $arcsize{$arc} + 1;
					$ndini=$tabarcdeb{$arc};
					$ndlnk=$tabarcfin{$arc};
					$sens=-1;
					$debug_list .= "DEBUG init arc $arc ($sens) $ndini $ndlnk $#tarc\n";
					while ($#tarc >= 0) {
						if ($sens != 0) {
							$ndtst=$tabarcfin{$arc};
						} else {
							$ndtst=$tabarcdeb{$arc};
						}
						# si on n'a pas rebouclï¿½, recherche de l'arc suivant
						if ($ndini ne $ndtst) {
							$sens=-1;
							CHERCH : for ($k=$#tarc; $k >=0;  $k--) {
								$arc=@tarc[$k];
								$debug_list .= "DEBUG cherche $ndlnk $arc ($tabarcdeb{$arc} $tabarcfin{$arc})\n";
								if ($ndlnk eq $tabarcdeb{$arc}) {
									$sens=1;
									$ndlnk=$tabarcfin{$arc};
									last CHERCH;
								}
								if ($ndlnk eq $tabarcfin{$arc}) {
									$sens=0;
									$ndlnk=$tabarcdeb{$arc};
									last CHERCH;
								}
							}
							if ($sens < 0) {
								print $debug_list if ($debug_on > 0);
								$debug_list="";
								print "ERREUR de chaï¿½nage pour $obj (arc $arc) non corrigible !!!\n";
	
								$nbreg++;
								$arc=pop(@tarc);
								push(@tchain, $arc);
								push (@tdirec, -1);
								@regsize[$nbreg] = $arcsize{$arc} + 1;
								$ndini=$tabarcdeb{$arc};
								$ndlnk=$tabarcfin{$arc};
								$sens=-1;
								$debug_list .= "DEBUG new arc $arc ($sens) $ndini $ndlnk $#tarc\n";
							} else {
								splice(@tarc, $k, 1);
								$debug_list .= "DEBUG trouve arc $arc ($sens)\n";
								# nombre de points moins un car sinon on double le
								# point commun aux deux arcs (on rajoute 1 au
								# premier arc pour terminer le polygone)
								@regsize[$nbreg] += $arcsize{$arc};
								push (@tchain, $arc);
								push (@tdirec, $sens);				
							}
						} else {
							$nbreg++;
							$arc=pop(@tarc);
							push(@tchain, $arc);
							push (@tdirec, -1);
							@regsize[$nbreg] = $arcsize{$arc} + 1;
							$ndini=$tabarcdeb{$arc};
							$ndlnk=$tabarcfin{$arc};
							$sens=-1;
							$debug_list .= "DEBUG new arc $arc ($sens) $ndini $ndlnk $#tarc\n";
						}
					}
				}
				# il reste ï¿½ ï¿½crire les coordonnï¿½es en respectant le sens des arcs
				# le premier arc est prï¿½cï¿½dï¿½ du total du nombre de points
				$nbreg++;
				if ($debug_on >= 6) {
					print "DEBUG Rï¿½gions $nbreg taille @regsize @tchain @tdirec\n";
				}
				print $fmif "Region $nbreg\n";
				$k=-1;
				for ($j=0; $j <= $#tchain; $j++) {
					$arc = @tchain[$j];
					@coord = split(/:/,$tabcoor{$arc});
					if (@tdirec[$j] < 0) {
						$k++;
						print $fmif "@regsize[$k]\n";
						for $co (@coord) {
							print $fmif "$co\n";
						}
					} elsif (@tdirec[$j] == 1) {
						for ($l=1; $l<=$#coord; $l++) {
							print $fmif "@coord[$l]\n";
						}
					} else {
						for ($l=$#coord-1; $l>=0; $l--) {
							print $fmif "@coord[$l]\n";
						}
					}	
				} # fin ï¿½criture coordonnï¿½es
				# Ecriture symbolisation s'il y a lieu
				if ($symbol ne "") {
					print $fmif "$symbol";
				}
			} else {
				print "ERREUR INATTENDUE! $obj dans $fic sans gï¿½omï¿½trie !\n";
			}
		}
		close($fmid);
		close($fmif);
	}
	if ($postgres==1){
		while (($obj,$fea) = each (%tabfea)) {
			@index = split(/;/, $fea);
			# fichiers cibles
			$ific = shift(@index);
			$fic = $indfic{$ific};
			$symbol = $tsymbol{$ific};
	
			open( $fsql, ">>$tficsql{$fic}");
	$chn_txt=$chene{$fic};
			# ï¿½criture des attributs
			@lst = split(/;/,$objatt{$ific});
			%att=@index;
			@attlst=();
			$attident = join(' ', $att{TEX_id}, $att{TEX2_id}, $att{TEX3_id},
				$att{TEX4_id}, $att{TEX5_id}, $att{TEX6_id}, $att{TEX7_id},
				$att{TEX8_id}, $att{TEX9_id}, $att{TEX10_id});
			$attident =~ s/ +$//;
			if ($attident =~ /^[0-9]+$/) {
				$att{'IDNUM'} =int($attident);
			}
 			if (length($attident) > 0) {
# 				$att{'IDENT'} = $attident;
 			} else {
 				$att{'IDENT'} = $obj;
 			}
			for $j (@lst) {
				if ($typatt{$j} =~ /char/) {
					if (length($att{$j}) > 0){
						#push(@attlst, "convert(\"$att{$j}\",'LATIN1','UTF8')");
						push(@attlst, "\"$att{$j}\"");
					}else{
						push(@attlst, "\"$att{$j}\"");
					}
				} else {
					push(@attlst, $att{$j});
				}
			}
			$attstring = join(",",@attlst);
			$attstring =~ s/"/'/g;
			$attstring =~ s/''/null/g;
			$attstring =~ s/,,/,null,/g;
			$attstring =~ s/(?<=[a-zA-Z])'(?=[a-zA-Z\s])/''/g;
			$attstring =~ s/\s+/ /g;
			print $fsql $chn_txt."$attstring".",'".$commune."',";
	
			# ï¿½criture de la gï¿½omï¿½trie
			# on fait en fonction du type d'objet...
			$objpnt = $tabfeapno{$obj};
			$objpar = $tabfeapar{$obj};
			$objpfe = $tabfeapfe{$obj};
			$objlie = $tabfeafea{$obj};
	
			if (length($objlie) > 0) {
				# Texte associï¿½ ï¿½ un autre objet : pointeur sur attribut ï¿½
				# afficher et lien vers un point ï¿½ l'endroit du toponyme...
				# on rï¿½cupï¿½re d'abord les caractï¿½ristiques du texte..
				while (($c,$v) = each (%att)) {
					if ($c =~ /_FON$/) {
						$font=$v;
					} elsif ($c =~ /_HEI$/) {
						$taille = $v;
						$taille =~ s/\+//;
					} elsif ($c =~ /_DI3$/) {
						$dx=$v;
					} elsif ($c =~ /_DI4$/) {
						$dy=$v;
					}
				}
				$angle = atan2($dy,$dx) * 360 / 6.283;
				@index = split(/;/, $tabfea{$objlie});
				shift(@index);
				%att = @index;
				if ($debug_on >= 6) {
					print "DEBUG objlie=$objlie ($tabfea{$objlie})\n";
				}
				$ptrtxtatt = $tabtxt{$obj};
				$texte = $att{$ptrtxtatt};
				# Si la taille affichï¿½e ne plait pas, on peut la modifier
				$taille *= $echelle_texte;
				# la bibliothï¿½que mitab a un problï¿½me si le texte est de
				# longueur nulle...
				if (length($texte) == 0) {
					$texte = " ";
				}
				@pos = split(/ /, $tabpno{$objpnt});
				$x1=@pos[0];
				$x2=@pos[0] + $taille;
				$y1=@pos[1];
				$y2=@pos[1] + $taille;
	
				if ($debug_on >= 6) {
					print "ETIQUETTE : $obj ($ptrtxtatt=$texte) -> $objlie ($objpnt:$xy)\n";
				}
				print $fsql "Text    \"$texte\"";
				print $fsql "    $x1 $y1 $x2 $y2\n";
#				print $fsql "    Font (\"$font\", 0, 0, 0)\n";
#				print $fsql "    Angle $angle\n";
			} elsif (length($objpnt) > 0) {
				# objet ponctuel ... le plus simple...
				$xy = $tabpno{$objpnt};
				print $fsql "geometryfromtext('MultiPoint( $xy)','".$projection."'));\n";
			} elsif (length($objpar) > 0) {
				# objet linï¿½aire ... il peut y en avoir plusieurs
				@lin = split(/;/, $objpar);
				$nblin = $#lin + 1;
				if ($nblin > 1) {
					print $fsql "geometryfromtext('MultiLinestring(( ";
				} else {
					print $fsql "geometryfromtext('MultiLinestring((";
				}
				$atxt = "";
				for $j (@lin) {
					@coord = split(/:/,$tabcoor{$j});
#					$nbpts = $#coord + 1;
#					print $fmif "$nbpts\n";
					#$atxt = "";
					for $co (@coord) {
#						print $fsql "$co ,";
						$atxt .= "$co,";
					}
					$atxt=substr($atxt,0,length($atxt)-1)."),(";
				}
				$atxt=substr($atxt,0,length($atxt)-3);
				print $fsql $atxt."))','".$projection."'));\n";
			} elsif (length($objpfe) > 0) {
				# Ah, des polygones... un peu plus complexe : il peut y avoir
				# plusieurs rï¿½gions (au sens MapInfo), chacune formï¿½e par plusieurs
				# arcs.  Il faut donc refaire le chaï¿½nage des arcs pour un contour
				# fermï¿½...
				#
				# Dans le cas des parcelles et autres gros objets, on peut faire
				# les liens avec les noeuds de dï¿½but et de fin d'arc...
				# mais ï¿½a ne marche pas avec tous les polygones (p.ex. bï¿½timents) !
				@reg = split(/;/,$objpfe);
				$nbreg = $#reg + 1;
	#			print "DEBUG Region $nbreg ($objpfe)\n";
	
				# On ne peut pas ï¿½crire la ligne "Region R" maintenant, car les
				# trous dans les objets sont gï¿½rï¿½s au niveau de la liste d'arcs
				# dans EDIGï¿½O, mais sont inclus dans le compte des contours dans
				# les rï¿½gions MapInfo... donc R peut augmenter...
				# Pour le dï¿½bug du chaï¿½nage, on ne l'imprime qu'en cas de soucis
				# ï¿½ la fin du travail pour ï¿½viter de faire grossir les logs...
				$nbreg=-1;
				@tchain=();
				@tdirec=();
				%tabarc=();
				for $j (@reg) {
					@tabarcdir=split(/;/,$tabpfepar{$j});
					$debug_list = "DEBUG $tabpfepar{$j}\n";
					# Dans certains cas, un arc est prï¿½sent 2, voire 3 fois pour
					# la mï¿½me face [Ceci n'est pas normal : bug du programme de
					# crï¿½ation de l'ï¿½change ?].  On l'ï¿½limine donc (sinon erreur
					# de chaï¿½nage) si le nombre d'occurence est pair...
					for $a (@tabarcdir) {
						($arc,$dir) = split(/:/, $a);
						if ($tabarc{$arc} ne "") {
							if ($tabarc{$arc} ne "#") {
								print "Doublon d'arc EDIGï¿½O dï¿½tectï¿½ pour $obj ($j) : $arc\n";
								$tabarc{$arc} = "#";
							} else {
								print "Erreur corrigï¿½e : Arc $arc restaurï¿½\n";
								$tabarc{$arc} = "@";
							}
						} else {
							$tabarc{$arc} = $dir;
						}
					}
					@tarc=keys(%tabarc);
					for ($a = $#tarc; $a >= 0; $a--) {
						$arc = @tarc[$a];
						if ($tabarc{$arc} eq "#" ) {
							splice (@tarc, $a, 1);
							print "Erreur corrigï¿½e : Arc $arc ï¿½liminï¿½\n";
						}
						$debug_list .= "DEBUG $arc $tabarc{$arc} [$arcsize{$arc}] $tabarcdeb{$arc} $tabarcfin{$arc} \n";
					}
					%tverif=();
					@bad=();
					for $arc (@tarc) {
						if ( $tabarcdeb{$arc} eq "" ) {
							@coord = split(/:/,$tabcoor{$arc});
							$tabarcdeb{$arc} = @coord[0];
							$tabarcfin{$arc} = @coord[$arcsize{$arc}];
						}
						$tverif{$tabarcdeb{$arc}}++;
						$tverif{$tabarcfin{$arc}}++;
					}
					while (($k, $v) = each (%tverif)) {
						if ( $v == 1 ) {
							push(@bad, $k);
						}
						$debug_list .= "DEBUG Noeud $k = $v\n";
					}
					if ($#bad >= 0) {
						$b=join(";",@bad);
						print "Erreur de chaï¿½nage ï¿½ cause de noeuds isolï¿½s : $b\n";
						AJOUT: while (($a, $v) = each (%tabarcdeb)) {
							if (($v eq @bad[0] && $tabarcfin{$a} eq @bad[1]) ||
								($v eq @bad[1] && $tabarcfin{$a} eq @bad[0]) ) {
								unshift(@tarc, $a);
								print "Tentative de correction du chaï¿½nage par ajout de l'arc $a\n";
								last AJOUT;
							}
						}
					}
					# on recalcule chaï¿½nage et nombre de rï¿½gions
					$arc=pop(@tarc);
					push (@tchain, $arc);
					push (@tdirec, -1);
					$nbreg++;
					@regsize[$nbreg] = $arcsize{$arc} + 1;
					$ndini=$tabarcdeb{$arc};
					$ndlnk=$tabarcfin{$arc};
					$sens=-1;
					$debug_list .= "DEBUG init arc $arc ($sens) $ndini $ndlnk $#tarc\n";
					while ($#tarc >= 0) {
						if ($sens != 0) {
							$ndtst=$tabarcfin{$arc};
						} else {
							$ndtst=$tabarcdeb{$arc};
						}
						# si on n'a pas rebouclï¿½, recherche de l'arc suivant
						if ($ndini ne $ndtst) {
							$sens=-1;
							CHERCH : for ($k=$#tarc; $k >=0;  $k--) {
								$arc=@tarc[$k];
								$debug_list .= "DEBUG cherche $ndlnk $arc ($tabarcdeb{$arc} $tabarcfin{$arc})\n";
								if ($ndlnk eq $tabarcdeb{$arc}) {
									$sens=1;
									$ndlnk=$tabarcfin{$arc};
									last CHERCH;
								}
								if ($ndlnk eq $tabarcfin{$arc}) {
									$sens=0;
									$ndlnk=$tabarcdeb{$arc};
									last CHERCH;
								}
							}
							if ($sens < 0) {
								print $debug_list if ($debug_on > 0);
								$debug_list="";
								print "ERREUR de chaï¿½nage pour $obj (arc $arc) non corrigible !!!\n";
	
								$nbreg++;
								$arc=pop(@tarc);
								push(@tchain, $arc);
								push (@tdirec, -1);
								@regsize[$nbreg] = $arcsize{$arc} + 1;
								$ndini=$tabarcdeb{$arc};
								$ndlnk=$tabarcfin{$arc};
								$sens=-1;
								$debug_list .= "DEBUG new arc $arc ($sens) $ndini $ndlnk $#tarc\n";
							} else {
								splice(@tarc, $k, 1);
								$debug_list .= "DEBUG trouve arc $arc ($sens)\n";
								# nombre de points moins un car sinon on double le
								# point commun aux deux arcs (on rajoute 1 au
								# premier arc pour terminer le polygone)
								@regsize[$nbreg] += $arcsize{$arc};
								push (@tchain, $arc);
								push (@tdirec, $sens);				
							}
						} else {
							$nbreg++;
							$arc=pop(@tarc);
							push(@tchain, $arc);
							push (@tdirec, -1);
							@regsize[$nbreg] = $arcsize{$arc} + 1;
							$ndini=$tabarcdeb{$arc};
							$ndlnk=$tabarcfin{$arc};
							$sens=-1;
							$debug_list .= "DEBUG new arc $arc ($sens) $ndini $ndlnk $#tarc\n";
						}
					}
				}
				# il reste ï¿½ ï¿½crire les coordonnï¿½es en respectant le sens des arcs
				# le premier arc est prï¿½cï¿½dï¿½ du total du nombre de points
				$nbreg++;
				if ($debug_on >= 6) {
					print "DEBUG Rï¿½gions $nbreg taille @regsize @tchain @tdirec\n";
				}
#				print $fmif "Region $nbreg\n";
				$k=-1;
				print $fsql "geometryfromtext('MULTIPOLYGON(((";
				$txt="";
				$som1="";
				$tri=1;
				for ($j=0; $j <= $#tchain; $j++) {
					$arc = @tchain[$j];
					@coord = split(/:/,$tabcoor{$arc});
					if (@tdirec[$j] < 0) {
						$k++;
#						print $fmif "@regsize[$k]\n";
						for $co (@coord) {
							$txt.="$co";
							if ($som1 eq $co){
								$txt.="),(";
								$som1="";
								$tri++;
							}else{
								$txt.=",";
								if ($som1 eq ""){
									$som1=$co;
								}
							}
						}
					} elsif (@tdirec[$j] == 1) {
						for ($l=1; $l<=$#coord; $l++) {
							$txt.="@coord[$l]";
							if ($som1 eq @coord[$l]){
								$txt.="),(";
								$som1="";
								$tri++;
							}else{
								$txt.=",";
								if ($som1 eq ""){
									$som1=@coord[$l];
								}
							}
						}
					} else {
						for ($l=$#coord-1; $l>=0; $l--) {
							$txt.="@coord[$l]";
							if ($som1 eq @coord[$l]){
								$txt.="),(";
								$som1="";
								$tri++;
							}else{
								$txt.=",";
								if ($som1 eq ""){
									$som1=@coord[$l];
								}
							}
						}
					}
				} # fin ï¿½criture coordonnï¿½es
				if ($tri>1){
					$txt=substr($txt,0,length($txt)-3);
				}else{
					$txt=substr($txt,0,length($txt)-1);
				}
				print $fsql $txt.")))','".$projection."'));\n";
				# Ecriture symbolisation s'il y a lieu
				if ($symbol ne "") {
					#print $fsql "$symbol";
				}
			} else {
				print "ERREUR INATTENDUE! $obj dans $fic sans gï¿½omï¿½trie !\n";
			}
		}
		close($fsql);
	}
#prï¿½voir terminaison des fichiers sql
}

