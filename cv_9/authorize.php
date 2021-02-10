<!-- ******************************************************************* -->
<!-- PHP simple header authorization code                                -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 3-4.10.2020 by CDesigner.eu                -->
<!-- ******************************************************************* -->
<?php // leading part of page for simple header securing and basic variable setup
    require_once('appvars.php'); // including variables for database
    $username = USERNAME;
    $password_sha = PASSWORD_SHA1;
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
       ($_SERVER['PHP_AUTH_USER'] != $username ) || ( sha1($_SERVER['PHP_AUTH_PW']) != $password_sha) ) {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Basic realm="benchmark_admin"');
        exit('<h2>Becnchmarkchart</h2> Access denied, you must enter a valid username and password to access this page!
              <p> <a href = "index.php"> &lt;&lt Back to benchmarkresults homepage page. </a></p>');   

    }

    
		
		
?>