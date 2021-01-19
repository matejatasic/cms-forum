<?php
    include('header.php');

    $cms->user_session();
?>
<div class="row pb-3">
    <a href="index.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row mb-5">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3>Add News</h3>
            </div>
            <div class="card-body">
                <div id="message"></div>
                <form action="post" id="news_form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="float-left">News Title</label>
                        <input type="text" name="news_title" id="news_title" class="form-control" placeholder="Enter News Title">
                    </div>
                    <div class="form-group">
                        <label class="float-left">News Body</label>
                        <textarea class="form-control" name="news_title" id="news_title" rows="3" placeholder="Enter News Title"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="float-left">News Image</label>
                        <input type="file" class="form-control-file" name="news_image" id="news_image">
                        <small class="float-left form-text text-muted">Supported file extensions: gif, jpeg, jpg, png</small>
                    </div>
                    <div class="form-group clearfix mt-5">
                        <input type="hidden" name="page" value="register">
                        <input type="hidden" name="action" value="user_register">
                        <input type="submit" name="add_news" id="add_news" class="float-left btn btn-info" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="./classes/Validate.js"></script>
<script>
    $(document).ready(() => {
        $('#add_news').click((e) => {
            e.preventDefault();

            let news_title = $('#news_title').val();
            let news_body = $('#news_body').val();
            let news_image = $('#news_image');

            //Check if all fields are valid
            validate.isEmpty(news_title, news_body);
            validate.imageUploaded(image);
            validate.showErrors(message);  

            //If all fields are valid, send the data to the ajax_action.php
            if(validate.isValid) {
                $.ajax({
                    url: "ajax_action.php",
                    method: "POST",
                    data: $('#news_form').serialize(),
                    dataType: "json",
                    beforeSend: () => {
                        $('#add_news').attr('disabled', 'disabled');
                        $('#add_news').val('Please wait...');
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
                        $('#add_news').attr('disabled', false);
                        $('#add_news').val('Register');
                    },
                    error: (xhr, ajaxOptions, thrownError) => {
                        console.log(xhr.status);
                        console.log(thrownError);
                        message.html('<div class="alert alert-danger">There was an error while trying to register...</div>');
                    },
                });
            }
        });
    });
</script>
<?php
    include('footer.php');
?>
