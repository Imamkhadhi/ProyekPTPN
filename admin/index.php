<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    include "template/header.php";
    
    if (isset($_SESSION['username'])) {
        echo "<h4>Selamat datang, " . $_SESSION['username'] . "</h4>";
    }
    ?>

    <style>
        h4 {
            margin-left: 300px;
        }
    </style>
</body>
</html>

