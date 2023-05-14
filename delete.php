<?php 
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'archive');

if (isset($_SESSION['id'])) {
	$ID = $_SESSION['id'];

	$document_query = "SELECT * FROM `document` WHERE documentID = '$ID'";
    $document_result = mysqli_query($conn,$document_query);
    $row = mysqli_fetch_array($document_result);
    $read_file = $row['file'];
    unlink($read_file);

	$ID_query = "DELETE FROM document WHERE documentID = '$ID'";
	$ID_result = mysqli_query($conn, $ID_query);

	unset($_SESSION['id']);
	header("Location: index.php");
}
?>