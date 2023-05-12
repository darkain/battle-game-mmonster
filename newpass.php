<?php
  require('includes/config.php');
  if ($user_id == 0) redirect_404();

  $page_name = 'Change Password';

  require('includes/header.php');

  $password = (isset($_POST['password'])) ? mysql_safe($_POST['password']) : '';
  $passconf = (isset($_POST['passconf'])) ? mysql_safe($_POST['passconf']) : '';

  if (isset($_POST['password'])) {
    if (strlen($password) < 5) {
      $err_name = 'Password is too short : ' . strlen($password) . ' characters.  Must be between 5 and 32.';
    } else if (strlen($password) > 32) {
      $err_name = 'Password is too long : ' . strlen($password) . ' characters.  Must be between 5 and 32.';
    } else if (!eregi('^([0-9a-z])+$', $password)) {
      $err_name = 'Password is invalid';
    } else if ($password !== $passconf) {
      $err_name = 'Password and Confirmation do not match';
    } else {
      $newpass = md5($password);
      mysql_query("UPDATE `$db_users` SET `user_pass`='$newpass' WHERE `user_id`='$user_id' LIMIT 1");
      echo "<span class=\"good\">Password successfully changed</span><br /><br />\n\n";
    }
  }

  if (isset($err_name)) echo "<span class=\"error\">$err_name</span><br /><br />\n\n";
?>


<h1>Change Password</h1><br />
<form action="newpass.php" method="post"><div>
<table class="stats">
<tr><th>New Password:</th><td><input type="password" name="password" size="30" /></td></tr>
<tr><th>Confirm Password:</th><td><input type="password" name="passconf" size="30" /></td></tr>
<tr><td></td><td>
  <input type="submit" value="Change" />
  <input type="button" value="Cancel" onclick="document.location='profile.php?id=<?php echo $user_id; ?>'" />
</td></tr>
</table>
</div>
</form>


<?php
  require('includes/footer.php');
?>