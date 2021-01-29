<?php
    include('./header.php');

    $id = $_GET['id'];

    $cms->data = array(
        ':id' => $id,
    );

    $cms->query = 'SELECT * FROM news_table WHERE id = :id';

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
        <h2><?php echo $result['news_title']; ?></h2>
    </div>
    <div class="col-md-12">
        <div class="container mt-2 p-5" id="posts-container">
            <div class="row">
                <img src="<?php echo $result['news_image']; ?>" alt="news_image" class="news_image mb-3">
                <p class="font-2"><?php echo nl2br($result['news_body']); ?></p>
                <p class="mx-auto mt-3 font-2">News by <?php echo $result['news_author'] . ', ' . $result['created_on']; ?></p>
            </div>
        </div>
    </div>
</div>
<?php
    include('./footer.php');
?>
