<?php

include "db.php";

function SEOLink($baslik)
{
    $metin_aranan = array("ş", "Ş", "ı", "ü", "Ü", "ö", "Ö", "ç", "Ç", "ş", "Ş", "ı", "ğ", "Ğ", "İ", "ö", "Ö", "Ç", "ç", "ü", "Ü");
    $metin_yerine_gelecek = array("s", "S", "i", "u", "U", "o", "O", "c", "C", "s", "S", "i", "g", "G", "I", "o", "O", "C", "c", "u", "U");
    $baslik = str_replace($metin_aranan, $metin_yerine_gelecek, $baslik);
    $baslik = preg_replace("@[^a-z0-9\-_şıüğçİŞĞÜÇ]+@i", "-", $baslik);
    $baslik = strtolower($baslik);
    $baslik = preg_replace('/&.+?;/', '', $baslik);
    $baslik = preg_replace('|-+|', '-', $baslik);
    $baslik = preg_replace('/#/', '', $baslik);
    $baslik = str_replace('.', '', $baslik);
    $baslik = str_replace('“', '', $baslik);
    $baslik = str_replace('”', '', $baslik);
    $baslik = mb_convert_encoding($baslik, 'UTF-8', 'UTF-8');
    $baslik = str_replace('?', '', $baslik);
    $baslik = trim($baslik, '-');
    return $baslik;
}
?>

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
                        move_uploaded_file($gecici_isim, "gecici/" . $isim);

                        include_once "excel.php";

                        // File Upload Control
                        if ($xlsx = SimpleXLSX::parse("gecici/" . $isim)) {

                            // Excel Convert to VT 
                            foreach ($xlsx->rows() as $key => $satir) {
 
                                $kod = $satir[0];
                                $kursadi = $satir[1];
                                $kategori = $satir[2];
                                $saat = $satir[3];

                                // Seo Link Oluşturma, Vt de Aynısı Varsa 2. bir tane oluşturuyor 
                                $seo = SEOLink($kursadi);

                                $seosor = $db->prepare("SELECT * FROM sayfa WHERE seo = ?");
                                $seosor->execute(array($seo));

                                // Seo Link Oluşturuluyor
                                $count = $seosor->rowCount();
                                if ($count != 0) {
                                    $rand = rand(10, 100);
                                    $seo =   $seo . $rand;

                                    $seosor = $db->prepare("SELECT * FROM sayfa WHERE seo = ?");
                                    $seosor->execute(array($seo));
                                    $count = $seosor->rowCount();

                                    if ($count != 0) {
                                        echo "Seo 0'a Eşit Değil !";
                                    }
                                }


                                $katsor = $db->prepare("SELECT * FROM kategori WHERE kategori_adi = ? ");
                                $katsor->execute([$kategori]);
                                $katyaz = $katsor->fetch(PDO::FETCH_ASSOC);
                                $katid = $katyaz['id'];

                                //echo '<br> Kod : ' . $kod . 'kursadi : ' . $kursadi . 'kategori : ' . $katid  . ' saat : ' . $saat;

                                // Excel Row Control
                                if (count($satir) >= 1) {
                                    $sorgu = $db->prepare("INSERT INTO sayfa SET
                                        sayfa_id = :sayfa_id,
                                        baslik = :baslik,
                                        on_yazi = :on_yazi,
                                        kategori = :kategori,
                                        kodu = :kodu,
                                        dil = :dil,
                                        seo = :seo
                                    ");
                                    $sorgu->execute([
                                        'sayfa_id' => 109,
                                        'baslik' => $kursadi,
                                        'on_yazi' => $kod, 
                                        'kategori' => $katid,
                                        'kodu' => $saat,
                                        'dil' => 'tr',
                                        'seo' => $seo,
                                    ]);


                                    if ($sorgu) {
                                        echo "başarı ile eklendi";
                                    } else {
                                        echo "eklerken sorun olutşu";
                                        echo '<br> Kod : ' . $kod . 'kursadi : ' . $kursadi . 'kategori : ' . $katid  . ' saat : ' . $saat;
                                    } 

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
                <a href="core/excek-export.php" class="btn btn-danger p-1">
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

                    $ogrenciler = $db->prepare("SELECT * FROM sayfa WHERE sayfa_id = 109 ");
                    $ogrenciler->execute(array());

                    while ($ogrenciyaz = $ogrenciler->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <tr>
                            <th scope="row"><?php echo $ogrenciyaz['id'] ?></th>
                            <td><?php echo $ogrenciyaz['baslik'] ?></td>
                            <td><?php echo $ogrenciyaz['kategori'] ?></td>
                            <td><?php echo $ogrenciyaz['on_yazi'] ?></td>
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