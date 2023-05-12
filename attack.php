<?php

  require('includes/config.php');
  require('includes/damage.php');


  if ( ($user_id == $id)  ||  ($user_id == 0)  ||  ($id == 0) ) {
    if (!$quick) {
      require('includes/header.php');
      require('includes/noattack.php');
      require('includes/footer.php');
    }
    exit;
  }


  mysql_query("LOCK TABLES `$db_users` WRITE, `bgm_users` u READ, `$db_attack` WRITE, `$db_stuff` WRITE, `$db_stuff` s READ");


  $quick  = (isset($_POST['quick']))  ? (mysql_safe($_POST['quick'])==='1') : false;
  $type   = (isset($_POST['type']))   ? (mysql_safe($_POST['type']))        : 0;
  $attack = (isset($_POST['attack'])) ? (mysql_safe($_POST['attack']))      : 0;
  $magic  = (isset($_POST['magic']))  ? (mysql_safe($_POST['magic']))       : 0;

  $crit_max         = 10;
  $attack_text      = '';


  $defend_query = "SELECT * FROM `$db_users` WHERE `user_id`='$id' LIMIT 1";
  $defend_result = mysql_query($defend_query);
  if (!$defend_result) redirect_404();
  if (mysql_numrows($defend_result) < 1) redirect_404();
  $defend = mysql_fetch_assoc($defend_result);
  mysql_free_result($defend_result);



  if ($attack >= 1  &&  $attack <= 2) {
    if ($type === 'class') {
      $use_attack = $bgm_class[$user['user_class']]["attack$attack"];
    } else if ($type === 'weapon') {
      $use_attack = $bgm_weapon[$user['user_weapon']]["attack$attack"];
    }
  }


  if (isset($use_attack)  &&  $user['user_last_attack'] == $magic  &&  $user['user_turns'] >= $use_attack['turn']) {
    $critical = mt_rand(1, $crit_max);

    $user['stat'] = $user['user_' . $use_attack['good']];
    if ($bgm_race[$user['user_race']]['good']     == $use_attack['good']) $user['stat'] *= 1.2;
    if ($bgm_race[$user['user_race']]['bad']      == $use_attack['good']) $user['stat'] *= 0.8;
    if ($bgm_class[$user['user_class']]['good']   == $use_attack['good']) $user['stat'] *= 1.2;
    if ($bgm_class[$user['user_class']]['bad']    == $use_attack['good']) $user['stat'] *= 0.8;
    if ($bgm_weapon[$user['user_weapon']]['good'] == $use_attack['good']) $user['stat'] *= 1.2;
    if ($bgm_weapon[$user['user_weapon']]['bad']  == $use_attack['good']) $user['stat'] *= 0.8;

    $defend['stat'] = $defend['user_' . $use_attack['bad']];
    if ($bgm_race[$defend['user_race']]['good']     == $use_attack['bad']) $defend['stat'] *= 1.2;
    if ($bgm_race[$defend['user_race']]['bad']      == $use_attack['bad']) $defend['stat'] *= 0.8;
    if ($bgm_class[$defend['user_class']]['good']   == $use_attack['bad']) $defend['stat'] *= 1.2;
    if ($bgm_class[$defend['user_class']]['bad']    == $use_attack['bad']) $defend['stat'] *= 0.8;
    if ($bgm_weapon[$defend['user_weapon']]['good'] == $use_attack['bad']) $defend['stat'] *= 1.2;
    if ($bgm_weapon[$defend['user_weapon']]['bad']  == $use_attack['bad']) $defend['stat'] *= 0.8;

    $user['update']   = '';
    $defend['update'] = '';

    if ($critical == 1) {  //counter
      do_damage($defend, $user, $critical, $use_attack);
    } else {
      do_damage($user, $defend, $critical, $use_attack);
    }

    $user['user_turns'] -= $use_attack['turn'];
    $user['update']     .= ", `user_turns`='" . $user['user_turns'] . "'";

    $user['user_last_attack'] = mt_rand();
    $user['update']     .= ", `user_last_attack`='" . $user['user_last_attack'] . "'";

    //update users table
    mysql_query("UPDATE `$db_users` SET `user_id`=`user_id` " . $user['update'] . " WHERE `user_id`='$user_id' LIMIT 1");
    mysql_query("UPDATE `$db_users` SET `user_id`=`user_id` " . $defend['update'] . " WHERE `user_id`='$id' LIMIT 1");


    //update attack table
    $atk_stealth = ($use_attack['type'] == 'stealth') ? '1' : '0';
    $atk_query   = "SELECT `user_id` FROM `$db_attack` WHERE `user_id`='$id' AND `attack_id`='$user_id' AND `attack_stealth`='$atk_stealth' LIMIT 1";
    $atk_result  = mysql_query($atk_query);
    $atk_count   = mysql_numrows($atk_result);
    mysql_free_result($atk_result);

    if ($atk_count > 0) {
      mysql_query("UPDATE `$db_attack` SET `attack_time`='$time' WHERE `user_id`='$id' AND `attack_id`='$user_id' AND `attack_stealth`='$atk_stealth' LIMIT 1");
    } else {
      $atk_query   = "SELECT * FROM `$db_attack` WHERE `user_id`='$id' ORDER BY `attack_time` DESC";
      $atk_result  = mysql_query($atk_query);
      $atk_count   = mysql_numrows($atk_result);

      if ($atk_count > 9) {
        $del_user    = mysql_result($atk_result, 9, 'user_id');
        $del_attack  = mysql_result($atk_result, 9, 'attack_id');
        $del_stealth = mysql_result($atk_result, 9, 'attack_stealth');
        mysql_query("DELETE FROM `$db_attack` WHERE `user_id`='$del_user' AND `attack_id`='$del_attack' AND `attack_stealth`='$del_stealth'");
      }

      mysql_query("INSERT INTO `$db_attack` (`user_id`, `attack_id`, `attack_time`, `attack_stealth`) VALUES ('$id', '$user_id', '$time', '$atk_stealth')");
      mysql_free_result($atk_result);
    }


    //check for #1 status
    $one_query  = "SELECT user_id, user_fame+user_bling AS user_rank FROM `$db_users` WHERE 1 ORDER BY user_fame+user_bling DESC LIMIT 1";
    $one_result = mysql_query($one_query);
    $one_id     = mysql_result($one_result, 0, 'user_id');
    $one_rank   = mysql_result($one_result, 0, 'user_rank');
    mysql_free_result($one_result);

    $top_query  = "SELECT u.user_id, u.user_fame+u.user_bling AS user_rank, u.user_top_shortest, u.user_top_longest FROM `$db_users` u, `$db_stuff` s WHERE s.stuff_name='toprank' AND s.stuff_int=u.user_id LIMIT 1";
    $top_result = mysql_query($top_query);
    $top_id     = mysql_result($top_result, 0, 'user_id');
    $top_rank   = mysql_result($top_result, 0, 'user_rank');
    $top_short  = mysql_result($top_result, 0, 'user_top_shortest');
    $top_long   = mysql_result($top_result, 0, 'user_top_longest');
    mysql_free_result($top_result);

    if ($one_id != $top_id) {
      $top_time  = $time - get_stuff_int('ranktime');
      if ($top_time > 0) {
        $top_short = ($top_short==0) ? $top_time : min($top_short, $top_time);
        $top_long  = max($top_long, $top_time);
      }
      mysql_query("UPDATE `$db_users` SET `user_top_total`=`user_top_total`+'$top_time', `user_top_shortest`='$top_short', `user_top_longest`='$top_long', `user_top_since`='$time', `user_top_count`=`user_top_count`+'1' WHERE `user_id`='$top_id' LIMIT 1");

      set_stuff_int('toprank',  $one_id);
      set_stuff_int('ranktime', $time);
    }

    increment_stuff('totalturn', $use_attack['turn']);
    increment_stuff('econfame',  $user['new_fame']  + $defend['new_fame']);
    increment_stuff('econbling', $user['new_bling'] + $defend['new_bling']);
  }


  mysql_query("UNLOCK TABLES");

  $attack_text .= " You have <b>" . $user['user_turns'] . "</b> turns left.<br />\n";

  if ($quick) {
    require('includes/attack_quick.php');
  } else {
    $page_name = 'Attack Player';
    require('includes/header.php');
    require('includes/attack_page.php');
    require('includes/footer.php');
  }
?>