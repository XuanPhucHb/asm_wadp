<?php
require_once "../Utils/connection_db.php";
$trangthai = $macnSelect = 0;
$namxb = date("Y");
$masach = $tensach = $tacgia = "";
$masach_err = $tensach_err = $tacgia_err = "";
$listSpec = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_masach = trim($_POST["masach"]);
    if (strlen($input_masach) != 5) {
        $masach_err = "Code must be greater than 5 characters";
    } else {
        $input_masach = strtoupper($input_masach);
        $sql = "SELECT * FROM sach WHERE upper(masach) = '$input_masach'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $masach_err = "Duplicated Code";
        }
        $masach = $input_masach;
    }

    $input_tensach = trim($_POST["tensach"]);
    if (empty($input_tensach)) {
        $tensach_err = "Please enter tensach.";
    } else {
        $tensach = $input_tensach;
    }

    $input_tacgia = trim($_POST["tacgia"]);
    if (empty($input_tacgia)) {
        $tacgia_err = "Please enter tacgia.";
    } else {
        $tacgia = $input_tacgia;
    }

    $input_namxb = $_POST["namxb"];
    if ($input_namxb < 0 || $input_namxb > date("Y")) {
        $namxb_err = "Invalid Publish year.";
    } else {
        $namxb = $input_namxb;
    }
    if (empty($tensach_err) && empty($masach_err) && empty($tacgia_err) && empty($namxb_err)) {
        $trangthai = trim($_POST["trangthai"]);
        $macn = trim($_POST["macnSelect"]);
        $sql = "INSERT INTO sach (masach, tensach, tacgia, namxb, trangthai, macn) VALUES ('$masach', '$tensach', '$tacgia', '$namxb', '$trangthai', '$macn')";
        echo $sql;
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
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM chuyennganh ORDER BY tencn";
    if ($result = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {
                $listSpec[$i] = [$row['macn'], $row['tencn']];
                $i++;
            }
        }
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
                <p>Please fill this form and submit to add "bandoc".</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="masach"
                               class="form-control <?php echo (!empty($masach_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $masach; ?>">
                        <span class="invalid-feedback"><?php echo $masach_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="tensach"
                               class="form-control <?php echo (!empty($tensach_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $tensach; ?>">
                        <span class="invalid-feedback"><?php echo $tensach_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Actor</label>
                        <input type="text" name="tacgia"
                               class="form-control <?php echo (!empty($tacgia_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $tacgia; ?>">
                        <span class="invalid-feedback"><?php echo $tacgia_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Publish Year</label>
                        <input type="number" name="namxb"
                               class="form-control <?php echo (!empty($namxb_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $namxb; ?>">
                        <span class="invalid-feedback"><?php echo $namxb_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-select" name="trangthai">
                            <option value="1" selected>Published</option>
                            <option value="0">Not Publish</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Specialization</label>
                        <select class="form-select" name="macnSelect">
                            <?php
                            foreach ($listSpec as $key => $value)
                                if ($key == 0) {
                                    echo "<option value='$value[0]' selected>$value[1]</option>";
                                } else {
                                    echo "<option value='$value[0]'>$value[1]</option>";
                                }
                            ?>
                        </select>
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