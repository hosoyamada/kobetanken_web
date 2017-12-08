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
  $id = $_POST["id"];
  if($id == ""){
  	  exit("Error:「ID」が入力されていません");
  }
  
  $pdo = new PDO('mysql:dbname=phpdb;host=localhost', 'root', 'mango0507', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $pdo->query('SET NAMES utf8');
  
 $stmt = $pdo->prepare("SELECT * FROM images WHERE id = :id");
  $stmt->bindParam(':id',$id);
 $stmt->execute();
if (!$stmt) {
  $info = $pdo->errorInfo();
  exit($info[2]);
}
if(!($data = $stmt->fetch(PDO::FETCH_ASSOC))){
	exit("id=".$id."の画像は見つかりませんでした");
}
 $stmt = $pdo->prepare("DELETE FROM images WHERE id = :id");
  $stmt->bindParam(':id',$id);
 $stmt->execute();

} catch (PDOException $e) {
  exit($e->getMessage());
}

$pdo = null;

?>
	<p>ID=<?php echo $id ?>の画像が削除されました。<br /></p>
	<p><a href="./submit.php">登録画面へ</a></p>
	<p><a href="../form.php">画像一覧へ</a></p>
	
</body>
</html>