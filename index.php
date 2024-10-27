<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <title>Rekomendasi Film</title>
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="card p-4 shadow-sm">
            <h1 class="text-center">REKOMENDASI FILM</h1>
            <form method="get" action="">
                <div class='d-flex align-items-end'>
                    <div class="me-4">
                        <label for="sort" class="form-label">Urutkan Berdasarkan:</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="rating">Rating</option>
                            <option value="popularitas">Popularitas</option>
                            <option value="tahun_rilis">Tahun Rilis</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Urutkan</button>
                </div>
            </form>

            <form method="GET" action="" class="mb-4">
                <div class="d-flex align-items-end">
                    <div class="me-3">
                        <label for="genre" class="form-label">Pilih Genre:</label>
                        <select name="genre" id="genre" class="form-select">
                            <option value="">Semua Genre</option>
                            <option value="Action">Aksi</option>
                            <option value="Drama">Drama</option>
                            <option value="Komedi">Komedi</option>
                            <option value="Horror">Horror</option>
                            <option value="Sci-Fi">Sci-Fi</option>
                            <option value="Animation">Animation</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </form>

            <?php
            // Menghubungkan ke database
            $conn = new mysqli("localhost", "root", "", "rekomendasi_film");
            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM film";
            $result = $conn->query($sql);
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            $conn->close();

            // Fungsi quickSort
            function quickSort($data, $key)
            {
                if (count($data) < 2) {
                    return $data;
                }
                $left = $right = [];
                $pivot = $data[0];
                for ($i = 1; $i < count($data); $i++) {
                    if ($data[$i][$key] > $pivot[$key]) {
                        $left[] = $data[$i];
                    } else {
                        $right[] = $data[$i];
                    }
                }
                return array_merge(
                    quickSort($left, $key),
                    [$pivot],
                    quickSort($right, $key)
                );
            }

            // Fungsi filterByGenre
            function filterByGenre($data, $genre)
            {
                if (empty($genre)) {
                    return $data; // Jika tidak ada genre dipilih, kembalikan semua data
                }
                return array_filter($data, function ($item) use ($genre) {
                    return $item['genre'] === $genre;
                });
            }

            // Memeriksa apakah ada parameter 'sort' dan 'genre' yang dikirimkan
            $kriteria = isset($_GET['sort']) ? $_GET['sort'] : 'rating';
            $kriteriaText = ucfirst($kriteria);
            $selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : '';

            $data = quickSort($data, $kriteria);
            $filteredData = filterByGenre($data, $selectedGenre);

            if (empty($filteredData)) {
                echo "<div class='alert alert-warning'>Tidak ada data untuk diurutkan.</div>";
            } else {
                echo "<h2 class='mb-4'>Daftar Film</h2>";
                echo "<p>Menampilkan hasil yang diurutkan berdasarkan: <strong>" . $kriteriaText . "</strong></p>";
                echo "<p>Menampilkan hasil untuk genre: <strong>" . (!empty($selectedGenre) ? htmlspecialchars($selectedGenre) : 'Semua Genre') . "</strong></p>";
                echo "<table class='table table-striped table-bordered'>";
                echo "<thead class='table-dark'>";
                echo "<tr><th class ='text-center align-middle'>Judul</th><th class ='text-center align-middle'>Gambar</th><th class ='text-center align-middle'>Rating</th><th class ='text-center align-middle'>Popularitas</th><th class ='text-center align-middle'>Tahun Rilis</th></tr>";
                echo "</thead>";
                echo "<tbody>";
                if (!empty($filteredData)) {
                    foreach ($filteredData as $item) {
                        echo "<tr>";
                        echo "<td class='align-middle text-center'>" . htmlspecialchars($item['judul']) . "</td>";
                        echo "<td class='align-middle text-center'><img src = 'img/" . htmlspecialchars($item['gambar']) . "' alt='" . htmlspecialchars($item["judul"]) . "' style='width:100px; height:auto;'></td>";
                        echo "<td class='align-middle text-center'>" . htmlspecialchars($item['rating']) . "</td>";
                        echo "<td class='align-middle text-center'>" . htmlspecialchars($item['popularitas']) . "</td>";
                        echo "<td class='align-middle text-center'>" . htmlspecialchars($item['tahun_rilis']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='4'>Tidak ada data untuk ditampilkan.</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
</body>

</html>