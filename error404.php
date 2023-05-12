<?php
  header("HTTP/1.0 404 Not Found");
  require('includes/config.php');
  $page_name = 'ERROR 404 - FILE NOT FOUND';
  require('includes/header.php');
?>

<h1 class="error">ERROR: 404</h1><br /><div class="error">
ERROR!  The file you requested could not be found!
</div>

<?php
  require('includes/footer.php');
?>