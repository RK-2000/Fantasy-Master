############################
PHP - PDF(TCPDF) Setup Guide
############################

Step 1 - Require tcpdf library

require APPPATH.'/libraries/pdf/tcpdf/PDF.php';

Step 2 - Create object

$pdfObj = new PDF();

******************
To create pdf file
******************

Method - $pdfObj->downloadPDF(html,filename,title,isDownloadable);

Required parameters - html

Optional parameters - filename,title,isDownloadable

Note:- 

1) filename - downloaded pdf file name
2) title    - downloaded pdf file title
3) isDownloadable - (0 - View PDF file, 1 - Download PDF file)
