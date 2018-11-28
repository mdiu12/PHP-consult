<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['faq_category_name'])) {
        $valid = 0;
        $error_message .= "FAQ Category Name can not be empty<br>";
    } else {
		// Duplicate checking
    	// current name that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_faq_category WHERE faq_category_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_faq_category_name = $row['faq_category_name'];
		}

		$statement = $pdo->prepare("SELECT * FROM tbl_faq_category WHERE faq_category_name=? and faq_category_name!=?");
    	$statement->execute(array($_POST['faq_category_name'],$current_faq_category_name));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'FAQ Category Name already exists<br>';
    	}
    }

    if($valid == 1) {

    	// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_faq_category SET faq_category_name=? WHERE faq_category_id=?");
		$statement->execute(array($_POST['faq_category_name'],$_REQUEST['id']));

    	$success_message = 'FAQ Category is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_faq_category WHERE faq_category_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit FAQ Category</h1>
	</div>
	<div class="content-header-right">
		<a href="faq-category.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php							
foreach ($result as $row) {
	$faq_category_name = $row['faq_category_name'];
}
?>


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
							<label for="" class="col-sm-2 control-label">FAQ Category Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="faq_category_name" value="<?php echo $faq_category_name; ?>" autocomplete="off">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Update</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>