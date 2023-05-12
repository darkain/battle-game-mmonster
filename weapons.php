<?php
  require('includes/config.php');
  require('includes/desc.php');

  $type = (isset($_GET['type'])) ? $_GET['type'] : 0;
  $type = mysql_safe($type);
  if (!array_key_exists($type, $bgm_weapon)) redirect_404();

  $page_name = $bgm_weapon[$type]['name'];
  require('includes/header.php');

  $db_query  = "SELECT * FROM `$db_users` WHERE `user_weapon`='$type' ORDER BY (`user_fame` + `user_bling`) DESC LIMIT 10";
  $db_result = mysql_query($db_query);
  $db_count  = mysql_numrows($db_result);
?>


<h1><?php echo $bgm_weapon[$type]['name']; ?></h1><br /><div><table class="stats"><tr><td>
<img src="images/<?php echo $type; ?>.png" alt="<?php echo $bgm_weapon[$type]['name']; ?>" title="<?php echo $bgm_weapon[$type]['name']; ?>" />
</td><td>
<?php echo $bgm_weapon[$type]['desc']; ?><br/><br/>
<span class="statname"><?php echo $bgm_weapon[$type]['attack1']['name']; ?></span>: <?php echo $bgm_weapon[$type]['attack1']['desc']; ?><br/><br/>
<span class="statname"><?php echo $bgm_weapon[$type]['attack2']['name']; ?></span>: <?php echo $bgm_weapon[$type]['attack2']['desc']; ?>
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