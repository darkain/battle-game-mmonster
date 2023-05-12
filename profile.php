<?php
  require('includes/config.php');

  //this is here so we can view other's profiles too
  $profile_id = $user_id;
  if ($id != 0) $profile_id = $id;

  $prof_query = "SELECT * FROM `$db_users` WHERE `user_id`='$profile_id' LIMIT 1";
  $prof_result = mysql_query($prof_query);

  if (!$prof_result) redirect_404();
  if (mysql_numrows($prof_result) < 1) redirect_404();

  $prof = mysql_fetch_assoc($prof_result);

  $prof_name   = $prof['user_name'];
  $prof_turns  = $prof['user_turns'];
  $prof_exp    = $prof['user_exp'];
  $prof_maxexp = $prof['user_exp_level'];
  $prof_type   = $prof['user_type'];
  $prof_theme  = $prof['user_theme'];

  $sort_type = '';
  $page_name = $prof_name;
  if ($prof_type === 'prem') $user_theme = $prof_theme;

  require('includes/header.php');
?>


<?php if ($user_type !== 'prem') { ?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="payprem"><div class="form" style="display:none">
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="business" value="darkain@darkain.com" />
<input type="hidden" name="item_name" value="Premium Account - Battle Game MMonster" />
<input type="hidden" name="item_number" value="<?php echo $user_id; ?>" />
<input type="hidden" name="amount" value="7.50" />
<input type="hidden" name="no_shipping" value="1" />
<input type="hidden" name="return" value="http://battle.darkain.com/payment.php" />
<input type="hidden" name="no_note" value="1" />
<input type="hidden" name="currency_code" value="USD" />
<input type="hidden" name="bn" value="PP-BuyNowBF" />
</div></form>
<?php } ?>


<?php if ($user_id == $profile_id) { ?>
 <h1>Account Options</h1><br /><div class="center nowrap">
 <a href="newpass.php">Change Password</a>
 <?php if ($user_type !== 'prem') { ?>
  : Userpic
  : Theme
  : Ranks Display
  : <a href="javascript: document.payprem.submit()">Upgrade to Premium ($7.50)</a>
 <?php } else { ?>
  : <a href="userpic.php">Userpic</a>
  : <a href="theme.php">Theme</a>
  : <a href="display.php">Ranks Display</a>
 <?php } ?>
 </div>
<?php } ?>


<h1>Character Profile</h1><br /><div class="rank">
<?php
  display_profile($prof, 1);
?>
</div>


<?php if ($user_id == $profile_id) { ?>

<h1>Character Stats</h1><br /><div>
<table class="stats">
  <tr><th>EXP:</th><td><?php echo display_hp($prof_exp, $prof_maxexp, 1); ?><span class="hp hptext"><?php echo "$prof_exp / $prof_maxexp : " . floor($prof_exp/$prof_maxexp*100); ?>%</span></td></tr>
  <tr><th>Turns:</th><td><?php echo $prof_turns ?></td></tr>
  <tr><th>Strength:</th><td><?php echo $prof['user_strength']; ?></td></tr>
  <tr><th>Agility:</th><td><?php echo $prof['user_agility']; ?></td></tr>
  <tr><th>Intelligence:</th><td><?php echo $prof['user_intelligence']; ?></td></tr>
  <tr><th>Luck:</th><td><?php echo $prof['user_luck']; ?></td></tr>
</table>
</div>


<h1>Recent Attackers</h1><br /><div class="rank">
<table class="rank">
<?php
    $db_query  = "SELECT * FROM `$db_attack`a , `$db_users` u WHERE a.`user_id`='$user_id' AND u.`user_id`=a.`attack_id` ORDER BY a.`attack_time` DESC LIMIT 10";
    $db_result = mysql_query($db_query);
    $db_count  = mysql_numrows($db_result);

    display_ranks($db_result);

    mysql_free_result($db_result);
?>
</table></div>


<h1>Referals</h1><br /><div class="rank">
<span class="center block">
<textarea rows="3" cols="85">&lt;a href="http://battle.darkain.com/index.php?referer=<?php echo $user_id; ?>"&gt;&lt;img border="0" src="http://battle.darkain.com/images/button/" alt="Come join Battle Game MMonster!" title="Come join Battle Game MMonster!" /&gt;&lt;/a&gt;</textarea><br /><br />
</span>

<table class="rank">
<?php
    $db_query  = "SELECT * FROM `$db_users` WHERE `user_referer`='$user_id' ORDER BY `user_reg_date` DESC LIMIT 10";
    $db_result = mysql_query($db_query);
    $db_count  = mysql_numrows($db_result);

    display_ranks($db_result);

    mysql_free_result($db_result);
?>
</table></div>
<?php } ?>

<?php
  mysql_free_result($prof_result);
  require('includes/footer.php');
?>
