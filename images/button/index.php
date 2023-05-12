<?php
  header('Content-type: image/png');
  header('Content-size: ' . filesize('battle.png'));
  $file = fopen('battle.png', 'r');
  fpassthru($file);
  fclose($file);
?>