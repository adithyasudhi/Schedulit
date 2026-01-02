<?php
    require_once "connection.php";
?>

<form method="POST">
    <input type="text" placeholder="department ID" name="dept_id" id="dept_id">
    <input type="text" placeholder="department Name" name="dept_name" id="dept_name">
    <input type="submit" value="Add" name="addDept">
</form>

<form method="POST">
    <select>
    <?php
        $query = "SELECT * FROM departments";
        $res = mysqli_query($conn, $query);
        if(!$res){
            echo "Department Retrieval Failed:". mysqli_error($conn);
        }
        if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                echo "<option value='{$row['dept_id']}'>{$row['dept_name']}</option>";
            }
        }
    ?>
    </select>
    <input type="submit" value="Delete" name="deleteDept">
</form>

<?php
    if(isset($_POST["addDept"])){
        $query = "INSERT INTO departments (dept_id, dept_name) VALUES ('{$_POST['dept_id']}', '{$_POST['dept_name']}');";
        $res = mysqli_query($conn, $query);
        if(!$res){
            echo "Department Insertion Failed:". mysqli_error($conn);
        }
        header("Location: admin.php");
        exit();
    }
?>