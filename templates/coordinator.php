<?php
    require_once "connection.php";
    session_start();
    if (!isset($_SESSION['emp_id'])) {
        header("Location: login.php");
        exit();
    }

$selected_sem = "";


/* ---------------- LOAD BATCHES ---------------- */
if (isset($_POST['semester'])) {
    $_SESSION['selected_sem'] = $_POST['semester'];
}
if (isset($_SESSION['selected_sem'])) {
    $selected_sem = $_SESSION['selected_sem'];
}
if (isset($_POST['batch'])) {
    $_SESSION['selected_batch'] = $_POST['batch'];
}

/* ---------------- ADD COURSE ---------------- */
if (isset($_POST['add_course'])) {

    $course_id   = mysqli_real_escape_string($conn, $_POST['course_id']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_l    = mysqli_real_escape_string($conn, $_POST['course_l']);
    $course_t    = mysqli_real_escape_string($conn, $_POST['course_t']);
    $course_p    = mysqli_real_escape_string($conn, $_POST['course_p']);
    $course_r    = mysqli_real_escape_string($conn, $_POST['course_r']);
    $selected_batch = mysqli_real_escape_string($conn, $_POST['selected_batch']);

    $query = "INSERT INTO course 
              (course_id, course_name, course_l, course_t, course_p, course_r, sem_id)
              VALUES 
              ('$course_id', '$course_name', '$course_l', '$course_t', '$course_p', '$course_r', '$selected_sem')";

    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Coordinator Page</title>
    </head>
    
    <body>
        <h2>Coordinator Page</h2>
        <nav class="navbar">
            <div class="nav-right">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>

    <!-- ================= SEMESTER & BATCH FORM ================= -->
        <form method="POST" action="coordinator.php">
            <label>Select Semester:</label>
            <select name="semester">
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
            <br><br>      
            <button type="submit" name="next">Next</button>
        </form>
        <hr>

    <!-- ================= ADD COURSE FORM ================= -->

        <?php if (isset($_POST['next']) || isset($_POST['add_course'])) { ?>

        <h3>Add Course for <?php echo $selected_sem; ?></h3>

        <form method="POST" action="coordinator.php">
            <input type="hidden" name="selected_batch" value="<?php echo htmlspecialchars($selected_batch); ?>">
            <input type="text" name="course_id" placeholder="Course ID" required><br><br>
            <input type="text" name="course_name" placeholder="Course Name" required><br><br>
            <input type="number" name="course_l" placeholder="L" required><br><br>
            <input type="number" name="course_t" placeholder="T" required><br><br>
            <input type="number" name="course_p" placeholder="P" required><br><br>
            <input type="number" name="course_r" placeholder="R"><br><br>
            <input type="submit" value="Add Course" name="add_course">
        </form>
        <hr>

    <!-- ================= DISPLAY COURSES ================= -->

        <h3>Course List</h3>
        <table border="1" cellpadding="8">
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Sem</th>
                <th>L</th>
                <th>T</th>
                <th>P</th>
                <th>R</th>
            </tr>

            <?php
                $query = "SELECT * FROM course WHERE sem_id = '$selected_sem'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>".$row['course_id']."</td>
                    <td>".$row['course_name']."</td>
                    <td>".$row['sem_id']."</td>
                    <td>".$row['course_l']."</td>
                    <td>".$row['course_t']."</td>
                    <td>".$row['course_p']."</td>
                    <td>".$row['course_r']."</td>
                </tr>";
                }
            ?>
        </table>
        <?php } ?>
    </body>
</html>
