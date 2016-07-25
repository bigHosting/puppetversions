<!-- //link ke skrip db_koneksi -->
<?php include 'db_koneksi.php';?>
<!-- // -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PHP + MySQL + DataTables</title>
	<!-- //CSS -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/gaya.css">
	<!-- //JS -->
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/dataTables.tableTools.js"></script>
	<script type="text/javascript" src="js/dataTables.bootstrap.js"></script>
	<script type="text/javascript" src="js/datatables.js"></script>
</head>
<body>
	<div class="container" style="margin-top:50px;">
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">PuppetVersions</h3>
				</div>
				<div class="panel-body">
					<!-- //di tag table, kan ada id=contoh_gan, itu id di panggil dari direktori js/datatables.js -->
					<table id="contoh_gan" class="table table-bordered table-hover">
						<thead align="center">
							<tr>
								<th>Id</th>
								<th>Server</th>
								<th>Product</th>
								<th>Version</th>
								<th>Release</th>
								<th>Date</th>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody>
							<?php while($row =mysql_fetch_array($db)){ ?>
							<tr>
								<td><?=$row['Id'] ?></td>
								<td><?=$row['Server'] ?></td>
								<td><?=$row['Product'] ?></td>
								<td><?=$row['Version'] ?></td>
								<td><?=$row['Release'] ?></td>
								<td><?=$row['Date'] ?></td>
								<td><?=$row['Comment'] ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>