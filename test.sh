while read ligne
do
fic=filo${ligne:3}".csv"
echo "convertxls2csv -x ../FILOCOM_CA_Meaux_Habitat_insalubre2.xls -b UTF-8 -a UTF-8  -c $fic -w $ligne"
convertxls2csv -x ../FILOCOM_CA_Meaux_Habitat_insalubre2.xls -b UTF-8 -a UTF-8 -c $fic -w $ligne
done < liste.txt
