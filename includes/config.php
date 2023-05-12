<?php
  /*******************************************************\
  *                                                       *
  *   Image Metabase™ Copyright © 2006 Vincent Milum Jr   *
  *   Version 0.2 (20061006)                              *
  *                                                       *
  *   config.php                                          *
  *                                                       *
  *   implementation specific configurations              *
  *                                                       *
  \*******************************************************/


  $utime       = microtime(true);

  $quick_lock  = FALSE;
//  $quick_lock  = TRUE;


  $time_start  = microtime(true);

  $site_name   = 'Battle Game MMonster';
  $site_keys   = 'Battle Game MMonster, online, web based, text adventure, pvp, player vs player, rpg, mmo, mmorpg, mulitplayer, online game, free, Anthropomorphic Person, Demi-Asian, Cybernetic Dog, Someone\'s Little Pwny, Thief, Assassin, Salesperson, Clown, Pirate, Ninja, Telemarketer, Jester, Really Big Sword, Gunchucks, Running Scissors, Giant 80\'s Cell Phone';

  $db_server   = 'localhost';
  $db_username = 'darkainc_darkain';
  $db_password = 'XXXXXX';
  $db_database = 'darkainc_main';
  $db_prefix   = 'bgm_';

  $db_stuff    = $db_prefix . 'stuff';
  $db_users    = $db_prefix . 'users';
  $db_session  = $db_prefix . 'sessions';
  $db_attack   = $db_prefix . 'attacks';
  $db_payment  = $db_prefix . 'payments';
  $db_display  = $db_prefix . 'display';


  $language    = 'en-us';
  

  if ($quick_lock) {
    echo "$site_name - Site is currently unavailable, please check back in a few minutes";
    exit;
  }

  @mysql_connect($db_server, $db_username, $db_password) or die('Database Error: ' . mysql_error());

  @mysql_select_db($db_database) or die('Database Error: ' . mysql_error());


  require('common.php');
  require('user.php');

  require('skills.php');
  require('columns.php');
  require('ranklist.php');

  if (!isset($nosession)) {
    require('session.php');
  }
?>