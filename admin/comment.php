<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {

	$statement = $pdo->prepare("UPDATE tbl_comment SET code_body=? WHERE id=1");
	$statement->execute(array($_POST['code_body']));

	$success_message = 'Comment Section Code is updated successfully.';

}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Facebook Comment Setup</h1>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_comment WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$code_body = $row['code_body'];
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
						
						<p style="padding-bottom: 20px;">Go to the facebook developer section (<a href="https://developers.facebook.com/docs/plugins/comments/">https://developers.facebook.com/docs/plugins/comments/</a>) to get your comment codes.</p>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Code to use after the opening &lt;body&gt; tag </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="code_body" style="height:300px;"><?php echo $code_body; ?></textarea>
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