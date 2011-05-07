<?php
/**********
View Name: index
Description: This view is the first page that the administrator will see after logging in, it shows a listing of all admins from the database
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 02:46 PM
Last Modified By: William DiStefano
**********/

//BREADCRUMB//
$breadCrumbText = 'Administrators';
$breadCrumbUrl = '/admins/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Administrators </h2>';
////////////////

	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 1) {

	echo'<div class="divContentCenter">';

			if (!empty($errorMessage)) {
				echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
			} //end if

				//The paginator sets the div to update (mainContent) and the indicator div (ajaxLoaderGeneral) to show while it is updating
				//$paginator->options(array('url'=>array('controller'=>'Admins', 'action'=>'index')));
				$paginator->options(array('update' => 'mainContent', 'indicator' => 'ajaxLoaderGeneral', 'url'=>array('controller'=>'Admins', 'action'=>'index')));

				echo'<table width="100% align="center">
					<tr>
						<td colspan="8" align="left">
						  <span id="navTools">';
				echo		$html->link($html->image('buttons/button_add-new-admin.png',
								array('title' => 'Add a New Admin', 'border' => 0)), "add", array('escape'=>false, 'class' => 'navTools'));
				echo	  '</span>';
				echo'	</td>
						<td width="20">';
							echo '<span id="ajaxLoaderGeneral" style="display: none;">';
								echo $html->image('ajax-loader.gif');
							echo '</span>';
				echo'	</td>
					</tr>';
				echo'<tr>
					  <td colspan="9">';
						$html->image('dot_clear.gif', array('height' => '5'));
				echo'</td>
					</tr>';


				echo '
					<tr>
					  <th>
						'. $paginator->sort('ID', 'id') .'
					  </th>
					  <th>
						'. $paginator->sort('Modified', 'modified') .'
					  </th>
					  <th>
						'. $paginator->sort('Name', 'last_name') .'
					  </th>
					  <th>
						'. $paginator->sort('Username', 'username') .'
					  </th>
					  <th>
						'. $paginator->sort('Email', 'email') .'
					  </th>
					  <th>
						'. $paginator->sort('Type', 'type') .'
					  </th>
					  <th>
						'. $paginator->sort('Enabled', 'enabled') .'
					  </th>
					  <td width="40px">
						&nbsp;
					  </td>
					</tr>';


				foreach ($admins as $admin):
					echo '
						<tr>
						  <td>
							'.
							////ID////
							  $admin['Admin']['id']
							//////////
							.'
						  </td>
						  <td>
							'.
							////MODIFIED////
							  $admin['Admin']['modified']
							////////////////
							.'
						  </td>
						  <td>
							';
							////FIRST AND LAST NAME////
								echo $admin['Admin']['last_name'] . ', ' . $admin['Admin']['first_name'];
								if (!empty($admin['Admin']['middle_name'])) {  //show the middle initial
								  echo ' ' . substr($admin['Admin']['middle_name'], 0, 1) . '.';
								}
							echo'
						  </td>
						  <td>
							' .
							////USERNAME////
							  $admin['Admin']['username']
							////////////////
						.'
						  </td>
						  <td>
							';
							////Email////
							  echo $admin['Admin']['email'];
							///////////////////
					 echo'
						  </td>
						  <td>
							';
							////GROUP////
							echo '<span class="smalltxt">';
							  if ($admin['Admin']['type'] == 1) {
								echo 'Administrator';
							  }else if($admin['Admin']['type'] == 2) {
								echo 'Technician';
							  }else {
								echo 'Error';
							  } //end if
							echo '</span>';
							///////////////////
							echo '
						  </td>
						  <td>
							';
							////ENABLED////
							echo '<span class="smalltxt">';
							  if ($admin['Admin']['enabled'] == 0) {
								echo 'no';
							  }else if($admin['Admin']['enabled'] == 1) {
								echo 'yes';
							  }else {
								echo 'Error';
							  } //end if
							echo '</span>';
							////////////////
							echo '
						  </td>
						  <td>'; 
								////FUNCTIONS////
								echo '<table cellpadding="0" cellspacing="0">
										<tr>
										  <td>';
											echo $html->link($html->image("icons/user_go.png", array('title' => 'View', 'border' => 0)),"/admins/view/{$admin['Admin']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/user_edit.png", array('title' => 'Edit', 'border' => 0)), "/admins/edit/{$admin['Admin']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											if($admin['Admin']['enabled'] == 0) {
												echo $html->link($html->image("icons/user_green.png", array('title' => 'Enable', 'border' => 0)), 
															 "/admins/enable/{$admin['Admin']['id']}", array('escape'=>false), 
															 "Are you sure you want to enable the admin: ". $admin['Admin']['username'] );
											} else {
												echo $html->link($html->image("icons/user_delete.png", array('title' => 'Disable', 'border' => 0)), 
															 "/admins/disable/{$admin['Admin']['id']}", array('escape'=>false), 
															 "Are you sure you want to disable the admin: ". $admin['Admin']['username'] );
											} //end if
								echo'	  </td>
										</tr>
									  </table>';
								//////////////////
						echo'</td>
						</tr>
					';
				endforeach;

				echo'</table>';

				echo $paginator->numbers(); //show the other pages (if multiple pages exist)

	echo	'</div>'
		;

	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	}
?>