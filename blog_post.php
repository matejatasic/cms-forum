<?php
    include('./header.php');

    $id = $_GET['id'];

    $cms->data = array(
        ':id' => $id,
    );

    $cms->query = 'SELECT * FROM blog_table WHERE id = :id';

    $total_rows = $cms->total_rows();

    //Redirect user if inserted wrong id in the url
    if($total_rows === 0) {
        $cms->redirect('index.php');
    }

    $result = $cms->result();

    $result = $result[0];
?>
<div class="row pb-3">
    <a href="index.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row my-5">
    <div class="col-md-12">
        <h2><?php echo $result['post_title']; ?></h2>
    </div>
    <div class="col-md-12">
        <div class="container mt-2 p-5" id="posts-container">
            <div class="row">
                <img src="<?php echo $result['post_image']; ?>" alt="post_image" class="post_image mb-3">
                <p class="font-2"><?php echo nl2br($result['post_body']); ?></p>
                <p class="mx-auto mt-3 font-2">Post by <?php echo $result['post_author'] . ', ' . $result['created_on']; ?></p>
                
                <!-- comments -->
                <div class="col-md-12">
                    <div class="col-md-12 mb-3">
                        <h3>Comments</h3>
                        <?php
                            $cms->data = array(
                                ':post_id' => $id,
                                ':approved' => 1, 
                            );

                            $cms->query = 'SELECT * FROM comments_table WHERE post_id = :post_id AND approved = :approved';

                            $total_rows = $cms->total_rows();

                            if($total_rows === 0) {
                                echo '
                                    <div class="comment-card p-3 font-2">
                                        <p>No comments have been added or the submitted comments have not been confirmed by the admin team</p>
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
                                    <div class="comment-card p-3 font-2">
                                        <p class="text-center">'.$row['comment_author'].'</p>
                                        <p class="text-center mt-1">'.$str_interval.'</p>
                                        <p class="mt-1">'.$row['comment_body'].'</p>
                                    </div> 
                                    ';
                                }
                            }
                        ?>
                    </div>
                    <?php
                        if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
                            echo '
                            <form method="post" id="comment_form">
                                <div id="message"></div>
                                <div class="form-group">
                                    <h4 class="float-left">Add a comment</h4>
                                    <textarea class="form-control" name="comment_body" id="comment_body" rows="3" placeholder="Enter a comment"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="page" value="blog_post">
                                    <input type="hidden" name="action" value="add_comment">
                                    <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                                    <input type="submit" name="add_comment" id="add_comment" class="float-left btn btn-info" value="Add">
                                </div>
                            </form>
                            ';
                        }
                    ?>
                </div>
                <!-- !comments -->
            </div>
        </div>
    </div>
</div>
<script src="./classes/Validate.js"></script>
<script>
    $(document).ready(() => {
        let validate = new Validate();
        let message = $('#message');

        $('#comment_form').submit((e) => {
            e.preventDefault();

            let form = $('#comment_form').get(0);
            let comment_body = $('#comment_body').val();
            
            //Check if all fields are valid
            validate.isEmpty(comment_body);
            validate.showErrors(message);
            
            //If all fields are valid, send the data to the ajax_action.php
            if(validate.isValid) {
                $.ajax({
                    url: "ajax_action.php",
                    method: "POST",
                    data: new FormData(form),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: () => {
                        $('#add_comment').attr('disabled', 'disabled');
                        $('#add_comment').val('Please wait...');
                    },
                    success: (data) => {
                        if(data.success) {
                            validate.showSuccess(message, data.success);
                        }
                        else {
                            if(typeof(data.error) === 'string') {
                                message.html('<div class="alert alert-danger">'+data.error+'</div>');
                            }
                            else {
                                validate.errors = data.error;
                                validate.showErrors(message);
                            }
                        }
                        $('#add_comment').attr('disabled', false);
                        $('#add_comment').val('Add');
                    },
                    error: (xhr, ajaxOptions, thrownError) => {
                        console.log(xhr.status);
                        console.log(thrownError);
                        message.html('<div class="alert alert-danger">There was an error while trying to add a comment...</div>');
                    },
                });
            }
        });
    });
</script>
<?php
    include('./footer.php');
?>
