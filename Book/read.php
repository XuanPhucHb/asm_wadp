<?php
$masach = $tensach = $tacgia = $tencn = "";
$namxb = $trangthai = 0;
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    require_once "../Utils/connection_db.php";
    $param_id = trim($_GET["id"]);
    $sql = "SELECT b.*, a.tencn FROM chuyennganh a JOIN sach b ON a.macn = b.macn WHERE b.id = '" . $param_id . "'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $masach = $row["masach"];
        $tensach = $row["tensach"];
        $tacgia = $row["tacgia"];
        $namxb = $row["tacgia"];
        $trangthai = $row["tacgia"];
        $tencn = $row["tencn"];
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
                    <p><b><?php echo $masach; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <p><b><?php echo $tensach; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <p><b><?php echo $tacgia; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Publish year</label>
                    <p><b><?php echo $namxb; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <p><b><?php echo $trangthai == 1 ? 'Published' : 'Not publish'; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Specialization</label>
                    <p><b><?php echo $tencn; ?></b></p>
                </div>
                <p><a href="index.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>