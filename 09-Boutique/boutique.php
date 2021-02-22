<?php
require_once('include/init_inc.php');



if(isset($_GET['categorie']) && !empty($_GET['categorie']))
{
   //echo "tout est ok dans $_GET";

   //selection des produits en fonction de la catégorie transmise dans l'url
     $result = $bdd->prepare("SELECT * FROM produit WHERE categorie = :categorie");
     $result->bindValue(':categorie', $_GET['categorie'], PDO::PARAM_STR);
     $result->execute();

     //si la requete de selection ne retourne pas de résultats, que rowCount() retourne FALSE, cela veut dire que la catégorie dans l'URL n'existe pas en BDD alors on redirige l'internaute vers la boutique
     if(!$result->rowCount())
     {
         //echo 'categorie inexistante en BDD';
         header('location: boutique.php');
     }
}
else
{
    //echo 'categorie pas dans l url on selectionne tous les produits';
    $result = $bdd->query("SELECT * FROM produit");
}


require_once('include/header_inc.php');
require_once('include/nav_inc.php');



?>

 <!-- Page Content -->
 <div class="container">

   <div class="row">

      <div class="col-lg-3">

            <h1 class="my-4">Que du lourd!!! Viendez-voir!!</h1>

            <?php
              $data = $bdd->query("SELECT DISTINCT(categorie) FROM produit")
              ?>
              <div class="list-group">
                  <p class="list-group-item p-0 m-0 bg-info text-center text-white">CATEGORIES</p>
                  <?php while($cat = $data->fetch(PDO::FETCH_ASSOC)):

                  //echo '<pre>'; print_r($cat); echo'</pre>';
                  ?>

                    <a href="?categorie=<?= $cat['categorie'] ?>" class="list-group-item text-center text-dark"><?= $cat['categorie'] ?></a>
                  
                   <?php endwhile; ?>
              </div>

     </div>
     <!-- /.col-lg-3 -->

        <div class="col-lg-9">

            <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
                    <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <img class="d-block img-fluid" src="photo/2474069-middle.png" alt="First slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block img-fluid" src="photo/All_Black_Haka51.jpg" alt="Second slide" width="900px" max-height="350px">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block img-fluid" src="photo/juventus_stadium_by_pirp_d4colkd-fullview.jpg" alt="Third slide">
                            </div>
                    </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
            </div>

            <div class="row">
                              <!--1 ARRAY par tour de boucle contenant 1 produit d'une catégorie -->
                  <?php while($products = $result->fetch(PDO::FETCH_ASSOC)):
                         // echo '<pre>'; print_r($products); echo '</pre>'; 
                    ?>

                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100">
                                                <a href="fiche_produit.php?id_produit=<?= $products['id_produit']?>"><img class="card-img-top" src="<?= $products['photo']?>" alt="<?= $products['titre']?>"></a>
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                <a href="fiche_produit.php?id_produit=<?= $products['id_produit']?>"><?= $products['titre']?></a>
                                            </h4>
                                            <h5><?= $products['prix']?>€</h5>

                                            <?php //découpe de la description trop longue
                                                  // si la taille de la description est supérieur à 30 caractères alors on coupe une partie de la chaine grace à la fonction substr()
                                             if(strlen($products['description']) >30)
                                                $description = substr($products['description'], 0, 30) . '...';
                                             else  //sinon la description est inférieure à 30 caractères on l'affiche normalement
                                                $description = $products['description'];

                                            ?>
                                            <p class="card-text"><?= $description?></p>
                                        </div>
                                        <div class="card-footer">
                                            <a href="fiche_produit.php?id_produit=<?= $products['id_produit']?>" class="btn btn-secondary">En savoir plus</a>
                                        </div>
                                </div>
                            </div>

                    <?php endwhile ?>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.col-lg-9 -->

        </div>
        <!-- /.row -->

</div>
<!-- /.container -->

<?php
require_once('include/footer_inc.php');