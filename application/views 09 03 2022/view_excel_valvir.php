<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=VALIDATE_KURSET_VIRTUAL".date('dmYHi').".xls");

echo @$html;
?>