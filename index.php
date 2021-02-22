<!DOCTYPE html>

<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-COMPATIBLE" content="ie=edge">
    <meta name="description" content="Portfolio">
    <meta name="author" content="Damien Fassiaux">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <title>Portfolio Damien Fassiaux</title>
  </head>
    <body>
      <div class="conteneur">
       <script src="https://code.jquery.com/jquery-3.5.1.min.js"
       integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
       crossorigin="anonymous"></script>
       
           <script src="Portfolio.js"></script>

     <header>
        <nav class="navbar navbar-expand-md">
          <div class="collapse navbar-collapse">
            <ul class="navbar-nav ">
                  <li class="nav-item active" id="cache">
                          <a class="nav-link" href=""> caché </a>
                  </li>
                   <li class="nav-item active" id="cache2">
                           <a class="nav-link" href=""> caché </a>
                   </li>
                    <li class="nav-item active">
                            <a class="nav-link" href="#lienPropos">A propos</a>
                    </li>
                    <li class="nav-item active">
                            <a class="nav-link" href="#lienParcours">Mon parcours</a>
                    </li>
                    <li class="nav-item active">
                          <a class="nav-link" href="#lienProjets">Mes projets</a>
                    </li>
                    <li class="nav-item active">
                            <a class="nav-link" href="#lienContact">Contact</a>
                    </li>
            </ul>
          </div>
        </nav>
                    <span><img src="IMG_20210128_184241.jpg" alt="" width="200px" height="300px"></span>
     </header>

        <section id="lienPropos" class="propos text-center py-5">
          <h1 class="text-center">Damien Fassiaux</h1>
          <h2 class="py-5">Développeur Web full-stack / back-end </h2>
         <article>
             <p>
             Actuellement en reconversion professionnelle dans le monde de la programmation Web<br> après un parcours de quinze ans dans l'hotellerie-restauration. <br><br><br> L'expérience m’a montré qu'avec du travail et de la persévérance tout est possible.<br>Je souhaite aujourd'hui exercer un métier qui me passionne et profiter pleinement de ma vie professionnelle.
             </p>
         </article>
       </section>
       <section class="parcours text-center" id="lienParcours">
        <h1>Mon Parcours</h1>
         <div class="lienParcours">
             <article class="formation">
                 <h3><strong>Formations</strong></h3>
                 <p><strong>Depuis Décembre 2020</strong> Préparation du titre professionnel<br> Développeur Web et web mobile Ecole WebForce3 Trappes<br><br>
                 <strong> 2005</strong> Bac Hôtellerie- restauration CFA Méderic Paris 17<br><br>
                <strong>2003</strong> Niveau Deug Histoire Panthéon La sorbonne Paris 1<br><br>
              <strong>2001</strong> Bac Littéraire Lycée Descartes Antony 92<br>
                  </p></article>
             <article class="experience">
                 <h3><strong>Expériences</strong></h3>
                <p><strong> Septembre 2019-Avril 2020 </strong>Responsable Restauration Hilton Garden Inn Massy<br><br>
                  <strong>Octobre 2017-Septembre 2019</strong> Responsable Economat Mandarin Oriental Paris<br><br>
                  <strong>Décembre 2014-Octobre 2017</strong> Econome Fouquet's Barrière Paris<br><br>
                  <strong> Juin 2013-Décembre 2014</strong> Responsable Restaurant L'atelier Paris 09<br><br>
                  <strong> 2005-2013</strong> Evolution de commis à Maître d'hôtel Hilton La Défense<br>
                </p></article>
         </div>
     </section>
     <div class="projets text-center" id="lienProjets"> 
     <section id="lienProjets2">
         <h1>Mes Projets</h1>
         <div class="lienProjets"> 
             <a href="09-Boutique/boutique.php"><img src="en-construction.jpg" alt="" width="200px" height="200px"></a>
             <a href=""><img src="en-construction.jpg" alt="" width="200px" height="200px"></a>
             <a href=""><img src="en-construction.jpg" alt="" width="200px" height="200px"></a>
            </div>
            <div class="lienProjets2">
            <p>Site eCommerce</p>
            <p>Site Wordpress</p>
            <p>Page from scratch</p>
            </div>
        </section>
     </div>
     <section class="contact text-center">
         <h1>Contact</h1>
         <div id="lienContact">
            <a href="https://www.linkedin.com/in/damien-fassiaux-dams78devweb/"><img src="linkedin (1).png" class="linkedin" alt="" width="100px" height="100px"></a>
            <a href="mailto:damienfassiaux@gmail.com"><img src="53711.png" class="mail" alt="" width="100px" height="100px"></a>
            <a href="tel:+3362116315"><img src="65680.png" class="phone" alt="" width="100px" height="100px"></a>
            <a href="CV_Damien_Fassiaux.pdf"><img src="resume.png" download="CV_Damien_Fassiaux" class="cv" alt="" width="100px" height="100px"></a>
         </div>
         <div id="lienContact2">
         <a href="https://www.linkedin.com/in/damien-fassiaux-dams78devweb/"><p>Linkedin</p></a>
         <a href="mailto:damienfassiaux@gmail.com"><p>damienfassiaux@gmail.com</p></a>
         <a href="tel:+3362116315"><p>06 62 11 63 15</p></a>
         <a href="CV_Damien_Fassiaux.pdf"><p>Affichez mon CV</p></a>
         </div>
     </section>

        <footer class="display-4 text-center">
           &copy; 2021 - DFDevWeb - Portfolio
        </footer>
   </div>
    </body>
</html>    