<?php
/**********
View Name: login
Description: This view is the login page for users (employees)
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/24 03:08 PM
Last Modified By: William DiStefano
**********/

	echo '<div class="topRightCornerFunctions">'.
			$html->link($html->image('buttons/button_admin-login.png',
								array('title' => 'Administrator Login', 'border' => 0)), "/admins/login", array('escape'=>false, 'class' => 'navTools')) .'
		  </div>'
	;

	echo '<div class="divContentCenter">' .

				$form->create('User', array('action' => 'login'));

			echo'<table width="100%">
					<tr>
					  <td align="center" width="100%" colspan="2">
						<h2>Employee Login</h2>
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