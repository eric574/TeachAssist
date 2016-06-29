<?php
session_start();
if(!isset($_SESSION['data'])){ 
	//direct access without login denied
	header("Location: index.php?error=1");
	exit(0);
}
require_once 'class-user.php';
$user=unserialize($_SESSION['data']);

if(isset($_GET['course'])){
	$course=$_GET['course'];
}else{
	$course = $user->getCourse(0, 'id');
	if(!isset($course)){
		//no courses oops
	}
}
echo '<pre>';
var_dump($user); 
echo '</pre>';
?>
<!DOCTYPE html>
<html>
<head>
	<title>TA Scraper</title>
	<!-- <link rel="stylesheet" type="text/css" href="/css/foundation.min.css"> -->
	<link rel="stylesheet" type="text/css" href="/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" type="text/css" href="/css/normalize.css">
	<script type="text/javascript" src="/js/vendor/jquery.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.min.js"></script>
	<!-- <script type="text/javascript" src="/js/vendor/foundation.min.js"></script> -->
</head>
<body>

	<div class="row wrapper">
		<div class="nav-side">
			<ul class="nav-container">
				<li class="nav-header">TeachAssist</li>
				<?php for($i=0; $i<$user->courseCount; $i++): ?>
					<li class="nav-element">
						<a href="dashboard.php?course=<?=$user->getCourse($i,'id')?>">
							<?php $mark=$user->getAverage($user->getCourse($i, 'id'))?>
							<?php $mark = $mark ? $mark : $user->getLastMark($user->getCourse($i, 'id'))?>
							<p class="course-mark"><?=round($mark*100, 1)?>%</p>
							<p class="course-name"><?=$user->getCourse($i, 'name')?></p>
						</a>
					</li>
				<?php endfor;?>
				<!-- <li class="nav-element">
					<p class="course-mark">7.5%</p>
					<p class="course-name">English</p>
				</li>
				<li class="nav-element"></li> -->
			</ul>
			<!-- side navigation -->
		</div>
		<div class="content">
			<ul class="top-bar">
				<li class="top-element">
					<p class="now-viewing">Now Viewing: <?=$user->toName($course)?></p>
				</li>
				<li class="top-element">
					<i class="icon ion-ios-bell-outline"></i>
				</li>
				<li class="top-element">
					<i class="icon ion-ios-person-outline"></i>
				</li>
			</ul>
			<hr/>
			<div class="overview">
				<div class="block term">
					<div class="circle"><?=round($user->getTermAverage($course)*100,2)?>%</div>
					<p class="achivement">Term Mark</p>
				</div>
				<div class="block course">	
					<!-- includes exams -->
					<div class="circle"><?=round($user->getCourseAverage($course)*100,2)?>%</div>
					<p class="achivement">Course Mark</p>
				</div>
				<div class="block status">
					<div class="circle">
						<i class="icon ion-ios-checkmark-empty"></i>
					</div>
					<p class="achivement">Up to date</p>
					<!-- up-to-date or hidden	 -->
				</div>
			</div>
			<div class="chart-container">
				<div class="chart">
					<div class="chart-header">Achievement</div>
					<canvas id="achievementLine"></canvas>
				</div>
				<div class="chart">
					<div class="chart-header">Weighting</div>
					<canvas id="weightDonut"></canvas>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="/js/app.js"></script>
	</body>
	</html>
	<?php $_SESSION['data']=serialize($user);?>