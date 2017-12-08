<?php
try {
		//クエリの取得
 if(preg_match('/^[0-9]+$/',$_GET['key'])){
 	 $id=$_GET['key'];
 }else{
 	 throw new Exception('エラー');
 }
  
	//データベースから対象のデータを取得
  $pdo = new PDO('mysql:dbname=kobetanken;host=localhost', 'kobetanken', '77cQgBpm', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $pdo->query('SET NAMES utf8');
  $stmt = $pdo->prepare('SELECT ext, contents FROM images WHERE id=:id');
  $stmt->bindParam(':id',$id);
  $stmt->execute();
  //Content-typeテーブル
  $contents_type = array(
  	  'jpg'  => 'image/jpeg',
  	  'jpeg' => 'image/jpeg',
  	  'png'  => 'image/png',
  	  'gif'  => 'image/gif',
  	  //'bmp'  => 'image/bmp',
   );
  //出力
  $img = $stmt->fetch(PDO::FETCH_ASSOC);
  header('Content-type:'.$contents_type[$img['ext']]);
  echo $img['contents'];
	
} catch (PDOException $e){
	exit($e->getMessage());
}
?>