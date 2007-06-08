<?php
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
