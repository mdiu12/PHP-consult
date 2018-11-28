<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'Name can not be empty<br>';
	}

    if(empty($_POST['designation_id'])) {
		$valid = 0;
		$error_message .= 'You must have to select a designation<br>';
	}

    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for Team Member photo<br>';
        }
    }


    $path1 = $_FILES['banner']['name'];
    $path_tmp1 = $_FILES['banner']['tmp_name'];

    if($path1!='') {
        $ext1 = pathinfo( $path1, PATHINFO_EXTENSION );
        $file_name1 = basename( $path1, '.' . $ext1 );
        if( $ext1!='jpg' && $ext1!='png' && $ext1!='jpeg' && $ext1!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for banner<br>';
        }
    }

	if($valid == 1) {

		if($_POST['slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['name']);
    		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	} else {
    		$temp_string = strtolower($_POST['slug']);
    		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_team_member WHERE slug=? AND name!=?");
		$statement->execute(array($slug,$_POST['current_team_member_name']));
		$total = $statement->rowCount();
		if($total) {
			$slug = $slug.'-1';
		}


		if($path == '' && $path1 == '') {
			$statement = $pdo->prepare("UPDATE tbl_team_member SET name=?, slug=?, designation_id=?, degree=?, detail=?, facebook=?, twitter=?, linkedin=?, youtube=?, google_plus=?, instagram=?, flickr=?, address=?, practice_location=?, phone=?, email=?, website=?, status=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$slug,$_POST['designation_id'],$_POST['degree'],$_POST['detail'],$_POST['facebook'],$_POST['twitter'],$_POST['linkedin'],$_POST['youtube'],$_POST['google_plus'],$_POST['instagram'],$_POST['flickr'],$_POST['address'],$_POST['practice_location'],$_POST['phone'],$_POST['email'],$_POST['website'],$_POST['status'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
		}

		if($path != '' && $path1 == '') {
			unlink('../assets/uploads/'.$_POST['current_photo']);

			$final_name = 'team-member-'.$_REQUEST['id'].'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

			$statement = $pdo->prepare("UPDATE tbl_team_member SET name=?, slug=?, designation_id=?, photo=?, degree=?, detail=?, facebook=?, twitter=?, linkedin=?, youtube=?, google_plus=?, instagram=?, flickr=?, address=?, practice_location=?, phone=?, email=?, website=?, status=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$slug,$_POST['designation_id'],$final_name,$_POST['degree'],$_POST['detail'],$_POST['facebook'],$_POST['twitter'],$_POST['linkedin'],$_POST['youtube'],$_POST['google_plus'],$_POST['instagram'],$_POST['flickr'],$_POST['address'],$_POST['practice_location'],$_POST['phone'],$_POST['email'],$_POST['website'],$_POST['status'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
		}

		if($path == '' && $path1 != '') {
			unlink('../assets/uploads/'.$_POST['current_banner']);

			$final_name1 = 'team-member-banner-'.$_REQUEST['id'].'.'.$ext1;
        	move_uploaded_file( $path_tmp1, '../assets/uploads/'.$final_name1 );

			$statement = $pdo->prepare("UPDATE tbl_team_member SET name=?, slug=?, designation_id=?, banner=?, degree=?, detail=?, facebook=?, twitter=?, linkedin=?, youtube=?, google_plus=?, instagram=?, flickr=?, address=?, practice_location=?, phone=?, email=?, website=?, status=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$slug,$_POST['designation_id'],$final_name1,$_POST['degree'],$_POST['detail'],$_POST['facebook'],$_POST['twitter'],$_POST['linkedin'],$_POST['youtube'],$_POST['google_plus'],$_POST['instagram'],$_POST['flickr'],$_POST['address'],$_POST['practice_location'],$_POST['phone'],$_POST['email'],$_POST['website'],$_POST['status'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
		}

		if($path != '' && $path1 != '') {
			unlink('../assets/uploads/'.$_POST['current_photo']);
			unlink('../assets/uploads/'.$_POST['current_banner']);

			$final_name = 'team-member-'.$_REQUEST['id'].'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

			$final_name1 = 'team-member-banner-'.$_REQUEST['id'].'.'.$ext1;
        	move_uploaded_file( $path_tmp1, '../assets/uploads/'.$final_name1 );

			$statement = $pdo->prepare("UPDATE tbl_team_member SET name=?, slug=?, designation_id=?, photo=?, banner=?, degree=?, detail=?, facebook=?, twitter=?, linkedin=?, youtube=?, google_plus=?, instagram=?, flickr=?, address=?, practice_location=?, phone=?, email=?, website=?, status=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$slug,$_POST['designation_id'],$final_name,$final_name1,$_POST['degree'],$_POST['detail'],$_POST['facebook'],$_POST['twitter'],$_POST['linkedin'],$_POST['youtube'],$_POST['google_plus'],$_POST['instagram'],$_POST['flickr'],$_POST['address'],$_POST['practice_location'],$_POST['phone'],$_POST['email'],$_POST['website'],$_POST['status'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
		}

	    $success_message = 'Team Member is updated successfully!';
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_team_member WHERE id=?");
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
		<h1>Edit Team Member</h1>
	</div>
	<div class="content-header-right">
		<a href="team-member.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_team_member WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$name              = $row['name'];
	$slug              = $row['slug'];
	$designation_id    = $row['designation_id'];
	$photo             = $row['photo'];
	$banner            = $row['banner'];
	$degree            = $row['degree'];
	$detail            = $row['detail'];
	$facebook          = $row['facebook'];
	$twitter           = $row['twitter'];
	$linkedin          = $row['linkedin'];
	$youtube           = $row['youtube'];
	$google_plus       = $row['google_plus'];
	$instagram         = $row['instagram'];
	$flickr            = $row['flickr'];
	$address           = $row['address'];
	$practice_location = $row['practice_location'];
	$phone             = $row['phone'];
	$email             = $row['email'];
	$website           = $row['website'];
	$status            = $row['status'];
	$meta_title        = $row['meta_title'];
	$meta_keyword      = $row['meta_keyword'];
	$meta_description  = $row['meta_description'];
}
?>

<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if($error_message): ?>
			<div class="callout callout-danger">
			<h4>Please correct the following errors:</h4>
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
				<input type="hidden" name="current_photo" value="<?php echo $photo; ?>">
				<input type="hidden" name="current_banner" value="<?php echo $banner; ?>">
				<input type="hidden" name="current_team_member_name" value="<?php echo $name; ?>">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Name <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $name; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Slug </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="slug" value="<?php echo $slug; ?>">
							</div>
						</div>
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Select Designation <span>*</span></label>
				            <div class="col-sm-3">
				            	<select class="form-control select2" name="designation_id" style="width:300px;">
								<?php
				            	$i=0;
				            	$statement = $pdo->prepare("SELECT * FROM tbl_designation ORDER BY designation_name ASC");
				            	$statement->execute(array('Active'));
				            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				            	foreach ($result as $row) {
									?>
									<option value="<?php echo $row['designation_id']; ?>" <?php if($row['designation_id']==$designation_id){echo 'selected';} ?>><?php echo $row['designation_name']; ?></option>
	                                <?php
								}
								?>
								</select>
				            </div>
				        </div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Existing Photo</label>
							<div class="col-sm-9" style="padding-top:5px">
								<img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $photo; ?>" alt="Team Member Photo" style="width:200px;">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Photo <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="photo">(Only jpg, jpeg, gif and png are allowed)
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Existing Banner</label>
							<div class="col-sm-9" style="padding-top:5px">
								<img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $banner; ?>" alt="Team Member Banner" style="width:200px;">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Banner <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="banner">(Only jpg, jpeg, gif and png are allowed)
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Degree </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="degree" value="<?php echo $degree; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Detail </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="detail" id="editor1"><?php echo $detail; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Facebook </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="facebook" value="<?php echo $facebook; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Twitter </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="twitter" value="<?php echo $twitter; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">LinkedIn </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="linkedin" value="<?php echo $linkedin; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">YouTube </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="youtube" value="<?php echo $youtube; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Google Plus </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="google_plus" value="<?php echo $google_plus; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Instagram </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="instagram" value="<?php echo $instagram; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Flickr </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="flickr" value="<?php echo $flickr; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Address </label>
							<div class="col-sm-6">
								<textarea class="form-control" name="address" style="height: 140px"><?php echo $address; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Practice Location </label>
							<div class="col-sm-6">
								<textarea class="form-control" name="practice_location" style="height: 140px"><?php echo $practice_location; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Phone </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="phone" value="<?php echo $phone; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Email Address </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="email" value="<?php echo $email; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Website </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="website" value="<?php echo $website; ?>">
							</div>
						</div>				        
				        <div class="form-group">
				            <label for="" class="col-sm-2 control-label">Active </label>
				            <div class="col-sm-6">
				                <label class="radio-inline">
				                    <input type="radio" name="status" value="Active" <?php if($status == 'Active') { echo 'checked'; } ?>>Yes
				                </label>
				                <label class="radio-inline">
				                    <input type="radio" name="status" value="Inactive" <?php if($status == 'Inactive') { echo 'checked'; } ?>>No
				                </label>
				            </div>
				        </div>
						<h3 class="seo-info">SEO Information</h3>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Title </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="meta_title" value="<?php echo $meta_title; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Keywords </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="meta_keyword" value="<?php echo $meta_keyword; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Description </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="meta_description" style="height:140px;"><?php echo $meta_description; ?></textarea>
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