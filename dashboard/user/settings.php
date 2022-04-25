<?php
//Start Session
session_start();

require '../../lib/db.php';

//Check the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
}

if (isset($_POST['submit'])) {
    //errors array
    $errors = array();

    //Check password is correct
    if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
        $errors[] = "Password is Missing/Invalid!";
    }

    //Check password2 is correct
    if (!isset($_POST['password2']) || strlen(trim($_POST['password2'])) < 1) {
        $errors[] = "Comfirm Password is Missing/Invalid!";
    }

    if ($_POST['password'] != $_POST['password2']) {
        $errors[] = "Passwords are not maching!";
    }

    if (empty($errors)) {
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $password2 = mysqli_real_escape_string($connection, $_POST['password2']);

        $hashed_password = sha1($password);

        $query = "UPDATE users SET password = '{$hashed_password}' LIMIT 1;";
        $result_set = mysqli_query($connection, $query);

        if (mysqli_affected_rows($connection) > 0) {
            header('Location: ./settings.php?change=success');
        } else {
            $errors[] = "Someting went wrong!";
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
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./dashboard.php"><i class="fa-solid fa-video"></i> Meetings</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#"><i class="far fa-bell"></i> Notifications&nbsp;&nbsp;<span class="badge badge-pill badge-danger">0</span></a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./birthdays.php"><i class="fas fa-birthday-cake"></i> Birthdays</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="./settings.php"><i class="fas fa-user-cog"></i> Settings</a>
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
                        <h2>Hi, <?php echo $_SESSION['user_firstName']; ?></h2>
                    </div>

                    <h4 class="mt-5">Change My Password</h4>
                    <!-- form -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- errors -->
                            <?php

                            if (isset($_GET['change']) && ($_GET['change'] == 'success')) {
                                echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">';
                                echo '<strong>Password change successful!</strong><br>';
                                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                                echo '<span aria-hidden="true">&times;</span>';
                                echo '</button>';
                                echo '</div>';
                            }


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
                    <form action="./settings.php" method="POST">
                        <div class="form-group row mb-3">
                            <label for="password" class="col-md-2 col-sm-2 col-form-label">New Password</label>
                            <div class="col-md-4 col-sm-10">
                                <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                            </div>
                            <label for="password2" class="col-md-2 col-sm-2 col-form-label">Comfirm Password</label>
                            <div class="col-md-4 col-sm-10">
                                <input type="password" class="form-control" id="password2" name="password2" placeholder="Comfirm Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa-solid fa-key"></i> Change my password</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


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