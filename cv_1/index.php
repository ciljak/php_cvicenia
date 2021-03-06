<!DOCTYPE html>
<html>
<head>
<title>Ex. 1. PHP & SQL - bascics of PHP langueage and embedding them.</title>
<link rel="stylesheet" href="style.css">
<!-- link rel="stylesheet" href="./bootstrap/bootstrap.min.css" --> 

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

</head>
<body>
<div class="container">


  
    <h1 id="Nadpis_headline">PHP language - 1. structure, embedding, basic variable types</h1> <!-- Príklad vloženia nadpisu prvej úrovne -->

    <h2>How to embeed PHP code into a page?</h2>

    <?php  
    echo '<table><tr class="alert alert-primary">';
    echo ' <h4 class="alert alert-primary"> + Our PHP output with bootstrap external style. <h4>';
    echo "<tr><table>";
    
    ?>


      <div class="articles">
        <p>
        Next part of source code is a example how to embedd php code and make simple output.
        </p>
        <?php
              echo "<p><em> Hello world </em> from our script :-)<p> "; // greate example how to echoe output from embedded php script
        ?>

        <p>This part of output is marked with HTML ...</p>
        <?php
              echo "<p><br /> and now from PHP</p>" // HTML and PHP parts can be combinetd. From HTML marked output to php and next return to HTML (BTW this is single line comment)
         /**
         * and this is multiline coment /* most common as in C and Java */ 
        

        ?>

       <p>All text outputs in next part are generated by php script.</p>
       <?php
              echo "<p>Basic sequence of commands is separated by ;</p>";
              echo "<p>Variables does not use explicit typing. Please refer to a sourcecode of page. ;</p>";
              $number1 = 10;
              $number2 = -2.35;
              $sentence = "Example of sentence.";
              $is_result = false;
              $result=$number1+$number2; // single = means evaluation but == is comparisn

              echo "<p>N1= $number1 and N2= $number2 and result is $result</p>";

              // next part will show how to assign one veriable to another

              $a = 5;
              $b = $a;

              echo "<p> b= $b </p>";
              
              // very specific way is variable nested into another variable

              $a = 10;
              $b = -5;
              $c = "b";
              $nested_result = $$c;

              echo "<p> Examle of nested result (variable in another variable) - result is $nested_result </p>";

              // display of string variables
              echo '<p>If something must be grab as is, simple quotation marks are good solution. Variable must be concatenated '.$a.' but our previous example</p>';
              echo "<p>with double qotations mark can expand nested variables a=$a <p>";

              // field of numeric values
              $a = array();

              $a[0] = 0.25; 

              $i=1;
              $a[i] = 0.55; 

              // initialization with array keyword

              $b = array(2.25, -3.12, 66.50);
              echo "Array b on second position contains $b[1]";

              // example of multidimensional array
              $matrix[0][0] = 1;
              $matrix[0][1] = 0;
              $matrix[1][0] = 0;
              $matrix[1][1] = 1;

              // asociative arrays

              $new_device["type"] = "Block device";
              echo "<p> New listed device is ". $new_device["type"]."   </p>";




      ?>

       

          
      </div>  
</div>

</body>
</html>