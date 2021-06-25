<?php
// Include config file
require_once "../Utils/connection_db.php";

$id = 0;
$idsachSelect = [];
$idbdSelect = 0;
$borrowDate = $expiredDate = "";
$borrowDate_err = $expiredDate_err = "";
$listReader = [];
$listBook = [];
if (isset($_GET['id'])) {
    $listReader = getListBanDoc($conn);
    $listBook = getListSach($conn);
    $id = $_GET["id"];
    $sql = "SELECT a.*, b.ngaymuon, b.ngaytra, b.bd_id FROM muonsach_chitiet a JOIN muonsach b ON a.muonsach_id = b.id WHERE muonsach_id = $id GROUP by a.id";
    $listBorrow_ed = [];
    if ($result = mysqli_query($conn, $sql)) {
        $i = 0;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $listBorrow_ed[$i] = [$row['id'], $row['muonsach_id'], $row['sach_id']];
                $borrowDate = $row['ngaymuon'];
                $expiredDate = $row['ngaytra'];
                $idbdSelect = $row['bd_id'];
                $i++;
            }
        }
    }
    for ($i = 0; $i < sizeof($listBook); $i++) {
        for ($j = 0; $j < sizeof($listBorrow_ed); $j++) {
            if ($listBorrow_ed[$j][2] == $listBook[$i][0]){
                $listBook[$j][5] = 'checked';
            }
        }
    }
} else {
    $id = $_POST["@$%"];
    $idbdSelect = $_POST["idbdSelect"];
    $input_borrowDate = $_POST["borrowDate"];
    $idsachSelect = $_POST["idsachSelect"];

    if (empty($input_borrowDate)) {
        $borrowDate_err = "Please enter or pick borrow date.";
    } else if (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/", $input_borrowDate) === 0) {
        $borrowDate_err = "Borrow date invalid";
    } else {
        $borrowDate = $input_borrowDate;
        $input_expiredDate = trim($_POST["expiredDate"]);
        if (empty($input_expiredDate)) {
            $expiredDate_err = "Please enter expired date.";
        } else if (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/", $input_expiredDate) === 0) {
            $expiredDate_err = "Expired date invalid";
        } else {
            $expiredDate = $input_expiredDate;

            if ($borrowDate > $expiredDate) {
                $expiredDate_err = "Expired date must be greater than Borrow date";
            }
        }
    }
    if (empty($borrowDate_err) && empty($expiredDate_err)) {
        $sql1 = "DELETE FROM muonsach_chitiet WHERE muonsach_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql1)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id1);
            $param_id1 = $id;
            if (mysqli_stmt_execute($stmt)) {
                for($i = 0; $i < sizeof($idsachSelect); $i++){
                    $sql = "INSERT INTO muonsach_chitiet (muonsach_id, sach_id) VALUES ('$id', '$idsachSelect[$i]')";
                    if (!$conn->query($sql)) {
                        echo "Oops! Something went wrong. Please try again later.";
                        exit();
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
            updateBorrow($conn, $borrowDate, $expiredDate, $idbdSelect, $id);
        }
    } else {
        $listReader = getListBanDoc($conn);
        $listBook = getListSach($conn);
    }
}

function getListBanDoc(mysqli $conn)
{
    $sql = "SELECT * FROM bandoc ORDER BY tenbd";
    if ($result = mysqli_query($conn, $sql)) {
        $i = 0;
        if (mysqli_num_rows($result) > 0) {
            $listReader = [];
            while ($row = mysqli_fetch_array($result)) {
                $listReader[$i] = [$row['id'], $row['mabd'], $row['tenbd'], $row['sdt']];
                $i++;
            }
            return $listReader;
        }
    }
}

function getListSach(mysqli $conn)
{
    $sql = "SELECT a.*, b.tencn FROM sach a JOIN chuyennganh b ON a.macn = b.macn ORDER BY tensach";
    if ($result = mysqli_query($conn, $sql)) {
        $i = 0;
        if (mysqli_num_rows($result) > 0) {
            $listBook = [];
            while ($row = mysqli_fetch_array($result)) {
                $listBook[$i] = [$row['id'], $row['masach'], $row['tensach'], $row['tacgia'], $row['tencn']];
                $i++;
            }
            return $listBook;
        }
    }
}

function updateBorrow(mysqli $conn, $borrowDate, $expiredDate, $idbdSelect, $id){
    $borrowDate = date_format(date_create($borrowDate),"Y-m-d");
    $expiredDate = date_format(date_create($expiredDate),"Y-m-d");
    $sql2 = "UPDATE muonsach SET bd_id=?, ngaymuon=?, ngaytra=? WHERE id=?";
    if ($conn->connect_errno) {
        echo "Failed to connect: " . $conn->connect_error;
        exit();
    }
    $stmt = mysqli_prepare($conn, $sql2);
    echo $idbdSelect;
    echo $borrowDate;
    echo $expiredDate;
    echo $id;
    $stmt->bind_param("issi", $idbdSelect, $borrowDate, $expiredDate, $id);
    if ($stmt->execute()) {
        header("location: index.php");
        exit();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
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
                <h2 class="mt-5">Update Book Borrow</h2>
                <p>Please edit the input values and submit to update the borrow.</p>
                <form action="/ASM/BookBorrow/update.php" method="post">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="@$%"
                               value="<?php echo $id; ?>"></div>
                    <div class="form-group">
                        <label><b>Reader (Code - Name - Phone):</b></label>
                        <div class="form-group">
                            <select class="form-select" name="idbdSelect">
                                <?php
                                echo sizeof($listReader);
                                foreach ($listReader as $key => $value)
                                    if ($value[0] == $idbdSelect) {
                                        echo "<option value='$value[0]' selected>$value[1] - $value[2] - $value[3]</option>";
                                    } else {
                                        echo "<option value='$value[0]'>$value[1] - $value[2] - $value[3]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><b>Borrow Date (month/day/year)</b></label>
                        <div class="md-form md-outline input-with-post-icon datepicker
                        <?php echo (!empty($borrowDate_err)) ? 'is-invalid' : ''; ?>">
                            <input name="borrowDate" id="datepicker" width="50%" value="<?php echo date_format(date_create($borrowDate),"m/d/Y"); ?>"/>
                        </div>
                        <span class="invalid-feedback"><?php echo $borrowDate_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label><b>Expired Date (month/day/year)</b></label>
                        <div class="md-form md-outline input-with-post-icon datepicker
                        <?php echo (!empty($expiredDate_err)) ? 'is-invalid' : ''; ?>">
                            <input name="expiredDate" id="datepicker2" width="50%" value="<?php echo date_format(date_create($expiredDate),"m/d/Y"); ?>"/>
                        </div>
                        <span class="invalid-feedback"><?php echo $expiredDate_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label><b>Book (Code - Name - Actor - Specialization):</b></label>
                        <div class="form-group">
                            <select id="selectBook" class="form-select" name="idsachSelect[]" multiple>
                                <?php
                                foreach ($listBook as $key => $value)
                                    if ($value[5] != null) {
                                        echo "<option value='$value[0]' selected>$value[1] - $value[2] - $value[3] - $value[4]</option>";
                                    } else {
                                        echo "<option value='$value[0]'>$value[1] - $value[2] - $value[3] - $value[4]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $(document).ready(function () {
        $('#selectBook').multiselect({});
    });
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        dateFormat: "yyyy-mm-dd"
    });
    $('#datepicker2').datepicker({
        uiLibrary: 'bootstrap4',
        dateFormat: "yyyy-mm-dd"
    });
</script>
</html>