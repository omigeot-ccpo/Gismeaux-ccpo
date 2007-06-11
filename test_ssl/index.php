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
session_start();
if (! $PHP_AUTH_USER || ! $PHP_AUTH_PW){
	header("WWW-authenticate: Basic realm=\"Veuillez vous identifier\"");
	header('HTTP/1.0 401 Unauthorized');
	header('status: 401 Unauthorized');
}else{
	include("./connexion/deb.php");
	$oten="select * from admin_svg.utilisateur where login='".$PHP_AUTH_USER."' and psw='".$PHP_AUTH_PW."'";
	$ot=tab_result($pgx,$oten);
	if (count($ot)>0) {
		$_SESSION['identifiant']=$ot[0]['login'];
		$_SESSION["code_insee"]=$ot[0]['idcommune']; 
		$_SESSION["utcleunik"]=$ot[0]['idutilisateur'];
		$_SESSION["droit"]=$ot[0]['droit'];
		$menu_query="select * from admin_svg.apputi inner join admin_svg.application on admin_svg.apputi.idapplication=admin_svg.application.idapplication where idutilisateur=".$ot[0]['idutilisateur']." and ordre=1";
		$mn=tab_result($pgx,$menu_query);
		echo '<frameset rows="130,*" cols="*" frameborder="NO" border="0" framespacing="0">';
		echo '  <frame src="menu.php" name="topFrame" scrolling="NO" noresize>';
		print '    <frame src="https://'.$HTTP_HOST.'/'.$mn[0]['url'].'?appli='.$mn[0]['idapplication'].'&'.session_name().'='.session_id().'" name="mainFrame">';
		echo '</frameset>';
		echo '<noframes><body>';
		print('Bonjour, <strong>'.strtok(gethostbyaddr($_SERVER['REMOTE_ADDR']),'.')."</strong> cette machine n'est pas accessible\n\r");
		echo '</body></noframes>';
	} else{
	header("WWW-authenticate: Basic realm=\"Veuillez vous identifier\"");
	header('HTTP/1.0 401 Unauthorized');
	}
}?>
