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
ini_set('session.gc_maxlifetime', 3600);
session_start();
//$pgx = pg_connect("host=localhost dbname=meaux user=meaux");
include('../connexion/deb.php');
$indice=strtoupper($indice);
$essai=ereg("([A-Z]{1,2})", $indice, $regs);
$debut=$regs[1];
$reste='0000'.str_replace($regs[1],"",$indice);
$reste=substr($reste,-4);
$concat=$debut.$reste;
print("<g id=\"recherche\" onclick=\"clear('controlrecherche');rechercheavance(evt)\"  onmouseover=\"switchColor(evt,'fill','red','','')\" onmouseout=\"switchColor(evt,'fill','url(#survol)','','')\" style=\"text-anchor:middle\">");
if(substr($code_insee, -3)=='000')
{
$result = pg_exec($pgx,"SELECT identifian,commune.nom FROM cadastre.parcelle join admin_svg.commune on parcelle.code_insee=commune.idcommune WHERE (identifian LIKE '%$indice' OR identifian LIKE '%$concat') AND code_insee like '".substr($code_insee,0,3)."%' group by identifian,nom");
}
else
{
$result = pg_exec($pgx,"SELECT parcelle.identifian FROM cadastre.parcelle WHERE (identifian LIKE '%$indice' OR identifian LIKE '%$concat') AND code_insee=".$code_insee." group by identifian");
}
$num = pg_numrows($result);
if ($num>0)
{
print("<text  x='316' y='120' style='pointer-events:none' class='fillfonce'>Sur la couche parcelle</text>");
}
$n=132;
for ($i=0; $i<$num; $i++)
{  
$r = pg_fetch_row($result, $i);
$nom=$r[0];
if($r[1]!="")
{
$nom1=$r[1].' ';
}
print("<text n=\"1\" x='316' y='$n' id=\"$nom\" class='fillfonce'>$nom1$nom</text>");

$n=$n+13;
}




$z=$n+13;
if(substr($code_insee, -3)=='000'){
    $result = pg_exec($pgx,"SELECT voies.commune||code_voie as cod,commune.nom||', '||voies.nom_voie as nom_voie FROM cadastre.voies join admin_svg.commune on voies.commune=commune.idcommune WHERE voies.nom_voie LIKE '%$indice%' AND voies.commune like'".substr($code_insee,0,3)."%'");
	
}else{
    $result = pg_exec($pgx,"SELECT voies.commune||code_voie as cod,nom_voie FROM cadastre.voies WHERE nom_voie LIKE '%".$indice."%' AND commune='".$code_insee."'");
}
$num = pg_numrows($result);
if ($num>0)
{
print("<text n=\"2\" x='316' y='$z' style='pointer-events:none' class='fillfonce'>Sur l'adresse</text>");
}
$n=$z+15;
for ($i=0; $i<$num; $i++)
{  
$r = pg_fetch_row($result, $i);
$code=$r[0];
$nom=$r[1];
print("<text id=\"$code\" n=\"2\" x='316' y='$n' class='fillfonce'>$nom</text>");

$n=$n+13;
}

print("</g>");
//pg_close($pgx);

?>
