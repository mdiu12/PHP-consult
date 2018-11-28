<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Videos</h1>
	</div>
	<div class="content-header-right">
		<a href="video-add.php" class="btn btn-primary btn-sm">Add New</a>
	</div>
</section>


<section class="content">

  <div class="row">
    <div class="col-md-12">


      <div class="box box-info">
        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-striped">
			<thead>
			    <tr>
			        <th>SL</th>
			        <th>Title</th>
			        <th style="width:300px;">Code (iframe)</th>
			        <th>Category</th>
			        <th>Action</th>
			    </tr>
			</thead>
            <tbody>
	            <?php
	            	$i=0;
	            	$statement = $pdo->prepare("SELECT 
	            	                           
												t1.video_id,
												t1.video_title,
												t1.video_iframe,
												t1.v_category_id,

												t2.v_category_id,
												t2.v_category_name

	            	                           	FROM tbl_video t1
	            	                           	JOIN tbl_category_video t2
	            	                           	ON t1.v_category_id = t2.v_category_id");
	            	$statement->execute();
	            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	            	foreach ($result as $row) {
	            		$i++;
		            	?>
			            <tr>
			                <td><?php echo $i; ?></td>
			                <td><?php echo $row['video_title']; ?></td>
			                <td>
			                	<div class="video-iframe">
			                		<?php echo $row['video_iframe']; ?>
			                	</div>
			                </td>
			                <td><?php echo $row['v_category_name']; ?></td>
			                <td>
			                    <a href="video-edit.php?id=<?php echo $row['video_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
			                    <a href="#" class="btn btn-danger btn-xs" data-href="video-delete.php?id=<?php echo $row['video_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
			                </td>
			            </tr>
			            <?php
	            	}
	            ?>
            </tbody>
          </table>
        </div>
      </div>
  

</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>