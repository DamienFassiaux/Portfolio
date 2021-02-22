<?php
require_once('include/init_inc.php');

//echo '<pre>'; print_r($_SESSION); echo '</pre';


if(!connect())
{
    //si l'internaute n'est pas identifié sur le site il ne peut pas accéder à la page profil et il est redirigé vers la page connexion
   header('location: connexion.php');
}

require_once('include/header_inc.php');
require_once('include/nav_inc.php');
?>


<!-- afficher Bonjour pseudo en passant par la session de l'utilisateur connecté $_SESSION -->
<!--<h1 class="display-4 text-center">Bonjour <span class="text-success"> <?= $_SESSION['user']['pseudo'] ?> </span> !</h1> -->

<!-- exo : réaliser une page profil contenant toutes les infos persos de l'utilisateur en passant par le fichier session  -->


   <br><br>
<div class="card col-md-4 mx-auto p-0 shadow-lg mb-5">
  <img src="https://picsum.photos/300/100" class="card-img-top" alt="...">
  <div class="card-body">
  <h4 class="text-center">Vos informations personnelles</h4><hr>

<!-- La boucle foreach passe en revue les données utilisateur stockées en session à l'indice user -->
  <?php  foreach($_SESSION['user'] as $key => $value) : ?>

  <!-- La condition if exclut id_membre et statut de l'affichage -->
  <?php if($key != 'id_membre' && $key != 'statut') : ?>
     
    <p class="card-text d-flex justify-content-between">
         <strong> <?= ucfirst($key)  ?></strong>  <!-- uc first ajoute une majuscule à $key -->
        <span><?= $value ?></span></p> 

    <?php endif; ?>

    <?php endforeach; ?>
    
    <hr>
    <p class="card-text text-center ">
    <a href="" class="btn btn-secondary">MODIFIER</a>
    </p>
  </div>
</div>

<?php
require_once('include/footer_inc.php');
?>