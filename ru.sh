for i in /home/sig/shp/*.shp;do
	pos=${#i}-4
	fic=${i:0:$pos}
	/usr/local/pgsql/bin/shp2pgsql -a /home/sig/shp/$fic geotest.$fic gismeaux > $fic'.sql'
	/usr/local/pgsql/bin/psql -d gismeaux -f home/sig/shp/$fic'.sql'
done