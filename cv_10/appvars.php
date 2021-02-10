<!-- ***************************************************************************** -->
<!-- PHP  code app variables included in to a script - bazaar                      -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 26.9.2020 by CDesigner.eu                            -->
<!-- ***************************************************************************** -->

<!-- ***************************************************************************** -->
<!--    Part I   |                  database access variables                      -->
<!-- ***************************************************************************** -->

<?php
  define('DB_HOST', 'localhost');
  define('DB_USER', 'admin');
  define('DB_PW', 'test*555');
  define('DB_NAME', 'test');
 /* this part defines global variables that can be changed from single location
    in all others files is these content included by require_once(); function */
 // mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
?>

<!-- ***************************************************************************** -->
<!--    Part II   |                  image location path                           -->
<!-- ***************************************************************************** -->

<?php
  define('IMAGE_PATH', 'images/');
  
 /* location where images are transfered afte succesfull submit */
 
?>

<!-- ***************************************************************************** -->
<!--    Part III   |                  authorization constants                      -->
<!-- ***************************************************************************** -->
<?php
  define('USERNAME', 'administrator');
  define('PASSWORD_SHA1', '02cc4d03794b3624b076e48a6d6d18b1f2af8dc1'); // SHA value for wery weak demonstration password PassworD newer use in production environment!!!
     // sha1 has code was generated for example by online app http://www.sha1-online.com/
 /* location where images are transfered afte succesfull submit */
 
?>


