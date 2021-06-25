<?php
// Include config file
require_once "../Utils/connection_db.php";

$id = 0;
$trangthai = $macnSelect = $macn = 0;
$namxb = date("Y");
$masach = $tensach = $tacgia = "";
$masach_err = $tensach_err = $tacgia_err = $namxb_err = "";
$listSpec = [];
if (isset($_GET['id'])) {
    if (isset($_GET["id"])) {
        $sql = "SELECT masach, tensach, tacgia, namxb, trangthai, macn FROM sach WHERE id = ?";
        $id = $_GET["id"];
        if ($conn->connect_errno) {
            echo "Failed to connect: " . $conn->connect_error;
            exit();
        }
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)) {

            $stmt->bind_param("i", $id);
            $stmt->execute();
            mysqli_stmt_bind_result($stmt, $masach, $tensach, $tacgia, $namxb, $trangthai, $macn);
            $stmt->fetch();
            $stmt->close();
        }
        $sql = "SELECT macn, tencn FROM chuyennganh ORDER BY tencn";
        if ($result = mysqli_query($conn, $sql)) {

            if (mysqli_num_rows($result) > 0) {
                $i = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $listSpec[$i] = [$row['macn'], $row['tencn']];
                    $i++;
                }
            }
        }
        $conn->close();

    } else {
        header("location: error.php");
        exit();
    }


} else {

    $id = $_POST["@$%"];
    $masach= trim($_POST["newmasach"]);
    if (strlen($masach) != 5) {
        $masach_err = "Code must be greater than 5 characters";
    } else {
        $masach = strtoupper($masach);
        $sql = "SELECT * FROM sach WHERE upper(masach) = '$masach'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $masach_err = "Duplicated Code";
        }
    }
    $tensach = trim($_POST["newtensach"]);
    if (empty($tensach)) {
        $tensach_err = "Please enter name.";
    }
    $tacgia = trim($_POST["newtacgia"]);
    if (empty($tacgia)) {
        $tacgia_err = "Please enter actor.";
    }
    $namxb = trim($_POST["newnamxb"]);
    if ($namxb < 0 || $namxb > date("Y")) {
        $namxb_err = "Invalid publish year.";
    }
    $trangthai = trim($_POST["newtrangthai"]);
    $macn = trim($_POST["macnSelect"]);

    if (empty($tensach_err) && empty($masach_err) && empty($tacgia_err) && empty($namxb_err)) {
        $sql = "UPDATE sach SET masach=?, tensach=?, tacgia=?, namxb=?, trangthai=?, macn=? WHERE id=?";
        if ($conn->connect_errno) {
            echo "Failed to connect: " . $conn->connect_error;
            exit();
        }

        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)) {
            $stmt->bind_param("sssiisi", $masach,  $tensach, $tacgia, $namxb, $trangthai, $macn, $id);

            if ($stmt->execute()) {
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
        $conn->close();
    } else {
        $sql = "SELECT macn, tencn FROM chuyennganh ORDER BY tencn";
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
                <h2 class="mt-5">Update Book: <?php echo $tensach; ?></h2>
                <p>Please edit the input values and submit to update the book.</p>
                <form action="/ASM/Book/update.php" method="post">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="@$%"
                               value="<?php echo $id; ?>"></div>
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text"  name="newmasach" class="form-control
<?php echo (!empty($masach_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $masach; ?>">
                        <span class="invalid-feedback"><?php echo $masach_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="newtensach" class="form-control
<?php echo (!empty($tensach_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tensach; ?>">
                        <span class="invalid-feedback"><?php echo $tensach_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Actor</label>
                        <input type="text" name="newtacgia" class="form-control
<?php echo (!empty($tacgia_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tacgia; ?>">
                        <span class="invalid-feedback"><?php echo $tacgia_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Publish Year</label>
                        <input type="number" name="newnamxb"
                               class="form-control <?php echo (!empty($namxb_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $namxb; ?>">
                        <span class="invalid-feedback"><?php echo $namxb_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-select" name="newtrangthai">
                            <option value="1" <?php if ($trangthai == 1) echo 'selected' ?>>Published</option>
                            <option value="0" <?php if ($trangthai == 0) echo 'selected' ?>>Not Publish</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Specialization</label>
                        <select class="form-select" name="macnSelect">
                            <?php
                            foreach ($listSpec as $key => $value)
                                if ($macn == $value[0]) {
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