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