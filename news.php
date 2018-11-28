<?php require_once('header.php'); ?>
		
<?php
// Preventing the direct access of this page.
if(!isset($_REQUEST['slug']))
{
	header('location: '.BASE_URL);
	exit;
}

// Getting the news detailed data from the news id
$statement = $pdo->prepare("SELECT
							t1.news_title,
							t1.news_slug,
							t1.news_content,
							t1.news_date,
							t1.publisher,
							t1.photo,
							t1.category_id,
							
							t2.category_id,
							t2.category_name,
							t2.category_slug

                           	FROM tbl_news t1
                           	JOIN tbl_category t2
                           	ON t1.category_id = t2.category_id
                           	WHERE t1.news_slug=?");
$statement->execute(array($_REQUEST['slug']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$news_title    = $row['news_title'];
	$news_content  = $row['news_content'];
	$news_date     = $row['news_date'];
	$publisher     = $row['publisher'];
	$photo         = $row['photo'];
	$category_id   = $row['category_id'];
	$category_slug = $row['category_slug'];
	$category_name = $row['category_name'];
}

// Update data for view count for this news page
// Getting current view count
$statement = $pdo->prepare("SELECT * FROM tbl_news WHERE news_slug=?");
$statement->execute(array($_REQUEST['slug']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) 
{
	$current_total_view = $row['total_view'];
}
$updated_total_view = $current_total_view+1;

// Updating database for view count
$statement = $pdo->prepare("UPDATE tbl_news SET total_view=? WHERE news_slug=?");
$statement->execute(array($updated_total_view,$_REQUEST['slug']));
?>


<!-- Banner Start -->
<div class="page-banner page-banner-single" style="background:none;">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="banner-text">
					<h1><?php echo $news_title; ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Banner End -->


<!-- Blog Start -->
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
								<div class="image-holder image-holder-single">
									<img class="img-responsive" src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $photo ?>" alt="<?php echo $news_title; ?>">
								</div>
								<div class="text text-single">
									<h3><?php echo $news_title; ?></h3>
									<ul class="status">
										<li><i class="fa fa-tag"></i><?php echo CATEGORY_COLON; ?> <a href="<?php echo BASE_URL.URL_CATEGORY.$category_slug; ?>"><?php echo $category_name; ?></a></li>
										<li><i class="fa fa-calendar"></i><?php echo DATE; ?> <?php echo $news_date; ?></li>
									</ul>
									<p>
										<?php echo $news_content; ?>
									</p>
									<h3><?php echo SHARE_THIS; ?></h3>
									<div class="sharethis-inline-share-buttons"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h3><?php echo COMMENTS; ?></h3>
									<?php
									// Getting the full url of the current page
									$final_url = BASE_URL.URL_NEWS.$_REQUEST['slug'];
									?>
									<!-- Facebook Comment Main Code (got from facebook website) -->
									<div class="fb-comments" data-href="<?php echo $final_url; ?>" data-numposts="5"></div>
								</div>
							</div>
							<!-- Post Item End -->

						</div>

					</div>
				</div>
				<!-- Blog Classic End -->
				

			</div>
			<div class="col-md-3">
				<?php require_once('sidebar.php'); ?>
			</div>

		</div>
	</div>
</section>
<!-- Blog End -->

<?php require_once('footer.php'); ?>