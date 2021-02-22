<?php

define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/PHP/eval_damien_fassiaux/');

define("URL", "http://localhost/PHP/eval_damien_fassiaux/");

foreach($_POST as $key => $value)
{
    $_POST[$key] = htmlspecialchars(trim($value));
}

echo '<pre>' ; print_r($_POST); echo '</pre>';


$bdd = new PDO("mysql:host=localhost;dbname=immobilier", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


           // echo'<pre>'; var_dump($bdd); echo'</pre>';


               
            $titre = (isset($logement['titre'])) ? $logement['titre'] : '';
            $adresse = (isset($logement['adresse'])) ? $logement['adresse'] : '';
            $ville = (isset($logement['ville'])) ? $logement['ville'] : '';
            $cp = (isset($logement['cp'])) ? $logement['cp'] : '';
            $surface = (isset($logement['surface'])) ? $logement['surface'] : '';
            $prix = (isset($logement['prix'])) ? $logement['prix'] : '';
            $photo = (isset($logement['photo'])) ? $logement['photo'] : '';
            $type = (isset($logement['type'])) ? $logement['type'] : '';
            $description = (isset($logement['description'])) ? $logement['description'] : '';
           
           
           if(isset($_GET['action']) && $_GET['action'] =='suppression')
           {
              $supp = $bdd->prepare("DELETE FROM logement WHERE id_logement = :id_logement");
                   
                   $supp->bindValue(':id_logement', $_GET['id_logement'], PDO:: PARAM_INT);
                   $supp->execute();
           
                   $_GET['action'] = 'affichage';
           
                   $vd = "<div class='col-md-3 mx-auto alert alert-success text-center'>Le logement <strong>$_GET[id_logement]</strong> a été supprimé avec succès !</div>";
           }
           
           if($_POST)
{
    $photoBdd = "";

    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {

        $photoBdd = $photo; 
    }
  
    if(!empty($_FILES['photo']['name']))

    {    
        $info = new SplFileInfo($_FILES['photo']['name']);
        //echo '<pre>'; var_dump($info); echo'<pre>';

        // echo '<pre>'; print_r(get_class_methods($info)); echo'<pre>';

        $extFichier = $info->getExtension();
        print_r($extFichier);

        $arrayExt = ['jpg', 'png', 'jpeg'];

        $positionExt = array_search($extFichier, $arrayExt);
        // echo '<pre>'; var_dump($positionExt); echo'<pre>';

        $taille=filesize($value);


        if($positionExt === false)
        {
              $errorfile = "<small class='font-italic text-danger'>extension non autorisée (jpg, png, jpeg).</small>";  

              $error = true;
        }
        elseif($taille > 300000)
        {
             $errorfile = "<small class='font-italic text-danger'>Fichier trop volumineux!</small>"; 
             $error = true;
        }
        else
        {
            $nomPhoto = 'logement' . '_' . date_timestamp_get($_FILES['photo']);

           $photoBdd = URL . "photo/$nomPhoto";
           echo $photoBdd . '<hr>';

           $photoDossier = RACINE_SITE . "photo/$nomPhoto";
           echo $photoDossier . '<hr>';


           copy($_FILES['photo']['tmp_name'], $photoDossier);
        }
    }

           
        
            

                   if(empty($_POST['titre']))
                   {
                     $errorTitre = "<small class= 'font-italic text-danger'> Veuillez saisir le titre </small>";
                     $error = true; 
                   }
                   if(empty($_POST['adresse']))
                   {
                     $errorAdresse = "<small class= 'font-italic text-danger'> Veuillez saisir l'adresse </small>";
                     $error = true; 
                   }
                   if(empty($_POST['ville']))
                   {
                     $errorVille = "<small class= 'font-italic text-danger'> Veuillez saisir la ville </small>";
                     $error = true; 
                   }
                   if(empty($_POST['cp']))
                   {
                     $errorCp = "<small class= 'font-italic text-danger'> Veuillez saisir le code postal </small>";
                     $error = true;
                   } 
                    elseif( !is_numeric($_POST['cp']) || iconv_strlen($_POST['cp']) !=5)
                     { 
                        $errorCp = "<small class= 'font-italic text-danger'> Veuillez saisir un code postal valide </small>";
                        $error = true;
                     }

                     if(empty($_POST['surface']))
                     {
                       $errorSurface = "<small class= 'font-italic text-danger'> Veuillez saisir la surface </small>";
                       $error = true; 
                     }
                     elseif( !is_numeric($_POST['surface'])) 
                     { 
                        $errorSurface = "<small class= 'font-italic text-danger'> Veuillez saisir une surface valide </small>";
                        $error = true;
                     }
                    

                     if(empty($_POST['prix']))
                     {
                       $errorPrix = "<small class= 'font-italic text-danger'> Veuillez saisir le prix </small>";
                       $error = true; 
                     }
                     elseif( !is_numeric($_POST['prix']))
                     { 
                        $errorPrix = "<small class= 'font-italic text-danger'> Veuillez saisir un prix valide </small>";
                        $error = true;
                     }

                     if(empty($_POST['type']))
                     {
                       $errorType = "<small class= 'font-italic text-danger'> Veuillez choisir le type </small>";
                       $error = true; 
                     }

                

                     if(!isset($error))
           

           
                   {
                           $data = $bdd->prepare("INSERT INTO logement (titre, adresse, ville, cp, surface, prix, photo, type, description) VALUES (:titre, :adresse, :ville, :cp, :surface, :prix, :photo, :type, :description)");
           
                        
                           $v = "<div class='col-md-3 mx-auto alert alert-success text-center'>Le logement <strong>$_POST[titre]</strong> a été enregistré avec succès !</div>";
                   
                           
                           $data->bindValue('titre', $_POST['titre'], PDO:: PARAM_STR);
                           $data->bindValue('adresse', $_POST['adresse'], PDO:: PARAM_STR);
                           $data->bindValue('ville', $_POST['ville'], PDO:: PARAM_STR);
                           $data->bindValue('cp', $_POST['cp'], PDO:: PARAM_INT);
                           $data->bindValue('surface', $_POST['surface'], PDO:: PARAM_INT);
                           $data->bindValue('prix', $_POST['prix'], PDO:: PARAM_INT);
                           $data->bindValue('photo', $photoBdd, PDO:: PARAM_STR);
                           $data->bindValue('type', $_POST['type'], PDO:: PARAM_STR);
                           $data->bindValue('description', $_POST['description'], PDO:: PARAM_STR);
                           $data->execute();
                   }
            
                 }
           
           

?>

<!DOCTYPE html>
<html lang='fr'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">

         <!--Cdn Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <title>EVAL - PHP</title>

    
    </head>
    <body>
      <main>
      <form method="post" action="tableau_logement.php"  class="col-md-7 mx-auto mb-5" enctype="multipart/form-data">
               <div class= "form-row">
                 <div class="form-group col-md-6">
                    <label for="titre">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" placeholder="titre" value="<?= $titre ?>">
                    <?php if(isset($errorTitre)) echo $errorTitre; ?>
                 </div>
                 <div class="form-group col-md-6">
                    <label for="adresse">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="adresse" value="<?= $adresse ?>">
                    <?php if(isset($errorAdresse)) echo $errorAdresse; ?>
                 </div>
                 </div>
                 <div class= "form-row">
                 <div class="form-group col-md-6">
                 
                      <label for="ville">Ville</label>
                      <input type="text" class="form-control" id="ville" name="ville" placeholder="ville" value="<?= $ville ?>"> 
                      <?php if(isset($errorVille)) echo $errorVille; ?>
                </div>
                 </div>
                <div class="form-group col-md-6">
                      <label for="cp">Code Postal</label>
                      <input type="text" class="form-control" id="cp" name="cp" placeholder="cp" value="<?= $cp ?>"> 
                      <?php if(isset($errorCp)) echo $errorCp; ?>
                </div>
                <div class="form-row">
                <div class=" form-group col-md-6">
                      <label for="surface">Surface</label>
                      <input type="text" class="form-control" id="surface" name="surface" placeholder="surface" value="<?= $surface ?>"> 
                      <?php if(isset($errorSurface)) echo $errorSurface; ?>
                      </div>
                <div class="form-group col-md-6">
                      <label for="prix"> Prix</label>
                      <input type="text" class="form-control" id="prix" name="prix" placeholder="prix" value="<?= $prix ?>"> 
                      <?php if(isset($errorPrix)) echo $errorPrix; ?>
                </div>
                </div>
    
                  <div class="form-group col-md-6">
                      <label for="photo"> Photo </label> 
                      <input type="file" class="form-control" id="photo" name="photo">
                      <?php if(isset($errorfile)) echo $errorfile;?> 
                    
                  </div>

                <?php if(!empty($photo)): ?>
                    <p class="text-center">
                        <em>Vous pouvez uploader une photo si vous souhaitez la changer</em><br>
                        <img src="<?= $photo ?>" alt=" <?=$titre ?>" style="width:150px;" >
                    </p>
                <?php endif ?>

                <div class="form-group col-md-6">
                      <label for="type">Type</label>
                      <select class="form-control" name="type" id="type" value="<?= $type ?>">
                      <option value="location" <?php if($type == 'location') echo 'selected'; ?> >Location</option>
                      <option value="vente" <?php if($type == 'vente') echo 'selected'; ?> >Vente</option>
                      </select>
                      <?php if(isset($errorType)) echo $errorType; ?>
                </div>

                   <div class="form-group col-md-6">
                      <label for="description">Description</label>
                      <textarea class="form-control" id="description" name="description" rows="5"><?= $description ?></textarea>
                  </div>
                </div>
                      <button type='submit' class='btn btn-info'>Créer Logement</button>
               </form>
      </main>
        
    </body>
</html>