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
    
    if($valid == 1) {

		// saving into the database
		$statement = $pdo->prepare("INSERT INTO tbl_faq (faq_title,faq_content,faq_category_id) VALUES (?,?,?)");
		$statement->execute(array($_POST['faq_title'],$_POST['faq_content'],$_POST['faq_category_id']));

    	$success_message = 'FAQ is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add FAQ</h1>
	</div>
	<div class="content-header-right">
		<a href="faq.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">FAQ Title <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="faq_title">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">FAQ Content <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="faq_content" id="editor1"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Select FAQ Category <span>*</span></label>
							<div class="col-sm-4">
								<select class="form-control select2" name="faq_category_id">
									<option value="">Select a faq category</option>
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_faq_category ORDER BY faq_category_id ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
									foreach ($result as $row) {
										echo '<option value="'.$row['faq_category_id'].'">'.$row['faq_category_name'].'</option>';
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