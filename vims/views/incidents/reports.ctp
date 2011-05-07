<?php
/**********
View Name: reports
Description: This view is the main reports page for administrators, this page will list the available reports
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 03:00 PM
Last Modified By: William DiStefano
**********/

//BREADCRUMB//
$breadCrumbText = 'Reports';
$breadCrumbUrl = '/incidents/reports';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Reports </h2>';
////////////////

	//Check if administrator or not
	if(!empty($sessionAdmin) == true) {

		//Check administrator type
		if($sessionAdmin[0]['admins']['type'] == 1) {


			/////
			//They are an administrator
			/////

				//Display any error messages if they exist
				if (!empty($errorMessage)) {
					echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
				} //end if

		   echo'<ul>
				  <li>'. $html->link('Number of Incidents Per Category', '/incidents/rptcountincidents') .'</li>
				  <li>'. $html->link('Enabled Employees and Computers', '/incidents/rptemployeescomputers') .'</li>
				  <li>'. $html->link('Enabled Admins/Techs and Computers', '/incidents/rptadminscomputers') .'</li>
				  <li>'. $html->link('All Unresolved Incidents', '/incidents/rptpendingandacceptedincidents') .'</li>
				  <li>'. $html->link('Number of Incidents Per Month', '/incidents/rptcountincidentsmonthly') .'</li>
				  <li>'. $html->link('Completed Incidents per Admin/Tech (past 30 days)', '/incidents/rptcountincidentsperadmin') .'</li>
				</ul>';

		} //end if


	} else {

		/////
		//They are not an administrator
		/////

		echo'<div class="divContentCenter">';

			//Display any error messages if they exist
			if (!empty($errorMessage)) {
				echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
			} //end if


			echo 'You do not have access to this area!';

		echo'</div>';

	} //end if

?>