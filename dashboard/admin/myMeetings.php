<?php
//Start Session
session_start();

require '../../lib/db.php';

//Check the user is logged in
if ($_SESSION['whoAmI'] != 'lecturer') {
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
    <title>My Meetings - FlashZoom</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../../css/dashboard.css" rel="stylesheet" />

</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light"><strong>FlashZoom</strong></div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./dashboard.php"><i class="fa-solid fa-plus"></i> Add meetings</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="./myMeetings.php"><i class="fa-solid fa-video"></i> My meetings</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./notifications.php"><i class="far fa-bell"></i> Notifications</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./studentsInfo.php"><i class="fa-solid fa-user-graduate"></i> Student Info</a>
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
                    <div id="notify" class="mt-5"></div>
                    <div class="mt-5">
                        <div>
                            <h4><i class="fa-solid fa-circle-check"></i> Active Meetings</h4>
                            <hr>
                            <!-- Active Cards -->
                            <div class="d-flex flex-wrap justify-content-around" id="activeMeetings">
                                <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div> <!-- cards-->
                        </div>
                        <div>
                            <h4><i class="fa-solid fa-circle-exclamation"></i> Draft Meetings</h4>
                            <hr>
                            <!-- Draft Cards -->
                            <div class="d-flex flex-wrap justify-content-around" id="draftMeetings">
                                <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div> <!-- cards-->
                        </div>
                    </div>
                </div>
                <!-- container -->
            </div>
        </div>
    </div>

    <!--Meeting card loader-->
    <script>
        //When window load
        window.onload = getData();

        function getData() {
            let xhr = new XMLHttpRequest();
            let endPoint = './getMyMeetings.php';
            xhr.open('GET', endPoint, true);

            xhr.onload = function() {
                if (this.status == 200) {
                    let data = JSON.parse(this.responseText);
                    let activeOnes = '';
                    let darftOnes = '';

                    for (let i in data) {
                        if (data[i].is_Active == 1) {
                            activeOnes += '<div class="card text-white bg-dark m-2 animate__animated animate__zoomIn">' +
                                '<div class="card-header">' + data[i].meetingGroup + '</div>' +
                                '<div class="card-body">' +
                                '<h5 class="card-title">' + data[i].Title + '</h5>' +
                                '<ul>' +
                                '<li>Start Time : ' + data[i].Start_Time + '</li>' +
                                '<li>End Time : ' + data[i].End_Time + '</li>' +
                                '<li>Schedule : ' + data[i].Schedule + '</li>' +
                                '<li>Host Name : ' + data[i].Host_Name + '</li>' +
                                '<li>Meeting ID : ' + data[i].Meeting_ID + '</li>' +
                                '<li>Passcode : ' + data[i].Passcode + '</li>' +
                                '</ul>' +
                                '<a href="' + data[i].Link + '" target="_blank" class="btn btn-primary"><i class="fa-solid fa-video"></i></a>' +
                                '<button class="btn btn-danger mx-2" id="delete" onclick="deleteOne(' + data[i].ID + ')"><i class="fa-solid fa-trash-can"></i></button>' +
                                '</div>' +
                                '</div>';
                        } else if (data[i].is_Active == 0) {
                            darftOnes += '<div class="card text-dark bg-light m-2 animate__animated animate__zoomIn">' +
                                '<div class="card-header">' + data[i].meetingGroup + '</div>' +
                                '<div class="card-body">' +
                                '<h5 class="card-title">' + data[i].Title + '</h5>' +
                                '<ul>' +
                                '<li>Start Time : ' + data[i].Start_Time + '</li>' +
                                '<li>End Time : ' + data[i].End_Time + '</li>' +
                                '<li>Schedule : ' + data[i].Schedule + '</li>' +
                                '<li>Host Name : ' + data[i].Host_Name + '</li>' +
                                '<li>Meeting ID : ' + data[i].Meeting_ID + '</li>' +
                                '<li>Passcode : ' + data[i].Passcode + '</li>' +
                                '</ul>' +
                                '<button class="btn btn-success" id="activate" onclick="activeOne(' + data[i].ID + ')"><i class="fa-solid fa-eye"></i> Publish</button>' +
                                '<a href="' + data[i].Link + '" target="_blank" class="btn btn-primary mx-2"><i class="fa-solid fa-video"></i></a>' +
                                '<button class="btn btn-danger" id="delete" onclick="deleteOne(' + data[i].ID + ')"><i class="fa-solid fa-trash-can"></i></button>' +
                                '</div>' +
                                '</div>';
                        }
                    }
                    document.getElementById('activeMeetings').innerHTML = activeOnes;
                    document.getElementById('draftMeetings').innerHTML = darftOnes;
                }
            }
            xhr.send();
        }
    </script>
    <!--Meeting card loader-->

    <!-- delete meeting-->
    <script>
        function deleteOne(cardID) {
            let xhr = new XMLHttpRequest();
            let endPoint = './deleteMeeting.php?cardID=' + cardID;
            xhr.open('GET', endPoint, true);

            xhr.onload = function() {
                if (this.status == 200) {
                    getData();
                    let notification = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Successfully deleted.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>                    
                    `;
                    document.getElementById("notify").innerHTML = notification;
                    //location.reload();
                } else {
                    let notification = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error - Something went wrong!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    `;
                    document.getElementById("notify").innerHTML = notification;
                }
            }
            xhr.send();

        }
    </script>

    <!-- activate meeting-->
    <script>
        function activeOne(cardID) {
            let xhr = new XMLHttpRequest();
            let endPoint = './activeMeeting.php?cardID=' + cardID;
            xhr.open('GET', endPoint, true);

            xhr.onload = function() {
                if (this.status == 200) {
                    getData();
                    let notification = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Successfully Published.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>                    
                    `;
                    document.getElementById("notify").innerHTML = notification;
                    //location.reload();
                } else {
                    let notification = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error - Something went wrong!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    `;
                    document.getElementById("notify").innerHTML = notification;
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

</body>

</html>