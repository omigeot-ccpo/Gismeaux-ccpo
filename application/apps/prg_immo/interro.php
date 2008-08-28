<html>
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

if ((!$_SESSION['profil']->acces_ssl) || !in_array ("Prg Immobilier", $_SESSION['profil']->liste_appli)){
	die("Point d'entrée réglementé.<br> Accès interdit. <br>Veuillez vous connecter via <a href=\"https://".$_SERVER['HTTP_HOST']."\">serveur carto</a><SCRIPT language=javascript>setTimeout(\"window.location.replace('https://".$_SERVER['HTTP_HOST']."')\",5000)</SCRIPT>");
}
if ($_POST['action']=='modif'){
   $toto=$_POST['indiv']+$_POST['collec'];
   $sql="update urba.program set nom='".$_POST['prog']."',nbre_lgt='".$toto."',typ_lgt='".$_POST['type']."',nbre_ind='".$_POST['indiv']."',nbre_coll='".$_POST['collec']."',annee='".$_POST['annee']."',pc='".$_POST['pc']."',commentaire='".$_POST['commentaire']."' where gid='".$_POST['obj_keys']."' ";
   $DB->exec($sql);
}elseif($_POST['action']=='supp'){
   $sql="delete from urba.program where gid='".$_POST['obj_keys']."' ";
   $DB->exec($sql);
}elseif($_POST['action']=='cree'){
    $toto=$indiv+$collec;
    $sql="insert into urba.program (nom,nbre_lgt,typ_lgt,nbre_ind,nbre_coll,annee,pc,commentaire,the_geom) values ('".$_POST['prog']."','".$toto."','".$_POST['type']."','".$_POST['indiv']."','".$_POST['collec']."','".$_POST['annee']."','".$_POST['pc']."','".$_POST['commentaire']."',GeometryFromtext('POLYGON((".$_POST['polygo']."))',$projection) )";
    $DB->exec($sql);
}
if($_GET['obj_keys']){
      $sql="SELECT nom,nbre_lgt,typ_lgt,nbre_ind,nbre_coll,annee,pc,commentaire from urba.program where gid IN(".$_GET['obj_keys'].")";
      $result = $DB->exec($sql);
      $num = pg_numrows($result);
      for ($i=0; $i<$num; $i++)
      {
              $r = pg_fetch_row($result, $i);
              $nom=$r[0];
              $type=$r[2];
              $nbind=$r[3];
              $nbcoll=$r[4];
              $anne=$r[5];
              $pc=$r[6];
              $comme=$r[7];
      }
}
if (($_POST['action']=='supp')||($_POST['action']=='modif')){echo '<body onload="window.close();">';}
elseif ($_POST['action']=='cree'){echo '<body onload="history.go(-2);">';}
else{echo "<body>";}
?>
<form action="interro.php" method="post">
  <p>Nom du Programme:
    <input name="prog" type="text" value="<?php echo $nom;?>" size="25">
  </p>
  <p>Type de logement:<select name="type">
      <option value="M" <?php if($type=="M")echo "selected";?> >Mixte</option>
      <option value="C" <?php if($type=="C")echo "selected";?> >Collectif</option>
      <option value="I" <?php if($type=="I")echo "selected";?> >Individuel</option>
        </select>
</p>
  <p>Nombre de logement individuel:
    <input name="indiv" type="text" value="<?php echo $nbind;?>" size="3">
</p>
  <p>Nombre de logement collectif:
    <input name="collec" type="text" value="<?php echo $nbcoll;?>" size="3">
</p>
<p>N� Permis de construire:
    <input name="pc" type="text" value="<?php echo $pc;?>" size="25">
          </p>
  <p>Annee de livraison du programme:
    <input name="annee" type="text" value="<?php echo $anne;?>" size="4">
          </p>
		  <p>
		  Commentaire:</p>
		<p>
    <textarea name="commentaire" cols="30" rows="5"><?php echo $comme;?></textarea>
          </p>  
    <input name="action" type="hidden" value="">
<?php if ($_GET['obj_keys']){ ?>
    <input name="obj_keys" type="hidden" value="<?php echo $_GET['obj_keys'];?>">
    <input name="Modifier" type="button" value="Modifier" onClick="document.forms[0].action.value='modif';submit();">
	<input name="Supprimer" type="button" value="Supprimer" onClick="document.forms[0].action.value='supp';submit();">
<?php }else{ ?>
    <input name="polygo" type="hidden" value="<?php echo $_GET['polygo'];?>">
     <input name="Ajouter" type="button" value="Ajouter" onClick="document.forms[0].action.value='cree';submit();">
<?php }?>
	<input name="Fermer" type="button" value="Fermer" onClick="window.close();">
</form>
</body>
</html>
