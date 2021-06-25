<?php
if (isset($_POST["id"]) && !empty($_POST["id"])) {

    require_once "../Utils/connection_db.php";
    $sql = "DELETE FROM muonsach_chitiet WHERE muonsach_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {

        mysqli_stmt_bind_param($stmt, "i", $param_id1);

        $param_id1 = $_POST["id"];

        if (mysqli_stmt_execute($stmt)) {
            $sql2 = "DELETE FROM muonsach WHERE id = ?";
            if ($stmt2 = mysqli_prepare($conn, $sql2)) {
                mysqli_stmt_bind_param($stmt2, "i", $param_id2);
                $param_id2 = $param_id1;
                if (mysqli_stmt_execute($stmt2)) {
                    header("location: index.php");
                    exit();
                }
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
} else {
    if (empty(trim($_GET["id"]))) {
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
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
                <h2 class="mt-5 mb-3">Delete Record</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger">
                        <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>"/>
                        <p>Are you sure you want to delete this book borrow?</p>
                        <p>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="index.php" class="btn btn-secondary">No</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>