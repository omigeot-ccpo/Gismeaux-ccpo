<?php
/*Copyright Ville de Meaux 2004-2007
contributeur: jean-luc Dechamp - robert Leguay 
sig@meaux.fr

Ce logiciel est un programme informatique fournissant une interface cartographique WEB communale. 

Ce logiciel est r�i par la licence CeCILL-C soumise au droit fran�is et
respectant les principes de diffusion des logiciels libres. Vous pouvez
utiliser, modifier et/ou redistribuer ce programme sous les conditions
de la licence CeCILL-C telle que diffus� par le CEA, le CNRS et l'INRIA 
sur le site "http://www.cecill.info".

En contrepartie de l'accessibilit�au code source et des droits de copie,
de modification et de redistribution accord� par cette licence, il n'est
offert aux utilisateurs qu'une garantie limit�.  Pour les m�es raisons,
seule une responsabilit�restreinte p�e sur l'auteur du programme,  le
titulaire des droits patrimoniaux et les conc�ants successifs.

A cet �ard  l'attention de l'utilisateur est attir� sur les risques
associ� au chargement,  �l'utilisation,  �la modification et/ou au
d�eloppement et �la reproduction du logiciel par l'utilisateur �ant 
donn�sa sp�ificit�de logiciel libre, qui peut le rendre complexe �
manipuler et qui le r�erve donc �des d�eloppeurs et des professionnels
avertis poss�ant  des connaissances  informatiques approfondies.  Les
utilisateurs sont donc invit� �charger  et  tester  l'ad�uation  du
logiciel �leurs besoins dans des conditions permettant d'assurer la
s�urit�de leurs syst�es et ou de leurs donn�s et, plus g��alement, 
�l'utiliser et l'exploiter dans les m�es conditions de s�urit� 

Le fait que vous puissiez acc�er �cet en-t�e signifie que vous avez 
pris connaissance de la licence CeCILL-C, et que vous en avez accept�les 
termes.*/
//session_start();
require_once("connexion/deb.php");
if ($_SESSION['code_insee']) {

 	$query_commune="SELECT * FROM admin_svg.commune where idcommune like '".$_SESSION['code_insee']."'";
}
$row_commune = tab_result($pgx,$query_commune);
echo '<html><head><title>'.$titre.'</title>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
echo '</head><body class="body"><table width="100%" align="center"><tr>';
echo '<td width="'.$row_commune[0]['larg_logo'].'"> <img src="'.$row_commune[0]['logo'].'" width="'.$row_commune[0]['larg_logo'].'" height="35" border="0"> ';
if ($row_commune[0]['idcommune']==$row_commune[0]['idagglo']) {
    echo '</td><td class="tt3"> '.$row_commune[0]['nom'];
}else{
    echo '</td><td class="tt3"> Commune de '.$row_commune[0]['nom'];
}
$query_agglo="SELECT * FROM admin_svg.commune where idcommune like '".$row_commune[0]['idagglo']."'";
$row_agglo = tab_result($pgx,$query_agglo);
echo '</td><td width="'.$row_agglo[0]['larg_logo'].'"> <img src="'.$row_agglo[0]['logo'].'" width="'.$row_agglo[0]['larg_logo'].'" height="35" border="0"> ';

echo '<input name="commune" type="hidden" value="'.$row_commune[0]['idcommune'].'"> <br>';
echo '</td></tr><tr><td colspan=2>';
echo '</table>';
?>
