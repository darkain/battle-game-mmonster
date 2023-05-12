<?php
  $nosession = true;
  require('includes/config.php');


  // read the post from PayPal system and add 'cmd'
  $req = 'cmd=_notify-validate';

  foreach ($_POST as $key => $value) {
    if (get_magic_quotes_gpc()) {
      $value = urlencode(stripslashes($value));
    } else {
      $value = urlencode($value);
    }
    $req .= "&$key=$value";
  }

  // post back to PayPal system to validate
  $header  = '';
  $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  $server  = 'www.paypal.com';
  //if (isset($_POST['test_ipn'])) $server = 'www.sandbox.paypal.com';
  $fp = fsockopen($server, 80, $errno, $errstr, 30);

  // assign posted variables to local variables
  $item_name        = isset($_POST['item_name'])      ? mysql_safe($_POST['item_name'])      : '';
  $user_id          = isset($_POST['item_number'])    ? mysql_safe($_POST['item_number'])    : '';
  $payment_status   = isset($_POST['payment_status']) ? mysql_safe($_POST['payment_status']) : '';
  $payment_amount   = isset($_POST['mc_gross'])       ? mysql_safe($_POST['mc_gross'])       : '';
  $payment_currency = isset($_POST['mc_currency'])    ? mysql_safe($_POST['mc_currency'])    : '';
  $paypal_id        = isset($_POST['txn_id'])         ? mysql_safe($_POST['txn_id'])         : '';
  $receiver_email   = isset($_POST['receiver_email']) ? mysql_safe($_POST['receiver_email']) : '';
  $paypal_email     = isset($_POST['payer_email'])    ? mysql_safe($_POST['payer_email'])    : '';

  $filename = md5(time() . microtime() . $user_id . $paypal_email);
  $thefile = fopen("logs/$filename.txt", 'w');
  fwrite($thefile, "$header$req\r\n\r\n");


  if (!$fp) {
    // HTTP ERROR
    fwrite($thefile, 'http error');
  } else {
    fputs($fp, $header . $req);
    while (!feof($fp)) {
      $res = fgets($fp, 1024);
      if (strcmp($res, "VERIFIED") == 0) {
        if (strcmp($payment_status, "Completed") == 0) {
          if (strcmp($receiver_email, "pkfk@hotmail.com") == 0  ||  strcmp($receiver_email, "darkain@darkain.com") == 0) {
            if (strcmp($payment_amount, "7.50") == 0) {
              if (strcmp($payment_currency, "USD") == 0) {
                $db_query  = "SELECT COUNT(*) FROM `$db_payment` WHERE `paypal_id`='$paypal_id'";
                $db_result = mysql_query($db_query);
                $db_count  = mysql_result($db_result, 0, 'COUNT(*)');
                mysql_free_result($db_result);

                if ($db_count == 0) {
                  $time = time();
                  mysql_query("INSERT INTO `$db_payment` (`user_id`, `payment_time`, `paypal_id`, `paypal_email`) VALUES ('$user_id', '$time', '$paypal_id', '$paypal_email')");
                  mysql_query("UPDATE `$db_users` SET `user_type`='prem' WHERE `user_id`='$user_id' LIMIT 1");
                } else {
                  fwrite($thefile, 'already exists: ' . $db_count);
                }
              } else {
                fwrite($thefile, 'wrong currency: ' . $payment_currency);
              }
            } else {
              fwrite($thefile, 'wrong amount: ' . $payment_amount);
            }
          } else {
            fwrite($thefile, 'invalid email: ' . $receiver_email);
          }
        } else {
          fwrite($thefile, 'not completed: ' . $payment_status);
        }
      } else if (strcmp ($res, "INVALID") == 0) {
        fwrite($thefile, 'not verified: ' . $res);
      }
    }
    fclose ($fp);
  }
  fclose($thefile);
?>
