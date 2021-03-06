<?php
//Session Start
session_start();

require '../lib/db.php';

//Autofill veriables
$firstName = "";
$lastName = "";
$whatsappNum = "";
$email = "";

//Get data form user
if (isset($_POST['submit'])) {

  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $whatsappNum = $_POST['whatsappNum'];
  $email = $_POST['email'];

  //Check errors
  $errors = array();
  $req_fields = array("title", "firstName", "lastName", "whatsappNum", "email", "password", "cPassword");

  //Check required
  foreach ($req_fields as $field) {
    if (empty(trim($_POST[$field]))) {
      $errors[] = $field . " is required";
    }
  }

  //Check Max Length
  $max_len_fields = array("title" => 10, "firstName" => 255, "lastName" => 255, "whatsappNum" => 10, "email" => 255, "password" => 255, "cPassword" => 255);

  foreach ($max_len_fields as $field => $max_len) {
    if (strlen(trim($_POST[$field])) > $max_len) {
      $errors[] = $field . " is too long. Max: " . $max_len;
    }
  }

  //Chech email
  function valid_email($str)
  {
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
  }

  if (!valid_email($_POST["email"])) {
    $errors[] = "E-mail address is invalid";
  }

  //Check Password
  if ($_POST['password'] != $_POST['cPassword']) {
    $errors[] = "Confirm Password is invalid";
  }

  //check email is already exists

  $sanitiz_email = mysqli_real_escape_string($connection, $_POST['email']);
  $query = "SELECT * FROM admins WHERE email = '{$sanitiz_email}' LIMIT 1;";

  $result_set = mysqli_query($connection, $query);

  if ($result_set) {
    if (mysqli_num_rows($result_set) == 1) {
      $errors[] = "E-mail address already exists";
    }
  }

  //Add data to database
  if (empty($errors)) {
    //Sanitiz User data

    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
    $whatsappNum = mysqli_real_escape_string($connection, $_POST['whatsappNum']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $hashed_password = sha1($password);
    $verify_code = sha1($sanitiz_email . time());
    $verify_URL = "http://localhost/FlashZoom/routes/verification.php?code=" . $verify_code;
    $avatar_URL = "https://ui-avatars.com/api/?name=" . $firstName . "+" . $lastName;

    $query = "INSERT INTO admins (Title, First_Name, Last_Name, WhatsApp, email, password, Verify_Code, Avatar_URL) VALUES ('{$title}', '{$firstName}', '{$lastName}', '{$whatsappNum}', '{$sanitiz_email}', '{$hashed_password}', '{$verify_code}', '{$avatar_URL}');";

    $result = mysqli_query($connection, $query);

    //Generate email
    $to = $sanitiz_email;
    $sender = "maxtintinmax@gmail.com";
    $mail_subject = "Verification Link - FlashZoom";
    $email_body = '
    
    <!DOCTYPE html>

<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<title></title>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		@media (max-width:520px) {
			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.row-content {
				width: 100% !important;
			}

			.stack .column {
				width: 100%;
				display: block;
			}
		}
	</style>
</head>
<body style="background-color: #FFFFFF; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF;" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3f3f3;" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
<tbody>
<tr>
<td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
<table border="0" cellpadding="0" cellspacing="0" class="heading_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td style="width:100%;text-align:center;">
<h1 style="margin: 0; color: #555555; font-size: 23px; font-family: Tahoma, Verdana, Segoe, sans-serif; line-height: 120%; text-align: center; direction: ltr; font-weight: normal; letter-spacing: normal; margin-top: 0; margin-bottom: 0;"><strong>Verification Link</strong></h1>
</td>
</tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
<tr>
<td>
<div style="font-family: sans-serif">
<div style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #555555; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
<p style="margin: 0; font-size: 12px; mso-line-height-alt: 14.399999999999999px;">??</p>
</div>
</div>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
<tbody>
<tr>
<td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
<table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
<tr>
<td>
<div style="font-family: sans-serif">
<div style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
<p style="margin: 0; font-size: 16px;"><span style="font-size:16px;color:#000000;">Dear ' . $firstName . ',</span></p>
<p style="margin: 0; font-size: 16px; mso-line-height-alt: 14.399999999999999px;">??</p>
<p style="margin: 0; font-size: 16px;"><span style="font-size:16px;color:#000000;">Thank you for join with <strong>FlashZoom</strong>. You can verify your email by click the verify button or the link.</span></p>
</div>
</div>
</td>
</tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" class="button_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td>
<div align="center">
<!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="' . $verify_URL . '" style="height:42px;width:121px;v-text-anchor:middle;" arcsize="10%" stroke="false" fillcolor="#3AAEE0"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px"><![endif]--><a href="' . $verify_URL . '" style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#3AAEE0;border-radius:4px;width:auto;border-top:1px solid #3AAEE0;border-right:1px solid #3AAEE0;border-bottom:1px solid #3AAEE0;border-left:1px solid #3AAEE0;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;" target="_blank"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;letter-spacing:normal;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;"><strong>Verify Now</strong></span></span></a>
<!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
</div>
</td>
</tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
<tr>
<td>
<div style="font-family: sans-serif">
<div style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #555555; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
<p style="margin: 0; font-size: 12px;"><span style="color:#000000;font-size:16px;">Verification link : ' . $verify_URL . '</span></p>
</div>
</div>
</td>
</tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
<tr>
<td>
<div style="font-family: sans-serif">
<div style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #555555; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
<p style="margin: 0; font-size: 16px;"><span style="color:#000000;font-size:16px;">Thank you,</span></p>
<p style="margin: 0; font-size: 16px;"><span style="color:#000000;font-size:16px;">FlashZoom.</span></p>
</div>
</div>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3f3f3;" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
<tbody>
<tr>
<td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
<table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
<tr>
<td>
<div style="font-family: sans-serif">
<div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #555555; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
<p style="margin: 0; font-size: 14px; text-align: center;">Five Stack - 2022</p>
</div>
</div>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
<tbody>
<tr>
<td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
<table border="0" cellpadding="0" cellspacing="0" class="icons_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td style="color:#9d9d9d;font-family:inherit;font-size:15px;padding-bottom:5px;padding-top:5px;text-align:center;">
<table cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td style="text-align:center;">
<!--[if vml]><table align="left" cellpadding="0" cellspacing="0" role="presentation" style="display:inline-block;padding-left:0px;padding-right:0px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;"><![endif]-->
<!--[if !vml]><!-->
<table cellpadding="0" cellspacing="0" class="icons-inner" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
<!--<![endif]-->
<tr>
<!-- <td style="text-align:center;padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:6px;"><a href="https://www.designedwithbee.com/"><img align="center" alt="Designed with BEE" class="icon" height="32" src="images/bee.png" style="display: block; height: auto; border: 0;" width="34"/></a></td> -->
<td style="font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:15px;color:#9d9d9d;vertical-align:middle;letter-spacing:undefined;text-align:center;"><a href="https://www.designedwithbee.com/" style="color:#9d9d9d;text-decoration:none;">Designed with BEE</a></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table><!-- End -->
</body>
</html>
    
  
    
    ';
    $header = "From: {$sender}\r\nMIME-Version: 1.0" . "\r\nContent-type:text/html;charset=UTF-8" . "\r\n";

    //Send email
    $send_mail_result = mail($to, $mail_subject, $email_body, $header);

    if ($result && $send_mail_result) {
      header("Location:../index.php?user_added=true&email=true");
    } else {
      $errors[] = "Registration Failed!";
    }
  }
}




?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="title" content="FlashZoom">
  <meta name="description" content="You can simply register as a lecturer using this link. After registering, you have to wait for approval.">
  <meta name="keywords" content="flashzoom, university of Sri Jayewardenepura, zoom, zoom links, first year project, five stack, flash zoom">
  <meta name="robots" content="index, follow">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="language" content="English">
  <meta name="author" content="Five Stack (Praboda, Isuru, Niroshani, Yeshani, and Deshani)">

  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../css/registration.css" />
  <title>Lecturer Registration - FlashZoom</title>
</head>

<body>
  <!-- navbar open -->
  <nav class="navbar navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="../index.php"><img src="../assets/images/flashzoom_logo.png" alt="" height="25" /></a>

      <a class="btn btn-outline-primary" type="submit" href="../index.php#login-area">Login</a>
    </div>
  </nav>
  <!--navbar end here-->
  <div class="container mt-3">
    <div class="row title">
      <div class="col-md-12">
        <h2>Registration Form</h2>
        <p>Enter you details correctly and make your accounct</p>
      </div>
    </div>

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

    <div class="row mt-3 justify-content-center">
      <div class="col-md-6">
        <div class="form-area">
          <form action="./lecRegistration.php" method="post">
            <div class="row">

              <div class="col-md-6">
                <label for="title" class="form-label">Title</label>
                <select name="title" class="form-control form-select" aria-label="Default select example">
                  <option selected>Open this select menu</option>
                  <option value="Dr.">Dr.</option>
                  <option value="Hon.">Hon.</option>
                  <option value="Mr.">Mr.</option>
                  <option value="Mrs.">Mrs.</option>
                  <option value="Ms.">Ms.</option>
                  <option value="Prof.">Prof.</option>
                  <option value="Rev.">Rev.</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" placeholder="First Name" name="firstName" id="firstName" class="form-control" <?php echo 'value="' . $firstName . '"'; ?> />
              </div>
              <div class="col-md-6 mt-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" placeholder="Last Name" name="lastName" id="lastName" class="form-control" <?php echo 'value="' . $lastName . '"'; ?> />
              </div>
              <div class="col-md-6 mt-3">
                <label for="whatsappNum" class="form-label">WhatsApp Number</label>
                <input type="tel" placeholder="WhatsApp Number" name="whatsappNum" id="whatsappNum" class="form-control" <?php echo 'value="' . $whatsappNum . '"'; ?> />
              </div>
              <div class="col-md-12 mt-3">
                <label for="email" class="form-label">E-mail Address</label>
                <input type="email" placeholder="E-mail Address" name="email" id="email" class="form-control" <?php echo 'value="' . $email . '"'; ?> />
              </div>
              <div class="col-md-6 mt-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" placeholder="Password" name="password" id="password" class="form-control">
              </div>
              <div class="col-md-6 mt-3">
                <label for="cPassword" class="form-label">Confirm Password</label>
                <input type="password" placeholder="Confirm Password" name="cPassword" id="cPassword" class="form-control">
              </div>
              <div class="col-md-12 mt-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="agree" name="agree" id="agree">
                  <label class="form-check-label" for="agree">
                    I agree to the Terms and Conditions
                  </label>
                </div>
              </div>

              <div class="col-md-12 mt-3">
                <button type="submit" name="submit" disabled class="btn btn-primary" id="submit"><i class="fas fa-clipboard-list"></i> Register</button>
              </div>

            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="../js/submitActivation.js"></script>
</body>

</html>