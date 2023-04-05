<?php
ob_start();
require('top.inc.php');

if (isset($_GET['type']) && $_GET['type'] != '') {
	$type = get_safe_value($con, $_GET['type']);
	if ($type == 'status') {
		$operation = get_safe_value($con, $_GET['operation']);
		$id = get_safe_value($con, $_GET['id']);
		if ($operation == 'active') {
			$status = '1';
		} else {
			$status = '0';
		}
		$update_status_sql = "update videos set status='$status' where id='$id'";
		$result = mysqli_query($con, $update_status_sql);
		if ($result) {
			header("location: ./manage_videos.php");
			exit();
		}
	}

	if ($type == 'delete') {
		$id = get_safe_value($con, $_GET['id']);
		$delete_sql = "delete from videos where id='$id'";
		$result = mysqli_query($con, $delete_sql);
		if ($result) {
			header("location: ./manage_videos.php");
			exit();
		}
	}
}

$sql = "SELECT * FROM videos WHERE `username`='{$_SESSION['ADMIN_USERNAME']}' ORDER BY id ASC";
$res = mysqli_query($con, $sql);
?>
<div class="content pb-0">
	<div class="orders">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-body">
						<h4 class="box-title">Videos </h4>
						<h4 class="box-link"><a href="<?php echo SITE_PATH . "admin/create" ?>">Add Videos</a> </h4>
						
					</div>
					<div class="card-body--">
						<div class="table-stats order-table ov-h">
							<table class="table display" id="table_id">
								<thead>
									<tr>
										<th class="serial">#</th>
										<!-- <th>ID</th> -->
										<th>Videos Title</th>
										<th>Short Url</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									while ($row = mysqli_fetch_assoc($res)) { ?>
										<tr>
											<td class="serial"><?php echo $i ?></td>
											<td><?php echo $row['title'] ?></td>
											<td><?php echo SITE_PATH . "watch/" . $row['short_url'] ?></td>
											<td>
												<div class="btn-group" role="group" aria-label="Button group with nested dropdown">

													<div class="btn-group" role="group">
														<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
															Actions
														</button>
														<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
															<?php
															if ($row['status'] == 1) {
															?>
																<li><a class="dropdown-item" href="?type=status&operation=deactive&id=<?php echo $row['id']; ?>">Private</a></li>
															<?php } else { ?>
																<li><a class="dropdown-item" href="?type=status&operation=active&id=<?php echo $row['id']; ?>">Publish</a></li>
															<?php } ?>
															<li><a class="dropdown-item" href="<?php echo SITE_PATH ?>admin/edit/<?php echo $row['short_url'] ?>">Edit</a></li>
															<li><a class="dropdown-item" href="?type=delete&id=<?php echo $row['id']; ?>">Delete</a></li>
															<li><button class="dropdown-item embed_url" data-url="<?php echo $row['short_url'] ?>">Embed</button></li>

														</ul>
													</div>
												</div>
											</td>
										</tr>
									<?php $i++;
									} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	const embed_urls = document.querySelectorAll('.embed_url');
	if (embed_urls.length != 0) {
		embed_urls.forEach(elem => {
			elem.addEventListener('click', event => {
				let dataLink = event.target.getAttribute('data-url');
				let copy_embed = `<iframe src='<?php echo SITE_PATH ?>watch/${dataLink}' width='720' height='400' frameborder='0'></iframe>`;
				var listener = function(ev) {
					ev.clipboardData.setData("text/plain", copy_embed);
					ev.preventDefault();
				};
				document.addEventListener("copy", listener);
				document.execCommand("copy");
				document.removeEventListener("copy", listener);
				alert('Link Copied');
			})
		})
	}
</script>
<?php
require('footer.inc.php');
?>