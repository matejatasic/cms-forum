<?php
    include('./header.php');

    $cms->user_session();

    $id = $_GET['id'];
?>
<div class="row pb-3">
    <a href="forum.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row my-5">
    <div class="col-md-12">
        <h2>Topics</h2>
    </div>
    <?php
        if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            echo '
                <div class="col-md-12 mb-3">
                    <a href="add_topic.php" class="btn btn-primary float-right">Add topic</a>
                </div>  
            ';
        }
    ?>
    <div class="col-md-12">
        <div class="container-fluid mt-2">
            <div class="row">
                <table class="table table-primary">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $cms->query = 'SELECT * FROM forum_topics';
                        $total_rows = $cms->total_rows();

                        if($total_rows === 0) {
                            echo '
                                <tr>
                                    <td colspan="3">No topics have been added</td>
                                </tr>
                            ';
                        }
                        else {
                            $result = $cms->result();

                            foreach($result as $row) {
                                $time = $cms->timePassed($row['created_on']);
                                $difference = $time[0];
                                $str_interval = $time[1] !== 'now' ? $difference . ' ' . $time[1] . ' ago' : 'now';
                                echo '
                                    <tr class="t-row" id="'.$row['id'].'">
                                        <td>'.$row['cat_title'].'</td>
                                        <td>'.$cms->shortenText($row['cat_description'], 200).'</td>
                                        <td>'.$row['created_on'].'</td>
                                    </tr>
                                ';
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
    include('./footer.php');
?>