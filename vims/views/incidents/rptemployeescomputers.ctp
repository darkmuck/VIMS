<?php
/**********
View Name: rptCountIncidents
Description: This view is the report which shows the Enabled Employees and their Computers
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/04/05 02:15 PM
Last Modified By: William DiStefano
**********/

//HEADING TEXT//
echo '<div class="divContentCenter"><h2> Enabled Employees and Computers Report </h2></div>';
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
						<table width="65%" cellpadding="0" cellspacing="0" border="1">
						<tr>
						  <th align="center"> Computer Name (Serial Number) </th>
						  <th align="center"> Employee Name (Username) </th>
						  <th align="center"> Location </th>
						</tr>';

						foreach($reportData as $data) :
							echo'<tr>
								  <td align="center">'. $data['computers']['name'] .' ('. $data['computers']['serial_number'] .') </td>
								  <td align="center">'. $data['users']['first_name'] .' '. $data['users']['last_name'] . ' ('.
									$data['users']['username'] .') </td>
								  <td align="center">'. $data['locations']['name'] .'</td>
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