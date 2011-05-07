<?php
/**********
View Name: rptCountIncidents
Description: This view is the report which shows the All Unresolved Incidents
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/04/05 01:30 PM
Last Modified By: William DiStefano
**********/

//HEADING TEXT//
echo '<div class="divContentCenter"><h2> All Unresolved Incidents Report </h2></div>';
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
						<table width="70%" cellpadding="0" cellspacing="0" border="1">
						<tr>
						  <th align="center"> ID </th>
						  <th align="center"> Created </th>
						  <th align="center"> Priority </th>
						  <th align="center"> Category </th>
						  <th align="center"> User </th>
						  <th align="center"> Admin/Tech </th>
						  <th align="center"> Description </th>
						</tr>';

						foreach($reportData as $data) :
							echo'<tr>
								  <td align="center">'. $data['incidents']['id'] .'</td>
								  <td align="center">'. $data['incidents']['created'] .' </td>
								  <td align="center">'; 
									  SWITCH($data['incidents']['priority']) {
										CASE 0:
											echo '<font size="-2" color="darkgreen"> <b> LOW </b> </font>';
											break;
										CASE 1:
											echo '<font size="-2" color="#FFCC66"> <b> MEDIUM </b> </font>';
											break;
										CASE 2:
											echo '<font size="-2" color="#FF9900"> <b> HIGH </b> </font>';
											break;
										CASE 3:
											echo '<font size="-2" color="red"> <b> WORK-STOPPAGE </b> </font>';
											break;
										DEFAULT:
											echo '<font size="-2" color="darkgreen"> --- </font>';
									  } //end switch
								echo'</td>
								  <td align="center">'. $data['categories']['name'] .'</td>
								  <td align="center">'. $data['users']['username'] .'</td>
								  <td align="center">'. $data['admins']['username'] .'</td>
								  <td align="center">'; 
									//If the incident's description is longer than 30 shorten it
									if (strlen($data['incidents']['description']) >= 30) {  
									  echo ' ' . substr($data['incidents']['description'], 0, 30) . '...';
									} else {
									  echo $data['incidents']['description'];
									} //end if
								echo'</td>
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