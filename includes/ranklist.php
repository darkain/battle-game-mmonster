<?php

  function display_ranks($db_result, $offset=1, $startrow=0, $headers=1) {
    global $user, $user_id, $db_display, $db_stuff, $db_users;
    global $bgm_columns, $time;


    $top_query  = "SELECT u.user_id, u.user_fame, u.user_bling FROM `$db_users` u, `$db_stuff` s WHERE s.stuff_name='toprank' AND s.stuff_int=u.user_id LIMIT 1";
    $top_result = mysql_query($top_query);
    $top_id     = mysql_result($top_result, 0, 'user_id');
    $top_fame   = mysql_result($top_result, 0, 'user_fame');
    $top_bling  = mysql_result($top_result, 0, 'user_bling');
    $top_rank   = $top_fame + $top_bling;
    mysql_free_result($top_result);


    $columns = array();
    $column_count = 0;
    if ($user_id > 0) {
      if ($user['user_type'] === 'prem') {
        $column_query  = "SELECT `column`, `align` FROM `$db_display` WHERE `user_id`='$user_id' ORDER BY `sort` ASC";
        $column_result = mysql_query($column_query);
        $column_count  = mysql_numrows($column_result);
        if ($column_count == 0) mysql_free_result($column_result);
      }
      if ($column_count == 0) {
        $column_query  = "SELECT `column`, `align` FROM `$db_display` WHERE `user_id`='-1' ORDER BY `sort` ASC";
        $column_result = mysql_query($column_query);
        $column_count  = mysql_numrows($column_result);
        if ($column_count == 0) mysql_free_result($column_result);
      }
    }
    if ($column_count == 0) {
      $column_query  = "SELECT `column`, `align` FROM `$db_display` WHERE `user_id`='0' ORDER BY `sort` ASC";
      $column_result = mysql_query($column_query);
      $column_count  = mysql_numrows($column_result);
    }
    for ($x=0; $x<$column_count; $x++) {
      $columns[$x][0] = mysql_result($column_result, $x, 0);
      $columns[$x][1] = mysql_result($column_result, $x, 1);
    }
    mysql_free_result($column_result);


    $econ_fame  = get_stuff_int('econfame');
    $econ_bling = get_stuff_int('econbling');

    if ($headers == 1) {
      echo "<tr class=\"rankheader\">\n";
      for ($x=0; $x<$column_count; $x++) {
        echo '<th>' . $bgm_columns[$columns[$x][0]]['disp'] . "</th>\n";
      }
      echo "</tr>\n";
    }


    $db_count = mysql_numrows($db_result);
    if ($db_count <= $startrow) return;
    mysql_data_seek($db_result, $startrow);

    for ($i=$startrow; $i<$db_count; $i++) {
      $data    = mysql_fetch_assoc($db_result);
      $db_id   = $data['user_id'];
      $stealth = (array_key_exists('attack_stealth', $data)  &&  $data['attack_stealth'] == '1');

      if ($user_id > 0 && $db_id == $user_id) {
        echo "<tr class=\"rowx\">\n";
      } else if ($i & 0x1 == 0x1) {
        echo "<tr class=\"row1\">\n";
      } else {
        echo "<tr class=\"row2\">\n";
      }

      for ($x=0; $x<$column_count; $x++) {
        $align = $columns[$x][1];

        if ($stealth) {
          if ($columns[$x][0] == 0) {
            echo '<th class="' . $align . '">' . ($i + $offset) . "</th>\n";
          } else if ($columns[$x][0] == 1) {
            echo '<td class="' . $align . '">' . 'Stealth' . "</td>\n";
          } else {
            echo "<td class=\"center\">?</td>\n";
          }
        }

        if (!$stealth) switch ($columns[$x][0]) {
          case 0: {
            echo '<th class="' . $align . '">' . ($i + $offset) . "</th>\n";
          } break;

          case 1: {
            if ($db_id == $user_id) {
              echo "<td>&nbsp;</td>\n";
            } else {
              echo '<td class="' . $align . '"><a href="attack.php?id=' . $db_id . '">Attack' . "</a></td>\n";
            }
          } break;

          case 2: {
            echo '<td class="' . $align . '">' . $db_id . "</td>\n";
          } break;

          case 3: {
            $db_name = $data['user_name'];
            echo '<td class="' . $align . '"><a href="profile.php?id=' . $db_id . '">' . $db_name . "</a></td>\n";
          } break;

          case 4: {
            $db_name = $data['user_name'];
            echo '<td class="' . $align . '"><a href="profile.php?id=' . $db_id . '">' . $db_name . ' / ' . $db_id . "</a></td>\n";
          } break;

          case 5: {
            $db_name = $data['user_name'];
            echo '<td class="' . $align . '"><a href="profile.php?id=' . $db_id . '">' . $db_id . ' / ' . $db_name . "</a></td>\n";
          } break;

          case 6: {
            $db_level = $data['user_level'];
            echo '<td class="' . $align . '">' . $db_level . "</td>\n";
          } break;

          case 7: {
            $db_name  = $data['user_name'];
            $db_level = $data['user_level'];
            echo '<td class="' . $align . '"><a href="profile.php?id=' . $db_id . '">' . $db_name . ' / ' . $db_level . "</a></td>\n";
          } break;

          case 8: {
            $db_name  = $data['user_name'];
            $db_level = $data['user_level'];
            echo '<td class="' . $align . '"><a href="profile.php?id=' . $db_id . '">' . $db_level . ' / ' . $db_name . "</a></td>\n";
          } break;

          case 9: {
            $db_name  = $data['user_name'];
            $db_level = $data['user_level'];
            echo '<td class="' . $align . '"><a href="profile.php?id=' . $db_id . '">' . $db_id . ' / ' . $db_name . ' / ' . $db_level . "</a></td>\n";
          } break;

          case 10: {
            $db_name  = $data['user_name'];
            $db_level = $data['user_level'];
            echo '<td class="' . $align . '"><a href="profile.php?id=' . $db_id . '">' . $db_level . ' / ' . $db_name . ' / ' . $db_id . "</a></td>\n";
          } break;

          case 11: {
            $db_hp  = $data['user_hp'];
            $db_max = $data['user_maxhp'];
            echo '<td class="' . $align . '">' . round($db_hp / $db_max * 100) . "%</td>\n";
          } break;

          case 12: {
            $db_hp  = $data['user_hp'];
            $db_max = $data['user_maxhp'];
            echo '<td class="rankhp" width="25%">' . display_hp2($db_hp, $db_max) . '<span class="hptext" style="text-align:' . $align . '">' . round($db_hp / $db_max * 100) . "%</span></td>\n";
          } break;

          case 13: {
            $db_race  = $data['user_race'];
            $db_title = $data['user_title'];
            echo '<td class="' . $align . '">' . display_race($db_race, $db_title, true) . "</td>\n";
          } break;

          case 14: {
            $db_class = $data['user_class'];
            echo '<td class="' . $align . '">' . display_class($db_class, true) . "</td>\n";
          } break;

          case 15: {
            $db_weapon = $data['user_weapon'];
            echo '<td class="' . $align . '">' . display_weapon($db_weapon, true) . "</td>\n";
          } break;

          case 16: {
            $db_fame = $data['user_fame'];
            echo '<td class="' . $align . '">' . $db_fame . "</td>\n";
          } break;

          case 17: {
            $db_fame = $data['user_fame'];
            echo '<td class="' . $align . '">' . ($db_fame >= $top_fame ? '+' : '') . ($db_fame-$top_fame) . "</td>\n";
          } break;

          case 18: {
            $db_fame = $data['user_fame'];
            echo '<td class="' . $align . '">' . $db_fame . '<span class="small"> (' . ($db_fame >= $top_fame ? '+' : '') . ($db_fame-$top_fame) . ")</span></td>\n";
          } break;

          case 19: {
            $db_fame = $data['user_fame'];
            echo '<td class="' . $align . '"><span class="small">(' . ($db_fame >= $top_fame ? '+' : '') . ($db_fame-$top_fame) . ') </span>' . $db_fame . "</td>\n";
          } break;

          case 20: {
            $db_fame = $data['user_fame'];
            echo '<td class="' . $align . '">' . round($db_fame / $econ_fame * 100, 2) . "%</td>\n";
          } break;

          case 21: {
            $db_bling = $data['user_bling'];
            echo '<td class="' . $align . '">' . $db_bling . "</td>\n";
          } break;

          case 22: {
            $db_bling = $data['user_bling'];
            echo '<td class="' . $align . '">' . ($db_bling >= $top_bling ? '+' : '') . ($db_bling-$top_bling) . "</td>\n";
          } break;

          case 23: {
            $db_bling = $data['user_bling'];
            echo '<td class="' . $align . '">' . $db_bling . '<span class="small"> (' . ($db_bling >= $top_bling ? '+' : '') . ($db_bling-$top_bling) . ")</span></td>\n";
          } break;

          case 24: {
            $db_bling = $data['user_bling'];
            echo '<td class="' . $align . '"><span class="small">(' . ($db_bling >= $top_bling ? '+' : '') . ($db_bling-$top_bling) . ') </span>' . $db_bling . "</td>\n";
          } break;

          case 25: {
            $db_bling = $data['user_bling'];
            echo '<td class="' . $align . '">' . round($db_bling / $econ_bling * 100, 2) . "%</td>\n";
          } break;

          case 26: {
            $db_fame  = $data['user_fame'];
            $db_bling = $data['user_bling'];
            echo '<td class="' . $align . '">' . round($db_fame / $db_bling * 100, 2) . "%</td>\n";
          } break;

          case 27: {
            $db_fame  = $data['user_fame'];
            $db_bling = $data['user_bling'];
            echo '<td class="' . $align . '">' . round($db_bling / $db_fame * 100, 2) . "%</td>\n";
          } break;

          case 28: {
            $db_rank = $data['user_fame'] + $data['user_bling'];
            echo '<td class="' . $align . '">' . $db_rank . "</td>\n";
          } break;

          case 29: {
            $db_rank = $data['user_fame'] + $data['user_bling'];
            echo '<td class="' . $align . '">' . ($db_rank >= $top_rank ? '+' : '') . ($db_rank-$top_rank) . "</td>\n";
          } break;

          case 30: {
            $db_rank = $data['user_fame'] + $data['user_bling'];
            echo '<td class="' . $align . '">' . $db_rank . '<span class="small"> (' . ($db_rank >= $top_rank ? '+' : '') . ($db_rank-$top_rank) . ")</span></td>\n";
          } break;

          case 31: {
            $db_rank = $data['user_fame'] + $data['user_bling'];
            echo '<td class="' . $align . '"><span class="small">(' . ($db_rank >= $top_rank ? '+' : '') . ($db_rank-$top_rank) . ') </span>' . $db_rank . "</td>\n";
          } break;

//32

          case 33: {
            $db_kills = $data['user_kills'];
            echo '<td class="' . $align . '">' . $db_kills . "</td>\n";
          } break;

          case 34: {
            $db_deaths = $data['user_deaths'];
            echo '<td class="' . $align . '">' . $db_deaths . "</td>\n";
          } break;

          case 35: {
            $db_kills = $data['user_kills'];
            $db_deaths = $data['user_deaths'];
            echo '<td class="' . $align . '">' . $db_kills . ' / ' . $db_deaths . "</td>\n";
          } break;

          case 36: {
            $db_kills  = $data['user_kills'];
            $db_deaths = $data['user_deaths'];
            $text = '0';
            if ($db_kills > 49  &&  $db_deaths > 49) $text = round($db_kills / $db_deaths * 100, 2);
            echo '<td class="' . $align . '">' . $text . "%</td>\n";
          } break;

          case 37: {
            $db_kills  = $data['user_kills'];
            $db_deaths = $data['user_deaths'];
            $text = '0';
            if ($db_kills > 49  &&  $db_deaths > 49) $text = round($db_kills / $db_deaths * 100, 2);
            echo '<td class="' . $align . '">' . $db_kills . ' / ' . $text . "%</td>\n";
          } break;

          case 38: {
            $db_kills  = $data['user_kills'];
            $db_deaths = $data['user_deaths'];
            $text = '0';
            if ($db_kills > 49  &&  $db_deaths > 49) $text = round($db_kills / $db_deaths * 100, 2);
            echo '<td class="' . $align . '">' . $db_deaths . ' / ' . $text . "%</td>\n";
          } break;

          case 39: {
            $db_kills  = $data['user_kills'];
            $db_deaths = $data['user_deaths'];
            $text = '0';
            if ($db_kills > 49  &&  $db_deaths > 49) $text = round($db_kills / $db_deaths * 100, 2);
            echo '<td class="' . $align . '">' . $db_kills . ' / ' . $db_deaths . ' / ' . $text . "%</td>\n";
          } break;

          case 40: {
            $db_age = $data['user_reg_date'];
            echo '<td class="' . $align . '">' . time_since($db_age) . "</td>\n";
          } break;

          case 41: {
            $db_type = $data['user_type'];
            echo '<td class="' . $align . '">' . ($db_type=='prem'?'Premium':'Normal') . "</td>\n";
          } break;

          case 42: {
            $db_idle = $data['user_last_login'];
            echo '<td class="' . $align . '">' . time_since($db_idle) . "</td>\n";
          } break;

//case 43: group

          case 44: {
            $db_top_total = $data['user_top_total'];
            if ($db_id == $top_id) $db_top_total += ($time - get_stuff_int('ranktime'));
            echo '<td class="' . $align . '">' . time_count($db_top_total) . "</td>\n";
          } break;

          case 45: {
            $db_top_longest = $data['user_top_longest'];
            if ($db_id == $top_id) $db_top_longest = max($db_top_longest, $time-get_stuff_int('ranktime'));
            echo '<td class="' . $align . '">' . time_count($db_top_longest) . "</td>\n";
          } break;

          case 46: {
            $db_top_shortest = $data['user_top_shortest'];
            if ($db_id == $top_id) $db_top_shortest = ($db_top_shortest==0) ? $time-get_stuff_int('ranktime') : min($db_top_shortest, $time-get_stuff_int('ranktime'));
            echo '<td class="' . $align . '">' . time_count($db_top_shortest) . "</td>\n";
          } break;

          case 47: {
            $db_top_total = $data['user_top_total'];
            $db_top_count = $data['user_top_count'];
            if ($db_id == $top_id) $db_top_total += ($time - get_stuff_int('ranktime'));
            if ($db_id == $top_id) $db_top_count++;
            $text = 'Never';
            if ($db_top_total > 0  &&  $db_top_count > 0) $text = time_count(round($db_top_total / $db_top_count, 2));
            echo '<td class="' . $align . '">' . $text . "</td>\n";
          } break;

          case 48: {
            $db_top_since = $data['user_top_since'];
            echo '<td class="' . $align . '">' . time_since($db_top_since) . "</td>\n";
          } break;

          case 49: {
            $db_top_count = $data['user_top_count'];
            if ($db_id == $top_id) $db_top_count++;
            echo '<td class="' . $align . '">' . $db_top_count . "</td>\n";
          } break;

          case 50: {
            $db_age = $data['user_reg_date'];
            $db_top = $data['user_top_total'];
            $text = 'Never';
            if ($db_age > 0  &&  $db_top > 0) $text = round($db_top / $db_age * 100, 2) . '%';
            echo '<td class="' . $align . '">' . $text . "</td>\n";
          } break;

          default: {
            echo '<td class="' . $align . '">TODO : ' . $columns[$x][0] . "</td>\n";
          }
        }
      }

      echo "</tr>\n";
    }
  }

?>