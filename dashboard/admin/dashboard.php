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

if (isset($_POST['submit'])) {
    //errors array
    $errors = array();
    //Check title is correct
    if (!isset($_POST['title']) || strlen(trim($_POST['title'])) < 1) {
        $errors[] = "Title is Missing/Invalid!";
    }
    //Check start time is ok
    if (empty($_POST['startTime'])) {
        $errors[] = "Start time is required";
    }
    //Check end time is ok
    if (empty($_POST['endTime'])) {
        $errors[] = "End time is required";
    }
    //Check date is ok
    if (empty($_POST['date'])) {
        $errors[] = "Date is required";
    }
    //Check schedule is ok
    if (!empty($_POST['scheduleDay']) && $_POST['scheduleDay'] == 'notSelected') {
        $errors[] = "Schedule Day is required";
    }
    //Check meeting ID is correct
    if (!isset($_POST['meetingID']) || strlen(trim($_POST['meetingID'])) < 1) {
        $errors[] = "Meeting ID is Missing/Invalid!";
    }
    //Check meeting passcode is correct
    if (!isset($_POST['passcode']) || strlen(trim($_POST['passcode'])) < 1) {
        $errors[] = "Meeting Passcode is Missing/Invalid!";
    }
    //Check meeting link is correct
    if (!isset($_POST['link']) || strlen(trim($_POST['link'])) < 1) {
        $errors[] = "Meeting Link is Missing/Invalid!";
    }
    //Check the host name is correct
    if (!isset($_POST['hostNameDef']) && strlen(trim($_POST['hostName'])) < 1) {
        $errors[] = "Custom host name is Invalid!";
    }

    if (empty($errors)) {
        //assign the data to variables
        $title = mysqli_real_escape_string($connection, $_POST['title']);
        $startTime = mysqli_real_escape_string($connection, $_POST['startTime']);
        $endTime = mysqli_real_escape_string($connection, $_POST['endTime']);
        $date = mysqli_real_escape_string($connection, $_POST['date']);

        $scheduleMethod = mysqli_real_escape_string($connection, $_POST['scheduleMethod']);
        $scheduleDay = mysqli_real_escape_string($connection, $_POST['scheduleDay']);
        $schedule = $scheduleMethod . ' ' . $scheduleDay;

        $meetingID = mysqli_real_escape_string($connection, $_POST['meetingID']);
        $passcode = mysqli_real_escape_string($connection, $_POST['passcode']);
        $link = mysqli_real_escape_string($connection, $_POST['link']);
        //Get host name
        if (isset($_POST['hostNameDef'])) {
            $hostName = $_SESSION['user_title'] . ' ' . $_SESSION['user_firstName'] . ' ' . $_SESSION['user_lastName'];
        } else {
            $hostName = mysqli_real_escape_string($connection, $_POST['hostName']);
        }

        //Prepare a Query
        $query = "INSERT INTO zoomlinks (Title, Start_Time, End_Time, Date, Schedule, Meeting_ID, Passcode, Link, Host_Name) VALUES ('{$title}', '{$startTime}', '{$endTime}', '{$date}', '{$schedule}', '{$meetingID}', '{$passcode}', '{$link}', '{$hostName}');";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            header('Location: ./dashboard.php?status=success');
        } else {
            $errors[] = "Something went wrong!";
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
    <title>Dashboard - FlashZoom</title>
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
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!"><i class="far fa-bell"></i> Notifications</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!"><i class="fas fa-birthday-cake"></i> Birthdays</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!"><i class="fas fa-user-cog"></i> Settings</a>
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
                            <li class="nav-item active"><a class="nav-link" href="#">Profile</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../routes/logout.php">Logout</a></li>

                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->
            <div class="container-fluid">
                <h1 class="mt-4">Hello, <?php echo $_SESSION['user_title'] . ' ' . $_SESSION['user_firstName']; ?></h1>
                <p>Post your meeting details to the FlashZoom.</p>
                <!-- Form -->
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (!empty($errors)) {
                                echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">';
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
                    <form action="./dashboard.php" method="post">
                        <div class="form-group row mb-3">
                            <label for="title" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="startTime" class="col-sm-2 col-form-label">Start Time</label>
                            <div class="col-sm-10">
                                <input type="time" class="form-control" id="startTime" name="startTime">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="endTime" class="col-sm-2 col-form-label">End Time</label>
                            <div class="col-sm-10">
                                <input type="time" class="form-control" id="endTime" name="endTime">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="date" class="col-sm-2 col-form-label">Date</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="date" name="date">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Schedule</label>
                            <div class="col-sm-10">
                                <select class="form-control custom-select mb-1" id="scheduleMethod" name="scheduleMethod">
                                    <option value="Every" selected>Every</option>
                                    <option value="This">This</option>
                                    <option value="Week after week">Week after week</option>
                                </select>
                                <select class="form-control custom-select" id="scheduleDay" name="scheduleDay">
                                    <option value="notSelected" selected>Choose the day</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="meetingID" class="col-sm-2 col-form-label">Meeting ID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="meetingID" name="meetingID" placeholder="Meeting ID">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="passcode" class="col-sm-2 col-form-label">Meeting Passcode</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="passcode" name="passcode" placeholder="Meeting Passcode">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="link" class="col-sm-2 col-form-label">Meeting Link</label>
                            <div class="col-sm-10">
                                <input type="url" class="form-control" id="link" name="link" placeholder="Meeting Link">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-sm-2">Host Name</div>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked id="hostNameDef" name="hostNameDef">
                                    <label class="form-check-label" for="hostNameDef">
                                        Use my name as the host name
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="hostName" class="col-sm-2 col-form-label">Custom host name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hostName" name="hostName" disabled placeholder="Custom Host Name">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-sm-10">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-check"></i> Publish</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- container -->
            </div>
        </div>
    </div>

    <!-- Core theme JS-->
    <script src="../../js/dashboard.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="../../js/hostNameActivation.js"></script>
</body>

</html>