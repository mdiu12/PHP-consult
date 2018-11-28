<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['faq_title'])) {
        $valid = 0;
        $error_message .= "FAQ Title can not be empty<br>";
    }

    if(empty($_POST['faq_content'])) {
        $valid = 0;
        $error_message .= "FAQ Content can not be empty<br>";
    }
    
    if(empty($_POST['faq_category_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a faq category<br>";
    }
        
    if($valid == 1) {

    	// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_faq SET faq_title=?, faq_content=?, faq_category_id=? WHERE faq_id=?");
		$statement->execute(array($_POST['faq_title'],$_POST['faq_content'],$_POST['faq_category_id'],$_REQUEST['id']));
    	    	
    	$success_message = 'FAQ is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_faq WHERE faq_id=?");
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
		<h1>Edit FAQ</h1>
	</div>
	<div class="content-header-right">
		<a href="faq.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php							
foreach ($result as $row) {
	$faq_title       = $row['faq_title'];
	$faq_content     = $row['faq_content'];
	$faq_category_id = $row['faq_category_id'];
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
							<label for="" class="col-sm-2 control-label">FAQ Title <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="faq_title" value="<?php echo $faq_title; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">FAQ Content <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="faq_content" id="editor1"><?php echo $faq_content; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Select FAQ Category <span>*</span></label>
							<div class="col-sm-4">
								<select class="form-control select2" name="faq_category_id">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_faq_category ORDER BY faq_category_id ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
									foreach ($result as $row) {
										if($row['faq_category_id'] == $faq_category_id) {
											$selected = 'selected';
										} else {
											$selected = '';
										}
										echo '<option value="'.$row['faq_category_id'].'" '.$selected.'>'.$row['faq_category_name'].'</option>';
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