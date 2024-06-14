<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function encrypt_aes($plaintext, $key) {
    $iv_size = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($iv_size);
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $ciphertext);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $key = $_POST['key'];
    $image = $_FILES['image'];

    if ($image['error'] != UPLOAD_ERR_OK) {
        die('Error during file upload: ' . $image['error']);
    }

    $image_info = getimagesize($image['tmp_name']);
    if ($image_info === false || $image_info[2] != IMAGETYPE_PNG) {
        die('Only PNG images are supported.');
    }

    $encrypted_message = encrypt_aes($message, $key);

    $img = imagecreatefrompng($image['tmp_name']);
    if (!$img) {
        die('Failed to read the image.');
    }

    $width = imagesx($img);
    $height = imagesy($img);
    $message_length = strlen($encrypted_message);
    $total_bits_needed = ($message_length + 1) * 8; 
    if ($total_bits_needed > $width * $height) {
        die('Message is too long to fit in this image.');
    }

    $binary_message = '';
    for ($i = 0; $i < $message_length; $i++) {
        $binary_message .= str_pad(decbin(ord($encrypted_message[$i])), 8, '0', STR_PAD_LEFT);
    }
    $binary_message .= '00000000';

    $message_pos = 0;
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($message_pos < strlen($binary_message)) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $b = ($b & 0xFE) | $binary_message[$message_pos];
                $color = imagecolorallocate($img, $r, $g, $b);
                imagesetpixel($img, $x, $y, $color);
                $message_pos++;
            }
        }
    }

    $upload_dir = 'upload/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $output = $upload_dir . 'encrypted_image.png';
    if (imagepng($img, $output)) {
        echo "Image saved successfully. <a href='$output' download>Download Gambar</a><br>";

        $image_data = file_get_contents($output);
        $base64_image = base64_encode($image_data);
        echo "Base64 Encoded Image:<br>";
        echo "<textarea readonly rows='10' cols='50'>$base64_image</textarea>";
    } else {
        echo "Failed to save the image.";
    }
    imagedestroy($img);
} else {
    echo "Invalid request.";
}
?>
