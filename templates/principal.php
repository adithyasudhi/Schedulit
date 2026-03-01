<?php
    require_once "connection.php";
    session_start();
    if (!isset($_SESSION['emp_id'])) {
        header("Location: login.php");
        exit();
    }    

    // Initialize variables to keep selections persistent
    $dept_id = $_POST['dept_list'] ?? $_POST['dept_id'] ?? "";
    $selected_tt_sem = $_POST['tt_sem'] ?? "";
    $selected_tt_batch = $_POST['tt_batch'] ?? "";
?>
<html>
    <head>
        <title>Principal's Dashboard</title>
        <link rel="stylesheet" href="/schedulit/static/style.css">
    </head>
    <body>
        <nav class="navbar">
            <div class="nav-right">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>

        <div class="card2">
            <form method="POST" action="">
                <button type="button" onclick="toggleSection('dept_section')" class="subbtn inpcommon">View Timetable</button>
                
                <div id="dept_section" style="display: <?php echo $dept_id ? 'block' : 'none'; ?>;">
                    <br><label>Select Department:</label><br>
                    <select name="dept_list" class="text-inputs inpcommon" required>
                        <option value="">-- Select Department --</option>
                        <?php
                            $query = "SELECT * FROM department";
                            $res = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_assoc($res)){
                                $sel = ($row['dept_id'] == $dept_id) ? "selected" : "";
                                echo "<option value='{$row['dept_id']}' $sel>{$row['dept_name']}</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" value="Next" name="select_dept" class="subbtn inpcommon">
                </div>
            </form>

            <hr>

            <?php
            // STEP 2: Semester & Batch Selection (Triggers when Dept is selected)
            if(!empty($dept_id)){
                $dept_res = mysqli_query($conn, "SELECT dept_name FROM department WHERE dept_id = '$dept_id'");
                $dept_data = mysqli_fetch_assoc($dept_res);
                
                echo "Department: " . htmlspecialchars($dept_data['dept_name']);
                ?>
                <form action="" method="POST">
                    <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>">
                    
                    <select name="tt_sem" class="text-inputs inpcommon" required>
                        <option value="">Select Semester</option>
                        <?php
                            $query = "SELECT DISTINCT sem_id FROM semester";
                            $res = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_assoc($res)){
                                $sel = ($row['sem_id'] == $selected_tt_sem) ? "selected" : "";
                                echo "<option value='{$row['sem_id']}' $sel>{$row['sem_id']}</option>";
                            }
                        ?>
                    </select>

                    <input type="submit" name="load_tt_batches" value="Load Batches" class="subbtn inpcommon">

                    <?php if (!empty($selected_tt_sem)): ?>
                        <select name="tt_batch" class="text-inputs inpcommon" required>
                            <option value="">Select Batch</option>
                            <?php
                                $stmt = mysqli_prepare($conn, "SELECT DISTINCT sem_batch FROM semester WHERE sem_id = ?");
                                mysqli_stmt_bind_param($stmt, "s", $selected_tt_sem);
                                mysqli_stmt_execute($stmt);
                                $res = mysqli_stmt_get_result($stmt);

                                while($row = mysqli_fetch_assoc($res)){
                                    $sel = ($row['sem_batch'] == $selected_tt_batch) ? "selected" : "";
                                    echo "<option value='{$row['sem_batch']}' $sel>{$row['sem_batch']}</option>";
                                }
                            ?>
                        </select>
                        <input type="submit" name="view_tt" value="View Timetable" class="subbtn inpcommon">
                    <?php endif; ?>
                </form>
            <?php 
            } 

            // STEP 4: Display the Timetable Results
            if (isset($_POST['view_tt']) && !empty($selected_tt_batch)) {
                echo "<div class='results-area'><h4>Showing Timetable for Sem $selected_tt_sem - Batch $selected_tt_batch</h4>";
                // Add your SQL query to fetch and display the actual timetable grid here
                echo "</div>";
            }
            ?>
        </div>

        <script>
            function toggleSection(id) {
                var x = document.getElementById(id);
                x.style.display = (x.style.display === "none") ? "block" : "none";
            }
        </script>
    </body>
</html>