<?php
    require_once "connection.php";
    session_start();
    if (isset($_POST["signup"])) {
        $user_name     = mysqli_real_escape_string($conn, $_POST["user_name"]);
        $user_empid    = mysqli_real_escape_string($conn, $_POST["user_empid"]);
        $user_password = mysqli_real_escape_string($conn, $_POST["user_password"]);
        $user_type     = mysqli_real_escape_string($conn, $_POST["user_type"]);
        $user_email    = mysqli_real_escape_string($conn, $_POST["user_email"]);;
        $user_dept = mysqli_real_escape_string($conn, $_POST["dept_list"]);

        $query = "INSERT INTO user (username, emp_id, pass, user_type, emp_email, dept_id) 
                  VALUES ('$user_name', '$user_empid', '$user_password', '$user_type', '$user_email', '$user_dept')";
        $res = mysqli_query($conn, $query);
        if(!$res) {
            echo "Connection failed :". mysqli_error($conn);
        }else {
            $_SESSION['emp_id']   = $user_empid;
            $_SESSION['username'] = $user_name;
            $_SESSION['user_type'] = $user_type;
            $_SESSION['dept_id']  = $user_dept;

            header("Location: login.php");
            exit();
        }
    }
?>








<!DOCTYPE html>
<html>
    <head>
        <title>Time Table Login Page</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/schedulit/static/style.css">
    </head>

    <body>
        <div class="card1">
            <label>Register Here</label>
            <form class="formcls" method="POST" action="signup.php" id="signup_form">
                <input type="text" placeholder="UserName" name="user_name" id="user" class="text-inputs inpcommon">
                <input type="text" placeholder="Enter Emp id " name="user_empid" id="user_empid" class="text-inputs inpcommon">
                <input type="email" placeholder="enter Emp email" name="user_email" class="text-inputs inpcommon" id="user_email">
                <input type="password" placeholder="Enter Your Password" name="user_password" class="text-inputs inpcommon" id="user_pass">
                <select name="user_type" class="text-inputs inpcommon">
                    <option value="1">HOD</option>
                    <option value="2">Coordinator</option>
                    <option value="3">Faculty</option>
                </select>
                <select name="dept_list" class="text-inputs inpcommon">
                    <?php
                        $query = " SELECT * FROM department ";
                        $res = mysqli_query($conn, $query);
                        if(!$res){
                            echo "Department Retrieval Failed: ". mysqli_error($conn);  
                        }
                        if(mysqli_num_rows($res) > 0){
                            while($row = mysqli_fetch_assoc($res)){
                                echo "<option value='{$row['dept_id']}'>{$row['dept_name']}</option>";
                            }
                        }
                    ?>
                </select>
                <input type="submit" value="Signup" name="signup" class="subbtn inpcommon">
            </form>
        </div>
        <script>
            document.getElementById('signup_form').onsubmit = function(event) {
                var empId = document.getElementById('user_empid').value;
                var email = document.getElementById('user_email').value;
                var password = document.getElementById('user_pass').value;
                if (name === "" || empId === "" || email === "" || password === "") {
                    alert("All fields are required!");
                    event.preventDefault();
                    return false;
                }
                var empIdPattern = /^emp\d+$/; 
                var emailPattern = /^emp\d+@vidyaacademy\.ac\.in$/;
                var passPattern = /^.{8,}$/;

                
                if (!empIdPattern.test(empId)) {
                    alert("Employee ID must be in format");
                    event.preventDefault();
                    return false;
                }

                if (!emailPattern.test(email)) {
                    alert("Email must be in format");
                    event.preventDefault();
                    return false;
                }

                if (!passPattern.test(password)) {
                    alert("Password must be at least 8 characters long");
                    event.preventDefault();
                    return false;
                }

                return true;
            };
        </script>
    </body>
</html>