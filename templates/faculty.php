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
        <title>Faculty Dashboard</title>
        <link rel="stylesheet" href="/schedulit/static/style.css">
    </head>
    <body>
        <nav class="navbar">
            <div class="nav-right">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
        <div class="card2">
            <form action="faculty.php" method="POST">
                <button type="button" onclick="viewtimetable()" class="subbtn inpcommon">view timetable</button>
            </form>
        </div>
    </body>
</html>