<?php
/*Copyright Pays de l'Ourcq 2008
contributeur: jean-luc Dechamp - robert Leguay - olivier Migeot
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
echo "<html><head>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf8\">";
echo "<title>Erreur SIG</title>";
echo "</head><body>";

if ($_GET['code'])
  {
    switch($_GET['code'])
      {
      case 1:
	$err = "Problème de 'previous'";
	break;
      case 2:
	$err = "Erreur dans le processus d'authentification. Vérifiez votre mot de passe.";
	break;
      default:
	$err = "Erreur inconnue";
      }
    echo "<h1>Une erreur est survenue</h1><p>".$err."</p><p style=\"font-size:0.8em;\">L'erreur a le code ".$_GET['code']."</p>";
  }
 else
   echo "<h1>Une erreur est survenue</h1><p>L'erreur n'a pas de code.</p>";

echo "</body></html>";

?>