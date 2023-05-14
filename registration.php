<?php

    $nameError = "";
    $employeeIDError = "";
    $emailError = "";
    $passwordError = "";
    $confirmPasswordError = "";

 
    if(isset($_POST['submit'])){

        $name = $_POST['name'];
        $employeeID = $_POST['employeeID'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmpassword'];

        
        if($password!=$confirmPassword && !empty($confirmPassword)){
            $confirmPasswordError = "Password Don't Match.";
        }else if(empty($name)){
            $nameError = "Name is required.";
        }else if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $name)){
            $nameError = "Name does not contain special characters.";
        }else if(preg_match('/\.{2,}/', $name)){
            $nameError = "Name does not have more than two dots.";
        }else if(empty($employeeID)){
            $employeeIDError =  "Teacher's Employee ID is required.";
        }else if(strlen($employeeID) > 25){
            $employeeIDError =  "Teacher's Employee ID is less then 25.";
        }else if(empty($email)){
            $emailError =  "Email is required.";
        }else if(empty($password)){
            $passwordError =  "Password is required.";
        }else if (strlen($password) < 8) {
            $passwordError =  "Password must be at least 8 characters long.";
        }else if (!preg_match('/[A-Z]/', $password)) {
            $passwordError =  "Password must contain at least one uppercase letter.";
        }else if (!preg_match('/[a-z]/', $password)) {
            $passwordError =  "Password must contain at least one lowercase letter.";
        }else if (!preg_match('/\d/', $password)) {
            $passwordError =  "Password must contain at least one digit.";
        }else if (!preg_match('/[^a-zA-Z\d]/', $password)) {
            $passwordError =  "Password must contain at least one special character.";
        }else if(strlen($password) > 25){
            $passwordError =  "Password is less then 25.";
        }else{
            $passwordError =  "";
        }

        if(empty($confirmPassword) && empty($nameError)){
            $confirmPasswordError =  "Confirm Password is required.";
        }
        
        if(!empty($name) && !empty($email) && !empty($employeeID) && !empty($password) && !empty($confirmPassword) && empty($confirmPasswordError)){
            
            $conn = mysqli_connect('localhost','root','','archive');

            if(!$conn){
                die ("Not Connected.");
            }
            
            $q="SELECT * FROM user WHERE email='$email'";
            $r=mysqli_query($conn,$q);
            
            if(mysqli_num_rows($r)>0){
                $emailError = "This Email Already Exist!";
            }else{
                $password=md5($password);
                $query = "INSERT INTO user(name,employeeID,email,password) VALUES('$name','$employeeID','$email','$password')";

                $result = mysqli_query($conn,$query);

                if($result){
                    header('location:login.php');
                }else{
                    die("Not Inserted".mysqli_error($conn));
                }
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
    <title>Thesis Project Archive - Registration Page</title>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="includes/fonts/icomoon/style.css">
    <link rel="stylesheet" href="includes/CSS/owl.carousel.min.css">
    <link rel="stylesheet" href="includes/CSS/bootstrap.min.css">
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
                            <a href="./login.php">
                                <input type="submit" name="loginbtn" style="border-radius: 50px;" value="Log In">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <section id="sign-up">
        <div class="sign-up-page">
            <form class="sign-up-form" action="" method="post" style="padding-top: 15%;">
                <h1>Sign Up</h1>

                <div>
                    <label for="name">Name</label><br>
                    <input type="name" name="name" value="<?php echo isset($name) ? $name:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$nameError"; ?></span><br>

                    <label for="employeeID">Teacher’s Employee ID</label><br>
                    <input type="number" name="employeeID" value="<?php echo isset($employeeID) ? $employeeID:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$employeeIDError"; ?></span><br>

                    <label for="email">Teacher’s Institution Email</label><br>
                    <input type="email" name="email" value="<?php echo isset($email) ? $email:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$emailError"; ?></span><br>

                    <label for="password">Password</label><br>
                    <input type="password" name="password" value="<?php echo isset($password) ? $password:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$passwordError"; ?></span><br>

                    <label for="confirmpassword">Confirm Password</label><br>
                    <input type="password" name="confirmpassword" value="<?php echo isset($confirmPassword) ? $confirmPassword:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$confirmPasswordError"; ?></span><br>
                </div><br>

                <div class="submit_button">
                    <input type="submit" name="submit" class="button" value="Create">
                </div>

                <div class="text">
                    <p style="color: black;">Have an account?<span><a href="login.php"> Sign In</a></span></p>
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