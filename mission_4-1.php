<html>
<html lang = "ja">
	<head>
		<meta charset = "UTF-8">
	</head>
	<body>
			<?php
			$name = "名前";
			$comment = "コメント";
			$pass = "パスワード";
			?>
			<!--フォーム作成-->
				<!--名前コメント-->
				<form action = "mission_4-1.php" method = "POST">
				<input type = "text" name = "name" value = "<?php echo $name; ?>"></br>
				<input type = "text" name = "comment" value = "<?php echo $comment; ?>"></br>
				<input type = "hidden" name = "flag" value = "<?php echo $_POST['edit']; ?>">
				<input type = "hidden" name = "editpass2" value = "<?php echo $_POST['editpass']; ?>"><!--編集フォームで打ち込んだパスワードが送信押した時にnullで上書きされるから４４行めで値を格納しておいた-->
				<input type = "text" name = "pass" value = "<?php echo $pass; ?>">
				<input type = "submit" value = "送信"></br></br>
				</form>
				<!--削除-->
				<form action = "mission_4-1.php" method = "POST">
				<input type = "text" name = "delete" value = "削除対象番号"></br>
				<input type = "text" name = "delpass" value = "パスワード">
				<input type = "submit" value = "削除"></br></br>
				</form>
				<!--編集-->
				<form action = "mission_4-1.php" method = "POST">
				<input type = "text" name = "edit" value = "編集対象番号"></br>
				<input type = "text" name = "editpass" value = "パスワード">
				<input type = "submit" value = "編集"></br>
				<!--フォームの終了-->
				</form>
	<?php
//テーブル作成
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn,$user,$password);
	$sql= "CREATE TABLE kanikeijiban (id INT, name char(32), comment TEXT, pass char(16), timedata datetime);";//CREATE TABLE〜で〜という名前のtableを作っている（）の中身のintは整数　charは文字　textは長めの文を表し、表を作っている
	$stmt = $pdo->query($sql);

//データ入力
	if(($_POST["comment"]) && !(is_numeric($_POST["flag"])) && !(is_numeric($_POST["delete"])) && !(is_numeric($_POST["edit"]))){
	$sql = $pdo -> prepare("INSERT INTO kanikeijiban (id,name,comment,pass,timedata) VALUES (:id, :name, :comment, :pass, :timedata)"); 
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);//bindParam( ':', $)の意味は'：'の中に＄を代入するという意味_strは文字列を表intは数列
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':id', $number, PDO::PARAM_INT);
	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	$sql -> bindParam(':timedata', $timedata, PDO::PARAM_INT);
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	//selectでカラム（縦の行のことちなみに横の行のことをレコードという）を指定してfromでテーブルから持ってくるwhere＝if文の条件式みたいなもの
	$sql2 = 'SELECT id FROM kanikeijiban where  id = (SELECT max(id) FROM kanikeijiban)';
	//queryの意味は問い合わせfetchは結果セットからレコードを一行取得します。PDOStatementクラスのfetchメソッドは、結果セットの先頭から一行ずつ順番に取得します。最後の行まで達して、取得できるデータが無くなったらfalseが返るので、ループを抜けます。fetchメソッドの戻り値は、カラム名をキーとした連想配列になります。$row['name']にnameカラムの値、$row['price]にpriceカラムの値が入るわけです。
	$id = $pdo -> query($sql2) -> fetch(PDO::FETCH_ASSOC);
	//$idに格納したレコードのidカラムを取得する
	$number = $id["id"]+1;
	$pass = $_POST["pass"];
	$timedata = date("Y/m/d/ H:i:s");
	$sql -> execute();
	 }
//削除機能
	$sql = 'SELECT * FROM kanikeijiban';
	$results = $pdo -> query($sql);
	$pass = $_POST["delpass"];
	$id = $_POST["delete"];
	if(is_numeric($id)){
		foreach ($results as $row){
			//
			if($id == $row['id'] && $pass == $row ['pass'] ){
				$sql = "delete from kanikeijiban where id=$id ";
				$result = $pdo->query($sql);
			}
		}
	}
//データの編集
 	$sql = 'SELECT * FROM kanikeijiban';
	$results = $pdo -> query($sql);
	$editpass = $_POST["editpass2"];
	$pass = $_POST["editpass2"];
	$id = $_POST["flag"]; 
	$nm = $_POST["name"]; 
	$kome = $_POST["comment"]; //好きな名前、好きな言葉は自分で決めること
	if(is_numeric($id)){
		foreach ($results as $row){
			//ぱっぱらぱー　=ではなく==!!!!!
			if($id == $row['id'] && $pass == $row ['pass'] ){
				$sql = "update kanikeijiban set name='$nm' , comment='$kome' where id = $id";
				$result = $pdo->query($sql);				
			}
		}
	}


//表示
	$sql = 'SELECT * FROM kanikeijiban';
	$results = $pdo -> query($sql);
	foreach ($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['timedata'].'<br>';
	}
	
?>
	</body>
</html>


