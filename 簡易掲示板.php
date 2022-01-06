<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>簡易掲示板</title>
</head>

<body>
    
<!--postで送信-->
    <form action="" method="post">
        
<!--名前、コメント、パスワード、送信ボタンを作成-->
        <input type="text" name="name" placeholder="名前"> <br>
        <input type="text" name="comment" placeholder="コメント"> <br>
        <input type="text" name="pass1" placeholder="パスワード">
        <input type="submit" name="submit" value="送信"> <br> <br>
        
<!--消去対象番号、パスワード、消去ボタンを作成-->
        <input type="number" name="d_num" placeholder="消去対象番号"> <br>
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="delete" value="消去"> <br> <br>

<!--編集対象番号、パスワード、編集ボタンを作成-->
        <input type="number" name="e_num" placeholder="編集対象番号"> <br>
        <input type="text" name="pass3" placeholder="パスワード">
        <input type="submit" name="edit" value="編集">
    </form>

<?php

// DB接続設定
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// テーブルを作成
$sql = "CREATE TABLE IF NOT EXISTS mission5_1"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "dname char(32),"
. "dcomment TEXT,"
. "ddate char(32),"
. "dpass char(32)"
.");";
$stmt = $pdo -> query($sql);

// 各項目を簡略化
$name = filter_input(INPUT_POST, "name");
$comment = filter_input(INPUT_POST, "comment");
$date = date("Y年m月d日(D) H時i分s秒");
$d_num = filter_input(INPUT_POST, "d_num");
$e_num = filter_input(INPUT_POST, "e_num");
$pass1 = filter_input(INPUT_POST, "pass1");
$pass2 = filter_input(INPUT_POST, "pass2");
$pass3 = filter_input(INPUT_POST, "pass3");
$submit = filter_input(INPUT_POST, "submit");
$delete = filter_input(INPUT_POST, "delete");
$edit = filter_input(INPUT_POST, "edit");

// キーワードを作成
$key1 = "1111";
$key2 = "2222";
$key3 = "3333";

// 名前が入力されておらず、コメントが入力されており、送信ボタンが押された場合
if(empty($name) && !empty($comment) && !empty($submit)){

// 文字を表示
    echo "!---------------! <br> <br>";
    echo "Error : Name is Empty. <br> <br>";
    echo "!---------------! <br> <br>";

// 名前が入力されており、コメントが入力されておらず、送信ボタンが押された場合
}elseif(!empty($name) && empty($comment) && !empty($submit)){

// 文字を表示
    echo "!---------------! <br> <br>";
    echo "Error : Comment is Empty. <br> <br>";
    echo "!---------------! <br> <br>";

// 名前、コメントが入力されており、送信ボタンが押された場合
}elseif(!empty($name) && !empty($comment) && !empty($submit)){

// 名称を変更
    $dname = $name;
    $dcomment = $comment;
    $ddate = $date;

// $pass1が入力されなかった場合
    if(empty($pass1)){
        
// 文字を表示
        echo "!---------------! <br> <br>";
        echo "Error : Password is Empty. <br> <br>";
        echo "!---------------! <br> <br>";
    
// $pass1が入力された場合
    }elseif(!empty($pass1)){
        
// $pass1が間違っていた場合        
        if($pass1 !== $key1){

// 文字を表示
            echo "!---------------! <br> <br>";
            echo "Error : Password is invalid. <br> <br>";
            echo "!---------------! <br> <br>";
    
// $pass1が合っていた場合
        }elseif($pass1 == $key1){
            
// データを入力
            $sql = $pdo -> prepare("INSERT INTO mission5_1 (dname, dcomment, ddate) VALUES (:dname, :dcomment, :ddate)");
            $sql -> bindParam(':dname', $dname, PDO::PARAM_STR);
            $sql -> bindParam(':dcomment', $dcomment, PDO::PARAM_STR);
            $sql -> bindParam(':ddate', $ddate, PDO::PARAM_STR);
            $sql -> execute();
            
// データレコードを抽出
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
        }
    }
}

// 消去対象番号が入力され、消去ボタンが押された場合
if(!empty($d_num) && !empty($delete)){
    
// $pass2が入力されていない場合
    if(empty($pass2)){
    
// 文字を表示
        echo "!---------------! <br> <br>";
        echo "Error : Password is Empty. <br> <br>";
        echo "!---------------! <br> <br>";

// $pass2が入力された場合
    }elseif(!empty($pass2)){
    
// $pass2が間違っている場合
        if($pass2 !== $key2){

// 文字を表示
            echo "!---------------! <br> <br>";
            echo "Error : Password is invalid. <br> <br>";
            echo "!---------------! <br> <br>";
    
// $pass2が合っていた場合
        }elseif($pass2 == $key2){

// データレコードを抽出
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            
// データレコードを消去
            foreach ($results as $row){
                $id = $d_num;
                $sql = 'delete from mission5_1 where id=:id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                $stmt -> execute();
            }
        }
    }
}

// 編集対象番号が入力され、編集ボタンが押された場合
if(!empty($e_num) && !empty($edit)){
    
// 名前、コメントが入力されていない場合
    if(empty($name) && empty($comment)){

// 文字を表示
        echo "!---------------! <br> <br>";
        echo "Error : Name and Comment are Empty. <br> <br>";
        echo "!---------------! <br> <br>";
    
// 名前が入力されておらず、コメントが入力されている場合
    }elseif(empty($name) && !empty($comment)){

// 文字を表示
        echo "!---------------! <br> <br>";
        echo "Error : Name is Empty. <br> <br>";
        echo "!---------------! <br> <br>";

// 名前が入力されており、コメントが入力されていない場合
    }elseif(!empty($name) && empty($comment)){

// 文字を表示
        echo "!---------------! <br> <br>";
        echo "Error : Comment is Empty. <br> <br>";
        echo "!---------------! <br> <br>";

// 全て入力されている場合
    }elseif(!empty($name) && !empty($comment)){

// $pass3が入力されなかった場合
        if(empty($pass3) && empty($pass1)){
        
// 文字を表示
            echo "!---------------! <br> <br>";
            echo "Error : Password is Empty. <br> <br>";
            echo "!---------------! <br> <br>";

// $pass3が入力された場合
        }elseif(!empty($pass3)){
        
// パスワードが間違っていた場合
            if($pass3 !== $key3){
            
// 文字を表示
                echo "!---------------! <br> <br>";
                echo "Error : Password is invalid. <br> <br>";
                echo "!---------------! <br> <br>";
            
// $pass3が合っていた場合
            }elseif($pass3 == $key3){

// 名称を変更
                $id = $e_num;
                $dname = $name;
                $dcomment = $comment;
                $ddate = $date;
                
// データレコードの内容を編集
                $sql = 'UPDATE mission5_1 SET dname = :dname, dcomment = :dcomment, ddate = :ddate WHERE id = :id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':dname', $dname, PDO::PARAM_STR);
                $stmt -> bindParam(':dcomment', $dcomment, PDO::PARAM_STR);
                $stmt -> bindParam(':ddate', $ddate, PDO::PARAM_STR);                
                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                $stmt -> execute();

// データレコードを抽出
                $sql = 'SELECT * FROM mission5_1';
                $stmt = $pdo -> query($sql);
                $results = $stmt -> fetchAll();
            }
        }
    }
}

// データレコードを抽出
$sql = 'SELECT * FROM mission5_1';
$stmt = $pdo -> query($sql);
$results = $stmt -> fetchAll();

// データレコードを表示
foreach ($results as $row){
    echo $row['id'];
    echo " " . $row['dname'];
    echo " " . $row['dcomment'];
    echo " " . $row['ddate'] . "<br>";
echo "<hr>";
}
    
?>

</body>
</html>