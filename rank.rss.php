<?php
  $nosession = true;
  require('includes/config.php');

  header('Content-type: application/xml');
  echo "<?xml version='1.0' encoding='utf-8' ?>\n";
?>

<rss version="2.0">
  <channel>
    <title>Top Ranks - Battle Game MMonster</title>
    <link>http://battle.darkain.com/rank.php</link>
    <description>Top ranking players in the online game Battle Game MMonster</description>
    <language>en-us</language>
    <pubDate><?php echo date('r'); ?></pubDate>
    <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
    <docs>http://blogs.law.harvard.edu/tech/rss</docs>
    <generator>Battle Game MMonster</generator>
    <managingEditor>darkain@darkain.com</managingEditor>
    <webMaster>darkain@darkain.com</webMaster>

<?php
  $db_query  = "SELECT * FROM `$db_users` WHERE 1 ORDER BY (`user_fame`+`user_bling`) DESC LIMIT 10";
  $db_result = mysql_query($db_query);
  $db_count  = mysql_numrows($db_result);

  for ($i=0; $i<$db_count; $i++) {
    $data = mysql_fetch_assoc($db_result);
    echo "    <item>\n";
    echo "      <title>" . $data['user_name'] . " (" . $data['user_id'] . ")</title>\n";
    echo "      <link>http://battle.darkain.com/profile.php?id=" . $data['user_id'] . "</link>\n";
    echo "      <description>";
    echo "Fame: "   . $data['user_fame']  . " | ";
    echo "Bling: "  . $data['user_bling'] . " | ";
    echo "HP: "     . round($data['user_hp'] / $data['user_maxhp'] * 100) . "% | ";
    echo "Level: "  . $data['user_level'] . " | ";
    echo "Kills: "  . $data['user_kills'] . " | ";
    echo "Deaths: " . $data['user_deaths'];
    echo "</description>\n";
    echo "      <pubDate>" . date('r', (time()-$i)) . "</pubDate>\n";
    echo "      <guid>" . (time()-$i) . ':' . $data['user_id'] . "</guid>\n";
    echo "    </item>\n";
  }

  mysql_free_result($db_result);
?>

  </channel>
</rss>