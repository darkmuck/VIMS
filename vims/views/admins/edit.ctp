<?php
/**********
View Name: edit
Description: This view will display detailed information about a particular administrator and allow modifications
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 02:52 PM
Last Modified By: William DiStefano
**********/

foreach ($selectedAdmin as $admin):
//BREADCRUMB//
$breadCrumbText = 'Administrators';
$breadCrumbUrl = '/admins/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Modify - ID: ' . $admin['admins']['id'];
$breadCrumbUrl = '/admins/edit/' . $admin['admins']['id'];
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////
endforeach;

//HEADING TEXT//
echo '<h2> Modify Administrator </h2>';
////////////////


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 1) {


foreach ($selectedAdmin as $admin):
	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/admins/", array('escape'=>false, 'class'=>'navTools')) . '&nbsp;';

					if($admin['admins']['enabled'] == 1) {
						echo $html->link($html->image("buttons/button_user-disable.png", array('title' => 'Disable', 'border' => 0)), 
															 "/admins/disable/{$admin['admins']['id']}", array('escape'=>false), 
															 "Are you sure you want to disable the admin: ". 
															 $admin['admins']['username'] );
					} else {
						echo $html->link($html->image("buttons/button_user-enable.png", array('title' => 'Enable', 'border' => 0)), 
															 "/admins/enable/{$admin['admins']['id']}", array('escape'=>false), 
															 "Are you sure you want to enable the admin: ". 
															 $admin['admins']['username'] );
					} //end if
				;
	echo '</div>';
endforeach;

	if (!empty($errorMessage)) {
		echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
	} //end if


	//Start HTML Form
	echo $form->create('Admin', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));
	//We need to have the username present so the SQL executes properly
	echo $form->input('username', array('type'=>'hidden', 'value' => $this->data['Admin']['username']));


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
			foreach($selectedAdmin as $admin) :
				echo $admin['admins']['created'];
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			foreach($selectedAdmin as $admin) :
				echo $admin['admins']['modified'];
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		First Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Admin.first_name', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Middle Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Admin.middle_name', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Admin.last_name', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Username: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $this->data['Admin']['username'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Password: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Admin.password', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Email: &nbsp;&nbsp;
		</td>
		<td valign="top">';
		echo $form->input('Admin.email', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Computer: &nbsp;&nbsp;
		</td>
		<td valign="top">';
				echo '<select name="data[Admin][computer_id]" id="AdminComputerId">';
					echo '<option value="0" >NONE</option>';

					/* Match up the availableComputers array with the computers array so that
						we can match up the computer names with the ids of available computers */
					foreach($availableComputers as $availableComputerID) :
						foreach ($computers as $computer) :
								if ($availableComputerID == $computer['computers']['id']) {
									echo '<option value="'. $computer['computers']['id'] .'" >'. $computer['computers']['name'] .'</option>';
								} //end if
						endforeach;
					endforeach;

					//Also need to display the already selected computer if the user has one assigned so it appears on the list
					//Match up the current admin's computer (if one is assigned) and display it
					foreach ($computers as $computer) :
							if ($this->data['Admin']['computer_id'] == $computer['computers']['id']) {
								echo '<option value="'. $computer['computers']['id'] .'" SELECTED>'. $computer['computers']['name'] .'</option>';
							} //end if
					endforeach;

				echo '</select> &nbsp;';
				echo'<font size="-2">'.
				$html->link('computers list', '/computers/') .'<br />
				Only unassigned/available computers appear in this list. </font>';
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Type: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Admin.type', array(
				'Technician' => 1,
				'Administrator' => 2,
				'options' => array('Technician', 'Administrator'), 
				'label' => ''));
		echo'</td>
		</tr><tr>
		<td align="right" valign="top" width="100">
		Enabled: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Admin.enabled', array(
				'No' => 0,
				'Yes' => 1,
				'options' => array('No', 'Yes'), 
				'label' => ''));
		echo'</td>
		</tr><tr>
		</tr></table>
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