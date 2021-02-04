<?php
    include('./header.php');

    $cms->data = array(
        ':approved' => 0,
    );

    $cms->query = 'SELECT * FROM comments_table WHERE approved = :approved';

    $total_rows = $cms->total_rows();
?>
<div class="col-md-9">
    <div class="card">
        <div class="card-header">
            <h3>Blog Comment Requests</h3>
            <div id="message">
                <?php 
                    if(isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                ?>
            </div>
        </div>
        <div class="card-body">
            <?php
                if($total_rows > 0) {
                    $result = $cms->result();

                    echo '
                        <table class="table table-hover comment-requests">
                            <thead>
                                <tr>
                                    <th>Comment Author</th>
                                    <th>Blog Post Title</th>
                                    <th>Comment Body</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ';
                    
                    foreach($result as $row) {
                        $cms->data = array(
                            ':post_id' => $row['post_id']
                        );

                        $cms->query = 'SELECT post_title FROM blog_table WHERE id = :post_id';

                        $blogResult = $cms->result();
                        $blogResult = $blogResult[0];

                        echo '
                            <tr class="p-3">
                                <td><p>'.$row['comment_author'].'</p></td>
                                <td><p>'.$blogResult['post_title'].'</p></td>
                                <td>'.$row['comment_body'].'</td>
                                <td>
                                    <div id="buttonDiv">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-success mr-3 accept '.$row['id'].'">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="submit" class="btn btn-danger reject '.$row['id'].'">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        ';
                    }

                    echo '
                            </tbody>
                        </table>
                    ';
                }
                else {
                    echo '
                        <p>No comments have been added...</p>
                    ';
                }
            ?>
        </div>
    </div>
</div>
<!-- Custom js -->
<script>
    $(document).ready(() => {
        //Execute if accpet post button is clicked
        $('.accept').click((e) => {
            e.preventDefault();
            
            let message = $('#message');
            
            let button = $(e.currentTarget);
            let id = button.attr('class');
            id = id[id.length-1];

            $.ajax({
                url: "../ajax_action.php",
                method: "POST",
                data: {
                    page: 'blog_post',
                    action: 'accept',
                    id : id,
                },
                dataType: "json",
                beforeSend: () => {
                    button.attr('disabled', 'disabled');
                },
                success: (data) => {
                    if(data.success) location.reload();
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    console.log(xhr.status);
                    console.log(thrownError);
                    message.html('<div class="alert alert-danger">There was an error while trying to confirm the comment...</div>');
                },
            });
        });

        //Execute if reject post button is clicked
        $('.reject').click((e) => {
            e.preventDefault();

            let message = $('#message');
            
            let button = $(e.currentTarget);
            let id = button.attr('class');
            id = id[id.length-1];

            $.ajax({
                url: "../ajax_action.php",
                method: "POST",
                data: {
                    page: 'blog_post',
                    action: 'reject',
                    id : id,
                },
                dataType: "json",
                beforeSend: () => {
                    button.attr('disabled', 'disabled');
                },
                success: (data) => {
                    if(data.success) location.reload();
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    console.log(xhr.status);
                    console.log(thrownError);
                    message.html('<div class="alert alert-danger">There was an error while trying to reject the comment...</div>');
                },
            });
        });
    });
</script>
<?php
    include('../footer.php');
?>
