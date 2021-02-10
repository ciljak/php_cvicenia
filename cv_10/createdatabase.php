
<!-- ***************************************************************************** -->
<!-- PHP  code for automation of preparation databasetable for bazaar app          -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 10.10.2020 by CDesigner.eu                           -->
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
$sql1 = "CREATE TABLE bazaar_user (
    users_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(40) NOT NULL,
    pass_word VARCHAR(44) NOT NULL,
    nickname VARCHAR(40) NOT NULL UNIQUE, /* not two identical nicknames allowed*/
    first_name VARCHAR(40) NOT NULL,
    lastname_name VARCHAR(40) NOT NULL,
    addresss VARCHAR(40) NOT NULL,
    city VARCHAR(40) NOT NULL,
    ZIPcode VARCHAR(40) NOT NULL,
    write_date DATETIME NOT NULL,
    email VARCHAR(70) NOT NULL , /* not UNIQUE e-mails because one user can submitt different benchmark results */
   /* message_text TEXT */ /* optionally add boolean fields for subscription */
    GDPR_accept BOOLEAN NOT NULL default 0, /* BOOLEAN value if user accepted GDPR */
    rules_accept BOOLEAN NOT NULL default 0, /* BOOLEAN value if user accepted portal rules */
    avatar  VARCHAR(70),                      /* link to image */
    profile_text TEXT                       /* submit text from publisher */
    
)";

$sql2 = "CREATE TABLE bazaar_item (
    item_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name_of_item VARCHAR(40) NOT NULL,
    price_eur VARCHAR(40) NOT NULL,
    subcategory_id INT NOT NULL,
    users_id INT NOT NULL,
    item_add_date DATETIME NOT NULL,
    published BOOLEAN NOT NULL default 0,
    screenshot1  VARCHAR(70),                      /* link to image of item 1 */
    screenshot2  VARCHAR(70),                      /* link to image of item 2 */
    screenshot3  VARCHAR(70),                      /* link to image of item 3 */
    item_description TEXT,                        /* item description */
    CONSTRAINT FK_subcategorz_id FOREIGN KEY (subcategory_id) REFERENCES bazaar_category(subcategory_id), /* foreign key N site of 1 to N relation */
    CONSTRAINT FK_users_id FOREIGN KEY (users_id)  REFERENCES bazaar_user(users_id) /* foreign key N site of 1 to N relation */

)";

$sql3 = "CREATE TABLE bazaar_category (
    subcategory_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(40) NOT NULL,
    subcategory VARCHAR(40) NOT NULL
    
)";

echo "<h2>Processing database tables for bazaar app.</h2>";


echo ' | ***************************************************************************** | ';
echo "<br>";
echo ' |    PHP  code for automation of preparation databasetable for bazaar app       | ';
echo "<br>";
echo ' | ***************************************************************************** | ';
echo "<br>";
echo ' | Vrsion: 1.0        Date: 10.10.2020 by CDesigner.eu                           | ';
echo "<br>";
echo ' | ***************************************************************************** | ';
echo "<br>";

if(mysqli_query($dbc, $sql1)){
    echo "Table 1 - bazaar_user created successfully.";
    echo "<br><br>";

} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
    echo "<br><br>";
};

if(mysqli_query($dbc, $sql3)){
    echo "Table 3 - bazaar_item created successfully.";
    echo "<br><br>";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
    echo "<br><br>";
};

if(mysqli_query($dbc, $sql2)){
    echo "Table 2 - bazaar_category created successfully - as last table because foreign key references.";
    echo "<br><br>";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
    echo "<br><br>";
}
 
// Close connection
mysqli_close($dbc);
?>