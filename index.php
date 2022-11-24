<?php include "db.php"; ?>

<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Excel Upload</title>

    <!-- Table Data -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="asset/css/datatables.min.css">
    <!-- Style Css -->
    <link rel="stylesheet" href="asset/css/datatables.min.css">
    <!-- Table data JS -->
    <script src="asset/js/datatables.min.js"></script>

</head>

<body>

    <div class="container py-5">

        <!-- Upload Alert Row -->
        <div class="row">
            <div class="col-md-12">

                <?php

                use Shuchkin\SimpleXLSX;

                if (isset($_POST['yukle'])) {

                    // File Upload
                    if ($_FILES['excel_file']['error'] == 0) {
                        $gecici_isim  = $_FILES['excel_file']['tmp_name'];
                        $dosya_ismi = $_FILES['excel_file']['name'];
                        $sayi = rand(1000, 9999);
                        $isim = $sayi . $dosya_ismi;
                        move_uploaded_file($gecici_isim, "gecici/isim");

                        include_once "excel.php";

                        // File Upload Control
                        if ($xlsx = SimpleXLSX::parse("gecici/isim")) {

                            // Excel Convert to VT
                            foreach ($xlsx->rows() as $key => $satir) {

                                // Over Colum Control
                                if (count($satir) >= 3) {
                                    $sorgu = $db->prepare(" INSERT INTO ogrenciler SET
                                    isim = :isim,
                                    mail = :mail,
                                    Telefon = :Telefon
                                    ");
                                    $sorgu->execute([
                                        'isim' => $satir[0],
                                        'mail' => $satir[1],
                                        'Telefon' => $satir[2]
                                    ]);
                                } else {
                                    echo "Boş Ve hatalı Excel lütfen tekrardan deneyiniz";
                                }

                            }

                        } else {
                            echo SimpleXLSX::parseError(); // başarısız
                        }
                    }
                }

                ?>

            </div>
        </div>

        <!-- Excel button -->
        <div class="row mb-3">
            <div class="col-md-12 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    EXCEL UPLOAD
                </button>
            </div>
        </div>

        <!-- List Table -->
        <div class="row">
            <table class="table" id="myTable">
                <thead class="bg-dark text-white">
                    <tr>
                        <th scope="col">Table</th>
                        <th scope="col">KODU</th>
                        <th scope="col">RENKLER</th>
                        <th scope="col">DEĞER</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $ogrenciler = $db->prepare("SELECT * FROM  ogrenciler ");
                    $ogrenciler->execute(array());

                    while ($ogrenciyaz = $ogrenciler->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <tr>
                            <th scope="row"><?php echo $ogrenciyaz['id'] ?></th>
                            <td><?php echo $ogrenciyaz['isim'] ?></td>
                            <td><?php echo $ogrenciyaz['mail'] ?></td>
                            <td><?php echo $ogrenciyaz['Telefon'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Upload Excel -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Excel Upload</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="excel_file" class="form-control">
                        <button class="btn btn-primary mt-3 w-50 mx-auto d-block" name="yukle">YÜKLE</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Çıkış</button>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>


</body>

</html>