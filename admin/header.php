<?php
    include('../classes/CMS.php');

    $cms = new CMS;

    $cms->admin_session();

    $cms->data = array(
        ':admin_id' => $_SESSION['admin_id'],
    );

    $cms->query = 'SELECT * FROM admin_table WHERE id = :admin_id';

    $result = $cms->result();
    $result = $result[0];
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
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <!-- header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand brand-font" href="../index.php">CMS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../forum.php">Forum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../blog.php">Blog</a>
                    </li>
                    <li>
                        <a class="nav-link" href="news_dash.php">Dashboard</a>
                    </li>
                </ul>
                <ul class="navbar-nav mr-left">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle mr-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo '..' . htmlspecialchars($result['admin_image']); ?>" alt="admin_image">
                            <span id="username"><?php echo htmlspecialchars($result['admin_username']); ?></span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- !header -->
    
    <!-- main -->
    <main>
        <!-- container -->
        <div class="container-fluid mt-5 pt-5 text-center">
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Admin Dashboard</div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="news_dash.php" class="list-group-item list-group-item-action active">News</a>
                                <a href="blog_dash.php" class="list-group-item list-group-item-action active">Posts</a>
                                <a href="topic_dash.php" class="list-group-item list-group-item-action active">Topics</a>
                                <a href="comments_dash.php" class="list-group-item list-group-item-action active">Comments</a>
                            </div>
                        </div>
                    </div>
                </div>
            
