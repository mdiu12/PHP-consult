<?php require_once('header.php'); ?>

<?php
// Preventing the direct access of this page.
if(!isset($_REQUEST['slug']))
{
	header('location: '.BASE_URL);
	exit;
}
else
{
	// Check the page slug is valid or not.
	$statement = $pdo->prepare("SELECT * FROM tbl_service WHERE slug=?");
	$statement->execute(array($_REQUEST['slug']));
	$total = $statement->rowCount();
	if( $total == 0 )
	{
		header('location: '.BASE_URL);
		exit;
	}
}

// Getting the detailed data of a service from slug
$statement = $pdo->prepare("SELECT * FROM tbl_service WHERE slug=?");
$statement->execute(array($_REQUEST['slug']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);				
foreach ($result as $row)
{
	$name              = $row['name'];
	$slug              = $row['slug'];
	$short_description = $row['short_description'];
	$description       = $row['description'];
	$photo             = $row['photo'];
	$banner            = $row['banner'];
}
?>
				
<!-- Banner Start -->
<div class="page-banner" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $banner; ?>);">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="banner-text">
					<h1><?php echo SERVICE_COLON; ?> <?php echo $name; ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Banner End -->


<!-- Service Start -->
<section class="blog">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				
				<!-- Blog Classic Start -->
				<div class="blog-grid">
					<div class="row">
						<div class="col-md-12">
							

							<!-- Post Item Start -->
							<div class="post-item">
								<div class="image-holder image-holder-service">
									<img class="img-responsive" src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $photo; ?>" alt="">
								</div>
								<div class="text text-service">
									<?php echo $description; ?>
								</div>
							</div>
							<!-- Post Item End -->

						</div>

					</div>
				</div>
				<!-- Blog Classic End -->

			</div>
			<div class="col-md-3">
				
				<!-- Sidebar Container Start -->
				<div class="sidebar">
					<div class="widget">
						<h4><?php echo SERVICES; ?></h4>
						<ul>
							<?php
							$statement = $pdo->prepare("SELECT * FROM tbl_service ORDER BY name ASC");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
							foreach ($result as $row) {
								?>
								<li><a href="<?php echo BASE_URL.URL_SERVICE.$row['slug']; ?>"><?php echo $row['name']; ?></a></li>
								<?php
							}
							?>							
						</ul>
					</div>							
				</div>
				<!-- Sidebar Container End -->
			
			</div>

			


		</div>
	</div>
</section>
<!-- Service End -->
			
<?php require_once('footer.php'); ?>