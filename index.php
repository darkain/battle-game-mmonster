<?php
  require('includes/config.php');

  $action   = (isset($_POST['action']))   ? mysql_safe($_POST['action']  ) : '';
  $username = (isset($_POST['username'])) ? mysql_safe($_POST['username']) : '';
  $password = (isset($_POST['password'])) ? mysql_safe($_POST['password']) : '';
  $email    = (isset($_POST['email']))    ? mysql_safe($_POST['email'])    : '';
  $db_error = '';


  if ($action === 'login') {
    mysql_query("LOCK TABLES `$db_users` READ, `$db_session` WRITE");
    $md5_pass = md5($password);

    $db_query  = "SELECT `user_id` FROM `$db_users` WHERE `user_name`='$username' AND `user_pass`='$md5_pass' LIMIT 1";
    $db_result = mysql_query($db_query);
    $db_count  = mysql_numrows($db_result);

    if ($db_count > 0) {
      $user_id = mysql_result($db_result, 0, 'user_id');
    } else {
      $user_id = 0;
      $db_error = 'Invalid username or password';
    }

    mysql_query("UPDATE `$db_session` SET `user_id`='$user_id', `ses_referer`='0' WHERE `ses_id`='$ses_id' LIMIT 1");

    mysql_free_result($db_result);
    mysql_query("UNLOCK TABLES");  


    if ($user_id != 0) {
      header("Location: profile.php?id=$user_id");
      exit;
    }
  }


  if ($action === 'resetpassword') {
    mysql_query("LOCK TABLES `$db_users` WRITE");

    $db_query  = "SELECT `user_id` FROM `$db_users` WHERE `user_email`='$email' LIMIT 1";
    $db_result = mysql_query($db_query);
    $db_count  = mysql_numrows($db_result);

    if ($db_count > 0) {
      $uid     = mysql_result($db_result, 0, 'user_id');
      $newpass = md5(time() . microtime());
      $dbpass  = md5($newpass);
      $message = "Your new password is: $newpass";
      $headers = "From: battle@darkain.com\r\nReply-To: battle@darkain.com\r\n";
      mysql_query("UPDATE `$db_users` SET `user_pass`='$dbpass' WHERE `user_id`='$uid' LIMIT 1");
      mail($email, 'Password Reset - Battle Game MMonster', $message, $headers);
    }

    mysql_free_result($db_result);
    mysql_query("UNLOCK TABLES");  
    $db_error = 'Your new password has been sent to your email';
  }


  $page_name = "Welcome";
  require('includes/header.php');


  if ($db_error != '') {
    echo "<span class=\"error\">$db_error</span><br/><br/>\n";
  }

?>

<table class="stats"><tr><td>

<h1>Welcome</h1><br /><div>
Welcome to <b>Battle Game MMonster!</b>  Want to know more about the game?
Then check out the <a href="about.php">About Page</a>.

<br /><br />

We now have a discussion community for <b>Battle Game MMonster</b> over at LiveJournal. Check us out at:
<b class="nobr"><a href="http://community.livejournal.com/battlegame/profile"><img src="images/community.gif"
alt="BattleGame on LiveJournal.com" title="BattleGame on LiveJournal.com" /></a>
<a href="http://battlegame.livejournal.com/">battlegame</a></b>.
</div>


<h1>A New Round!</h1><br /><div>
First off, thanks to everyone who participated in the <b>Battle Game MMonster</b> open beta test.  We are now running
the "final" version of the game, which includes countless amounts of improvements and updates.  Premium user accounts
are now enabled, which give several additional features (including customizable ranks display, custom themes, and user
icons).<br /><br />

Speaking of premium user accounts, <a href="profile.php?id=60">FearMyPickle</a> and
<a href="profile.php?id=48">notoriousblue</a> won the "Who can say #1 the longest" contest with times of 4 hours 44
minutes and 4 hours 42 minutes respectively.  It was decided that since these two hard-core players where so close
in the contest, that they both should be awarded the glorious prize of being the first users with premium accounts!
So, make sure to give them a great big "congratulations beating" on their victory in the contest.
</div>


<?php if ($user_id == 0) { ?>
</td><td>&nbsp;</td><td>


<form action="index.php" method="post" id="formlogin"><div class="form">
<h1>Login</h1><br /><div><table class="stats">
<tr><th>Name:</th><td colspan="2"><input type="text" size="30" maxlength="32" value="" name="username" /></td></tr>
<tr><th>Password:</th><td colspan="2"><input type="password" size="30" maxlength="32" value="" name="password" /></td></tr>
<tr><td></td><td><input type="hidden" name="action" value="login" /><input type="submit" value="Login" /></td>
<td class="right">
  <a href="register.php">Register</a><br />
  <a href="javascript:disp_password()">Reset Password</a>
</td></tr>
</table></div>
</div></form>

<form action="index.php" method="post" id="formpassword" style="display:none"><div class="form">
<h1>Reset Password</h1><br /><div><table class="stats">
<tr><th>E-Mail:</th><td colspan="2"><input type="text" size="30" value="" name="email" /></td></tr>
<tr><td></td><td><input type="hidden" name="action" value="resetpassword" /><input type="submit" value="Reset Password" /></td>
<td class="right"><a href="javascript:disp_login()">Login</a></td></tr>
</table></div>
</div></form>
<?php } ?>

</td></tr></table>

<?
  require('includes/footer.php');
?>