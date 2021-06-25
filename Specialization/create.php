<?php
require_once "../Utils/connection_db.php";

$macn = $tencn = "";
$macn_err = $tencn_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_macn = trim($_POST["macn"]);
    if (strlen($input_macn) != 5) {
        $macn_err = "Code must be greater than 5 characters";
    } else {
        $input_macn = strtoupper($input_macn);
        $sql = "SELECT * FROM chuyennganh WHERE upper(macn) = '$input_macn'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $macn_err = "Duplicated Code";
        }
        $macn = $input_macn;
    }

    $input_tencn = trim($_POST["tencn"]);
    if (empty($input_tencn)) {
        $tencn_err = "Please enter name.";
    }
    $tencn = $input_tencn;

    if (empty($macn_err) && empty($tencn_err)) {
        $sql = "INSERT INTO chuyennganh (macn, tencn) VALUES ('$macn', '$tencn')";
        var_dump($conn);
        if (mysqli_query($conn, $sql)) {
            header("location: index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    if($conn != null){
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                <h2 class="mt-5">Create Record</h2>
                <p>Please fill this form and submit to add "chuyennganh".</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="macn"
                               class="form-control <?php echo (!empty($macn_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $macn; ?>">
                        <span class="invalid-feedback"><?php echo $macn_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="tencn"
                               class="form-control <?php echo (!empty($tencn_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $tencn; ?>">
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