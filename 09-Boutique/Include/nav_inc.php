<nav class="navbar navbar-expand-md navbar-dark bg-info">
  <a class="navbar-brand" href="#">Ma Boutique !!!</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExample04">
    <ul class="navbar-nav mr-auto">

          <!-- On entre ds la condition if si l'internaute est identifié sur le site l'indice user est bien défini la fonction connect() retourne TRUE -->
          <?php if(connect()):  ?>


                 <li class="nav-item active">
                    <a class="nav-link" href="<?=URL ?>profil.php">Mon compte</a>
                 </li>
      

          <?php else: // Liens visiteurs non identifié sur le site ?>

                 
            <li class="nav-item active">
                    <a class="nav-link" href="<?= URL ?>inscription.php">Créer votre compte</a>
                 </li>
                 <li class="nav-item active">
                   <a class="nav-link" href="<?= URL ?>connexion.php">Identifiez-vous</a>
                 </li>
            

          <?php endif; ?>

          <!-- Ces deux liens sont accessibles que l'internaute soit identifié ou pas -->
          <li class="nav-item active">
                    <a class="nav-link" href="<?= URL ?>boutique.php">Accès à la boutique</a>
                 </li>
                 <li class="nav-item active">
                   <a class="nav-link" href="<?= URL ?>panier.php">Votre panier</a>
                 </li>

            <?php if(connect()): ?>

              <li class="nav-item active">
                   <a class="nav-link" href="<?= URL ?>connexion.php?action=deconnexion">Déconnexion</a>
                 </li>

            <?php endif;  ?>

            <!-- Si l'internaute est identifié sur le site et qu'il a le statut admin on entre dans la condition if et le menu backoffice apparait dans la nav-->
            <?php if(adminConnect()): ?>

      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BackOffice</a>
         <div class="dropdown-menu" aria-labelledby="dropdown04">
                <a class="dropdown-item" href="<?=URL ?>admin/gestion_boutique.php">Gestion de la Boutique</a>
                <a class="dropdown-item" href="#">Gestion des utilisateurs</a>
                <a class="dropdown-item" href="#">Gestion des commandes</a>
        </div>
      </li>


              <?php endif;  ?>

    </ul>
    <?php  
    if(isset($_SESSION['user']))
    { 
      echo "<strong class=' badge badge-dark text-white p-2'>Bonjour " . $_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom'] . "</strong>";
    }
    ?>
    <form class="form-inline my-2 my-md-0 ml-2">
        <input class="form-control" type="text" placeholder="Rechercher">
    </form>
  </div>
</nav>
</header>
<main style="min-height :90vh">