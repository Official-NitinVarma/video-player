<?php
require('top.inc.php');
$sql = "SELECT COUNT(id) AS total_videos, COUNT(IF( `status` = '1',1,Null)) 'published', COUNT(IF(`status` = '0',1, null)) 'private', SUM(views) AS total_views FROM `videos` WHERE `username` ='{$_SESSION['ADMIN_USERNAME']}'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($result);
?>
<div class="content pb-0">
	<div class="orders">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-body">
						<h4 class="box-title">Dashboard </h4>
					</div>
					<div class="card-body">
						<div class="wrapper">
							<div class="row">
								<div class="col mb-4">
									<div class="card-content bg-primary p-4">
										<i class="fa-solid fa-video"></i>
										<h3><?php echo $row['total_videos']?></h3>
										<p>Total Videos</p>
									</div>
								</div>
								<div class="col mb-4">
									<div class="card-content bg-primary p-4">
										<i class="fa-solid fa-play"></i>
										<h3><?php echo $row['published']?></h3>
										<p>Published Videos</p>
									</div>
								</div>
								<div class="col mb-4">
									<div class="card-content bg-primary p-4">
										<i class="fa-solid fa-video-slash"></i>
										<h3><?php echo $row['private']?></h3>
										<p>Private Videos</p>
									</div>
								</div>
								<div class="col mb-4">
									<div class="card-content bg-primary p-4">
										<i class="fa-solid fa-eye"></i>
										<h3><?php echo $row['total_views']?></h3>
										<p>Total Views</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require('footer.inc.php');
?>