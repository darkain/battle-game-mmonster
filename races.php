<?php
  require('includes/config.php');
  require('includes/desc.php');

  $type = (isset($_GET['type'])) ? $_GET['type'] : 0;
  $type = mysql_safe($type);
  if (!array_key_exists($type, $bgm_race)) redirect_404();

  $page_name = $bgm_race[$type]['name'];
  require('includes/header.php');

  $db_query  = "SELECT * FROM `$db_users` WHERE `user_race`='$type' ORDER BY (`user_fame` + `user_bling`) DESC LIMIT 10";
  $db_result = mysql_query($db_query);
  $db_count  = mysql_numrows($db_result);
?>


<h1><?php echo $bgm_race[$type]['name']; ?></h1><br /><div><table class="stats"><tr><td>
<img src="images/<?php echo $type; ?>.png" alt="<?php echo $bgm_race[$type]['name']; ?>" title="<?php echo $bgm_race[$type]['name']; ?>" />
</td><td>
<?php echo $bgm_race[$type]['desc']; ?><br/><br/>
</td></tr></table></div>


<h1>Top 10</h1><br /><div class="rank">
<table class="rank">
<?php display_ranks($db_result); ?>
</table>
</div>


<?
  mysql_free_result($db_result);
  require('includes/footer.php');
?>