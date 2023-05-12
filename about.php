<?php
  require('includes/config.php');

  $page_name = 'About';

  require('includes/header.php');
?>


<h1>About</h1><br /><div>
<span class="statname">Battle Game MMonster</span> is a <b>free</b> online text-based Player-vs-Player RPG clickfest!
Well, what does this mean, exactly? Players don't need to download anything to start playing.  If you can read this
page, then you already have everything you need to get started.  In this game, your character goes out to fight the
other characters of the world.  There are a total of over 100 possible combinations of character 
<a href="#races">races</a>, <a href="#classes">classes</a> and <a href="#weapons">weapons</a>.  See below for a
listing of each.
</div>


<h1>Game Mechanics</h1><br /><div>
Each character has 4 primary stats: <span class="statname">Strength</span>, <span class="statname">Agility</span>,
<span class="statname">Intelligence</span> and <span class="statname">Luck</span>.  Each attack takes one of these
stats from the attacker and one from the defender to determine damage.  An example might be that one attack uses the
attackers <span class="statname">Luck</span> against the defenders <span class="statname">Agility</span>.
<br /><br />
To keep things even and fair, each attack requires a specific number of
<span class="statname">Turns</span>.  Each user starts with the same number of <span class="statname">Turns</span>,
and <span class="statname">Turns</span> regenerate at the rate of 1 <span class="statname">Turn</span> every 120
seconds for a total of 720 <span class="statname">Turns</span> per day.  Each character may only store up to 2000
<span class="statname">Turns</span> total.  Premium users regenerate <span class="statname">Turns</span> at the
rate of 1 <span class="statname">Turn</span> every 110 seconds, for a total of 864
<span class="statname">Turns</span> per day.
</div>


<a id="screens"></a>
<h1>Screen Shots</h1><br /><div>
<table class="about" cellpadding="0"><tr>
<td><a href="images/ss1.png">Rank Page<br /><img src="images/ss1t.png" /></a></td>
<td><a href="images/ss2.png">Profile Page<br /><img src="images/ss2t.png" /></a></td>
<td><a href="images/ss3.png">Attack Page<br /><img src="images/ss3t.png" /></a></td>
<td><a href="images/ss4.png">Rank Display<br /><img src="images/ss4t.png" /></a></td>
</tr></table>
</div>


<a id="races"></a>
<h1>Races</h1><br /><div>
<table class="about" cellpadding="0"><tr>
<?php
  reset($bgm_race);
  while ($array = each($bgm_race)) {
    $name = $array[0];
    $race = $array[1];
    echo '<td><a href="races.php?type=' . $name . '">' . $race['name'] . '<br /><img src="images/' . $name . '.png" alt="' . $race['name'] . '" title="' . $race['name'] . '" /></a></td>' . "\n";
  } 
?>
</tr></table>
</div>


<a id="classes"></a>
<h1>Classes</h1><br /><div>
<table class="about" cellpadding="0"><tr>
<?php
  reset($bgm_class);
  while ($array = each($bgm_class)) {
    $name = $array[0];
    $class = $array[1];
    echo '<td><a href="classes.php?type=' . $name . '">' . $class['name'] . '<br /><img src="images/' . $name . '.png" alt="' . $class['name'] . '" title="' . $class['name'] . '" /></a></td>' . "\n";
    each($bgm_class);
  } 
?>
</tr><tr>
<?php
  reset($bgm_class);
  each($bgm_class);
  while ($array = each($bgm_class)) {
    $name  = $array[0];
    $class = $array[1];
    echo '<td><a href="classes.php?type=' . $name . '">' . $class['name'] . '<br /><img src="images/' . $name . '.png" alt="' . $class['name'] . '" title="' . $class['name'] . '" /></a></td>' . "\n";
    each($bgm_class);
  } 
?>
</tr></table>
</div>



<a id="weapons"></a>
<h1>Weapons</h1><br /><div>
<table class="about" cellpadding="0"><tr>
<?php
  reset($bgm_weapon);
  while ($array = each($bgm_weapon)) {
    $name   = $array[0];
    $weapon = $array[1];
    echo '<td><a href="weapons.php?type=' . $name . '">' . $weapon['name'] . '<br /><img src="images/' . $name . '.png" alt="' . $weapon['name'] . '" title="' . $weapon['name'] . '" /></a></td>' . "\n";
  } 
?>
</tr></table>
</div>


<?php
  require('includes/footer.php');
?>