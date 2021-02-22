<?php
require_once('include/init_inc.php');

if(connect())
{
    //si l'internaute est identifié sur le site c'est à dire que l'indice 'user' est défini dans la session, alors l'internaute n'a rien à faire sur la page validation_inscription.php, on le redirige automatiquement vers sa page profil
   header('location: connexion.php');
}

require_once('include/header_inc.php');
require_once('include/nav_inc.php');
?>

<h1 class="display-1 text-center py-5">Félicitations !!!</h1>

<h3 class="text-center">Vous êtes maintenant inscrit sur notre site !!</h3>

<h4 class="text-center">Vous pouvez dés à présent vous connecter !</h4>

<p class="text-center">
<a href="connexion.php" class="btn btn-success mt-5">IDENTIFIEZ-VOUS</a>
</p>

<?php
require_once('include/footer_inc.php');