<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['video_title'])) {
        $valid = 0;
        $error_message .= "Video title can not be empty<br>";
    }

    if(empty($_POST['video_iframe'])) {
        $valid = 0;
        $error_message .= "Video iframe code can not be empty<br>";
    }
    
    if(empty($_POST['v_category_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a video category<br>";
    }
    
    if($valid == 1) {

		// saving into the database
		$statement = $pdo->prepare("INSERT INTO tbl_video (video_title,video_iframe,v_category_id) VALUES (?,?,?)");
		$statement->execute(array($_POST['video_title'],$_POST['video_iframe'],$_POST['v_category_id']));

    	$success_message = 'Video is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Video</h1>
	</div>
	<div class="content-header-right">
		<a href="video.php" class="btn btn-primary btn-sm">View All</a>
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

			<form class="form-horizontal" action="" method="post">

				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Video Title <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="video_title">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">iframe Code <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="video_iframe" style="height:200px;"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Video Category <span>*</span></label>
							<div class="col-sm-4">
								<select class="form-control" name="v_category_id">
									<option value="">Select a video category</option>
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_category_video ORDER BY v_category_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
									foreach ($result as $row) {
										echo '<option value="'.$row['v_category_id'].'">'.$row['v_category_name'].'</option>';
									}
									?>
								</select>
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