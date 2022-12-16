<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=".$file_name.date('dmYHi').".xls");

echo $html;
?>