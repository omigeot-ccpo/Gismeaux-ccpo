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

if ((!$_SESSION['profil']->acces_ssl) || !in_array ("scolaire", $_SESSION['profil']->liste_appli)){
	die("Point d'entrée réglementé.<br> Accès interdit. <br>Veuillez vous connecter via <a href=\"https://".$_SERVER['HTTP_HOST']."\">serveur carto</a><SCRIPT language=javascript>setTimeout(\"window.location.replace('https://".$_SERVER['HTTP_HOST']."')\",5000)</SCRIPT>");
}
?>
<style type="text/css">
<!--
.Style1 {font-size: smaller}
-->
</style>
<script language="JavaScript">
<!--
function ajoute(page)
{
	var indexfo0=document.forms[0].elements[0].options.selectedIndex;
	var hypotese=document.forms[0].elements[0].options[indexfo0].value;
	var indexfo1=document.forms[0].elements[1].options.selectedIndex;
	var niv=document.forms[0].elements[1].options[indexfo1].value;
	var indexfo2=document.forms[0].elements[2].options.selectedIndex;
	var eta=document.forms[0].elements[2].options[indexfo2].value;
	
	location.href=page+".php?polygo=<?php echo $polygo;?>&mat1=<?php echo $mat1;?>&mat2=<?php echo $mat2;?>&mat3=<?php echo $mat3;?>&cp=<?php echo $cp;?>&ce1=<?php echo $ce1;?>&ce2=<?php echo $ce2;?>&cm1=<?php echo $cm1;?>&cm2=<?php echo $cm2;?>&hypotese="+hypotese+"&niv="+niv+"&eta="+eta;

}

</script> 
<table width="600" border="0" align="center">
<tr><td valign="top">
<table width="200" border="0" align="left" class="Style1" >

<?php
//$connexion = pg_connect("host=localhost dbname=meaux user=meaux");
$sql="SELECT count(nompre_enft)as nb_enfant FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol =('PETITES SECTIONS')";
$r = $DB->tab_result($sql);
$mat1=$r[0][0];

$sql="SELECT count(nompre_enft)as nb_enfant FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol =('MOYENS')";
$r = $DB->tab_result($sql);
$mat2=$r[0][0];

$sql="SELECT count(nompre_enft)as nb_enfant FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol =('GRANDS')";
$r = $DB->tab_result($sql);
$mat3=$r[0][0];

$sql="SELECT count(nompre_enft)as nb_enfant FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol =('COURS PREPARATOIRE')";
$r = $DB->tab_result($sql);
$cp=$r[0][0];

$sql="SELECT count(nompre_enft)as nb_enfant FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol =('COURS ELEMENTAIRE 1ERE ANNEE')";
$r = $DB->tab_result($sql);
$ce1=$r[0][0];

$sql="SELECT count(nompre_enft)as nb_enfant FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol =('COURS ELEMENTAIRE 2EME ANNEE')";
$r = $DB->tab_result($sql);
$ce2=$r[0][0];

$sql="SELECT count(nompre_enft)as nb_enfant FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol =('COURS MOYEN 1ERE ANNEE')";
$r = $DB->tab_result($sql);
$cm1=$r[0][0];

$sql="SELECT count(nompre_enft)as nb_enfant FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol =('COURS MOYEN 2EME ANNEE')";
$r = $DB->tab_result($sql);
$cm2=$r[0][0];


$sql="SELECT count(nompre_enft)as nb_enfant,etablissement.etablissement as eta,enf.id_etablissement
FROM (scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli)) INNER JOIN scolaire.etablissement ON (scolaire.enf.id_etablissement = scolaire.etablissement.id_etablissement) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol IN('PETITES SECTIONS','MOYENS','GRANDS')
group by enf.id_etablissement,etablissement.etablissement";
//$sql1="SELECT nompre_enft,etablissement,niv_scol,adr1 FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol IN('PETITES SECTIONS','MOYENS','GRANDS')";
$sql2="SELECT count(nompre_enft)as nb_enfant,etablissement.etablissement as eta,enf.id_etablissement
FROM (scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli)) INNER JOIN scolaire.etablissement ON (scolaire.enf.id_etablissement = scolaire.etablissement.id_etablissement) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol NOT IN('PETITES SECTIONS','MOYENS','GRANDS')
group by enf.id_etablissement,etablissement.etablissement";
// $sql2="SELECT nompre_enft,etablissement,niv_scol,adr1 FROM scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0  and scolaire.enf.type_ins='D' and niv_scol NOT IN('PETITES SECTIONS','MOYENS','GRANDS')";
$r = $DB->tab_result($sql);
$nb=0;
echo "<tr><td colspan=3 bgcolor='#dddddd' align='center'><strong>Donn�es sur la section dessin�e</strong></td></tr>";
echo "<tr><td colspan=3><strong><font size='3'>Maternelle</font></strong></td></tr>";
for ($i=0; $i<count($r); $i++)
{  
echo "<tr><td colspan=2><strong>Etab: ".$r[$i][1]."</strong></td><td><strong>".$r[$i][0]." enfant(s)</strong></td></tr>";

$nb=$nb+$r[0];
$result1 = $DB->tab_result("SELECT count(nompre_enft)as nb_enfant,abrege
FROM (scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli)) INNER JOIN scolaire.niveau ON (scolaire.enf.niv_scol = scolaire.niveau.niveau) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0 and id_etablissement='".$r[$i][2]."' and scolaire.enf.type_ins='D'
group by abrege,code order by code ASC");

for ($ii=0; $ii<count($result1); $ii++)
{  
echo "<tr><td>&nbsp;</td><td>".$result1[$ii][0]." enfant(s)</td><td>".$result1[$ii][1]."pour info tous les d�tails </td></tr>";
}
}
echo "<tr><td colspan=3>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;</td><td bgcolor='#dddddd' align='center'>$nb enfant(s)</td><td>&nbsp;</td></tr>";

$result = $DB->tab_result($sql2);
$nb=0;
echo "<tr><td colspan=3>&nbsp;</td></tr>";
echo "<tr><td colspan=3><strong><font size='3'>Primaire</font></strong></td></tr>";
for ($i=0; $i<count($result); $i++)
{  
$r = pg_fetch_row($result, $i);
echo "<tr><td colspan=2><strong>Etab: ".$result[$i][1]."</strong></td><td><strong>".$result[$i][0]." enfant(s)</strong></td></tr>";

$nb=$nb+$r[0];
$result1 = $DB->tab_result("SELECT count(nompre_enft)as nb_enfant,abrege
FROM (scolaire.enf INNER JOIN eco.adresse ON (scolaire.enf.numero = eco.adresse.numero) AND (scolaire.enf.rivoli = eco.adresse.rivoli)) INNER JOIN scolaire.niveau ON (scolaire.enf.niv_scol = scolaire.niveau.niveau) where  Distance('POLYGON((".$polygo."))',eco.adresse.the_geom)=0 and id_etablissement='".$result[$i][2]."' and scolaire.enf.type_ins='D'
group by abrege,code order by code ASC");

for ($ii=0; $ii<count($result1); $ii++)
{  
echo "<tr><td>&nbsp;</td><td>".$result1[$ii][0]." enfant(s)</td><td>".$result1[$ii][1]."</td></tr>";
}
}
echo "<tr><td colspan=3>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;</td><td bgcolor='#dddddd' align='center'>$nb enfant(s)</td><td>&nbsp;</td></tr>";


?>

 
</table></td><td>
<table width="200" border="1" class="Style1">
<tr bgcolor="#dddddd"><td colspan="3" align="center"><strong>Pr�vision</strong></td>
</tr>
  <tr>
    <td>&nbsp;</td>
    <td><strong>2006-2007</strong></td>
    <td><strong>2007-2008</strong></td>
  </tr>
  <tr>
    <td>Petits</td>
    <td><?php $var1=ceil((((((($cm2+$cm1+$ce2+$ce1+$cp)/5+$mat3)/2)+$mat2)/2)+$mat1)/2);echo $var1;?></td>
    <td><?php $var2=ceil((((((((($cm2+$cm1+$ce2+$ce1+$cp)/5+$mat3)/2)+$mat2)/2)+$mat1)/2)+$var1)/2);echo $var2;?></td>
  </tr>
  <tr>
    <td>Moyens</td>
    <td><?php echo $mat1 ;?></td>
    <td><?php echo $var1;?></td>
  </tr>
  <tr>
    <td>Grands</td>
    <td><?php echo $mat2 ;?></td>
    <td><?php echo $mat1 ;?></td>
  </tr>
  <tr>
    <td>CP</td>
    <td><?php echo $mat3 ;?></td>
    <td><?php echo $mat2 ;?></td>
  </tr>
  <tr>
    <td>CE1</td>
    <td><?php echo $cp ;?></td>
    <td><?php echo $mat3 ;?></td>
  </tr>
  <tr>
    <td>CE2</td>
    <td><?php echo $ce1 ;?></td>
    <td><?php echo $cp ;?></td>
  </tr>
  <tr>
    <td>CM1</td>
    <td><?php echo $ce2 ;?></td>
    <td><?php echo $ce1 ;?></td>
  </tr>
  <tr>
    <td>CM2</td>
    <td><?php echo $cm1 ;?></td>
    <td><?php echo $ce2 ;?></td>
  </tr></table>
 <table  border=0 class="Style1"> <tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan='2' bgcolor="#DDDDDD"><em>Section concernant le(s) p�rim�tre(s) actuelle(s)</em></td>
</tr>
<tr><td colspan="2"><strong>Maternelle</strong></td>
</tr> 
<?php $sql="SELECT etablissement,etablissement.id_etablissement FROM scolaire.etablissement INNER JOIN scolaire.perimetre ON (scolaire.etablissement.etablissement = scolaire.perimetre.nom) where  perimetre.type_ecole like 'mat%' and etablissement.code_eta='1' and Distance('POLYGON((".$polygo."))',scolaire.perimetre.the_geom)=0";
$result = $DB->tab_result($sql);
for ($i=0; $i<count($result); $i++)
{
echo "<tr><td colspan='2'><em><strong>".$result[$i][0]."</strong></em></td></tr>";
echo "<tr><td colspan='2'><table  border=1 class='Style1'>";
$sql1="SELECT count(nompre_enft)as nb_enfant,abrege FROM scolaire.enf INNER JOIN scolaire.niveau ON (scolaire.enf.niv_scol = scolaire.niveau.niveau) where id_etablissement='".$result[$i][1]."' and type_ins='D' group by abrege,code order by code ASC";
$result1 = $DB->tab_result($sql1);
for ($ii=0; $ii<count($result1); $ii++)
{
echo "<tr><td>".$result1[$ii][1]."</td><td>".$result1[$ii][0]."</td></tr>";
}
echo "</table></td></tr>";
}
echo "
<tr><td colspan='2'>&nbsp;</td></tr>
<tr><td colspan='2'><strong>Primaire</strong></td>
</tr> ";
$sql="SELECT etablissement,etablissement.id_etablissement FROM scolaire.etablissement INNER JOIN scolaire.perimetre ON (scolaire.etablissement.etablissement = scolaire.perimetre.nom) where  perimetre.type_ecole like 'pri%' and etablissement.code_eta='2' and Distance('POLYGON((".$polygo."))',scolaire.perimetre.the_geom)=0";
$result = $DB->tab_result($sql);
for ($i=0; $i<count($result); $i++)
{
echo "<tr><td colspan='2'><em><strong>".$result[$i][0]."</strong></em></td></tr>";
echo "<tr><td colspan='2'><table  border=1 class='Style1'>";
$sql1="SELECT count(nompre_enft)as nb_enfant,abrege FROM scolaire.enf INNER JOIN scolaire.niveau ON (scolaire.enf.niv_scol = scolaire.niveau.niveau) where id_etablissement='".$rsult[$i][1]."' and type_ins='D' group by abrege,code order by code ASC";
$result1 = $DB->tab_result($sql1);
for ($ii=0; $ii<count($result1); $ii++)
{
echo "<tr><td>".$result1[$ii][1]."</td><td>".$result1[$ii][0]."</td></tr>";
}
echo "</table></td></tr>";
}?></table>
</td></tr>
<form action="valide.php?polygo=<?php echo $polygo;?>&mat1=<?php echo $mat1;?>&mat2=<?php echo $mat2;?>&mat3=<?php echo $mat3;?>&cp=<?php echo $cp;?>&ce1=<?php echo $ce1;?>&ce2=<?php echo $ce2;?>&cm1=<?php echo $cm1;?>&cm2=<?php echo $cm2;?>" method="post" target="_parent">
<table width="600" border="0" align="center">
  <tr>
    <td align="right"><strong><span class="Style1">Hypotese</span></strong></td>
    <td><select name="hypotese" size="1">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      </select></td>
  </tr>
  <tr><span class="Style1">
    <td>&nbsp;</td><td><select name="niv" size="1">
      <option value="1">Maternelle</option>
      <option value="2">Primaire</option>
    </select></td>
  </span></tr>
  <tr>
    <td align="right"><strong><span class="Style1">Etablissement</span></strong></td>
    <td><select name="eta" size="1">
	<?php
	
	$sql="SELECT etablissement FROM scolaire.etablissement group by etablissement";

$result = $DB->tab_result($sql);
for ($i=0; $i<count($result); $i++)
{ 
echo "<option value='".$result[$i][0]."'>".$result[$i][0]."</option>";
}
?>
</select>
  </tr>
  <tr>
    <td>&nbsp;</td><td><input name="Annuler" type="button" onClick="javascript:history.back()" value="Annuler">
	
	<input name="Valider" type="button" value="Valider" onClick="ajoute('valide');">
	</td>
  </tr>
</table>

</form>

