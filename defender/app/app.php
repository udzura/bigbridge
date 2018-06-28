<?php
function form_exist($v){
  return isset($v) === true && $v !== "";
}

$errors = array();
$message = "";

//投稿がある場合のみ処理を行う
if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {
  if ( !form_exist($_POST["name"]) )
    $errors["name"] = "名前を入力してください";
  if ( !form_exist($_POST["message"]) )
    $errors["message"] = "コメントを入力してください";

  if( count($errors) === 0 ){
    $message = "書き込みに成功しました。";
  }
}
$dataArr = array();
/* while( $res = fgets( $fp)){ */
/*     $tmp = explode("\t",$res); */
/*     $arr = array( */
/*         "name"=>$tmp[0], */
/*         "comment"=>$tmp[1] */
/*     ); */
/*     $dataArr[]= $arr; */
/* } */

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>掲示板</title>
    </head>
    <body>
        <?php echo $message; ?>
        <form method="post" action="">
        名前：<input type="text" name="name" value="<?php echo $_POST["name"]; ?>" >
            <?php echo $errors["name"]; ?><br>
            コメント：<textarea  name="message" rows="4" cols="40"><?php echo $_POST["message"]; ?></textarea>
            <?php echo $errors["message"]; ?><br>
        削除パスワード：<input type="password" name="password" > <br>
        添付画像：<input type="file" name="image" >
<br>
          <input type="submit" name="send" value="投稿する" >
        </form>
        <dl>
         <?php foreach( $dataArr as $data ):?>
         <p><span><?php echo $data["name"]; ?></span>:<span><?php echo $data["comment"]; ?></span></p>
        <?php endforeach;?>
</dl>
    </body>
</html>