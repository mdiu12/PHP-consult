<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['news_title'])) {
		$valid = 0;
		$error_message .= 'News title can not be empty<br>';
	} else {
		// Duplicate Checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_news WHERE news_title=?");
    	$statement->execute(array($_POST['news_title']));
    	$total = $statement->rowCount();
    	if($total) {
    		$valid = 0;
        	$error_message .= "News title already exists<br>";
    	}
	}

	if(empty($_POST['news_content'])) {
		$valid = 0;
		$error_message .= 'News content can not be empty<br>';
	}

	if(empty($_POST['news_date'])) {
		$valid = 0;
		$error_message .= 'News publish date can not be empty<br>';
	}

	if(empty($_POST['category_id'])) {
		$valid = 0;
		$error_message .= 'You must have to select a category<br>';
	}


	if($_POST['publisher'] == '') {
		$publisher = $_SESSION['user']['full_name'];
	} else {
		$publisher = $_POST['publisher'];	
	}


	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];


    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }
	

	if($valid == 1) {

		// getting auto increment id for photo renaming
		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_news'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

		if($_POST['news_slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['news_title']);
    		$news_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	} else {
    		$temp_string = strtolower($_POST['news_slug']);
    		$news_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_news WHERE news_slug=?");
		$statement->execute(array($news_slug));
		$total = $statement->rowCount();
		if($total) {
			$news_slug = $news_slug.'-1';
		}

		if($path=='') {
			// When no photo will be selected
			$statement = $pdo->prepare("INSERT INTO tbl_news (news_title,news_slug,news_content,news_date,photo,category_id,publisher,total_view,meta_title,meta_keyword,meta_description) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
			$statement->execute(array($_POST['news_title'],$news_slug,$_POST['news_content'],$_POST['news_date'],'',$_POST['category_id'],$publisher,0,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description']));
		} else {
    		// uploading the photo into the main location and giving it a final name
    		$final_name = 'news-'.$ai_id.'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

            $statement = $pdo->prepare("INSERT INTO tbl_news (news_title,news_slug,news_content,news_date,photo,category_id,publisher,total_view,meta_title,meta_keyword,meta_description) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
			$statement->execute(array($_POST['news_title'],$news_slug,$_POST['news_content'],$_POST['news_date'],$final_name,$_POST['category_id'],$publisher,0,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description']));
		}
	
		$success_message = 'News is added successfully!';
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add News</h1>
	</div>
	<div class="content-header-right">
		<a href="news.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if($error_message): ?>
			<div class="callout callout-danger">
				<p>
				<?php echo $error_message; ?>
				</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
				<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">News Title <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="news_title" placeholder="Example: News Headline">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">News Slug </label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="news_slug" placeholder="Example: news-headline">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">News Content <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="news_content" id="editor1"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">News Publish Date <span>*</span></label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="news_date" id="datepicker" value="<?php echo date('d-m-Y'); ?>">(Format: dd-mm-yy)
							</div>
						</div>
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Featured Photo</label>
				            <div class="col-sm-6" style="padding-top:6px;">
				                <input type="file" name="photo">
				            </div>
				        </div>
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Select Category <span>*</span></label>
				            <div class="col-sm-3">
				            	<select class="form-control select2" name="category_id">
				            		<option value="">Select a category</option>
				            		<?php
						            	$i=0;
						            	$statement = $pdo->prepare("SELECT * FROM tbl_category ORDER BY category_name ASC");
						            	$statement->execute();
						            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
						            	foreach ($result as $row) {
						            		?>
											<option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></option>
						            		<?php
						            	}
					            	?>
				            	</select>
				            </div>
				        </div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Publisher </label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="publisher"> (If you keep this blank, logged user will be treated as the publisher)
							</div>
						</div>
						<h3 class="seo-info">SEO Information</h3>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Title </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="meta_title">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Keywords </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="meta_keyword">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Description </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="meta_description" style="height:200px;"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>