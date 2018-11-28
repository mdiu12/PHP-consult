<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_faq_category WHERE faq_category_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php
	// Delete from tbl_faq_category
	$statement = $pdo->prepare("DELETE FROM tbl_faq_category WHERE faq_category_id=?");
	$statement->execute(array($_REQUEST['id']));

	// Delete from tbl_faq
	$statement = $pdo->prepare("DELETE FROM tbl_faq WHERE faq_category_id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: faq-category.php');
?>