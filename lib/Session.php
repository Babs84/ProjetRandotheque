<?php

namespace Randotheque\lib;

class Session {
    public static function is_user($login) {
        return (!empty($_SESSION['id']) && ($_SESSION['id'] == $login));
    }
    //Permet de vérifier si l'utilisateur est Administrateur
    public static function is_admin() {
    	return (!empty($_SESSION['admin']) && $_SESSION['admin']);
	}
}
?>