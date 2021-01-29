<?php
    include('./header.php');

    $cms->user_session();

    $id = $_GET['id'];

    $cms->data = array(
        ':id' => $id,
    );

    $cms->query = 'SELECT cat_title FROM forum_categories WHERE id = :id';

    $total_rows = $cms->total_rows();

    //Redirect the user if inserted wrong id in the url
    if($total_rows === 0) {
        $cms->redirect('forum.php');
    }

    $category_name = $cms->result();
?>
<div class="row pb-3">
    <a href="forum.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row my-5">
    <div class="col-md-12">
        <h2>Topics</h2>
    </div>
    <div class="col-md-12">
        <h3>Topics in '<?php echo $category_name[0][0]; ?>' category</h3>
    </div>
    <?php
        if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            echo '
                <div class="col-md-12 mb-3">
                    <a href="add_topic.php?id='.$id.'" class="btn btn-primary float-right">Add topic</a>
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
                            <th>Topic Title</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $cms->data = array(
                            ':approved' => 1,
                        );
                        
                        $cms->query = 'SELECT * FROM forum_topics WHERE approved = :approved';
                        
                        $total_rows = $cms->total_rows();
                        
                        if($total_rows === 0) {
                            echo '
                                <tr>
                                    <td colspan="3">No topics have been added or the submitted topics have not been confirmed by the admin</td>
                                </tr>
                            ';
                        }
                        else {
                            $result = $cms->result();
    
                            foreach($result as $row) {
                                echo '
                                    <tr class="t-row" id="'.$row['id'].'">
                                        <td>
                                            '.$cms->shortenText($row['topic_title'], 50).'
                                        </td>
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
<script>
$(document).ready(() => {
        $('.t-row').click((e) => {
            let id = $(e.target).parent().attr('id');
            
            window.location.replace('topic.php?cat_id=<?php echo $id; ?>&id='+id);
        })
    });
</script>
<?php
    include('./footer.php');
?>
