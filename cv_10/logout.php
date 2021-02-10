<!-- ******************************************************************* -->
<!-- PHP "self" code handling logout procedure into the bazaar app       -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 24.10-24.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
 require_once('appvars.php'); // including variables for database
    // part for SESSION solution for login persistence and its ending

    //even when logging out you have to first start the session in order to access the session variables
    session_start();
    if(isset($_SESSION['users_id'])) {
        $_SESSION = array(); // deleting session vars
               
    };

    // if session cookie exists, then delete it
    if(isset($_COOKIE[session_name()])) {
        setcookie('session_name()','',time() - 3600);
        
               
    };

    // Destroy session
    session_destroy();


    // logout user by deleting cookie - for COOKIES persistence solution
    
   /* if(isset($_COOKIE['users_id'])) {
        setcookie('user_id','',time() - 3600);
        setcookie('username','',time() - 3600);
        echo "deleted cookies";
               
    }; */

    // for our final solution SESSIONS+ longer login persistency with COOKIES must be also cokies deleted
    setcookie('users_id', $row['users_id'], time()-3600);
    setcookie('username', $row['username'], time()-3600);
    setcookie('user_role', $row['user_role'], time()-3600); // added deletion of user_role cookie - after altering table for user_role

    // redirect to homepage in logout state
    $home_url = 'http://'. $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
    header('Location:'. $home_url);

 ?>