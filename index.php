<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$total_recent_news_home_page = $row['total_recent_news_home_page'];
	$home_title_service          = $row['home_title_service'];
	$home_subtitle_service       = $row['home_subtitle_service'];
	$home_status_service         = $row['home_status_service'];
	$home_title_team_member      = $row['home_title_team_member'];
	$home_subtitle_team_member   = $row['home_subtitle_team_member'];
	$home_status_team_member     = $row['home_status_team_member'];
	$home_title_testimonial      = $row['home_title_testimonial'];
	$home_subtitle_testimonial   = $row['home_subtitle_testimonial'];
	$home_photo_testimonial      = $row['home_photo_testimonial'];
	$home_status_testimonial     = $row['home_status_testimonial'];
	$home_title_news             = $row['home_title_news'];
	$home_subtitle_news          = $row['home_subtitle_news'];
	$home_status_news            = $row['home_status_news'];
	$home_title_partner          = $row['home_title_partner'];
	$home_subtitle_partner       = $row['home_subtitle_partner'];
	$home_status_partner         = $row['home_status_partner'];
	$counter_1_title             = $row['counter_1_title'];
    $counter_1_value             = $row['counter_1_value'];
    $counter_2_title             = $row['counter_2_title'];
    $counter_2_value             = $row['counter_2_value'];
    $counter_3_title             = $row['counter_3_title'];
    $counter_3_value             = $row['counter_3_value'];
    $counter_4_title             = $row['counter_4_title'];
    $counter_4_value             = $row['counter_4_value'];
    $counter_photo               = $row['counter_photo'];
    $counter_status              = $row['counter_status'];
}
?>

<!-- Slider Start -->
<section class="main-slider">
	<div class="slider">
		<ul class="bxslider">
				
			<?php
			$statement = $pdo->prepare("SELECT * FROM tbl_slider WHERE status=? ORDER BY id DESC");
			$statement->execute(array('Active'));
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
			foreach ($result as $row) 
			{
				if($row['position']=='Left') {$align='tal';}
				if($row['position']=='Center') {$align='tac';}
				if($row['position']=='Right') {$align='tar';}
				?>
				<li style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>);">
					<div class="overlay"></div>
					<div class="content">
						<div class="inner <?php echo $align; ?>">
							<div class="text">
							
								<?php if($row['heading']!=''): ?>
								<h2>
									<?php echo $row['heading']; ?>
								</h2>
								<?php endif; ?>
								
								<?php if($row['content']!=''): ?>
								<p>
									<?php echo nl2br($row['content']); ?>
								</p>
								<?php endif; ?>
								
								<?php if($row['button_text']!=''): ?>
								<p class="button">
									<a href="<?php echo $row['button_url']; ?>" class="btn btn-flat"><?php echo $row['button_text']; ?></a>
								</p>
								<?php endif; ?>

							</div>
						</div>
					</div>
				</li>
				<?php
			}
			?>			
		</ul>
	</div>
</section>
<!-- Slider End -->


<?php if($home_status_service == 'Show'): ?>
<!-- Service Start -->
<section class="service-v1">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading wow fadeInUp">
					<h2><?php echo $home_title_service; ?></h2>
					<p><?php echo $home_subtitle_service; ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<?php
			$statement = $pdo->prepare("SELECT * FROM tbl_service ORDER BY id ASC");
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
			foreach ($result as $row) {
				?>
				<div class="col-sm-6 col-md-4 ser-item wow fadeInUp">
					<div class="item">
						<div class="photo" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>);">
						</div>
						<div class="text">
							<h3><a href="<?php echo BASE_URL.URL_SERVICE.$row['slug']; ?>"><?php echo $row['name']; ?></a></h3>
							<p>
								<?php echo $row['short_description']; ?>
							</p>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
<!-- Service End -->
<?php endif; ?>



<?php if($home_status_team_member == 'Show'): ?>
<!-- Team Member Start -->
<section class="team-member-v1">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading wow fadeInUp">
					<h2><?php echo $home_title_team_member; ?></h2>
					<p><?php echo $home_subtitle_team_member; ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				
				<!-- Team Member Carousel Start -->
				<div class="team-member-carousel">
					<?php
					$statement = $pdo->prepare("SELECT 
												
												t1.id,
												t1.name,
												t1.slug,
												t1.designation_id,
												t1.photo,
												t1.facebook,
												t1.twitter,
												t1.linkedin,
												t1.youtube,
												t1.google_plus,
												t1.instagram,
												t1.flickr,

												t2.designation_id,
												t2.designation_name

					                           FROM tbl_team_member t1
					                           JOIN tbl_designation t2
					                           ON t1.designation_id = t2.designation_id
					                           WHERE t1.status = ?
					                           ");
					$statement->execute(array('Active'));
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						?>
						<div class="item wow fadeInUp">
							<div class="thumb">
								<div class="photo" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>);"></div>
								<div class="overlay"></div>
								<div class="social-icons">
									<ul>
										<?php if($row['facebook']!=''): ?>
											<li><a href="<?php echo $row['facebook']; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
										<?php endif; ?>

										<?php if($row['twitter']!=''): ?>
											<li><a href="<?php echo $row['twitter']; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
										<?php endif; ?>

										<?php if($row['linkedin']!=''): ?>
											<li><a href="<?php echo $row['linkedin']; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
										<?php endif; ?>

										<?php if($row['youtube']!=''): ?>
											<li><a href="<?php echo $row['youtube']; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
										<?php endif; ?>

										<?php if($row['google_plus']!=''): ?>
											<li><a href="<?php echo $row['google_plus']; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
										<?php endif; ?>

										<?php if($row['instagram']!=''): ?>
											<li><a href="<?php echo $row['instagram']; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
										<?php endif; ?>

										<?php if($row['flickr']!=''): ?>
											<li><a href="<?php echo $row['flickr']; ?>" target="_blank"><i class="fa fa-flickr"></i></a></li>
										<?php endif; ?>
									</ul>
								</div>
							</div>
							<div class="text">
								<h3><a href="<?php echo BASE_URL.URL_TEAM.$row['slug']; ?>"><?php echo $row['name']; ?></a></h3>
								<p><?php echo $row['designation_name']; ?></p>
							</div>
						</div>
						<?php
					}
					?>					
				</div>
				<!-- Team Member Carousel End -->

			</div>
		</div>
	</div>
</section>
<!-- Team Members End -->
<?php endif; ?>



<?php if($counter_status == 'Show'): ?>
<div class="why-us" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $counter_photo; ?>);">
	<div class="overlay"></div>
	<div class="container">
		<div class="row why-us-counter">
			<div class="col-md-3">
				<div class="counter"><?php echo $counter_1_value; ?></div>
				<div class="title"><?php echo $counter_1_title; ?></div>
			</div>
			<div class="col-md-3">
				<div class="counter"><?php echo $counter_2_value; ?></div>
				<div class="title"><?php echo $counter_2_title; ?></div>
			</div>
			<div class="col-md-3">
				<div class="counter"><?php echo $counter_3_value; ?></div>
				<div class="title"><?php echo $counter_3_title; ?></div>
			</div>
			<div class="col-md-3">
				<div class="counter"><?php echo $counter_4_value; ?></div>
				<div class="title"><?php echo $counter_4_title; ?></div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>



<?php if($home_status_news == 'Show'): ?>
<!-- News Start -->
<section class="news-v1">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading wow fadeInUp">
					<h2><?php echo $home_title_news; ?></h2>
					<p><?php echo $home_subtitle_news; ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				
				<!-- News Carousel Start -->
				<div class="news-carousel">

					<?php
					$i=0;
					$statement = $pdo->prepare("SELECT * FROM tbl_news ORDER BY news_id DESC");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						$i++;
						if($i>$total_recent_news_home_page) {break;}
						?>
						<div class="item wow fadeInUp">
							<div class="thumb">
								<div class="photo" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>);"></div>
							</div>
							<div class="text">
								<h3><a href="<?php echo BASE_URL.URL_NEWS.$row['news_slug']; ?>"><?php echo $row['news_title']; ?></a></h3>
								<?php echo substr($row['news_content'],0,120).' ...'; ?>
							</div>
						</div>
						<?php
					}
					?>
					
				</div>
				<!-- News Carousel End -->

			</div>
		</div>
	</div>
</section>
<!-- News End -->
<?php endif; ?>





<?php if($home_status_testimonial == 'Show'): ?>
<!-- Testimonial Start -->
<section class="testimonial-v1" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $home_photo_testimonial; ?>);">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading">
					<h2><?php echo $home_title_testimonial; ?></h2>
					<p><?php echo $home_subtitle_testimonial; ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				
				<!-- Testimonial Carousel Start -->
				<div class="testimonial-carousel">
					<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_testimonial ORDER BY id ASC");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						?>
						<div class="item">
							<div class="testimonial-wrapper">								
								<div class="content">									
									<div class="author">
										<div class="photo">
											<img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>" alt="<?php echo $row['name']; ?>">
										</div>
										<div class="text">
											<h3><?php echo $row['name']; ?></h3>
											<h4><?php echo $row['designation']; ?> 
											<?php if($row['company']!=''): ?>
											<span>(<?php echo $row['company']; ?>)</span>
											<?php endif; ?>
											</h4>
										</div>
									</div>	
									<div class="comment">
										<p>
											<?php echo nl2br($row['comment']); ?>
										</p>
										<div class="icon"></div>
									</div>									
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<!-- Testimonial Carousel End -->

			</div>
		</div>
	</div>
</section>
<!-- Testimonial End -->
<?php endif; ?>


	


<?php if($home_status_partner == 'Show'): ?>
<!-- Partner Start -->
<section class="partner-v1">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading">
					<h2><?php echo $home_title_partner; ?></h2>
					<p><?php echo $home_subtitle_partner; ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="partner-carousel">
					<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_partner ORDER BY id ASC");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						?>
						<div class="item">
							<div class="inner">
								<?php if($row['url']==''): ?>
									<img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>" alt="<?php echo $row['name']; ?>">
								<?php else: ?>
									<a href="<?php echo $row['url']; ?>" target="_blank"><img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>" alt="<?php echo $row['name']; ?>"></a>
								<?php endif; ?>
								
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Partner End -->
<?php endif; ?>

<?php require_once('footer.php'); ?>