<?php
    require_once('phpqrcode/qrlib.php');

    QRcode::png('code data text', 'qr_codes_images/filename.png');
?>