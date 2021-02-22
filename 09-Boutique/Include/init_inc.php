<?php

 // CONNEXION BDD
 $bdd = new PDO("mysql:host=localhost;dbname=boutique", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


 // SESSION
 session_start();

 // CONSTANTES

define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/PHP/Portfolio4/09-Boutique/');
//echo RACINE_SITE . '<hr>';
//echo $_SERVER['DOCUMENT_ROOT'] . '<hr>';

// Cette constante retourne le chemin physique du dossier 09-Boutique sur le serveur loczl xampp
// Lors de l'enregistrement d'une photo, nous aurons besoin du chemin physique complet vers le dossier photo sur le serveur pour enregistrer la photo dans le bon dossier 
// On appel $_SERVER['DOCUMENT_ROOT'] parce que chaque serveur possède des chemins différents

define("URL", "http://localhost/PHP/Portfolio4/09-Boutique/");

// Cette constante servira par exemple à enregistrer l'URL d'une image en BDD. On ne conserve jamais l'image elle même en BDD
// Elle pourra permettre aussi de définir des liens absolues pour éviter des erreurs 404 en fonction du dossier ou de la page ou l'on se trouve 

// Ex :
// http://localhost/PHP/09-Boutique/photo/45A78-tee-shirt.jpg

// FAILLES XSS
foreach($_POST as $key => $value)
{
    // on execute htmlspecialchars() sur chaque valeur saisie dans les formulaires
    $_POST[$key] = htmlspecialchars(trim($value));
}
            // trim() fonction permettant de supprimer les espaces en début et fin de chaine
// INCLUSION
// On inclus directement les fonctions dans le fichier init_inc.php, ce qui évite de l'inclure à chaque fois sur toutes les pages

require_once('fonctions_inc.php');

?>