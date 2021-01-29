<?php
    include('./header.php');

    $cms->user_session();

    $id = $_GET['id'];
    $cat_id = $_GET['cat_id'];

    $cms->data = array(
        ':id' => $id,
        ':approved' => 1,
    );

    $cms->query = 'SELECT * FROM forum_topics WHERE id = :id AND approved = :approved';

    //Redirect the user if inserted wrong id in url or if topic is not confirmed by admin
    $total_rows = $cms->total_rows();
    if($total_rows === 0) {
        $cms->redirect("forum.php");
    }

    $result = $cms->result();

    $result = $result[0];
?>
<div class="row pb-3">
    <a href="category.php?id=<?php echo $cat_id; ?>" class="btn btn-primary float-left">Back</a>
</div>
<div class="row my-5">
    <div class="col-md-12">
        <h2>Topic</h2>
    </div>
    <?php
        if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            echo '
                <div class="col-md-12 mb-3">
                    <a href="add_post.php?cat_id='.$cat_id.'&id='.$id.'" class="btn btn-primary float-right">Add post</a>
                </div>  
            ';
        }
    ?>
    <div class="col-md-12">
        <div class="container-fluid mt-2">
            <div class="row">
                <table class="table table-primary topic-posts">
                    <thead>
                        <tr>
                            <th colspan="2"><?php echo $result['topic_title']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $cms->query = 'SELECT * FROM forum_posts';
                    
                        $result = $cms->result();

                        foreach($result as $row) {
                            echo '
                                <tr>
                                    <td class="post-user">
                                        <div>
                                            <p>'.$row['post_author'].'</p>
                                            <p>'.$row['created_on'].'</p>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <p>'.$row['post_content'].'</p>
                                    </td>
                                </tr>
                            ';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
    include('./footer.php');
?>