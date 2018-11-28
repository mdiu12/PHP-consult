<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['v_category_name'])) {
        $valid = 0;
        $error_message .= "Video Category Name can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_category_video WHERE v_category_name=?");
    	$statement->execute(array($_POST['v_category_name']));
    	$total = $statement->rowCount();
    	if($total) {
    		$valid = 0;
        	$error_message .= "Video Category Name already exists<br>";
    	}
    }

    if($valid == 1) {

		// saving into the database
		$statement = $pdo->prepare("INSERT INTO tbl_category_video (v_category_name,status) VALUES (?,?)");
		$statement->execute(array($_POST['v_category_name'],$_POST['status']));

    	$success_message = 'Video Category is added successfully.';
    }
}
?>


<section class="content-header">
	<div class="content-header-left">
		<h1>Add Video Category</h1>
	</div>
	<div class="content-header-right">
		<a href="video-category.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">Category Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="v_category_name">
							</div>
						</div>
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Active <span>*</span></label>
				            <div class="col-sm-6">
				                <label class="radio-inline">
				                    <input type="radio" name="status" value="Active" checked>Yes
				                </label>
				                <label class="radio-inline">
				                    <input type="radio" name="status" value="Inactive">No
				                </label>
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