<?php
    include('./header.php');

    $cms->data = array(
        ':approved' => 0,
    );

    $cms->query = 'SELECT * FROM blog_table WHERE approved = :approved';

    $total_rows = $cms->total_rows();
?>
<div class="col-md-9">
    <div class="card">
        <div class="card-header">
            <h3>Blog Post Requests</h3>
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
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Post Title</th>
                                    <th>Post Author</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ';
                    
                    foreach($result as $row) {
                        echo '
                            <tr>
                                <td>'.$row['post_title'].'</td>
                                <td>'.$row['post_author'].'</td>
                                <td class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-success mr-3 accept '.$row['id'].'">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="submit" class="btn btn-danger reject '.$row['id'].'">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                        <p>No news addedd...</p>
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
                    page: 'add_blog',
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
                    message.html('<div class="alert alert-danger">There was an error while trying to confirm the post...</div>');
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
                    page: 'add_blog',
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
                    message.html('<div class="alert alert-danger">There was an error while trying to reject the post...</div>');
                },
            });
        });
    });
</script>
<?php
    include('../footer.php');
?>