<?php
require_once('include/init_inc.php');
require_once('include/header_inc.php');
require_once('include/nav_inc.php');

//echo '<pre>'; print_r($_POST); echo '</pre>';

if(connect())
{
    //si l'internaute est déjà identifié sur le site il est redirigé directement vers la page votre profil
   header('location: profil.php');
}

if($_POST)
{
     // classe affecté au champ input avec bordure rouge en cas d'erreur utilisateur
     $border = 'border border-danger';

     $verifPseudo = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
     $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
     $verifPseudo->execute();

     //echo "nombre de pseudo existant :" . $verifPseudo->rowCount().'<hr>';


  if(empty($_POST['pseudo']))
  {
    $errorPseudo = "<small class= 'font-italic text-danger'> Veuillez saisir un pseudo </small>";
    $error = true; 
  }
  elseif($verifPseudo->rowCount())
  {
     // Si la requete de selection retourne un résultat supérieur à o, cela veut dire que le pseudo saisi est existant en BDD
     $errorPseudo = '<small class="font-italic text-danger">Ce pseudo existe déjà! Merci d\'en saisir un nouveau!</small>';
     $error = true; 
  }
  elseif(iconv_strlen($_POST['pseudo'])<2 OR iconv_strlen($_POST['pseudo'])>20)
  {
      $errorPseudo = "<small class= 'font-italic text-danger'> Veuillez saisir un pseudo compris entre 2 et 20 caractères </small>"; 
      $error = true;
  }


  if(empty($_POST['nom']))
  {
    $errorNom = "<small class= 'font-italic text-danger'> Veuillez saisir votre nom </small>";
    $error = true; 
  }
  if(empty($_POST['prenom']))
  {
    $errorPrenom = "<small class= 'font-italic text-danger'> Veuillez saisir votre prenom </small>";
    $error = true; 
  }
  // Expression régulière (REGEX)
  /*
  preg_match() : fonction prédéfinie permettant de définir une expression régulière 
  Une expression régulière (REGEX) est tjs entouré de # afin de préciser les options :
  ^ indique le début de la chaine
  $ indique la fin de la chaine
  + est la pour indiquer que les caractères peuvent etre utilisés plusieurs fois
  {2,20} permet d'indiquer la taille de la chaine
  [a-zA-Zéèàê-] indique les caractères autorisés dans la chaîne de caractères
  */
  elseif(!preg_match('#^[a-zA-Zéèàê-]{2,20}+$#', $_POST['prenom']))
  {
    $errorPrenom = "<small class= 'font-italic text-danger'> Votre prénom contient des caractères non autorisés! </small>";
    $error = true;
  }

  if(empty($_POST['ville']))
  {
    $errorVille = "<small class= 'font-italic text-danger'> Veuillez saisir votre ville de résidence </small>";
    $error = true; 
  }

  if(empty($_POST['adresse']))
  {
    $errorAdresse = "<small class= 'font-italic text-danger'> Veuillez saisir votre adresse </small>";
    $error = true; 
  }

  if(empty($_POST['mdp']))
  {
    $errorMdp = "<small class= 'font-italic text-danger'> Veuillez saisir votre mot de passe </small>";
    $error = true; 
  }

  if($_POST['mdp'] != $_POST['confirm_password'])
  {
      $errorPassword = "<small class= 'font-italic text-danger'> Veuillez vérifier le mot de passe </small>";
      $error = true;
  }

  if( !is_numeric($_POST['code_postal']) || iconv_strlen($_POST['code_postal']) !=5)
  { 
          $errorCode_postal = "<small class= 'font-italic text-danger'> Veuillez saisir un code postal valide </small>";
          $error = true;
  }

  $verifEmail = $bdd->prepare("SELECT * FROM membre WHERE email = :email");
  $verifEmail->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $verifEmail->execute();

  if(empty($_POST['email']))
  {
    $errorEmail = "<small class= 'font-italic text-danger'> Veuillez saisir un email </small>";
    $error = true; 
  }
  elseif($verifEmail->rowCount())
  {
     $errorEmail = '<small class="font-italic text-danger">Cet email est associé à un compte déjà existant!</small>';
     $error = true; 
  }
  elseif(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))

  {
      $errorEmail = "<small class= 'font-italic text-danger'> Veuillez saisir une adresse mail valide </small>";
      $error = true;
  }

  if (!isset($error))
  {

    // Hachage du mot de passe
    //Les mots de passe en BDD ne sont jamais conservé en claire, nous devons créer une clé de hachage
    //password_hash(): fonction prédéfinie permettant de créer une clé de hachage à partir d'un algorhytme (PASSWORD_BCRYPT)
    // A la connexion pour comparer la clé de hachage nous executerons la fonction password_verify()
    $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_BCRYPT);

    $insertUser = $bdd->prepare("INSERT INTO membre (pseudo, email, mdp, civilite, adresse, code_postal, nom, prenom, ville) VALUES(:pseudo, :email, :mdp, :civilite, :adresse, :code_postal, :nom, :prenom, :ville)");

    //echo '<pre>'; var_dump($insert); echo '</pre';
 
    $insertUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $insertUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $insertUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
    $insertUser->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
    $insertUser->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
    $insertUser->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
    $insertUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
    $insertUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
    $insertUser->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
 
    $insertUser->execute();

    //On redirige après la validation de l'inscription vers le fichier validation_inscription.php
    header('location: validation_inscription.php');
      }
}

?>

<!--
     Nous sommes dans la balise main

     exo : 
     1 créer un formulaire HTML correspondant à la table 'membre' sauf id_membre et statut
     2 controler en PHP que l'on receptionne bien toute les données saisie dans le formulaire (print_r)
     3 controler la dispo du pseudo (unique en BDD)
     4 controler la dispo de l'email (unique en BDD)
     5 faire en sorte d'informer l'internaute si le champ email n'est pas du bon format et si le champ est laissé vide
     6 Faire en sorte d'informer l'internaute si le champ pseudo est laissé vide
     7 faire en sorte d'informer l'internaute si les mots de passe ne correspondent pas   
 -->
 <br> <br>
 <form method="post" class="col-md-6 mx-auto">
               <div class= "form-row">
                 <div class="form-group col-md-6">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control <?php if (isset($errorPseudo)) echo $border; ?>" id="pseudo" name="pseudo" placeholder="Pseudo"><?php if(isset($_POST['pseudo'])) echo $_POST['pseudo']?>
                    
                    <?php if(isset($errorPseudo)) echo $errorPseudo; 
                  ?>
                 </div>
                 <div class="form-group col-md-6">
                    <label for="email">Adresse mail</label>
                    <input type="text" class="form-control <?php if (isset($errorEmail)) echo $border; ?>" id="email" name="email" placeholder="Adresse email">
                    <?PHP if(isset($errorEmail)) echo $errorEmail; ?>
                 </div>
                 </div>
                 <div class= "form-row">
                 <div class="form-group col-md-6">
                 
                      <label for="mdp">Mot de passe</label>
                      <input type="password" class="form-control <?php if (isset($errorMdp)) echo $border; ?>" id="mdp" name="mdp" placeholder="mot de passe"> <?PHP if(isset($errorMdp)) echo $errorMdp; ?>
                </div>
        
                <div class="form-group col-md-6">
                      <label for="confirm_password"> confirmer votre mot de passe</label>
                      <input type="password" class="form-control <?php if (isset($errorPassword)) echo $border; ?>" id="confirm_password" name="confirm_password" placeholder="confirmer votre mot de passe">
                      <?PHP if(isset($errorPassword)) echo $errorPassword; ?>
                </div>
                </div>
                <div class="form-row">
                <div class=" form-group col-md-6">
                <label for="civilite">Civilité</label>
                      <select class="form-control" name="civilite" id="civilite">
                      <option value="femme">Madame</option>
                      <option value="homme">Monsieur</option>
                      </select>
                      </div>
                      </div>
                <div class= "form-row">
                
                <div class="form-group col-md-6">
                      <label for="nom">    Nom</label>
                      <input type="text" class="form-control <?php if (isset($errorNom)) echo $border; ?>" id="nom" name="nom" placeholder="nom">
                      <?PHP if(isset($errorNom)) echo $errorNom; ?>
                </div>
                <div class="form-group col-md-6">
                      <label for="prenom"> Prenom</label>
                      <input type="text" class="form-control <?php if (isset($errorPrenom)) echo $border;?>" id="prenom" name="prenom" placeholder="prenom">
                      <?PHP if(isset($errorPrenom)) echo $errorPrenom;?>
                </div>
                </div>
                <div class= "form-row">
                <div class="form-group col-md-12">
                      <label for="adresse"> Adresse</label>
                      <input type="text" class="form-control <?php if (isset($errorAdresse)) echo $border; ?>" id="adresse" name="adresse" placeholder="adresse">
                      <?PHP if(isset($errorAdresse)) echo $errorAdresse; ?>
                </div>
                </div>
                <div class= "form-row">
                <div class="form-group col-md-6">
                      <label for="ville"> Ville</label>
                      <input type="text" class="form-control <?php if (isset($errorVille)) echo $border; ?>" id="ville" name="ville" placeholder="ville">
                      <?PHP if(isset($errorVille)) echo $errorVille; ?>
                </div>
                <div class="form-group col-md-6">
                      <label for="code_postal"> Code postal</label>
                      <input type="text" class="form-control <?php if (isset($errorCode_postal)) echo $border; ?>" id="code_postal" name="code_postal" placeholder="code postal">
                      <?php if (isset ($errorCode_postal)) echo $errorCode_postal;  ?>
                </div>
                </div>
                <button type="submit" class="btn btn-info">Valider votre compte</button>
                
            </form>

<?php
require_once('include/footer_inc.php');
