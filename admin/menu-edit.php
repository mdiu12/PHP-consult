<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form_page'])) {
    $valid = 1;

    if(empty($_POST['menu_order'])) {
        $valid = 0;
        $error_message .= "Menu Order can not be empty<br>";
    } else {
        if(!is_numeric($_POST['menu_order'])) {
            $valid = 0;
            $error_message .= "Menu Order must be numeric value<br>";
        }
    }
    
    if($valid == 1) {
        $q = $pdo->prepare("UPDATE tbl_menu SET 
                    menu_type=?, 
                    page_id=?, 
                    menu_name=?,
                    menu_url=?,
                    menu_order=?,
                    menu_parent=?
        
                    WHERE menu_id=?
                ");
        $q->execute([
                    'Page',
                    $_POST['page_id'],
                    '',
                    '',
                    $_POST['menu_order'],
                    $_POST['menu_parent'],
                    $_REQUEST['id']
                ]);
        $success_message = 'Menu is updated successfully.';
    }
}
?>

<?php
if(isset($_POST['form_other'])) {
    $valid = 1;

    if(empty($_POST['menu_order'])) {
        $valid = 0;
        $error_message .= "Menu Order can not be empty<br>";
    } else {
        if(!is_numeric($_POST['menu_order'])) {
            $valid = 0;
            $error_message .= "Menu Order must be numeric value<br>";
        }
    }
    
    if($valid == 1) {
        $q = $pdo->prepare("UPDATE tbl_menu SET 
                    menu_type=?, 
                    page_id=?, 
                    menu_name=?,
                    menu_url=?,
                    menu_order=?,
                    menu_parent=?
        
                    WHERE menu_id=?
                ");
        $q->execute([
                    'Other',
                    '',
                    $_POST['menu_name'],
                    $_POST['menu_url'],
                    $_POST['menu_order'],
                    $_POST['menu_parent'],
                    $_REQUEST['id']
                ]);
        $success_message = 'Menu is updated successfully.';
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_menu WHERE menu_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $menu_type = $row['menu_type'];
    $page_id = $row['page_id'];
    $menu_name = $row['menu_name'];
    $menu_url = $row['menu_url'];
    $menu_order = $row['menu_order'];
    $menu_parent = $row['menu_parent'];
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Menu</h1>
	</div>
	<div class="content-header-right">
		<a href="menu.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<section class="content" style="min-height:auto;margin-bottom: -30px;">
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
			<h4>Success:</h4>
			<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">

			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="<?php if($menu_type == 'Page') {echo 'active';} ?>"><a href="#tab_1" data-toggle="tab">Page as Menu</a></li>
					<li class="<?php if($menu_type == 'Other') {echo 'active';} ?>"><a href="#tab_2" data-toggle="tab">Other Menu</a></li>
				</ul>
				<div class="tab-content">
      				<div class="tab-pane <?php if($menu_type == 'Page') {echo 'active';} ?>" id="tab_1">

						<form class="form-horizontal" action="" method="post">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Select Page <span>*</span></label>
										<div class="col-sm-4">
											<select class="form-control select2" name="page_id" style="width:100%;">
												<?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_page ORDER BY page_name ASC");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if($row['page_id']==$page_id) {
                                                        $sel = 'selected';
                                                    } else {
                                                        $sel = '';
                                                    }
                                                    echo '<option value="'.$row['page_id'].'" '.$sel.'>'.$row['page_name'].'</option>';
                                                }
                                                ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Select Parent <span>*</span></label>
										<div class="col-sm-4">
											<select class="form-control select2" name="menu_parent" style="width:100%;">
												<option value="0" <?php if($menu_parent == 0){echo 'selected';} ?>>No Parent</option>
												<?php
                                                $q = $pdo->prepare("SELECT * 
                                                                    FROM tbl_menu 
                                                                    ORDER BY menu_order ASC");
                                                $q->execute();
                                                $res = $q->fetchAll();
                                                foreach ($res as $row) {
                                                    if($row['menu_id'] == $menu_parent) {
                                                        $sel1 = 'selected';
                                                    } else {
                                                        $sel1 = '';
                                                    }
                                                    if($row['page_id']==0) {      
                                                        echo '<option value="'.$row['menu_id'].'" '.$sel1.'>'.$row['menu_name'].'</option>';
                                                    } else {
                                                        $r = $pdo->prepare("SELECT * 
                                                                            FROM tbl_page 
                                                                            WHERE page_id=?");
                                                        $r->execute([$row['page_id']]);
                                                        $res1 = $r->fetchAll();
                                                        foreach ($res1 as $row1) {
                                                            echo '<option value="'.$row['menu_id'].'" '.$sel1.'>'.$row1['page_name'].'</option>';
                                                        }
                                                    }
                                                }
                                                ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Order <span>*</span></label>
										<div class="col-sm-1">
											<input type="text" class="form-control" name="menu_order" value="<?php echo $menu_order; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form_page">Update</button>
										</div>
									</div>
								</div>
							</div>
						</form>


      				</div>
      				<div class="tab-pane <?php if($menu_type == 'Other') {echo 'active';} ?>" id="tab_2">


						<form class="form-horizontal" action="" method="post">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Menu Name <span>*</span></label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="menu_name" value="<?php echo $menu_name; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Menu URL <span>*</span></label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="menu_url" value="<?php echo $menu_url; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Parent <span>*</span></label>
										<div class="col-sm-4">
											<select class="form-control select2" name="menu_parent">
												<option value="0" <?php if($menu_parent == 0){echo 'selected';} ?>>No Parent</option>
												<?php
                                                $q = $pdo->prepare("SELECT * 
                                                                    FROM tbl_menu 
                                                                    ORDER BY menu_order ASC");
                                                $q->execute();
                                                $res = $q->fetchAll();
                                                foreach ($res as $row) {
                                                    if($row['menu_id'] == $menu_parent) {
                                                        $sel1 = 'selected';
                                                    } else {
                                                        $sel1 = '';
                                                    }
                                                    if($row['page_id']==0) {      
                                                        echo '<option value="'.$row['menu_id'].'" '.$sel1.'>'.$row['menu_name'].'</option>';
                                                    } else {
                                                        $r = $pdo->prepare("SELECT * 
                                                                            FROM tbl_page 
                                                                            WHERE page_id=?");
                                                        $r->execute([$row['page_id']]);
                                                        $res1 = $r->fetchAll();
                                                        foreach ($res1 as $row1) {
                                                            echo '<option value="'.$row['menu_id'].'" '.$sel1.'>'.$row1['page_name'].'</option>';
                                                        }
                                                    }
                                                }
                                                ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Order <span>*</span></label>
										<div class="col-sm-1">
											<input type="text" class="form-control" name="menu_order" value="<?php echo $menu_order; ?>">
										</div>
									</div>
									
									<div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form_other">Update</button>
										</div>
									</div>
								</div>
							</div>
						</form>



      				</div>
      				
      			</div>
      		</div>
			
		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>