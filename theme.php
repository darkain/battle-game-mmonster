<?php
  require('includes/config.php');
  if ($user_id == 0) redirect_404();
  if ($user_type !== 'prem') redirect_404();

  $page_name = 'Change Theme';

  $theme = (isset($_POST['theme'])) ? mysql_safe($_POST['theme']) : '';

  if (isset($_POST['theme'])) {
    if ($theme==='0'  ||  $theme==='white'  ||  $theme==='red'  ||  $theme==='pink') {
      mysql_query("UPDATE `$db_users` SET `user_theme`='$theme' WHERE `user_id`='$user_id' LIMIT 1");
      $db_ok = 'Theme successfully changed';
      $user['user_theme'] = $theme;
      $user_theme = $theme;
    }
  }

  require('includes/header.php');
  if (isset($err_name)) echo "<span class=\"error\">$err_name</span><br /><br />\n\n";
  if (isset($db_ok)) echo "<span class=\"good\">$db_ok</span><br /><br />\n\n";
?>


<h1>Change Theme</h1><br />
<form action="theme.php" method="post"><div>
<table class="stats">
<tr><th><label for="themedefault">Default</label></th><td><input type="radio" name="theme" value="0" id="themedefault" <?php if ($user_theme=='0') echo 'checked="checked"'; ?> /></td></tr>
<tr><th><label for="themewhite">Silver</label></th><td><input type="radio" name="theme" value="white" id="themewhite" <?php if ($user_theme=='white') echo 'checked="checked"'; ?> /></td></tr>
<tr><th><label for="themered">Red</label></th><td><input type="radio" name="theme" value="red" id="themered" <?php if ($user_theme=='red') echo 'checked="checked"'; ?> /></td></tr>
<tr><th><label for="themepink">Pink</label></th><td><input type="radio" name="theme" value="pink" id="themepink" <?php if ($user_theme=='pink') echo 'checked="checked"'; ?> /></td></tr>
<tr><td colspan="2" class="center">
  <input type="submit" value="Change" />
  <input type="button" value="Cancel" onclick="document.location='profile.php?id=<?php echo $user_id; ?>'" />
</td></tr>
</table>
</div>
</form>


<?php
  require('includes/footer.php');
?>