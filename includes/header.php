<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title><?php echo (isset($page_name)) ? "$page_name - $site_name" : "$site_name"; ?></title>
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="language" content="en-us" />
<meta name="generator" content="PHP5" />
<meta name="doc-class" content="Completed" />
<meta name="rating" content="general" />
<meta name="keywords" content="<?php echo (isset($page_keys)) ? "$site_keys, $page_keys" : "$site_keys"; ?>" />
<script type="text/javascript" src="scripts.js"></script>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<?php if ($user_theme !== '0') echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style_$user_theme.css\" />\n"; ?>
<!--[if lte IE 6]><style type="text/css">@import url('IE60Fixes.css');</style><![endif]-->
<?php if (isset($more_header)) echo $more_header . "\n"; ?>
</head>
<body>

<div class="title"><a href="index.php"><span>B</span>attle <span>G</span>ame <span>MMo</span>nster</a></div>

<div class="nav">
<span class="navleft">
<a href="about.php">About</a> : 
<a href="players.php">Players</a> : 
<a href="rank.php">Ranks</a>
</span>

<span class="navright"><?php
  if ($user_id == 0) {
    echo '<a href="index.php">Login</a> : <a href="register.php">Register</a>';
  } else {
    echo "<a href=\"profile.php?id=$user_id\">$user_name</a> : <a href=\"index.php?logout=1\">Logout</a>";
  }
?></span>
</div>

<div id="body"><br />

<?php if ($user_type !== 'prem') { ?><div id="google">
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div><?php } ?>
