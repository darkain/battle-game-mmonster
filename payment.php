<?php
  require('includes/config.php');

  $page_name = 'Payment Status';

  require('includes/header.php');

  echo "<h1>Payment Status</h1><br /><div>\n";  

  $payment_status = isset($_POST['payment_status']) ? mysql_safe($_POST['payment_status']) : 'Unknown';
  if ($payment_status === 'Completed') {
    echo 'Thank you for your payment. Your transaction has been <span class="statname">Completed</span>, and a receipt for your purchase has been emailed to you. You may log into your PayPal account at <a href="http://www.paypal.com/us">www.paypal.com/us</a> to view details of this transaction.<br /><br />Please allow up to 24 hours for your premium account to activate on <a href="http://battle.darkain.com/">Battle Game MMonster</a>.';
  } else {
    echo 'Thank you for your payment. Your transaction is currently <span class="statname">' . $payment_status . '</span>, and a receipt for your purchase has been emailed to you. You may log into your PayPal account at <a href="http://www.paypal.com/us">www.paypal.com/us</a> to view details of this transaction.';
  }

  echo "\n</div>\n";

  require('includes/footer.php');
?>