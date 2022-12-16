<?php 
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename(@$file_name).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile(@$file_name);
exit;
?>