<!-- ******************************************************************* -->
<!-- PHP footer of bazaar  for including                                 -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 22. - 22.11.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
 // for further rework of the code
 function generate_footer($width) {
  if($width==580) {
    echo '<div class="footer" >'; 		
    echo '<div class="footer" id="footer_container_580">'; 
    echo '<a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>'; 
    echo '<a class="navbar-brand" href="rss.php"> Subscribe to newsfeed <img src="./images/rss.png" width="25"> </a>'; 
    echo '</div>'; 
    echo '</div>'; 		

  }

  if($width==1060) {
    echo '<div class="footer" >'; 		
    echo '<div class="footer" id="footer_container_1060">'; 
    echo '<a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>'; 
    echo '<a class="navbar-brand" href="rss.php"> Subscribe to newsfeed <img src="./images/rss.png" width="25"> </a>'; 
    echo '</div>'; 
    echo '</div>'; 		

  }

      }
?>

<!-- older solution without daptive width
    <div class="footer" > 		
        <div class="footer" id="footer_container_1060"> 
            <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
            <a class="navbar-brand" href="rss.php"> Subscribe to newsfeed <img src="./images/rss.png" width="25"> </a>
            </div>
    </div>

-->