<?php
    include('classes/CMS.php');

    $dir = explode('\\', dirname(__FILE__));
    $curr_dir = $dir[count($dir)-1];

    $cms = new CMS;
    $admin;
    $user;
    
    if(isset($_SESSION['admin_id'])) {
        $cms->data = array(
            ':admin_id' => $_SESSION['admin_id'],
        );

        $cms->query = 'SELECT * FROM admin_table WHERE id = :admin_id';

        $result = $cms->result();
        $admin = $result[0];
    }
    
    if(isset($_SESSION['user_id'])) {
        $cms->data = array(
            ':user_id' => $_SESSION['user_id'],
        );

        $cms->query = 'SELECT * FROM users_table WHERE id = :user_id';

        $result = $cms->result();
        $user = $result[0];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CMS</title>

    <!-- Bootswatch - Flattly -->
    <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Bootstrap js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/f9d2d5cb9c.js" crossorigin="anonymous"></script>

    <!-- Custom css -->
    <link rel="stylesheet" href="./styles/styles.css">
</head>
<body>
    <!-- header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand brand-font" href="index.php">CMS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="forum.php">Forum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php">Blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav mr-left">
                    <?php 
                        if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>      
                            ';
                        }
                        else if(isset($_SESSION['user_id'])){
                            echo '
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle mr-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                        <img src="'.$user['user_image'].'" alt="user_image">
                                        <span id="username">'.$user['username'].'</span>
                                    </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Profile</a>
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </li>
                            ';
                        }
                        else if(isset($_SESSION['admin_id'])) {
                            $img = $curr_dir !== 'admin' ? '.' . $admin['admin_image'] : '..' . $admin['admin_image'];
                            echo '
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle mr-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                        <img src="'.$img.'" alt="admin_image">
                                        <span id="username">'.$admin['admin_username'].'</span>
                                    </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Profile</a>
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </li>
                            ';
                        }
                    ?>
                </ul>
            </div>
        </nav>
    </header>
    <!-- !header -->
    <?php echo $_SESSION['admin_id']; ?>
    <!-- main -->
    <main>
        <!-- container -->
        <div class="container mt-5 pt-5 text-center">
