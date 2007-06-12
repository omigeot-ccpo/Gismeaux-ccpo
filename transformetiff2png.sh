#!/bin/sh
cd $1
for i in *.tif ; do
/usr/local/bin/gdal_translate -ot Byte -of PNG -outsize $3% $3% $i $2/${i/.tif/.png}
done
#for i in *.tfw ; do
#cp $i $2/${i/.tfw/.wld}
#done