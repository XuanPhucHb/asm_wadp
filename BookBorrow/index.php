<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            width: 800px;
            margin: 0 auto;
        }

        table tr td:last-child {
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="mt-5 mb-3 clearfix">
                    <h2 class="pull-left">Borrow List</h2>
                    <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New
                        Borrow Order</a>
                </div>
                <?php
                require_once "../Utils/connection_db.php";

                $sql = "SELECT a.*, b.mabd, b.sdt, b.tenbd FROM muonsach a JOIN bandoc b ON a.bd_id = b.id 
                                                JOIN muonsach_chitiet c ON a.id = c.muonsach_id GROUP BY a.id";
                if ($result = mysqli_query($conn, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th style='text-align: center'>Reader Code</th>";
                        echo "<th style='text-align: center'>Reader Name</th>";
                        echo "<th style='text-align: center'>Reader Phone</th>";
                        echo "<th style='text-align: center'>Borrow Date</th>";
                        echo "<th style='text-align: center'>Expired Date</th>";
                        echo "<th style='text-align: center'>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td style='text-align: center'>" . $row['mabd'] . "</td>";
                            echo "<td>" . $row['tenbd'] . "</td>";
                            echo "<td style='text-align: center'>" . $row['sdt'] . "</td>";
                            echo "<td style='text-align: center'>" . date_format(date_create($row['ngaymuon']),"d/m/Y") . "</td>";
                            echo "<td style='text-align: center'>" . date_format(date_create($row['ngaytra']),"d/m/Y") . "</td>";
                            echo "<td align='center'>";
                            echo '<a href="read.php?id=' . $row['id'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?id=' . $row['id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?id=' . $row['id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                $conn->close();;
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>