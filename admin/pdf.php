<?php
require "vendor/autoload.php";

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$html = "<h1>Hello World</h1>";

$dompdf->loadHtml($html);

$dompdf->render();

$dompdf->stream("output.pdf", array('Attachment' => false));


?>