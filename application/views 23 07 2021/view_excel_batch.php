<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=REPORT_BATCH".date('dmYHi').".xls");

echo $html;
?>