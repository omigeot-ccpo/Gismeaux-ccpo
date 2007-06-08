<?php
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
