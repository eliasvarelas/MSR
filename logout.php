<?php
  session_start(); //neccesary for the declaration of a particullar active user
  echo "Logout Successfully ";
  session_destroy();   // function that Destroys Session
  $script = file_get_contents('jsredirectlogin.js');
  echo "<script>".$script."</script>";
?>
