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
            <label>Register Here</label>
            <form class="formcls" method="POST" action="signup.php" >
                <input type="text" placeholder="UserName" name="user_name" id="user" class="inpbox inpcommon">
                <input type="text" placeholder="Enter Emp id " name="user_empid" id="user" class="inpbox inpcommon">
                <input type="email" placeholder="enter Emp email" name="user_email" class="inpbox inpcommon">
                <input type="password" placeholder="Enter Your Password" name="user_password" class="inpbox inpcommon">
                <select name="user_type">
                    <option value="1">HOD</option>
                    <option value="2">Coordinator</option>
                    <option value="3">Faculty</option>
                </select>
                <select name="dept_id">
                    <option value="1">AIML</option>
                    <option value="2">CE</option>
                    <option value="3">CSE</option>
                    <option value="4">ECE</option>
                    <option value="5">EEE</option>
                </select>
                <input type="submit" value="Signup" name="signup" class="subbtn inpcommon">
            </form>
        </div>
    </body>
</html>

<?php
    require_once "connection.php";
    if (isset($_POST["signup"])) {
        $user_name = $_POST["user_name"];
        $user_empid = $_POST["user_empid"];
        $user_password = $_POST["user_password"];
        $user_type = $_POST["user_type"];
        $user_email = $_POST["user_email"];

        echo $user_type;

        $query = "INSERT INTO user (username, emp_id, pass, user_type,emp_email) values ('$user_name', '$user_empid', '$user_password','$user_type','user_email');";
        $res = mysqli_query($conn, $query);
        if(!$res) {
            echo "Connection failed :". mysqli_error($conn);
        }else {
            header("Location: login.php");
            
        }
    }
?>