<?php
sleep(1);

//Start Session
session_start();

require '../../lib/db.php';

//Check the user is logged in
if ($_SESSION['whoAmI'] != 'lecturer') {
    header('Location: ../../index.php');
}

if (isset($_GET['userID'])) {
    $id = mysqli_real_escape_string($connection, $_GET['userID']);

    $query = "SELECT * FROM users WHERE ID = {$id} LIMIT 1;";
    $result = mysqli_query($connection, $query);

    if (isset($result) && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $fName = $data["First_Name"];

        $file = "../../assets/" . $fName . "-FlashZoom.txt";
        unlink($file);
    }
}
