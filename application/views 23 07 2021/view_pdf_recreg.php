<?php
header("Content-type: application/pdf");
header("Content-Disposition: attachment; filename=REPORT_BATCH".date('dmYHi').".pdf");

echo @$html;
?>