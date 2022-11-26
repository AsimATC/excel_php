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

                                // Excel Row Control
                                if (count($satir) >= 1) {
                                    $sorgu = $db->prepare(" INSERT INTO ogrenciler SET
                                    kodu = :kodu,
                                    durum = :durum
                                    ");
                                    $sorgu->execute([
                                        'kodu' => $satir[0],
                                        'durum' => "off",
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
                <a  href="core/excek-export.php" class="btn btn-danger p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <line x1="12" y1="11" x2="12" y2="17" />
                        <polyline points="9 14 12 17 15 14" />
                    </svg>
            </a>
                <a type="button" class="btn btn-success p-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <line x1="12" y1="11" x2="12" y2="17" />
                        <polyline points="9 14 12 11 15 14" />
                    </svg>
            </a>
            </div>
        </div>

        <!-- List Table -->
        <div class="row">
            <table class="table" id="myTable">
                <thead class="bg-dark text-white">
                    <tr>
                        <th scope="col">Table</th>
                        <th scope="col">İsim</th>
                        <th scope="col">Soyisim</th>
                        <th scope="col">Mail</th>
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