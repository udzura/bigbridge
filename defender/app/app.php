<?php
function form_exist($v){
  return isset($v) === true && $v !== "";
}

$errors = array();
$message = "";

$my = mysqli_connect( 'localhost', 'bigbridge', 'bigbridge0630', 'testapp' );
if ($my === false) {
  echo "!!! DB connection failed";
}

if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {
  if ( $_POST["mode"] === "delete" ) {
    $query = "select * from messages "
      . "where id = " . $_POST["mid"]
      . " and password = '" . $_POST["password"]
      . "'";

    $res = mysqli_query( $my, $query );
    if ( mysqli_num_rows($res) === 0 ) {
      $message = '削除に失敗しました';
    }else{
      $query = "delete from messages "
        . "where id = " . $_POST["mid"]
        . " and password = '" . $_POST["password"]
        . "' limit 1";

      $res = mysqli_query( $my, $query );
      if ( $res !== false ) {
        $message = '削除に成功しました';
      }else{
        $message = '削除に失敗しました';
      }
    }
  } else {
    if ( !form_exist($_POST["name"]) )
      $errors["name"] = "名前を入力してください";
    if ( !form_exist($_POST["message"]) )
      $errors["message"] = "コメントを入力してください";

    if( count($errors) === 0 ){
      $filepath = "";
      if(isset($_FILES["image"]) && isset($_FILES["image"]['name'])) {
        $uploaddir = dirname(__FILE__) . "images";
        mkdir($uploaddir, 0775, true);
        $uploadfile = $uploaddir . "/" . basename($_FILES['image']['name']);

        if (copy($_FILES['image']['tmp_name'], $uploadfile)) {
          $message = "画像のアップロードに成功しました<br>";
          $filepath = str_replace('/var/www/html', '', $uploadfile);
        } else {
          $message = "画像のアップロードに失敗しました<br>";
          $filepath = "";
        }
      }

      $query = "INSERT INTO messages ( "
        . "    username , "
        . "    message , "
        . "    password , "
        . "    filepath "
        . " ) VALUES ( "
        . "'" . $_POST["name"] ."', "
        . "'" . mysqli_real_escape_string($my, $_POST["message"]) ."', "
        . "'" . $_POST["password"] ."', "
        . "'" . $filepath ."' "
        ." ) ";

      $res = mysqli_query( $my, $query );

      if ( $res !== false ) {
        $message = $message . '書き込みに成功しました';
      }else{
        $message = '書き込みに失敗しました';
      }
    }
  }
}
$data = array();
$res = mysqli_query($my, "select id, username, message, password, filepath from messages order by id asc;");
while( $row = mysqli_fetch_assoc( $res ) ) {
  array_push($data, $row);
}

mysqli_close( $my );
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>掲示板</title>
    </head>
    <body>
        <?php echo $message; ?>
        <form method="post" enctype="multipart/form-data" id="main" action="">
        名前：<input type="text" name="name" value="<?php echo $_POST["name"]; ?>" >
            <?php echo $errors["name"]; ?><br>
            コメント：<textarea  name="message" rows="4" cols="40"><?php echo $_POST["message"]; ?></textarea>
            <?php echo $errors["message"]; ?><br>
        削除パスワード：<input type="password" name="password" > <br>
        添付画像：<input type="file" name="image" >
        <input type="hidden" name="mode" value="post">
<br>
          <input type="submit" name="send" value="投稿する" >
        </form>
        <dl>

<h2>投稿一覧</h2>

<?php if ( count($data) === 0): ?>
投稿がありません
<?php endif;?>

<?php foreach( $data as $key => $row):?>
  <p><span class="author">投稿: <?php echo $row["username"]; ?></span></p>
<p><?php echo str_replace(array("\r\n", "\n", "\r"), "<br>", $row["message"]); ?></p>
<?php if ( form_exist($row["filepath"]) ): ?>
<img src="<?php echo $row["filepath"] ?>" >
<?php endif;?>
<form method="post" action="" id="delete-<?php echo $row["id"] ?>">
  <input type="hidden" name="mode" value="delete">
  <input type="hidden" name="mid" value="<?php echo $row["id"] ?>">
  del key: <input type="password" name="password">
  <input type="submit" name="send" value="削除" >
</form>
<hr>
<?php endforeach;?>

</dl>
    </body>
</html>
