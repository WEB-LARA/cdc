<?php 
header('Content-Type: application/vnd-ms-excel');
header('Content-Disposition: attachment; filename=Sheet1.csv');
echo @$html;

?>