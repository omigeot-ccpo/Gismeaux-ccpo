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

if (!$_SESSION['profil']->ok($insee = $insee, $appli = $appli))
  {
    die("Interdit.");
    //TODO: Trouver une sortie plus élégante et informative.
  }

if (isset($_GET["commune"]))
  {
    $commune=$_GET["commune"];
  }
 else
   {
     $commune=$insee;
   }
if ($_GET["obj_keys"])
  {
    $comma1="select * from cadastre.parcel where ind in ('".str_replace(",","','",$_GET["obj_keys"])."')";
  }
 else
   {
     if ($_GET["sect"]!="" and $_GET["num"]=="")
       {
	 $comma1="select * from cadastre.parcel where ";
	 $comma1.="ccosec like '".$_GET["sect"]."'";
       }
     elseif ($_GET["sect"]!="" and $_GET["num"]!="")
       {
	 $comma1="select * from cadastre.parcel where ";
	 $comma1.="par1 like '".$_GET["sect"].$_GET["num"]."'";
       }
     elseif ($_GET["pnprop"]!="")
       {
	 $comma2="select prop1 from cadastre.propriet where ddenom like '%".$_GET["pnprop"]."%' and commune like '".$commune."'";
	 $result=$DB->tab_result($comma2);
	 $comma1="select * from cadastre.parcel where prop1 in('";
	 for ($i=0;$i<count($result);$i++)
	   {
	     $comma1.=$result[$i][0]."','";
	   }
	 $comma1.="')";
       }
     if ($commune)
       {
	 $comma1.=" and commune like '".$commune."'";
       }
   }
if ($_GET["sect"]!="" or $_GET["num"]!="" or $_GET["pnprop"]!="" or $_GET["obj_keys"]!="")
  {
    $rowparc=$DB->tab_result($comma1);
    $nbr_par=count($rowparc);
  }
if ($_GET["noprop"]!="")
  {
    $comma2="select * from cadastre.propriet where ddenom like '%".$_GET["noprop"]."%' ";
    if ($commune)
      {
	$comma2.=" and commune = '".$commune."'";
      }
    $pprow=$DB->tab_result($comma2);
    $nbr_par=0;
  }
set_time_limit(120);
if ($nbr_par==1)
  {
    /* fiche parcelle */
    $par1=$rowparc[0]['ind'];
    header("Location:./fic_parc2.php?ind=$par1&".session_name()."=".session_id());
  }
elseif ($nbr_par==0)
{
  $titre='Recherche cadastrale';
  include('head.php');
  $presult=count($pprow);
  if ($presult==0)
    {
      echo "Critères ne correspondant à aucun éléments de la table ".$_GET["obj_keys"];
    }
  elseif ($presult==1)
    {
      /* fiche propriétaire */
      $prop1=$pprow[0]['prop1'];
      include ("fic_prop.php");
    }
  elseif ($presult>1)
    {
      /* table propriétaire */
      echo '<table width="100%" align="center">';
      echo '<tr><td>Nom</td><td>Adresse</td></tr>';
      for ($j=0;$j<$presult;$j++)
	{
	  echo "<tr><td rowspan=\"5\"><a href=\"fic_prop.php?prop1=".$pprow[$j]['prop1']."&".session_name()."=".session_id()."\" target=\"_self\">".$pprow[$j]['ddenom']."</a></td>";
	  echo "<td>".$pprow[$j]['dlign3']."</td></tr><tr>";
	  echo "<td>".$pprow[$j]['dlign4']."</td></tr><tr>";
	  echo "<td>".$pprow[$j]['dlign5']."</td></tr><tr>";
	  echo "<td>".$pprow[$j]['dlign6']."</td></tr><tr>";
	  echo "<td>".$pprow[$j]['ccopay']."</td></tr>";
	}
      echo '</table>';
    }
}
elseif ($nbr_par>1)
{
  /* table des parcelle */
  $titre='Recherche cadastrale';
  include('head.php');
  echo '<table width="100%" align="center">';
  echo '<tr><td>Numéro</td><td>Contenance</td><td>adresse</td><td>Propriétaire</td><td>Adresse Propriétaire</td></tr>';
  for($k=0;$k<$nbr_par;$k++)
    { 
      $rvoie=$DB->tab_result("select nom_voie from cadastre.voies where code_voie='".$rowparc[$k]['ccoriv']."' and commune ='".$rowparc[$k]['commune']."';" );
      $rowvoie=$rvoie[0][0];
      $rprop=$DB->tab_result("select ddenom,dlign3,dlign4,dlign5,dlign6 from cadastre.propriet where prop1='".$rowparc[$k]['prop1']."' and gdesip='1' and commune='".$rowparc[$k]['commune']."';") ;
      echo '<tr>';
      echo '<td><a href="fic_parc2.php?ind='.$rowparc[$k]['ind'].'&'.session_name().'='.session_id().'" target="_self">'.$rowparc[$k]['par1'].'</a></td>';
      echo '<td>'.$rowparc[$k]['dcntpa'].'</td>';
      echo '<td>'.$rowparc[$k]['dnuvoi'].$rowparc[$k]['dindic'].", ".$rowvoie.'</td>';
      echo '<td>'.$rprop[0]['ddenom'].'</td>';
      echo '<td>'.$rprop[0]['dlign3'].$rprop[0]['dlign4'].$rprop[0]['dlign5'].$rprop[0]['dlign6'].'</td>';
      echo '</tr>';
    }
  echo '</table>';
}
include('pied_cad.php');
?>
