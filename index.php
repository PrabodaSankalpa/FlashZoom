<?php
//Session Start
session_start();

require './lib/db.php';

//check user already logged in..
if (isset($_SESSION['whoAmI']) && $_SESSION['whoAmI'] == 'lecturer') {
  header('Location: ./dashboard/admin/dashboard.php');
} elseif (isset($_SESSION['whoAmI']) && $_SESSION['whoAmI'] == 'student') {
  header('Location: ./dashboard/user/dashboard.php');
}

//Check the user click the submit
if (isset($_POST['submit'])) {
  //errors array
  $errors = array();
  //Check email is correct
  if (!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1) {
    $errors[] = "email is Missing/Invalid!";
  }
  //Check password is correct
  if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
    $errors[] = "Password is Missing/Invalid!";
  }

  //Check the errors array
  if (empty($errors)) {
    //assign the data to variables
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $hashed_password = sha1($password);

    $query = "SELECT * FROM users WHERE email = '{$email}' AND password = '{$hashed_password}' LIMIT 1;";
    $result_set = mysqli_query($connection, $query);


    if (mysqli_num_rows($result_set) == 1) {
      $user = mysqli_fetch_assoc($result_set);

      //Session Variables
      $_SESSION['whoAmI'] = 'student';
      $_SESSION['user_id'] = $user['ID'];
      $_SESSION['user_firstName'] = $user['First_Name'];
      $_SESSION['user_lastName'] = $user['Last_Name'];
      $_SESSION['avatar'] = $user['Avatar_URL'];

      header('Location: ./dashboard/user/dashboard.php');
    } else {
      //Admin table Check
      $query = "SELECT * FROM admins WHERE email = '{$email}' AND password = '{$hashed_password}' AND is_Deleted = 0 LIMIT 1;";
      $result_set = mysqli_query($connection, $query);

      if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
          $user = mysqli_fetch_assoc($result_set);

          if ($user['Is_Accepted'] == 0) {
            $errors[] = "Waiting for Admin Approval!";
          } else {
            //Session Variables
            $_SESSION['whoAmI'] = 'lecturer';
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['user_title'] = $user['Title'];
            $_SESSION['user_firstName'] = $user['First_Name'];
            $_SESSION['user_lastName'] = $user['Last_Name'];
            $_SESSION['avatar'] = $user['Avatar_URL'];

            header('Location: ./dashboard/admin/dashboard.php');
          }
        } else {
          $errors[] = "email or password is Missing/Invalid!";
        }
      } else {
        $errors[] = "Database Failed!";
      }
    }
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="title" content="FlashZoom">
  <meta name="description" content="This is a web application that allows you to manage your zoom links and notifications relevant to your university and department.">
  <meta name="keywords" content="flashzoom, university of Sri Jayewardenepura, zoom, zoom links, first year project, five stack, flash zoom">
  <meta name="robots" content="index, follow">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="language" content="English">
  <meta name="author" content="Five Stack (Praboda, Isuru, Niroshani, Yeshani, and Deshani)">

  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="./css/style.css" />
  <title>FlashZoom - Work like the Flash</title>
</head>

<body>
  <!-- navbar open -->
  <nav class="navbar navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php"><img src="./assets/images/flashzoom_logo.png" alt="" height="25" /></a>

      <a class="btn btn-outline-primary" type="submit" href="#login-area">Login</a>
    </div>
  </nav>
  <!--navbar end here-->

  <div class="jumbotron jumbotron-fluid">
    <div class="container">
      <h1 id="headerTitle">Welcome to FlashZoom💙</h1>
      <p>
        This is a small service to University student to find there lecture
        links quickly.
      </p>
    </div>
  </div>
  <div class="content">
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
      <div class="row">
        <div class="col-md-6">
          <div class="register-area animate__animated animate__bounceIn">
            <h2>Why are you waiting for..</h2>
            <p>Register today and do your work like the Flash</p>
            <button type="button" class="btn btn-light btn-lg" data-toggle="modal" data-target="#regModal"><i class="fas fa-user-graduate"></i> Get Started</button>
          </div>

          <?php
          if (isset($_GET['user_added']) && $_GET['user_added'] == true) {
            echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">';
            echo '<strong>Registration successfully🎉, Please Login...</strong><br>';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
          }

          ?>
          <div class="icon-list">
            <div class="item animate__animated animate__flipInX">
              <i class="fas fa-tachometer-alt fa-3x"></i><span>Speed</span>
            </div>
            <div class="item item animate__animated animate__flipInX">
              <i class="fas fa-rocket fa-3x"></i><span>Quick Access</span>
            </div>
            <div class="item item animate__animated animate__flipInX">
              <i class="fas fa-clock fa-3x"></i><span>Anytime</span>
            </div>
            <div class="item item animate__animated animate__flipInX">
              <i class="fas fa-map-marker-alt fa-3x"></i><span>Anywhere</span>
            </div>
          </div>
          <hr>
        </div>
        <!--Col end-->

        <div class="col-md-6 animate__animated animate__fadeInRight" id="login-area">
          <h2>Login here...</h2>
          <p>Please enter your email and password and click the login button.</p>
          <form action="./index.php" method="post" class="p-3 form-border">
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" />
              <div class="valid-feedback">
                Looks good!
              </div>
              <div class="invalid-feedback">
                Please choose a username.
              </div>
              <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" />
              <div class="valid-feedback">
                Looks good!
              </div>
              <div class="invalid-feedback">
                Please choose a username.
              </div>
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1" />
              <label class="form-check-label" for="exampleCheck1">Remember me</label>
            </div>
            <button type="submit" name="submit" class="btn btn-outline-primary mt-3"><i class="fas fa-sign-in-alt"></i> Login</button>
          </form>
        </div>
        <!--Col-end-->
      </div>
    </div>
    <!--container-->
    <div class="wave-container">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#0099ff" fill-opacity="1" d="M0,128L48,133.3C96,139,192,149,288,176C384,203,480,245,576,245.3C672,245,768,203,864,181.3C960,160,1056,160,1152,176C1248,192,1344,224,1392,240L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
      </svg>
    </div>
    <!--wave-container-->
    <footer class="page-footer" style="background-color: #0099ff; margin-top: 100px;">
      <!-- Copyright -->
      <div class="footer-copyright text-center text-light p-3">
        Made with ❤ by
        <a class="text-light" href="#">Five Stack</a>
      </div>
      <!-- Copyright -->
    </footer>
  </div>
  <!-- content -->

  <!-- registration modal -->
  <div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Register Now</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col-md-8">
                <p>Register as a <b>Student</b></p>
              </div>
              <div class="col-md-4"><a class="btn btn-primary" href="./routes/registration.php">Register</a></div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-8">
                <p>Register as a Lecturer</p>
              </div>
              <div class="col-md-4"><a class="btn btn-info" href="./routes/lecRegistration.php">Register</a></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>