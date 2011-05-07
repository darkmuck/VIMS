<?php
/**********
View Name: view
Description: This view will display detailed information about a particular article.
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/24 02:53 PM
Last Modified By: William DiStefano
**********/

foreach ($selectedArticle as $article):
//BREADCRUMB//
$breadCrumbText = 'Articles';
$breadCrumbUrl = '/articles/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'View - ID: ' . $article['articles']['id'];
$breadCrumbUrl = '/articles/view/' . $article['articles']['id'];
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////
endforeach;


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 2) {

		////////////////////////////////////////////////////////////////////////////////////////
		//THE FOLLOWING CODE FOR THE TECHNICIAN GROUP IS IDENTICAL TO REGULAR USER'S CODE BELOW

		//Display any error messages if they exist
		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if

	foreach ($selectedArticle as $article):


	//Start HTML Form
	echo $form->create('Article', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));


	echo '
	<font size="-1">
	<table border="0" bordercolor="#333333" cellspacing="5" cellpadding="0">
	<tr><td height="1" width="30"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
	<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
	  <tr><td height="1" width="100%" colspan="2"></td></tr>
	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		&nbsp;
		</strong>
		</td>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		</td></tr><tr>
		<td align="left" valign="top" colspan="2">
			&nbsp;&nbsp;&nbsp;
			<font size="+2">'. $article['articles']['title'] .' </font>
		</td></tr><tr>
		<td align="right" valign="top" width="100">
		Submitted: &nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
			echo $article['articles']['created'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Author: &nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
				foreach($admins as $admin) :
					if($admin['admins']['id'] == $article['articles']['admin_id']) {

						echo $admin['admins']['last_name'] . ', ' . $admin['admins']['first_name'];

						if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
						  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
						} //end if

						echo ' ('. $admin['admins']['username'] .')';

					} //end if
				endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		&nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
			$content = html_entity_decode($article['articles']['content']);
			echo $content;
		echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end(); //end the form
	echo'</td></tr>
	</table>';

	endforeach;
		////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////

	}else if($sessionAdmin[0]['admins']['type'] == 1) {


		//Display any error messages if they exist
		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if

	foreach ($selectedArticle as $article):


	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_article-modify.png", array('title' => 'Modify', 'border' => 0)), "/articles/edit/{$article['articles']['id']}", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';


	//Start HTML Form
	echo $form->create('Article', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));


	echo '
	<font size="-1">
	<table border="0" bordercolor="#333333" cellspacing="5" cellpadding="0">
	<tr><td height="1" width="30"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
	<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
	  <tr><td height="1" width="100%" colspan="2"></td></tr>
	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		&nbsp;
		</strong>
		</td>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		</td></tr><tr>
		<td align="left" valign="top" colspan="2">
			&nbsp;&nbsp;&nbsp;
			<font size="+2">'. $article['articles']['title'] .' </font>
		</td></tr><tr>
		<td align="right" valign="top" width="100">
		Submitted: &nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
			echo $article['articles']['created'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
			echo $article['articles']['modified'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Author: &nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
				foreach($admins as $admin) :
					if($admin['admins']['id'] == $article['articles']['admin_id']) {

						echo $admin['admins']['last_name'] . ', ' . $admin['admins']['first_name'];

						if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
						  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
						} //end if

						echo ' ('. $admin['admins']['username'] .')';

					} //end if
				endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		&nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
			$content = html_entity_decode($article['articles']['content']);
			echo $content;
		echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end(); //end the form
	echo'</td></tr>
	</table>';

	endforeach;


	} else if(empty($sessionUser) == false) {



		//Display any error messages if they exist
		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if

	foreach ($selectedArticle as $article):


	//Start HTML Form
	echo $form->create('Article', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));


	echo '
	<font size="-1">
	<table border="0" bordercolor="#333333" cellspacing="5" cellpadding="0">
	<tr><td height="1" width="30"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
	<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
	  <tr><td height="1" width="100%" colspan="2"></td></tr>
	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		&nbsp;
		</strong>
		</td>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		</td></tr><tr>
		<td align="left" valign="top" colspan="2">
			&nbsp;&nbsp;&nbsp;
			<font size="+2">'. $article['articles']['title'] .' </font>
		</td></tr><tr>
		<td align="right" valign="top" width="100">
		Submitted: &nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
			echo $article['articles']['created'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Author: &nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
				foreach($admins as $admin) :
					if($admin['admins']['id'] == $article['articles']['admin_id']) {

						echo $admin['admins']['last_name'] . ', ' . $admin['admins']['first_name'];

						if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
						  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
						} //end if

						echo ' ('. $admin['admins']['username'] .')';

					} //end if
				endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		&nbsp;&nbsp;
		</td>
		<td valign="top" align="left" width="700">';
			$content = html_entity_decode($article['articles']['content']);
			echo $content;
		echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end(); //end the form
	echo'</td></tr>
	</table>';

	endforeach;

	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	} //end if

?>