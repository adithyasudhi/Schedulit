<!DOCTYPE html>
<html>
    <head>
        <title>HOD Dashboard</title>
    </head>
    <body>
        <div>
            <label>HOD Page</label>
            <form method="POST" action="hod.php">
                <input type = "text" name = "sem_id" placeholder = "Semester Name" id = "sem_id" >             
                <input type = "text" name = "sem_batch" placeholder = "Batch" id = "sem_batch" >
                <input type = "submit" value = "Add Semester" name = "add_sem" class = "subbtn inpcommon">

                <input type = "text" name = "sem_id_remove" placeholder = "Semester Name to Remove" id = "sem_id_remove">
                <input type = "text" name = "sem_batch_remove" placeholder = "Batch to Remove" id = "sem_batch_remove" >
                <input type = "submit" value = "Remove Semester" name = "remove_sem" class = "subbtn inpcommon">             
            </form>
            <table>
                <tr>
                    <th>Semester Name</th>
                    <th></th>
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
                            echo "<td>  </td>";
                            echo "<td>{$row['sem_batch']}</td>";
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
    if (isset($_POST["add_sem"])) {
        $sem_id = mysqli_real_escape_string($conn, $_POST["sem_id"]);
        $sem_batch = mysqli_real_escape_string($conn, $_POST["sem_batch"]);

        
        $query = "INSERT INTO semester (sem_id, sem_batch) values ('$sem_id', '$sem_batch');";
        $res = mysqli_query($conn, $query);
        if(!$res) {
            echo "<script>alert('Semester insertion failed');</script>" . mysqli_error($conn) ;
        }else {
            header("Location: hod.php");
            exit();
        }
    }
?>
<?php
    require_once "connection.php";
    if (isset($_POST["remove_sem"])) {
        $sem_id = mysqli_real_escape_string($conn, $_POST["sem_id_remove"]);
        $sem_batch = mysqli_real_escape_string($conn, $_POST["sem_batch_remove"]);
        $query = "DELETE FROM semester WHERE sem_id = '$sem_id' AND sem_batch = '$sem_batch';";
        $res = mysqli_query($conn, $query);
        if(!$res) {
            echo "<script>alert('Semester removal failed');</script>" . mysqli_error($conn) ;
        }else {
            if(mysqli_affected_rows($conn) > 0) {
                header("Location: hod.php");
                exit();
            } else {
                echo "<script>alert('No semester found with that ID');</script>";
            }
        }
    }
?>





