<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form_page'])) {
    $valid = 1;

    if(empty($_POST['page_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a page<br>";
    }
    if(empty($_POST['menu_order'])) {
        $valid = 0;
        $error_message .= "Menu Order can not be empty<br>";
    } else {
        if(!is_numeric($_POST['menu_order'])) {
            $valid = 0;
            $error_message .= "Menu Order must be numeric value<br>";
        }
    }
    if( $_POST['menu_parent'] == '') {
        $valid = 0;
        $error_message .= "You must have to select a parent for this menu<br>";
    }

    if($valid == 1) {

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO tbl_menu (menu_type,page_id,menu_name,menu_url,menu_order,menu_parent) VALUES (?,?,?,?,?,?)");
        $statement->execute(array('Page',$_POST['page_id'],'','',$_POST['menu_order'],$_POST['menu_parent']));

        $success_message = 'Menu is added successfully.';
    }

}
?>

<?php
if(isset($_POST['form_other'])) {
    $valid = 1;

    if(empty($_POST['menu_name'])) {
        $valid = 0;
        $error_message .= "Menu Name can not be empty<br>";
    }
    if(empty($_POST['menu_url'])) {
        $valid = 0;
        $error_message .= "Menu URL can not be empty<br>";
    }
    if(empty($_POST['menu_order'])) {
        $valid = 0;
        $error_message .= "Menu Order can not be empty<br>";
    } else {
        if(!is_numeric($_POST['menu_order'])) {
            $valid = 0;
            $error_message .= "Menu Order must be numeric value<br>";
        }
    }
    if( $_POST['menu_parent'] == '') {
        $valid = 0;
        $error_message .= "You must have to select a parent for this menu<br>";
    }

    if($valid == 1) {

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO tbl_menu (menu_type,page_id,menu_name,menu_url,menu_order,menu_parent) VALUES (?,?,?,?,?,?)");
        $statement->execute(array('Other',0,$_POST['menu_name'],$_POST['menu_url'],$_POST['menu_order'],$_POST['menu_parent']));

        $success_message = 'Menu is added successfully.';
    }

}
?>


<section class="content-header">
	<div class="content-header-left">
		<h1>Add Menu</h1>
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
					<li class="active"><a href="#tab_1" data-toggle="tab">Page as Menu</a></li>
					<li><a href="#tab_2" data-toggle="tab">Other Menu</a></li>
				</ul>
				<div class="tab-content">
      				<div class="tab-pane active" id="tab_1">


						<form class="form-horizontal" action="" method="post">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Select Page <span>*</span></label>
										<div class="col-sm-4">
											<select class="form-control select2" name="page_id">
												<option value="">Select a page</option>
												<?php
												$statement = $pdo->prepare("SELECT * FROM tbl_page ORDER BY page_name ASC");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);       
                                                foreach ($result as $row) {
                                                    echo '<option value="'.$row['page_id'].'">'.$row['page_name'].'</option>';
                                                }
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Select Parent <span>*</span></label>
										<div class="col-sm-4">
											<select class="form-control select2" name="menu_parent">
												<option value="">Select a parent for this menu</option>
                                                <option value="0">No Parent</option>
												<?php
                                                $q = $pdo->prepare("SELECT * 
                                                                    FROM tbl_menu 
                                                                    ORDER BY menu_order ASC");
                                                $q->execute();
                                                $res = $q->fetchAll();
                                                foreach ($res as $row) {
                                                    if($row['page_id']==0) {
                                                        echo '<option value="'.$row['menu_id'].'">'.$row['menu_name'].'</option>';
                                                    } else {
                                                        $r = $pdo->prepare("SELECT * 
                                                                            FROM tbl_page 
                                                                            WHERE page_id=?");
                                                        $r->execute([$row['page_id']]);
                                                        $res1 = $r->fetchAll();
                                                        foreach ($res1 as $row1) {
                                                            echo '<option value="'.$row['menu_id'].'">'.$row1['page_name'].'</option>';
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
											<input type="text" class="form-control" name="menu_order">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form_page">Submit</button>
										</div>
									</div>
								</div>
							</div>
						</form>


      				</div>
      				<div class="tab-pane" id="tab_2">


						<form class="form-horizontal" action="" method="post">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Menu Name <span>*</span></label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="menu_name">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Menu URL <span>*</span></label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="menu_url">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Parent <span>*</span></label>
										<div class="col-sm-4">
											<select class="form-control select2" name="menu_parent" style="width:100%;">
												<option value="">Select a parent for this menu</option>
												<option value="0">No Parent</option>
												<?php
                                                $q = $pdo->prepare("SELECT * 
                                                                    FROM tbl_menu 
                                                                    ORDER BY menu_order ASC");
                                                $q->execute();
                                                $res = $q->fetchAll();
                                                foreach ($res as $row) {
                                                    if($row['page_id']==0) {
                                                        echo '<option value="'.$row['menu_id'].'">'.$row['menu_name'].'</option>';
                                                    } else {
                                                        $r = $pdo->prepare("SELECT * 
                                                                            FROM tbl_page 
                                                                            WHERE page_id=?");
                                                        $r->execute([$row['page_id']]);
                                                        $res1 = $r->fetchAll();
                                                        foreach ($res1 as $row1) {
                                                            echo '<option value="'.$row['menu_id'].'">'.$row1['page_name'].'</option>';
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
											<input type="text" class="form-control" name="menu_order">
										</div>
									</div>
									
									<div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form_other">Submit</button>
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