<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['file_title'])) {
        $valid = 0;
        $error_message .= "File Title can not be empty<br>";
    }

    $path = $_FILES['file']['name'];
    $path_tmp = $_FILES['file']['tmp_name'];

    if($path == '') {
    	$valid = 0;
        $error_message .= "You must have to select a file<br>";
    } else {
    	$ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' && $ext!='pdf' && $ext!='doc' && $ext!='docx' && $ext!='ppt' && $ext!='pptx' && $ext!='xls' && $ext!='xlsx' && $ext!='zip' && $ext!='rar' ) {
            $valid = 0;
            $error_message .= 'Only these files are allowed: jpg, jpeg, gif, png, pdf, doc, docx, ppt, pptx, xls, xlsx, zip, rar<br>';
        }
    }

 
    if($valid == 1) {

    	// getting auto increment id for file renaming
		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_file'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {$ai_id=$row[10];}

		// uploading the file into the main location and giving it a final name
		$final_name = 'file-'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

		// saving into the database
		$statement = $pdo->prepare("INSERT INTO tbl_file (file_title,file_name) VALUES (?,?)");
		$statement->execute(array($_POST['file_title'],$final_name));

    	$success_message = 'File is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add File</h1>
	</div>
	<div class="content-header-right">
		<a href="file.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">File Title <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="file_title">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Upload File <span>*</span></label>
							<div class="col-sm-4" style="padding-top:6px;">
								<input type="file" name="file">
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