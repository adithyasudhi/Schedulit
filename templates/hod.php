<?php
    require_once "connection.php";
    session_start();
    if (!isset($_SESSION['emp_id'])) {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>HOD's page</title>
    </head>
    <body>
        <div>
            <label>HOD's Page</label>
            <nav class="navbar">
                <div class="nav-right">
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </nav>

            <br><br>
            <button type="button" onclick="showModification()">Semester Modification</button>
            <br><br>
            <div id="mod_area" style="display: none;">
                <form method="POST" action="hod.php">
        
                    <input type = "text" name = "sem_id" placeholder = "Semester ID" id = "sem_id">            
                    <input type = "text" name = "sem_batch" placeholder = "Batch" id = "sem_batch" >
                    <input type = "submit" value = "Add Semester" name = "add_sem" class = "subbtn inpcommon">
                    <?php
                        require_once "connection.php";

                        if (isset($_POST['add_sem'])) {
                            $sem_id = $_POST['sem_id'];
                            $sem_batch = $_POST['sem_batch'];

                            $stmt = mysqli_prepare($conn, "INSERT INTO semester (sem_id, sem_batch) VALUES (?, ?)");
                            mysqli_stmt_bind_param($stmt, "ss", $sem_id, $sem_batch);
                            mysqli_stmt_execute($stmt);
                        }
                    ?>

                    <br><br>

                    <input type = "text" name = "sem_id_remove" placeholder = "Remove Semester ID" id = "sem_id_remove">
                    <input type = "text" name = "sem_batch_remove" placeholder = "Remove Batch" id = "sem_batch_remove" >
                    <input type = "submit" value = "Remove Semester" name = "remove_sem" class = "subbtn inpcommon"> 
                    <?php
                        require_once "connection.php";

                        if (isset($_POST['remove_sem'])) {
                            $sem_id_remove = $_POST['sem_id_remove'];
                            $sem_batch_remove = $_POST['sem_batch_remove'];

                            $stmt = mysqli_prepare($conn, "DELETE FROM semester WHERE sem_id = ? AND sem_batch = ?");
                            mysqli_stmt_bind_param($stmt, "ss", $sem_id_remove, $sem_batch_remove);
                            mysqli_stmt_execute($stmt);
                        }
                    ?>           
                </form>
                <br>
                <table border="1">
                    <tr>
                        <th>Semester Name</th>
                        <th>Batch</th>
                    </tr>
                    <?php
                        require_once "connection.php";
                        $query = " SELECT * FROM semester ";
                        $res = mysqli_query($conn, $query);
                        if(!$res){
                            echo "Semester Retrieval Failed: ". mysqli_error($conn);  
                        }
                        if(mysqli_num_rows($res) > 0){
                            while($row = mysqli_fetch_assoc($res)){
                                echo "<tr>";
                                    echo "<td>{$row['sem_id']}</td>";
                                    echo "<td>{$row['sem_batch']}</td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                </table>
            </div>
            <br><br>
<div id="alloc_area">
    <button type="button" onclick="showAllocation()">Teacher Allocation</button>
    <h3>Select Semester for Teacher Allocation</h3>

    <div id="teacher_alloc_area">

        <?php require_once "connection.php"; ?>

        <form method="POST" action="hod.php">

            <label>Select Semester and Batch</label>
            <br><br>

            <!-- Semester Dropdown -->
            <select name="sem_list">
                <option value="">Select Semester</option>
                <?php
                    $selected_sem = $_POST['sem_list'] ?? "";

                    $query = "SELECT DISTINCT sem_id FROM semester";
                    $res = mysqli_query($conn, $query);

                    while($row = mysqli_fetch_assoc($res)){
                        $sel = ($row['sem_id'] == $selected_sem) ? "selected" : "";
                        echo "<option value='{$row['sem_id']}' $sel>{$row['sem_id']}</option>";
                    }
                ?>
            </select>

            <input type="submit" name="load_batches" value="Load Batches" class="subbtn inpcommon">

            <br><br>

            <!-- Batch Dropdown -->
            <select name="batch_list">
                <option value="">Select Batch</option>
                <?php
                    if (!empty($selected_sem)) {

                        $selected_batch = $_POST['batch_list'] ?? "";

                        $stmt = mysqli_prepare($conn, 
                            "SELECT DISTINCT sem_batch FROM semester WHERE sem_id = ?"
                        );
                        mysqli_stmt_bind_param($stmt, "s", $selected_sem);
                        mysqli_stmt_execute($stmt);
                        $res = mysqli_stmt_get_result($stmt);

                        while($row = mysqli_fetch_assoc($res)){
                            $sel = ($row['sem_batch'] == $selected_batch) ? "selected" : "";
                            echo "<option value='{$row['sem_batch']}' $sel>{$row['sem_batch']}</option>";
                        }
                    }
                ?>
            </select>

            <input type="submit" name="show_courses" value="Show Courses" class="subbtn inpcommon">

        </form>
        <?php
if (isset($_POST['show_courses']) && !empty($_POST['sem_list']) && !empty($_POST['batch_list'])) {

    $selected_sem = $_POST['sem_list'];
    $selected_batch = $_POST['batch_list'];

    echo "<h3>Courses for $selected_sem - Batch $selected_batch</h3>";

    $stmt = mysqli_prepare($conn, 
        "SELECT * FROM course WHERE sem_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $selected_sem);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        // Fetch all faculty users
        $faculty_query = "SELECT username FROM user WHERE user_type = 3 ";
        $faculty_res = mysqli_query($conn, $faculty_query);
        $faculty_list = [];

        while ($fac = mysqli_fetch_assoc($faculty_res)) {
            $faculty_list[] = $fac['username'];
        }

        echo "<table border='1'>";
        echo "<tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Assign Faculty</th>
              </tr>";

        while ($row = mysqli_fetch_assoc($result)) {

            echo "<tr>";
            echo "<td>{$row['course_id']}</td>";
            echo "<td>{$row['course_name']}</td>";

            // Dropdown with typing support
            echo "<td>
                    <input list='faculty_list_{$row['course_id']}' name='faculty_{$row['course_id']}'>
                    <datalist id='faculty_list_{$row['course_id']}'>";

            foreach ($faculty_list as $faculty) {
                echo "<option value='$faculty'>";
            }

            echo "  </datalist>
                  </td>";

            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No courses found for this semester and batch.";
    }
}
?>


    </div>
</div>






        </div>

        <script>
            function showModification() {
                var area = document.getElementById("mod_area");
                if (area.style.display === "none") {
                    area.style.display = "block";
                } else {
                    area.style.display = "none";
                }
            }

            function showAllocation() {
                var area = document.getElementById("teacher_alloc_area");
                if (area.style.display === "none") {
                    area.style.display = "block";
                }
            }
        </script>
    </body>
</html>