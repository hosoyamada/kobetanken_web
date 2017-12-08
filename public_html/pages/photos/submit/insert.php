<!--
 * A Design by GraphBerry
 * Author: GraphBerry
 * Author URL: http://graphberry.com
 * License: http://graphberry.com/pages/license
-->
<!DOCTYPE html>
<html>
<head>
  <title>神戸大学探検部</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Load fonts -->
	<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:500' rel='stylesheet' type='text/css'>

	<!--Load styles -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="../css/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="../css/animate.css">
	<link rel="stylesheet" type="text/css" href="../css/style.css">	
<link rel="stylesheet" type="text/css" href="../css/photos.css">
        <link rel="stylesheet" type="text/css" href="../css/cal.css">
</head>
<body>

<!-- ヘッダ -->
<dir id="header">
	<header>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<h1 class="navbar-brand" href="#"></h1>
				</div>

				<div class="collapse navbar-collapse navbar-right" id="navbar-collapse">
		
					<ul class="nav navbar-nav">

						<li><a  href="../index.html#home">トップ</a></li>
						<li><a  href="../home.html#home">ホーム</a></li>
						<li><a  href="../services.html">探検部とは？</a></li>
						<li><a  href="../menber.html">メンバー</a></li>
						<li><a  href="../photo.html">活動写真</a></li>
						<li><a  href="../ensei.html">遠征</a></li>
						<li><a  href="../index.html#contact">問い合わせ</a></li>

					</ul>
				</div>
			</div>
		</nav>
	</header>
</dir>
	
	<?php
		
		
try {
	
	if (is_uploaded_file($_FILES["photo"]["tmp_name"])) {
		$photoName = $_FILES['photo']['name'];
		$photoType = $_FILES['photo']['type'];
		$photoTmp = $_FILES['photo']['tmp_name'];
	        $photoError = $_FILES['photo']['error'];
		$photoSize = $_FILES['photo']['size'];
		} else {
  exit("ファイルが選択されていません。");
}
	
  $date = $_POST["date"];
  $name = $_POST["name"];
  if($date == ""){
  	  exit("Error:「遠征実施日」が入力されていません");
  }else if($name == ""){
  	  exit("Error:「遠征名」が入力されていません");
  }else if($photoTmp == ""){
  	  exit("Error:「載せる写真」が入力されていません");
  }
  
  $pdo = new PDO('mysql:dbname=kobetanken;host=localhost', 'kobetanken', '77cQgBpm', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $pdo->query('SET NAMES utf8');
  
  
  /*
  $fp = fopen($photoTmp,"rb");
  $img = fread($fp,filesize($photoTmp));
  fclose($fp);
  $img = addslashes($img);
  $dat = pathinfo($photoName);
  $ext = $dat['extension'];
  */
   
 //画像と拡張子を取得
  $img = file_get_contents($photoTmp);
  $ext = pathinfo($photoName, PATHINFO_EXTENSION);
  if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif'){
  	  exit("Error:拡張子が「.jpg」「.jpeg」「.png」「.gif」のファイルを選択してください");
  }
  
  //サムネイル作成
	$new_width = 100;
	$image_file = $photoTmp;
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
	$file_type = $ext;
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
		ob_start();
		ImageJPEG($new_image);
		$image_binary = ob_get_contents();
		ob_end_clean();
	}elseif($file_type === "gif"){
		ob_start();
		ImageGIF($new_image);
		$image_binary = ob_get_contents();
		ob_end_clean();
	}elseif($file_type === "png"){
		ob_start();
		ImagePNG($new_image);
		$image_binary = ob_get_contents();
		ob_end_clean();
	}
	// メモリを開放する
	imagedestroy($new_image);
	imagedestroy($original_image);
	
  
  
 //データベースに保存
 $stmt = $pdo->prepare("INSERT INTO images(id,ext,contents,date,name,thumbnail) VALUES(0,:ext,:img,:date,:name, :thumbnail)");
 $stmt->bindParam(':ext',$ext);
 $stmt->bindParam(':img',$img);
 $stmt->bindParam(':date',$date);
 $stmt->bindParam(':name',$name);
 $stmt->bindParam(':thumbnail',$image_binary);
 $stmt->execute();
 
 //echo $photoName;
 
 
 $stmt = $pdo->prepare("SELECT * FROM images WHERE ext = :ext AND contents = :img");
 $stmt->bindParam(':ext',$ext);
 $stmt->bindParam(':img',$img);
 $stmt->execute();
if (!$stmt) {
  $info = $pdo->errorInfo();
  exit($info[2]);
}
 $data = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  exit($e->getMessage());
}

$pdo = null;

?>
<br/>
	<p>以下の内容で登録が完了しました。<br /></p>
<table border="1">
  <tr>
    <td>登録ID</td><td><?php echo $data['id'] ?></td>
  </tr>
  <tr>
    <td>遠征実施日</td><td><?php echo $data['date'] ?></td>
  </tr>
  <tr>
    <td>遠征名</td><td><?php echo $data['name'] ?></td>
  </tr>
  <tr>
    <td>ファイル名</td><td><?php echo $photoName ?></td>
  </tr>
  <tr>
    <td>投稿した写真</td><td><?php echo '<img src= "../imgOrigin.php?key=' . $data['id'] . '"/>' ?></td> 
  </tr>
  <tr>
    <td>サムネイル</td><td><?php echo '<img src= "../imgThumb.php?key=' . $data['id'] . '"/>'?></td>
  </tr>
</table>
	<p><a href="submit.php">登録画面へ</a></p>
	<p><a href="../form.php">画像一覧へ</a></p>
	
</body>
</html>