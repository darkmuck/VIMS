<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $title_for_layout?></title>
	<?php echo $html->css('default.css', 'stylesheet', array("media"=>"all" )); ?>
	<?php echo $javascript->link(array('prototype','scriptaculous', 'tiny_mce/tiny_mce.js')); ?>
	<?php echo $scripts_for_layout ?>
</head>
<body>

<div id="container">

	<div id="header">
		
	</div>

	<div id="content">
	  <table height="100%" width="860px">
		<tr>
		  <td width="860px">
			<table class="tblGeneral">
			  <tr>
				<td align="left" width="100%" colspan="2">
				  <?php echo $html->image('vims_logo.PNG'); ?>
				</td>
			  </tr>
				<tr>
				  <td width="100%" colspan="2">
					<table cellspacing="0" cellpadding="0" width="100%">
						<tr>
						  <td class="navLine">
						  </td>
						</tr>
						<tr>
						  <td>
						  <div class="nav">
							  <?php 
								echo 

									$html->image("buttons/button_spacer_2px.png") .

									$html->link($html->image("buttons/button_incidents.png", array('title' => 'Incidents', 'border' => 0)), "/incidents/", array('escape'=>false)) .
									$html->image("buttons/button_spacer_10px.png") . 

									$html->link($html->image("buttons/button_articles.png", array('title' => 'Articles', 'border' => 0)), "/articles/", array('escape'=>false)) .
									$html->image("buttons/button_spacer_10px.png") . 

									$html->link($html->image("buttons/button_kbentrys.png", array('title' => 'Knowledge Base', 'border' => 0)), "/kbentries/", array('escape'=>false)) .
									$html->image("buttons/button_spacer_10px.png")

								;
								?>
						  </div>
						  </td>
						</tr>
						<tr>
						  <td class="navLine">
						  </td>
						</tr>
					</table>
				  </td>
				</tr>
			  <tr>
				<td align="left" width="92%">
				  <div id="breadCrumb" class="breadCrumb">
					<?php echo $html->getCrumbs(' > ','Home'); ?>
				  </div>
				<td align="right" width="8%">
				  <?php echo $html->link($html->image("icons/help.png", array('title' => 'Help', 'border' => 0)), "/files/admin_guide.pdf", array('escape'=>false));?>
				  <?php echo $html->link($html->image("icons/printer.png", array('title' => 'Print', 'border' => 0)), "javascript:window.print()", array('escape'=>false));?>
				  <?php echo $html->link($html->image("icons/door_in.png", array('title' => 'Logout', 'border' => 0)), "/users/logout", array('escape'=>false));?>
				</td>
				</tr><tr>
				<td width="100%" colspan="2">
				  <div id="mainContent">
					<?php echo $content_for_layout ?>
				  </div>
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
		<tr>
		  <td width="860px" valign="bottom" align="center" height="50px">
			<?php echo $html->image('voyager_software.PNG'); ?>
		  </td>
		</tr>
	  </table>

	</div>

	<div id="footer">
		
	</div>

</div>

</body>
</html>