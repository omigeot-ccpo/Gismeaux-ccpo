#!/bin/sh
#premier param�re : r�ertoire contenant les shp
#deuxi�e param�re : Sch�a postgr� contenant la table
#troisième paramètre : nom de la base postgrès
for i in $1/*.shp;do
	pos=${#i}-4
	fic=${i:0:$pos}
# 	nam=`basename $i`
# 	pos=${#nam}-4
# 	fic1=${nam:0:$pos}
# 	tabl=${fic1:0:$pos-$lon}
# 	echo $tabl
	/usr/local/pgsql/bin/shp2pgsql -a $fic $2.$fic $3 > $fic'.sql'
	/usr/local/pgsql/bin/psql -e $3 -f $fic'.sql'
done