<!DOCTYPE html>
<html>
    <head>
        <title>coordinator page</title>
    </head>
    <body>
        <div>
            <label>Coordinator Page</label>
            <form method="POST" action="coordinator.php">
                
                <label>Select Semester and Batch to Manage Timetable</label>
                <select name="sem_list" onchange = "this.form.submit()">
                    <option value="">Select Semester</option>
                    <?php
                        require_once "connection.php";
                        $selected_sem = "";
                        if (isset($_POST['sem_list'])) {
                            $selected_sem = $_POST['sem_list'];
                        }
                        $query = " SELECT DISTINCT sem_id FROM semester ";
                        $res = mysqli_query($conn, $query);
                        if(!$res){
                            $selected = ($selected_sem == $row['sem_id']) ? "selected" : "";
                            echo "Semester Retrieval Failed: ". mysqli_error($conn);  
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
                        $stmt = mysqli_prepare($conn, "SELECT DISTINCT sem_batch FROM semester WHERE sem_id = ?");
                        mysqli_stmt_bind_param($stmt, "s", $selected_sem);
                        mysqli_stmt_execute($stmt);
                        $res = mysqli_stmt_get_result($stmt);

                        while($row = mysqli_fetch_assoc($res)){
                            echo "<option value='{$row['sem_batch']}'>{$row['sem_batch']}</option>";
                        }
                    }
                ?>
                </select>
                <input type = "submit" value = "sem-batch" name = "sem-batch" class = "subbtn inpcommon">
            </form>
            <?php
                if (isset($_POST['submit_btn'])) {
                    echo "<h3>Selection Result:</h3>";
                    echo "You have selected Semester: <b>" . $selected_sem . "</b> and Batch: <b>" . $selected_batch . "</b>";
                } else {    
                    echo "<p>Please select both Semester and Batch.</p>";
                }
            ?>
        </div>
    </body>
</html>
<?php
    require_once "connection.php";
    
?>







