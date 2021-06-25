<?php
// Include config file
require_once "../Utils/connection_db.php";

$macn = $tencn = "";
$macn_err = $tencn_err = "";

if (isset($_GET['macn'])) {
    $macn = trim($_GET["macn"]);

    $sql = "SELECT macn, tencn FROM chuyennganh WHERE macn = ?";

    if ($conn->connect_errno) {
        echo "Failed to connect: " . $conn->connect_error;
        exit();
    }
    $stmt = $conn->stmt_init();
    if ($stmt->prepare($sql)) {

        $stmt->bind_param("s", $macn);
        $stmt->execute();
        mysqli_stmt_bind_result($stmt, $macn, $tencn);
        $stmt->fetch();
        $stmt->close();
    }
    $conn->close();
} else {
    $newMacn = trim($_POST["newMacn"]);

    $newTencn = trim($_POST["newTencn"]);

    if (empty($newTencn)) {
        $tencn_err = "Please enter tencn.";
    }

    if (empty($macn_err) && empty($tencn_err)) {
        $sql = "UPDATE chuyennganh SET tencn=? WHERE macn=?";
        if ($conn->connect_errno) {
            echo "Failed to connect: " . $conn->connect_error;
            exit();
        }
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)) {
            $stmt->bind_param("ss", $newTencn, $newMacn);

            if ($stmt->execute()) {
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                <h2 class="mt-5">Update Specialization: <?php echo $tencn; ?></h2>
                <p>Please edit the input values and submit to update the specialization.</p>
                <form action="/ASM/Specialization/update.php" method="post">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="newMacn" readonly class="form-control"
                               value="<?php echo $macn; ?>">
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="newTencn" class="form-control
<?php echo (!empty($tencn_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tencn; ?>">
                        <span class="invalid-feedback"><?php echo $tencn_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>