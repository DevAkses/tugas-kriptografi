<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steganografi - Enkripsi dan Dekripsi Pesan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <h1 class="mx-auto">Steganografi - Enkripsi dan Dekripsi Pesan</h1>
        </div>
    </nav>
    <div class="container mt-0 mb-5">
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <h2>Enkripsi Pesan</h2>
                <form action="encrypt.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="upload_image">Upload Gambar</label>
                        <input type="file" class="form-control" id="upload_image" name="image" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Pesan yang akan dienkripsi</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="key_encrypt">Kunci Enkripsi</label>
                        <input type="text" class="form-control" id="key_encrypt" name="key" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enkripsi Pesan</button>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <h2>Dekripsi Pesan</h2>
                <form action="decrypt.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="upload_image_decrypt">Upload Gambar dengan Pesan</label>
                        <input type="file" class="form-control" id="upload_image_decrypt" name="image" required>
                    </div>
                    <div class="form-group">
                        <label for="key_decrypt">Kunci Dekripsi</label>
                        <input type="text" class="form-control" id="key_decrypt" name="key" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Dekripsi Pesan</button>
                </form>
                <hr>
                <h2>Dekripsi Pesan dari Base64 String</h2>
                <form action="decrypt.php" method="POST" class="mb-4">
                        <div class="form-group">
                        <label for="base64_image">String Base64 Gambar dengan Pesan</label>
                        <textarea class="form-control" id="base64_image" name="base64_image" rows="4"
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="key_base64">Kunci Dekripsi</label>
                        <input type="text" class="form-control" id="key_base64" name="key" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Dekripsi Pesan dari Base64</button>
                </form>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <p>&copy; 2024 Steganografi - Enkripsi dan Dekripsi Pesan. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
