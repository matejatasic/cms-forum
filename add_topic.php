<?php
    include('./header.php');

    $cms->user_session();

    $id = $_GET['id'];
?>
<div class="row pb-3">
    <a href="category.php?id=<?php echo $id; ?>" class="btn btn-primary float-left">Back</a>
</div>
<div class="row mb-5">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3>Add Topic</h3>
            </div>
            <div class="card-body">
                <div id="message"></div>
                <form method="post" id="topic_form">
                    <div class="form-group">
                        <label class="float-left">Topic Title</label>
                        <input type="text" name="topic_title" id="topic_title" class="form-control" placeholder="Enter Topic Title">
                    </div>
                    <div class="form-group">
                        <label class="float-left">Post Text</label>
                        <textarea class="form-control" name="post_content" id="post_content" rows="3" placeholder="Enter Post Text"></textarea>
                    </div>
                    <div class="form-group clearfix mt-5">
                        <input type="hidden" name="page" value="add_topic">
                        <input type="hidden" name="action" value="add_topic">
                        <input type="hidden" name="category_id" value="<?php echo $id;?>">
                        <input type="submit" name="add_topic" id="add_topic" class="float-left btn btn-info" value="Add">
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

        $('#topic_form').submit((e) => {
            e.preventDefault();

            let form = $('#topic_form').get(0);
            let topic_title = $('#topic_title').val();
            let post_content = $('#post_content').val();
            
            //Check if all fields are valid
            validate.isEmpty(topic_title, post_content);
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
                        $('#add_topic').attr('disabled', 'disabled');
                        $('#add_topic').val('Please wait...');
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
                        $('#add_topic').attr('disabled', false);
                        $('#add_topic').val('Add');
                    },
                    error: (xhr, ajaxOptions, thrownError) => {
                        console.log(xhr.status);
                        console.log(thrownError);
                        message.html('<div class="alert alert-danger">There was an error while trying to add the topic...</div>');
                    },
                });
            }
        });
    });
</script>
<?php
    include('./footer.php');
?>
