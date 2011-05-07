<?php
/**********
View Name: add
Description: This view allows an administrator/technician/user to add a new incident to the database
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/24 02:59 PM
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


//BREADCRUMB//
$breadCrumbText = 'Incidents';
$breadCrumbUrl = '/incidents/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Add';
$breadCrumbUrl = '/incidents/add/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Add New Incident </h2>';
////////////////


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 2) {


		//////////TECHNICIAN CODE - THIS IS IDENTICAL TO THE ADMINISTRATOR CODE//////////
		/////////////////////////////////////////////////////////////////////////////////

	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/incidents/", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';

		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if



	//Start HTML Form
	echo $form->create('Incident', array('action' => 'add'));

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
		Employee: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo '<select name="data[Incident][user_id]" id="IncidentUserId">';

					foreach($users as $user) :
						if($user['users']['enabled'] == 1) {
							echo '<option value="'. $user['users']['id'] .'">';

								echo $user['users']['last_name'] . ', ' . $user['users']['first_name'];
								if (!empty($user['users']['middle_name'])) {  //show the middle initial
								  echo ' ' . substr($user['users']['middle_name'], 0, 1) . '.';
								} //end if
								echo ' ('. $user['users']['username'] .')';

							echo '</option>';
						} //end if
					endforeach;

			echo '</select>';
	echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Category: &nbsp;&nbsp;
			</td>
			<td valign="top">';

			echo '<select name="data[Incident][category_id]" id="IncidentCategoryId">';

					foreach($categories as $categoryLevel1) :

						if($categoryLevel1['categories']['parent_id'] == 0  && $categoryLevel1['categories']['enabled'] == 1) {

							echo '<option value="'. $categoryLevel1['categories']['id'] .'">'. 
								$categoryLevel1['categories']['name'] .' </option>';

							foreach($categories as $categoryLevel2) :

								if(($categoryLevel2['categories']['parent_id'] == $categoryLevel1['categories']['id'])  && 
									$categoryLevel2['categories']['enabled'] == 1) {

									echo '<option value="'. $categoryLevel2['categories']['id'] .'">--'. 
										$categoryLevel2['categories']['name'] .'</option>';

									foreach($categories as $categoryLevel3) :

										if(($categoryLevel3['categories']['parent_id'] == $categoryLevel2['categories']['id'])  && 
											$categoryLevel3['categories']['enabled'] == 1) {

											echo '<option value="'. $categoryLevel3['categories']['id'] .'">----'. 
												$categoryLevel3['categories']['name'] .'</option>';

										} //end if

									endforeach;

								} //end if

							endforeach;

						} //end if

					endforeach;

				echo '</select>';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Priority: &nbsp;&nbsp;
			</td>
			<td valign="top">';

			echo $form->input('Incident.priority', array(
				'LOW' => 0,
				'MEDIUM' => 1,
				'HIGH' => 2,
				'WORK-STOPPAGE' => 3,
				'options' => array('LOW', 'MEDIUM', 'HIGH', 'WORK-STOPPAGE'), 
				'label' => '' , 
				'default' => 'LOW'));

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Status: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo 'Pending';
				//The initial value is always Pending (this is 0 in the database)
				echo $form->input('Incident.status', array('type'=>'hidden', 'value' => 0));
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Admin/Tech: &nbsp;&nbsp;
			</td>
			<td valign="top">';

					echo '<select name="data[Incident][admin_id]" id="IncidentAdminId">';

					echo '<option value="0">UNASSIGNED</option>';

						foreach($admins as $admin) :

						if($admin['admins']['id'] == $sessionAdmin[0]['admins']['id']) {
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
			echo $form->input('Incident.description', array('maxlength' => '250', 'size' => 112, 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Problem: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Incident.content', array('label' => '', 'rows' => 20, 'cols' => 85));
	echo'</td></tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Create'); //end the form
	echo'</td></tr>
	</table>';

		/////////////////////////////////////////////////////////////////////////////////

	}else if($sessionAdmin[0]['admins']['type'] == 1) {


		//////////ADMINISTRATOR CODE - THIS IS IDENTICAL TO THE ADMINISTRATOR CODE//////////
		////////////////////////////////////////////////////////////////////////////////////


	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/incidents/", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';


		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if



	//Start HTML Form
	echo $form->create('Incident', array('action' => 'add'));

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
		Employee: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo '<select name="data[Incident][user_id]" id="IncidentUserId">';

					foreach($users as $user) :
						if($user['users']['enabled'] == 1) {
							echo '<option value="'. $user['users']['id'] .'">';

								echo $user['users']['last_name'] . ', ' . $user['users']['first_name'];
								if (!empty($user['users']['middle_name'])) {  //show the middle initial
								  echo ' ' . substr($user['users']['middle_name'], 0, 1) . '.';
								} //end if
								echo ' ('. $user['users']['username'] .')';

							echo '</option>';
						} //end if
					endforeach;

			echo '</select>';
	echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Category: &nbsp;&nbsp;
			</td>
			<td valign="top">';

			echo '<select name="data[Incident][category_id]" id="IncidentCategoryId">';

					foreach($categories as $categoryLevel1) :

						if($categoryLevel1['categories']['parent_id'] == 0  && $categoryLevel1['categories']['enabled'] == 1) {

							echo '<option value="'. $categoryLevel1['categories']['id'] .'">'. 
								$categoryLevel1['categories']['name'] .' </option>';

							foreach($categories as $categoryLevel2) :

								if(($categoryLevel2['categories']['parent_id'] == $categoryLevel1['categories']['id'])  && 
									$categoryLevel2['categories']['enabled'] == 1) {

									echo '<option value="'. $categoryLevel2['categories']['id'] .'">--'. 
										$categoryLevel2['categories']['name'] .'</option>';

									foreach($categories as $categoryLevel3) :

										if(($categoryLevel3['categories']['parent_id'] == $categoryLevel2['categories']['id'])  && 
											$categoryLevel3['categories']['enabled'] == 1) {

											echo '<option value="'. $categoryLevel3['categories']['id'] .'">----'. 
												$categoryLevel3['categories']['name'] .'</option>';

										} //end if

									endforeach;

								} //end if

							endforeach;

						} //end if

					endforeach;

				echo '</select>';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Priority: &nbsp;&nbsp;
			</td>
			<td valign="top">';

			echo $form->input('Incident.priority', array(
				'LOW' => 0,
				'MEDIUM' => 1,
				'HIGH' => 2,
				'WORK-STOPPAGE' => 3,
				'options' => array('LOW', 'MEDIUM', 'HIGH', 'WORK-STOPPAGE'), 
				'label' => '' , 
				'default' => 'LOW'));

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Status: &nbsp;&nbsp;
			</td>
			<td valign="top">';
				echo 'Pending';
				//The initial value is always Pending (this is 0 in the database)
				echo $form->input('Incident.status', array('type'=>'hidden', 'value' => 0));
		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Admin/Tech: &nbsp;&nbsp;
			</td>
			<td valign="top">';

					echo '<select name="data[Incident][admin_id]" id="IncidentAdminId">';

					echo '<option value="0">UNASSIGNED</option>';

						foreach($admins as $admin) :

						if($admin['admins']['id'] == $sessionAdmin[0]['admins']['id']) {
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
			echo $form->input('Incident.description', array('maxlength' => '250', 'size' => 112, 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Problem: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Incident.content', array('label' => '', 'rows' => 20, 'cols' => 85));
	echo'</td></tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Create'); //end the form
	echo'</td></tr>
	</table>';


	////////////////////////////////////////////////////////////////////////////////////


} else if(empty($sessionUser) == false) {


	////////////////////          USER CODE           /////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////

	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/incidents/", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';

		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if



	//Start HTML Form
	echo $form->create('Incident', array('action' => 'add'));

	//Include the admin_id as 0 because it will be unassigned
	echo $form->input('admin_id', array('type'=>'hidden', 'value' => 0));
	//Include the status as pending (0) becuase it is not yet accepted
	echo $form->input('status', array('type'=>'hidden', 'value' => 0));
	//Include the user_id from the sessionUser to get the current user logged in
	echo $form->input('user_id', array('type'=>'hidden', 'value' => $sessionUser[0]['users']['id']));

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
			Category: &nbsp;&nbsp;
			</td>
			<td valign="top">';

			echo '<select name="data[Incident][category_id]" id="IncidentCategoryId">';

					foreach($categories as $categoryLevel1) :

						if($categoryLevel1['categories']['parent_id'] == 0  && $categoryLevel1['categories']['enabled'] == 1) {

							echo '<option value="'. $categoryLevel1['categories']['id'] .'">'. 
								$categoryLevel1['categories']['name'] .' </option>';

							foreach($categories as $categoryLevel2) :

								if(($categoryLevel2['categories']['parent_id'] == $categoryLevel1['categories']['id'])  && 
									$categoryLevel2['categories']['enabled'] == 1) {

									echo '<option value="'. $categoryLevel2['categories']['id'] .'">--'. 
										$categoryLevel2['categories']['name'] .'</option>';

									foreach($categories as $categoryLevel3) :

										if(($categoryLevel3['categories']['parent_id'] == $categoryLevel2['categories']['id'])  && 
											$categoryLevel3['categories']['enabled'] == 1) {

											echo '<option value="'. $categoryLevel3['categories']['id'] .'">----'. 
												$categoryLevel3['categories']['name'] .'</option>';

										} //end if

									endforeach;

								} //end if

							endforeach;

						} //end if

					endforeach;

				echo '</select>';

		echo'</td></tr><tr>
			<td align="right" valign="top" width="100">
			Priority: &nbsp;&nbsp;
			</td>
			<td valign="top">';

			echo $form->input('Incident.priority', array(
				'LOW' => 0,
				'MEDIUM' => 1,
				'HIGH' => 2,
				'WORK-STOPPAGE' => 3,
				'options' => array('LOW', 'MEDIUM', 'HIGH', 'WORK-STOPPAGE'), 
				'label' => '' , 
				'default' => 'LOW'));

		echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Description: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Incident.description', array('maxlength' => '250', 'size' => 112, 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Problem: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Incident.content', array('label' => '', 'rows' => 20, 'cols' => 85));
	echo'</td></tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Create'); //end the form
	echo'</td></tr>
	</table>';

	//////////////////////////////////////////////////////////////////////////////////

} else {

		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;

}

?>