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
//$config_serveur_smtp="smtp.mydomain" ;
$config_serveur_smtp="192.168.200.2" ;
// Si vous ne maitriser pas le serveur SMTP ou si à�l'utilisation, vous avez une erreur de connexion au serveur, 
//  laisser cette variable vide ("")

/**************************************************************************************************************/
/**************************************************************************************************************/
//  ENVOI DE MAIL 
//adresse administrateur
$adresse_admin="sig@meaux.fr;sig1@meaux.fr";
// sujet du message :
$config_mail_sujet= "- Problème interface -";
// corps du message : 
$config_mail_contenu = " a rencontré un problème avec l'interface cartographique.

Merci de consulter la base d'administration

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

?>
