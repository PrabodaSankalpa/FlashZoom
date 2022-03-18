<?php
//Start Session
session_start();

require '../../lib/db.php';

//Check the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
}

//Prepare a Query
$query = "SELECT * FROM zoomlinks WHERE Host_ID = {$_SESSION['user_id']};";
$result_set = mysqli_query($connection, $query);

$data = mysqli_fetch_all($result_set, MYSQLI_ASSOC);
echo json_encode($data);
