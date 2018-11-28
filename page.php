<?php
require_once('header.php');

// Preventing the direct access of this page.
if(!isset($_REQUEST['slug']))
{
	header('location: index.php');
	exit;
}
else
{
	// Check the page slug is valid or not.
	$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE page_slug=? AND status=?");
	$statement->execute(array($_REQUEST['slug'],'Active'));
	$total = $statement->rowCount();
	if( $total == 0 )
	{
		header('location: index.php');
		exit;
	}
}

// Getting the detailed data of a page from page slug
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE page_slug=?");
$statement->execute(array($_REQUEST['slug']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) 
{
	$page_name    = $row['page_name'];
	$page_slug    = $row['page_slug'];
	$page_content = $row['page_content'];
	$page_layout  = $row['page_layout'];
	$banner       = $row['banner'];
	$status       = $row['status'];
}

// If a page is not active, redirect the user while direct URL press
if($status == 'Inactive')
{
	header('location: index.php');
	exit;
}
?>
		
		
<!-- Banner Start -->
<div class="page-banner" style="background-image: url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $banner; ?>)">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="banner-text">
					<h1><?php echo $page_name; ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Banner End -->


<?php if($page_layout == 'Full Width Page Layout'): ?>
<section class="about-v2">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php echo $page_content; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>


<?php if($page_layout == 'Contact Us Page Layout'): ?>
<?php
	$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) 
	{
		$contact_map_iframe = $row['contact_map_iframe'];
	}
?>
<section class="contact-v1">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading-normal">
					<h2><?php echo CONTACT_FORM; ?></h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">

<?php
// After form submit checking everything for email sending
if(isset($_POST['form_contact']))
{
	$error_message = '';
	$success_message = '';
	$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) 
	{
		$receive_email = $row['receive_email'];
		$receive_email_subject = $row['receive_email_subject'];
		$receive_email_thank_you_message = $row['receive_email_thank_you_message'];
	}

    $valid = 1;

    if(empty($_POST['visitor_name']))
    {
        $valid = 0;
        $error_message .= FULL_NAME_EMPTY_CHECK.'\n';
    }

    if(empty($_POST['visitor_phone']))
    {
        $valid = 0;
        $error_message .= PHONE_EMPTY_CHECK.'\n';
    }


    if(empty($_POST['visitor_email']))
    {
        $valid = 0;
        $error_message .= EMAIL_EMPTY_CHECK.'\n';
    }
    else
    {
    	// Email validation check
        if(!filter_var($_POST['visitor_email'], FILTER_VALIDATE_EMAIL))
        {
            $valid = 0;
            $error_message .= EMAIL_VALID_CHECK.'\n';
        }
    }

    if(empty($_POST['visitor_comment']))
    {
        $valid = 0;
        $error_message .= COMMENT_EMPTY_CHECK.'\n';
    }

    if($valid == 1)
    {
		
		$visitor_name = strip_tags($_POST['visitor_name']);
		$visitor_email = strip_tags($_POST['visitor_email']);
		$visitor_phone = strip_tags($_POST['visitor_phone']);
		$visitor_comment = strip_tags($_POST['visitor_comment']);

        // sending email
		$to_admin = $receive_email;
        $subject = $receive_email_subject;

        $message = '
<html><body>
<table>
<tr>
<td>Name</td>
<td>'.$visitor_name.'</td>
</tr>
<tr>
<td>Email</td>
<td>'.$visitor_email.'</td>
</tr>
<tr>
<td>Phone</td>
<td>'.$visitor_phone.'</td>
</tr>
<tr>
<td>Comment</td>
<td>'.nl2br($visitor_comment).'</td>
</tr>
</table>
</body></html>
';

		try {
		    
		    $mail->setFrom($visitor_email, $visitor_name);
		    $mail->addAddress($to_admin, 'Admin');
		    $mail->addReplyTo($visitor_email, $visitor_name);
		    
		    $mail->isHTML(true);
		    $mail->Subject = $subject;
  
		    $mail->Body = $message;
		    $mail->send();

		    $success_message = $receive_email_thank_you_message;    
		} catch (Exception $e) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}

    }
}
?>
				
				<?php
				if($error_message != '') {
					echo "<script>alert('".$error_message."')</script>";
				}
				if($success_message != '') {
					echo "<script>alert('".$success_message."')</script>";
				}
				?>


				<form action="<?php echo BASE_URL.URL_PAGE.$_REQUEST['slug']; ?>" class="form-horizontal cform-1" method="post">
					<div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" placeholder="<?php echo FULL_NAME; ?>" name="visitor_name">
                        </div>
                    </div>
					<div class="form-group">
                        <div class="col-sm-12">
                            <input type="email" class="form-control" placeholder="<?php echo EMAIL_ADDRESS; ?>" name="visitor_email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" placeholder="<?php echo PHONE_NUMBER; ?>" name="visitor_phone">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea name="visitor_comment" class="form-control" cols="30" rows="10" placeholder="<?php echo MESSAGE; ?>"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
	                    <div class="col-sm-12">
	                        <input type="submit" value="<?php echo SEND_MESSAGE; ?>" class="btn btn-success" name="form_contact">
	                    </div>
	                </div>
				</form>
			</div>
			<div class="col-md-5">
				<div class="google-map">
					<?php echo $contact_map_iframe; ?>
				</div>	
			</div>
			
		</div>
	</div>
</section>
<?php endif; ?>



<?php if($page_layout == 'FAQ Page Layout'): ?>
<section class="faq">
	<div class="container">
		<div class="row">
			<div class="col-md-12">			
				
				<?php
				$i=0;
				$j=0;
				$statement = $pdo->prepare("SELECT * FROM tbl_faq_category ORDER BY faq_category_id ASC");
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
				foreach ($result as $row) {
					$i++;
					?>
					<h1><?php echo $row['faq_category_name']; ?></h1>
					<div class="panel-group" id="accordion<?php echo $i; ?>" role="tablist" aria-multiselectable="true">
						<?php
						$statement1 = $pdo->prepare("SELECT * FROM tbl_faq WHERE faq_category_id=?");
						$statement1->execute(array($row['faq_category_id']));
						$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);							
						foreach ($result1 as $row1) {
							$j++;
							?>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="heading1">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion<?php echo $i; ?>" href="#collapse<?php echo $j; ?>" aria-expanded="false" aria-controls="collapse<?php echo $j; ?>">
											<?php echo $row1['faq_title']; ?>
										</a>
									</h4>
									
								</div>
								<div id="collapse<?php echo $j; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
									<div class="panel-body">
										<?php echo $row1['faq_content']; ?>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>				
			</div>			
		</div>
	</div>
</section>
<?php endif; ?>



<?php if($page_layout == 'Photo Gallery Page Layout'): ?>
<section class="gallery">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<ul class="gallery-menu">
					<li class="filter" data-filter="all" data-role="button">All</li>
					<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_category_photo WHERE status=?");
					$statement->execute(array('Active'));
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						$temp_string = strtolower($row['p_category_name']);
    					$temp_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    					?>
    					<li class="filter" data-filter=".<?php echo $temp_slug; ?>" data-role="button"><?php echo $row['p_category_name']; ?></li>
						<?php
					}
					?>
				</ul>

				<div id="mix-container">
					<?php
					$i=0;
					$statement = $pdo->prepare("SELECT
					                           	t1.photo_id,
												t1.photo_caption,
												t1.photo_name,
												t1.p_category_id,
												t2.p_category_id,
												t2.p_category_name,
												t2.status
					                            FROM tbl_photo t1
					                            JOIN tbl_category_photo t2
					                            ON t1.p_category_id = t2.p_category_id 
					                            ");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						$i++;
						$temp_string = strtolower($row['p_category_name']);
    					$temp_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
						?>
						<div class="col-md-4 mix <?php echo $temp_slug; ?> all" data-my-order="<?php echo $i; ?>">
							<div class="inner">
								<div class="photo" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo_name']; ?>);"></div>
								<div class="overlay"></div>
								<div class="icons">
									<div class="icons-inner">
										<a class="gallery-photo" href="<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo_name']; ?>"><i class="fa fa-search-plus"></i></a>
									</div>
								</div>
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
<?php endif; ?>





<?php if($page_layout == 'Video Gallery Page Layout'): ?>
<section class="gallery">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<ul class="gallery-menu">
					<li class="filter" data-filter="all" data-role="button">All</li>
					<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_category_video WHERE status=?");
					$statement->execute(array('Active'));
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						$temp_string = strtolower($row['v_category_name']);
    					$temp_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    					?>
    					<li class="filter" data-filter=".<?php echo $temp_slug; ?>" data-role="button"><?php echo $row['v_category_name']; ?></li>
						<?php
					}
					?>
				</ul>

				<div id="mix-container">
					<?php
					$i=0;
					$statement = $pdo->prepare("SELECT
					                           	t1.video_id,
												t1.video_title,
												t1.video_iframe,
												t1.v_category_id,
												t2.v_category_id,
												t2.v_category_name,
												t2.status
					                            FROM tbl_video t1
					                            JOIN tbl_category_video t2
					                            ON t1.v_category_id = t2.v_category_id 
					                            ");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						$i++;
						$temp_string = strtolower($row['v_category_name']);
    					$temp_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
						?>
						<div class="col-md-4 mix <?php echo $temp_slug; ?> all" data-my-order="<?php echo $i; ?>">
							<div class="inner viframe">
								<?php echo $row['video_iframe']; ?>
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
<?php endif; ?>



<?php if($page_layout == 'Blog Page Layout'): ?>
<section class="blog">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				
				<!-- Blog Classic Start -->
				<div class="blog-grid">
					<div class="row">
						<div class="col-md-12">
							

							<?php
							$statement = $pdo->prepare("SELECT * FROM tbl_news ORDER BY news_id DESC");
							$statement->execute();
							$total = $statement->rowCount();
							?>

							<?php if(!$total): ?>
							<p style="color:red;"><?php echo NEWS_EMPTY_CHECK; ?></p>
							<?php else: ?>




<?php
/* ===================== Pagination Code Starts ================== */
		$adjacents = 10;	
		
		$statement = $pdo->prepare("SELECT * FROM tbl_news ORDER BY news_id DESC");
		$statement->execute();
		$total_pages = $statement->rowCount();
		
		$targetpage = $_SERVER['PHP_SELF'];
		$limit = 5;                                 
		$page = @$_GET['page'];
		if($page) 
			$start = ($page - 1) * $limit;          
		else
			$start = 0;	
		

		$statement = $pdo->prepare("SELECT
								   t1.news_title,
		                           t1.news_slug,
		                           t1.news_content,
		                           t1.news_date,
		                           t1.photo,
		                           t1.category_id,

		                           t2.category_id,
		                           t2.category_name,
		                           t2.category_slug
		                           FROM tbl_news t1
		                           JOIN tbl_category t2
		                           ON t1.category_id = t2.category_id 		                           
		                           ORDER BY t1.news_id 
		                           LIMIT $start, $limit");
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		
		$s1 = $_REQUEST['slug'];
				
		if ($page == 0) $page = 1;                  
		$prev = $page - 1;                          
		$next = $page + 1;                          
		$lastpage = ceil($total_pages/$limit);      
		$lpm1 = $lastpage - 1;   
		$pagination = "";
		if($lastpage > 1)
		{   
			$pagination .= "<div class=\"pagination\">";
			if ($page > 1) 
				$pagination.= "<a href=\"$targetpage?slug=$s1&page=$prev\">&#171; ".PREVIOUS."</a>";
			else
				$pagination.= "<span class=\"disabled\">&#171; ".PREVIOUS."</span>";    
			if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
			{   
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?slug=$s1&page=$counter\">$counter</a>";                 
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
			{
				if($page < 1 + ($adjacents * 2))        
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?slug=$s1&page=$counter\">$counter</a>";                 
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?slug=$s1&page=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?slug=$s1&page=$lastpage\">$lastpage</a>";       
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<a href=\"$targetpage?slug=$s1&page=1\">1</a>";
					$pagination.= "<a href=\"$targetpage?slug=$s1&page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?slug=$s1&page=$counter\">$counter</a>";                 
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?slug=$s1&page=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?slug=$s1&page=$lastpage\">$lastpage</a>";       
				}
				else
				{
					$pagination.= "<a href=\"$targetpage?slug=$s1&page=1\">1</a>";
					$pagination.= "<a href=\"$targetpage?slug=$s1&page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?slug=$s1&page=$counter\">$counter</a>";                 
					}
				}
			}
			if ($page < $counter - 1) 
				$pagination.= "<a href=\"$targetpage?slug=$s1&page=$next\">".NEXT." &#187;</a>";
			else
				$pagination.= "<span class=\"disabled\">".NEXT." &#187;</span>";
			$pagination.= "</div>\n";       
		}
		/* ===================== Pagination Code Ends ================== */
		?>

							<?php
							foreach ($result as $row) {
								?>
								<div class="post-item">
									<div class="image-holder">
										<img class="img-responsive" src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>" alt="<?php echo $row['news_title']; ?>">
									</div>
									<div class="text">
										<div class="inner">
											<h3><a href="<?php echo BASE_URL.URL_NEWS.$row['news_slug']; ?>"><?php echo $row['news_title']; ?></a></h3>
											<ul class="status">
												<li><i class="fa fa-tag"></i><?php echo CATEGORY_COLON; ?> <a href="<?php echo BASE_URL.URL_CATEGORY.$row['category_slug']; ?>"><?php echo $row['category_name']; ?></a></li>
												<li><i class="fa fa-calendar"></i><?php echo POSTED_ON; ?> <?php echo $row['news_date']; ?></li>
											</ul>
											<p>
												<?php echo substr($row['news_content'],0,200).' ...'; ?>
											</p>
											<p class="button">
												<a href="<?php echo BASE_URL.URL_NEWS.$row['news_slug']; ?>"><?php echo READ_MORE; ?></a>
											</p>
										</div>
									</div>
								</div>
								<?php
							}
							?>							
							<?php endif; ?>

						</div>

						<div class="col-md-12">
							<?php if($total): ?>
							<?php echo $pagination; ?>
							<?php endif; ?>
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
<?php endif; ?>



<?php if($page_layout == 'Team Member Page Layout'): ?>
<section class="team-member-v3">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				
				<!-- Team Member Container Start -->
				<div class="team-member-inner">
					
					<?php
					$statement = $pdo->prepare("SELECT
												
												t1.id,
												t1.name,
												t1.slug,
												t1.designation_id,
												t1.photo,
												t1.degree,
												t1.detail,
												t1.facebook,
												t1.twitter,
												t1.linkedin,
												t1.youtube,
												t1.google_plus,
												t1.instagram,
												t1.flickr,
												t1.address,
												t1.practice_location,
												t1.phone, 
												t1.email,
												t1.website,
												t1.status,

												t2.designation_id,
												t2.designation_name
						
					                            FROM tbl_team_member t1
					                            JOIN tbl_designation t2
					                            ON t1.designation_id = t2.designation_id
					                            WHERE t1.status=?
					                            ");
					$statement->execute(array('Active'));
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
					foreach ($result as $row) {
						?>
						<div class="col-md-3 item">
							<div class="inner">
								<div class="thumb">
									<div class="photo" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>)"></div>
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
									<h4><?php echo $row['designation_name']; ?></h4>
									<p class="button">
										<a href="<?php echo BASE_URL.URL_TEAM.$row['slug']; ?>"><?php echo SEE_FULL_PROFILE; ?></a>
									</p>
								</div>
							</div>
						</div>
						<?php
					}
					?>
					
				</div>
				<!-- Team Member Container End -->

			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php require_once('footer.php'); ?>