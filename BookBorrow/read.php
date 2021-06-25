<?php
$tenbd = $tensach = $tacgia = $sdt = "";
$ngaymuon = $ngaytra = date("d-M-Y");
$namxb = $trangthai = 0;
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    require_once "../Utils/connection_db.php";
    $param_id = trim($_GET["id"]);
    $sql = "SELECT a.*, b.tenbd, b.sdt, d.tensach, d.tacgia FROM muonsach a 
            JOIN bandoc b ON a.bd_id = b.id 
            JOIN muonsach_chitiet c ON a.id = c.muonsach_id 
            JOIN sach d ON c.sach_id = d.id WHERE a.id = '" . $param_id . "' GROUP BY c.id";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $tensach = $row["tensach"];
        $tenbd = $row["tenbd"];
        $tacgia = $row["tacgia"];
        $sdt = $row["sdt"];
        $ngaymuon = date_format(date_create($row["ngaymuon"]),"d/m/Y");
        $ngaytra = date_format(date_create($row["ngaytra"]),"d/m/Y");
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $tensach .= ', ' . $row["tensach"];
        }

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
                    <label>Reader Name</label>
                    <p><b><?php echo $tenbd; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Books Name</label>
                    <p><b><?php echo $tensach; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Actor</label>
                    <p><b><?php echo $tacgia; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <p><b><?php echo $sdt; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Borrow Date</label>
                    <p><b><?php echo $ngaymuon; ?></b></p>
                </div>
                <div class="form-group">
                    <label>Expired Date</label>
                    <p><b><?php echo $ngaytra; ?></b></p>
                </div>
                <p><a href="index.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>

</div>
</body>
</html>