<?php
ob_start();
$currentDate = date("Y-m-d");
require_once('tcpdf_include.php');
$conn = new mysqli('localhost', 'root', '','superttr_word241');
if(isset($_GET['did'])){
$sql = "DELETE FROM multiform WHERE id=".$_GET['did'];
if ($conn->query($sql) === TRUE) {
    
    header('Location: http://www.superttransport.com/wp-admin/admin.php?page=driver-info');
} else {
    echo "Error deleting record: " . $conn->error;die;
}
}
else{
$id = $_GET['id'];
$sql = 'SELECT * FROM multiform where id='.$id;
$result = $conn->query($sql);
if ($result->num_rows > 0)
{
    $row = $result->fetch_assoc();
 $dynamicFieldArray= (array) json_decode($row['dynamicFieldArray']);
//echo "<pre>";  print_r(json_decode($row['dynamicFieldArray'])); die;
}
$workedcompanyStatus="";
$workedcompanyStatusfalse="";
$terminatedStatus="";
$terminatedStatusfalse="";
$convictedFelony="";
$convictedFelonyfalse="";
 if($row["f1_workedcompanyStatus"]=="on")
    { $workedcompanyStatus = '<img src="images/right.png" >'; }else
    {
       $workedcompanyStatusfalse = '<img src="images/right.png" >';
    }
    
    if($row["f1_terminatedStatus"]=="on")
    { $terminatedStatus= '<img src="images/right.png">'; }
     else
    {
       $terminatedStatusfalse = '<img src="images/right.png">';
    }
    
    if($row["f1_convictedFelony"]=="on"){
    $convictedFelony= '<img src="images/right.png">'; } else
      {
          $convictedFelonyfalse= '<img src="images/right.png">';                                                     
      }
    
     if($row["f6_employmentinCalifornia"]=="on")
    { $employmentinCalifornia= '<img src="images/right.png">'; }
     else{
          $employmentinCalifornia= '<img src="images/wrong.png">';
        }
    
     if($row["f6_employmentinOklahoma"]=="on")
    { $employmentinOklahoma= '<img src="images/right.png" >'; }  
    else{
          $employmentinCalifornia= '<img src="images/wrong.png">';
        }
     
    if($row["f6_minnesota"]=="on")
    { $minnesota= '<img src="images/right.png">'; }
    else{
          $employmentinCalifornia= '<img src="images/wrong.png">';
     }

	/*dynamic portion for second page*/
	if($row['f2_anyViolation'] == "1")
        {
			$f2_dynamic1 = '<table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
                      <tbody>
                        <tr>
                            <td style="width:650px;">Any traffic violation conviction in the last 3 Years if (Yes): 
                               <img src="images/right.png" height="30px" width="30px"></td>
                            <td style="padding-right: 8px;width:50px;"></td>
                            <td style="width: 500px;">No</td>
                            </tr>
                         </tbody>
                            </table>';
		
           if(!empty($dynamicFieldArray['f2_datetrafficViolation']) && count($dynamicFieldArray['f2_datetrafficViolation']) > 0)
            {
                for($i=0;$i<count($dynamicFieldArray['f2_datetrafficViolation']);$i++)
                {
                  $f2_dynamic1.= '<table cellpadding="5" cellspacing="0" border="0" style="width:1100px;margin-bottom: 25px;">
                        <tbody>
                            <tr>
                              <td style="width: 60px;">Date </td>
                              <td style="padding-right:15px;width: 150px;border-bottom: solid 1px #696969;">
                               &nbsp;<label>'.$dynamicFieldArray['f2_datetrafficViolation'][$i].'</label></td>
                              <td style="width: 100px;">Violation</td>	
                              <td style="padding-right:15px;width: 150px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<label>'.$dynamicFieldArray['f2_violation'][$i].'</label></td>
                              <td style="width:120px;">State</td>
                              <td style="width: 154px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<label>'.$dynamicFieldArray['f2_trafficviolationState'][$i].'</label>
                              </td>
                              <td style="width: 80px;">CMV</td>
                              <td style="width: 150px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<label>'.$dynamicFieldArray['f2_trafficviolationCmv'][$i].'</label>
                              </td>
                            </tr>
                        </tbody>
                    </table>';
                }
            }
        }
        else
        {
            $f2_dynamic1 = '<table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
                      <tbody>
                        <tr>
                            <td style="width: 500px;">Any traffic violation convictions in the last 3 years? Yes </td>
                            <td style="padding-right: 8px;width:50px;"></td>
                            <td style="width: 500px;">No &nbsp;
                               <img src="images/right.png" height="30px" width="30px"></td>
                            </tr>
                         </tbody>
                            </table>';
        }

	
	if($row['f2_anyAccident'] == "1")
        {
			$f2_dynamic2 = '<table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
                      <tbody>
                        <tr>
                            <td style="width: 500px;">Any Accidents, Last 3 Years if (Yes): &nbsp;
                               <img src="images/right.png" height="30px" width="30px"></td>
                            <td style="padding-right: 8px;width:50px;"></td>
                            <td style="width: 500px;">No</td>
                            </tr>
                         </tbody>
                            </table>';
           if(!empty($dynamicFieldArray['f2_accidentdate']) && count($dynamicFieldArray['f2_accidentdate']) > 0)
             {
                for($i=0;$i<count($dynamicFieldArray['f2_accidentdate']);$i++)
                {
                  $f2_dynamic2.= '<table cellpadding="5" cellspacing="0" border="0" style="width:1100px;margin-bottom: 25px;">
                        <tbody>
                            <tr>
                              <td style="width: 60px;">Date </td>
                              <td style="padding-right:15px;width: 150px;border-bottom: solid 1px #696969;">
                               &nbsp;<label>'.$dynamicFieldArray['f2_accidentdate'][$i].'</label></td>
                              <td style="width: 100px;">Describe</td>	
                              <td style="padding-right:15px;width: 150px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<label>'.$dynamicFieldArray['f2_accidentDesc'][$i].'</label></td>
                              <td style="width:120px;">Fatalities</td>
                              <td style="width: 154px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<label>'.$dynamicFieldArray['f2_accidentFacilities'][$i].'</label>
                              </td>
                              <td style="width: 100px;">Injuries</td>
                              <td style="width: 150px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<label>'.$dynamicFieldArray['f2_accidentInjuries'][$i].'</label>
                              </td>
                            </tr>
                        </tbody>
                    </table>';
                }
            }
        }
         else
        {
            $f2_dynamic2 = '<table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
                      <tbody>
                        <tr>
                            <td style="width: 500px;">Any Accidents, Last 3 Years if (Yes): </td>
                            <td style="padding-right: 8px;width:50px;"></td>
                            <td style="width: 500px;">No &nbsp;
                               <img src="http://www.superttransport.com/wp-content/themes/CherryFramework/images/right.png" height="30px" width="30px"></td>
                            </tr>
                         </tbody>
                            </table>';
        }

        if($row['f2_driverslicensedeniedStatus'] == "on")
          {
            $f2_dynamic3 ='<table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
                <tbody>
                   <tr>
						<td>
							<p>Have you ever had any drivers’ license denied, suspended, revoked or cancelled by any issuing State agency?</p>
						</td>
					</tr>
					<tr>
						<td style="width:150px;">
							Yes &nbsp;<img src="images/right.png" height="30px" width="30px">
						</td>
						<td style="width:200px;">
							No
						</td>
                  </tr>
                </tbody>
              </table>
            <table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
              <tbody>
                <tr>
                  <td style="width:300px;">if YES,state of insurance and explnation</td>
                  <td style="width: 700px;border-bottom:1px solid #000;">
                  &nbsp;<lable>'.$row['f2_driverslicensedeniedExplanation'].'</lable></td>
                </tr>
              </tbody>
            </table>';
          }
          else
          {
            $f2_dynamic3 ='<table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
                <tbody>
                   <tr>
                    <td>
                      <p>Have you ever had any drivers’ license denied, suspended, revoked or cancelled by any issuing State agency?</p>
                    </td>
					</tr>
					<tr>
						<td style="width:100px;">
						  Yes
						</td>
						<td style="width:100px;">
						   No &nbsp;<img src="images/right.png" height="30px" width="30px">
						</td>
                  </tr>
                </tbody>
              </table>
            <table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
              <tbody>
                <tr>
                  <td style="width: 100px;">Explain</td>
                  <td style="width: 700px;border-bottom:1px solid #000;">
                  &nbsp;<lable>No Explanation</lable></td>
                </tr>
              </tbody>
            </table>';
          }
		
		$FMCSAperiodyes = ""; $FMCSAperiodno = ""; $FMCSAlcoholtesting = ""; $FMCSAlcoholtestingno = "";
		if(!empty($dynamicFieldArray['f2_employer']) && count($dynamicFieldArray['f2_employer']) > 0)
		{
			$f2_dynamic4 = '<p>Employment history Last 10 Year FMCSA 383-35.Account for Gaps betwwen employers:(of Owner/Operator, listt carrier leased to).</p>';	
			for($i=0,$j=1;$i<count($dynamicFieldArray['f2_employer']);$i++,$j++)
			{
				if($dynamicFieldArray['f2_FMCSAperiod'][$i]!="" && $dynamicFieldArray['f2_FMCSAperiod'][$i] == "1")
				{
                      $FMCSAperiodyes = '<img src="images/right.png" height="30px" width="30px">';
				}
				else
				{
                    $FMCSAperiodno = '<img src="images/right.png" height="30px" width="30px">';
				}
				if($dynamicFieldArray['f2_FMCSAlcoholtesting'][$i]!="" && $dynamicFieldArray['f2_FMCSAlcoholtesting'][$i] == "1")
				{
                      $FMCSAlcoholtesting = '<img src="images/right.png" height="30px" width="30px">';
				}
				else
				{
                    $FMCSAlcoholtestingno = '<img src="images/right.png" height="30px" width="30px">';
				}
				$f2_dynamic4.= '<table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;">
                    <tbody>
                      <tr>
                          <td style="width: 15px;">'.$j.'</td>
                          <td style="width:150px;">Employer</td>
                          <td style="padding-right: 15px;width:150px;border-bottom: solid 1px #696969;">
                          &nbsp;<label>'.$dynamicFieldArray['f2_employer'][$i].'</label></td>
                          <td style="width: 100px;">Date</td>
                          <td style="padding-right:15px;width: 160px;border-bottom: solid 1px #696969;">
                          &nbsp;<label>'.$dynamicFieldArray['f2_month'][$i].'</label></td>
                          <td style="width: 50px;">to</td>
                          <td style="width: 160px;border-bottom: solid 1px #696969;">
                          &nbsp;<label>'.$dynamicFieldArray['f2_year'][$i].'</label></td>
                      </tr>
                    </tbody>
                  </table>
				  <table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
                    <tbody>
                      <tr>
                        <td style="width: 15px;"></td>
                        <td style="width: 120px;">Address: </td>
                        <td style="padding-right:15px;width: 290px;border-bottom: solid 1px #696969;">
                        &nbsp;<lable>'.$dynamicFieldArray['f2_addres'][$i].'</lable></td>
                        <td style="width: 120px;">Supervisor</td>
                        <td style="padding-right:15px;width: 310px;border-bottom: solid 1px #696969;">
                        &nbsp;<lable>'.$dynamicFieldArray['f2_supervisor'][$i].'</lable></td>
                       </tr>
                    </tbody>
                  </table>
				  <table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
                    <tbody>
                      <tr>
                        <td style="width: 15px;"></td>
                        <td style="width: 160px;">City,State,Zip: </td>
                        <td style="padding-right:15px;width: 290px;border-bottom: solid 1px #696969;">
                        &nbsp;<lable>'.$dynamicFieldArray['f2_cityStateZip'][$i].'</lable></td>
                        <td style="width: 120px;">Phone</td>
                        <td style="padding-right:15px;width: 310px;border-bottom: solid 1px #696969;">
                        &nbsp;<lable>'.$dynamicFieldArray['f2_phone'][$i].'</lable></td>
                       </tr>
                    </tbody>
                  </table>
				  <table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 0px;">
                  <tbody>
                    <tr>
                      <td style="width: 15px;"></td>
                      <td style="width: 700px;">
                        <p style="margin-bottom: 10px;margin-top: 25px;margin-left: 25px;">Were you subject to FMCSA Regulations during that period? Yes</p>
                        </td>
                      <td style="">
                        <lable>'.$FMCSAperiodyes.'</lable></td>
                      <td style="width: 30px;">
                       <lable>No</lable>
                      </td>
                      <td style="width: 100px;padding-right:15px;">
                      &nbsp;<lable>'.$FMCSAperiodno.'</lable></td>
                    </tr>
                   </tbody>
                </table>
				<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 0px;">
                  <tbody>
                    <tr>
                      <td style="width: 15px;"></td>
                      <td style="width: 900px;">
                        <p style="margin-bottom: 10px;margin-top: 25px;margin-left: 25px;">Were you subject to 49 CFR part 40 controlled Substance/Alcohol testing? Yes</p>
                        </td>
                      <td style="">
                        <lable>'.$FMCSAlcoholtesting.'</lable></td>
                      <td style="width: 30px;">
                       <lable>No</lable>
                      </td>
                      <td style="width: 100px;padding-right:15px;">
                      &nbsp;<lable>'.$FMCSAlcoholtestingno.'</lable></td>
                    </tr>
                   </tbody>
                </table>
				<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
                  <tbody>
                    <tr>
                      <td style="width: 15px;"></td>
                      <td style="width: 200px;"><p style="margin-bottom: 10px;margin-top: 25px;">Reason for leaving</p></td>
                      <td style="width:320px;padding-right:15px;border-bottom: solid 1px #696969;">
                      &nbsp;<lable>'.$dynamicFieldArray['f2_reasoningLeaving'][$i].'</lable></td>
                    </tr>
                  </tbody>
                </table>';
			}
		}
		else
		{
			$f2_dynamic4 = '<p>Employment history Last 10 Year FMCSA 383-35.Account for Gaps betwwen employers:(of Owner/Operator, listt carrier leased to).</p>';	
		}
	/*dynamic portion for second page*/
	
	/*dynamic portion for 5th page*/
		if($row['f5_transportemployeeStatus'] == "1")
        {
            $dynamic5 = '<p style="margin-bottom: 10px;margin-top: 15px;">
                             Have You Ever Been Employed By a Department of transportation Employer? yes &nbsp;<img src="images/right.png" height="30px" width="30px">&nbsp;&nbsp;&nbsp;No
                          </p>';
				if(!empty($dynamicFieldArray['f2_employer_new']) && count($dynamicFieldArray['f2_employer_new'] > 0))
				{
					for($i=0,$j=1;$i<count($dynamicFieldArray['f2_employer_new']);$i++,$j++)
					{
						$FMCSAperiodyes = '<img src="images/right.png" height="30px" width="30px">';
					  
						$dynamic5.= '<table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;">
						<tbody>
						  <tr>
							  <td style="width: 15px;">'.$j.'</td>
							  <td style="width:150px;">Employer</td>
							  <td style="padding-right: 15px;width:150px;border-bottom: solid 1px #696969;">
							  &nbsp;<lable>'.$dynamicFieldArray['f2_employer_new'][$i].'</lable></td>
							  <td style="width: 100px;">Date</td>
							  <td style="padding-right:15px;width: 160px;border-bottom: solid 1px #696969;">
							  &nbsp;<lable>'.$dynamicFieldArray['f2_month_new'][$i].'</lable></td>
							  <td style="width: 50px;">to</td>
							  <td style="width: 160px;border-bottom: solid 1px #696969;">
							  &nbsp;<lable>'.$dynamicFieldArray['f2_year_new'][$i].'</lable></td>
						  </tr>
						</tbody>
					  </table>
					  <table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
						<tbody>
						  <tr>
							<td style="width: 15px;"></td>
							<td style="width: 120px;">Address: </td>
							<td style="padding-right:15px;width: 290px;border-bottom: solid 1px #696969;">
							&nbsp;<lable>'.$dynamicFieldArray['f2_addres_new'][$i].'</lable></td>
							<td style="width: 120px;">Supervisor</td>
							<td style="padding-right:15px;width: 310px;border-bottom: solid 1px #696969;">
							&nbsp;<lable>'.$dynamicFieldArray['f2_supervisor_new'][$i].'</lable></td>
						   </tr>
						</tbody>
					  </table>
					  <table cellpadding="5" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
						<tbody>
						  <tr>
							<td style="width: 15px;"></td>
							<td style="width: 160px;">City,State,Zip: </td>
							<td style="padding-right:15px;width: 290px;border-bottom: solid 1px #696969;">
							&nbsp;<lable>'.$dynamicFieldArray['f2_cityStateZip_new'][$i].'</lable></td>
							<td style="width: 120px;">Phone</td>
							<td style="padding-right:15px;width: 310px;border-bottom: solid 1px #696969;">
							&nbsp;<lable>'.$dynamicFieldArray['f2_phone_new'][$i].'</lable></td>
						   </tr>
						</tbody>
					  </table>
					  <table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 0px;">
					  <tbody>
						<tr>
						  <td style="width: 15px;"></td>
						  <td style="width: 700px;">
							<p style="margin-bottom: 10px;margin-top: 25px;margin-left: 25px;">Were you subject to FMCSA Regulations during that period? Yes</p>
							</td>
						  <td style="">
							<lable>'.$FMCSAperiodyes.'</lable></td>
						  <td style="width: 30px;">
						   <lable>No</lable>
						  </td>
						  <td style="width: 100px;padding-right:15px;">
						  &nbsp;<lable></lable></td>
						</tr>
					   </tbody>
					</table>
					<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 0px;">
					  <tbody>
						<tr>
						  <td style="width: 15px;"></td>
						  <td style="width: 900px;">
							<p style="margin-bottom: 10px;margin-top: 25px;margin-left: 25px;">Were you subject to 49 CFR part 40 controlled Substance/Alcohol testing? Yes</p>
							</td>
						  <td style="">
							<lable>'.$FMCSAlcoholtesting.'</lable></td>
						  <td style="width: 30px;">
						   <lable>No</lable>
						  </td>
						  <td style="width: 100px;padding-right:15px;">
						  &nbsp;<lable></lable></td>
						</tr>
					   </tbody>
					</table>
					<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
					  <tbody>
						<tr>
						  <td style="width: 15px;"></td>
						  <td style="width: 200px;"><p style="margin-bottom: 10px;margin-top: 25px;">Reason for leaving</p></td>
						  <td style="width:320px;padding-right:15px;border-bottom: solid 1px #696969;">
						  &nbsp;<lable>'.$dynamicFieldArray['f2_reasoningLeaving_new'][$i].'</lable></td>
						</tr>
					  </tbody>
					</table>';
					}
				}
				else
				{
                    
                }
        }
        else
        {
            $dynamic5 = '<p style="margin-bottom: 10px;margin-top: 15px;">
                             Have You Ever Been Employed By a Department of transportation Employer?yes &nbsp;&nbsp;&nbsp;No&nbsp;<img src="images/right.png" height="30px" width="30px">
                          </p><br>';
        }
	
	/*dynamic portion for 5th page*/
	
	/*dynamic portion for 6th page*/  $employmentinCalifornia=""; $employmentinOklahoma=""; $employmentinCalifornia= "";
	 if($row["f6_employmentinCalifornia"]=="on")
    { $employmentinCalifornia= '<img src="images/right.png">'; }
     else{
          $employmentinCalifornia= '<img src="images/wrong.png">';
        }
    
     if($row["f6_employmentinOklahoma"]=="on")
    { $employmentinOklahoma= '<img src="images/right.png" >'; }  
    else{
          $employmentinCalifornia= '<img src="images/wrong.png" >';
        }
     
    if($row["f6_minnesota"]=="on")
    { $minnesota= '<img src="images/right.png" >'; }
    else{
          $employmentinCalifornia= '<img src="images/wrong.png" >';
     }

	
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 006');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set image scale factor
$pdf->setImageScale(2.0);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = '<table cellpadding="0" cellspacing="0" border="0" style="line-height:30px;">
      <tbody><tr>
        <td>
          <table cellpadding="0" cellspacing="0" border="0">
            <tbody><tr>
              <td>
                <table>
                  <tbody><tr>
                    <td>
                      <img src="images/Logo.png"/><br/>
                      <span style="margin-top: 0px;">PO Box 218 Rigby ID, 83442, 8886290719<br> Fax: 208-754-7397</span></td>
                    <td align="right">
                      <h2 style="">Driver Application for Employment</h2>
                    </td>
                  </tr>
                </tbody></table>
                <table cellpadding="0" cellspacing="0" border="0">
                  <tbody><tr>
                    <td>
                      <p style="margin-bottom: 0px;margin-top: 0px;"><strong>Note to Applicant:</strong>&nbsp;&nbsp; Please advise us in advance if you need any type of special accommodation to complete this application or to take any pre-employment test. </p>
                      <p style="margin-bottom: 0px;margin-top: 0px;">*Qualified applicants are considered for all positions without regard to age, sex, race, color, religion, national origin, sexual orientation, disability, marital and/or veteran status.</p>
                    </td>
                  </tr>
                </tbody></table>
                <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                  <tbody><tr>
                    <td>
                      <form action="">
                        <table cellpadding="5" cellspacing="0" border="0" style="">
                          <tr>
                            <td>Position Applied for:</td>
                            <td><lable>'. $row["f1_positionApplied"].'</lable></td>
                            <td>Date: </td>
                            <td style="border-bottom:solid 1px #696969;"><lable>'. $row["f1_date"].'</lable></td>
                          </tr>
                        </table>
                        <table cellpadding="5" cellspacing="" border="0" style="margin-bottom: 25px;">
                          <tr><td colspan="6" style="padding-bottom:5px;"><strong>Name</strong></td> </tr>
                          <tr>
                            <td style="">First: </td>
                            <td style="border-bottom: solid 1px #696969">
                            <lable>'. $row["f1_firstName"].'</lable></td>
                            <td style="">Last: </td>
                            <td style="border-bottom: solid 1px #696969">
                            <lable>'. $row["f1_middleName"].'</lable></td>
                            <td style="">Middle: </td>
                            <td style="border-bottom: solid 1px #696969">
                            <lable>'. $row["f1_lastName"].'</lable></td>
                          </tr>
                        </table>
                           <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody>
                            <tr>
                              <td style="">Address: </td>
                              <td style="border-bottom: solid 1px #696969">
                              <lable>'. $row["f1_address"].'</lable></td>
                              <td style="">City, St: </td>
                              <td style="border-bottom: solid 1px #696969">
                              <lable>'. $row["f1_city"].'</lable></td>
                              <td style="">Zip: </td>
                              <td style="border-bottom: solid 1px #696969">
                              <lable>'. $row["f1_zip"].'</lable>
                              </td>
                          </tr>
                        </tbody>
                      </table>
                        <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr><td colspan="6" style="padding-bottom:15px;">if you have Not been at this address for more than 7 years, list previous address</td></tr>
                          <tr>
                            <td style="">Address: </td>
                            <td style="padding-right:15px;border-bottom: solid 1px #696969">
                            <lable>'. $row["f1_previousAddress"].'</lable></td>
                            <td style="">City, St: </td>
                            <td style="padding-right:15px;border-bottom: solid 1px #696969">
                            <lable>'. $row["f1_previousCity"].'</lable></td>
                            <td style="">Zip: </td>
                            <td style="border-bottom: solid 1px #696969">
                            &nbsp;&nbsp;<lable>'. $row["f1_previousZip"].'</lable></td>
                          </tr>
                        </tbody>
                        </table>
                        <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="">Home Phone: </td>
                            <td style="padding-right:15px;border-bottom: solid 1px #696969">
                            <lable>'. $row["f1_homePhone"].'</lable></td>
                            <td style="">Date of Birth: </td>
                            <td style="border-bottom: solid 1px #696969">
                            <lable>'. $row["f1_dob"].'</lable></td>
                          </tr>
                        </tbody>
                        </table>
                        <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="">Cell Phone: </td>
                            <td style="padding-right:15px;border-bottom: solid 1px #696969;"><lable>'. $row["f1_cellPhone"].'</lable></td>
                            <td style="">SSN: </td>
                            <td style="border-bottom: solid 1px #696969;">&nbsp;&nbsp;<lable>'. $row["f1_ssn"].'</lable></td>
                          </tr>
                        </tbody></table>
                        <table cellpadding="5" cellspacing="0" style="margin-bottom: 25px">
                          <tbody><tr>
                            <td style="width:500px;">Have you ever worked for this company? yes &nbsp;'.$workedcompanyStatus .'</td>
                            <td style="width: 45px;">No &nbsp;'.$workedcompanyStatusfalse .'</td>
                            <td style="width: 75px;">When: </td>
                            <td style="width:180px;"><lable>'. $row["f1_workedcompanyDate"].'</lable></td>
                          </tr>
                        </tbody></table>
						<table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width:450px;">How many years have you held your CDL:   </td>
                            <td style="width:50px;border-bottom: solid 1px #696969;">&nbsp;&nbsp;<lable>'. $row["f1_cdlYear"].'</lable></td>
                            <td style="width:200px;">Drivers License #: </td>
                            <td style="width:200px; border-bottom: solid 1px #696969;">&nbsp;&nbsp;<lable>'. $row["f1_drivingLicense"].'</lable></td>
                          </tr>
                        </tbody></table>
                        <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width:280px;">Current State CDL is held:  </td>
                            <td style="width:150px;border-bottom: solid 1px #696969">&nbsp;&nbsp;<lable>'. $row["f1_currentstateCdl"].'</lable></td>
                            <td style="width:80px;">Class:  </td>
                            <td style="width:100px;border-bottom: solid 1px #696969; ">&nbsp;&nbsp;<lable>'. $row["f1_cdlClass"].'</lable></td>
                            <td style="width:100px;">Expires:</td>
                            <td style="width:250px;border-bottom: solid 1px #696969; ">&nbsp;&nbsp;<lable>'. $row["f1_cdlExpires"].'</lable></td>
                          </tr>
                        </tbody></table>
                        <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width:750px;">Have you ever been terminated or asked to resign by an employe? yes'.$terminatedStatus.'</td>
                            <td style="width:80px;">No'.$terminatedStatusfalse.'</td>
                            <td style="width:250px;">If yes, pleased explain</td>
                          </tr>
                          <tr><td colspan="5" style="padding-top: 15px;border-bottom: solid 1px #696969">
                          &nbsp;&nbsp;<lable>'. $row["f1_terminatedReason"].'</lable></td></tr>
                        </tbody></table>
                        <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width:520px;">Have you ever been convicted of a felony? yes &nbsp;'.$convictedFelony.'</td>
                            <td style="width:80px;">No &nbsp;'.$convictedFelonyfalse.'</td>
                            <td style="width:500px;">A felony is not an absolute bar of employment. </td>
                          </tr>
                        </tbody></table>
                        <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width:250px;">If yes please explain: </td>
                            <td style="width:850px;border-bottom: solid 1px #696969">&nbsp;&nbsp;<lable>'.$row['f1_convictedfelonyReason'].'</lable></td>
                          </tr>
                          <tr><td colspan="5" style="padding-top: 15px;"><lable></lable></td></tr>
                        </tbody></table>
                        <table cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width:300px;"> Emergency contact person: </td>
                            <td style="width:300px; border-bottom: solid 1px #696969">&nbsp;&nbsp;<lable>'.$row['f1_emergencycontactName'].'</lable></td>
                            <td style="width:100px;">Phone: </td>
                            <td style="width:200px; border-bottom: solid 1px #696969">&nbsp;&nbsp;<lable>'.$row['f1_emergencycontactNumber'].'</lable></td>
                          </tr>&nbsp;&nbsp;
                        </tbody></table>
                        <p style="margin-bottom: 10px;margin-top: 0px;">Government regulation require that we verify your identity and employment authorization (1-9) within three (3) working days of your date of hire.Please be prepared to submit proper identification.</p><p>
                      </p>
                      </form>
                    </td>
                  </tr>
                </tbody></table>    
              </td><!--/container -->
            </tr>
          </tbody></table>
        </td>
      </tr>
    </tbody></table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->ImageSVG($file='images/tux.svg', $x=150, $y=265, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false);

// reset pointer to the last page
$pdf->lastPage();

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

$pdf->addpage();

$html = '<table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
      <tbody><tr>
        <td style="width:100%;">
          <table cellpadding="0" cellspacing="0" border="0" style="margin: auto;margin-bottom: 25px;">
            <tbody><tr>
              <td style="width:1100px; background: #F9F9F9; padding:15px;">
                <table style="width: 1100px;margin-bottom: 25px;">
                  <tbody>
				  <tr>
                    <td align="center">
                      <img src="images/shortLogo.png" width="300px">
                    </td>
                  </tr></tbody></table><br>'.$f2_dynamic1.$f2_dynamic2.$f2_dynamic3.$f2_dynamic4.'
				</td>
                  </tr>
                </tbody></table>    
              </td><!--/container -->
            </tr>
          </tbody></table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->ImageSVG($file='images/tux.svg', $x=150, $y=265, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false);

// reset pointer to the last page
$pdf->lastPage();




// end add a page  for 2nd
$pdf->AddPage();

$html = ' <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 25px;">
      <tbody><tr>
        <td style="width:1100px;">
          <table cellpadding="5" cellspacing="5" border="0" style="margin: auto;margin-bottom: 25px;">
            <tbody><tr>
              <td style="padding:15px;">
                <table style="margin-bottom: 25px;">
                  <tbody><tr>
                    
                    <td align="center">
                      <img src="images/shortLogo.png">
                    </td>
                  </tr><tr>
                    
                    <td align="center">
                      <h2 style="text-align:center;">BACKGROUND CHECK AUTHORIZATION AND RELEASE FORM</h2>
                    </td>
                  </tr>
                </tbody></table>
                <br>
                <table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
                  <tbody><tr>
                    <td>
                      <table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
                          <tbody><tr><td colspan="6" style="padding-bottom:5px;"><strong>Name</strong></td> </tr>
                          <tr>
                            <td style="width:65px;">First: </td>
                            <td style="padding-right:15px;width: 300px;border-bottom: solid 1px #696969">
                            &nbsp;&nbsp;<lable>'. $row["f1_firstName"].'</lable></td>
                            <td style="width: 60px;">Last:</td>
                            <td style="padding-right:15px;width: 300px;border-bottom: solid 1px #696969">
                           &nbsp;&nbsp;<lable>'. $row["f1_lastName"].'</lable></td>
                            <td style="width: 90px;">Middle: </td>
                            <td style="width: 200px;border-bottom: solid 1px #696969">
                            &nbsp;&nbsp;<lable>'. $row["f1_middleName"].'</lable></td>
                          </tr>
                        </tbody></table>
			<table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
                          <tbody>
                            <tr>
                              <td style="width: 65px;">SSN:</td>
                              <td style="padding-right:15px;width: 200px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<lable>'. $row["f3_ssn"].'</lable></td>
                              <td style="width: 190px;">Driver License #:</td>
                              <td style="padding-right:15px;width: 200px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<lable>'. $row["f3_drivingLicense"].'</lable></td>
                              <td style="width: 160px;">State Issued:</td>
                              <td style="width: 200px;border-bottom: solid 1px #696969;">
                              &nbsp;&nbsp;<lable>'.$row['f3_stateIssued'].'</lable></td>
                          </tr>
                        </tbody>
			</table>
                        
			<table cellpadding="5" cellspacing="5" border="0" style="width: 1100px;margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width: 160px;">Date of Birth: </td>
                            <td style="padding-right: 8px;width: 200px;border-bottom: solid 1px #696969;">
                          &nbsp;&nbsp; <lable>'.$row['f3_dob'].'</lable></td>
                            <td style="width: 350px;">Maiden and all other name used :</td>
                            <td style="width: 250px;border-bottom: solid 1px #696969;">
                           &nbsp;&nbsp;<lable>'.$row['f3_allotherName'].'</lable></td>
                          </tr>
                        </tbody>
		    </table>
                    <table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
			<tbody>
                            <tr>
                              <td style="width:100px;">Address: </td>
                              <td style="padding-right:15px;width: 281px;border-bottom: solid 1px #696969;">
                                &nbsp;&nbsp; <lable>'.$row['f3_address'].'</lable></td>
                              <td style="width:100px;">City, St.: </td>
                              <td style="padding-right:15px;width: 310px;border-bottom: solid 1px #696969">
                               &nbsp;&nbsp; <lable >'.$row['f3_city'].'</lable></td>
                              <td style="width: 50px;">Zip: </td>
                              <td style="width: 200px;border-bottom: solid 1px #696969">
                               &nbsp;&nbsp; <lable>'.$row['f3_zip'].'</lable></td>
                          </tr>
                        </tbody>
			</table>
                        <table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width: 290px;">Length at present address :</td>
                            <td style="padding-right: 8px;width: 200px;border-bottom: solid 1px #696969;">
                            &nbsp;&nbsp;<lable>'.$row['f3_presentAddress'].'</lable></td>
                            <td style="width: 450px;">(if less than 7 years, list previous address)</td>
                            
                          </tr>
                          
                        </tbody>
                        </table>
                        <table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
                          <tbody>
                            <tr>
                              <td style="width:100px;">Address: </td>
                              <td style="padding-right:15px;width: 281px;border-bottom: solid 1px #696969;">
                               &nbsp;&nbsp;<lable>'.$row['f3_previousAddress'].'</lable></td>
                              <td style="width: 100px;">City, St.: </td>
                              <td style="padding-right:15px;width: 310px;border-bottom: solid 1px #696969">
                               &nbsp;&nbsp;<lable >'.$row['f3_previousCity'].'</lable></td>
                              <td style="width: 50px;">Zip: </td>
                              <td style="width: 200px;border-bottom: solid 1px #696969">
                              &nbsp;&nbsp;<lable>'.$row['f3_previousZip'].'</lable></td>
                          </tr>
                        </tbody>
                      </table>
                        <lable name="">
                          </lable>
                      <table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
                          <tbody><tr>
                            <td style="width: 150px;">Home phone:</td>
                            <td style="padding-right:15px;border-bottom: solid 1px #696969;width: 179px;">&nbsp;&nbsp;<lable>'.$row['f3_homePhone'].'</lable></td>
                            <td style="width:150px;">Cell phone:</td>
                            <td style="width: 318px;padding-right:15px;border-bottom: solid 1px #696969;">&nbsp;&nbsp;<lable>'.$row['f3_cellPhone'].'</lable></td>
                            
                            </tr>
                        </tbody>
                     </table>
                        <br> <br> <br>
                     <p style="margin-bottom: 10px;margin-top: 50px;">
			 I hereby authorize the release to Super T Transport, Inc., any and all information regarding my prior
			 employment, criminal, credit, driving, workers compensation and educational history as well as information
			 regarding my general character and reputation. I release any providers of such information from any liability for
			 providing the information. I understand the information may be reviewed initially and periodically by Super T
			 Transport, Inc.
		    </p>  

		    <p style="margin-bottom: 10px;margin-top: 15px;">
			I release Super T Transport, Inc. and their agents and assigns, from any and all demands and/or liabilities
			that may originate from these investigations, or any demand or liability which may result from any drug testing
			procedure, or other medical screening procedures conducted by them or their agents, and any person,
			corporation, company, institute or their agents who may act upon the authority of this release.
		   </p> 

	          <p style="margin-bottom: 10px;margin-top: 15px;">
                       I agree falsification may make me ineligible for employment or subject to immediate dismissal, if hired. I
		       further acknowledge that Super T Transport, Inc. is relying on third party information and I therefore release
		       Super T Transport, Inc., my prospective/actual employer, and their respective owners, agents and employees from
		       any and all liability arising out of errors or omissions.
                  </p>
                 <p style="margin-bottom: 10px;margin-top: 15px;">
                           I hereby authorize that a photocopy or electronic facsimile of this document shall serve as an original.
		</p>
		</td>
                  </tr>
                </tbody></table>    
              </td><!--/container -->
            </tr>
          </tbody></table>
        </td>
      </tr>
    </tbody></table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->ImageSVG($file='images/tux.svg', $x=150, $y=265, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false);

$pdf->lastPage();

$pdf->AddPage();


//$html="<h1>gaurav created</h1>";
$html='<table cellpadding="0" cellspacing="0" border="0">
      <tbody><tr>
        <td style="width:100%;">
          <table cellpadding="0" cellspacing="0" border="0">
            <tbody><tr>
              <td>
                <table style="">
                  <tbody><tr>
               
                    <td align="center">
                      <center><h3 style="text-align:center; line-height:10px;">IMPORTANT NOTICE</h3></center>
                    </td>
                  </tr><tr>
                    
                    <td align="center">
                      <h4 style="text-align:center; line-height:10px;">REGARDING BACKGROUND REPORTS FROM THE <i>PSP Online service</i></h4>
                    </td>
                  </tr>
                </tbody>
				</table>
                
                <table cellpadding="0" cellspacing="0" border="0" style="line-height:24px;">
                  <tbody><tr>
                    <td>
                      <p>
                           1. In connection with your application for employment with   Super T Transportation, Inc. 
              it may obtain one or more reports regarding your driving, and safety inspection history from the Federal Motor Carrier Safety Administration (FMCSA).
                       </p>

	            <p>
                        When the application for employment is submitted in person, if the Prospective Employer uses any information it obtains from FMCSA in a decision           to not hire you or to make any other adverse employment decision regarding you, the Prospective Employer will provide you with a copy of the report upon which its decision was based and a written summary of your rights under the Fair Credit Reporting Act before taking any final adverse action. If any final adverse action is taken against you based upon your driving history or safety report, the Prospective Employer will notify you that the action has been taken and that the action was based in part or in whole on this report.
                     </p>
                 <p>&nbsp;
When the application for employment is submitted by mail, telephone, computer, or other similar means, if the Prospective Employer uses any information it obtains from FMCSA in a decision to not hire you or to make any other adverse employment decision regarding you, the Prospective Employer must provide you within three business days of taking adverse action oral, written or
electronic notification: that adverse action has been taken based in whole or in part on information obtained from FMCSA; the name, address, and the toll free telephone number of FMCSA; that the FMCSA did not make the decision to take the adverse action and is unable to provide you the specific reasons why the adverse action was taken; and that you may, upon providing proper identification, request a free copy of the report and may dispute with the FMCSA the accuracy or completeness of any information or report. If you request a copy of a driver record from the Prospective Employer who procured the report, then, within 3 business days of receiving your request, together with proper identification, the Prospective Employer must send or provide to you a copy of your report and a summary of your rights under the Fair Credit Reporting Act.
                </p>
              <p>
                  The Prospective Employer cannot obtain background reports from FMCSA unless you consent in writing. 
              </p>

               <p>
                    If you agree that the Prospective Employer may obtain such background reports, please read the following and sign below:
                </p>

                <p>
                       2. I authorize Super T Transportation Inc. to access the FMCSA Pre-Employment Screening Program (PSP)
system to seek information regarding my commercial driving safety record and information regarding my safety inspection history. I understand that I am consenting to the release of safety performance information including crash data from the
previous five (5) years and inspection history from the previous three (3) years. I understand and acknowledge that this release of information may assist the Prospective Employer to make a determination regarding my suitability as an employee.
</p>

<p>3. I further understand that neither the Prospective Employer nor the FMCSA contractor supplying the crash and safety information has the capability to correct any safety data that appears to be incorrect. I understand I may challenge the accuracy of the data by submitting a request to https://dataqs.fmcsa.dot.gov. If I am challenging crash or inspection information reported by a State, FMCSA cannot change or correct this data. I understand my request will be forwarded by the DataQs system to the appropriate State for
adjudication.</p>

<p>4. Please note: Any crash or inspection in which you were involved will display on your PSP report. Since the PSP report does not report, or assign, or imply fault, it will include all Commercial Motor Vehicle (CMV) crashes where you were a driver or co-driver and where those crashes were reported to FMCSA, regardless of fault. Similarly, all inspections, with or without violations, appear on the PSP report. State citations associated with FMCSR violations that have been adjudicated by a court of law will also appear, and remain, on a PSP report.</p>

<p>I have read the above Notice Regarding Background Reports provided to me by Prospective Employer and I understand that if I sign this consent form, Prospective Employer may obtain a report of my crash and inspection history. I hereby authorize Prospective Employer and its employees, authorized agents, and/or affiliates to obtain the information authorized above. 
                </p>
                  </td>
                  </tr>
                </tbody></table>    
              </td><!--/container -->
            </tr>
          </tbody></table>
        </td>
      </tr>
    </tbody></table>';
$pdf->writeHTML($html, true, false, true, false, '');


$pdf->ImageSVG($file='images/tux.svg', $x=150, $y=265, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false);

$pdf->lastPage();

// -----------5th page----------------------------------------------
$pdf->AddPage();
$html = '<table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
      <tbody><tr>
        <td style="width:100%;">
          <table cellpadding="5" cellspacing="5" border="0" style="margin: auto;margin-bottom: 25px;">
            <tbody><tr>
              <td style="width:1100px; background: #F9F9F9; padding:15px;">
                <table style="margin-bottom: 25px;">
                  <tbody><tr>
                    
                    <td>
                      <h3 style="">PART I - DISCLOSURE AND AUTHORIZATION FOR RELEASE OF INFORMATION FOR EMPLOYMENT PURPOSES - 49 CFR PART 391.23, DOT DRUG AND ALCOHOL TESTING</h3>
                    </td>
                  </tr>
                </tbody>
				</table>
                
                <table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
                  <tbody><tr>
                    <td>
                    <p style="margin-bottom: 10px;margin-top: 5px;">In accordance with DOT Regulation 49 CFR Part 391.23, I hereby authorize release of my DOT-regulated drug and alcohol testing records by the DOT-regulated employer(s) listed below to HireRight for the purpose of HireRight transmitting such records to the HireRight customer listed above. I understand that information/documents released pursuant to this part I is limited to the following DOT-regulated testing items, including pre-employment testing result, occurring during the previous three (3) years: (i) alcohol tests with a result of 0.04 or higher;(ii)verified positive drug test(iii)refusals to be tested(including dual terated  and/or s substituted  tests)(iv) other violations of DOT drug and alcohol test regulation (i.e violations of 49 CFR 382 sub part B);(V)information obtain from previous employee of drug and alcohol rule violations and(vi) documentation of complement of the return-to-duty process following a rule violations.</p><br>
					<p style="margin-bottom: 10px;margin-top: 15px;">if any company listed below furnished HireRight with information concern items(i)through(vi)above,I also authorize such company to furnish the following information to HireRight, if applicable (i) dates of my negative  drug and/or alcohol  tests and/or tests with result below 0.04 during the previous three(3)year and(ii) the name and phone number of any substance abuse profession who involve me during previous three (3)year.</p>
					<br>
                   </td>
                  </tr>
                </tbody></table>'.$dynamic5.'    
              </td><!--/container -->
            </tr>
          </tbody></table>
        </td>
      </tr>
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ImageSVG($file='images/tux.svg', $x=150, $y=265, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false);

$pdf->lastPage();

/*6th page */

$pdf->addPage();
$html = '<h4>CONSUMER REPORT/INVESTIGATIVE CONSUMER REPORT DISCLOSURE</h4>
			<p style="line-height:22px;">
                            In connection with your employment or application for employment (including independent contractor assignments, if applicable) and in                      accordance with pertinent laws, HireRight may obtain or assemble consumer reports and/or
investigative consumer reports (collectively, “Reports”) related to information concerning your: previous employment
(including employers, dates of employment, salary information, reasons for termination, etc.), academic history,
verification of references and verification of other information supplied by you, professional credentials, drug/alcohol
use in violation of law and/or company policy, driving record, accident history, workers’ compensation claims, credit
history, creditworthiness, credit capacity, bankruptcy filings, criminal history records and information about your
character, general reputation, personal characteristics and mode of living (collectively, “Information”). Information
may be obtained from government agencies, educational institutions, HireRight clients, personal references, personal
interviews and other Information sources (collectively, “Suppliers”).
			   </p>

				<p style="line-height:22px;">
                    Upon providing proper identification and subject to applicable legal requirements and restrictions, you have the right
                    to request the nature and substance of all Information in HireRight’s files pertaining to you, as well as information
                   including, but not limited to: (i) whether any Reports have been provided by HireRight to other parties; (ii)
                   identification of any Suppliers utilized by HireRight in compiling such Reports; and (iii) identification of any recipients
                   of Reports furnished by HireRight within certain statutorily-prescribed time periods preceding your request. HireRight
                   may be contacted by mail at P.O. Box 33181, Tulsa, Oklahoma, 74153, or by phone at (800) 381-0645.
                </p>

				<table cellpadding="5" cellspacing="0" style="line-height:22px; border: 1px solid #000;padding: 10px;">
                 <tbody>
                       <tr>
                         <td>
                           '.$employmentinCalifornia.' Check this box if you are applying for employment in <strong>California</strong> and/or you are a California resident and, in either
case, you wish to receive a copy of your <strong>consumer credit report or investigative consumer report</strong>if one is
obtained or assembled by HireRight. Pursuant to the California Civil Code, during normal business hours you may
view the file maintained on you by HireRight. You may also obtain a copy of this file by submitting proper
identification and paying any statutorily-prescribed costs for such file by contacting HireRight in person, by mail or by
phone. HireRight is required to have personnel available to explain your file to you and must explain to you any coded
information appearing in your file. If you appear in person, a person of your choice may accompany you provided that
this person furnishes proper identification.
                                                  </td>
						</tr>

					<tr>
						  <td>
						   '.$employmentinOklahoma.' Check this box if you are applying for employment in <strong>Oklahoma</strong> and/or you are an Oklahoma resident and, in either
							case, you wish to receive a copy of your <strong>consumer report </strong>if one is obtained or assembled by HireRight.
						</td>
					</tr>


					<tr>
						 <td>
						   '.$minnesota.'Check this box if you are applying for employment in <strong>Minnesota</strong> and/or you are a Minnesota resident and, in either
						case, you wish to receive a copy of your <strong>consumer report</strong> if one is obtained or assembled by HireRight.
						</td>
					</tr>
				</tbody>
			</table>

			<p style="line-height:22px;">
					If you are a Maine, Massachusetts, New York or Washington State applicant, employee or contractor, please also
					refer to the additional state law notices attached herewith.
			</p>

			<h5 style="line-height:15px;"> <center>AUTHORIZATION FOR RELEASE OF INFORMATION</center></h5>
            <p style="line-height:22px;">
                  I hereby authorize HireRight to obtain Information and disclose Information to its customers (“Customers”), if
applicable, for the purpose of making a determination as to my eligibility for employment (including independent
contractor assignments), promotion, retention or other lawful purpose. If hired or contracted, I authorize HireRight and
HireRight Customers, if applicable, to retain this document on file to act as ongoing authorization for the procurement
and assembly of Reports at any time during my employment or contract period. As permitted by law, I fully release
HireRight and Suppliers from all claims of damages related to the investigation of my background and provision of
Information as set forth in this document. I agree that Information in HireRight’s possession and my employment
history with Customers if I am hired or contracted may be supplied by HireRight to other HireRight Customers for
legally permissible purposes.
           </p>
           <p style="line-height:22px;">
                  By signing below, I certify that: (i) all information provided herein is complete and accurate; (ii) I have read and fully
understand this disclosure and authorization for release; (iii) prior to signing I was given an opportunity to ask
questions and to have those questions answered to my satisfaction; (iv) I execute this authorization voluntarily and
with the knowledge that the Information obtained pursuant to this authorization could affect my eligibility for
employment, independent contractor status, promotion, retention or other lawful purpose; (v) I understand I may
review this document with legal counsel prior to signing; (vi) I authorize HireRight and any person or entity contacted
by HireRight to furnish the above-mentioned Information; and (vii) facsimile or e-mail copies of this authorization are
as valid as an original.
	</p>				
	<table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
		  <tbody>
		  <tr>
			<td style="width:65px;">Name: </td>
			<td style="padding-right:15px;width: 300px;border-bottom: solid 1px #696969">
			&nbsp;&nbsp;<lable>'. $row["f1_firstName"].'</lable></td>
			<td style="width: 60px;">SSN:</td>
			<td style="padding-right:15px;width: 300px;border-bottom: solid 1px #696969">
		   &nbsp;&nbsp;<lable>'. $row["f1_ssn"].'</lable></td>
			<td style="width: 90px;">Date: </td>
			<td style="width: 200px;border-bottom: solid 1px #696969">
			&nbsp;&nbsp;<lable>'. $currentDate.'</lable></td>
		  </tr>
		</tbody>
	</table>
	<table cellpadding="5" cellspacing="5" border="0" style="margin-bottom: 25px;">
		<tbody>
			<tr>
			 <td style="width: 190px;">Address:</td>
			  <td style="padding-right:15px;width: 200px;border-bottom: solid 1px #696969;">
			   &nbsp;&nbsp;<lable>'. $row["f1_address"].'</lable></td>
			  <td style="width: 160px;">Phone Number:</td>
			  <td style="width: 200px;border-bottom: solid 1px #696969;">
			  &nbsp;&nbsp;<lable>'.$row['f1_cellPhone'].'</lable></td>
		  </tr>
		</tbody>
	</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ImageSVG($file='images/tux.svg', $x=150, $y=265, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false);

$pdf->lastPage();

/*7th page*/
$pdf->addpage();

$html = '<table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
      <tbody>
		<tr>
        <td style="width:100%;">
        <table cellpadding="5" cellspacing="0" border="0" style="margin: auto;margin-bottom: 25px;">
            <tbody>
			<tr>
              <td style="width:1100px; background: #F9F9F9; padding:15px;">
                <table style="width:100%;margin-bottom: 25px;">
					<tbody>
					   <tr>
						<td>
						 <img src="images/shortLogo.png">
						</td>
						<td align="right">
						       <h3 style="">Safety Performance History Records Request</h3>
						</td>
						</tr>
					</tbody>
				</table>
                <p style="margin-bottom: 10px;margin-top: 0px;">
					<strong>To Former Employer:</strong>The federal Motor Carrier Safety Regulations Parts 391.23 & 382.413,require that a motor carrier obtain previous employment information.Therefore you are herby authorized to give SuperT Transport Inc.All information regarding my duties,character,positive drug or alcohol results to submit to a require testing while in your employment. 
				</p>
					<table cellpadding="5" cellspacing="0" border="0" style="width:1100px;margin-bottom: 25px;">
                          <tr>
							<td style="width:150px;">Signature: </td>
                            <td style="width:600px;">'.$pdf->ImageSVG($file='images/tux.svg', $x=30, $y=50, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false).'</td>
                            <td style="width:100px;">Date: </td>
							<td style="padding-right:15px;width: 200px;border-bottom: solid 1px #696969">'.$currentDate.'</td>
                          </tr>
					</table><br>		
					<table cellpadding="5" cellspacing="0" border="0" style="width:1100px;margin-bottom: 25px;">
                          <tr>
                            <td style="width:250px;">Previous Employer: </td>
                            <td style="padding-right:15px;width:200px;border-bottom: solid 1px #696969">
                            &nbsp;<label></label></td>
                            <td style="width:55px;">Fax: </td>
                            <td style="padding-right:15px;width: 200px;border-bottom: solid 1px #696969">
                            &nbsp;<label></label></td>
                            <td style="width: 90px;">Phone: </td>
                            <td style="width: 230px;border-bottom: solid 1px #696969">
                            &nbsp;<label></label></td>
                          </tr>
					</table>
					<table cellpadding="5" cellspacing="0" border="0" style="width:1100px;">
					  <tr>
						<td style="width:200px;">Applicnt Name:</td>
						<td style="padding-right:15px; width:250px; border-bottom:solid 1px #696969; ">&nbsp;<label></label></td>
						<td style="width:120px;">Soc Sec: </td>
						<td style="border-bottom:solid 1px #696969; width: 250px;">&nbsp;<label></label></td>
					  </tr>
					</table>
                        <table cellpadding="5" cellspacing="0" border="0" style="width:1100px;margin-bottom: 25px;">
                          <tbody><tr><td colspan="6" style="padding-bottom:15px;">The above named applicant has applied to superT Transport Inc. for a position as a Truck driver and states that He/she was employed by you as a.. &nbsp;&nbsp;<label>'.$row["f8_employBy"].'</label></td></tr>
                          <tr>
                            <td style="width:180px;">From:</td>
                            <td style="padding-right:15px; width: 350px; border-bottom:solid 1px #696969; ">&nbsp;<label></label></td>
                            <td style="width: 100px;">To: </td>
                            <td style="border-bottom:solid 1px #696969; width: 350px;">&nbsp;<label></label></td>
                          </tr>
                        </tbody>
					</table>
					<table cellpadding="5" cellspacing="0" border="0" style="width:1100px;margin-bottom: 25px;">
						<tbody>
						  <tr>
							<td style="width:500x;">Are dates for employment correct as a stated?: Yes &nbsp;&nbsp;No </td>
							<td style="width:70px;">Dates From:  </td>
							<td style="padding-right:15px;border-bottom: solid 1px #696969; width: 150px;"><label></label></td>
							<td style="width:70px;">To:</td>
							<td style="border-bottom: solid 1px #696969; width: 200px;">&nbsp;<label></label></td>
						  </tr>
						</tbody>
					</table>
					<table cellpadding="5" cellspacing="0" border="0" style="width:1100px;margin-bottom: 25px;">
						<tbody>
							<tr>
								<td style="width:650px;">Did applicant drive an 18 wheeler? yes  &nbsp;&nbsp;No &nbsp;&nbsp;</td>
								<td style="width:170px;">Trailer Type: </td>	
								<td style="width:270px; border-bottom: solid 1px #696969;"><label></label></td>
							</tr>
						</tbody>
					</table>
					<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
						<tbody>
							<tr>
								<td style="width:500px;">Were trips DOT regulated? yes  &nbsp;&nbsp;No &nbsp;&nbsp;</td>
								<td style="width:500px;">Was applicant conduct satisfactory? yes  &nbsp;&nbsp;No &nbsp;&nbsp;</td>
							</tr>
						</tbody>
					</table>
						<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px">
							<tbody>
								<tr>
									<td style="width:700px;">Did you consider this person a safe driver? yes &nbsp;&nbsp;No &nbsp;&nbsp;</td>
									<td style="width: 200px;">Comments:</td>
								</tr>
								<tr><td colspan="5" style="padding-top: 15px;border-bottom: solid 1px #696969">
							  &nbsp;&nbsp;<label></label></td></tr>
							</tbody>
						</table>
						<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
							<tbody>
								<tr>
									<td style="width:400px;">How many accident in the last 3 Years</td>
									<td style="width:150px; border-bottom: solid 1px #696969;"><label></label></td>
									<td style="width:380px;">Number of Preventable accidents: </td>
									<td style="width:150px; border-bottom: solid 1px #696969;"><label></label></td>
								</tr>
							</tbody>
						</table>
						<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
							<tbody>
								<tr>
									<td style="width:500px;">Why did applicant leave your employments</td>
									<td style="width:500px; border-bottom: solid 1px #696969;"><label></label></td>
								</tr>
							</tbody>
						</table>
						<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
							<tbody>
								<tr>
									<td style="width:500px;">Would you re-employ this applicant? yes &nbsp;&nbsp;No &nbsp;&nbsp;</td>
									<td style="width: 250px;">Upon review, Explain:</td>
								</tr>
								<tr><td colspan="5" style="padding-top: 15px;border-bottom: solid 1px #696969">
							  &nbsp;&nbsp;<label></label></td></tr>
							</tbody>
						</table>
						<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
							<tbody>
								<tr>
									<td style="width:900px;">Did applicant ever test positive for a controlled substance in the last 3 year? yes  &nbsp;&nbsp;No &nbsp;&nbsp;</td>
								</tr>
							</tbody>
						</table>
						<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
							<tbody>
								<tr>
									<td style="width:900px;">Has the applicant ever refused a test for a drug or alcohol in the last 3 years? yes  &nbsp;&nbsp;No &nbsp;&nbsp;</td>
								</tr>
							</tbody>
						</table>
						<table cellpadding="5" cellspacing="0" border="0" style="width:100%;margin-bottom: 25px;">
							<tbody>
								<tr>
									<td style="width:900px;">Has the applicant had an alcohol with breath alcohol concentration 0.04 or greater than in the last 3 year? yes  &nbsp;&nbsp;No &nbsp;&nbsp;</td>
								</tr>
							</tbody>
						</table>
				<p style="margin-bottom: 10px;margin-top: 0px;">
					We appreciate your help in completing this informationplease return fax to(208) 754-7397
				</p>	
			</td><!--/container -->
            </tr>
          </tbody>
		</table>
        </td>
      </tr>
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ImageSVG($file='images/tux.svg', $x=150, $y=265, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false);

$pdf->lastPage();

/*8th page*/
$pdf->addpage();

$html='<table cellpadding="0" cellspacing="0" border="0" style="width: 1100px;margin-bottom: 25px;">
      <tbody>
		<tr>
        <td style="width:100%;">
          <table cellpadding="0" cellspacing="0" border="0" style="margin: auto;margin-bottom: 25px;">
            <tbody>
				<tr>
					<td style="width:1100px; background: #F9F9F9; padding:15px;">
					<table style="width: 1100px;margin-bottom: 5px;">
						<tbody>
							<tr>
							 <td align="center">
							   <h3 style="text-align:center">Part 2-FMCSA Notification of Driver Rights</h3>
							</td>
						  </tr>
						</tbody>
					</table>
					<p>
						In compliance with 49 CFR Part 40 ??391.23 you have certain rights regarding the safety performance history information that will be provided to prospective employers. I) You have the right to review information provided by previous employers. II) You have the
						right to have errors in the information corrected by the previous employer and for that
						previous employer to re-send the corrected information to prospective employers. III)
						You have the right to have a rebuttal statement attached to the alleged erroneous
						information, if the previous employer and the driver cannot agree on the accuracy of the
						information. (2) Drivers who have previous DOT regulated employment history in the
						preceding three years and wish to review previous employer-provided investigative
						information must submit a written request to prospective employers. This may be done at
						any time, including when applying, or as late as 30 days after being employed or being
						notified of denial of employment. Prospective employers must provide this information
						within five business days of receiving the written request. If prospective employers have
						not yet received the requested information from the previous employer, then the five day
						deadline will begin when the requested safety performance history information is
						received. If you have not arranged to pick up or receive the requested records within 30
						days of prospective employers making them available, the prospective employers may
						consider you to have waived your request to review the record. 
					</p>
					<p>
						<img src="images/right.png">
						<strong style="padding: 15px;">I Have Read The Notification of Drive Rights Above </strong>
					</p>
				</td><!--/container -->
            </tr>
          </tbody>
		</table>
        </td>
      </tr>
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ImageSVG($file='images/tux.svg', $x=150, $y=265, $w='', $h=20, $link='', $align='', $palign='', $border=0, $fitonpage=false);

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
}
//============================================================+
// END OF FILE
//============================================================+
?>