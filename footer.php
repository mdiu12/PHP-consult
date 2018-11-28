	<?php
	$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) 
	{
		$footer_about                = $row['footer_about'];
		$footer_copyright            = $row['footer_copyright'];
		$contact_address             = $row['contact_address'];
		$contact_email               = $row['contact_email'];
		$contact_phone               = $row['contact_phone'];
		$contact_fax                 = $row['contact_fax'];
		$total_recent_news_footer    = $row['total_recent_news_footer'];
		$total_popular_news_footer   = $row['total_popular_news_footer'];
		$total_recent_news_sidebar   = $row['total_recent_news_sidebar'];
		$total_popular_news_sidebar  = $row['total_popular_news_sidebar'];
		$total_recent_news_home_page = $row['total_recent_news_home_page'];
		$newsletter_title            = $row['newsletter_title'];
		$newsletter_text             = $row['newsletter_text'];
		$newsletter_photo            = $row['newsletter_photo'];
		$newsletter_status           = $row['newsletter_status'];

		$receive_email = $row['receive_email'];
	}
	?>


	<?php if($newsletter_status=='Show'): ?>
	<div class="newsletter-area" style="background-image: url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $newsletter_photo; ?>);">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="newsletter-headline wow fadeInUp">
						<h2><?php echo $newsletter_title; ?></h2>
						<?php if($newsletter_text!=''): ?>
						<p>
							<?php echo nl2br($newsletter_text); ?>
						</p>
						<?php endif; ?>
					</div>
					<div class="newsletter-submit wow fadeInUp">
						<?php
			if(isset($_POST['form_subscribe']))
			{

				if(empty($_POST['email_subscribe'])) 
			    {
			        $valid = 0;
			        $error_message1 .= EMAIL_EMPTY_CHECK;
			    }
			    else
			    {
			    	if (filter_var($_POST['email_subscribe'], FILTER_VALIDATE_EMAIL) === false)
				    {
				        $valid = 0;
				        $error_message1 .= EMAIL_VALID_CHECK;
				    }
				    else
				    {
				    	$statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_email=?");
				    	$statement->execute(array($_POST['email_subscribe']));
				    	$total = $statement->rowCount();							
				    	if($total)
				    	{
				    		$valid = 0;
				        	$error_message1 .= EMAIL_EXIST_CHECK;
				    	}
				    	else
				    	{
				    		// Sending email to the requested subscriber for email confirmation
				    		// Getting activation key to send via email. also it will be saved to database until user click on the activation link.
				    		$key = md5(uniqid(rand(), true));

				    		// Getting current date
				    		$current_date = date('Y-m-d');

				    		// Getting current date and time
				    		$current_date_time = date('Y-m-d H:i:s');

				    		// Inserting data into the database
				    		$statement = $pdo->prepare("INSERT INTO tbl_subscriber (subs_email,subs_date,subs_date_time,subs_hash,subs_active) VALUES (?,?,?,?,?)");
				    		$statement->execute(array($_POST['email_subscribe'],$current_date,$current_date_time,$key,0));

				    		// Sending Confirmation Email
				    		$to = $_POST['email_subscribe'];
							$subject = 'Subscriber Email Confirmation';
							
							// Getting the url of the verification link
							$verification_url = BASE_URL.'verify.php?email='.$to.'&key='.$key;

							$message = '
Thanks for your interest to subscribe our newsletter!<br><br>
Please click this link to confirm your subscription:
					'.$verification_url.'<br><br>
This link will be active only for 24 hours.
					';


							try {
							    $mail->setFrom($receive_email, 'Admin');
							    $mail->addAddress($to);
							    $mail->addReplyTo($receive_email, 'Admin');
							    
							    $mail->isHTML(true);
							    $mail->Subject = $subject;
					  
							    $mail->Body = $message;
							    $mail->send();

							    $success_message1 = SUBSCRIPTION_SUCCESS_MESSAGE;
							} catch (Exception $e) {
							    echo 'Message could not be sent.';
							    echo 'Mailer Error: ' . $mail->ErrorInfo;
							}							
				    	}
				    }
			    }
			}
			if($error_message1 != '') {
				echo "<script>alert('".$error_message1."')</script>";
			}
			if($success_message1 != '') {
				echo "<script>alert('".$success_message1."')</script>";
			}
			?>
						<form action="" method="post">
							<input type="text" placeholder="<?php echo ENTER_YOUR_EMAIL; ?>" name="email_subscribe">
							<input type="submit" value="<?php echo SUBMIT; ?>" name="form_subscribe">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

		
		<!-- Footer Main Start -->
		<section class="footer-main">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-md-3 col-lg-3 footer-col wow fadeInLeft">
						<h3><?php echo ABOUT_US; ?></h3>
						<p>
							<?php echo nl2br($footer_about); ?>
						</p>
					</div>
					<div class="col-sm-6 col-md-3 col-lg-3 footer-col wow fadeInLeft">
						<h3><?php echo LATEST_NEWS; ?></h3>
						<?php
						$i=0;
						$statement = $pdo->prepare("SELECT * FROM tbl_news ORDER BY news_id DESC");
						$statement->execute();
						$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
						foreach ($result as $row) {
							$i++;
							if($i>$total_recent_news_footer) {break;}
							?>
							<div class="news-item">
								<div class="news-title"><a href="<?php echo BASE_URL.URL_NEWS.$row['news_slug']; ?>"><?php echo $row['news_title']; ?></a></div>
							</div>
							<?php
						}
						?>
					</div>
					<div class="col-sm-6 col-md-3 col-lg-3 footer-col wow fadeInRight">
						<h3><?php echo POPULAR_NEWS; ?></h3>
						<?php
						$i=0;
						$statement = $pdo->prepare("SELECT * FROM tbl_news ORDER BY total_view DESC");
						$statement->execute();
						$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
						foreach ($result as $row) {
							$i++;
							if($i>$total_popular_news_footer) {break;}
							?>
							<div class="news-item">
								<div class="news-title"><a href="<?php echo BASE_URL.URL_NEWS.$row['news_slug']; ?>"><?php echo $row['news_title']; ?></a></div>
							</div>
							<?php
						}
						?>
					</div>
					<div class="col-sm-6 col-md-3 col-lg-3 footer-col wow fadeInRight">
						<h3><?php echo CONTACT_US; ?></h3>
						<div class="contact-item">
							<div class="icon"><i class="fa fa-map-marker"></i></div>
							<div class="text"><?php echo $contact_address; ?></div>
						</div>
						<div class="contact-item">
							<div class="icon"><i class="fa fa-phone"></i></div>
							<div class="text"><?php echo $contact_phone; ?></div>
						</div>
						<div class="contact-item">
							<div class="icon"><i class="fa fa-fax"></i></div>
							<div class="text"><?php echo $contact_fax; ?></div>
						</div>
						<div class="contact-item">
							<div class="icon"><i class="fa fa-envelope-o"></i></div>
							<div class="text"><?php echo $contact_email; ?></div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Footer Main End -->

		
		<!-- Footer Bottom Start -->
		<section class="footer-bottom">
			<div class="container">
				<div class="row">
					<div class="col-md-12 copyright" style="text-align: center;">
						<?php echo $footer_copyright; ?>
					</div>
				</div>
			</div>
		</section>
		<!-- Footer Bottom End -->

		<a href="#" class="scrollup">
			<i class="fa fa-angle-up"></i>
		</a>

	</div>


	<!-- Scripts -->
	<script src="<?php echo BASE_URL; ?>assets/js/jquery-2.2.4.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/jquery.slicknav.min.js"></script>	
	<script src="<?php echo BASE_URL; ?>assets/js/hoverIntent.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/superfish.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/owl.carousel.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/owl.animate.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/wow.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/jquery.bxslider.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/jquery.mixitup.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/jquery.magnific-popup.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/waypoints.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/jquery.counterup.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/custom.js"></script>
	
</body>
</html>