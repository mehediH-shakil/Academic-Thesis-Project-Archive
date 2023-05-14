<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'archive');
$email = "";
$date = date('Y');
$type = "Project";
$search = "";

if(isset($_SESSION['read_year'])){
    $year = $_SESSION['read_year'];
    $type = $_SESSION['read_type'];
}else if(isset($_SESSION['read_type'])){
    $year = $_SESSION['read_year'];
    $type = $_SESSION['read_type'];
}

if(isset($_SESSION['msg'])){
    $massage = $_SESSION['msg'];
}else{
    $massage = '';
}

if(isset($_POST['tagbtn'])){
    $date = $_POST['year'];
    $type = $_POST['type'];
    $massage = '';
}else if(isset($_POST['searchbtn'])){
    $search = $_POST['search'];
    $massage = '';
}

function encrypt($value, $key) {
    $cipher = "AES-256-CBC";
    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivLength);
    $encryptedValue = openssl_encrypt($value, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $encryptedValue = base64_encode($iv . $encryptedValue);
    return $encryptedValue;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thesis Project Archive</title>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="includes/fonts/icomoon/style.css">
    <link rel="stylesheet" href="includes/CSS/owl.carousel.min.css">
    <link rel="stylesheet" href="includes/CSS/bootstrap.min.css">
    <link rel="stylesheet" href="includes/CSS/home_navBar.css">
    <link rel="stylesheet" href="includes/CSS/app.css">
    <link rel="stylesheet" href="includes/CSS/archive.css">
</head>

<body>
<div align="center">
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

                        <?php
                        if (isset($_SESSION['email'])) {
                            $email = $_SESSION['email'];
                            $email_query = "SELECT * FROM user WHERE email='$email' LIMIT 1";
                            $email_result = mysqli_query($conn, $email_query);
                            $row = mysqli_fetch_array($email_result);
                            $name = $row['name']; ?>

                        <li class="list-inline-item">
                            <a class="text-muted" href="#"><?php echo $name; ?></a>
                        </li>

                        <li class="list-inline-item">
                            <input type="submit" name="logoutbtn" style="border-radius: 50px;" value="Log Out" onclick="logoutConfirmation()">
                        </li>

                        <?php
                        } else {
                            ?><li class="list-inline-item">
                                <a href="./login.php">
                                    <input type="submit" name="loginbtn" style="border-radius: 50px;" value="Log In">
                                </a>
                            </li><?php
                        } ?>

                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div align="center" class="inline-div">
        <form id="searchbox" class="searchbox" action=""  method="POST" >

            <!-- search -->
            <input id="search" type="text" placeholder="Search Here" name="search" class="searchbar">
            <input name="searchbtn" type="submit" id="submit" value="Search" class="searchbtn"><br>

            
            <!-- tag -->
            <select id="year" name="year" class="tag" str>
                <?php
                $current_year = date('Y');

                if($date==$current_year){?>

                    <option value="<?php echo $date ?>" <?php if($date == $date) echo " selected"; ?> ><?php echo $date ?></option>

                    <?php for ($year = $current_year-1; $year >= 2012; $year--) {
                        echo '<option value="' . $year . '">' . $year . '</option>';
                    }
                }else if($date==2012){
                    for ($year = $current_year; $year >= 2013; $year--) {
                        echo '<option value="' . $year . '">' . $year . '</option>';
                    }?>

                    <option value="<?php echo $date ?>" <?php if($date == $date) echo " selected"; ?> ><?php echo $date ?></option>

                    <?php
                }else{
                    for ($year = $current_year; $year >= $date+1; $year--) {
                        echo '<option value="' . $year . '">' . $year . '</option>';
                    }?>

                    <option value="<?php echo $date ?>" <?php if($date == $date) echo " selected"; ?> ><?php echo $date ?></option>

                    <?php for ($year = $date-1; $year >= 2012; $year--) {
                        echo '<option value="' . $year . '">' . $year . '</option>';
                    }
                }?>

            </select>

            <select id="type" name="type" class="tag">
                <option value="Project" <?php if($type == "Project") echo " selected"; ?> >Project</option>
                <option value="Thesis" <?php if($type == "Thesis") echo " selected"; ?> >Thesis</option>
            </select>

            <input type="submit" name = "tagbtn" class="tagbtn" value="Add Tag">

        </form>

    </div>


     
     <!-- document table -->

    <div align="center" style="align-items: stretch;">
        <h5 style="color: red;"><?php echo $massage ?></h5>

        <table width="90%" id="table4" cellspacing="0" cellpadding="3">

            <tbody>
                <tr>
                    <td align="left" class="tabletitle">
                        <?php
                        if(empty($search)){
                            echo $type?> Year: <?php echo $date;
                        }else{
                            ?>Search By Title: <?php echo $search;
                        }?>

                        <?php
                        if(!empty($email)){
                            ?><a style="color: white; font-weight: normal; font-size: small; padding-right: 10px;" href="./create_document.php">Add New Document</a>
                            <?php
                        }?>

                    </td>
                </tr>

                <?php

                if(empty($search)){

                $document_query = "SELECT * FROM `document` WHERE year = '$date' and type = '$type'";
                $document_result = mysqli_query($conn,$document_query);
                $num_rows = mysqli_num_rows($document_result);

                while($row = mysqli_fetch_array($document_result)) { 
                    $ID = $row['documentID'];
                    $title = $row['title'];
                    $author1 = $row['author1'];
                    $author2 = $row['author2'];
                    $author3 = $row['author3'];
                    $supervisor = $row['supervisor'];
                    $year = $row['year'];
                    $type = $row['type'];
                    $file = $row['file'];

                    $_SESSION['year'] = $year;
                    $_SESSION['type'] = $type;

                    if(!empty($author2)){
                        $author2 = ', '.$author2;
                    }
                    if(!empty($author3)){
                        $author3 = ', '.$author3;
                    }

                    ?><tr>
                        <td align="left" class="tabletext">
                            <b><?php echo $title?></b>
                            <a href="<?php echo $file ?>" download>Download</a>
                            <br>Author - <?php echo $author1.$author2.$author3?>

                            <?php
                            if(!empty($email)){

                                $encryptedID = encrypt($ID, 'crypto');
                                $_SESSION['id'] = $ID;
                                ?><a href="./update_document.php?id=<?php echo $encryptedID; ?>" id="counterLink"> Update Document</a>
                                <?php
                            }?>

                            <br>Supervisor - <?php echo $supervisor?>

                            <?php
                            if(!empty($email)){
                                ?><a style="color: red;" onclick="DeleteConfirmation()"> Delete</a>
                                <?php
                            }?>

                        </td>
                    </tr>
                    <?php
                }

                if($num_rows=='0'){
                    ?><tr>
                        <td align="left" class="tabletext">
                            <h5 align="center"> <?php echo $type.' year '.$date ?>  archive is empty.</h5>
                        </td>
                    </tr>
                    <?php
                    }
                }else{

                $document_query = "SELECT * FROM `document` WHERE title LIKE '%$search%'";
                $document_result = mysqli_query($conn,$document_query);
                $num_rows = mysqli_num_rows($document_result);

                while($row = mysqli_fetch_array($document_result)) { 
                    $ID = $row['documentID'];
                    $title = $row['title'];
                    $author1 = $row['author1'];
                    $author2 = $row['author2'];
                    $author3 = $row['author3'];
                    $supervisor = $row['supervisor'];
                    $year = $row['year'];
                    $type = $row['type'];
                    $file = $row['file'];

                    if(!empty($author2)){
                        $author2 = ', '.$author2;
                    }
                    if(!empty($author3)){
                        $author3 = ', '.$author3;
                    }

                    ?><tr>
                        <td align="left" class="tabletext">
                            <b><?php echo $title?></b> (<?php echo $type ?> year <?php echo $year ?>)
                            <a href="<?php echo $file ?>" download>Download</a>
                            <br>Author - <?php echo $author1.$author2.$author3?>

                            <?php
                            $_SESSION['id']=$ID;
                            if(!empty($email)){

                                $encryptedID = encrypt($ID, 'crypto');
                                ?><a href="./update_document.php?id=<?php echo $encryptedID; ?>" id="counterLink"> Update Document</a>
                                <?php
                            }?>

                            <br>Supervisor - <?php echo $supervisor?>

                            <?php
                            if(!empty($email)){
                                ?><a style="color: red;" onclick="DeleteConfirmation()"> Delete</a>
                                <?php
                            }?>

                        </td>
                    </tr>
                    <?php
                }

                if($num_rows=='0'){
                    ?><tr>
                        <td align="left" class="tabletext">
                            <h5 align="center"> <b><?php echo $search?></b> is not found.</h5>
                        </td>
                    </tr>
                    <?php
                    }

                }?>
            </tbody>
        </table>
    </div>  
</div>


    <script src="includes/js/jquery-3.3.1.min.js"></script>
    <script src="includes/js/popper.min.js"></script>
    <script src="includes/js/bootstrap.min.js"></script>
    <script src="includes/js/jquery.sticky.js"></script>
    <script src="includes/js/main.js"></script>


    <script>
        function logoutConfirmation() {
          if (confirm("Are you sure you want to log out?")) {
            window.location.href = "logout.php";
          }
        }

        function DeleteConfirmation() {
          if (confirm("Are you sure you are delete this document?")) {
            window.location.href = "delete.php";
          }
        }
    </script>
</body>

</html>