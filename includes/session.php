<?php
  /*******************************************************\
  *                                                       *
  *   Image Metabase™ Copyright © 2006 Vincent Milum Jr   *
  *   Version 0.2 (20061006)                              *
  *                                                       *
  *   session.php                                         *
  *                                                       *
  *   handles browser sessions and user login             *
  *                                                       *
  \*******************************************************/


  sscanf($_SERVER["REMOTE_ADDR"], "%d.%d.%d.%d", $ip0, $ip1, $ip2, $ip3);
  $user['ip']        = ($ip0 << 24) | ($ip1 << 16) | ($ip2 << 8) | ($ip3);

  $user['user_id']   =  0;
  $user['user_name'] = '';
  $user['auth']      = 'guest';
  $user['referer']   = (isset($_GET['referer'])) ? ((int)mysql_safe($_GET['referer'])) : 0;
  $user['agent']     =  mysql_safe($_SERVER['HTTP_USER_AGENT']);


  ini_set('session.use_cookies',      TRUE);
  ini_set('session.use_only_cookies', TRUE);
  ini_set('session.cookie_lifetime',  $stuff['session_timeout']);
  ini_set('session.cache_limite',     'private');


  session_start();
  $user['session'] = mysql_safe(session_id());
  $stuff['session_expire'] = $time - $stuff['session_timeout'];
  session_write_close();


 
  mysql_query("LOCK TABLES `$db_session` WRITE, `$db_users` WRITE");

  if (mt_rand(1,1000) == 1) {  //we are the lucky winner of the database cleanup code
    $delete_time = ($time - $stuff['sesdion_timeout']);
    mysql_query("DELETE FROM `$db_session` WHERE `ses_timeout` < '$delete_time'");
  }


  $db_query  = "SELECT `ses_timeout`, `user_id`, `ses_referer` FROM `$db_session` WHERE `ses_id`='" . $user['session'] . "' LIMIT 1";
  $db_result = mysql_query($db_query);
  $db_count  = mysql_numrows($db_result);

  if ($db_count > 0) {
    $ses_end = mysql_result($db_result, 0, 'ses_timeout');
    if ($stuff['session_expire'] < $ses_end  ||  isset($_GET['logout'])) {
      mysql_query("DELETE FROM `$db_session` WHERE `ses_id`='" . $user['session'] . "' LIMIT 1");
    } else {
      $user['user_id']  = mysql_result($db_result, 0, 'user_id');
      $ses_referer      = mysql_result($db_result, 0, 'ses_referer');
      mysql_query("UPDATE `$db_session` SET `ses_timeout`='" . $stuff['session_expire'] . "' WHERE `ses_id`='" . $user['session'] . "' LIMIT 1");
    }
  } else if ($user['referer'] !== 0) {
    mysql_query("INSERT INTO `$db_session` (`ses_id`, `ses_ip`, `user_id`, `ses_timeout`, `ses_referer`) VALUES ('" . $user['session'] . "', '" . $user['ip'] . "', '0', '" . $stuff['session_expire'] . "', '" . $user['referer'] . "')");
  }
  mysql_free_result($db_result);


  if ($user['user_id'] > 0) {
    $db_query  = "SELECT * FROM `$db_users` WHERE user_id='" . $user['user_id'] . "' LIMIT 1";
    $db_result = mysql_query($db_query);
    $user      = array_merge($user, mysql_fetch_assoc($db_result));
    mysql_free_result($db_result);

    $user['referer'] = 0;
    $update_query    = "`user_last_login`='$time'";

    update_user($user, $update_query);

    mysql_query("UPDATE `$db_users` SET $update_query WHERE `user_id`='" . $user['user_id'] . "' LIMIT 1");

  }

  mysql_query("UNLOCK TABLES");



  function process_login($username, $password) {
    global $user, $stuff, $db_users, $db_session;
    $password = md5($password);

    mysql_query("LOCK TABLES `$db_session` WRITE, `$db_users` READ");

    $db_query  = "SELECT * FROM `$db_users` WHERE `user_name`='$username' AND `user_pass`='$password' LIMIT 1";
    $db_result = mysql_query($db_query);
    $db_count  = mysql_numrows($db_result);

    if ($db_count > 0) {
      $ret = true;

      $user = array_merge($user, mysql_fetch_assoc($db_result));

      //if successful
      $ses_result = mysql_query("SELECT COUNT(*) FROM `$db_session` WHERE `ses_id`='" . $user['session'] . "' LIMIT 1");
      $ses_count  = mysql_result($ses_result, 0, 'COUNT(*)');
      mysql_free_result($ses_result);

      if ($ses_count != 0) {
        mysql_query("UPDATE `$db_session` SET `user_id`='" . $user['user_id'] . "', `ses_referer`='0' WHERE `ses_id`='" . $user['session'] . "' LIMIT 1");
      } else {
        mysql_query("INSERT INTO `$db_session` (`ses_id`, `ses_ip`, `user_id`, `ses_timeout`, `ses_referer`) VALUES ('" . $user['session'] . "', '" . $user['ip'] . "', '" . $user['user_id'] . "', '" . $stuff['session_expire'] . "', '" . $user['referer'] . "')");
      }

    } else {
      $user['user_id'] = 0;
      $ret = 'Invalid username or password';
    }

    mysql_free_result($db_result);
    mysql_query("UNLOCK TABLES");

    return $ret;
  }
?>