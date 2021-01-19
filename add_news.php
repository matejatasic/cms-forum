<?php
    include('header.php');
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
                        <textarea name="" id="" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="float-left">News Image</label>
                        <input type="file" class="form-control-file" name="user_image" id="user_image">
                        <small class="float-left form-text text-muted">Supported file extensions: gif, jpeg, jpg, png</small>
                    </div>
                    <div class="form-group clearfix mt-5">
                        <input type="hidden" name="page" value="register">
                        <input type="hidden" name="action" value="user_register">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <input type="submit" name="user_register" id="user_register" class="float-left btn btn-info" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    include('footer.php');
?>