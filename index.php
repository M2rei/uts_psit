<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Mahasiswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-top: 30px;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .action-buttons {
            display: flex;
            justify-content: space-around;
        }

        .action-buttons button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .action-buttons button.edit {
            background-color: #4CAF50;
            color: white;
        }

        .action-buttons button.delete {
            background-color: #f44336;
            color: white;
        }

      
        .add-button {
            text-align: center;
            margin-top: 20px;
        }

        .add-button a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .add-button a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Nilai Mahasiswa</h1>
    <table id="nilai_table">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Tanggal Lahir</th>
                <th>Kode Matakuliah</th>
                <th>Nama Matakuliah</th>
                <th>SKS</th>
                <th>Nilai</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil data dari API
            $data = file_get_contents('http://localhost/uts_psit/api.php');
            $data = json_decode($data, true);

            // Cek apakah data berhasil diambil
            if ($data['status'] == 1) {
                foreach ($data['data'] as $item) {
                    echo '<tr>';
                    echo '<td>' . $item['nim'] . '</td>';
                    echo '<td>' . $item['nama'] . '</td>';
                    echo '<td>' . $item['alamat'] . '</td>';
                    echo '<td>' . $item['tanggal_lahir'] . '</td>';
                    echo '<td>' . $item['kode_mk'] . '</td>';
                    echo '<td>' . $item['nama_mk'] . '</td>';
                    echo '<td>' . $item['sks'] . '</td>';
                    echo '<td>' . $item['nilai'] . '</td>';
                    echo '<td class="action-buttons">';
                    echo '<button class="edit" onclick="location.href=\'halamanedit.php?nim=' . $item['nim'] . '&kode_mk=' . $item['kode_mk'] . '\'">Edit</button>';
                    echo '<button class="delete" onclick="deleteData(\'' . $item['nim'] . '\', \'' . $item['kode_mk'] . '\')">Delete</button>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="9">Gagal mengambil data: ' . $data['message'] . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <div class="add-button">
        <a href="halamanaddnilai.php">Add Nilai</a>
    </div>

    <script>
       
        function deleteData(nim, kode_mk) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                // Kirim permintaan delete menggunakan AJAX
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Refresh halaman jika berhasil dihapus
                        window.location.reload();
                    }
                };
                xhttp.open("GET", "deletedata.php?nim=" + nim + "&kode_mk=" + kode_mk, true);
                xhttp.send();
            }
        }
    </script>
</body>
</html>
