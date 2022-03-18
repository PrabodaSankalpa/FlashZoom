<?php

require '../lib/db.php';


//Check URL Prameters
if (isset($_GET['code'])) {
    $verification_code = mysqli_real_escape_string($connection, $_GET['code']);

    //query
    $query = "SELECT * FROM users WHERE Verify_Code = '{$verification_code}';";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        $query = "UPDATE users SET is_Verified = true, Verify_Code = NULL WHERE Verify_Code = '{$verification_code}' LIMIT 1; ";
        $result = mysqli_query($connection, $query);

        if (mysqli_affected_rows($connection) == 1) {
            echo '<div style="display:flex; justify-content:center; align-items:center; height:50vh;"><div><h2 style="color:green;">e-mail verification successfull.🎉</h2> <p>Thank you!</p></div></div>';
        } else {
            echo "Invalid Validation Code. Please contact FlashZoom help desk";
        }
    } else {
        $query = "SELECT * FROM admins WHERE Verify_Code = '{$verification_code}';";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) == 1) {
            $query = "UPDATE admins SET is_Verified = true, Verify_Code = NULL WHERE Verify_Code = '{$verification_code}' LIMIT 1; ";
            $result = mysqli_query($connection, $query);

            if (mysqli_affected_rows($connection) == 1) {
                echo '<div style="display:flex; justify-content:center; align-items:center; height:50vh;"><div><h2 style="color:green;">e-mail verification successfull.🎉</h2> <p>Thank you!</p></div></div>';
            } else {
                echo "Invalid Validation Code. Please contact FlashZoom help desk";
            }
        } else {
            echo "Invalid Validation Code. Please contact FlashZoom help desk";
        }
    }
} else {
    echo "Invalid URL.";
}
