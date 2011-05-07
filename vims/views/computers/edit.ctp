<?php
/**********
View Name: edit
Description: This view will display detailed information about a particular computer and allow modifications
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 02:55 PM
Last Modified By: William DiStefano
**********/

foreach ($selectedComputer as $computer):
//BREADCRUMB//
$breadCrumbText = 'Computers';
$breadCrumbUrl = '/computers/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Modify - ID: ' . $computer['computers']['id'];
$breadCrumbUrl = '/computers/edit/' . $computer['computers']['id'];
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////
endforeach;

//HEADING TEXT//
echo '<h2> Modify Computer </h2>';
////////////////


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 1) {

foreach ($selectedComputer as $computer):
	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/computers/", array('escape'=>false, 'class'=>'navTools')) . '&nbsp;';

								//Setup the delete button
								$assigned = false;

								  foreach($admins as $admin) :
									//Check if an administrator/technician is using the computer
									if($admin['admins']['computer_id'] == $computer['computers']['id']) {

										//Check if they are a technician or administrator
										if($admin['admins']['type'] == 1) {
											echo $html->link($html->image("buttons/button_computer-delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/computers/delete/{$computer['computers']['id']}", array('escape'=>false),  
															 "WARNING: This computer (". $computer['computers']['name'] .") is assigned to someone. If you continue they will no longer have a computer assigned");
											$assigned = true;
										} else if($admin['admins']['type'] == 2) {
											echo $html->link($html->image("buttons/button_computer-delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/computers/delete/{$computer['computers']['id']}", array('escape'=>false), 
															 "WARNING: This computer (". $computer['computers']['name'] .") is assigned to someone. If you continue they will no longer have a computer assigned");
											$assigned = true;
										} //end if
									} //end if
								  endforeach;

								  foreach($users as $user) :
									//Check if an employee/user is using the computer
									if($user['users']['computer_id'] == $computer['computers']['id']) {
										echo $html->link($html->image("buttons/button_computer-delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/computers/delete/{$computer['computers']['id']}", array('escape'=>false), 
															 "WARNING: This computer (". $computer['computers']['name'] .") is assigned to someone. If you continue they will no longer have a computer assigned");
										$assigned = true;
									} //end if
								  endforeach;

								  if($assigned == false) {
								  echo $html->link($html->image("buttons/button_computer-delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/computers/delete/{$computer['computers']['id']}", array('escape'=>false), 
															 "Are you sure you want to permanently delete computer: ". $computer['computers']['name']);
								  } //end if

				;
	echo '</div>';
endforeach;

	if (!empty($errorMessage)) {
		echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
	} //end if


	//Start HTML Form
	echo $form->create('Computer', array('action' => 'edit'));

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
			foreach ($selectedComputer as $computer):
				echo $computer['computers']['created'];
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			foreach ($selectedComputer as $computer):
				echo $computer['computers']['modified'];
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Assigned To: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			foreach($admins as $admin) :
				if($admin['admins']['computer_id'] == $computer['computers']['id']) {
					//Check if technician or administrator
					if($admin['admins']['type'] == 1) {
						echo $admin['admins']['username'] . ' (administrator) ';
						echo $html->link($html->image("icons/user_edit.png", array('title' => 'Modify Administrator', 'border' => 0)), 
												 "/admins/edit/{$admin['admins']['id']}", array('escape'=>false, 'class'=>'navTools'));
					} else if($admin['admins']['type'] == 2){
						echo $admin['admins']['username'] . ' (technician) ';
						echo $html->link($html->image("icons/user_edit.png", array('title' => 'Modify Technician', 'border' => 0)), 
												 "/admins/edit/{$admin['admins']['id']}", array('escape'=>false, 'class'=>'navTools'));
					} //end if
				} //end if
			endforeach;

			foreach($users as $user) :
				if($user['users']['computer_id'] == $computer['computers']['id']) {
					echo $user['users']['username'] . ' (employee) ';
					echo $html->link($html->image("icons/user_edit.png", array('title' => 'Modify Employee', 'border' => 0)), 
												  "/users/edit/{$user['users']['id']}", array('escape'=>false, 'class'=>'navTools'));
				} //end if
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Computer.name', array('maxlength' => '75', 'size' => 50, 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Type: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Computer.type', array('maxlength' => '75', 'size' => 50, 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Serial Number: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Computer.serial_number', array('maxlength' => '100', 'size' => 50, 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		O/S: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Computer.operating_system', array('maxlength' => '50', 'size' => 50, 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Memory: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Computer.memory', array('maxlength' => '8', 'size' => 50, 'label' => ''));
			echo '<font size="-2"> Number of gigabytes (GB), enter a number only. Examples: 1, 1.5, 3, 4, or 4.5. </font>';
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		HDD Space: &nbsp;&nbsp;
		</td>
		<td valign="top">';
		echo $form->input('Computer.hdd_space', array('maxlength' => '8', 'size' => 50, 'label' => ''));
		echo '<font size="-2"> Number of gigabytes (GB), enter a number only. Examples: 40, 120, 300, or 500.</font>';
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Processor: &nbsp;&nbsp;
		</td>
		<td valign="top">';
		echo $form->input('Computer.processor', array('maxlength' => '50', 'size' => 50, 'label' => ''));
	echo'</td></tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Update'); //end the form
	echo'</td></tr>
	</table>';


	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	}

?>