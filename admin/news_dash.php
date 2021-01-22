<?php
    include('./header.php');

    $cms->data = array(
        ':approved' => 0,
    );

    $cms->query = 'SELECT * FROM news_table WHERE approved = :approved';

    $total_rows = $cms->total_rows();
?>
<div class="col-md-9">
    <div class="card">
        <div class="card-header">
            <h3>News Post Requests</h3>
        </div>
        <div class="card-body">
            <?php
                if($total_rows > 0) {
                    $result = $cms->result();

                    echo '
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>News Title</th>
                                    <th>News Author</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ';
                    
                    foreach($result as $row) {
                        echo '
                            <tr>
                                <td>'.$row['news_title'].'</td>
                                <td>'.$row['news_author'].'</td>
                                <td class="d-flex justify-content-center">
                                    <form method="post" class="mr-3">
                                        <input type="hidden" name="page" value="news_dash">
                                        <input type="hidden" name="action" value="edit">
                                        <button class="btn btn-success edit">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="post">
                                        <input type="hidden" name="page" value="news_dash">
                                        <input type="hidden" name="page" value="delete">
                                        <button class="btn btn-danger edit">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
<?php
    include('../footer.php');
?>