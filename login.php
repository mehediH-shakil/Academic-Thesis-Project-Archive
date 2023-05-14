<?php

    $emailError = "";
    $passwordError = "";
   
session_start();
if(isset($_POST['submit'])){

       $email=$_POST['email'];
       $password=$_POST['password'];

       if(empty($email)){
        $emailError="Username is required.";
       }else if(empty($password)){
        $passwordError="Password is required.";
       }


       if(!empty($email) && !empty($password)){
            $conn = mysqli_connect('localhost','root','','archive');
            if(!$conn){
                die("Connection Error");
            }
            $pass=md5($password);

            $query="select email,password from user where email='$email' and password='$pass' limit 1";
            $result = mysqli_query($conn,$query);

            $email_query="select email from user where email='$email' limit 1";
            $email_result = mysqli_query($conn,$email_query);

            $password_query="select email from user where password='$pass' limit 1";
            $password_result = mysqli_query($conn,$email_query);

            if(mysqli_num_rows($result)==1){
                $_SESSION['email']=$email;
                header("location: index.php");
            }

            if(mysqli_num_rows($email_result)!=1){
              $emailError = "Invalid email address.";
            }
            if(mysqli_num_rows($password_result)!=1){
              $passwordError = "Invalid password.";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thesis Project - Login Page</title>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="includes/fonts/icomoon/style.css">
    <link rel="stylesheet" href="includes/CSS/owl.carousel.min.css">
    <link rel="stylesheet" href="includes/CSS/bootstrap.min.css">
    <link rel="stylesheet" href="includes/CSS/home_navBar.css">
    <link rel="stylesheet" href="includes/CSS/app.css">
    <link rel="stylesheet" href="includes/CSS/form.css">
</head>

<body>
    <header class="header" style="margin-top: 5px;">
        <div class="container-fluid">
            <div class="row text-muted">
                <div class="col-6 text-start">
                    <p class="mb-0">
                        <a class="text-muted" href="./index.php"><strong style="font-size: 24px;">Archive</strong></a>
                    </p>
                </div>
                <div class="col-6 text-end">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a class="text-muted" href="#" target="_blank">Help Center</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted" href="#" target="_blank">Terms and Condition</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted" href="#" target="_blank">About Us </a>
                        </li>

                        <li class="list-inline-item">
                            <a href="./registration.php">
                                <input type="submit" name="registrationbtn" style="border-radius: 50px;" value="Sign Up">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <section id="log-in">
        <div class="log-oin-page">
            <form class="log-in-form" action="" method="post">
                <h1>Log in</h1>

                <label for="email">Teacher’s Institution Email</label><br>
                <input type="email" name="email" value="<?php echo isset($email) ? $email:"" ?>"><br>
                <span style="color: red; padding-left: 5px"><?php echo "$emailError"; ?></span><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" value="<?php echo isset($password) ? $password:"" ?>"><br>
                <span style="color: red; padding-left: 5px"><?php echo "$passwordError"; ?></span><br>

                <div class="forgot">
                    <span><a href="forgot_password.php" style="color: black;"> Forgot Password?</a></span>
                </div>
                <div class="submit_button">
                    <input type="submit" name="submit" class="button" value="Log In">
                </div>
                <div class="text">
                    <p style="color: black;">Don't have an account?<span><a href="registration.php"> Sign Up</a></span></p>
                </div>
            </form>
        </div>
    </section>


    <script src="includes/js/jquery-3.3.1.min.js"></script>
    <script src="includes/js/popper.min.js"></script>
    <script src="includes/js/bootstrap.min.js"></script>
    <script src="includes/js/jquery.sticky.js"></script>
    <script src="includes/js/main.js"></script>

</body>

</html>