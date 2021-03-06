<?php
    include('header.php');
?>
<!-- first row -->
<div class="row">
    <div class="col-md-12">
        <h1 class="text-center">Simple CMS</h1>
        <p class="font-1">Welcome to the simple Content Management System</p>
        <?php 
            if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
                echo '
                    <p class="font-1">Login if you have an account or register if you don\'t to be able to post content on our blog and forum pages</p>
                    <div class="d-flex justify-content-center">
                        <a href="login.php" class="btn btn-primary mr-3">Login</a>
                        <a href="register.php" class="btn btn-success">Register</a>
                    </div>
                ';
            }
            else {
                echo '
                    <p class="font-1">Create content on our blog and forum pages</p>
                ';
            }
        ?>
    </div>
</div>
<!-- !first row -->

<!-- second row -->
<div class="row my-5">
    <div class="col-md-12">
        <h2>News</h2>
    </div>
    <?php
        if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            echo '
                <div class="col-md-12 mb-3">
                    <a href="add_news.php" class="btn btn-primary float-right">Add news</a>
                </div>  
            ';
        }
    ?>
    <div class="col-md-12">
        <div class="container mt-2" id="news-container">
            <div class="row">
            <?php
                $cms->data = array(
                    ':approved' => 1,
                );
                $cms->query = 'SELECT * FROM news_table WHERE approved = :approved';
                $total_rows = $cms->total_rows();

                if($total_rows === 0) {
                    echo '
                        <div class="col-md-4">
                            <p class="font-1">No news have been added</p>
                        </div>
                    ';
                }
                else {
                    $result = $cms->result();

                    foreach($result as $row) {
                        $time = $cms->timePassed($row['created_on']);
                        $difference = $time[0];
                        $str_interval = $time[1] !== 'now' ? $difference . ' ' . $time[1] . ' ago' : 'now';
                        echo '
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header embed-responsive embed-responsive-16by9">
                                        <img src="'.$row['news_image'].'" alt="news_image" class="card-img-top embed-responsive-item">
                                    </div>
                                    <div class="card-body">
                                        <h3><a href="#">'.$row['news_title'].'</a></h3>
                                        <div class="mt-5 text-left font-2">
                                            <p>By '.$row['news_author'].', '.$str_interval.'</p>
                                            <p>0 comments &nbsp;  
                                                <span><i class="fas fa-star"></i></span>
                                                <span><i class="fas fa-star"></i></span>
                                                <span><i class="fas fa-star"></i></span>
                                                <span><i class="fas fa-star"></i></span>
                                                <span><i class="fas fa-star"></i></span> &nbsp;  
                                                79 views
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
            ?>
            </div>
        </div>
    </div>
</div>
<!-- !second row -->

<?php
    include('footer.php');
?>
