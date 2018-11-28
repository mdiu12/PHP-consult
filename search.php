<?php require_once('header.php'); ?>

<?php
// Preventing the direct access of this page.
if(!isset($_REQUEST['search_string']))
{
	header('location: '.BASE_URL);
	exit;
}

$search_string = strip_tags($_REQUEST['search_string']);
?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
    $banner_search = $row['banner_search'];
}
?>

<!-- Banner Start -->
<div class="page-banner" style="background-image:url(<?php echo BASE_URL; ?>assets/uploads/<?php echo $banner_search; ?>);">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="banner-text">
					<h1><?php echo SEARCH_BY_COLON; ?> <?php echo $search_string; ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Banner End -->


<!-- Blog Start -->
<section class="blog">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				
				<!-- Blog Classic Start -->
				<div class="blog-grid">
					<div class="row">
						<div class="col-md-12">
							

							<?php
							$search_string = "%" . $search_string . "%";

							$statement = $pdo->prepare("SELECT * FROM tbl_news WHERE news_title like ? OR news_content like ?");
							$statement->execute(array($search_string,$search_string));
							$total = $statement->rowCount();
							?>

							<?php if(!$total): ?>
							<p style="color:red;"><?php echo NEWS_EMPTY_CHECK; ?></p>
							<?php else: ?>




<?php
/* ===================== Pagination Code Starts ================== */
		$adjacents = 10;	
		
		$statement = $pdo->prepare("SELECT 
		                           t1.news_title,
		                           t1.news_slug,
		                           t1.news_content,
		                           t1.news_date,
		                           t1.photo,
		                           t1.category_id,

		                           t2.category_id,
		                           t2.category_name,
		                           t2.category_slug
		                           FROM tbl_news t1
		                           JOIN tbl_category t2
		                           ON t1.category_id = t2.category_id
		                           WHERE t1.news_title like ? OR t1.news_content like ?");
		$statement->execute(array($search_string,$search_string));
		$total_pages = $statement->rowCount();
		
		$targetpage = $_SERVER['PHP_SELF'];
		$limit = 5;                                 
		$page = @$_GET['page'];
		if($page) 
			$start = ($page - 1) * $limit;          
		else
			$start = 0;	
		

		$statement = $pdo->prepare("SELECT
		                           t1.news_title,
		                           t1.news_slug,
		                           t1.news_content,
		                           t1.news_date,
		                           t1.photo,
		                           t1.category_id,

		                           t2.category_id,
		                           t2.category_name,
		                           t2.category_slug
		                           FROM tbl_news t1
		                           JOIN tbl_category t2
		                           ON t1.category_id = t2.category_id
		                           WHERE t1.news_title like ? OR t1.news_content like ? 
		                           LIMIT $start, $limit");
		$statement->execute(array($search_string,$search_string));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		
		$s1 = $_REQUEST['search_string'];
				
		if ($page == 0) $page = 1;                  
		$prev = $page - 1;                          
		$next = $page + 1;                          
		$lastpage = ceil($total_pages/$limit);      
		$lpm1 = $lastpage - 1;   
		$pagination = "";
		if($lastpage > 1)
		{   
			$pagination .= "<div class=\"pagination\">";
			if ($page > 1) 
				$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$prev\">&#171; ".PREVIOUS."</a>";
			else
				$pagination.= "<span class=\"disabled\">&#171; ".PREVIOUS."</span>";    
			if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
			{   
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$counter\">$counter</a>";                 
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
			{
				if($page < 1 + ($adjacents * 2))        
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$counter\">$counter</a>";                 
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$lastpage\">$lastpage</a>";       
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<a href=\"$targetpage?search_string=$s1&page=1\">1</a>";
					$pagination.= "<a href=\"$targetpage?search_string=$s1&page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$counter\">$counter</a>";                 
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$lastpage\">$lastpage</a>";       
				}
				else
				{
					$pagination.= "<a href=\"$targetpage?search_string=$s1&page=1\">1</a>";
					$pagination.= "<a href=\"$targetpage?search_string=$s1&page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$counter\">$counter</a>";                 
					}
				}
			}
			if ($page < $counter - 1) 
				$pagination.= "<a href=\"$targetpage?search_string=$s1&page=$next\">".NEXT." &#187;</a>";
			else
				$pagination.= "<span class=\"disabled\">".NEXT." &#187;</span>";
			$pagination.= "</div>\n";       
		}
		/* ===================== Pagination Code Ends ================== */
		?>

							<?php
							foreach ($result as $row) {
								?>
								<div class="post-item">
									<div class="image-holder">
										<img class="img-responsive" src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['photo']; ?>" alt="<?php echo $row['news_title']; ?>">
									</div>
									<div class="text">
										<h3><a href="<?php echo BASE_URL.URL_NEWS.$row['news_slug']; ?>"><?php echo $row['news_title']; ?></a></h3>
										<ul class="status">
											<li><i class="fa fa-tag"></i><?php echo CATEGORY_COLON; ?> <a href="<?php echo BASE_URL.URL_CATEGORY.$row['category_slug']; ?>"><?php echo $row['category_name']; ?></a></li>
											<li><i class="fa fa-calendar"></i><?php echo DATE; ?> <?php echo $row['news_date']; ?></li>
										</ul>
										<p>
											<?php echo substr($row['news_content'],0,200).' ...'; ?>
										</p>
										<p class="button">
											<a href="<?php echo BASE_URL.URL_NEWS.$row['news_slug']; ?>"><?php echo READ_MORE; ?></a>
										</p>
									</div>
								</div>
								<?php
							}
							?>							
							<?php endif; ?>

						</div>

						<div class="col-md-12">
							<?php if($total): ?>
							<?php echo $pagination; ?>
							<?php endif; ?>
						</div>

					</div>
				</div>
				<!-- Blog Classic End -->

			</div>
			<div class="col-md-3">
				
				<?php require_once('sidebar.php'); ?>
			
			</div>

			


		</div>
	</div>
</section>
<!-- Blog End -->

<?php require_once('footer.php'); ?>