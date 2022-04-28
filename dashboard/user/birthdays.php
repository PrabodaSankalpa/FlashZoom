<?php
//Start Session
session_start();

require '../../lib/db.php';

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
    <title>Birthdays - FlashZoom</title>
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
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#"><i class="far fa-bell"></i> Notifications</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="./birthdays.php"><i class="fas fa-birthday-cake"></i> Birthdays</a>
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
                    <img src="../../assets/images/gift-box-lottie-animation.gif" class="mx-auto d-block" style="height: 18rem;" alt="Gift-Box">
                    <h2 class="mb-3">Today's Birthdays...</h2>

                    <!-- birthday table -->
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">WhatsApp</th>
                                <th scope="col">Greeting</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $month = date("m");
                            $date = date("d");
                            $query = "SELECT * FROM users;";
                            $result_set = mysqli_query($connection, $query);
                            

                            if (mysqli_num_rows($result_set) > 0) {
                                while ($row = mysqli_fetch_assoc($result_set)) {
                                    $bMonth = substr($row["DOB"], -5, -3);
                                    $bDate = substr($row["DOB"], -2);
                                    if($month == $bMonth AND $date == $bDate){
                                        echo "<tr>";
                                        echo "<td>" . $row["First_Name"] . " " . $row["Last_Name"] . "</td>";
                                        echo "<td>" . $row["WhatsApp"] . "</td>";
                                        echo "<th>Many Happy returns fo the day " . $row["First_Name"] . "!🎉</th>";
                                    }
                                }
                            }

                            ?>
                        </tbody>
                    </table>
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