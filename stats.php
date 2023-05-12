<?php
  require('includes/config.php');

  $page_name = 'Stats';

  require('includes/header.php');

  $db_query = "SELECT COUNT(*), SUM(user_fame), SUM(user_bling), SUM(user_kills)  FROM `$db_users`";
  $db_result = mysql_query($db_query);
  $player_total = mysql_result($db_result, 0, 'COUNT(*)');
  $fame_total   = mysql_result($db_result, 0, 'SUM(user_fame)');
  $bling_total  = mysql_result($db_result, 0, 'SUM(user_bling)');
  $kills_total  = max(1, mysql_result($db_result, 0, 'SUM(user_kills)'));
  mysql_free_result($db_result);

  $today = time() - 60*60*24;
  $db_query = "SELECT COUNT(*) FROM `$db_users` WHERE `user_last_login` >= '$today'";
  $db_result = mysql_query($db_query);
  $player_recent = mysql_result($db_result, 0, 'COUNT(*)');
  mysql_free_result($db_result);

  $user_fame   = 0;
  $user_bling  = 0;
  $user_deaths = 0;
  $user_kills  = 0;
  if ($user_id > 0) {
    $db_query    = "SELECT * FROM `$db_users` WHERE `user_id`='$user_id' LIMIT 1";
    $db_result   = mysql_query($db_query);
    $user_fame   = mysql_result($db_result, 0, 'user_fame');
    $user_bling  = mysql_result($db_result, 0, 'user_bling');
    $user_deaths = mysql_result($db_result, 0, 'user_deaths');
    $user_kills  = mysql_result($db_result, 0, 'user_kills');
    mysql_free_result($db_result);
  }
?>


<h1>Game Stats</h1><br /><div class="rank">
<table class="stats">
<tr><th>Total Number of Players:</th><td><?php echo $player_total; ?></td></tr>
<tr><th>Number of Logins Today:</th><td><?php echo $player_recent; ?></td></tr>
<tr><th>Total Fame Economy:</th><td><?php echo $fame_total; ?></td></tr>
<tr><th>Total Bling Economy:</th><td><?php echo $bling_total; ?></td></tr>
<tr><th>Total Number of Kills:</th><td><?php echo $kills_total; ?></td></tr>
</table>
</div>

<?php if ($user_id > 0) { ?>
<h1>Player Stats</h1><br /><div class="rank">
<table class="stats">
<tr><th>Your Fame:</th><td><?php echo $user_fame; ?> : <?php echo round($user_fame / $fame_total * 100, 2) ?>% of Total Economy</td></tr>
<tr><th>Your Bling:</th><td><?php echo $user_bling; ?> : <?php echo round($user_bling / $bling_total * 100, 2) ?>% of Total Economy</td></tr>
<tr><th>Your Kills:</th><td><?php echo $user_kills; ?> : <?php echo round($user_kills / $kills_total * 100, 2) ?>% of Total Kills</td></tr>
<tr><th>Your Deaths:</th><td><?php echo $user_deaths; ?> : <?php echo round($user_deaths / $kills_total * 100, 2) ?>% of Total Deaths</td></tr>
</table>
</div>
<?php } ?>


<?php
  require('includes/footer.php');
?>