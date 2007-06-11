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
include("./connexion/deb.php");
$menu_query="select * from admin_svg.apputi inner join admin_svg.application on admin_svg.apputi.idapplication=admin_svg.application.idapplication where idutilisateur=".$_SESSION['utcleunik']." order by ordre::integer asc";
$mn=tab_result($pgx,$menu_query);
if (count($mn)>0){
	echo '<html><head><title></title>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
	//echo '<link href="https://'.$HTTP_HOST.'/cadastre/cadastre.css" rel="stylesheet" type="text/css">';
	if (file_exists('./css/head_'.$_SESSION['code_insee'].'.css')){
   echo '<link href="./css/head_'.$_SESSION['code_insee'].'.css" rel="stylesheet" type="text/css">';
}else{
   echo '<link href="./css/head_default.css" rel="stylesheet" type="text/css">';
}
 if (file_exists('./css/bout_'.$_SESSION['code_insee'].'.css')){
   echo '<link href="./css/bout_'.$_SESSION['code_insee'].'.css" rel="stylesheet" type="text/css">';
   $libouton="bouton_".$_SESSION['code_insee'];
}else{
   echo '<link href="./css/bout_default.css" rel="stylesheet" type="text/css">';
   $libouton="bouton_default";
} 
	echo "</head>";

echo '<script type = "text/javascript" language="JavaScript">';
echo 'function cli(x){';
echo '   for (y=0;y<'.count($mn).';y++){';
echo  "     w=\"a\"+y;
       if(x!=y){
             document.getElementById(y).style.backgroundImage='url(./css/".$libouton."/left.gif)';
             document.getElementById(w).style.backgroundImage='url(./css/".$libouton."/right.gif)';
             document.getElementById(w).style.paddingBottom='4px';
            document.getElementById(w).style.color='#765';
       }else{
             document.getElementById(x).style.backgroundImage='url(./css/".$libouton."/left_on.gif)';
             document.getElementById(w).style.backgroundImage='url(./css/".$libouton."/right_on.gif)';
             document.getElementById(w).style.paddingBottom='5px';
            document.getElementById(w).style.color='#333';
       }
   }
}
</script>";

       include('head.php');
	 
  	//echo '<link rel="stylesheet" type="text/css" media="screen" href="v1.css" >';
	//echo "<body>";
	echo '<div id="header">';
	echo '	<ul>';
	echo '	   <li id="0" style="BACKGROUND-IMAGE: url(./css/'.$libouton.'/left_on.gif)"><a id="a0" style="BACKGROUND-IMAGE: url(./css/'.$libouton.'/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333" href="https://'.$HTTP_HOST.'/'.$mn[0]['url'].'?'.session_name().'='.session_id().'&appli='.$mn[0]['idapplication'].'" onclick="cli(0)" target="mainFrame">'.$mn[0]['libelle_appli'].'</a></li> ';
	for ($y=1;$y<count($mn);$y++){
		    echo '  <li id="'.$y.'"><a id="a'.$y.'" href="https://'.$HTTP_HOST.'/'.$mn[$y]['url'].'?appli='.$mn[$y]['idapplication'].'&'.session_name().'='.session_id().'" onclick="cli('.$y.')" target="mainFrame">'.$mn[$y]['libelle_appli'].'</a></li>   ';
    }
    //if ( $PHP_AUTH_USER=='sig1'){
    if ($_SESSION["droit"]=='AD'){
		    echo ' <li id="'.$y.' "><a id="a'.$y.'" href="https://'.$HTTP_HOST.'/administration/utilisateur.php?'.session_name().'='.session_id().'" onclick="cli('.$y.')" target="mainFrame">Administration</a></li>   ';
    }
	echo '	</ul> ';
	echo '</div>';
	echo '</body></html>';
}
//}
?>
