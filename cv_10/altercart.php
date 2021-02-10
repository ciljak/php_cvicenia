<!-- ***************************************************************************** -->
<!-- PHP  code for modification bazaar_item table with cart_number                 -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 1.11.2020 by CDesigner.eu                            -->
<!-- ***************************************************************************** -->


<?php // script for updating bazaar_itme with cart_number
//after item is added to cart, default cart number 0 is changed to user_id of buying user
// this way is implemented shopping cart
require_once('appvars.php'); // including variables for database

/* Attempt MySQL server connection. Assuming you are running MySQL
server with  (user 'admin' with  password test*555) */
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
 
// Check connection
if($dbc === false){
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}
 
// Attempt create table query execution
$sql1 = "ALTER TABLE bazaar_item ADD COLUMN cart_number INT DEFAULT "."'0'"; 
//adding cart_number field, that holds 0 if item was not added to cart or users_id if
// somebody added item into their cart

  



echo "<h2>Altering bazaar_user table app.</h2>";


echo ' | ***************************************************************************** | ';
echo "<br>";
echo ' |  PHP  code for automation of updating bazaar_item table for cart_number       | ';
echo "<br>";
echo ' | ***************************************************************************** | ';
echo "<br>";
echo ' | Vrsion: 1.0        Date: 1.11.2020 by CDesigner.eu                            | ';
echo "<br>";
echo ' | ***************************************************************************** | ';
echo "<br>";

if(mysqli_query($dbc, $sql1)){
    echo "Table 1 - bazaar_item altered successfully.";
    echo "<br><br>";

} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
    echo "<br><br>";
};


 
// Close connection
mysqli_close($dbc);
?>