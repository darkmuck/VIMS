<?php
/**********
View Name: rptCountIncidents
Description: This view is the report which shows the number of incidents per admin/tech
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/04/05 01:20 PM
Last Modified By: William DiStefano
**********/

//HEADING TEXT//
echo '<div class="divContentCenter"><h2> Completed Incidents per Admin/Tech (past 30 days) Report </h2></div>';
////////////////

	//Check if administrator or not
	if(!empty($sessionAdmin) == true) {

		//Check administrator type
		if($sessionAdmin[0]['admins']['type'] == 1) {


			/////
			//They are an administrator
			/////

			echo'<div class="divContentCenter">';
				//Display any error messages if they exist
				if (!empty($errorMessage)) {
					echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
				} //end if

				echo '<table width="100%"><tr><td width="100%" align="center">
						<table width="40%" cellpadding="0" cellspacing="0" border="1">
						<tr>
						  <th align="center"> Number of Incidents </th>
						  <th align="center"> Full Name </th>
						  <th align="center"> Username </th>
						</tr>';

						foreach($reportData as $data) :
							echo'<tr>
								  <td align="center">'. $data[0]['numberIncidents'] .' </td>
								  <td align="center">'. $data['admins']['first_name'] . ' ' . $data['admins']['middle_name'] . $data['admins']['last_name'] .'</td>
								  <td align="center">'. $data['admins']['username'] .' </td>
								</tr>';
						endforeach;


				echo' </table>
					</td></tr></table>
					</div>';
				

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