<?php
    include('./header.php');

    $cms->user_session();
?>
<div class="row pb-3">
    <a href="blog.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row mb-5">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3>Add Blog Post</h3>
            </div>
            <div class="card-body">
                <div id="message"></div>
                <form method="post" id="blog_form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="float-left">Post Title</label>
                        <input type="text" name="post_title" id="post_title" class="form-control" placeholder="Enter Post Title">
                    </div>
                    <div class="form-group">
                        <label class="float-left">Post Body</label>
                        <textarea class="form-control" name="post_body" id="post_body" rows="3" placeholder="Enter Post Text"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="float-left">Post Image</label>
                        <input type="file" class="form-control-file" name="post_image" id="post_image">
                        <small class="float-left form-text text-muted">Supported file extensions: gif, jpeg, jpg, png</small>
                    </div>
                    <div class="form-group clearfix mt-5">
                        <input type="hidden" name="page" value="add_blog">
                        <input type="hidden" name="action" value="add_blog">
                        <input type="submit" name="add_blog" id="add_blog" class="float-left btn btn-info" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="./classes/Validate.js"></script>
<script>
    $(document).ready(() => {
        let validate = new Validate();
        let message = $('#message');

        $('#blog_form').submit((e) => {
            e.preventDefault();

            let form = $('#blog_form').get(0);
            let post_title = $('#post_title').val();
            let post_body = $('#post_body').val();
            let post_image = $('#post_image').val();
            
            //Check if all fields are valid
            validate.isEmpty(post_title, post_body, post_image);
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
                        $('#add_blog').attr('disabled', 'disabled');
                        $('#add_blog').val('Please wait...');
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
                        $('#add_blog').attr('disabled', false);
                        $('#add_blog').val('Register');
                    },
                    error: (xhr, ajaxOptions, thrownError) => {
                        console.log(xhr.status);
                        console.log(thrownError);
                        message.html('<div class="alert alert-danger">There was an error while trying to add a post...</div>');
                    },
                });
            }
        });
    });
</script>
<?php
    include('./footer.php');
?>
