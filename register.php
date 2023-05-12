<?php
  require('includes/config.php');
  require('includes/desc.php');

  $page_name = 'Registration';

  $reg_error = array();
  $err_names = array();

  $username = (isset($_POST['username'])) ? mysql_safe($_POST['username']) : '';
  $password = (isset($_POST['password'])) ? mysql_safe($_POST['password']) : '';
  $passconf = (isset($_POST['passconf'])) ? mysql_safe($_POST['passconf']) : '';
  $email    = (isset($_POST['email']))    ? mysql_safe($_POST['email'])    : '';

  $race     = (isset($_POST['race']))     ? mysql_safe($_POST['race'])     : '';
  $class    = (isset($_POST['class']))    ? mysql_safe($_POST['class'])    : '';
  $weapon   = (isset($_POST['weapon']))   ? mysql_safe($_POST['weapon'])   : '';

  $title    = '';
  $title0   = (isset($_POST['title0']))   ? mysql_safe($_POST['title0'])   : '';
  $title1   = (isset($_POST['title1']))   ? mysql_safe($_POST['title1'])   : '';
  $title2   = (isset($_POST['title2']))   ? mysql_safe($_POST['title2'])   : '';
  $title3   = (isset($_POST['title3']))   ? mysql_safe($_POST['title3'])   : '';

  $str      = (isset($_POST['str']))      ? mysql_safe($_POST['str'])      : '5';
  $agil     = (isset($_POST['agil']))     ? mysql_safe($_POST['agil'])     : '5';
  $intel    = (isset($_POST['intel']))    ? mysql_safe($_POST['intel'])    : '5';
  $luck     = (isset($_POST['luck']))     ? mysql_safe($_POST['luck'])     : '5';


  if (isset($_POST['username'])) {
    mysql_query("LOCK TABLES `$db_users` WRITE, `$db_session` WRITE");

    if (strlen($username) < 5) {
      $reg_error['character'] = true;
      $reg_error['username']  = true;
      $err_names[] = 'Name is too short : ' . strlen($username) . ' characters.  Must be between 5 and 32.';
    } else if (strlen($username) > 32) {
      $reg_error['character'] = true;
      $reg_error['username']  = true;
      $err_names[] = 'Name is too long : ' . strlen($username) . ' characters.  Must be between 5 and 32.';
    } else if (!eregi('^([0-9a-z])+$', $username)) {
      $reg_error['character'] = true;
      $reg_error['username']  = true;
      $err_names[] = 'Name is invalid';
    } else {
      $db_query  = "SELECT COUNT(*) FROM `$db_users` WHERE `user_name` LIKE '$username' LIMIT 1";
      $db_result = mysql_query($db_query);
      $db_count  = mysql_result($db_result, 0, 'COUNT(*)');
      mysql_free_result($db_result);
      if ($db_count > 0) {
        $reg_error['character'] = true;
        $reg_error['username']  = true;
        $err_names[] = 'Name is already in use';
      }
    }

    if (strlen($password) < 5) {
      $reg_error['character'] = true;
      $reg_error['password']  = true;
      $err_names[] = 'Password is too short : ' . strlen($password) . ' characters.  Must be between 5 and 32.';
    } else if (strlen($password) > 32) {
      $reg_error['character'] = true;
      $reg_error['password']  = true;
      $err_names[] = 'Password is too long : ' . strlen($password) . ' characters.  Must be between 5 and 32.';
    } else if (!eregi('^([0-9a-z])+$', $password)) {
      $reg_error['character'] = true;
      $reg_error['password']  = true;
      $err_names[] = 'Password is invalid';
    } else if ($password !== $passconf) {
      $reg_error['character'] = true;
      $reg_error['password']  = true;
      $err_names[] = 'Password and Confirmation do not match';
    }

    if (!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $email)  ||  strlen($email) > 64) {
      $reg_error['character'] = true;
      $reg_error['email']     = true;
      $err_names[] = 'E-Mail address is invalid';
    } else {
      $db_query  = "SELECT COUNT(*) FROM `$db_users` WHERE `user_email` LIKE '$email' LIMIT 1";
      $db_result = mysql_query($db_query);
      $db_count  = mysql_result($db_result, 0, 'COUNT(*)');
      mysql_free_result($db_result);
      if ($db_count > 0) {
        $reg_error['character'] = true;
        $reg_error['email']     = true;
        $err_names[] = 'E-Mail address is already in use';
      }
    }

    if      ($race == 'anthro') $title = $title0;
    else if ($race == 'demi')   $title = $title1;
    else if ($race == 'dog')    $title = $title2;
    else if ($race == 'pwny')   $title = $title3;
    $title = htmlspecialchars($title);

    if ($race != 'anthro'  &&  $race != 'demi'  &&  $race != 'dog'  && $race != 'pwny') {
      $reg_error['race'] = true;
      $err_names[] = 'Please select a character race';
    } else if (strlen($title) < 2) {
      $reg_error['race']  = true;
      $reg_error['title'] = true;
      $err_names[] = 'Title is too short : ' . strlen($username) . ' characters.  Must be between 2 and 32.';
    } else if (strlen($title) > 32) {
      $reg_error['race']  = true;
      $reg_error['title'] = true;
      $err_names[] = 'Title is too long : ' . strlen($username) . ' characters.  Must be between 2 and 32.';
    } else if (!eregi('^([ -~])+$', $title)) {
      $reg_error['race']  = true;
      $reg_error['title'] = true;
      $err_names[] = 'Title is invalid';
    }

    if ($class != 'thief'  &&  $class != 'assassin'  &&  $class != 'salesperson'  && $class != 'clown') {
      $reg_error['class'] = true;
      $err_names[] = 'Please select a character class';
    }

    if ($weapon != 'sword'  &&  $weapon != 'gunchuck'  &&  $weapon != 'scissor'  && $weapon != 'phone') {
      $reg_error['weapon'] = true;
      $err_names[] = 'Please select a character weapon';
    }

    if (!is_numeric($str)  ||  $str < 5  ||  $str > 20) {
      $reg_error['stats']    = true;
      $reg_error['strength'] = true;
      $reg_error['statamt']  = true;
      $err_names[] = 'Strength must be between 5 and 20';
    }

    if (!is_numeric($agil)  ||  $agil < 5  ||  $agil > 20) {
      $reg_error['stats']   = true;
      $reg_error['agility'] = true;
      $reg_error['statamt'] = true;
      $err_names[] = 'Agility must be between 5 and 20';
    }

    if (!is_numeric($intel)  ||  $intel < 5  ||  $intel > 20) {
      $reg_error['stats']        = true;
      $reg_error['intelligence'] = true;
      $reg_error['statamt']      = true;
      $err_names[] = 'Intelligence must be between 5 and 20';
    }

    if (!is_numeric($luck)  ||  $luck < 5  ||  $luck > 20) {
      $reg_error['stats']   = true;
      $reg_error['luck']    = true;
      $reg_error['statamt'] = true;
      $err_names[] = 'Luck must be between 5 and 20';
    }

    if (is_numeric($str)  &&  is_numeric($agil)  &&  is_numeric($intel)  &&  is_numeric($luck)) {
      $total_stats = $str + $agil + $intel + $luck;
      if ($total_stats != 40) {
        $reg_error['stats']     = true;
        $reg_error['stattotal'] = true;
        $err_names[] = 'You may only spend 40 stat points total';
      }
    }


    //all valid
    if (count($reg_error) == 0) {
      $password = md5($password);
      $time = time();

      $turns = 800;

//TODO:  check to see if user_lasT_turn and user_last_login overlap, and can be merged into one

      mysql_query("INSERT INTO `$db_users` (`user_referer`, `user_reg_date`, `user_name`, `user_title`, `user_pass`, `user_email`, `user_race`, `user_class`, `user_weapon`, `user_strength`, `user_agility`, `user_intelligence`, `user_luck`, `user_base_strength`, `user_base_agility`, `user_base_intelligence`, `user_base_luck`, `user_last_turn`, `user_last_login`, `user_turns`) VALUES ('$ses_referer', '$time', '$username', '$title', '$password', '$email', '$race', '$class', '$weapon', '$str', '$agil', '$intel', '$luck', '$str', '$agil', '$intel', '$luck', '$time', '$time', '$turns')");

      increment_stuff('econfame',  100);
      increment_stuff('econbling', 100);

      $user_id = mysql_insert_id();
      mysql_query("UPDATE `$db_session` SET `user_id`='$user_id', `ses_referer`='0' WHERE `ses_id`='$ses_id'");


      //referal stuffs
      if ($ses_referer > 0) {
        $db_query  = "SELECT * FROM `$db_users` WHERE `user_id`='$ses_referer' LIMIT 1";
        $db_result = mysql_query($db_query);
        if (mysql_numrows($db_result) > 0) {
          $bonus_turn = mysql_result($db_result, 0, 'user_bonus_turn');
          $bonus_last = mysql_result($db_result, 0, 'user_bonus_last');

          $bonus_turn = min($bonus_turn + 100, 1000);
          if ($bonus_last == 0) $bonus_last = time();

          mysql_query("UPDATE `$db_users` SET `user_bonus_turn`='$bonus_turn', `user_bonus_last`='$bonus_last' WHERE `user_id`='$ses_referer' LIMIT 1");
        }
      }

      $user_id = mysql_insert_id();
      
      mysql_query("UNLOCK TABLES");
      header("Location: profile.php?id=$user_id");
      exit;
    }

    mysql_query("UNLOCK TABLES"); 
  }


  require('includes/header.php');


  for ($i=0; $i<count($err_names); $i++) {
    echo "<span class=\"error\">ERROR: " . $err_names[$i] . "</span><br />\n";
  }
  if (count($err_names) > 0) echo "<br />\n";
?>



<form action="register.php" method="post"><div class="form">


<h1<?php if (array_key_exists('character',$reg_error)) echo ' class="error"'; ?>>Character</h1><br />
<div>
<table class="stats">
<tr<?php if (array_key_exists('username',$reg_error)) echo ' class="error"'; ?>><th>Name:</th><td><input type="text" size="30" name="username" value="<?php echo $username; ?>" /> (you will use this name to log into the game)</td></tr>
<tr<?php if (array_key_exists('password',$reg_error)) echo ' class="error"'; ?>><th>Password:</th><td><input type="password" size="30" name="password" /> <b>Confirm:</b> <input type="password" size="30" name="passconf" /></td></tr>
<tr<?php if (array_key_exists('email',$reg_error)) echo ' class="error"'; ?>><th>E-Mail:</th><td><input type="text" size="30" name="email" value="<?php echo $email; ?>" /> (this is used for confirmations)</td></tr>
<tr><td></td><td>Name and Password are case sensitive</td></tr>
</table>
</div>


<table class="reg"><tr><td class="reg" valign="top">


<h1<?php if (array_key_exists('race',$reg_error)) echo ' class="error"'; ?>>Race</h1><br />
<div><table class="regsec" cellspacing="0" cellpadding="0"><tr><td>
<input type="radio" name="race" id="race0" onclick="group_sel('race',0)" value="anthro" <?php if ($race=='anthro') echo 'checked="checled"'; ?> /><label for="race0"><?php echo $bgm_race['anthro']['name']; ?></label><br />
<input type="radio" name="race" id="race1" onclick="group_sel('race',1)" value="demi" <?php if ($race=='demi') echo 'checked="checled"'; ?> /><label for="race1"><?php echo $bgm_race['demi']['name']; ?></label><br />
<input type="radio" name="race" id="race2" onclick="group_sel('race',2)" value="dog" <?php if ($race=='dog') echo 'checked="checled"'; ?> /><label for="race2"><?php echo $bgm_race['dog']['name']; ?></label><br />
<input type="radio" name="race" id="race3" onclick="group_sel('race',3)" value="pwny" <?php if ($race=='pwny') echo 'checked="checled"'; ?> /><label for="race3"><?php echo $bgm_race['pwny']['name']; ?></label>
</td><th>
<span <?php if ($race!='anthro') echo 'style="display:none"'; ?> id="racepic0"><img src="images/anthro.png" alt="<?php echo $bgm_race['anthro']['name']; ?>" title="<?php echo $bgm_race['anthro']['name']; ?>" /></span>
<span <?php if ($race!='demi') echo 'style="display:none"'; ?> id="racepic1"><img src="images/demi.png" alt="<?php echo $bgm_race['demi']['name']; ?>" title="<?php echo $bgm_race['demi']['name']; ?>" /></span>
<span <?php if ($race!='dog') echo 'style="display:none"'; ?> id="racepic2"><img src="images/dog.png" alt="<?php echo $bgm_race['dog']['name']; ?>" title="<?php echo $bgm_race['dog']['name']; ?>" /></span>
<span <?php if ($race!='pwny') echo 'style="display:none"'; ?> id="racepic3"><img src="images/pwny.png" alt="<?php echo $bgm_race['pwny']['name']; ?>" title="<?php echo $bgm_race['pwny']['name']; ?>" /></span>
<span <?php if ($race!='') echo 'style="display:none"'; ?> id="racepic4"></span>
</th></tr></table>
<hr />
<span <?php if ($race!='anthro') echo 'style="display:none"'; ?> id="racedesc0"><span<?php if (array_key_exists('title',$reg_error)) echo ' class="error"'; ?>><b>Display:</b> Anthropomorphic <input type="text" name="title0" maxlength="32" value="<?php echo htmlspecialchars(stripslashes($title0)); ?>" /> Person</span><hr /><b>Description:</b> <?php echo $bgm_race['anthro']['desc']; ?></span>
<span <?php if ($race!='demi') echo 'style="display:none"'; ?> id="racedesc1"><span<?php if (array_key_exists('title',$reg_error)) echo ' class="error"'; ?>><b>Display:</b> Half <input type="text" name="title1" maxlength="32" value="<?php echo htmlspecialchars(stripslashes($title1)); ?>" /> Demi-Asian</span><hr /><b>Description:</b> <?php echo $bgm_race['demi']['desc']; ?></span>
<span <?php if ($race!='dog') echo 'style="display:none"'; ?> id="racedesc2"><span<?php if (array_key_exists('title',$reg_error)) echo ' class="error"'; ?>><b>Display:</b> Cybernetic Dog produced by <input type="text" name="title2" maxlength="32" value="<?php echo htmlspecialchars(stripslashes($title2)); ?>" /></span><hr /><b>Description:</b> <?php echo $bgm_race['dog']['desc']; ?></span>
<span <?php if ($race!='pwny') echo 'style="display:none"'; ?> id="racedesc3"><span<?php if (array_key_exists('title',$reg_error)) echo ' class="error"'; ?>><b>Display:</b> <input type="text" name="title3" maxlength="32" value="<?php echo htmlspecialchars(stripslashes($title3)); ?>" /> Little Pwny</span><hr /><b>Description:</b> <?php echo $bgm_race['pwny']['desc']; ?></span>
<span <?php if ($race!='') echo 'style="display:none"'; ?> id="racedesc4"><b>Description:</b> <i>select a race</i></span>
</div>


</td><td>&nbsp;</td><td class="reg" valign="top">


<h1<?php if (array_key_exists('class',$reg_error)) echo ' class="error"'; ?>>Class</h1><br />
<div><table class="regsec" cellspacing="0" cellpadding="0"><tr><td>
<input type="radio" name="class" id="class0" onclick="group_sel('class',0)" value="thief" <?php if ($class=='thief') echo 'checked="checled"'; ?> /><label for="class0"><?php echo $bgm_class['thief']['name']; ?></label><br />
<input type="radio" name="class" id="class1" onclick="group_sel('class',1)" value="assassin" <?php if ($class=='assassin') echo 'checked="checled"'; ?> /><label for="class1"><?php echo $bgm_class['assassin']['name']; ?></label><br />
<input type="radio" name="class" id="class2" onclick="group_sel('class',2)" value="salesperson" <?php if ($class=='salesperson') echo 'checked="checled"'; ?> /><label for="class2"><?php echo $bgm_class['salesperson']['name']; ?></label><br />
<input type="radio" name="class" id="class3" onclick="group_sel('class',3)" value="clown" <?php if ($class=='clown') echo 'checked="checled"'; ?> /><label for="class3"><?php echo $bgm_class['clown']['name']; ?></label>
</td><th>
<span <?php if ($class!='thief') echo 'style="display:none"'; ?> id="classpic0"><img src="images/thief.png" alt="<?php echo $bgm_class['thief']['name']; ?>" title="<?php echo $bgm_class['thief']['name']; ?>" /></span>
<span <?php if ($class!='assassin') echo 'style="display:none"'; ?> id="classpic1"><img src="images/assassin.png" alt="<?php echo $bgm_class['assassin']['name']; ?>" title="<?php echo $bgm_class['assassin']['name']; ?>" /></span>
<span <?php if ($class!='salesperson') echo 'style="display:none"'; ?> id="classpic2"><img src="images/salesperson.png" alt="<?php echo $bgm_class['salesperson']['name']; ?>" title="<?php echo $bgm_class['salesperson']['name']; ?>" /></span>
<span <?php if ($class!='clown') echo 'style="display:none"'; ?> id="classpic3"><img src="images/clown.png" alt="<?php echo $bgm_class['clown']['name']; ?>" title="<?php echo $bgm_class['clown']['name']; ?>" /></span>
<span <?php if ($class!='') echo 'style="display:none"'; ?> id="classpic4"></span>
</th></tr></table>
<hr />
<span <?php if ($class!='thief') echo 'style="display:none"'; ?> id="classdesc0"><b>Advances to:</b> <span class="statname">Pirate</span><hr /><b>Description:</b> <?php echo $bgm_class['thief']['desc']; ?></span>
<span <?php if ($class!='assassin') echo 'style="display:none"'; ?> id="classdesc1"><b>Advances to:</b> <span class="statname">Ninja</span><hr /><b>Description:</b> <?php echo $bgm_class['assassin']['desc']; ?></span>
<span <?php if ($class!='salesperson') echo 'style="display:none"'; ?> id="classdesc2"><b>Advances to:</b> <span class="statname">Telemarketer</span><hr /><b>Description:</b> <?php echo $bgm_class['salesperson']['desc']; ?></span>
<span <?php if ($class!='clown') echo 'style="display:none"'; ?> id="classdesc3"><b>Advances to:</b> <span class="statname">Jester</span><hr /><b>Description:</b> <?php echo $bgm_class['clown']['desc']; ?></span>
<span <?php if ($class!='') echo 'style="display:none"'; ?> id="classdesc4"><b>Description:</b> <i>select a class</i></span>
</div>


</td></tr><tr><td class="reg" valign="top">


<h1<?php if (array_key_exists('weapon',$reg_error)) echo ' class="error"'; ?>>Weapon</h1><br />
<div><table class="regsec" cellspacing="0" cellpadding="0"><tr><td>
<input type="radio" name="weapon" id="weapon0" onclick="group_sel('weapon',0)" value="sword" <?php if ($weapon=='sword') echo 'checked="checled"'; ?> /><label for="weapon0"><?php echo $bgm_weapon['sword']['name']; ?></label><br />
<input type="radio" name="weapon" id="weapon1" onclick="group_sel('weapon',1)" value="gunchuck" <?php if ($weapon=='gunchuck') echo 'checked="checled"'; ?> /><label for="weapon1"><?php echo $bgm_weapon['gunchuck']['name']; ?></label><br />
<input type="radio" name="weapon" id="weapon2" onclick="group_sel('weapon',2)" value="scissor" <?php if ($weapon=='scissor') echo 'checked="checled"'; ?> /><label for="weapon2"><?php echo $bgm_weapon['scissor']['name']; ?></label><br />
<input type="radio" name="weapon" id="weapon3" onclick="group_sel('weapon',3)" value="phone" <?php if ($weapon=='phone') echo 'checked="checled"'; ?> /><label for="weapon3"><?php echo $bgm_weapon['phone']['name']; ?></label>
</td><th>
<span <?php if ($class!='sword') echo 'style="display:none"'; ?> id="weaponpic0"><img src="images/sword.png" alt="<?php echo $bgm_weapon['sword']['name']; ?>" title="<?php echo $bgm_weapon['sword']['name']; ?>" /></span>
<span <?php if ($class!='gunchuck') echo 'style="display:none"'; ?> id="weaponpic1"><img src="images/gunchuck.png" alt="<?php echo $bgm_weapon['gunchuck']['name']; ?>" title="<?php echo $bgm_weapon['gunchuck']['name']; ?>" /></span>
<span <?php if ($class!='scissor') echo 'style="display:none"'; ?> id="weaponpic2"><img src="images/scissor.png" alt="<?php echo $bgm_weapon['scissor']['name']; ?>" title="<?php echo $bgm_weapon['scissor']['name']; ?>" /></span>
<span <?php if ($class!='phone') echo 'style="display:none"'; ?> id="weaponpic3"><img src="images/phone.png" alt="<?php echo $bgm_weapon['phone']['name']; ?>" title="<?php echo $bgm_weapon['phone']['name']; ?>" /></span>
<span <?php if ($class!='') echo 'style="display:none"'; ?> id="weaponpic4"></span>
</th></tr></table>
<hr />
<span <?php if ($weapon!='sword') echo 'style="display:none"'; ?> id="weapondesc0"><b>Description:</b> <?php echo $bgm_weapon['sword']['desc']; ?></span>
<span <?php if ($weapon!='gunchuck') echo 'style="display:none"'; ?> id="weapondesc1"><b>Description:</b> <?php echo $bgm_weapon['gunchuck']['desc']; ?></span>
<span <?php if ($weapon!='scissor') echo 'style="display:none"'; ?> id="weapondesc2"><b>Description:</b> <?php echo $bgm_weapon['scissor']['desc']; ?></span>
<span <?php if ($weapon!='phone') echo 'style="display:none"'; ?> id="weapondesc3"><b>Description:</b> <?php echo $bgm_weapon['phone']['desc']; ?></span>
<span <?php if ($weapon!='') echo 'style="display:none"'; ?> id="weapondesc4"><b>Description:</b> <i>select a weapon</i></span>
</div>


<input type="submit" value="Register" />

</td><td></td><td class="reg" valign="top">


<h1<?php if (array_key_exists('stats',$reg_error)) echo ' class="error"'; ?>>Stats</h1><br />
<div>
<table class="stats">
<tr<?php if (array_key_exists('strength',$reg_error)) echo ' class="error"'; ?>><th>Strength:</th><td><input type="text" id="stat1" onchange="update_stats()" name="str" size="2" maxlength="2" value="<?php echo $str; ?>" /></td>
<td rowspan="2"<?php if (array_key_exists('statamt',$reg_error)) echo ' class="error"'; ?>>Stat points must be between 5 and 20.</td></tr>
<tr<?php if (array_key_exists('agility',$reg_error)) echo ' class="error"'; ?>><th>Agility:</th><td><input type="text" name="agil" id="stat2" onchange="update_stats()" size="2" maxlength="2" value="<?php echo $agil; ?>" /></td></tr>
<tr<?php if (array_key_exists('intelligence',$reg_error)) echo ' class="error"'; ?>><th>Intelligence:</th><td><input type="text" name="intel" id="stat3" onchange="update_stats()" size="2" maxlength="2" value="<?php echo $intel; ?>" /></td>
<td rowspan="2"<?php if (array_key_exists('stattotal',$reg_error)) echo ' class="error"'; ?>>Stat point total must equal 40.  You have <span id="statleft">20</span> points remaining.</td></tr>
<tr<?php if (array_key_exists('luck',$reg_error)) echo ' class="error"'; ?>><th>Luck:</th><td><input type="text" name="luck" id="stat4" onchange="update_stats()" size="2" maxlength="2" value="<?php echo $luck; ?>" /></td></tr>
</table>
</div>

</td></tr></table>
</div></form>

<br />

<?php
  require('includes/footer.php');
?>