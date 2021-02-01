<?php
  // ・データベース名：tb221122db
	// ・ユーザー名：tb-221122
	// ・パスワード：4Ah664Tzgh

  
	/*DB接続設定*/
  $dsn = 'mysql:dbname=tb221122db;host=localhost';
	$user = 'tb-221122';
	$password = '4Ah664Tzgh';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//PDOクラスのインスタンス作成
  
  
  /*テーブル作成*/
  $sql_table = "CREATE TABLE IF NOT EXISTS keijiban"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
  . "comment TEXT,"
  . "time datetime"
	.");";
  $stmt = $pdo->query($sql_table);
  
  /*変数を定義------------------------------------------------------*/
  $now = new datetime();
  $format_now = $now -> format("y-m-d H-i-s");

  if(isset($_POST["name"]))
  {
    $name=$_POST["name"];
  }
  if(isset($_POST["comment"]))
  { 
    $comment=$_POST["comment"];
  }  
  if(isset($_POST["delete"]))
  { 
    $delete=$_POST["delete"];
  } 
  if(isset($_POST["edit"]))
  { 
    $edit=$_POST["edit"];
  } 
  if(isset($_POST["password_input"]))
  { 
    $password_input=$_POST["password_input"];
  } 
  if(isset($_POST["password_delete"]))
  { 
    $password_delete=$_POST["password_delete"];
  } 
  if(isset($_POST["password_edit"]))
  { 
    $password_edit=$_POST["password_edit"];
  } 
  
  /*編集内容を保存する変数を準備*/
  $rename="";
  $recomment="";
  $edit_id="";
  $del_com="";
  $edit_com="";
  $not_pass="";


  /*番号選んで削除する機能*/
  //もし削除番号が送信されたら、データベースの中から削除内容を消す
  if(isset($delete))   
  {
    if($password_delete == "tech_base")   //パスワードがtech_baseなら処理開始
    {
      $id =$delete;  //削除する投稿番号
      $sql = "DELETE from keijiban WHERE id=:id";    
      $stmt = $pdo->prepare($sql);//SQL文実行の準備
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $del_com = "削除されました";
    }
    if($password_delete!= "tech_base")
    {
      $not_pass ="パスワードが正しくありません";
    }
  } 
  
  /*編集する内容をデータベースから読み取り、入力フォームに入れる機能*/ 
  if(isset($edit))
  {
    if($password_edit == "tech_base")   //パスワードがtech_baseなら処理開始
    {
      /*idと$editが一致の行を配列で抽出*/
      $sql_get = "SELECT * FROM keijiban where id= $edit"; 
      $stmt_get = $pdo->query($sql_get);
      $results = $stmt_get -> fetch(PDO::FETCH_ASSOC);
      $rename=$results['name'];
      $recomment=$results['comment'];
      $edit_id = $edit; //$edit_idに$editを代入
    } 
    if($password_edit != "tech_base")
    {
      $not_pass ="パスワードが正しくありません";
    } 
  }
  ?>  

  <!DOCTYPE html>
  <html lang="ja">
  <head>
      <meta charset="UTF-8">
      <title>mission_5-1</title>
      </head>
      <body>
      <form action="" method="POST">
      <input name="edit_id" type="hidden" value="<?php if (!empty($edit_id)){echo $edit_id;}?>">       
      <p>名前：<input type="text" autocomplete="off" name="name"
      value="<?php if (!empty($rename)){echo $rename;}?>"</p> 
      <p>コメント：<input type="text" autocomplete="off" name="comment" 
      value="<?php if (!empty($recomment)){echo $recomment;}?>"></p>
      <p>パスワード：<input type="text" autocomplete="off" name="password_input"
      ></p>
      <p><input type="submit" name="submit"></p>    
      </form>
      <form action="" method="POST">
      <p>削除：<input type="text" autocomplete="off" name="delete"></p>
      <p>パスワード：<input type="text" autocomplete="off" name="password_delete" ></p>
      <p><input type="submit" value="削除"></p>    
      </form>
      <form action="" method="POST">
      <p>編集番号：<input type="text" autocomplete="off" name="edit"></p>
      <p>パスワード：<input type="text" autocomplete="off" name="password_edit" ></p>
      <p><input type="submit" value="送信"></p>    
      </form>
      </body>
      </html>
      
  
<?php 
  /*データベースに入力する機能*/
  if(isset($name) && isset($comment))  
  { 
    /*編集して入力する機能*/ 
    if(isset($_POST["edit_id"]))  //入力フォームに編集番号が送られたときに処理開始
    {
      if($password_input =="tech_base")   //パスワードがtech_baseなら処理開始
      {
        $id = $_POST["edit_id"];  //編集する投稿番号
        $sql_edit = "UPDATE keijiban SET name=:name,comment=:comment, time=:time WHERE id =:id";
        $stmt_edit = $pdo->prepare($sql_edit);//SQL文実行の準備
        /*値をバインド*/
        $stmt_edit->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt_edit->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt_edit->bindParam(':time',$format_now , PDO::PARAM_STR);
        $stmt_edit->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_edit->execute();
        $edit_com="正常に編集されました";
      }
      if($password_input != "tech_base")
      {
        $not_pass ="パスワードが正しくありません";
      }  
    }
    /*新規投稿で入力する機能*/
    if(empty($_POST["edit_id"]))//入力フォームに編集番号が送られてないときに処理開始
    {
      if($password_input == "tech_base")   //パスワードがtech_baseなら処理開始
      {
        $sql_input = $pdo -> prepare("INSERT INTO keijiban (name, comment, time) VALUES (:name, :comment, :time)");//SQL文実行の準備
        /*値をバインド*/
        $sql_input -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql_input -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql_input -> bindParam(':time', $format_now, PDO::PARAM_STR);
        $sql_input -> execute();
      }
      if($password_input != "tech_base")
      {
        $not_pass ="パスワードが正しくありません";
      }  
    }
  }
  
  /*テーブル中の中身を表示する機能*/
  //データベースからテーブル内の内容を読み取る機能
  $sql_input = 'SELECT * FROM keijiban';
  $stmt_input = $pdo->query($sql_input);
  $results = $stmt_input->fetchall();
  
  //画面に表示する機能
  foreach ($results as $row)   
  {
    echo $row['id'].'   ';
		echo $row['name'].'   ';
    echo $row['comment'].'   ';
    echo $row['time'].'<br>';
  }
  
  if(isset($del_com))
  {
    echo $del_com;
  }
  if(isset($not_pass))
  {
    echo $not_pass;
  }
  if(isset($edit_com))
  {
    echo $edit_com;
  }

  ?>
