<!-- ******************************************************************* -->
<!-- PHP header menu  of bazaar for including                            -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 22. - 22.11.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->
<?php
   // generate menu if user is loged in or not
		 // old solution with cookies if(isset($_COOKIE['username'])) { // loged in user
			require_once('headerlogo.php');
			
			if(isset($_SESSION['username'])) { // loged in user
				 
				echo '<div id="menu">';
				echo '<a class="navbar-brand" href="index.php"><img width="150" src="./images/bazaarheader.png"> Bazaar - best items for a best prices!</a>';
				echo '<a class="navbar-brand" href="editprofile.php"><img id="menuimage" src="./images/menu_profile.png"> Edit profile </a>';
								
				if(isset($_SESSION['user_role'])=='admin') { // if oged user is admin role
				   echo '<a class="navbar-brand" href="admin.php"><img id="menuimage" src="./images/menu_admin.png"> Manage your page </a>';
			   };
			   echo '<a class="navbar-brand" href="logout.php"><img id="menuimage" src="./images/menu_logout.png"> Logout <b><span id="username">' .$_SESSION['username'] .'</span></b></a>';
			   echo '</div >';
			   require_once('sell_icon.php'); // graphic menu item for selling your items
			   echo '<a class="navbar-brand" href="rss.php"><img src="./images/rss.png" width="45"></a>'; //rss feed link
			   require_once('cart_icon.php'); // small cart icon in menu
			   
			  } else { // visitor without login
			   echo '<div id="menu">';
			   echo '<a class="navbar-brand" href="login.php"><img id="menuimage" src="./images/menu_login.png"> Log In </a>';
			   echo '<a class="navbar-brand" href="signup.php"><img id="menuimage" src="./images/menu_signup.png"> Sign Up for better membership! </a>';
   
			   echo '<a class="navbar-brand" href="index.php"><img width="150" src="./images/bazaarheader.png"> Bazaar - best items for a best prices!</a>';
			   echo '</div >';
			 };
			 
?>