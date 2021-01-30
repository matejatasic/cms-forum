<?php
    include('./header.php');
?>
<div class="row pb-3">
    <a href="index.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row my-5">
    <div class="col-md-12">
        <h2>Blog Posts</h2>
    </div>
    <?php
        if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            echo '
                <div class="col-md-12 mb-3">
                    <a href="add_blog.php" class="btn btn-primary float-right">Add post</a>
                </div>  
            ';
        }
    ?>
    <div class="col-md-12">
        <div class="container mt-2" id="posts-container">
            <div class="row">
                <?php
                    $cms->data = array(
                        ':approved' => 1,
                    );
                    $cms->query = 'SELECT * FROM blog_table WHERE approved = :approved';
                    $total_rows = $cms->total_rows();

                    if($total_rows === 0) {
                        echo '
                            <div class="col-md-4">
                                <p class="font-1">No posts have been added</p>
                            </div>
                        ';
                    }
                    else {
                        $result = $cms->result();

                        foreach($result as $row) {
                            $cms->data = array(
                                ':post_id' => $row['id'],
                                ':approved' => 1,
                            );

                            $cms->query = 'SELECT * FROM comments_table WHERE post_id = :post_id AND approved = :approved';

                            $comments = $cms->total_rows();

                            echo '
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header embed-responsive embed-responsive-16by9">
                                            <img src="'.$row['post_image'].'" alt="news_image" class="card-img-top embed-responsive-item">
                                        </div>
                                        <div class="card-body font-2 text-left">
                                            <h3><a href="blog_post.php?id='.$row['id'].'">'.$row['post_title'].'</a></h3>
                                            <p>'.$cms->shortenText($row['post_body'], 200).'</p>
                                            
                                            <div class="card-footer">
                                                <p>Post by '.$row['post_author'].'</p>
                                                <p>'.date('F d, Y', strtotime($row['created_on'])).'<span class="commentNum">'.$comments.'</span><i class="fas fa-comments"></i></p>
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
<?php
    include('./footer.php');
?>
