<?php
/**********
View Name: login
Description: This view is login page for administrators and technicians
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/24 02:46 PM
Last Modified By: William DiStefano
**********/

	echo '<div class="topRightCornerFunctions">'.
			$html->link($html->image('buttons/button_employee-login.png',
								array('title' => 'Employee Login', 'border' => 0)), "/users/login", array('escape'=>false, 'class' => 'navTools')) .'
		  </div>'
	;


	echo '<div class="divContentCenter">' .

				$form->create('Admin', array('action' => 'login'));

			echo'<table width="100%">
					<tr>
					  <td align="center" width="100%" colspan="2">
						<h2>Administrator Login</h2>
					  </td>
					<tr>
					  <td align="right" width="45%">
					  Username: 
					  </td>
					  <td align="left" width="55%">'.
						$form->input('username', array('maxlength' => '30', 'label' => '')) .'
					  </td>
					</tr>
					<tr>
					  <td align="right" width="45%">
					  Password: 
					  </td>
					  <td align="left" width="55%">'.
						$form->input('password', array('maxlength' => '15', 'label' => '')) .'
					  </td>
					</tr>
					<tr>
					  <td align="center" width="100%" colspan="2">'.
						$form->end('Login') . '
					  </td>
					</tr>
				</table>

		  </div>';

?>