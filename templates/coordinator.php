<?php
    session_start();
    require_once "connection.php";
    if (!isset($_SESSION['emp_id'])) {
        header("Location: login.php");
        exit();
    }

$selected_sem = "";
$selected_batch = "";

/* ---------------- LOAD DATA ---------------- */
if (isset($_POST['semester'])) {
    $_SESSION['selected_sem'] = $_POST['semester'];
}
if (isset($_SESSION['selected_sem'])) {
    $selected_sem = $_SESSION['selected_sem'];
}

if (isset($_POST['batch'])) {
    $_SESSION['selected_batch'] = $_POST['batch'];
}
if (isset($_SESSION['selected_batch'])) {
    $selected_batch = $_SESSION['selected_batch'];
}

/* ---------------- ADD COURSE ---------------- */
if (isset($_POST['add_course'])) {
    $course_id   = mysqli_real_escape_string($conn, $_POST['course_id']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_l    = mysqli_real_escape_string($conn, $_POST['course_l']);
    $course_t    = mysqli_real_escape_string($conn, $_POST['course_t']);
    $course_p    = mysqli_real_escape_string($conn, $_POST['course_p']);
    $course_r    = mysqli_real_escape_string($conn, $_POST['course_r']);

    $query = "INSERT INTO course 
              (course_id, course_name, course_l, course_t, course_p, course_r, sem_id)
              VALUES 
              ('$course_id', '$course_name', '$course_l', '$course_t', '$course_p', '$course_r', '$selected_sem')";

    mysqli_query($conn, $query);
}

/* ---------------- REMOVE COURSE ---------------- */
if (isset($_POST['remove_course'])) {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $query = "DELETE FROM course WHERE course_id = '$course_id'";
    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Coordinator's Page</title>
        <link rel="stylesheet" href="/schedulit/static/style.css">
    </head>
    
    <body>
        <nav class="navbar">
            <div class="nav-right">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
        <h2>Coordinator Page</h2>
        
        <div class="card2">
            <button type="button" onclick="semesterSelection()" class="subbtn inpcommon">Manage Courses</button>
            
            <div id="semester-selection" style="display: <?php echo ($selected_sem) ? 'block' : 'none'; ?>;">
                <form method="POST" action="coordinator.php">
                    <label>Select Semester:</label>
                    <select name="semester" class="text-inputs" required onchange="this.form.submit()">
                        <option value="">-- Select Semester --</option>
                        <?php
                            $query = "SELECT DISTINCT sem_id FROM semester";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = ($selected_sem == $row['sem_id']) ? "selected" : "";
                                echo "<option value='".$row['sem_id']."' $selected>".$row['sem_id']."</option>";
                            }
                        ?>
                    </select> 
                    <button type="submit" name="next" class="subbtn inpcommon">Next</button>
                </form>

                <?php if ($selected_sem) { ?>

                    <hr>
                    <h3>Add Course for <?php echo htmlspecialchars($selected_sem); ?></h3>
                    <form method="POST" action="coordinator.php" class="formcls">
                        <input type="text" name="course_id" placeholder="Course ID" class="text-inputs inpcommon" required><br><br>
                        <input type="text" name="course_name" placeholder="Course Name" class="text-inputs inpcommon" required><br><br>
                        <input type="number" name="course_l" placeholder="L" class="text-inputs inpcommon" required><br><br>
                        <input type="number" name="course_t" placeholder="T" class="text-inputs inpcommon" required><br><br>
                        <input type="number" name="course_p" placeholder="P" class="text-inputs inpcommon" required><br><br>
                        <input type="number" name="course_r" placeholder="R" class="text-inputs inpcommon"><br><br>
                        <input type="submit" value="Add Course" name="add_course" class="subbtn inpcommon">
                    </form>
                    
                    <br><hr>

                    <h3>Course List (<?php echo htmlspecialchars($selected_sem); ?>)</h3>
                    <table border="1" cellpadding="8" style="width:100%; text-align:left; border-collapse: collapse;">
                        <tr>
                            <th>Course ID</th>
                            <th>Course Name</th>
                            <th>L</th>
                            <th>T</th>
                            <th>P</th>
                            <th>R</th>
                        </tr>
                        <?php
                            $query = "SELECT * FROM course WHERE sem_id = '$selected_sem'";
                            $result = mysqli_query($conn, $query);
                            if(mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td>".$row['course_id']."</td>
                                        <td>".$row['course_name']."</td>
                                        <td>".$row['course_l']."</td>
                                        <td>".$row['course_t']."</td>
                                        <td>".$row['course_p']."</td>
                                        <td>".$row['course_r']."</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No courses added yet.</td></tr>";
                            }
                        ?>
                    </table>

                    <div style="margin-top: 20px; padding: 15px; border: 1px solid #ffcccc; background-color: #fff5f5;">
                        <h3>Remove a Course</h3>
                        <form method="POST" action="coordinator.php" onsubmit="return confirm('Are you sure you want to delete this course?');">
                            <label>Select Course ID:</label>
                            <select name="course_id" class="text-inputs" required style="width: auto;">
                                <option value="">-- Select Course --</option>
                                <?php
                                    $rm_res = mysqli_query($conn, "SELECT course_id, course_name FROM course WHERE sem_id = '$selected_sem'");
                                    while ($rm_row = mysqli_fetch_assoc($rm_res)) {
                                        echo "<option value='".$rm_row['course_id']."'>".$rm_row['course_id']." - ".$rm_row['course_name']."</option>";
                                    }
                                ?>
                            </select>
                            <input type="submit" name="remove_course" value="Remove Course" class="logout-btn" style="background-color: #d9534f; border:none; padding: 10px; cursor:pointer;">
                        </form>
                    </div>

                <?php } ?>
            </div>

            <br>
            <button type="button" onclick="showTimetable()" class="subbtn inpcommon">View Timetable</button>
            <div id="timetable_area" style="display: none;">
                <form method="POST" action="coordinator.php">
                    <h3>Select Semester to View Timetable</h3>
                    <select name="tt_sem" class="text-inputs inpcommon">
                        <option value="">Select Semester</option>
                        <?php
                            $selected_tt_sem = $_POST['tt_sem'] ?? "";
                            $query = "SELECT DISTINCT sem_id FROM semester";
                            $res = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_assoc($res)){
                                $sel = ($row['sem_id'] == $selected_tt_sem) ? "selected" : "";
                                echo "<option value='{$row['sem_id']}' $sel>{$row['sem_id']}</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" name="load_tt_batches" value="Load Batches" class="subbtn inpcommon">
                    
                    <?php if (isset($_POST['load_tt_batches']) || isset($_POST['view_tt'])) { 
                        echo "<script>document.getElementById('timetable_area').style.display = 'block';</script>";
                    ?>
                        <select name="tt_batch" class="text-inputs inpcommon">
                            <option value="">Select Batch</option>
                            <?php
                                if (!empty($selected_tt_sem)) {
                                    $selected_tt_batch = $_POST['tt_batch'] ?? "";
                                    $stmt = mysqli_prepare($conn,"SELECT DISTINCT sem_batch FROM semester WHERE sem_id = ?");
                                    mysqli_stmt_bind_param($stmt, "s", $selected_tt_sem);
                                    mysqli_stmt_execute($stmt);
                                    $res = mysqli_stmt_get_result($stmt);
                                    while($row = mysqli_fetch_assoc($res)){
                                        $sel = ($row['sem_batch'] == $selected_tt_batch) ? "selected" : "";
                                        echo "<option value='{$row['sem_batch']}' $sel>{$row['sem_batch']}</option>";
                                    }
                                }
                            ?>
                        </select>
                        <input type="submit" name="view_tt" value="View" class="subbtn inpcommon">
                    <?php } ?>
                </form>
            </div>
        </div>

        <script>
            function semesterSelection() {
                var x = document.getElementById("semester-selection");
                x.style.display = (x.style.display === "none") ? "block" : "none";
            }
            function showTimetable() {
                var area = document.getElementById("timetable_area");
                area.style.display = (area.style.display === "none") ? "block" : "none";
            }
        </script>
    </body>
</html>