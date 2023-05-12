<?php
  /*******************************************************\
  *                                                       *
  *   Image Metabase™ Copyright © 2006 Vincent Milum Jr   *
  *   Version 0.2 (20061006)                              *
  *                                                       *
  *   user.php                                            *
  *                                                       *
  *   updates custom user status within database          *
  *                                                       *
  \*******************************************************/


  //do all custom $user init stuff here

  $user['user_theme'] = 0;
  $user['user_type']  = 'norm';



  function update_user(&$user, &$query) {
    global $time;

    $seconds = 120;
    $minutes = 15 * 60;

    if ($user['user_type'] === 'prem') {
      $seconds = 110;
      $minutes = 10 * 60;
    }

    if (($time - $seconds) > $user['user_last_turn']) {
      $gain = floor(($time - $user['user_last_turn']) / $seconds);
      $user['user_turns']   += $gain;
      $user['user_turns']    = min(2000, $user['user_turns']);
      $user['user_last_turn']    += $gain * $seconds;

      if ($user['user_bonus_turn'] > 0  &&  ($time - $minutes) > $user['user_bonus_last']  &&  $user['user_turns'] < 2000) {
        $gain_max     = min(2000 - $user['user_turns'], $user['user_bonus_turn']);
        $gain         = floor(($time - $user['user_bonus_last']) / $minutes);
        $user['user_bonus_last']  += $gain * $minutes;
        $gain         = min($gain, $gain_max);
        $user['user_turns']  += $gain;
        $user['user_bonus_turn'] -= $gain;

        if ($user['user_bonus_turn'] < 1) {
          $query .= ", `user_bonus_turn`='0', `user_bonus_last`='0'";
        } else {
          $query .= ", `user_bonus_turn`='" . $user['user_bonus_turn'] . "', `user_bonus_last`='" . $user['user_bonus_last'] . "'";
        }
      }

      $query .= ", `user_turns`='" . $user['user_turns'] . "', `user_last_turn`='" . $user['user_last_turn'] . "'";
    }
  }

?>