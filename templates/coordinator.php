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
                        $query = " SELECT DISTINCT sem_id FROM semester ";
                        $res = mysqli_query($conn, $query);
                        if(mysqli_num_rows($res) > 0){
                            while($row = mysqli_fetch_assoc($res)){
                                echo "<option value='{$row['sem_id']}' $is_selected>{$row['sem_id']}</option>";
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
                                echo "<option value='{$row['sem_batch']}' $batch_selected>{$row['sem_batch']}</option>";
                            }
                        }
                    ?>
                </select>
                <input type = "submit" value = "sem-batch" name = "sem-batch" class = "subbtn inpcommon">
            </form>
        </div>
    </body>
</html>








