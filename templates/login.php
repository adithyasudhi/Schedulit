<!DOCTYPE html>
<html>
    <head>
        <title>Time Table Login Page</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="card1">
            <label>Login Page</label>
            <form class="formcls" method="POST" action="login.php" >
                <input type="text" placeholder="Enter Emp id" name="user_empid" id="user_empid" class="inpbox inpcommon">
                <input type="password" placeholder="Enter Password" name="user_password" class="inpbox inpcommon">
                <input type="submit" value="Login" name="submit" class="subbtn inpcommon" >
                <label>New user</label>
                <a href="signup.php">Signup</a> 
            </form>
            
        </div>
    </body>
</html>

<?php
    session_start();
    require_once "connection.php";
    if (isset($_POST['submit'])) {
    
        $user_empid = $_POST["user_empid"];
        $user_password = $_POST["user_password"];

        $query = "SELECT * FROM user WHERE emp_id= '$user_empid' AND pass = '$user_password';";

        $res = mysqli_query($conn, $query);

        if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);
            $_SESSION["user_empid"] = $row["emp_id"];
            $_SESSION["user_name"] = $row["username"];
            if($row["user_type"] == 0) {
                if($_SESSION['user_name'] == 'principal'){
                    header("Location: principal.php");
                    exit();
                }else{
                    header("Location: admin.php");
                    exit();
                }

            }else if($row["user_type"] == 1) {
                header("Location: hod.php");
                exit();
            }else if($row["user_type"] == 2) {
                header("Location: coordinator.php");
                exit();
            }else if($row["user_type"] == 3) {
                header("Location: faculty.php");
                exit();
            }
            else{
                echo "<script>alert('Invalid Employee ID or Password');</script>";
            }
        }
    }
?>