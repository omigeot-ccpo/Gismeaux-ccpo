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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
<!--
function hover(obj){
  if(document.all){
    UL = obj.getElementsByTagName('ul');
    if(UL.length > 0){
      sousMenu = UL[0].style;
      if(sousMenu .display == 'none' || sousMenu.display == ''){
        sousMenu.display = 'block';
      }else{
        sousMenu.display = 'none';
      }
    }
  }
}
function setHover(){
  LI = document.getElementById('menu').getElementsByTagName('li');
  nLI = LI.length;
  for(i=0; i < nLI; i++){
    LI[i].onmouseover = function(){
      hover(this);
    }
    LI[i].onmouseout = function(){
      hover(this);
    }
  }
}//-->
</script>
		<style type="text/css">
		<!--
#menu{
    width: 300px;
    list-style-type: none;
    margin: 0;
    padding: 0;
    border: 0;
}
#menu li {
    float: top;
    width: 150px;
    margin: 0;
    padding: 0;
    border: 0;
}
#menu li a {
    display: block;
    width: 100%;
}
#menu .sousmenu {
    display: none;
    list-style-type: none;
    margin: 0;
    padding: 0;
    border: 0;
}
#menu .sousmenu li {
    float:none;
    margin: 0;
    padding: 0;
    border: 0;
    width: 148px;
    border-top: 1px solid transparent;
    border-right: 1px solid transparent;
}
#menu li a:link , #menu li a:visited{
    display: block;
    height: auto;
    color: #FFF;
    background: #3B4E77;
    margin: 0;
    padding: 4px 8px;
    border-right: 1px solid #fff;
    text-decoration: none;
}
#menu li a:hover { background-color: #F2462E;}
#menu li a:active { background-color: #5F879D;}
#menu .sousmenu li a:link, #menu .sousmenu li a:visited {
    display: block;
    color: #FFF;
    margin: 0;
    border: 0;
    text-decoration: none;
    background-color: #56A5D3;
}
#menu li .sousmenu a:hover { background-color: #F2462E;}
#menu li:hover > .sousmenu {display: block;}
      		-->
		</style>
</head>

	<body onload="setHover()">
			<ul id="menu">
<?php require_once('./connexion/deb.php');
 $ch2="select * from general.testmenu where id_pere in (select id from general.testmenu where id_pere is null) order by libelle";
$quer2=tab_result($pgx,$ch2);
$kk=0;$t1='';
for($kk=0;$kk<count($quer2);$kk++){
//	if ($rw2['id_pere']!=''){
    if($quer2[$kk]['page']!=''){
        echo '<li ><a href="'.$quer2[$kk]['page'].'">'.htmlentities($quer2[$kk]['libelle']).'</a>';
    }else{
        echo '<li ><a href="#">'.htmlentities($quer2[$kk]['libelle']).'</a>';
    }
    $ch1="select * from general.testmenu where id_pere = '".$quer2[$kk]['id']."' and ( code_insee is null or code_insee='$code_insee' ) order by libelle";
    $quer1=tab_result($pgx,$ch1);
    if (count($quer1)>0){
        echo '<ul class="sousmenu">';
        for ($qq=0;$qq<count($quer1);$qq++){
            echo '<li ><a href="'.$quer1[$qq]['page'].'">'.htmlentities($quer1[$qq]['libelle']).'</a></li>';
        }
        echo '</ul></li>';
    }else{ echo '</li>';}
//	}
}
//echo '</ul></li>';
?>
			</ul>
	</body>
</html>
