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
    <meta name="title" content="FlashZoom">
    <meta name="description" content="This is a web application that allows you to manage your zoom links and notifications relevant to your university and department.">
    <meta name="keywords" content="flashzoom, university of Sri Jayewardenepura, zoom, zoom links, first year project, five stack, flash zoom">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Five Stack (Praboda, Isuru, Niroshani, Yeshani, and Deshani)">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Notifications - FlashZoom</title>
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
                <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="./notifications.php"><i class="far fa-bell"></i> Notifications</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./birthdays.php"><i class="fas fa-birthday-cake"></i> Birthdays</a>
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
                        <h2>Hi, <?php echo $_SESSION['user_firstName']; ?></h2>
                    </div>

                    <div class="my-5">
                        <h3>Notifications</h3>
                    </div>
                    <!-- All notifications here  -->
                    <div>
                        <?php

                        //define total number of results you want per page  
                        $results_per_page = 5;

                        $query = "SELECT * FROM notifications;";
                        $result = mysqli_query($connection, $query);

                        $number_of_result = mysqli_num_rows($result);
                        //determine the total number of pages available  
                        $number_of_page = ceil($number_of_result / $results_per_page);

                        //determine which page number visitor is currently on  
                        if (!isset($_GET['page'])) {
                            $page = 1;
                        } else {
                            $page = $_GET['page'];
                        }

                        //determine the sql LIMIT starting number for the results on the displaying page  
                        $page_first_result = ($page - 1) * $results_per_page;

                        $query = "SELECT notifications.Caption, notifications.Attention, notifications.Message, notifications.Date, notifications.Time, admins.Title, admins.First_Name, admins.Last_Name, admins.Avatar_URL FROM notifications INNER JOIN admins ON notifications.Author_ID=admins.ID ORDER BY notifications.ID DESC LIMIT " . $page_first_result . "," . $results_per_page . ";";
                        $result_set = mysqli_query($connection, $query);



                        if (mysqli_num_rows($result_set) == 0) {
                            $errors[] = "No Notification Founded!";
                        } else {
                            while ($data = mysqli_fetch_assoc($result_set)) {
                                $nCaption = $data['Caption'];
                                $nAttention = $data['Attention'];
                                $nMessage = $data['Message'];
                                $nDate = $data['Date'];
                                $nTime = $data['Time'];
                                $nAuthor = $data['Title'] . ' ' . $data['First_Name'] . ' ' . $data['Last_Name'];
                                $nAvatar = $data['Avatar_URL'];

                                echo '<div class="card mb-3">';
                                echo '<div class="card-header">';
                                echo "Time: " . $nTime . " | Date: " . $nDate;
                                echo '</div>';
                                echo '<div class="card-body">';
                                echo '<div class="d-flex align-items-center mb-3">';
                                echo '<img src = "';
                                echo $nAvatar;
                                echo '" alt = "Profile Photo" class="rounded-circle">';
                                echo '<p class="mx-3">';
                                echo $nAuthor;
                                echo '</p>';
                                if ($nAttention == 'Low') {
                                    echo '<p>| Attention: <span class="badge badge-pill badge-success">Low</span></p>';
                                } elseif ($nAttention == 'High') {
                                    echo '<p>| Attention: <span class="badge badge-pill badge-danger">High</span></p>';
                                } else {
                                    echo '<p>| Attention: <span class="badge badge-pill badge-warning">Medium</span></p>';
                                }
                                echo '</div>';
                                echo '<h5>';
                                echo $nCaption;
                                echo '</h5>';
                                echo '<p>';
                                echo $nMessage;
                                echo '</p>';
                                echo '</div>';
                                echo '</div>';
                            }
                            //display the link of the pages in URL 
                            echo '<nav aria-label="Page navigation example">';
                            echo '<ul class="pagination">';
                            for ($page = 1; $page <= $number_of_page; $page++) {
                                echo '<li class="page-item"><a class="page-link" href = "notifications.php?page=' . $page . '">' . $page . '</a></li>';
                            }
                            echo '</ul></nav>';
                        }
                        ?>
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
    <script src="../../js/hostNameActivation.js"></script>
</body>

</html>