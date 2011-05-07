<?php
/**********
View Name: view
Description: This view will display detailed information about a particular incident and the associated worklogs.
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/24 02:59 PM
Last Modified By: William DiStefano
**********/

foreach ($selectedIncident as $incident):
//BREADCRUMB//
$breadCrumbText = 'Incidents';
$breadCrumbUrl = '/incidents/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'View - ID: ' . $incident['incidents']['id'];
$breadCrumbUrl = '/incidents/view/' . $incident['incidents']['id'];
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////
endforeach;


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 2) {

		///////////////////////////////////////////////////////////////////////////////////////
		//THE FOLLOWING CODE FOR THE TECHNICIAN GROUP IS -NOT- IDENTICAL TO ADMIN's CODE BELOW

		foreach ($selectedIncident as $incident):


		echo '<div class="navTools">';
					echo 
						'&nbsp;' .
						$html->link($html->image("buttons/button_incident-modify.png", array('title' => 'Modify', 'border' => 0)), "/incidents/edit/{$incident['incidents']['id']}", array('escape'=>false, 'class'=>'navTools'))
					;
		echo '</div>';


		//Start HTML Form
		echo $form->create('incidents', array('action' => 'edit'));

		//We need to have the ID present so cakephp fills in most form fields automatically
		echo $form->input('id', array('type'=>'hidden'));


		echo '
		<font size="-1">
		<table border="0" bordercolor="#333333" cellspacing="5" cellpadding="0">
		<tr><td height="1" width="30"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
		<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
		  <tr><td bgcolor="#333333" height="1" width="100%" colspan="2"></td></tr>
		  <tr width="100%">
			<td align="center" valign="top" width="30"><strong>
			&nbsp;
			</strong>
			</td>
			<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
			</td></tr><tr>
			<td align="right" valign="top" width="100">
			Created: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo $incident['incidents']['created'];
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Last Modified: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo $incident['incidents']['modified'];
		echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Submitted By: &nbsp;&nbsp;
		</td>
		<td valign="top">';
				if(!empty($incident['incidents']['user_id']) || ($incident['incidents']['user_id'] != 0)) {
					foreach($users as $user) :
						if($user['users']['id'] == $incident['incidents']['user_id']) {

							echo'
							<table cellpadding="0" cellspacing="0" width="100%">
							  <tr>
								<td width="80">Name: </td>
								<td>';
									echo $user['users']['last_name'] . ', ' . $user['users']['first_name'];
									if (!empty($user['users']['middle_name'])) {  //show the middle initial
									  echo ' ' . substr($user['users']['middle_name'], 0, 1) . '.';
									} //end if
									echo ' ('. $html->link($user['users']['username'], '/users/view/' . $user['users']['id']) .')';
							echo'</td>
							  </tr>
							  <tr>
								<td width="80">Network Port:</td>
								<td>'. $user['users']['network_port'] .'</td>
							  </tr>
							  <tr>
								<td width="80">Email:</td>
								<td>'. $user['users']['email'] .'</td>
							  </tr>
							  <tr>
								<td width="80">Location:</td>
								<td>';
									foreach($locations as $location) :
										if($user['users']['location_id'] == $location['locations']['id']) {
											echo $location['locations']['name'];
										} //end if
									endforeach;
							echo'</td>
							  </tr>
							</table>';

						} //end if
					endforeach;
				} //end if
	echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Computer: &nbsp;&nbsp;
			</td>
			<td valign="top">';
					if(!empty($incident['incidents']['user_id']) || ($incident['incidents']['user_id'] != 0)) {
						foreach($users as $user) :
							if($user['users']['id'] == $incident['incidents']['user_id']) {

								foreach($computers as $computer) :
									if($user['users']['computer_id'] == $computer['computers']['id']) {
										echo '<table width="100%" cellspacing="0" cellpadding-"0">
												<tr>
												  <td width="80">
													Name: 
												  </td>
												  <td>
													'. $html->link($computer['computers']['name'], '/computers/view/' . $computer['computers']['id']) .' 
												  </td>
												</tr>
												<tr>
												  <td width="80">
													Serial #: 
												  </td>
												  <td>'. $computer['computers']['serial_number'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													Memory: 
												  </td>
												  <td>'. $computer['computers']['memory'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													CPU: 
												  </td>
												  <td>'. $computer['computers']['processor'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													HDD: 
												  </td>
												  <td>'. $computer['computers']['hdd_space'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													O/S: 
												  </td>
												  <td>'. $computer['computers']['operating_system'] .'
												  </td>
												</tr>
											  </table>';
									} //end if
								endforeach;

							} //end if
						endforeach;
					} //end if
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Category: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				foreach($categories as $category) :
					if($category['categories']['id'] == $incident['incidents']['category_id']) {
						echo $category['categories']['name'];
					} //end if
				endforeach;
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Priority: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				  SWITCH($incident['incidents']['priority']) {
					CASE 0:
						echo '<font color="darkgreen"> <b> LOW </b> </font>';
						break;
					CASE 1:
						echo '<font color="#FFCC66"> <b> MEDIUM </b> </font>';
						break;
					CASE 2:
						echo '<font color="#FF9900"> <b> HIGH </b> </font>';
						break;
					CASE 3:
						echo '<font color="red"> <b> WORK-STOPPAGE </b> </font>';
						break;
					DEFAULT:
						echo '<font color="darkgreen"> --- </font>';
				  } //end switch
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Status: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				  SWITCH($incident['incidents']['status']) {
					CASE 0:
						echo 'pending';
						break;
					CASE 1:
						echo 'accepted';
						break;
					CASE 2:
						echo 'resolved'; //This status level will be archived
						break;
					CASE 3:
						echo 'duplicate'; //This status level will be archived
						break;
					CASE 4:
						echo 'inaccurate'; //This status level will be archived
						break;
					CASE 5:
						echo 'unresolvable'; //This status level will be archived
						break;
					DEFAULT:
						echo '---';
				  }//end switch
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Admin/Tech: &nbsp;&nbsp;
			</td>
			<td valign="top">';
			if(!empty($incident['incidents']['admin_id']) || ($incident['incidents']['admin_id'] != 0)) {
				foreach($admins as $admin) :
					if($admin['admins']['id'] == $incident['incidents']['admin_id']) {

						echo $html->link($admin['admins']['username'], '/admins/view/' . $admin['admins']['id']);

					} //end if
				endforeach;
			} //end if
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Description: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo $incident['incidents']['description'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Problem: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			$problemContent = html_entity_decode($incident['incidents']['content']);
			echo $problemContent;
			unset($problemContent);
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Worklog: &nbsp;&nbsp;
			</td>
			<td valign="top">';

				foreach($worklogs as $worklog) :

					echo '<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
							  <td height="1" bgcolor="#000000">
							  </td>
							</tr>
							<tr>
							  <td width="100%">'.
								$worklog['worklogs']['created']
							  .'&nbsp; | &nbsp;';
								if(!empty($worklog['worklogs']['user_id']) || ($worklog['worklogs']['user_id'] != 0) ) {
									foreach($users as $user) :
										if($user['users']['id'] == $worklog['worklogs']['user_id']) {

											echo $user['users']['first_name'];

											if (!empty($user['users']['middle_name'])) {  //show the middle initial
											  echo ' ' . substr($user['users']['middle_name'], 0, 1) . '.';
											} //end if

											echo ' ' .$user['users']['last_name'];

											echo ' ('. $user['users']['username'] .', employee)';

										} //end if
									endforeach;
								} else if(!empty($worklog['worklogs']['admin_id']) || ($worklog['worklogs']['admin_id'] != 0) ) {
									foreach($admins as $admin) :
										if($admin['admins']['id'] == $worklog['worklogs']['admin_id']) {

											echo ' ' .$admin['admins']['first_name'];

											if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
											  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
											} //end if

											echo ' ' .$admin['admins']['last_name'];

											echo ' ('. $admin['admins']['username'] .', ';
											if($admin['admins']['type'] == 1) {
												echo 'administrator)';
											} else if($admin['admins']['type'] == 2) {
												echo 'technician)';
											} //end if

										} //end if
									endforeach;
								} //end if
							echo'</b>
							  </td>
							</tr>
							<tr>
							  <td>';
								$worklogContent = html_entity_decode($worklog['worklogs']['content']);
								echo $worklogContent;
								unset($worklogContent);
						echo' </td>
							</tr>
						  </table>';

				endforeach;

				if( !empty($worklogs) ) {
					echo '<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
							  <td height="1" bgcolor="#000000">
							  </td>
							</tr>
						  </table>';
				} //end if

			echo'</td>
			</tr></table>
		</td></tr>
		 <tr>
			<td>&nbsp;</td>
			<td align="center" valign="top">';
			echo '</font>' . $form->end(); //end the form
		echo'</td></tr>
		</table>';

		endforeach;

		/////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////

	}else if($sessionAdmin[0]['admins']['type'] == 1) {

	//HEADING TEXT//
	echo '<h2> View Details </h2>';
	////////////////


	foreach ($selectedIncident as $incident):


	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_incident-modify.png", array('title' => 'Modify', 'border' => 0)), "/incidents/edit/{$incident['incidents']['id']}", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';


	//Start HTML Form
	echo $form->create('incidents', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));


	echo '
	<font size="-1">
	<table border="0" bordercolor="#333333" cellspacing="5" cellpadding="0">
	<tr><td height="1" width="30"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
	<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
	  <tr><td bgcolor="#333333" height="1" width="100%" colspan="2"></td></tr>
	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		&nbsp;
		</strong>
		</td>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		</td></tr><tr>
		<td align="right" valign="top" width="100">
		Created: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $incident['incidents']['created'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $incident['incidents']['modified'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Submitted By: &nbsp;&nbsp;
		</td>
		<td valign="top">';
				if(!empty($incident['incidents']['user_id']) || ($incident['incidents']['user_id'] != 0)) {
					foreach($users as $user) :
						if($user['users']['id'] == $incident['incidents']['user_id']) {

							echo'
							<table cellpadding="0" cellspacing="0" width="100%">
							  <tr>
								<td width="80">Name: </td>
								<td>';
									echo $user['users']['last_name'] . ', ' . $user['users']['first_name'];
									if (!empty($user['users']['middle_name'])) {  //show the middle initial
									  echo ' ' . substr($user['users']['middle_name'], 0, 1) . '.';
									} //end if
									echo ' ('. $html->link($user['users']['username'], '/users/view/' . $user['users']['id']) .')';
							echo'</td>
							  </tr>
							  <tr>
								<td width="80">Network Port:</td>
								<td>'. $user['users']['network_port'] .'</td>
							  </tr>
							  <tr>
								<td width="80">Email:</td>
								<td>'. $user['users']['email'] .'</td>
							  </tr>
							  <tr>
								<td width="80">Location:</td>
								<td>';
									foreach($locations as $location) :
										if($user['users']['location_id'] == $location['locations']['id']) {
											echo $location['locations']['name'];
										} //end if
									endforeach;
							echo'</td>
							  </tr>
							</table>';

						} //end if
					endforeach;
				} //end if
	echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Computer: &nbsp;&nbsp;
			</td>
			<td valign="top">';
					if(!empty($incident['incidents']['user_id']) || ($incident['incidents']['user_id'] != 0)) {
						foreach($users as $user) :
							if($user['users']['id'] == $incident['incidents']['user_id']) {

								foreach($computers as $computer) :
									if($user['users']['computer_id'] == $computer['computers']['id']) {
										echo '<table width="100%" cellspacing="0" cellpadding-"0">
												<tr>
												  <td width="80">
													Name: 
												  </td>
												  <td>
													'. $html->link($computer['computers']['name'], '/computers/view/' . $computer['computers']['id']) .' 
												  </td>
												</tr>
												<tr>
												  <td width="80">
													Serial #: 
												  </td>
												  <td>'. $computer['computers']['serial_number'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													Memory: 
												  </td>
												  <td>'. $computer['computers']['memory'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													CPU: 
												  </td>
												  <td>'. $computer['computers']['processor'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													HDD: 
												  </td>
												  <td>'. $computer['computers']['hdd_space'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													O/S: 
												  </td>
												  <td>'. $computer['computers']['operating_system'] .'
												  </td>
												</tr>
											  </table>';
									} //end if
								endforeach;

							} //end if
						endforeach;
					} //end if
		echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Category: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			foreach($categories as $category) :
				if($category['categories']['id'] == $incident['incidents']['category_id']) {
					echo $category['categories']['name'];
				} //end if
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Priority: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			  SWITCH($incident['incidents']['priority']) {
				CASE 0:
					echo '<font color="darkgreen"> <b> LOW </b> </font>';
					break;
				CASE 1:
					echo '<font color="#FFCC66"> <b> MEDIUM </b> </font>';
					break;
				CASE 2:
					echo '<font color="#FF9900"> <b> HIGH </b> </font>';
					break;
				CASE 3:
					echo '<font color="red"> <b> WORK-STOPPAGE </b> </font>';
					break;
				DEFAULT:
					echo '<font color="darkgreen"> --- </font>';
			  } //end switch
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Status: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			  SWITCH($incident['incidents']['status']) {
				CASE 0:
					echo 'pending';
					break;
				CASE 1:
					echo 'accepted';
					break;
				CASE 2:
					echo 'resolved'; //This status level will be archived
					break;
				CASE 3:
					echo 'duplicate'; //This status level will be archived
					break;
				CASE 4:
					echo 'inaccurate'; //This status level will be archived
					break;
				CASE 5:
					echo 'unresolvable'; //This status level will be archived
					break;
				DEFAULT:
					echo '---';
			  }//end switch
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Admin/Tech: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			if(!empty($incident['incidents']['admin_id']) || ($incident['incidents']['admin_id'] != 0)) {
				foreach($admins as $admin) :
					if($admin['admins']['id'] == $incident['incidents']['admin_id']) {

						echo $html->link($admin['admins']['username'], '/admins/view/' . $admin['admins']['id']);

					} //end if
				endforeach;
			} //end if
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Description: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $incident['incidents']['description'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Problem: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			$problemContent = html_entity_decode($incident['incidents']['content']);
			echo $problemContent;
			unset($problemContent);
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Worklog: &nbsp;&nbsp;
		</td>
		<td valign="top">';

			foreach($worklogs as $worklog) :

				echo '<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <td height="1" bgcolor="#000000">
						  </td>
						</tr>
						<tr>
						  <td width="100%">'.
							$worklog['worklogs']['created']
						  .'&nbsp; | &nbsp;';
							if(!empty($worklog['worklogs']['user_id']) || ($worklog['worklogs']['user_id'] != 0) ) {
								foreach($users as $user) :
									if($user['users']['id'] == $worklog['worklogs']['user_id']) {

										echo $user['users']['first_name'];

										if (!empty($user['users']['middle_name'])) {  //show the middle initial
										  echo ' ' . substr($user['users']['middle_name'], 0, 1) . '.';
										} //end if

										echo ' ' .$user['users']['last_name'];

										echo ' ('. $user['users']['username'] .', employee)';

									} //end if
								endforeach;
							} else if(!empty($worklog['worklogs']['admin_id']) || ($worklog['worklogs']['admin_id'] != 0) ) {
								foreach($admins as $admin) :
									if($admin['admins']['id'] == $worklog['worklogs']['admin_id']) {

										echo $admin['admins']['first_name'];

										if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
										  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
										} //end if

										echo ' ' .$admin['admins']['last_name'];

										echo ' ('. $admin['admins']['username'] .', ';
										if($admin['admins']['type'] == 1) {
											echo 'administrator)';
										} else if($admin['admins']['type'] == 2) {
											echo 'technician)';
										} //end if

									} //end if
								endforeach;
							} //end if
						echo'</b>
						  </td>
						</tr>
						<tr>
						  <td>';
							$worklogContent = html_entity_decode($worklog['worklogs']['content']);
							echo $worklogContent;
							unset($worklogContent);
					echo' </td>
						</tr>
					  </table>';

			endforeach;

			if( !empty($worklogs) ) {
				echo '<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <td height="1" bgcolor="#000000">
						  </td>
						</tr>
					  </table>';
			} //end if

		echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end(); //end the form
	echo'</td></tr>
	</table>';

	endforeach;


	} else if(empty($sessionUser) == false) {


	foreach ($selectedIncident as $incident):

	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_incident-modify_worklog.png", array('title' => 'Worklog', 'border' => 0)), "/incidents/edit/{$incident['incidents']['id']}", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';

//Only show the incident if it belongs to the user
if( !empty($incident['incidents']['user_id']) && ($sessionUser[0]['users']['id'] == $incident['incidents']['user_id']) ) {

	//Start HTML Form
	echo $form->create('incidents', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));


	echo '
	<font size="-1">
	<table border="0" bordercolor="#333333" cellspacing="5" cellpadding="0">
	<tr><td height="1" width="30"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
	<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
	  <tr><td bgcolor="#333333" height="1" width="100%" colspan="2"></td></tr>
	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		&nbsp;
		</strong>
		</td>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		</td></tr><tr>
		<td align="right" valign="top" width="100">
		Created: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $incident['incidents']['created'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $incident['incidents']['modified'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Submitted By: &nbsp;&nbsp;
		</td>
		<td valign="top">';
				if(!empty($incident['incidents']['user_id']) || ($incident['incidents']['user_id'] != 0)) {
					foreach($users as $user) :
						if($user['users']['id'] == $incident['incidents']['user_id']) {

							echo'
							<table cellpadding="0" cellspacing="0" width="100%">
							  <tr>
								<td width="80">Name: </td>
								<td>';
									echo $user['users']['last_name'] . ', ' . $user['users']['first_name'];
									if (!empty($user['users']['middle_name'])) {  //show the middle initial
									  echo ' ' . substr($user['users']['middle_name'], 0, 1) . '.';
									} //end if
									echo ' ('. $user['users']['username'] .')';
							echo'</td>
							  </tr>
							  <tr>
								<td width="80">Network Port:</td>
								<td>'. $user['users']['network_port'] .'</td>
							  </tr>
							  <tr>
								<td width="80">Email:</td>
								<td>'. $user['users']['email'] .'</td>
							  </tr>
							  <tr>
								<td width="80">Location:</td>
								<td>';
									foreach($locations as $location) :
										if($user['users']['location_id'] == $location['locations']['id']) {
											echo $location['locations']['name'];
										} //end if
									endforeach;
							echo'</td>
							  </tr>
							</table>';

						} //end if
					endforeach;
				} //end if
	echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Computer: &nbsp;&nbsp;
			</td>
			<td valign="top">';
					if(!empty($incident['incidents']['user_id']) || ($incident['incidents']['user_id'] != 0)) {
						foreach($users as $user) :
							if($user['users']['id'] == $incident['incidents']['user_id']) {

								foreach($computers as $computer) :
									if($user['users']['computer_id'] == $computer['computers']['id']) {
										echo '<table width="100%" cellspacing="0" cellpadding-"0">
												<tr>
												  <td width="80">
													Name: 
												  </td>
												  <td>
													'. $computer['computers']['name'] .' 
												  </td>
												</tr>
												<tr>
												  <td width="80">
													Serial #: 
												  </td>
												  <td>'. $computer['computers']['serial_number'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													Memory: 
												  </td>
												  <td>'. $computer['computers']['memory'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													CPU: 
												  </td>
												  <td>'. $computer['computers']['processor'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													HDD: 
												  </td>
												  <td>'. $computer['computers']['hdd_space'] .'
												  </td>
												</tr>
												<tr>
												  <td width="80">
													O/S: 
												  </td>
												  <td>'. $computer['computers']['operating_system'] .'
												  </td>
												</tr>
											  </table>';
									} //end if
								endforeach;

							} //end if
						endforeach;
					} //end if
		echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Category: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			foreach($categories as $category) :
				if($category['categories']['id'] == $incident['incidents']['category_id']) {
					echo $category['categories']['name'];
				} //end if
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Priority: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			  SWITCH($incident['incidents']['priority']) {
				CASE 0:
					echo '<font color="darkgreen"> <b> LOW </b> </font>';
					break;
				CASE 1:
					echo '<font color="#FFCC66"> <b> MEDIUM </b> </font>';
					break;
				CASE 2:
					echo '<font color="#FF9900"> <b> HIGH </b> </font>';
					break;
				CASE 3:
					echo '<font color="red"> <b> WORK-STOPPAGE </b> </font>';
					break;
				DEFAULT:
					echo '<font color="darkgreen"> --- </font>';
			  } //end switch
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Status: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			  SWITCH($incident['incidents']['status']) {
				CASE 0:
					echo 'pending';
					break;
				CASE 1:
					echo 'accepted';
					break;
				CASE 2:
					echo 'resolved'; //This status level will be archived
					break;
				CASE 3:
					echo 'duplicate'; //This status level will be archived
					break;
				CASE 4:
					echo 'inaccurate'; //This status level will be archived
					break;
				CASE 5:
					echo 'unresolvable'; //This status level will be archived
					break;
				DEFAULT:
					echo '---';
			  }//end switch
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Admin/Tech: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			if(!empty($incident['incidents']['admin_id']) || ($incident['incidents']['admin_id'] != 0)) {
				foreach($admins as $admin) :
					if($admin['admins']['id'] == $incident['incidents']['admin_id']) {

						echo $admin['admins']['username'];

					} //end if
				endforeach;
			} //end if
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Description: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $incident['incidents']['description'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Problem: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			$problemContent = html_entity_decode($incident['incidents']['content']);
			echo $problemContent;
			unset($problemContent);
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Worklog: &nbsp;&nbsp;
		</td>
		<td valign="top">';

			foreach($worklogs as $worklog) :

				echo '<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <td height="1" bgcolor="#000000">
						  </td>
						</tr>
						<tr>
						  <td width="100%">'.
							$worklog['worklogs']['created']
						  .'&nbsp; | &nbsp;';
							if(!empty($worklog['worklogs']['user_id']) || ($worklog['worklogs']['user_id'] != 0) ) {
								foreach($users as $user) :
									if($user['users']['id'] == $worklog['worklogs']['user_id']) {

										echo $user['users']['first_name'];

										if (!empty($user['users']['middle_name'])) {  //show the middle initial
										  echo ' ' . substr($user['users']['middle_name'], 0, 1) . '.';
										} //end if

										echo ' ' . $user['users']['last_name'];

										echo ' ('. $user['users']['username'] .', employee)';

									} //end if
								endforeach;
							} else if(!empty($worklog['worklogs']['admin_id']) || ($worklog['worklogs']['admin_id'] != 0) ) {
								foreach($admins as $admin) :
									if($admin['admins']['id'] == $worklog['worklogs']['admin_id']) {

										echo $admin['admins']['first_name'];

										if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
										  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
										} //end if

										echo ' ' . $admin['admins']['last_name'];

										echo ' ('. $admin['admins']['username'] .', ';
										if($admin['admins']['type'] == 1) {
											echo 'administrator)';
										} else if($admin['admins']['type'] == 2) {
											echo 'technician)';
										} //end if

									} //end if
								endforeach;
							} //end if
						echo'</b>
						  </td>
						</tr>
						<tr>
						  <td>';
							$worklogContent = html_entity_decode($worklog['worklogs']['content']);
							echo $worklogContent;
							unset($worklogContent);
					echo' </td>
						</tr>
					  </table>';

			endforeach;

			if( !empty($worklogs) ) {
				echo '<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <td height="1" bgcolor="#000000">
						  </td>
						</tr>
					  </table>';
			} //end if

		echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end(); //end the form
	echo'</td></tr>
	</table>';

} //end if

	endforeach;

	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	}

?>