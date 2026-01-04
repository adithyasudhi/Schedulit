<!DOCTYPE html>
<html>
    <head>
        <title>coordinator's page</title>
    </head>
    <body>
        <div>
            <label>Coordinator's Page</label>
            <form method="POST" action="coordinator.php">
                <label>Select Semester and Batch to Manage Timetable</label>
                <select name="sem_list">
                    <option value="">Select Semester</option>
                    <?php
                        require_once "connection.php";
                        $selected_sem = isset($_POST['sem_list']) ? $_POST['sem_list'] : "";
                        
                        $query = " SELECT DISTINCT sem_id FROM semester ";
                        $res = mysqli_query($conn, $query);
                        if(!$res){
                            echo "Department Retrieval Failed: ". mysqli_error($conn);  
                        }
                        if(mysqli_num_rows($res) > 0){
                            while($row = mysqli_fetch_assoc($res)){
                                echo "<option value='{$row['sem_id']}'>{$row['sem_id']}</option>";
                            }
                        }
                    ?>
                </select>
                <select name = "batch_list">
                    <option value="">Select Batch</option>
                        <?php
                        if ($selected_sem != "") {
                            $selected_batch = isset($_POST['batch_list']) ? $_POST['batch_list'] : "";
                            $stmt = mysqli_prepare($conn, "SELECT DISTINCT sem_batch FROM semester WHERE sem_id = ?");
                            mysqli_stmt_bind_param($stmt, "s", $selected_sem);
                            mysqli_stmt_execute($stmt);
                            $res = mysqli_stmt_get_result($stmt);

                            while($row = mysqli_fetch_assoc($res)){
                                $is_selected = ($row['sem_batch'] == $selected_batch) ? "selected" : "";
                                echo "<option value='{$row['sem_batch']}' $is_selected>{$row['sem_batch']}</option>";
                            }
                        }
                    ?>
                </select>
                <input type = "submit" value = "sem-batch" name = "sem-batch" class = "subbtn inpcommon">

                
                <?php
                    if(isset($_POST['sem-batch'])) {
                        $sem_selected = $_POST['sem_list'];
                        $batch_selected = $_POST['batch_list'];
                        echo "<p>Managing Timetable for <br> Semester: <strong>$sem_selected</strong> Batch: <strong>$batch_selected</strong></p>";
                    }
                ?>
            </form>
        </div>
    </body>
</html>


    

