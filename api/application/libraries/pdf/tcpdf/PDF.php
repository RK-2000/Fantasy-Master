<?php

/**
 * PHP PDF(TCPDF) class
 * @package   PHP
 * @author    Sorav Garg
 */

/* Load tcpdf class */
require APPPATH.'/libraries/pdf/tcpdf/tcpdf.php';

class PDF
{

    function __construct()
    {

    }

    /**
     * [To download pdf file]
     * @param string $html
     * @param string $filename
     * @param string $Title
     * @param integer $isDownloadable
    */
    function downloadPDF($html,$filename = 'pdf-file',$Title = 'PDF',$isDownloadable = 0)
    {
        set_time_limit(0);
        ini_set('memory_limit', '640M');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($Title);
        $pdf->SetTitle($Title);
        $pdf->SetSubject($Title);
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->setImageScale(2.0);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
          require_once(dirname(__FILE__).'/lang/eng.php');
          $pdf->setLanguageArray($l);
        }
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();
        if($isDownloadable == 0){
          $pdf->Output($filename);
        }else{
          $pdf->Output($filename,'F');
        }
    }


}


?>