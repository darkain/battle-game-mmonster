<?php
  require('includes/config.php');

  $page_name = 'Players';

  require('includes/header.php');
?>


<h1>Players</h1><br /><div class="rank">
<table class="rank">
<tr class="row1">
<td class="center"><a href="players.php?p=a">A</a></td>
<td class="center"><a href="players.php?p=b">B</a></td>
<td class="center"><a href="players.php?p=c">C</a></td>
<td class="center"><a href="players.php?p=d">D</a></td>
<td class="center"><a href="players.php?p=e">E</a></td>
<td class="center"><a href="players.php?p=f">F</a></td>
<td class="center"><a href="players.php?p=g">G</a></td>
<td class="center"><a href="players.php?p=h">H</a></td>
<td class="center"><a href="players.php?p=i">I</a></td>
<td class="center"><a href="players.php?p=j">J</a></td>
<td class="center"><a href="players.php?p=k">K</a></td>
<td class="center"><a href="players.php?p=l">L</a></td>
<td class="center"><a href="players.php?p=m">M</a></td>
<td class="center"><a href="players.php?p=n">N</a></td>
<td class="center"><a href="players.php?p=o">O</a></td>
<td class="center"><a href="players.php?p=p">P</a></td>
<td class="center"><a href="players.php?p=q">Q</a></td>
<td class="center"><a href="players.php?p=r">R</a></td>
<td class="center"><a href="players.php?p=s">S</a></td>
<td class="center"><a href="players.php?p=t">T</a></td>
<td class="center"><a href="players.php?p=u">U</a></td>
<td class="center"><a href="players.php?p=v">V</a></td>
<td class="center"><a href="players.php?p=w">W</a></td>
<td class="center"><a href="players.php?p=x">X</a></td>
<td class="center"><a href="players.php?p=y">Y</a></td>
<td class="center"><a href="players.php?p=z">Z</a></td>
</tr>
</table>


<table class="rank">

<?php
  $letter = (isset($_GET['p']))   ? mysql_safe($_GET['p']) : 'a';

  $db_query = "SELECT * FROM `$db_users` WHERE `user_name` LIKE '$letter%' ORDER BY `user_name` ASC";
  $db_result = mysql_query($db_query);
  $db_count  = mysql_numrows($db_result);

  display_ranks($db_result);

  mysql_free_result($db_result);
?>

</table></div>


<?php
  require('includes/footer.php');
?>