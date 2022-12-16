<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=REPORT_DEPOSIT".date('dmYHi').".xls");

echo $html;
?>