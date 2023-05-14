<?php

    $titleError = "";
    $authorError = "";
    $supervisorError = "";
    $dateError = "";
    $typeError = "";
    $fileError = "";

 
    if(isset($_POST['submit'])){

        $title = $_POST['title'];
        $author1 = $_POST['author1'];
        $author2 = $_POST['author2'];
        $author3 = $_POST['author3'];
        $supervisor = $_POST['supervisor'];
        $date = $_POST['year'];
        $type = $_POST['document_type'];



        $conn = mysqli_connect('localhost','root','','archive');

        $target_dir = "upload/".$type."/";
        $filename = $target_dir.$_FILES['files']['name'];
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
        }else if(empty($fileType)){
            $fileError =  "File is not uploaded.";
        }else if ($fileType != "pdf"){
            $fileError =  "This is not a PDF file.";
        }else{
            $fileError =  "";
        }

        if(!empty($title) && !empty($author1) && !empty($supervisor) && !empty($date) && !empty($type) && !empty($file) && empty($fileError)){


            move_uploaded_file($temp_file_name, $filename);

            $new_file_name = $target_dir . $title.".pdf";
            rename($filename, $new_file_name);


            $insert_query  = "INSERT INTO `document`(`title`, `author1`, `author2`, `author3`, `supervisor`, `year`, `type`, `file`) VALUES ('$title','$author1','$author2','$author3','$supervisor','$date','$type','$new_file_name')";
            $insert_result = mysqli_query($conn, $insert_query);

            if($insert_result){
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
    <title>Thesis Project Archive - Create Document</title>
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
                <h1>Create Document</h1>

                <div>
                    <label for="title">Title</label><br>
                    <input type="text" name="title" value="<?php echo isset($title) ? $title:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$titleError"; ?></span><br>

                    <label for="author1">1st Author</label><br>
                    <input type="text" name="author1" value="<?php echo isset($author1) ? $author1:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$authorError"; ?></span><br>

                    <label for="author2">2nd Author(optional)</label><br>
                    <input type="text" name="author2" value="<?php echo isset($author2) ? $author2:"" ?>"><br>

                    <label for="author3">3rd Author(optional)</label><br>
                    <input type="text" name="author3" value="<?php echo isset($author3) ? $author3:"" ?>"><br>

                    <label for="supervisor">Supervisor</label><br>
                    <input type="name" name="supervisor" value="<?php echo isset($supervisor) ? $supervisor:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$supervisorError"; ?></span><br>

                    <label style="width: 150px;" for="year">Year</label>
                    <label style="width: 140px;" for="document_type">Document Type</label><br>

                    <select class="document_type" name="year" id="year">
                        <?php
                        $current_year = date('Y');
                        for ($year = $current_year; $year >= 2012; $year--) {
                            echo '<option value="' . $year . '">' . $year . '</option>';
                        }?>
                    </select>


                    <select class="document_type" name="document_type" id="document_type">
                        <option value="Project">Project</option>
                        <option value="Thesis">Thesis</option>
                    </select><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$dateError"; ?></span>
                    <span style="color: red; width: 140px; padding-left: 5px"><?php echo "$typeError"; ?></span><br>

                    <label for="files">Choose a file</label><br>
                    <input type="file" name="files" value="<?php echo isset($file) ? $file:"" ?>"><br>
                    <span style="color: red; padding-left: 5px"><?php echo "$fileError"; ?></span><br>

                </div>

                <div style="padding-top: 20px; padding-bottom: 50px;">
                    <input style="width: 300px;" type="submit" name="submit" class="button" value="Create">
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