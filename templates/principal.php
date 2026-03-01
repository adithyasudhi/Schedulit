<?php
    require_once "connection.php";
    session_start();
    if (!isset($_SESSION['emp_id'])) {
        header("Location: login.php");
        exit();
    }    
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
            <form method="POST">
                <button type="button" onclick="viewtimetable()" class="subbtn inpcommon">View Timetable</button>
                <div id="dept_section" style="display:none;">
                    <!-- Department Dropdown -->
                    <label>Select Department:</label><br><br>
                    <select name="dept_list" class="text-inputs inpcommon" required>
                        <option value="">-- Select Department --</option>
                        <?php
                            $query = "SELECT * FROM department";
                            $res = mysqli_query($conn, $query);

                            while($row = mysqli_fetch_assoc($res)){
                                echo "<option value='{$row['dept_id']}'>{$row['dept_name']}</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" value="Next" name="select_dept" class="subbtn inpcommon">
                </div>
            </form>
            <?php
            // STEP 2: If 'Next' was clicked, show Semester & Batch Selection
            if(isset($_POST['select_dept']) && !empty($_POST['dept_list'])){
                $dept_id = mysqli_real_escape_string($conn, $_POST['dept_list']);
                
                // Fetch Dept Name for display
                $dept_res = mysqli_query($conn, "SELECT dept_name FROM department WHERE dept_id = '$dept_id'");
                $dept_data = mysqli_fetch_assoc($dept_res);
                
                echo "<h3>Department: " . htmlspecialchars($dept_data['dept_name']) . "</h3>";
                echo "<form action='faculty.php' method='POST'>";
                echo "<input type='hidden' name='dept_id' value='$dept_id'>";

                // Semester Dropdown
                echo "<label>Select Semester:</label><br><br>";
                echo "<select name='tt_sem' class='text-inputs inpcommon' required>";
                echo "<option value=''>Select Semester</option>";
                
                $query = "SELECT DISTINCT sem_id FROM semester"; 
                $res = mysqli_query($conn, $query);
                while($row = mysqli_fetch_assoc($res)){
                    echo "<option value='{$row['sem_id']}'>{$row['sem_id']}</option>";
                }
                echo "</select><br><br>";

                // Batch Dropdown
                echo "<label>Select Batch:</label><br><br>";
                echo "<select name='tt_batch' class='text-inputs inpcommon' required>";
                echo "<option value=''>Select Batch</option>";
                
                $res2 = mysqli_query($conn, "SELECT DISTINCT sem_batch FROM semester");
                while($row2 = mysqli_fetch_assoc($res2)){
                    echo "<option value='{$row2['sem_batch']}'>{$row2['sem_batch']}</option>";
                }
                echo "</select><br><br>";

                echo "<input type='submit' name='view_tt' value='View Timetable' class='subbtn inpcommon'>";
                echo "</form>";
            }
            ?>
        </div>
        <script>
            function viewtimetable() {
                var section = document.getElementById("dept_section");

                if (section.style.display === "none") {
                    section.style.display = "block";
                } else {
                    section.style.display = "none";
                }
            }
        </script>
    </body>
</html>