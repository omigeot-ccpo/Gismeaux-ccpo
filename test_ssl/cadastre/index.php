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
session_start();
if (! $PHP_AUTH_USER || ! $PHP_AUTH_PW){
	header('status: 401 Unauthorized');
	header('HTTP/1.0 401 Unauthorized');
	header("WWW-authenticate: Basic realm=\"Veuillez vous identifier\"");
}else{
    include("../connexion/deb.php");
    if (!isset($_SESSION["code_insee"])){

       $oten="select * from general.utilisateur2 where login='".$PHP_AUTH_USER."' and psw='".$PHP_AUTH_PW."'";
       $ot=tab_result($pgx,$oten);
       if (count($ot)>0) {
          $_SESSION["code_insee"]=$ot[0]['commune'];
          $_SESSION["utcleunik"]=$ot[0]['utcleunik'];
          $_SESSION["droit"]=$ot[0]['droit'];
       }
   }
/*		if ($commune=='77100'){
			//Choix de la commune
			$l_comm=tab_result($pgx,"SELECT * FROM cadastre.commune order by lib_com");
			echo '<form action="index.php" method="post">';
			echo 'Sélectionnez la commune : <br>';
			echo '<select name="commune">';
			for ($i=0;$i<count($l_comm);$i++){
				echo '<option value="'.$l_comm[$i][0].'">'.$l_comm[$i][1].'</option>';
			}
			echo '</select>';
			echo '<input name="" type="submit" value="O.k.">';
			echo '</form>';
		}else{*/
			//recherche de la parcelle
    if (isset($_SESSION["code_insee"])){
		$titre='Recherche cadastrale';
		if (!isset($appli)){include('head_cad.php');}else{echo '<link href="https://'.$HTTP_HOST.'/svg4/css/'.$_SESSION['code_insee'].'.css" rel="stylesheet" type="text/css"><body class="body">';}
		echo '<form action="fich_parc.php" method="get">';
//		echo '<input name="commune" type="hidden" value="'.$_SESSION["code_insee"].'">';
		echo '<input name="'.session_name().'" type="hidden" value="'.session_id().'">';
  		echo '<table width="100%" align="center">';
    // pour acces agglomération
    if (substr($_SESSION["code_insee"],4)=='000'){
         echo '<tr><td colspan=2><h2>Recherche sur la commune de :</h2>';
         $q_commune="select idcommune,nom from admin_svg.commune where idagglo ='".$_SESSION["code_insee"]."' and idcommune !='".$_SESSION["code_insee"]."' order by nom asc";
         $r_commune=tab_result($pgx,$q_commune);
         echo '<select name="commune">';
         for ($p=0;$p<count($r_commune);$p++){
             echo '<option value='.$r_commune[$p]['idcommune'].'>'.$r_commune[$p]['nom'].'</option>';
         }
         echo "</select>";
         echo '</td><td colspan=2><h2>&nbsp;</h2></td></tr>';
    }
		echo '<tr><td colspan=2><h2>Parcelle</h2></td><td colspan=2><h2>Propriétaire</h2></td></tr>';		
		echo '<tr><td rowspan=2 width="22%" >Section<br>numero</td>';
		echo '<td rowspan=2 width="28%"><input name="sect" type="text" onChange="javascript:this.value=this.value.toUpperCase();" size="2" maxlength="2"><br><input name="num" type="text" size="5" maxlength="4"></td>';
		echo '<td width="25%">Nom du propriétaire</td>';
		echo '<td width="25%"><input name="noprop" type="text" onChange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30"></td>';
		echo '</tr><tr><td></td><td></td></tr>';
        if ($_SESSION["droit"]=='AD'){
           echo '<tr><td>Par le nom du propri&eacute;taire</td>';
		   echo '<td><input name="pnprop" type="text" onChange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30"></td>';
		   echo '<td></td><td></td></tr>';
        }
        echo '<tr><td colspan=2 align="center"><input name="" type="submit" value="Rechercher"></td><td colspan=2>&nbsp;</td></tr>';
		echo '</table>';
		echo '</form>';
		include('pied_cad.php');
	}
}?>
