
<!-- ***************************************************************************** -->
<!-- PHP  code for automation of preparation databasetable for benchmarkchart app  -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 27.9.2020 by CDesigner.eu                            -->
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
$sql = "CREATE TABLE benchmark_chart (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nickname VARCHAR(40) NOT NULL,
    write_date DATETIME NOT NULL,
    email VARCHAR(70) NOT NULL , /* not UNIQUE e-mails because one user can submitt different benchmark results */
   /* message_text TEXT */ /* optionally add boolean fields for subscription */
    GDPR_accept BOOLEAN, /* BOOLEAN value if user accepted GDPR */
    screenshot  VARCHAR(70) NOT NULL ,                      /* link to image */
    message_from_submitter TEXT,                        /* submit text from publisher */
    score INT NOT NULL
)";
if(mysqli_query($dbc, $sql)){
    echo "Table created successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
}
 
// Close connection
mysqli_close($dbc);
?>