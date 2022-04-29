<?php
//Start Session
session_start();

require '../../lib/db.php';

//Check the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
}

if (isset($_POST["submit"])) {
    $errors = array();
    if (!isset($_POST["keyword"]) && $_POST["keyword"] < 9) {
        $errors[] = "E-mail or Password Missing/Invalid!";
    }

    if (empty($errors)) {
        $type = $_POST["searchType"];
        if (strlen($_POST["keyword"]) == 10) {
            $tel = (int)$_POST["keyword"];
        } else {
            $email = $_POST["keyword"];
        }

        if ($type == "email" && isset($email)) {
            $queryForEmail = "SELECT * FROM users WHERE email = '{$email}' LIMIT 1;";
            $result = mysqli_query($connection, $queryForEmail);
            if (mysqli_num_rows($result) == 0) {
                $errors[] = "Something went wrong; there is no data by the provided e-mail address";
            }
        } elseif ($type == "whatsapp" && isset($tel)) {
            $queryForTel = "SELECT * FROM users WHERE WhatsApp = {$tel} LIMIT 1;";
            $result = mysqli_query($connection, $queryForTel);
            if (mysqli_num_rows($result) == 0) {
                $errors[] = "Something went wrong; there is no data by the provided whatsapp number";
            }
        } else {
            $errors[] = "Please select the correct filter method using the radio buttons";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Settings - FlashZoom</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../../css/dashboard.css" rel="stylesheet" />

</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light"><strong>FlashZoom</strong></div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./dashboard.php"><i class="fa-solid fa-plus"></i> Add meetings</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./myMeetings.php"><i class="fa-solid fa-video"></i> My meetings</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./notifications.php"><i class="far fa-bell"></i> Notifications</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="./studentsInfo.php"><i class="fa-solid fa-user-graduate"></i> Student Info</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./settings.php"><i class="fas fa-user-cog"></i> Settings</a>
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <div class="nav-link" id="time">
                                    <div class="spinner-border text-primary spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="../../routes/logout.php"><i class="fa-solid fa-power-off"></i> Logout</a></li>

                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid">
                <div class="container">
                    <div class="d-flex align-items-center mt-2">
                        <img src="<?php echo $_SESSION['avatar']; ?>" class="rounded-circle mr-2" alt="profile photo">
                        <h2>Hello <?php echo $_SESSION['user_title'] . ' ' . $_SESSION['user_firstName']; ?></h2>
                    </div>

                    <h3 class="my-3">Student Info</h3>
                    <form action="./studentsInfo.php" method="POST">
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="searchType" id="email" class="form-check-input" value="email" checked>
                                    <label for="email" class="form-check-label">E-mail</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="searchType" id="whatsapp" class="form-check-input" value="whatsapp">
                                    <label for="whatsapp" class="form-check-label">WhatsApp</label>
                                </div>
                            </div>

                            <div class="col-md-4 form-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Please Enter WhatsApp Number Or E-mail">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-dark" name="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- errors -->
                            <?php
                            if (!empty($errors)) {
                                echo '<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">';
                                echo '<strong>There is error(s) here!</strong><br>';
                                echo '<ol>';
                                foreach ($errors as $error) {
                                    echo '<li>' . $error . '</li>';
                                }
                                echo '</ol>';
                                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                                echo '<span aria-hidden="true">&times;</span>';
                                echo '</button>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <!-- dispaly the results -->
                    <div>
                        <?php

                        if (isset($result) && mysqli_num_rows($result) > 0) {

                            $data = mysqli_fetch_assoc($result);
                            $id = $data["ID"];
                            $fName = $data["First_Name"];
                            $lName = $data["Last_Name"];
                            $gender = $data["Gender"];
                            $dob = $data["DOB"];
                            $email = $data["email"];
                            $verify = $data["is_Verified"];
                            $tel = $data["Phone_Number"];
                            $whatsapp = $data["WhatsApp"];
                            $district = $data["District"];
                            $uni = $data["University"];
                            $faculty = $data["Faculty"];
                            $batch = $data["Batch_No"];
                            $index = $data["Index_No"];

                            echo '<p class="mt-5"><i>You can download this data as a text file if you need it.</i> <a href="../../assets/' . $fName . '-FlashZoom.txt" onclick="fileDownload(' . $id . ')" download><i class="fa-solid fa-file-arrow-down"></i> Click here</a></p>';

                            if ($verify == 1) {
                                $badge = '<span class="badge badge-success">Verify</span>';
                            } else {
                                $badge = '<span class="badge badge-warning">Not Verify</span>';
                            }

                            $table = '<table class="table">'
                                . '<tbody>'
                                . '<tr>'
                                . '<td>First Name</td>'
                                . '<th>' . $fName . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Last Name</td>'
                                . '<th>' . $lName . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Gender</td>'
                                . '<th>' . $gender . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Date of Birth</td>'
                                . '<th>' . $dob . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>E-mail</td>'
                                . '<th>' . $email . ' ' . $badge . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Phone Number</td>'
                                . '<th>' . $tel . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>WhatsApp</td>'
                                . '<th>' . $whatsapp . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>District</td>'
                                . '<th>' . $district . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>University</td>'
                                . '<th>' . $uni . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Faculty</td>'
                                . '<th>' . $faculty . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Batch Number</td>'
                                . '<th>' . $batch . '</th>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Index Number</td>'
                                . '<th>' . $index . '</th>'
                                . '</tr>'
                                . '</tbody>'
                                . '</table>';
                            echo $table;
                            
                            $textContent = "---- Details ----"
                                . "\n"
                                . "\nFirst Name - " . $fName
                                . "\nLast Name - " . $lName
                                . "\nGender - " . $gender
                                . "\nDate of Birth - " . $dob
                                . "\nE-mail - " . $email
                                . "\nPhone Number - " . $tel
                                . "\nWhatsApp - " . $whatsapp
                                . "\nDistrict - " . $district
                                . "\nUniversity - " . $uni
                                . "\nFaculty - " . $faculty
                                . "\nBatch Number - " . $batch
                                . "\nIndex Number - " . $index
                                . "\n"
                                . "\n**This is an auto-generated text file by FlashZoom.**";

                            $file = "../../assets/" . $fName . "-FlashZoom.txt";
                            $txt = fopen($file, "w") or die("Unable to open file!");
                            fwrite($txt, $textContent);
                            fclose($txt);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete text file -->
    <script>
        function fileDownload(ID) {
            let xhr = new XMLHttpRequest();
            let endPoint = './fileDownload.php?userID=' + ID;
            xhr.open('GET', endPoint, true);
            xhr.onload = function() {
                if (this.status != 200) {
                    console.log("Server error");
                }
            }
            xhr.send();
        }
    </script>

    <!-- dispaly time on navbar -->
    <script type="text/javascript">
        let clockElement = document.getElementById('time');

        function clock() {
            clockElement.textContent = new Date().toLocaleString();
        }

        setInterval(clock, 1000);
    </script>

    <!-- Core theme JS-->
    <script src="../../js/dashboard.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="../../js/hostNameActivation.js"></script>
</body>

</html>