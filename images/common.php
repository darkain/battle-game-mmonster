<?php

  $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
  if ($id == 0) $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
  $id = mysql_safe($id);



   function get_stuff_int($name) {
     global $stuff;
     return $stuff[$name];
   }

   function set_stuff_int($name, $value) {
     global $db_stuff, $stuff;
     $stuff[$name] = $value;
     mysql_query("UPDATE `$db_stuff` SET `stuff_int`='$value' WHERE `stuff_name`='$name' LIMIT 1");
   }

   function increment_stuff($name, $value) {
     global $db_stuff, $stuff;
     $stuff[$name] += $value;
     mysql_query("UPDATE `$db_stuff` SET `stuff_int`=`stuff_int`+'$value' WHERE `stuff_name`='$name' LIMIT 1");
   }


  function display_race($race, $title, $link=false) {
    global $bgm_race;
    $ret = '';
    if ($link) $ret .= '<a href="races.php?type=' . $race . '">';
    $ret .= sprintf($bgm_race[$race]['disp'], $title);
    if ($link) $ret .= '</a>';
    return $ret;
  }


  function display_class($class, $link=false) {
    global $bgm_class;
    $ret = '';
    if ($link) $ret .= '<a href="classes.php?type=' . $class . '">';
    $ret .= $bgm_class[$class]['name'];
    if ($link) $ret .= '</a>';
    return $ret;
  }


  function display_weapon($weapon, $link=false) {
    global $bgm_weapon;
    $ret = '';
    if ($link) $ret .= '<a href="weapons.php?type=' . $weapon . '">';
    $ret .= $bgm_weapon[$weapon]['name'];
    if ($link) $ret .= '</a>';
    return $ret;
  }


  function display_race_pic($race) {
    global $bgm_race;
    $ret  = '<a href="races.php?type=';
    $ret .= $race;
    $ret .= '"><img src="images/';
    $ret .= $race;
    $ret .= '.png" alt="';
    $ret .= $bgm_race[$race]['name'];
    $ret .= '" title="';
    $ret .= $bgm_race[$race]['name'];
    $ret .= '" /></a>';
    return $ret;
  }


  function display_class_pic($class) {
    global $bgm_class;
    $ret  = '<a href="classes.php?type=';
    $ret .= $class;
    $ret .= '"><img src="images/';
    $ret .= $class;
    $ret .= '.png" alt="';
    $ret .= $bgm_class[$class]['name'];
    $ret .= '" title="';
    $ret .= $bgm_class[$class]['name'];
    $ret .= '" /></a>';
    return $ret;
  }


  function display_weapon_pic($weapon) {
    global $bgm_weapon;
    $ret  = '<a href="weapons.php?type=';
    $ret .= $weapon;
    $ret .= '"><img src="images/';
    $ret .= $weapon;
    $ret .= '.png" alt="';
    $ret .= $bgm_weapon[$weapon]['name'];
    $ret .= '" title="';
    $ret .= $bgm_weapon[$weapon]['name'];
    $ret .= '" /></a>';
    return $ret;
  }


  function display_user_pic($id) {
    if (file_exists("userpics/$id.png")) return '<img src="userpics/' . $id . '.png?x=' . filemtime("userpics/$id.png") . '" alt="Custom User Picture" title="Custom User Picture" />';
    if (file_exists("userpics/$id.gif")) return '<img src="userpics/' . $id . '.gif?x=' . filemtime("userpics/$id.gif") . '" alt="Custom User Picture" title="Custom User Picture" />';
    if (file_exists("userpics/$id.jpg")) return '<img src="userpics/' . $id . '.jpg?x=' . filemtime("userpics/$id.jpg") . '" alt="Custom User Picture" title="Custom User Picture" />';
    return '';
  }


  function display_hp($hp, $max, $style=0, $side=0, $id=0, $width=300) {
    $text = '<span class="hp ';
    if ($style == 1) $text .= 'blue1'; else $text .= 'red';
    $text .= '" style="width:' . $width . 'px"><span class="hp ';
    if ($style == 1) $text .= 'blue2'; else $text .= 'green';
    $text .= '" style="width:' . (round($hp / $max * $width)) . 'px';
    if ($side == 1) $text .= ';margin-left:' . ($width-(round($hp / $max * $width))) . 'px';
    $text .= '"';
    if ($id) $text .= ' id="' . $id . '"';
    $text .= '>&nbsp;</span></span>';
    return $text;
  }


  function display_hp2($hp, $max) {
    $text = '<span class="hp red" style="width:100%"><span class="hp green" style="width:' . (round($hp / $max * 100)) . '%">&nbsp;</span></span>';
    return $text;
  }



  function redirect_self() {
    global $PHPSELF, $id;
    if ($id > 0) {
      header("Location: $PHPSELF?id=$id");
    } else {
      header("Location: $PHPSELF");
    }
    exit;
  }


  function redirect_404() {
    header("Location: error404.php");
    exit;
  }


  // this function was taken from:  http://us2.php.net/manual/en/function.mysql-real-escape-string.php
  function mysql_safe($value) {
    $value = trim($value);
  
    // Stripslashes
    if (get_magic_quotes_gpc()) {
      $value = stripslashes($value);
    }
     
    // Quote if not a number or a numeric string
    if (!is_numeric($value)) {
      $value = mysql_real_escape_string($value);
    }
     
    return $value;
  }



  function time_since($original) {
    global $time;

    if ($original == 0) return "Never";

    // array of time period chunks
    $chunks = array(
      array(60 * 60 * 24 * 365 , 'year'),
      array(60 * 60 * 24 * 30 , 'month'),
      array(60 * 60 * 24 * 7, 'week'),
      array(60 * 60 * 24 , 'day'),
      array(60 * 60 , 'hour'),
      array(60 , 'minute'),
    );

    $today = $time; /* Current unix time in seconds  */
    $since = $today - $original;

    if ($since == 1) return '1 second';
    if ($since < 60) return "$since seconds";

    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        
      $seconds = $chunks[$i][0];
      $name = $chunks[$i][1];
        
      // finding the biggest chunk (if the chunk fits, break)
      if (($count = floor($since / $seconds)) != 0) {
        break;
      }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

    if ($i + 1 < $j) {
      // now getting the second item
      $seconds2 = $chunks[$i + 1][0];
      $name2 = $chunks[$i + 1][1];

      // add second item if it's count greater than 0
      if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
        $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
      }
    }

    return $print;
  }


  function time_count($original) {
    global $time;
    if ($original == 0) return 'Never';
    return time_since($time - $original);
  }



  function display_profile(&$prof, $prof_page=0) {
    global $user_id, $sort_type, $time, $stuff;
    global $bgm_race, $bgm_class, $bgm_weapon;

//    mysql_data_seek($prof_result, 0);
//    $prof = mysql_fetch_assoc($prof_result);

    $prof_id     = $prof['user_id'];
    $prof_name   = $prof['user_name'];
    $prof_age    = $prof['user_reg_date'];
    $prof_level  = $prof['user_level'];
    $prof_hp     = $prof['user_hp'];
    $prof_maxhp  = $prof['user_maxhp'];
    $prof_race   = $prof['user_race'];
    $prof_class  = $prof['user_class'];
    $prof_weapon = $prof['user_weapon'];
    $prof_title  = $prof['user_title'];
    $prof_type   = $prof['user_type'];
    $prof_fame   = $prof['user_fame'];
    $prof_bling  = $prof['user_bling'];
    $prof_kills  = $prof['user_kills'];
    $prof_deaths = $prof['user_deaths'];

    $prof_short  = $prof['user_top_shortest'];
    $prof_long   = $prof['user_top_longest'];
    $prof_top    = $prof['user_top_total'];
    $prof_since  = $prof['user_top_since'];

    $top_id      = get_stuff_int('toprank');

    if ($top_id == $prof_id) {
      $top_last = get_stuff_int('ranktime');
      $prof_top  += ($time - $top_last);
      
      $diff = $time - $top_last;
      if ($diff > 0) {
        $prof_short = ($prof_short==0) ? $diff : min($diff, $prof_short);
        $prof_long = max($diff, $prof_long);
      }
    }


    $prof_url    = '';
    if ($prof_page == 1) $prof_url = '&amp;id=' . $prof_id;

    echo "<table class=\"rank toprank\" cellspacing=\"0\">\n";

    echo '<tr';
    if ($user_id == $prof_id  &&  $prof_page == 0) echo ' class="rowx"';
    echo '><th>Name:</th><td colspan="4">';
    if ($prof_page == 1) {
      if ($user_id > 0  &&  $user_id != $prof_id) {
        echo '<a href="attack.php?id=' . $prof_id . '">' . $prof_name . '</a>';
      } else {
        echo $prof_name;
      }
    } else {
      echo '<a href="profile.php?id=' . $prof_id . '">' . $prof_name . '</a>';
    }
    echo ' is a <a href="races.php?type=' . $prof_race . '">';
    echo display_race($prof_race, $prof_title);
    echo '</a> who is a <a href="classes.php?type=' . $prof_class . '">';
    echo display_class($prof_class);
    echo '</a> that wields <a href="weapons.php?type=' . $prof_weapon . '">';
    echo display_weapon($prof_weapon);
    echo "</a></td></tr>\n";

    echo '<tr><th>HP:</th><td colspan="4">';
    if ($user_id != $prof_id) {
      echo display_hp($prof_hp, $prof_maxhp) . '<span class="hp hptext">' . round($prof_hp/$prof_maxhp*100) . "%</span>\n";
    } else {
      echo display_hp($prof_hp, $prof_maxhp) . '<span class="hp hptext">' . "$prof_hp / $prof_maxhp : " . round($prof_hp/$prof_maxhp*100) . "%</span>\n";
    }
    echo "</td></tr>\n";

    echo '<tr><th>Age:</th><td>' . time_since($prof_age) . "</td>\n";
    echo '<th style="text-align:center" colspan="2">Top Rank Info</th>' . "\n";
    echo "<td rowspan=\"5\" class=\"icons\">\n";

    if ($prof_type === 'prem') echo display_user_pic($prof_id) . "\n";
    echo display_race_pic($prof_race)     . "\n";
    echo display_class_pic($prof_class)   . "\n";
    echo display_weapon_pic($prof_weapon) . "\n";
    echo "</td></tr>\n";

    echo '<tr><th>Level:</th><td>' . $prof_level  . "</td>\n";
    echo '<th>Total:</th><td>' . time_count($prof_top) . "</td></tr>\n";

    echo '<tr><th>Fame:</th><td>' . $prof_fame . ' <span class="small">(' . round($prof_fame / $stuff['econfame'] * 100, 2) .  "%)</span></td>\n";
    echo '<th>Longest:</th><td>' . time_count($prof_long) . "</td></tr>\n";

    echo '<tr><th>Bling:</th><td>' . $prof_bling . ' <span class="small">(' . round($prof_bling / $stuff['econbling'] * 100, 2) .  "%)</span></td>\n";
    echo '<th>Shortest:</th><td>' . time_count($prof_short) . "</td></tr>\n";

    echo '<tr><th>Kills:</th><td>' . $prof_kills . ' <span class="small">(' . round($prof_kills / $stuff['econkills'] * 100, 2) .  "%)</span></td>\n";
    echo '<th>Last:</th><td>' . time_since($prof_since) . "</td></tr>\n";

    echo '<tr><th>Deaths:</th><td>' . $prof_deaths . ' <span class="small">(' . round($prof_deaths / $stuff['econkills'] * 100, 2) .  "%)</span></td>\n";
    if ($top_id == $prof_id) echo '<th>This:</th><td>' . time_count($diff) . '</td>';
    echo "</tr>\n";

    if ($user_id > 0  &&  $user_id != $prof_id) {
      echo "<tr><td colspan=\"2\" class=\"atkplr\"><a href=\"attack.php?id=$prof_id\" style=\"display:block\">Attack Player</a></td></tr>\n";
    }

    echo "</table>\n";
  }



?>