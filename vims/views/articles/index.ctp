<?php
/**********
View Name: index
Description: This view is the first page that the user administrator will see for the articles component, it shows a listing of the articles from the database
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/24 02:53 PM
Last Modified By: William DiStefano
**********/

//BREADCRUMB//
$breadCrumbText = 'Articles';
$breadCrumbUrl = '/articles/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Articles </h2>';
////////////////

	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 2) {

		////////////////////////////////////////////////////////////////////////////////////////
		//THE FOLLOWING CODE FOR THE TECHNICIAN GROUP IS IDENTICAL TO REGULAR USER'S CODE BELOW
			//Display any error messages if they exist
			if (!empty($errorMessage)) {
				echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
			} //end if


			//The paginator sets the div to update (mainContent) and the indicator div (ajaxLoaderGeneral) to show while it is updating
			//$paginator->options(array('url'=>array('controller'=>'Articles', 'action'=>'index')));
			$paginator->options(array('update' => 'mainContent', 'indicator' => 'ajaxLoaderGeneral', 'url'=>array('controller'=>'Articles', 'action'=>'index')));


			foreach ($articles as $article):
				echo'<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <td width="50px"> </td>
						  <td align="left" width="100%">
								<b><font size="+1">'.
								$html->link( $article['Article']['title'], '/articles/view/'.$article['Article']['id'] , array('class' => 'articleHlink')) .'
								</font></b>
						  </td>
						  <td width="50px"> </td>
						</tr>
						<tr>
						  <td width="50px"> </td>
						  <td align="left" width="100%">
								<font size="-2">
									Submitted: '. $article['Article']['created'] . '&nbsp;|&nbsp;
									Last Modified: '. $article['Article']['modified'] . '&nbsp;|&nbsp;
									Author: ';
									foreach($admins as $admin) :
										if($admin['admins']['id'] == $article['Article']['admin_id']) {

											echo $admin['admins']['first_name'] . ' ';

											if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
											  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
											} //end if

											echo ' ' . $admin['admins']['last_name'] . ' ('. $admin['admins']['username'] .')';

										} //end if
									endforeach;
							echo'</font>
						  </td>
						  <td width="50px"> </td>
						</tr>
					  </table>
					  <br />';
				endforeach;

				echo $paginator->numbers(); //show the other pages (if multiple pages exist)
		////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////

	}else if($sessionAdmin[0]['admins']['type'] == 1) {

	echo'<div class="divContentCenter">';

				//Display any error messages if they exist
				if (!empty($errorMessage)) {
					echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
				} //end if

				//The paginator sets the div to update (mainContent) and the indicator div (ajaxLoaderGeneral) to show while it is updating
				//$paginator->options(array('url'=>array('controller'=>'Articles', 'action'=>'index')));
				$paginator->options(array('update' => 'mainContent', 'indicator' => 'ajaxLoaderGeneral', 'url'=>array('controller'=>'Articles', 'action'=>'index')));

				echo'<table width="100% align="center">
					<tr>
						<td colspan="6" align="left">
						  <span id="navTools">';
				echo		$html->link($html->image('buttons/button_add-new-article.png',
								array('title' => 'Add a New Article', 'border' => 0)), "add", array('escape'=>false, 'class' => 'navTools'));
				echo	  '</span>';
				echo'	</td>
						<td width="20">';
							echo '<span id="ajaxLoaderGeneral" style="display: none;">';
								echo $html->image('ajax-loader.gif');
							echo '</span>';
				echo'	</td>
					</tr>';
				echo'<tr>
					  <td colspan="7">';
						$html->image('dot_clear.gif', array('height' => '5'));
				echo'</td>
					</tr>';


				echo '
					<tr>
					  <th>
						'. $paginator->sort('ID', 'id') .'
					  </th>
					  <th>
						'. $paginator->sort('Created', 'created') .'
					  </th>
					  <th>
						'. $paginator->sort('Modified', 'modified') .'
					  </th>
					  <th>
						'. $paginator->sort('Author', 'admin_id') .'
					  </th>
					  <th>
						'. $paginator->sort('Title', 'title') .'
					  </th>
					  <td width="40px">
						&nbsp;
					  </td>
					</tr>';


				foreach ($articles as $article):
					echo '
						<tr>
						  <td>
							'.
							////ID////
							  $article['Article']['id']
							//////////
							.'
						  </td>
						  <td>
							'.
							////CREATED////
							  $article['Article']['created']
							///////////////
							.'
						  </td>
						  <td>
							'.
							////MODIFIED////
							  $article['Article']['modified']
							////////////////
							.'
						  </td>
						  <td>
							';
							////FIRST AND LAST NAME of AUTHOR (ADMIN)////
							foreach($admins as $admin) :
								if($admin['admins']['id'] == $article['Article']['admin_id']) {
									echo $admin['admins']['last_name'] . ', ' . $admin['admins']['first_name'];
									if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
									  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
									} //end if
								} //end if
							endforeach;
							/////////////////////////////////////////////
							echo'
						  </td>
						  <td>
							';
							////TITLE////
									//If the article's title is longer than 30 shorten it
									if (strlen($article['Article']['title']) >= 30) {  
									  echo ' ' . substr($article['Article']['title'], 0, 30) . '...';
									} else {
									  echo $article['Article']['title'];
									} //end if
							/////////////
						echo'
						  </td>
						  <td>'; 
								////FUNCTIONS////
								echo '<table cellpadding="0" cellspacing="0">
										<tr>
										  <td>';
											echo $html->link($html->image("icons/page_go.png", array('title' => 'View', 'border' => 0)),"/articles/view/{$article['Article']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/page_edit.png", array('title' => 'Edit', 'border' => 0)), "/articles/edit/{$article['Article']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/page_delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/articles/delete/{$article['Article']['id']}", array('escape'=>false), 
															 "Are you sure you want to permanently delete article with id: ". $article['Article']['id'] );
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

	} else if(!empty($sessionUser) == true) {


			//Display any error messages if they exist
			if (!empty($errorMessage)) {
				echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
			} //end if


			//The paginator sets the div to update (mainContent) and the indicator div (ajaxLoaderGeneral) to show while it is updating
			//$paginator->options(array('url'=>array('controller'=>'Articles', 'action'=>'index')));
			$paginator->options(array('update' => 'mainContent', 'indicator' => 'ajaxLoaderGeneral', 'url'=>array('controller'=>'Articles', 'action'=>'index')));


			foreach ($articles as $article):
				echo'<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <td width="50px"> </td>
						  <td align="left" width="100%">
								<b><font size="+1">'.
								$html->link( $article['Article']['title'], '/articles/view/'.$article['Article']['id'] , array('class' => 'articleHlink')) .'
								</font></b>
						  </td>
						  <td width="50px"> </td>
						</tr>
						<tr>
						  <td width="50px"> </td>
						  <td align="left" width="100%">
								<font size="-2">
									Submitted: '. $article['Article']['created'] . '&nbsp;|&nbsp;
									Last Modified: '. $article['Article']['modified'] . '&nbsp;|&nbsp;
									Author: ';
									foreach($admins as $admin) :
										if($admin['admins']['id'] == $article['Article']['admin_id']) {

											echo $admin['admins']['first_name'] . ' ';

											if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
											  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
											} //end if

											echo ' ' . $admin['admins']['last_name'] . ' ('. $admin['admins']['username'] .')';

										} //end if
									endforeach;
							echo'</font>
						  </td>
						  <td width="50px"> </td>
						</tr>
					  </table>
					  <br />';
				endforeach;

				echo $paginator->numbers(); //show the other pages (if multiple pages exist)


	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	} //end if

?>