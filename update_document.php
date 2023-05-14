<?php

    session_start();
    $conn = mysqli_connect('localhost', 'root', '', 'archive');

    function decrypt($encryptedValue, $key) {
        $cipher = "AES-256-CBC";
        $encryptedValue = base64_decode($encryptedValue);
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = substr($encryptedValue, 0, $ivLength);
        $encryptedValue = substr($encryptedValue, $ivLength);
        $decryptedValue = openssl_decrypt($encryptedValue, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return $decryptedValue;
    }

    $titleError = "";
    $authorError = "";
    $supervisorError = "";
    $dateError = "";
    $typeError = "";
    $fileError = "";
    $new_file_name = "";
    $fileType = "";
    $read_title = "";
    $read_author1 = "";
    $read_author2 = "";
    $read_author3 = "";
    $read_supervisor = "";
    $read_year = "";
    $read_type = "";
    $read_file = "";
    $ID = "";

    $encryptedID = $_GET['id'];
    $ID = decrypt(urldecode($encryptedID), 'crypto'); 

    if(empty($ID)){
        $_SESSION['msg'] = 'Something Went Wrong. Please try again click update document.';
        $_SESSION['read_year'] =  $_SESSION['year'];
        $_SESSION['read_type'] =  $_SESSION['type'];
        header("location: index.php");
    }else{
        $_SESSION['msg'] = '';
    }

    $document_query = "SELECT * FROM `document` WHERE documentID = '$ID'";
    $document_result = mysqli_query($conn,$document_query);
    $row = mysqli_fetch_array($document_result);
    if (is_null($row)) {
        $read_title = "";
        $read_author1 = "";
        $read_author2 = "";
        $read_author3 = "";
        $read_supervisor = "";
        $read_year = "";
        $read_type = "";
        $read_file = "";
    }else{
        $read_title = $row['title'];
        $read_author1 = $row['author1'];
        $read_author2 = $row['author2'];
        $read_author3 = $row['author3'];
        $read_supervisor = $row['supervisor'];
        $read_year = $row['year'];
        $read_type = $row['type'];
        $read_file = $row['file'];

        $_SESSION['read_year'] =  $read_year;
        $_SESSION['read_type'] =  $read_type;
    }


 
    if(isset($_POST['submit'])){

        $title = $_POST['title'];
        $author1 = $_POST['author1'];
        $author2 = $_POST['author2'];
        $author3 = $_POST['author3'];
        $supervisor = $_POST['supervisor'];
        $date = $_POST['year'];
        $type = $_POST['document_type'];


        $target_dir = "upload/".$type."/";

        if(empty($_FILES['files']['name'])){
            $filename = $read_file;
        }else{
            $filename = $target_dir.$_FILES['files']['name'];
        }

        $temp_file_name = $_FILES['files']['tmp_name'];
        $file=$target_dir.$_FILES['files']['name'];
        $fileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

        
        if(empty($title)){
            $titleError = "Title is required.";
        }else if(empty($author1)){
            $authorError =  "1st author is required.";
        }else if(empty($supervisor)){
            $supervisorError =  "Supervisor is required.";
        }else if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $supervisor)){
            $supervisorError = "Supervisor Name does not contain special characters.";
        }else if(preg_match('/\.{2,}/', $supervisor)){
            $supervisorError = "Supervisor Name does not have more than two dots.";
        }else if(empty($date)){
            $dateError =  "Year is not selected.";
        }else if(empty($type)){
            $typeError =  "Document type is not selected.";
        }else if ($fileType != "pdf" && !empty($_FILES['files']['name'])){
            $fileError =  "This is not a PDF file.";
        }else{
            $fileError =  "";
        }

        if(!empty($title) && !empty($author1) && !empty($supervisor) && !empty($date) && !empty($type) && !empty($file) && empty($fileError)){

            $new_file_name = $target_dir . $title.".pdf";
            if(!empty($_FILES['files']['name'])){
                if($read_type!=$type){
                    unlink($read_file);
                }

                move_uploaded_file($temp_file_name, $filename);

                if($filename!=$new_file_name){
                    rename($filename, $new_file_name);
                }
            }else{
                if($read_type!=$type){
                    if(copy($read_file, $target_dir.$title.'.pdf')){
                        unlink($read_file);
                    }
                }
            }

            $update_query  = "UPDATE `document` SET `title`='$title', `author1`='$author1', `author2`='$author2', `author3`='$author3', `supervisor`='$supervisor', `year`='$date', `type`='$type', `file`='$new_file_name' WHERE `documentID`='$ID'";
            $update_result = mysqli_query($conn, $update_query);

            if($update_result){
                $_SESSION['msg'] = '';
                header('location:index.php');
            }else{
                die("Not Inserted".mysqli_error($conn));
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
    <title>Thesis Project Archive - Update Document</title>
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
                            <a href="./index.php">
                                <input type="submit" name="backbtn" style="border-radius: 50px;" value="Back">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <section id="sign-up">
        <div class="sign-up-page">
            <form class="sign-up-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" style="padding-top: 25%;">
                <h1>Update Document</h1>

                <div>
                    <label for="title">Title</label><br>
                    <input type="text" name="title" value="<?php echo $read_title ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$titleError"; ?></span><br>

                    <label for="author1">1st Author</label><br>
                    <input type="text" name="author1" value="<?php echo $read_author1 ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$authorError"; ?></span><br>

                    <label for="author2">2nd Author(optional)</label><br>
                    <input type="text" name="author2" value="<?php echo $read_author2 ?>"><br>

                    <label for="author3">3rd Author(optional)</label><br>
                    <input type="text" name="author3" value="<?php echo $read_author3 ?>"><br>

                    <label for="supervisor">Supervisor</label><br>
                    <input type="name" name="supervisor" value="<?php echo $read_supervisor ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$supervisorError"; ?></span><br>

                    <label style="width: 150px;" for="year">Year</label>
                    <label style="width: 140px;" for="document_type">Document Type</label><br>

                    <select class="document_type" name="year" id="year">
                        <?php
                        $current_year = date('Y');

                        if($read_year==$current_year){?>

                            <option value="<?php echo $read_year ?>" <?php if($read_year == $read_year) echo " selected"; ?> ><?php echo $read_year ?></option>

                            <?php for ($year = $current_year-1; $year >= 2012; $year--) {
                                echo '<option value="' . $year . '">' . $year . '</option>';
                            }
                        }else if($read_year==2012){
                            for ($year = $current_year; $year >= 2013; $year--) {
                                echo '<option value="' . $year . '">' . $year . '</option>';
                            }?>

                            <option value="<?php echo $read_year ?>" <?php if($read_year == $read_year) echo " selected"; ?> ><?php echo $read_year ?></option>

                            <?php
                        }else{
                            for ($year = $current_year; $year >= $read_year+1; $year--) {
                                echo '<option value="' . $year . '">' . $year . '</option>';
                            }?>

                            <option value="<?php echo $read_year ?>" <?php if($read_year == $read_year) echo " selected"; ?> ><?php echo $read_year ?></option>

                            <?php for ($year = $read_year-1; $year >= 2012; $year--) {
                                echo '<option value="' . $year . '">' . $year . '</option>';
                            }
                        }?>

                    </select>


                    <select class="document_type" name="document_type" id="document_type" value="<?php echo $read_type ?>">
                        <option value="Project" <?php if($read_type == "Project") echo " selected"; ?> >Project</option>
                        <option value="Thesis" <?php if($read_type == "Thesis") echo " selected"; ?> >Thesis</option>
                    </select><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$dateError"; ?></span>
                    <span style="color: red; width: 140px; padding-left: 5px"><?php echo "$typeError"; ?></span><br>

                    <label for="files">Choose a file</label><br>
                    <input type="file" name="files" value="<?php echo $read_file ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$fileError"; ?></span><br>

                </div>

                <div style="padding-top: 20px; padding-bottom: 50px;">
                    <input style="width: 300px;" type="submit" name="submit" class="button" value="Save">
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