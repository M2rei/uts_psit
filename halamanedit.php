<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nilai Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"] {
            width: calc(100% - 10px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Nilai Mahasiswa</h1>
        <?php
        // Ambil nim, kode_mk, dan nama_mk 
        $nim = $_GET['nim'];
        $kode_mk = $_GET['kode_mk'];

        // URL untuk mengambil data mahasiswa berdasarkan nim dan kode_mk
        $url = 'http://localhost/uts_psit/api.php?nim=' . $nim . '&kode_mk=' . $kode_mk;

        // Mengambil data mahasiswa dari API
        $response = file_get_contents($url);

        // Menangani respons JSON
        $data = json_decode($response, true);

        // Memeriksa jika data berhasil diambil
        if ($data['status'] == 1) {
            $nilai = $data['data'][0]['nilai'];
            $kode_mk = $data['data'][0]['kode_mk'];
            $nim_mahasiswa = $data['data'][0]['nim'];

            // Form untuk mengedit nilai mahasiswa
            echo '<form method="POST" action="editdata.php">';
            echo '<input type="hidden" name="nim" value="' . $nim . '">';
            echo '<input type="hidden" name="kode_mk" value="' . $kode_mk . '">';
            echo '<label for="nim_mahasiswa">NIM Mahasiswa</label>';
            echo '<input type="text" id="nim_mahasiswa" name="nim_mahasiswa" value="' . $nim_mahasiswa . '" readonly>';
            echo '<label for="kode_mk">Kode Mata Kuliah</label>';
            echo '<input type="text" id="kode_mk" name="kode_mk" value="' . $kode_mk . '" readonly>';
            echo '<label for="nilai">Nilai</label>';
            echo '<input type="text" id="nilai" name="nilai" value="' . $nilai . '">';
            echo '<input type="submit" name="submit" value="Update">';
            echo '</form>';
        } else {
            // Jika data tidak berhasil diambil
            echo '<p class="message">Gagal mengambil data nilai mahasiswa.</p>';
        }
        ?>
    </div>
</body>
</html>
