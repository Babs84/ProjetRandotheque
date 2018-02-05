<?php

namespace Randotheque\lib;

class security {

	private static $seed = '7035244053';

	static public function getSeed() {
		return self::$seed;
	}

	//Création de mot de passe chiffrer seed+hash
	static function chiffrer($texte_en_clair) {
		$txt= security::getSeed();
		$txt2= $txt . $texte_en_clair;
		$texte_chiffre = hash('sha256', $txt2);
		return $texte_chiffre;
	}

	static function generateRandomHex() {
	  // Generate a 32 digits hexadecimal number
	  $numbytes = 16; // Because 32 digits hexadecimal = 16 bytes
	  $bytes = openssl_random_pseudo_bytes($numbytes); 
	  $hex   = bin2hex($bytes);
	  return $hex;
	}
}
?>
