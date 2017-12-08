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
	<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../../css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="../../css/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="../../css/animate.css">
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
<link rel="stylesheet" type="text/css" href="../../css/photos.css">
</head>
<body>
	<!-- ヘッダ -->
	<dir id="header">
		<header>
			<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="background-color:snow;">
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

					<div class="collapse navbar-collapse navbar-right" id="navbar-collapse" style="background-color:snow;">
						<ul class="nav navbar-nav" >
							<li><a  href="../home/home.html">ホーム</a></li>
							<li><a  href="../what_is_tanken/services.html">探検部とは？</a></li>
							<li><a  href="../members/member.html">メンバー</a></li>
							<li><a  href="./form.php">活動写真</a></li>
							<li><a  href="../ensei/ensei.html">遠征</a></li>
							<li><a  href="../contact/index.html">問い合わせ</a></li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
	</dir>



<br>
<h2>〜活動写真〜</h2>
<br>
	<p>こちらでは探検部の活動写真を随時載せています！！！！！！</p>

<?php
try {
  $pdo = new PDO('mysql:dbname=kobetanken;host=127.0.0.1', 'kobetanken', '77cQgBpm');
} catch (PDOException $e) {
  exit('データベースに接続できませんでした。' . $e->getMessage());
}

$stmt = $pdo->query('SET NAMES utf8');
if (!$stmt) {
  $info = $pdo->errorInfo();
  exit($info[2]);
}

//date型から文字列へ変換する関数
function dateToString($date,$string = ''){
	return date($string,strtotime($date));
}
//それぞれの画像を適切な位置に表示する関数
function positionPhoto($data,&$years,&$titles){
	$year = dateToString($data['date'],'Y年');
	$title = dateToString($data['date'],'m/d ').$data['name'];
	for($q=0;$q!=100;$q++){
	for($i=0; $years[$i]!=NULL; $i++){
		if($years[$i] == $year){
			for($p=0;$p!=100;$p++){
			for($j=0; $titles[$j]!=NULL; $j++){
				if($titles[$j] == $title){
					//リスト形式で写真を表示
					echo "<li><div>\n";
  					echo "<a href=\"./imgOrigin.php?key=" . $data['id'] . "\"><img src= \"./imgThumb.php?key=" . $data['id'] . "\"/ ></a></br>\n";
  					echo "ID=" . $data['id'] . "\n";
					echo "</div></li>\n";
					return;
				}
			}
			//(div#titleを閉じて)新たにdiv#titleを作る
			if($j != 0){
				echo "</ul></div>";
			}
			$titles[$j] = $title;
			echo "<div id=title><h3>".$title."</h3><ul id=photos>\n";
			}
		}
	}
	//(div#yearを閉じて)新たにdiv#yearを作る
	if($i != 0){
		echo "</ul></div></div>";
	}
	$titles = array_fill(0,200,NULL);
	$years[$i] = $year;
	echo "<div id=year><h2>".$year."</h2><hr>\n";
	}
}
	
//echo dateToString('2010-12-30','Y年m月d日');「2010年12月30日」
//画像表示
$stmt = $pdo->query('SELECT id,date,name FROM images ORDER BY date DESC,id');
if (!$stmt) {
  $info = $pdo->errorInfo();
  exit($info[2]);
}
	echo "<div id=contents>";
	echo "<p>投稿済みの画像</p>";
	$years = array_fill(0,200,NULL);
	$titles = array_fill(0,200,NULL);
while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
	positionPhoto($data,$years,$titles);
}
	echo "</ul></div></div>";
	echo "</div>";

/*
while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
  echo 'id=' . $data['id'] . '</br>';
  echo '<img src= "./img.php?key=' . $data['id'] . '"/></br></br>';
}
echo "</br>";
*/

$pdo = null;

?>

	<p><a href="./submit/submit.php">画像を載せたい人はこちら（要パスワード）</a></p></br>
		
</body>
</html>