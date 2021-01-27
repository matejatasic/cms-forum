<?php
    include('./header.php');

    $cms->user_session();
?>
<div class="row pb-3">
    <a href="forum.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row mb-5">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3>Add Category</h3>
            </div>
            <div class="card-body">
                <div id="message"></div>
                <form method="post" id="category_form">
                    <div class="form-group">
                        <label class="float-left">Category Title</label>
                        <input type="text" name="category_title" id="category_title" class="form-control" placeholder="Enter Category Title">
                    </div>
                    <div class="form-group">
                        <label class="float-left">Category Description</label>
                        <textarea class="form-control" name="category_desc" id="category_desc" rows="3" placeholder="Enter Category Description"></textarea>
                    </div>
                    <div class="form-group clearfix mt-5">
                        <input type="hidden" name="page" value="add_category">
                        <input type="hidden" name="action" value="add_category">
                        <input type="submit" name="add_category" id="add_category" class="float-left btn btn-info" value="Add">
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

        $('#category_form').submit((e) => {
            e.preventDefault();

            let form = $('#category_form').get(0);
            let category_title = $('#category_title').val();
            let category_desc = $('#category_desc').val();
            
            //Check if all fields are valid
            validate.isEmpty(category_title, category_desc);
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
                        $('#add_category').attr('disabled', 'disabled');
                        $('#add_category').val('Please wait...');
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
                        $('#add_category').attr('disabled', false);
                        $('#add_category').val('Add');
                    },
                    error: (xhr, ajaxOptions, thrownError) => {
                        console.log(xhr.status);
                        console.log(thrownError);
                        message.html('<div class="alert alert-danger">There was an error while trying to add the category...</div>');
                    },
                });
            }
        });
    });
</script>
<?php
    include('./footer.php');
?>
