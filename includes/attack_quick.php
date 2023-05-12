<?php
  $render = round((microtime(true)-$utime), 2);

  if ($user_type === 'prem') {
    usleep(250000);
  } else {
    usleep(500000);
  }

  //user / attacker

  echo $user['user_last_attack'] . '~';
  echo "$attack_text~";

  echo $user['user_level'] . '~';

  if ($user['new_fame'] == 0) {
    echo $user['user_fame'] . ' : 0~';
  } else if ($user['new_fame'] < 0) {
    echo $user['user_fame'] . ' : <span class="lose">'  . $user['new_fame'] . '</span>~';
  } else {
    echo $user['user_fame'] . ' : <span class="gain">+' . $user['new_fame'] . '</span>~';
  }

  if ($user['new_bling'] == 0) {
    echo $user['user_bling'] . ' : 0~';
  } else if ($user['new_bling'] < 0) {
    echo $user['user_bling'] . ' : <span class="lose">'  . $user['new_bling'] . '</span>~';
  } else {
    echo $user['user_bling'] . ' : <span class="gain">+' . $user['new_bling'] . '</span>~';
  }

  echo $user['user_hp'] . ' / ' . $user['user_maxhp'] . ' : ' . round($user['user_hp'] / $user['user_maxhp'] * 100) . '%~';
  echo round($user['user_hp'] / $user['user_maxhp'] * 300) . '~';
  echo $user['user_exp'] . ' / ' . $user['user_exp_level'] . ' : ' . floor($user['user_exp'] / $user['user_exp_level'] * 100) . '%~';
  echo floor($user['user_exp'] / $user['user_exp_level'] * 300) . "~";



  //defender

  echo $defend['user_level'] . '~';

  if ($defend['new_fame'] == 0) {
    echo '0 : ' . $defend['user_fame'] . '~';
  } else if ($defend['new_fame'] < 0) {
    echo '<span class="lose">'  . $defend['new_fame'] . '</span> : ' . $defend['user_fame'] . '~';
  } else {
    echo '<span class="gain">+' . $defend['new_fame'] . '</span> : ' . $defend['user_fame'] . '~';
  }

  if ($defend['new_bling'] == 0) {
    echo '0 : ' . $defend['user_bling'] . '~';
  } else if ($defend['new_bling'] < 0) {
    echo '<span class="lose">'  . $defend['new_bling'] . '</span> : ' . $defend['user_bling'] . '~';
  } else {
    echo '<span class="gain">+' . $defend['new_bling'] . '</span> : ' . $defend['user_bling'] . '~';
  }

  echo round($defend['user_hp'] / $defend['user_maxhp'] * 100) . "%~";
  echo round($defend['user_hp'] / $defend['user_maxhp'] * 300) . "~";



  //other infos

  echo "$render~";
?>