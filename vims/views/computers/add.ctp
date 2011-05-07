<?php
/**********
View Name: add
Description: This view allows an administrator to add a new computer to the database
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 02:56 PM
Last Modified By: William DiStefano
**********/


//BREADCRUMB//
$breadCrumbText = 'Computers';
$breadCrumbUrl = '/computers/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Add';
$breadCrumbUrl = '/computers/add/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Add New Computer </h2>';
////////////////


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 1) {


	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/computers/", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';


	if (!empty($errorMessage)) {
		echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
	} //end if


	//Start HTML Form
	echo $form->create('Computer', array('action' => 'add'));


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
			echo '<font size="-2"> Examples: Desktop, Laptop, or Netbook </font>';
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
	
	}

?>