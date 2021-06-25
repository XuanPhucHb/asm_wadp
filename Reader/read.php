<?php
$mabd = "";
$tenbd = "";
$diachi = "";
$email = "";
$phone = "";
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    require_once "../Utils/connection_db.php";
    $param_id = trim($_GET["id"]);
    $sql = "SELECT * FROM bandoc WHERE id = '" . $param_id . "'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $mabd = $row["mabd"];
        $tenbd = $row["tenbd"];
        $diachi = $row["diachi"];
        $email = $row["email"];
        $phone = $row["sdt"];
    } else {
        header("location: error.php");
        exit();
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-5 mb-3">View Record</h1>
                <div class="form-group">
                    <label>Code</label>
                    <p><b><?php echo $mabd; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <p><b><?php echo $tenbd; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <p><b><?php echo $diachi; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <p><b><?php echo $email; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <p><b><?php echo $phone; ?></b></p>
                </div>
                <p><a href="index.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>