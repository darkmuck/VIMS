<?php
/**********
View Name: add
Description: This view allows an administrator to add a new user to the database
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 03:08 PM
Last Modified By: William DiStefano
**********/

//BREADCRUMB//
$breadCrumbText = 'Users';
$breadCrumbUrl = '/users/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Add';
$breadCrumbUrl = '/users/add/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Add New User </h2>';
////////////////


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 1) {



	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/users/", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';


		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if



	//Start HTML Form
	echo $form->create('User', array('action' => 'add'));


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
		First Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('User.first_name', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Middle Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('User.middle_name', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('User.last_name', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Username: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('User.username', array('maxlength' => '30', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Password: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('User.password', array('maxlength' => '15', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Email: &nbsp;&nbsp;
		</td>
		<td valign="top">';
		echo $form->input('User.email', array('maxlength' => '75', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Network Port: &nbsp;&nbsp;
		</td>
		<td valign="top">';
		echo $form->input('User.network_port', array('maxlength' => '30', 'label' => ''));
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Computer: &nbsp;&nbsp;
		</td>
		<td valign="top">';
				echo '<select name="data[User][computer_id]" id="UserComputerId">';
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
				echo '</select> &nbsp;';
				echo'<font size="-2">'.
				$html->link('computers list', '/computers/') .'<br />
				Only unassigned computers appear in this list. </font>';
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Location: &nbsp;&nbsp;
		</td>
		<td valign="top">';
				echo '<select name="data[User][location_id]" id="UserLocationId">';
						foreach ($locations as $location) :
							echo '<option value="'. $location['locations']['id'] .'" >'. $location['locations']['name'] .'</option>';
						endforeach;
				echo '</select>';
		echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Create'); //end the form
	echo'</td></tr>
	</table>';


	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	} //end if

?>