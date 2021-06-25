<?php
//$row = [];
$macn = "";
$tencn = "";
if (isset($_GET["macn"]) && !empty(trim($_GET["macn"]))) {
    require_once "../Utils/connection_db.php";
    $param_macn = trim($_GET["macn"]);
    $sql = "SELECT * FROM chuyennganh WHERE macn = '" . $param_macn . "'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $macn = $row["macn"];
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
                    <p><b><?php echo $macn; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <p><b><?php echo $tencn; ?></b></p>
                </div>
                <p><a href="index.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>