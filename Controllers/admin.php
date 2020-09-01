<?php
include '../Models/connectdb.php';
include '../Models/postList.php';


$postList = postList();

include '../Views/admin/header.php';

if (isset($_GET['act'])) {
	$act = $_GET['act'];
	switch ($act) {
		case 'new':
			if (isset($_POST['submit']) && ($_POST['submit'])) {
				$title = $_POST['title'];
				$status = $_POST['status'];
				$descriptions = $_POST['descriptions'];
				$img = 'uploads/' . $_FILES['img']['name'];
				$target_dir = "/uploads/";
				$target_file = $target_dir . basename($img);
				if ($_FILES['img']['error'] > 0) {
					echo 'upload error';
				} else {
					move_uploaded_file($_FILES['img']['tmp_name'], $img);
				}
				newPost($title, $descriptions, $img, $status);
			}
			include '../Views/admin/new.php';
			break;

		case 'edit':
			if (isset($_GET['id']) && ($_GET['id'] > 0)) {
				$ide = $_GET['id'];
			} else {
				$ide = 0;
			}
			$infoPost = showPost($_GET['id']);
			if (isset($_POST['submit'])) {
				$id = $_POST['id'];
				$title = $_POST['title'];
				$status = $_POST['status'];
				$descriptions = $_POST['descriptions'];
				if ($_FILES['img']['name'] != '') {
					$img = 'uploads/' . $_FILES['img']['name'];
					$target_dir = "/uploads/";
					$target_file = $target_dir . basename($img);
					if ($_FILES['img']['error'] > 0) {
						echo 'not image';
					} else {
						move_uploaded_file($_FILES['img']['tmp_name'], $img);
					}
				} else {
					$img = '';
				}

				updatePost($id, $title, $status, $descriptions, $img);
			}
			$infoPost = showPost($ide);
			include '../Views/admin/edit.php';
			break;

		case 'delete':
			if (isset($_GET['id']) && ($_GET['id'] > 0)) {
				delPost($_GET['id']);
			}
			$postList = postList();
			include '../Views/admin/home.php';
			break;


		default:
			include '../Views/admin/home.php';
			break;
	}
} else {

	$tongsoluong = showTotalPost();
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		$soluongpost = 5;
	} else {
		$page = 1;
		$soluongpost = 5;
	}
	if (isset($_POST['soluongpost'])&&($_POST['soluongpost']>0)) {
		$soluongpost = $_POST['soluongpost'];
	}
	if(isset($_GET['soluongpost'])&&($_GET['soluongpost']>0)){
		$soluongpost = $_GET['soluongpost'];
	}
	$postList = paginatorPost($page, $soluongpost);
	$paginator = paginator($tongsoluong, $soluongpost);



	include '../Views/admin/home.php';
}
