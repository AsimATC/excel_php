<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Table</title>

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

        <div class="row mb-3">
            <div class="col-md-12 text-end">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    EXCEL UPLOAD
                </button>
            </div>


        </div>

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
                    <tr>
                        <th scope="row">1</th>
                        <td>Asım</td>
                        <td>Atıcı</td>
                        <td>Asim@tonyukukajans.com</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Mehmet</td>
                        <td>Yoldakalan</td>
                        <td>Mehmet@tonyukukajans.com</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Excel Upload</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="file" class="form-control">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" name="yukle" class="btn btn-warning" data-bs-dismiss="modal">Çıkış</button>
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