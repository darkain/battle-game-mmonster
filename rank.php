<?php
  require('includes/config.php');

  $page_name = 'Top Ranks';
  $more_header = '<link rel="alternate" type="application/rss+xml" title="RSS" href="rank.rss.php" />';

  require('includes/header.php');

//TODO: add sub-ordering for tie breakers

  $type      = (isset($_GET['type']))   ? mysql_safe($_GET['type']) : 0;
  $sorttype  = $type;
  $sort_type = $type;
  $extra     = '';

  $order   = "(`user_fame` + `user_bling`) DESC";
  if      ($type === 'level')  $order = "`user_level` DESC";
  else if ($type === 'fame')   $order = "`user_fame` DESC";
  else if ($type === 'bling')  $order = "`user_bling` DESC";
  else if ($type === 'kills')  $order = "`user_kills` DESC";
  else if ($type === 'deaths') $order = "`user_deaths` DESC";
  else if ($type === 'age')  { $order = "`user_reg_date` DESC"; $extra = ", `user_reg_date`"; }
  if ($user_id > 0) $order .= ", `user_id`='$user_id' DESC";


  $myval  = '';
  $myrank = 0;
  if ($user_id > 0) {
    $db_query  = "SELECT `user_fame`, `user_bling`, `user_level`, `user_kills`, `user_deaths` $extra FROM `$db_users` WHERE `user_id`='$user_id' LIMIT 1";
    $db_result = mysql_query($db_query);
    if      ($type === 'level')  $myval = "`user_level` > '" . mysql_result($db_result, 0, 'user_level') . "'";
    else if ($type === 'fame')   $myval = "`user_fame` > '" . mysql_result($db_result, 0, 'user_fame') . "'";
    else if ($type === 'bling')  $myval = "`user_bling` > '" . mysql_result($db_result, 0, 'user_bling') . "'";
    else if ($type === 'kills')  $myval = "`user_kills` > '" . mysql_result($db_result, 0, 'user_kills') . "'";
    else if ($type === 'deaths') $myval = "`user_deaths` > '" . mysql_result($db_result, 0, 'user_deaths') . "'";
    else $myval = "(`user_fame` + `user_bling`) > '" . (mysql_result($db_result, 0, 'user_fame') + mysql_result($db_result, 0, 'user_bling')) . "'";
    mysql_free_result($db_result);

    $db_query  = "SELECT COUNT(*) FROM `bgm_users` WHERE $myval OR `user_id`='$user_id'";    
    $db_result = mysql_query($db_query);
    $myrank = mysql_result($db_result, 0, 'COUNT(*)');
    mysql_free_result($db_result);
  }



  $top_query  = "SELECT u.user_id, u.user_fame, u.user_bling, u.user_fame+u.user_bling AS user_rank FROM `$db_users` u, `$db_stuff` s WHERE s.stuff_name='toprank' AND s.stuff_int=u.user_id LIMIT 1";
  $top_result = mysql_query($top_query);
  $top_id     = mysql_result($top_result, 0, 'user_id');
  $top_fame   = mysql_result($top_result, 0, 'user_fame');
  $top_bling  = mysql_result($top_result, 0, 'user_bling');
  $top_rank   = mysql_result($top_result, 0, 'user_rank');
  mysql_free_result($top_result);


  $db_limit  = 10;
  if ($user_id > 0  &&  $myrank < 21) $db_limit = 30;

  $db_query  = "SELECT * FROM `$db_users` WHERE 1 ORDER BY $order LIMIT $db_limit";
  $db_result = mysql_query($db_query);
  $db_count  = mysql_numrows($db_result);
?>


<h1>Top Rank</h1><br /><div class="rank">
<?php
  $prof = mysql_fetch_assoc($db_result);
  display_profile($prof);
?>
</div>



<h1>Top Ranks</h1><br /><div class="rank">
<table class="rank">
<?php

  display_ranks($db_result, 1, 1);

  mysql_free_result($db_result);


  if ($user_id > 0  &&  $myrank > 20) {
    echo "<tr><th>&nbsp;</th><td colspan=\"7\" style=\"border:none\">&nbsp;</td></tr>\n";

    $db_query  = "SELECT * FROM `$db_users` WHERE 1 ORDER BY $order LIMIT " . ($myrank-6) . ", 11";
    $db_result = mysql_query($db_query);
    $db_count  = mysql_numrows($db_result);

    display_ranks($db_result, $myrank-5, 0, 0);

    mysql_free_result($db_result);
  }


  echo "</table></div>\n";
  require('includes/footer.php');
?>