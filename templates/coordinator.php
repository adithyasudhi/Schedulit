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
                <select name="sem_list" onchange = "this.form.submit()">
                    <option value="">Select Semester</option>
                    <?php
                        require_once "connection.php";
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


<form method="POST" action="coordinator.php">
    <input type="text" name="course_id" placeholder="Course ID" id="course_id" required>
    <input type="text" name="course_name" placeholder="Course Name" id="course_name" required>
    <input type="number" name="course_l" placeholder="Course L" id="course_l" required>
    <input type="number" name="course_t" placeholder="Course T" id="course_t" required>
    <input type="number" name="course_p" placeholder="Course P" id="course_p" required>
    <input type="number" name="course_r" placeholder="Course R" id="course_r">
    <input type="submit" value="Add Course" name="add_course" class="subbtn inpcommon"> 
</form>


