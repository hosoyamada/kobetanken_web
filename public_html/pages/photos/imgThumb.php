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
  $stmt = $pdo->prepare('SELECT ext, thumbnail FROM images WHERE id=:id');
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
  echo $img['thumbnail'];
  /*
	$new_width = 100;
	$image_file = $img['contents'];
	// 元画像のファイルサイズを取得
	list($original_width, $original_height) = getimagesize($image_file);
	//元画像の比率を計算し、高さを設定
	$proportion = $original_width/$original_height;
	$new_height = $new_width/$proportion;
	//高さが幅より大きい場合は、高さを幅に合わせ、横幅を縮小
	if($proportion < 1){
		$new_height = $new_width;
		$new_width = $new_width * $proportion;
	}
	//$file_type = strtolower(end(explode('.',$image_file)));
	$file_type = $img['ext'];
	if($file_type === "jpg" || $file_type === "jpeg"){
		$original_image = ImageCreateFromJPEG($image_file);
		$new_image = ImageCreateTrueColor($new_width,$new_height);
	}elseif ($file_type === "gif"){
		$original_image = ImageCreateFromGIF($image_file);
		$new_image = ImageCreateTrueColor($new_width,$new_height);
		//透過問題解決
		$alpha = imagecolortransparent($original_image);
		imagefill($new_image,0,0,$alpha);
		imagecolortransparent($new_image,$alpha);
	}elseif($file_type === "png"){
		$original_image = imageCreateFromPNG($image_file);
		$new_image = ImageCreateTrueColor($new_width,$new_height);
		imagealphaBlending($new_image,false);
		imagesavealpha($new_image,true);
	}else{
		return;
	}
	// 元画像から再サンプリング
	ImageCopyResampled($new_image,$original_image,0,0,0,0,$new_width,$new_height,$original_width,$original_height);
	// 画像をブラウザに表示
	if($file_type ==="jpg" || $file_type === "jpeg"){
		ImageJPEG($new_image);
	}elseif($file_type === "gif"){
		ImageGIF($new_image);
	}elseif($file_type === "png"){
		ImagePNG($new_image);
	}
	// メモリを開放する
	imagedestroy($new_image);
	imagedestroy($original_image);
	*/
  
} catch (PDOException $e){
	exit($e->getMessage());
}
?>