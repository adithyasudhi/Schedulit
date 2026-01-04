<html>
    <head>
        <title>Admin's page</title>
    </head>
    <body>
        <div>
            <label>Admin's page</label>
            <form method="POST" action="admin.php">
                <input type = "text" name = "dept_id" placeholder = "Department ID" id = "dept_id">
                <input type = "text" name = "dept_name" placeholder = "Department Name" id = "dept_name">
                <input type = "submit" value = "Add Department" name = "add_dept" class = "subbtn inpcommon">
                <input type = "text" name = "dept_id-remove" placeholder = "Department to Remove ID" id = "dept_id_remove">
                <input type = "submit" value = "Remove Department" name = "remove_dept" class = "subbtn inpcommon">
            </form>
            <table>
                <tr>
                    <th>Department ID</th>
                    <th></th>
                    <th>Department Name</th>
                </tr>
                <?php
                    require_once "connection.php";
                    $query = " SELECT * FROM department ";
                    $res = mysqli_query($conn, $query);
                    if(!$res){
                        echo "Department Retrieval Failed: ". mysqli_error($conn);  
                    }
                    if(mysqli_num_rows($res) > 0){
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<tr>";
                            echo "<td>{$row['dept_id']}</td>";
                            echo "<td>  </td>";
                            echo "<td>{$row['dept_name']}</td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </table>
        </div>
    </body> 
</html>


<?php
    require_once "connection.php";
    if (isset($_POST["add_dept"])) {
        $dept_id = $_POST["dept_id"];
        $dept_name = $_POST["dept_name"];
        if (empty($dept_id) || empty($dept_name)) {
            echo "<script>alert('Error: Both ID and Name are required to add a department'); window.location.href='admin.php';</script>";
            exit();
        }

        $query = "INSERT INTO department (dept_id, dept_name) values ('$dept_id', '$dept_name');";
        $res = mysqli_query($conn, $query);
        if(!$res) {
            echo "<script>alert('Department insertion failed');</script>" . mysqli_error($conn) ;
        }else {
            header("Location: admin.php");
            exit();
        }
    }
?>
<?php
    require_once "connection.php";
    if (isset($_POST["remove_dept"])) {
        $dept_id = mysqli_real_escape_string($conn, $_POST["dept_id-remove"]);
        $query = "DELETE FROM department WHERE dept_id = '$dept_id';";
        $res = mysqli_query($conn, $query);
        if(!$res) {
            echo "<script>alert('Department removal failed');</script>" . mysqli_error($conn) ;
        }else {
            if(mysqli_affected_rows($conn) > 0) {
                header("Location: admin.php");
                exit();
            } else {
                echo "<script>alert('No department found with that ID');</script>";
            }
        }
    }
?>
