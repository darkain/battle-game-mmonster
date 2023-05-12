<?php
  require('includes/config.php');
  if ($user_id == 0) redirect_404();
  if ($user_type !== 'prem') redirect_404();

  $page_name = 'Upload Userpic';

  require('includes/header.php');

  if (isset($_FILES['userpic'])) {
    $target_path = 'temp/' . basename($_FILES['userpic']['name']);
    if (move_uploaded_file($_FILES['userpic']['tmp_name'], $target_path)) {
      $imagedata = @getimagesize($target_path);
      if ($imagedata[2] != 1  &&  $imagedata[2] != 2  &&  $imagedata[2] != 3) {
        $db_err = 'Userpics must be in JPG/PNG/GIF format: ' . $imagedata[2];
        unlink($target_path);
      } else if ($imagedata[0] > 100  ||  $imagedata[1] > 100) {
        $db_err = 'Userpics must be 100 x 100 pixels in size: ' . $imagedata[0] . 'x' . $imagedata[1];
        unlink($target_path);
      } else {
        $final_path1 = 'userpics/' . $user_id . '.gif';
        $final_path2 = 'userpics/' . $user_id . '.jpg';
        $final_path3 = 'userpics/' . $user_id . '.png';

        if (file_exists($final_path1)) unlink($final_path1);
        if (file_exists($final_path2)) unlink($final_path2);
        if (file_exists($final_path3)) unlink($final_path3);

        if ($imagedata[2] == 1) $final_path = $final_path1;
        if ($imagedata[2] == 2) $final_path = $final_path2;
        if ($imagedata[2] == 3) $final_path = $final_path3;

        rename($target_path, $final_path);
        chmod($final_path, 0644);
        echo "<span class=\"good\">Userpic uploaded successfully</span><br /><br />\n\n";
      }
    } else {
      $db_err = 'Error uploading file';
    }
  }

  if (isset($db_err)) echo "<span class=\"error\">$db_err</span><br /><br />\n\n";
?>


<h1>Upload Userpic</h1><br />
<form enctype="multipart/form-data" action="userpic.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
<div>
<table class="stats">
<tr><td></td><td><?php echo display_user_pic($user_id); ?><td></tr>
<tr><th>File:</th><td><input type="file" name="userpic" /></td></tr>
<tr><td></td><td>
  <input type="submit" value="Upload" />
  <input type="button" value="Cancel" onclick="document.location='profile.php?id=<?php echo $user_id; ?>'" />
</td></tr>
</table>
</div>
</form>


<?php
  require('includes/footer.php');
?>