<?php
//Start Session
session_start();

//Check the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
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
    <!-- Core theme CSS (includes Bootstrap)-->
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
                <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="./dashboard.php"><i class="fa-solid fa-video"></i> Meetings</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#"><i class="far fa-bell"></i> Notifications&nbsp;&nbsp;<span class="badge badge-pill badge-danger">0</span></a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#"><i class="fas fa-birthday-cake"></i> Birthdays</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#"><i class="fas fa-user-cog"></i> Settings</a>
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
                            <li class="nav-item"><span class="nav-link" id="time">Time</span></li>
                            <li class="nav-item"><a class="nav-link" href="../../routes/logout.php"><i class="fa-solid fa-power-off"></i> Logout</a></li>

                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->
            <div class="container-fluid">
                <div class="container">
                    <div class="d-flex align-items-center mt-2">
                        <img src="<?php echo $_SESSION['avatar']; ?>" class="rounded-circle mr-2" alt="profile photo">
                        <h2>Hi, <?php echo $_SESSION['user_firstName']; ?></h2>
                    </div>

                    <div class="form-group row mt-5 d-flex justify-content-center">
                        <label for="filter" class="col-md-2 col-form-label fw-bold"><i class="fa-solid fa-filter"></i> Filter</label>
                        <div class="col-md-4">
                            <select class="form-control custom-select mb-1" id="filter" name="filter">
                                <optgroup label="Departments">
                                    <option value="All" selected>All</option>
                                    <option value="Common">Common</option>
                                    <option value="ET">ET</option>
                                    <option value="BST">BST</option>
                                    <option value="ICT">ICT</option>
                                </optgroup>
                                <optgroup label="Groups">
                                    <option value="Group 01">Group 01</option>
                                    <option value="Group 02">Group 02</option>
                                    <option value="Group 03">Group 03</option>
                                    <option value="Group 04">Group 04</option>
                                    <option value="Group 05">Group 05</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <!-- cards -->
                    <div class="row mt-5">
                        <div class="card text-white bg-dark col-md-3">
                            <h5 class="card-header">ICT</h5>
                            <div class="card-body">
                                <h5 class="card-title">SE Meeting</h5>
                                <ul>
                                    <li>Start Time : 03.00</li>
                                    <li>End Time : 05.00</li>
                                    <li>Schedule : Every Tuesday</li>
                                    <li>Host Name : Dr. Nalaka Lankasena</li>
                                    <li>Meeting ID : 1234534567</li>
                                    <li>Passcode : Abc@1234</li>
                                </ul>
                                <a href="https://www.google.lk/" target="_blank" class="btn btn-primary"><i class="fa-solid fa-angles-right"></i> Join Now</a>
                            </div>
                        </div>
                    </div>
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
</body>

</html>