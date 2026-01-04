<!DOCTYPE html>
<html>
    <head>
        <title>coordinator's page</title>
    </head>
    <body>
        <div>
            <label>Coordinator's Page</label>
            <form method="POST" action="coordinator.php">
                <label>Select Semester and Batch</label>
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
                                $is_sem_selected = ($row['sem_id'] == $selected_sem) ? "selected" : "";
                                echo "<option value='{$row['sem_id']}' $is_sem_selected>{$row['sem_id']}</option>";
                            }
                        }
                    ?>
                </select>
                <input type="submit" name="load_batches" value="Load Batches" class="subbtn inpcommon" >
                <select name = "batch_list">
                <option value="">Select Batch</option>
                    <?php
                        require_once "connection.php";
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
            </form>
           
            <?php 
                require_once "connection.php";
                if (isset($_POST['add_course'])) {
                    $c_id = $_POST['course_id'];
                    $c_name = $_POST['course_name'];
                    $l = $_POST['course_l'];
                    $t = $_POST['course_t'];
                    $p = $_POST['course_p'];
                    $r = $_POST['course_r'];
                    if (!mysqli_ping($conn)) {
                        require "connection.php"; 
                    }
                    $query = "INSERT INTO course (course_id, course_name, L, T, P, R) VALUES ('$c_id', '$c_name', '$l', '$t', '$p', '$r');";
                    $res = mysqli_query($conn, $query);
                    if(!$res) {
                        header("Location: coordinator.php?status=success");  
                        exit();
                    } else {
                        $error_msg = mysqli_error($conn);
                        echo "<script>alert('Course insertion failed: $error_msg');</script>";
                    }
                }
                if(isset($_POST['sem-batch']) || isset($_POST['add_course_form'])) {
                    $sem_selected = $_POST['sem_list'] ?? $_POST['selected_sem'];
                    $batch_selected = $_POST['batch_list'] ?? $_POST['selected_batch'];
                    
                    echo "<p>Managing Timetable for <br> Semester: <strong>$sem_selected</strong> Batch: <strong>$batch_selected</strong></p>";
                if (!isset($_POST['add_course_form'])) {
            ?>
                    <form method="POST" action="">
                        <label>Add Course</label><br>
                        <input type="hidden" name="selected_sem" value="<?php echo $sem_selected; ?>">
                        <input type="hidden" name="selected_batch" value="<?php echo $batch_selected; ?>">
                        
                        <input type="submit" name="add_course_form" value="Load Course Form" class="subbtn inpcommon">
                    </form>
                <?php
                require_once "connection.php";
                }
                }
            if (isset($_POST['add_course_form'])) {
                $sem_selected = $_POST['selected_sem'];
                $batch_selected = $_POST['selected_batch'];
                ?>
                    <form method="POST" action="">
                        <input type="hidden" name="selected_batch" value="<?php echo $batch_selected; ?>">
                        <input type="hidden" name="selected_sem" value="<?php echo $sem_selected; ?>">

                        <input type="text" name="course_id" placeholder="Course ID" required>
                        <input type="text" name="course_name" placeholder="Course Name" required>
                        <input type="number" name="course_l" placeholder="L" title="Lecture" required>
                        <input type="number" name="course_t" placeholder="T" title="Tutorial" required>
                        <input type="number" name="course_p" placeholder="P" title="Practical" required>
                        <input type="number" name="course_r" placeholder="R" title="Research">
        
                        <input type="submit" value="Add Course" name="add_course" class="subbtn inpcommon">
                    </form>
                <?php
            }       
                ?>
    </body>
</html>


    

