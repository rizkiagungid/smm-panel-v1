<div>
<ul class="pagination pagination-split">
<?php
// start paging link
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$query_list = mysqli_query($db, $query_list);
$total_no_of_records = mysqli_num_rows($query_list);
if($total_no_of_records > 0) {
    if (isset($_GET['search']) AND isset($_GET['status'])) {
        if (!empty($_GET['search']) OR !empty($_GET['status'])) {
	        $post_data = "&status=".$_GET['status']."&search=".$_GET['search'];
        } else {
            $post_data = "";
        }
	} else if (isset($_GET['search']) AND isset($_GET['category'])) {
	    if (!empty($_GET['search']) OR !empty($_GET['category'])) {
	        $post_data = "&category=".$_GET['category']."&search=".$_GET['search'];
	    } else {
	        $post_data = "";
		}
	} else if (isset($_GET['search'])) {
		if (!empty($_GET['search'])) {
	        $post_data = "&search=".$_GET['search'];
	    } else {
	        $post_data = "";
		}
	} else {
	    $post_data = "";
	}
	$total_no_of_pages = ceil($total_no_of_records/$records_per_page);
	$current_page = 1;
	if(isset($_GET["page"])) {
		$current_page = $_GET["page"];
	}
	if($current_page != 1) {
		$previous = $current_page-1;
	?>  
	    <li class="page-item"><a class="page-link" href="<?php echo $url."?page=1.$post_data"; ?>">&lsaquo; First</a></li>
		<li class="page-item"><a class="page-link" href="<?php echo $url."?page=".$previous.$post_data; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>
	<?php 
	}
	$jumlah_number = 2;
	$start_number = ($current_page > $jumlah_number)? $current_page - $jumlah_number : 1;
	$end_number = ($current_page < ($total_no_of_pages - $jumlah_number))? $current_page + $jumlah_number : $total_no_of_pages;
	for($i=$start_number; $i<=$end_number; $i++) {
	    if($i==$current_page) {
	?>
	    <li class="page-item active"><a class="page-link" href="<?php echo $url."?page=".$i.$post_data; ?>"><?php echo $i; ?></a></li>
	<?php } else { ?>
		<li class="page-item"><a class="page-link" href="<?php echo $url."?page=".$i.$post_data; ?>"><?php echo $i; ?></a></li>
	<?php }
	}
	if($current_page!=$total_no_of_pages) {
		$next = $current_page+1;
	?>
	    <li class="page-item"><a class="page-link" href="<?php echo $url."?page=".$next.$post_data; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>
		<li class="page-item"><a class="page-link" href="<?php echo $url."?page=".$total_no_of_pages.$post_data; ?>">Last &rsaquo;</a></li>
	<?php
	}
}
// end paging link
?>
</ul>
</div>
<span>Total data: <?php echo $total_no_of_records; ?></span>