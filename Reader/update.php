<?php
// Include config file
require_once "../Utils/connection_db.php";

$id = 0;
$mabd = $tenbd = $diachi = $email = $phone = "";
$mabd_err = $tenbd_err = $diachi_err = "";

if (isset($_GET['id'])) {
    if (isset($_GET["id"])) {
        $sql = "SELECT mabd, tenbd, diachi, email, sdt FROM bandoc WHERE id = ?";
        $id = $_GET["id"];
        if ($conn->connect_errno) {
            echo "Failed to connect: " . $conn->connect_error;
            exit();
        }
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)) {

            $stmt->bind_param("i", $id);
            $stmt->execute();
            mysqli_stmt_bind_result($stmt, $mabd, $tenbd, $diachi, $email, $phone);
            $stmt->fetch();
            $stmt->close();
        }
        $conn->close();
    } else {
        header("location: error.php");
        exit();
    }


} else {

    $id = $_POST["@$%"];
    $mabd = trim($_POST["newmabd"]);
    $tenbd = trim($_POST["newtenbd"]);
    $diachi = trim($_POST["newdiachi"]);
    $email = trim($_POST["newemail"]);
    $phone = trim($_POST["newphone"]);

    if (strlen($mabd) != 5) {
        $mabd_err = "Code must be greater than 5 characters";
    } else {
        $mabd = strtoupper($mabd);
        $sql = "SELECT * FROM bandoc WHERE upper(mabd) = '$mabd'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $mabd_err = "Duplicated Code";
        }
    }

    if (empty($tenbd)) {
        $tenbd_err = "Please enter name.";
    }

    if (empty($diachi)) {
        $diachi_err = "Please enter address.";
    }

    if (empty($mabd_err) && empty($tenbd_err) && empty($diachi_err)) {
        $sql = "UPDATE bandoc SET mabd=?, tenbd=?, diachi=?, email=?, sdt=? WHERE id=?";
        if ($conn->connect_errno) {
            echo "Failed to connect: " . $conn->connect_error;
            exit();
        }
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)) {
            $stmt->bind_param("sssssi", $mabd, $tenbd, $diachi, $email, $phone, $id);

            if ($stmt->execute()) {
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        if ($stmt != null)
            mysqli_stmt_close($stmt);


    }
    if ($conn != null)
        $conn->close();
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
                <h2 class="mt-5">Update Specialization: <?php echo $tenbd; ?></h2>
                <p>Please edit the input values and submit to update the specialization.</p>
                <form action="/ASM/Reader/update.php" method="post">
                    <div class="form-group">
                        <input type="hidden" class="form-control" style="display: none" name="@$%"
                               value="<?php echo $id; ?>"></div>
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="newmabd" class="form-control
<?php echo (!empty($mabd_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $mabd; ?>">
                        <span class="invalid-feedback"><?php echo $mabd_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="newtenbd" class="form-control
<?php echo (!empty($tenbd_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tenbd; ?>">
                        <span class="invalid-feedback"><?php echo $tenbd_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea type="text" name="newdiachi" class="form-control
<?php echo (!empty($diachi_err)) ? 'is-invalid' : ''; ?>"><?php echo $diachi; ?></textarea>
                        <span class="invalid-feedback"><?php echo $diachi_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="newemail" class="form-control
<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                        <span class="invalid-feedback"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="newphone" class="form-control
<?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                        <span class="invalid-feedback"><?php echo $phone_err; ?></span>
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