$(document).ready(function()
{

     $('nav a').on('mouseover', function()
     {
          $(this).css({'color': 'black', 'font-size':'2.5vw', 'position: relative;top:5%;left:5%;':AnimationEffect });
     });

     $('nav a').on('mouseleave', function()
     {
         $(this).css({'color': 'blanchedalmond', 'font-size':'2.3vw'}); 
     });
});