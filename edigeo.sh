 #!/bin/sh
#maj_edigeo.dd repertoire_edigeo code_d�partement+cc r�pertoire_d�compo
#repert=dirname edigeo.sh
cd $1
inter=0		#commande de creation des tables au d�but du fichier si =0
  for i in * ;do
  	mkdir $3/$i
  	cd $i
	commune=$2${i:4:3}
	echo $commune
  	for o in *;do
 		sect=${o:14:2}
		cd $1/$i/$o
  		tar -xjf $1/$i/$o/$o.tar.bz2 
#ecriture en majuscule des noms de fichier	
  		for p in * ;do 
			nomF=`basename $p`
			n=`echo $nomF | tr [:lower:] [:upper:]`
			if  test "$nomF" != "$n" ;then
				mv $nomF $n
			fi
		done
#appelle de la fonction d'�criture des donn�es EGIGEO dans fichier SQL
 		perl $6/edi2mif.pl $1/$i/$o $3/$i test_cadastre $commune $inter $4
#		cd ..
		inter=1	#on ne cr�e les tables qu'une fois!
  	done
#  	cd /home/sig
  	cd $1
 done
cd $3

#boucle de lecture des fichiers sql
for i in *;do
	for o in $i/*.sql;do
		#echo $o
		psql -e meaux -f $o -U $5
	done
done
cd ..
#/usr/local/pgsql/bin/psql -e meaux -f maj_edigeo_sql.sql
