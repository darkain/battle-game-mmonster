<?php
  require('includes/config.php');
  if ($user_id == 0) redirect_404();
  if ($user_type !== 'prem') redirect_404();

  $page_name = 'Ranks Display';

  require('includes/header.php');


  $in_ali = (isset($_POST['ali'])) ? $_POST['ali'] : '';
  $in_col = (isset($_POST['col'])) ? $_POST['col'] : '';

  if (is_array($in_ali)  &&  is_array($in_col)) {
    mysql_query("LOCK TABLES `$db_display` WRITE");
    mysql_query("DELETE FROM `$db_display` WHERE `user_id`='$user_id'");

    $i = 0;
    while ($col = each($in_col)) {
      $i++;
      $row   = mysql_safe($col[0]);
      $id    = mysql_safe($col[1]);

      $align = 'left';
      if (isset($in_ali[$row])) {
        if ($in_ali[$row] === 'Center') $align='center';
        if ($in_ali[$row] === 'Right' ) $align='right';
      }

      mysql_query("INSERT INTO `$db_display` (`user_id`,`sort`,`column`,`align`) VALUES('$user_id', '$i', '$id', '$align') ");
    }

    $db_ok = 'Ranks display successfully updated';
    mysql_query("UNLOCK TABLES");
  }



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


  if (isset($err_name)) echo "<span class=\"error\">$err_name</span><br /><br />\n\n";
  if (isset($db_ok)) echo "<span class=\"good\">$db_ok</span><br /><br />\n\n";
?>


<script type="text/javascript"><!--
  function get_count() {
    var i = 1;
    while (document.getElementById('rank' + i)) i++;
    return i;
  }

  function update_display() {
    var cnt = get_count();
    if (cnt == 1) return;
    document.getElementById('rankup1').disabled = true;
    document.getElementById('rankdn'+(cnt-1)).disabled = true;
    if (cnt > 2) document.getElementById('rankdn'+(cnt-2)).disabled = false;
  }

  function move_up(id) {
    if (id < 2) return;
    var index1 = document.getElementById('ali'+(id-0)).selectedIndex;
    var index2 = document.getElementById('ali'+(id-1)).selectedIndex;
    var temp = document.getElementById('ranktxt' + id).innerHTML;
    document.getElementById('ranktxt'+(id-0)).innerHTML = document.getElementById('ranktxt'+(id-1)).innerHTML;
    document.getElementById('ranktxt'+(id-1)).innerHTML = temp;
    document.getElementById('ali'+(id-0)).selectedIndex = index2;
    document.getElementById('ali'+(id-1)).selectedIndex = index1;
  }

  function move_down(id) {
    if (id > get_count()-2) return;
    var index1 = document.getElementById('ali'+(id+0)).selectedIndex;
    var index2 = document.getElementById('ali'+(id+1)).selectedIndex;
    var temp = document.getElementById('ranktxt' + id).innerHTML;
    document.getElementById('ranktxt'+(id+0)).innerHTML = document.getElementById('ranktxt'+(id+1)).innerHTML;
    document.getElementById('ranktxt'+(id+1)).innerHTML = temp;
    document.getElementById('ali'+(id+0)).selectedIndex = index2;
    document.getElementById('ali'+(id+1)).selectedIndex = index1;
  }

  function remove(id) {
    var cnt = get_count();
    for (var i=id; i<cnt-1; i++) {
      var index = document.getElementById('ali'+(i+1)).selectedIndex;
      document.getElementById('ranktxt'+i).innerHTML = document.getElementById('ranktxt'+(i+1)).innerHTML;
      document.getElementById('ali'+i).selectedIndex = index;
    }
    var parent  = document.getElementById('ranklist');
    var element = document.getElementById('rank' + (cnt-1));
    parent.removeChild(element);

    update_display();
  }

  function add_line() {
    var index = document.getElementById('tabs').selectedIndex;
    if (index < 0) return;
    var text  = document.getElementById('tabs').options[index].text;
    var value = document.getElementById('tabs').options[index].value;

    var cnt    = get_count();
    var newdiv = document.createElement('div');
    newdiv.setAttribute('id', 'rank' + cnt);

    newdiv.innerHTML = '<input type="button" value="Remove" onclick="remove(' + cnt + ')" /> <input type="button" value="Up" id="rankup' + cnt + '" onclick="move_up(' + cnt + ')" /> <input type="button" value="Down" id="rankdn' + cnt + '" onclick="move_down(' + cnt + ')" /> Align: <select name="ali[]" id="ali' + cnt + '"><option>Left</option><option>Center</option><option>Right</option></select> <span id="ranktxt' + cnt + '">' + text + '<input type="hidden" name="col[]" value="' + value + '" /></span>';

    var parent = document.getElementById('ranklist');
    parent.appendChild(newdiv);

    update_display();
  }
//--></script>


<h1>Ranks Display</h1><br />
<form action="display.php" method="post"><div>
<table class="stats"><tr><td class="center" style="padding-right:1em">

<select id="tabs" size="20" ondblclick="add_line()">
<?php
  reset($bgm_columns);
  while ($col = each($bgm_columns)) {
    echo '<option value="' . $col[0] . '">' . $col[1]['name'] . "</option>\n";
  }
?>
</select><br />
<input type="button" value="Add Selected" onclick="add_line()" /><br /><br />
<input type="submit" value="Submit Changes" />

</td><td id="ranklist">

<?php
  for ($i=0; $i<$column_count; $i++) {
    echo '<div id="rank' . ($i+1) . '"><input type="button" value="Remove" onclick="remove(' . ($i+1) . ')" /> <input type="button" value="Up" id="rankup' . ($i+1) . '" onclick="move_up(' . ($i+1) . ')" /> <input type="button" value="Down" id="rankdn' . ($i+1) . '" onclick="move_down(' . ($i+1) . ')" /> Align: <select name="ali[]" id="ali' . ($i+1) . '"><option>Left</option><option' . ($columns[$i][1]=='center'?' selected="selected"':'') . '>Center</option><option' . ($columns[$i][1]=='right'?' selected="selected"':'') . '>Right</option></select> <span id="ranktxt' . ($i+1) . '">' . $bgm_columns[$columns[$i][0]]['name'] . '<input type="hidden" name="col[]" value="' . $columns[$i][0] . '" /></span></div>';
  }
?>

</td></tr></table>

</div>
</form>


<script type="text/javascript"><!--
  update_display();
//--></script>


<?php
  require('includes/footer.php');
?>