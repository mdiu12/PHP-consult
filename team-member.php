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
	$statement = $pdo->prepare("SELECT * FROM tbl_team_member WHERE slug=?");
	$statement->execute(array($_REQUEST['slug']));
	$total = $statement->rowCount();
	if( $total == 0 )
	{
		header('location: '.BASE_URL);
		exit;
	}
}

// Getting the detailed data of a service from slug
$statement = $pdo->prepare("SELECT * FROM tbl_team_member WHERE slug=?");
$statement->execute(array($_REQUEST['slug']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);				
foreach ($result as $row)
{
	$name              = $row['name'];
	$slug              = $row['slug'];
	$designation_id    = $row['designation_id'];
	$photo             = $row['photo'];
	$banner            = $row['banner'];
	$degree            = $row['degree'];
	$detail            = $row['detail'];
	$facebook          = $row['facebook'];
	$twitter           = $row['twitter'];
	$linkedin          = $row['linkedin'];
	$youtube           = $row['youtube'];
	$google_plus       = $row['google_plus'];
	$instagram         = $row['instagram'];
	$flickr            = $row['flickr'];
	$address           = $row['address'];
	$practice_location = $row['practice_location'];
	$phone             = $row['phone'];
	$email             = $row['email'];
	$website           = $row['website'];
	$status            = $row['status'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_designation WHERE designation_id=?");
$statement->execute(array($designation_id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);				
foreach ($result as $row)
{
	$designation_name = $row['designation_name'];
}
?>

<!-- Banner Start -->
<div class="page-banner" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $banner; ?>);">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="banner-text">
					<h1><?php echo TEAM_MEMBER_COLON; ?> <?php echo $name; ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Banner End -->


<!-- Team Member Start -->
<section class="team-member-detail">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="team-member-single">
					<div class="thumb">
						<img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $photo; ?>" alt="<?php echo $name; ?>">
					</div>
					<div class="text">
						<h2><?php echo $name; ?></h2>
						<h3><?php echo $designation_name; ?></h3>
						<p>
							<?php echo $degree; ?>
						</p>
					</div>
					<div class="social">
						<div class="title">
							<?php echo SOCIAL_MEDIA_HEADLINE; ?>
						</div>
						<ul>
							<?php if($facebook!=''): ?>
								<li><a href="<?php echo $facebook; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
							<?php endif; ?>

							<?php if($twitter!=''): ?>
								<li><a href="<?php echo $twitter; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
							<?php endif; ?>

							<?php if($linkedin!=''): ?>
								<li><a href="<?php echo $linkedin; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
							<?php endif; ?>

							<?php if($youtube!=''): ?>
								<li><a href="<?php echo $youtube; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
							<?php endif; ?>

							<?php if($google_plus!=''): ?>
								<li><a href="<?php echo $google_plus; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
							<?php endif; ?>

							<?php if($instagram!=''): ?>
								<li><a href="<?php echo $instagram; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
							<?php endif; ?>

							<?php if($flickr!=''): ?>
								<li><a href="<?php echo $flickr; ?>" target="_blank"><i class="fa fa-flickr"></i></a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				
				<!-- Team Member Detail Tab Start -->
				<div class="team-member-detail-tab">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true"><?php echo ABOUT; ?></a></li>
						<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false"><?php echo CONTACT; ?></a></li>
					</ul>
					
					<!-- Tab Content Start -->
					<div class="tab-content">
						<div class="tab-pane fade active in" id="tab1">
							<div class="row">										
								<div class="col-md-12">
									<div class="content">
										<?php echo $detail; ?>										
									</div>
								</div>
							</div>
						</div>
						
						<div class="tab-pane fade" id="tab2">
							<div class="row">
								<div class="col-md-12">
									<div class="content">									
										<div class="row">
											<div class="col-md-6">
												<div class="contact">
													<div class="icon"><i class="fa fa-map-o"></i></div>
													<div class="text">
														<h4><?php echo ADDRESS; ?></h4>
														<p>
															<?php if($address!=''): ?>
																<?php echo $address; ?>
															<?php else: ?>
																N/A
															<?php endif; ?>
														</p>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="contact">
													<div class="icon"><i class="fa fa-phone"></i></div>
													<div class="text">
														<h4><?php echo PHONE_NUMBER; ?></h4>
														<p>
															<?php if($phone!=''): ?>
																<?php echo $phone; ?>
															<?php else: ?>
																N/A
															<?php endif; ?>
														</p>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="contact">
													<div class="icon"><i class="fa fa-envelope"></i></div>
													<div class="text">
														<h4><?php echo EMAIL_ADDRESS; ?></h4>
														<p>
															<?php if($email!=''): ?>
																<?php echo $email; ?>
															<?php else: ?>
																N/A
															<?php endif; ?>
														</p>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="contact">
													<div class="icon"><i class="fa fa-globe"></i></div>
													<div class="text">
														<h4><?php echo WEBSITE; ?></h4>
														<p>
															<?php if($website!=''): ?>
																<?php echo $website; ?>
															<?php else: ?>
																N/A
															<?php endif; ?>
														</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>									
						</div>
					</div>
					<!-- Tab Content End -->
				</div>
				<!-- Team Member Detail Tab End -->

			</div>
		</div>
	</div>
</section>
<!-- Team Member End -->


<?php require_once('footer.php'); ?>