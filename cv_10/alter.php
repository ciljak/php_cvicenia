
<!-- ***************************************************************************** -->
<!-- PHP  code for modification bazaar_user table for user_role for bazaar app     -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 1.11.2020 by CDesigner.eu                            -->
<!-- ***************************************************************************** -->

<?php // script for accessing database and first table structure establishement
require_once('appvars.php'); // including variables for database

/* Attempt MySQL server connection. Assuming you are running MySQL
server with  (user 'admin' with  password test*555) */
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
 
// Check connection
if($dbc === false){
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}
 
// Attempt create table query execution
$sql1 = "ALTER TABLE bazaar_user ADD COLUMN user_role VARCHAR(40) DEFAULT "."'user'"; 
//adding role filed into user table, default registered user is user role, only admin is added manually

  



echo "<h2>Altering bazaar_user table app.</h2>";


echo ' | ***************************************************************************** | ';
echo "<br>";
echo ' |  PHP  code for automation of updating bazaar_user table for user_role filed   | ';
echo "<br>";
echo ' | ***************************************************************************** | ';
echo "<br>";
echo ' | Vrsion: 1.0        Date: 1.11.2020 by CDesigner.eu                           | ';
echo "<br>";
echo ' | ***************************************************************************** | ';
echo "<br>";

if(mysqli_query($dbc, $sql1)){
    echo "Table 1 - bazaar_user altered successfully.";
    echo "<br><br>";

} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
    echo "<br><br>";
};


 
// Close connection
mysqli_close($dbc);
?>