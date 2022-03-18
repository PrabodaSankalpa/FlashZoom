<?php
//Start Session
session_start();

require '../../lib/db.php';

//Check the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
}

if (isset($_GET['cardID'])) {
    //Prepare a Query
    $query = "DELETE FROM zoomlinks WHERE ID = {$_GET['cardID']} LIMIT 1;";
    $result_set = mysqli_query($connection, $query);
}
