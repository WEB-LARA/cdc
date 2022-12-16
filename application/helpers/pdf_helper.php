<?php
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class pdf_helper extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
	
	function tcpdf()
	{
		require_once('tcpdf/config/lang/eng.php');
		require_once('tcpdf/tcpdf.php');
	}
}
?>