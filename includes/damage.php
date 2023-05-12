<?php
  function do_damage(&$attack, &$defend, $critical, &$use_attack) {
    global $crit_max, $bgm_class, $attack_text;

    $level_ratio = 1;
    if ($defend['user_level'] == $attack['user_level']) {
      $level_ratio   = 1;
    } else if ($defend['user_level'] > $attack['user_level']) {
      $level_ratio   = ($attack['user_level'] +  4) / ($defend['user_level'] +  3);
    } else {
      $level_ratio   = ($defend['user_level'] +  4) / ($attack['user_level'] +  3);
    }

    $level_ratio   = max(0.1, min($level_ratio, 1.0)) * 2;

    $stat_ratio    = ($attack['stat'] / $defend['stat']);
    $stat_ratio    = max(0.1, min($stat_ratio, 5.0));

    $damage = ($stat_ratio) * ((mt_rand(80000,120000)/100000) * ($level_ratio) * ($attack['user_level'] / 3));

    if ($critical == $crit_max) $damage *= 2;
    if ($use_attack['type']   == 'damage')  $damage *= 3;
    if ($use_attack['type']   == 'fame')    $damage *= 2;
    if ($use_attack['type']   == 'bling')   $damage *= 2;
    if ($use_attack['type']   == 'stealth') $damage *= 2.5;
    if ($attack['user_level'] >= 30)        $damage *= 1.25;
    $damage = max(1, round($damage));


    if ($critical == 1) {
      $attack_text = "You miss and are countered for <b>$damage</b> damage";
    } else if ($critical == $crit_max) {
      $attack_text = "You strike a critical blow on the defender for <b>$damage</b> damage";
    } else {
      $attack_text = "You inflict <b>$damage</b> damage on the defender";
    }


    $defend['user_hp'] -= $damage;
    $attack_kill = false;

    if ($defend['user_hp'] < 1) {
      $defend['user_hp'] = $defend['user_maxhp'];
      $attack_kill = true;
      $attack_text .= ' for the kill';


      $new_exp = 0;
      if ($attack['user_level'] == $defend['user_level']) {
        $new_exp = $attack['user_level'];
      } else if ($attack['user_level'] > $defend['user_level']) {
        $new_exp = $defend['user_level'] / $attack['user_level'] * $defend['user_level'];
      } else if ($attack['user_level'] + 3 > $defend['user_level']) {
        $new_exp = ($defend['user_level'] - 1) / $attack['user_level'] * $defend['user_level'];
      } else {
        $new_exp = ($attack['user_level'] + 5) / $defend['user_level'] * $attack['user_level'];
      }

      $new_exp *= (mt_rand(80000,120000)/100000);
      if ($attack['user_level'] < 10) $new_exp = max($new_exp, 1);
      $attack['user_exp'] += round($new_exp);


      $new_level = false;
      while ($attack['user_exp'] >= $attack['user_exp_level']) {
        $new_level                    = true;
        $attack['user_level']        ++;
        $attack['user_exp']          -= $attack['user_exp_level'];
        if ($attack['user_level'] >= 20) {
          $attack['user_exp_level']     = round( 3 * (($attack['user_level']+3) * ($attack['user_level']+3)) );
        } else {
          $attack['user_exp_level']     = round( 2 * (($attack['user_level']+1) * ($attack['user_level']+1)) );
        }
        $attack['user_maxhp']        += max(1, round($attack['user_base_hp']           * $attack['user_level'] / 10));
        $attack['user_strength']     += max(1, round($attack['user_base_strength']     * $attack['user_level'] / 10));
        $attack['user_agility']      += max(1, round($attack['user_base_agility']      * $attack['user_level'] / 10));
        $attack['user_intelligence'] += max(1, round($attack['user_base_intelligence'] * $attack['user_level'] / 10));
        $attack['user_luck']         += max(1, round($attack['user_base_luck']         * $attack['user_level'] / 10));
        $attack['user_hp']            = $attack['user_maxhp'];


        if ($attack['user_level'] == 30) {
          $attack['user_class'] = $bgm_class[$attack['user_class']]['adv'];
          $attack['update'] .= ", `user_class`='" . $attack['user_class'] . "'";
        }
      }

      if ($new_level === true) {
        $attack['update'] .= ", `user_level`='"        . $attack['user_level']        . "'";
        $attack['update'] .= ", `user_exp_level`='"    . $attack['user_exp_level']    . "'";
        $attack['update'] .= ", `user_maxhp`='"        . $attack['user_maxhp']        . "'";
        $attack['update'] .= ", `user_hp`='"           . $attack['user_maxhp']        . "'";
        $attack['update'] .= ", `user_strength`='"     . $attack['user_strength']     . "'";
        $attack['update'] .= ", `user_agility`='"      . $attack['user_agility']      . "'";
        $attack['update'] .= ", `user_intelligence`='" . $attack['user_intelligence'] . "'";
        $attack['update'] .= ", `user_luck`='"         . $attack['user_luck']         . "'";
      }

      $attack['update'] .= ", `user_exp`='" . $attack['user_exp'] . "'";
      $attack['update'] .= ", `user_kills`=(`user_kills`+1)";

      $defend['update'] .= ", `user_deaths`=(`user_deaths`+1)";
    }


    {
      $attack_fame   = $attack['user_fame'];
      $defend_fame   = $defend['user_fame'];
      $fame_ratio    = ($defend_fame  + 100) / ($attack_fame  + 100);
      $fame_ratio    = max(0.1, min($fame_ratio,  1.5)) / 2;
      if ($critical == $crit_max) $fame_ratio  *= 2;
      $fame_change   = $level_ratio * $fame_ratio  * (mt_rand(80000,120000)/100000);
      $fame_mod      = 1;
      if ($use_attack['type']   == 'fame')    $fame_mod = 5;
      if ($use_attack['type']   == 'bling')   $fame_mod = 2;
      if ($use_attack['type']   == 'damage')  $fame_mod = 1.5;
      if ($use_attack['type']   == 'stealth') $fame_mod = 2;
      if ($attack['user_level'] >= 30) $fame_mod *= 1.25;
      $attack_fc     = max(1, round($fame_change * $attack['user_level'] * $fame_mod));
      $defend_fc     = 0 - max(1, round($fame_change * $defend['user_level'] * $fame_mod * 2 / 3));
      if ($attack_fame + $attack_fc < 50) $attack_fc = $attack_fame - 50;
      if ($defend_fame + $defend_fc < 50) $defend_fc = $defend_fame - 50;
      $attack_fame  += $attack_fc;
      $defend_fame  += $defend_fc;

      $attack['update']   .= ", `user_fame`='$attack_fame'";
      $attack['user_fame'] = $attack_fame;
      $attack['new_fame']  = $attack_fc;

      $defend['update']   .= ", `user_fame`='$defend_fame'";
      $defend['user_fame'] = $defend_fame;
      $defend['new_fame']  = $defend_fc;
    }

    {
      $attack_bling  = $attack['user_bling'];
      $defend_bling  = $defend['user_bling'];
      $bling_ratio   = ($defend_bling + 100) / ($attack_bling + 100);
      $bling_ratio   = max(0.1, min($bling_ratio, 1.5)) / 2;
      if ($attack_kill == true)   $bling_ratio *= 2;
      $bling_change  = $level_ratio * $bling_ratio * (mt_rand(80000,120000)/100000);
      $bling_mod     = 1;
      if ($use_attack['type']   == 'fame')    $bling_mod = 2;
      if ($use_attack['type']   == 'bling')   $bling_mod = 5;
      if ($use_attack['type']   == 'damage')  $bling_mod = 1.5;
      if ($use_attack['type']   == 'stealth') $bling_mod = 2;
      if ($attack['user_level'] >= 30) $bling_mod *= 1.25;
      $attack_bc     = max(1, round($bling_change * $attack['user_level'] * $bling_mod));
      $defend_bc     = 0 - max(1, round($bling_change * $defend['user_level'] * $bling_mod * 2 / 3));
      if ($attack_bling + $attack_bc < 50) $attack_bc = $attack_bling - 50;
      if ($defend_bling + $defend_bc < 50) $defend_bc = $defend_bling - 50;
      $attack_bling += $attack_bc;
      $defend_bling += $defend_bc;

      $attack['update']    .= ", `user_bling`='$attack_bling'";
      $attack['user_bling'] = $attack_bling;
      $attack['new_bling']  = $attack_bc;

      $defend['update']    .= ", `user_bling`='$defend_bling'";
      $defend['user_bling'] = $defend_bling;
      $defend['new_bling']  = $defend_bc;
    }


    $defend['update'] .= ", `user_hp`='" . $defend['user_hp'] . "'";

    $attack_text .= '.';
  }
?>