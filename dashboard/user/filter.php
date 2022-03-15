<?php
//Start Session
session_start();

//Connection
$connection = mysqli_connect("localhost", "root", "", "flashzoom");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to the database: " . $mysqli->connect_error;
    exit();
}

//Check the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
}

if (isset($_GET['selection'])) {
    if ($_GET['selection'] == 'All') {
        //Prepare a Query
        $query = "SELECT * FROM zoomlinks WHERE is_Active = 1;";
        $result_set = mysqli_query($connection, $query);

        $data = mysqli_fetch_all($result_set, MYSQLI_ASSOC);
        echo json_encode($data);
    } else {
        //Prepare a Query
        $query = "SELECT * FROM zoomlinks WHERE meetingGroup = '{$_GET['selection']}' AND is_Active = 1;";
        $result_set = mysqli_query($connection, $query);

        $data = mysqli_fetch_all($result_set, MYSQLI_ASSOC);
        echo json_encode($data);
    }
}
