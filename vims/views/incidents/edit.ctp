<?php
/**********
View Name: edit
Description: This view will display detailed information about a particular incident/worklog and allow modifications
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/24 02:58 PM
Last Modified By: William DiStefano
**********/

//The following is required to use the tinyMCE editor
echo'
	<script type="text/javascript">
	    tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7"
	    });
	</script>
';


foreach ($selectedIncident as $incident):
//BREADCRUMB//
$breadCrumbText = 'Incidents';
$breadCrumbUrl = '/incidents/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Modify - ID: ' . $incident['incidents']['id'];
$breadCrumbUrl = '/incidents/edit/' . $incident['incidents']['id'];
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////
endforeach;

//HEADING TEXT//
echo '<h2> Modify Incident </h2>';
////////////////


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 2) {


		//////////TECHNICIAN CODE - THIS IS IDENTICAL TO THE ADMINISTRATOR CODE//////////
		/////////////////////////////////////////////////////////////////////////////////

foreach ($selectedIncident as $incident):
	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/incidents/", array('escape'=>false, 'class'=>'navTools'))

				;
	echo '</div>';
endforeach;

		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if


foreach ($selectedIncident as $incident){
	//Start HTML Form
	echo $form->create('Incident', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden', 'value' => $incident['incidents']['id']));

	//We need to have this admin_id here (but hidden) so it is added to the database upon submitting
	echo $form->input('admin_id', array('type'=>'hidden', 'value' => $sessionAdmin[0]['admins']['id']));

	//We need to have this user_id here (but hidden) so it is added to the database upon submitting
	echo $form->input('user_id', array('type'=>'hidden', 'value' => $incident['incidents']['user_id']));

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

			echo '<select name="data[Incident][category_id]" id="IncidentCategoryId">';

					foreach($categories as $categoryLevel1) :
					
						if( ($categoryLevel1['categories']['id'] == $incident['incidents']['category_id']) && ($categoryLevel1['categories']['enabled'] == 0) ) {
							echo '<option value="'. $categoryLevel1['categories']['id'] .'" SELECTED="selected">'.
									$categoryLevel1['categories']['name'] .' (disabled)</option>';
						} //end if

					endforeach;

					foreach($categories as $categoryLevel1) :

						if($categoryLevel1['categories']['parent_id'] == 0  && $categoryLevel1['categories']['enabled'] == 1) {

							echo '<option value="'. $categoryLevel1['categories']['id'] .'" ';
								if($incident['incidents']['category_id'] == $categoryLevel1['categories']['id']) {
									echo ' SELECTED="selected">';
								} else {
									echo '>';
								} //end if
							echo $categoryLevel1['categories']['name'];
							echo '</option>';

							foreach($categories as $categoryLevel2) :

								if(($categoryLevel2['categories']['parent_id'] == $categoryLevel1['categories']['id'])  && 
									$categoryLevel2['categories']['enabled'] == 1) {

									echo '<option value="'. $categoryLevel2['categories']['id'] .'" ';
										if($incident['incidents']['category_id'] == $categoryLevel2['categories']['id']) {
											echo ' SELECTED="selected">';
										} else {
											echo '>';
										} //end if
									echo '--'. $categoryLevel2['categories']['name'];
									echo '</option>';

									foreach($categories as $categoryLevel3) :

										if(($categoryLevel3['categories']['parent_id'] == $categoryLevel2['categories']['id'])  && 
											$categoryLevel3['categories']['enabled'] == 1) {

											echo '<option value="'. $categoryLevel3['categories']['id'] .'" ';
												if($incident['incidents']['category_id'] == $categoryLevel3['categories']['id']) {
													echo ' SELECTED="selected">';
												} else {
													echo '>';
												} //end if
											echo '----'. $categoryLevel3['categories']['name'];
											echo '</option>';

										} //end if

									endforeach;

								} //end if

							endforeach;

						} //end if

					endforeach;

				echo '</select> &nbsp;';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Priority: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo '<select name="data[Incident][priority]" id="IncidentPriority">';

						echo '<option value=0 ';
							if($incident['incidents']['priority'] == 0) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'LOW';
						echo '</option>';
						echo '<option value=1 ';
							if($incident['incidents']['priority'] == 1) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'MEDIUM';
						echo '</option>';
						echo '<option value=2 ';
							if($incident['incidents']['priority'] == 2) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'HIGH';
						echo '</option>';
						echo '<option value=3 ';
							if($incident['incidents']['priority'] == 3) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'WORK-STOPPAGE';
						echo '</option>';
				echo '</select>';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Status: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo '<select name="data[Incident][status]" id="IncidentPriority">';

						echo '<option value=0 ';
							if($incident['incidents']['status'] == 0) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Pending';
						echo '</option>';
						echo '<option value=1 ';
							if($incident['incidents']['status'] == 1) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Accepted';
						echo '</option>';
						echo '<option value=2 ';
							if($incident['incidents']['status'] == 2) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Resolved';
						echo '</option>';
						echo '<option value=3 ';
							if($incident['incidents']['status'] == 3) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Duplicate';
						echo '</option>';
						echo '<option value=4 ';
							if($incident['incidents']['status'] == 4) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Inaccurate';
						echo '</option>';
						echo '<option value=5 ';
							if($incident['incidents']['status'] == 5) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Unresolvable';
						echo '</option>';
				echo '</select>
				<br />
				<font size="-2"> If you change pending to accepted you must assign an admin/tech (below); and 
								if you change accepted to pending the incident will be unassigned.</font>';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Admin/Tech: &nbsp;&nbsp;
			</td>
			<td valign="top">';

					if(empty($incident['incidents']['admin_id']) || ($incident['incidents']['admin_id'] == 0)) {
						echo '<select name="data[Incident][admin_id]" id="IncidentAdminId">';
						echo '<option value="0" SELECTED="selected">UNASSIGNED</option>';
					} else {
						echo '<select name="data[Incident][admin_id]" id="IncidentAdminId">';
					} //end if

						foreach($admins as $admin) :

						if($admin['admins']['id'] == $incident['incidents']['admin_id']) {
							//Check if the admin is enabled
							if($admin['admins']['enabled'] == 0) {
								echo '<option value="0"  SELECTED="selected">';
								echo $admin['admins']['first_name'];

								if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
								  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
								} //end if

								echo ' '. $admin['admins']['last_name'];

								echo ' ('. $admin['admins']['username'] .', ';
								if($admin['admins']['type'] == 1) {
									echo 'administrator)';
								} else if($admin['admins']['type'] == 2) {
									echo 'technician)';
								} //end if

								echo ' (disabled)';
							} else {

								echo '<option value="'. $admin['admins']['id'] .'" SELECTED="selected">';

									echo $admin['admins']['first_name'];

									if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
									  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
									} //end if

									echo ' '. $admin['admins']['last_name'];

									echo ' ('. $admin['admins']['username'] .', ';
									if($admin['admins']['type'] == 1) {
										echo 'administrator)';
									} else if($admin['admins']['type'] == 2) {
										echo 'technician)';
									} //end if

								echo '</option>';

							} //end if

						} else {
							//Check if the admin is enabled
							if($admin['admins']['enabled'] == 1) {
								echo '<option value="'. $admin['admins']['id'] .'">';

									echo $admin['admins']['first_name'];

									if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
									  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
									} //end if

									echo ' '. $admin['admins']['last_name'];

									echo ' ('. $admin['admins']['username'] .', ';
									if($admin['admins']['type'] == 1) {
										echo 'administrator)';
									} else if($admin['admins']['type'] == 2) {
										echo 'technician)';
									} //end if

								echo '</option>';
							} //end if

						} //end if

						endforeach;

				echo'</select>';
		echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Description: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Incident.description', array('maxlength' => '250', 'size' => 112, 'label' => '', 'value' => $incident['incidents']['description']));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Problem: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Incident.content', array('label' => '', 'rows' => 20, 'cols' => 85, 'value' => $incident['incidents']['content']));
	echo'</td></tr><tr>
		<td align="center" colspan="2" valign="top" width="100%">';
			echo $form->end('Update Incident') . '<br />';
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
							$content = html_entity_decode($worklog['worklogs']['content']);
							echo $content;
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
		</tr><tr>
		<td align="right" valign="top" width="100">
		&nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->create('Incident', array('action' => 'addWorklog'));

			//We need to have this admin_id here (but hidden) so it is added to the database upon submitting
			echo $form->input('Worklog.admin_id', array('type'=>'hidden', 'value' => $sessionAdmin[0]['admins']['id']));
			echo $form->input('Worklog.incident_id', array('type'=>'hidden', 'value' => $incident['incidents']['id']));
			echo $form->input('Worklog.content', array('label' => '', 'rows' => 5, 'cols' => 85));
	echo'</td></tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Submit New Worklog Entry'); //end the form
	echo'</td></tr>
	</table>';
} //end foreach

		/////////////////////////////////////////////////////////////////////////////////

	}else if($sessionAdmin[0]['admins']['type'] == 1) {

		/////////////ADMINISTRATOR CODE - THIS IS IDENTICAL TO THE TECHNICIAN CODE (ABOVE)
		//////////////////////////////////////////////////////////////////////////////////

foreach ($selectedIncident as $incident):
	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/incidents/", array('escape'=>false, 'class'=>'navTools'))

				;
	echo '</div>';
endforeach;

		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if


foreach ($selectedIncident as $incident):
	//Start HTML Form
	echo $form->create('Incident', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden', 'value' => $incident['incidents']['id']));

	//We need to have this admin_id here (but hidden) so it is added to the database upon submitting
	echo $form->input('admin_id', array('type'=>'hidden', 'value' => $sessionAdmin[0]['admins']['id']));

	//We need to have this user_id here (but hidden) so it is added to the database upon submitting
	echo $form->input('user_id', array('type'=>'hidden', 'value' => $incident['incidents']['user_id']));

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

			echo '<select name="data[Incident][category_id]" id="IncidentCategoryId">';

					foreach($categories as $categoryLevel1) :
					
						if( ($categoryLevel1['categories']['id'] == $incident['incidents']['category_id']) && ($categoryLevel1['categories']['enabled'] == 0) ) {
							echo '<option value="'. $categoryLevel1['categories']['id'] .'" SELECTED="selected">'.
									$categoryLevel1['categories']['name'] .' (disabled)</option>';
						} //end if

					endforeach;

					foreach($categories as $categoryLevel1) :

						if($categoryLevel1['categories']['parent_id'] == 0  && $categoryLevel1['categories']['enabled'] == 1) {

							echo '<option value="'. $categoryLevel1['categories']['id'] .'" ';
								if($incident['incidents']['category_id'] == $categoryLevel1['categories']['id']) {
									echo ' SELECTED="selected">';
								} else {
									echo '>';
								} //end if
							echo $categoryLevel1['categories']['name'];
							echo '</option>';

							foreach($categories as $categoryLevel2) :

								if(($categoryLevel2['categories']['parent_id'] == $categoryLevel1['categories']['id'])  && 
									$categoryLevel2['categories']['enabled'] == 1) {

									echo '<option value="'. $categoryLevel2['categories']['id'] .'" ';
										if($incident['incidents']['category_id'] == $categoryLevel2['categories']['id']) {
											echo ' SELECTED="selected">';
										} else {
											echo '>';
										} //end if
									echo '--'. $categoryLevel2['categories']['name'];
									echo '</option>';

									foreach($categories as $categoryLevel3) :

										if(($categoryLevel3['categories']['parent_id'] == $categoryLevel2['categories']['id'])  && 
											$categoryLevel3['categories']['enabled'] == 1) {

											echo '<option value="'. $categoryLevel3['categories']['id'] .'" ';
												if($incident['incidents']['category_id'] == $categoryLevel3['categories']['id']) {
													echo ' SELECTED="selected">';
												} else {
													echo '>';
												} //end if
											echo '----'. $categoryLevel3['categories']['name'];
											echo '</option>';

										} //end if

									endforeach;

								} //end if

							endforeach;

						} //end if

					endforeach;

				echo '</select> &nbsp;';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Priority: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo '<select name="data[Incident][priority]" id="IncidentPriority">';

						echo '<option value=0 ';
							if($incident['incidents']['priority'] == 0) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'LOW';
						echo '</option>';
						echo '<option value=1 ';
							if($incident['incidents']['priority'] == 1) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'MEDIUM';
						echo '</option>';
						echo '<option value=2 ';
							if($incident['incidents']['priority'] == 2) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'HIGH';
						echo '</option>';
						echo '<option value=3 ';
							if($incident['incidents']['priority'] == 3) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'WORK-STOPPAGE';
						echo '</option>';
				echo '</select>';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Status: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo '<select name="data[Incident][status]" id="IncidentPriority">';

						echo '<option value=0 ';
							if($incident['incidents']['status'] == 0) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Pending';
						echo '</option>';
						echo '<option value=1 ';
							if($incident['incidents']['status'] == 1) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Accepted';
						echo '</option>';
						echo '<option value=2 ';
							if($incident['incidents']['status'] == 2) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Resolved';
						echo '</option>';
						echo '<option value=3 ';
							if($incident['incidents']['status'] == 3) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Duplicate';
						echo '</option>';
						echo '<option value=4 ';
							if($incident['incidents']['status'] == 4) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Inaccurate';
						echo '</option>';
						echo '<option value=5 ';
							if($incident['incidents']['status'] == 5) {
								echo ' SELECTED="selected">';
							} else {
								echo '>';
							} //end if
						echo 'Unresolvable';
						echo '</option>';
				echo '</select>
				<br />
				<font size="-2"> If you change pending to accepted you must assign an admin/tech (below); and 
								if you change accepted to pending the incident will be unassigned.</font>';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Admin/Tech: &nbsp;&nbsp;
			</td>
			<td valign="top">';

					if(empty($incident['incidents']['admin_id']) || ($incident['incidents']['admin_id'] == 0)) {
						echo '<select name="data[Incident][admin_id]" id="IncidentAdminId">';
						echo '<option value="0" SELECTED="selected">UNASSIGNED</option>';
					} else {
						echo '<select name="data[Incident][admin_id]" id="IncidentAdminId">';
					} //end if

						foreach($admins as $admin) :

						if($admin['admins']['id'] == $incident['incidents']['admin_id']) {
							//Check if the admin is enabled
							if($admin['admins']['enabled'] == 0) {
								echo '<option value="0"  SELECTED="selected">';
								echo $admin['admins']['first_name'];

								if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
								  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
								} //end if

								echo ' '. $admin['admins']['last_name'];

								echo ' ('. $admin['admins']['username'] .', ';
								if($admin['admins']['type'] == 1) {
									echo 'administrator)';
								} else if($admin['admins']['type'] == 2) {
									echo 'technician)';
								} //end if

								echo ' (disabled)';
							} else {

								echo '<option value="'. $admin['admins']['id'] .'" SELECTED="selected">';

									echo $admin['admins']['first_name'];

									if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
									  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
									} //end if

									echo ' '. $admin['admins']['last_name'];

									echo ' ('. $admin['admins']['username'] .', ';
									if($admin['admins']['type'] == 1) {
										echo 'administrator)';
									} else if($admin['admins']['type'] == 2) {
										echo 'technician)';
									} //end if

								echo '</option>';

							} //end if

						} else {
							//Check if the admin is enabled
							if($admin['admins']['enabled'] == 1) {
								echo '<option value="'. $admin['admins']['id'] .'">';

									echo $admin['admins']['first_name'];

									if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
									  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
									} //end if

									echo ' '. $admin['admins']['last_name'];

									echo ' ('. $admin['admins']['username'] .', ';
									if($admin['admins']['type'] == 1) {
										echo 'administrator)';
									} else if($admin['admins']['type'] == 2) {
										echo 'technician)';
									} //end if

								echo '</option>';
							} //end if

						} //end if

						endforeach;

				echo'</select>';
		echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Description: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Incident.description', array('maxlength' => '250', 'size' => 112, 'label' => '', 'value' => $incident['incidents']['description']));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Problem: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Incident.content', array('label' => '', 'rows' => 20, 'cols' => 85, 'value' => $incident['incidents']['content']));
	echo'</td></tr><tr>
		<td align="center" colspan="2" valign="top" width="100%">';
			echo $form->end('Update Incident') . '<br />';
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Worklog: &nbsp;&nbsp;
		</td>
		<td valign="top">';

			foreach($worklogs as $worklog) :

				echo '<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <td height="1" bgcolor="#000000" colspan="2">
						  </td>
						</tr>
						<tr>
						  <td width="95%" align="left">'.
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
						  <td width="5%" align="right">'.
						  $html->link($html->image("icons/cancel.png", array('title' => 'Delete', 'border' => 0)), "/incidents/deleteWorklog/{$worklog['worklogs']['id']}", array('escape'=>false), 
															 "Are you sure you want to delete this worklog entry?") .'
						  </td>
						</tr>
						<tr>
						  <td width="100%" colspan="2">';
							$content = html_entity_decode($worklog['worklogs']['content']);
							echo $content;
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
		</tr><tr>
		<td align="right" valign="top" width="100">
		&nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->create('Incident', array('action' => 'addWorklog'));

			//We need to have this admin_id here (but hidden) so it is added to the database upon submitting
			echo $form->input('Worklog.admin_id', array('type'=>'hidden', 'value' => $sessionAdmin[0]['admins']['id']));
			echo $form->input('Worklog.incident_id', array('type'=>'hidden', 'value' => $incident['incidents']['id']));
			echo $form->input('Worklog.content', array('label' => '', 'rows' => 5, 'cols' => 85));
	echo'</td></tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Submit New Worklog Entry'); //end the form
	echo'</td></tr>
	</table>';
endforeach;

	} else if(empty($sessionUser) == false) {


foreach ($selectedIncident as $incident):
	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/incidents/", array('escape'=>false, 'class'=>'navTools'))

				;
	echo '</div>';
endforeach;

		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if


	////////////////////          USER CODE           /////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////

	foreach ($selectedIncident as $incident):

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

			echo $form->end();
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
		</tr><tr>
		<td align="right" valign="top" width="100">
		&nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->create('Incident', array('action' => 'addWorklog'));

			//We need to have this user_id here (but hidden) so it is added to the database upon submitting
			echo $form->input('Worklog.user_id', array('type'=>'hidden', 'value' => $sessionUser[0]['users']['id']));
			echo $form->input('Worklog.incident_id', array('type'=>'hidden', 'value' => $incident['incidents']['id']));
			echo $form->input('Worklog.content', array('label' => '', 'rows' => 5, 'cols' => 85));
	echo'</td></tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Submit New Worklog Entry'); //end the form
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