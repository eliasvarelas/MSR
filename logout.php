<?php
  session_start();

  echo "Logout Successfully ";
  session_destroy();   // function that Destroys Session
  $script = file_get_contents('jsredirectlogin.js');
  echo "<script>".$script."</script>";
?>
