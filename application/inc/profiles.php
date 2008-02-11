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

if (!defined('GIS_ROOT'))
  {
    die('Interdit. Forbidden. Verboten.');
  }
include_once(GIS_ROOT . '/inc/common.php');


// PROFIL DE BASE (CLASSE PARENTE)

class Profile {
  var $name; // Probablement sans objet, mais parfois utile pour débugguer.
  var $insee; // Code INSEE par défaut -> les permissions devraient être positionnées en concordance.
  var $appli; // Application chargée par défaut -> même remarque que ci-dessus
  var $roles;
  var $rolequery;
  
  function __construct($name)
  {
    $this->name = $name;
    $this->insee = 770126;
    $this->appli = 2;
    $this->roles = array();
    $this->rolequery = "";
  }

  function matches() // Permet de vérifier si un profil s'applique au cas présent
  {
    return 0;
  }

  function checkMatch()
  {
    if ($this->matches())
      $_SESSION['profil'] = $this;
  }

  function is_identified()
  {
    // Retourne 1 si le nom de l'utilisateur est connu - par quelque moyen que ce soit - et 0 sinon.
    // Peut obtenir le nom de l'utilisateur lui-même ou faire appel à une fonction externe (TODO).
    // Dans tous les cas, on voudra mettre cette valeur en cache "au cas ou".
    die("ERROR method Profile::is_identified() is missing");
    return 0;
  }

  function is_authentified()
  {
    // Retourne 1 si l'utilisateur "prétendu" a été validé.
    // Peut pratiquer l'authentification lui-même à la volée, ou faire appel à un "authentificateur" externe (TODO)
    die("ERROR method Profile::is_authentified() is missing");
    return 0;
  }

  function fetch_roles()
  {
    global $DB;

    $this->roles = array();
    $query = "SELECT idrole FROM admin_svg.roleuti WHERE idutilisateur = '".$this->getUserName()."'";
    $t = $DB->tab_result($query);
    for ($i=0;$i<count($t);$i++)
      {
	$role = $t[$i]['idrole'];
	$this->roles[] = $role;
	if ($i > 0)
	  $this->rolequery .= " OR ";
	$this->rolequery .= "idrole = " . $role;
      }
  }

  function getUserName()
  {
    die("ERROR method Profile::getUserName() is missing");
    return "No user";
  }

  function info($value="")
  {
    static $val;
    $tmp = $val;
    $val = $value;
    return $tmp;
  }

  function ok($insee="",$appli=0,$action="")
  {
    die("ERROR method Profile::ok() is missing");
    return 0;
  }

}

// FIN DU PROFIL DE BASE

class InternetProfile extends Profile {
  var $guestname;
 
  function __construct($guest)
  {
    parent::__construct("internet");
    $this->guestname = $guest;
  }

  function is_identified()
  {
    return 1;
  }
  
  function is_authentified()
  {
    return 1;
  }

  function getUserName()
  {
    return $this->guestname;
  }

  function ok()
  {
    return 1;
  }

  function matches()
  {
    return 1; // Works by default
  }
}

/////////////////////////////////////////////

class CertifiedProfile extends Profile {
  var $username;
  var $ssl;

  function __construct()
  {
    parent::__construct("certified");
  }

  function getUserName()
  {
    return $this->username;
  }

  function is_identified()
  {
    if ($_SERVER["SSL_CLIENT_CERT"])
     {
	$this->ssl = openssl_x509_parse($_SERVER["SSL_CLIENT_CERT"]);
	if ($this->ssl)
	  {
	    $this->username = $this->ssl['subject']['CN'];
	    $this->fetch_roles();
	    return 1;
	  }
      }
    return 0;
  }

  function is_authentified()
  {
    global $DB;

    $org = str_replace("'","\\'",$this->ssl['subject']['O']);
    $orgunit = str_replace("'","\\'",$this->ssl['subject']['OU']);
    $query = "SELECT appli, insee FROM admin_svg.profils WHERE org = '".$org."' AND (orgunit = '".$orgunit."' OR orgunit IS NULL) ORDER BY orgunit";
    $t = $DB->tab_result($query);
    if (count($t) == 0)
      {
	$this->insee = "770000"; // FIXME param
	$this->appli = 2; // FIXME param
      }
    else
      {
	$this->insee = $t[0]['insee'];
	$this->appli = $t[0]['appli'];
      }
    return 1;
  }
  
  function ok($insee="",$appli=0,$action="")
  {
    return 1;
  }

  function matches()
  {
    if ($_SERVER)
      if ($_SERVER['HTTPS'])
	if ($_SERVER['SSL_CLIENT_CERT'])
	  {
	    $cainfo = array();
	    $cainfo[] = '/etc/apache2/ssl/cacert.pem'; // FIXME param
	    $this->ssl = openssl_x509_parse($_SERVER["SSL_CLIENT_CERT"]);
	    if (openssl_x509_checkpurpose($SERVER["SSL_CLIENT_CERT"],X509_PURPOSE_SSL_CLIENT,$cainfo))
	      return 1;
	  }
    return 0;
  }
}

//////////////////////////////////////////////////////////////////

class TestingProfile extends Profile {
  function __construct()
  {
    parent::__construct("testing");
  }

  function getUserName()
  {
    return "OM";
  }

  function is_identified()
  {
    return 1;
  }

  function is_authentified()
  {
    return 1;
  }

  function ok($insee="",$appli=0,$action="")
  {
    return 1;
  }

  function matches()
  {
    if ($_SERVER)
      if ($_SERVER['REMOTE_ADDR'] == "192.168.1.82")
	return 1;
    return 0;
  }

}

///////////////////////////////////////////

class IntranetProfile extends Profile {
  var $user;
  var $pass;

  function __construct()
  {
    parent::__construct("ldap");
  }

  function is_identified()
  {
    if (!$this->user)
      {
	$this->user = $_POST['form_user'];
	$this->pass = $_POST['form_pass'];
	
	if ($this->user) // Cette partie correspond à auth()
	  {
	    return 1;
	  }
	else // Cette partie correspond à ident() (du moins à sa première phase)
	  {
	    echo "<html>";
	    echo "<head>";
	    echo "<title>GISMeaux :: Connexion</title>";
	    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf8\">";
	    echo "</head>";
	    echo "<body>";
	    echo "<p><form action=\"auth.php\" method=\"post\">";
	    echo "Login: <input type=\"text\" name=\"form_user\" value=\"\" />";
	    echo "Password: <input type=\"password\" name=\"form_pass\" value=\"\" />";
	    echo "<input type=\"submit\" value=\"Connexion\" />";
	    echo "</form>";
	    echo "</p>";
	    echo "</body>";
	    echo "</html>";
	    die();
	  }
      }
  }
  
  function getUserName()
  {
    return $this->user;
  }

  function ok($insee="",$appli=0,$action="")
  {
    return 1;
  }

  function is_authentified()
  {
    $ds=ldap_connect("mercure"); // FIXME param (host)
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    
    if ($ds)
      {
	$res = @ldap_bind($ds,'uid='.$this->user.',ou=users,dc=paysdelourcq,dc=fr',$this->pass); // FIXME param (UID et DN)
	if ($res)
	  return 1;
      }
    return 0;
  }
  
  function matches()
  {
    if ($_SERVER)
      if ($_SERVER['REMOTE_ADDR'] == '192.168.1.81') // FIXME param (dev ip)
	//	if ($_SERVER['HTTPS'])
	return 1;
    return 0;

  }

}


?>