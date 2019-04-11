<?php
// Initialise the session
 session_start();



// Initialise Constants

 $nexus_db_server = "your_db_server";
 $nexus_db_name = "your_db_name";
 $nexus_db_username = "your_db_username";
 $nexus_db_password = "your_db_password";

 $nexus_site_url = "http://your_domain_name/nexus";
 
 $nexus_site_long_name = "PluggedOut Nexus";
 $nexus_site_short_name = "Nexus";
 
 $nexus_admin_name = "Your Admin Name";
 $nexus_admin_email = "your_admin@your_domain.com";


function nexus_db_connect()
{

	global $nexus_db_server;
	global $nexus_db_username;
	global $nexus_db_password;
	global $nexus_db_name;

	$con = mysql_connect($nexus_db_server,$nexus_db_username,$nexusdb_password);
	if (!(mysql_select_db($nexus_db_name,$con)))
	{
		// error
	}

	return $con;
}


function html_page_start($page_title){

	global $nexus_site_long_name;
	global $nexus_site_short_name;

	if ($_SESSION["nexus_userid"]!=""){
		$con = nexus_db_connect();
		$sql = "UPDATE nexus_users SET dLastActivity=now() WHERE nUserId=".$_SESSION["nexus_userid"];
		$result = mysql_query($sql);
	}

	$html = "<html>\n"
		."<head>\n"
		."<link rel='SHORTCUT ICON' href='".$site_url."/favicon.ico'>\n"
		."<title>".$site_long_name." - ".$page_title."</title>\n"
		."<style>\n"
		.".nexus_site_title{font-family:\"Trebuchet MS\",Verdana,Arial,Helvetica;font-size:36px;}\n"
		.".nexus_large{font-family:\"Trebuchet MS\",Verdana,Arial,Helvetica;font-size:24px;}\n"
		.".nexus_normal{font-family:Verdana,Arial,Helvetica;font-size:11px;}\n"
		.".nexus_small{font-family:Verdana,Arial,Helvetica;font-size:10px;}\n"
		.".nexus_title{font-family:\"Trebuchet MS\",Verdana,Arial,Helvetica;font-size:24px;font-weight:bold;}\n"
		.".nexus_heading{font-family:\"Trebuchet MS\",Verdana,Arial,Helvetica;font-size:16px;font-weight:bold;}\n"
		.".nexus_subheading{font-family:Verdana,Arial,Helvetica;font-size:12px;font-weight:bold;padding-left:5px;}\n"
		.".nexus_body{font-family:Verdana,Arial,Helvetica;font-size:11px;font-weight:normal;padding-left:10px;}\n"
		.".nexus_detail{font-family:Verdana,Arial,Helvetica;font-size:10px;font-weight:normal;padding-left:10px;}\n"
		.".nexus_button{font-family:\"Trebuchet MS\",Verdana,Arial,Helvetica;font-size:10px;border:1px bevel;color:#000;}\n"
		.".nexus_text{font-family:\"MS Trebuchet\",Verdana,Arial,Helvetica;font-size:10px;border:1px solid #676;color:#000;padding:2px;}\n"
		.".nexus_link{text-decoration:none;}\n"
		.".nexus_link:hover{color:#a00;}\n"
		."</style>\n"
		."<meta name='description' content='Communitys and friends website.'>\n"
		."<meta name='keywords' content='communities,community,friends,friend,contacts,contact,free,good'>\n"
		."<meta name='author' content='Jonathan Beckett mailto:jonbeckett@pluggedout.com'>\n"
		."<meta name='copyright' content='Jonathan Beckett mailto:jonbeckett@pluggedout.com'>\n"
		."<meta name='robots' content='index'>\n"
		."</head>\n"
		."<body style='margin:0px;padding:0px;'>\n"
		."<table width='100%' border='0' cellspacing='0' cellpadding='0'>\n"
		."<tr><td bgcolor='#ffffff'>"
		."<table border='0' cellspacing='0' cellpadding='0'><tr><td width='5'>&nbsp;</td><td><img src='images/nexus.png' width='48' height='48' title='".$site_long_name." - Free Community site'></td><td width='5'>&nbsp;</td><td><div class='site_title'><b>".$site_long_name."</b></div><div class='nexus_small' style='padding-left:20px;padding-bottom:5px;'>".$site_long_name." is a free website to find new friends around the world. Joining is free and easy - why not join today?</div></td></tr></table>"
		."</td></tr>\n"
		."<tr><td class='nexus_normal' bgcolor='#aabbaa'>"
		."<table border='0' cellspacing='0' cellpadding='2'><tr>"
		."<td class='nexus_normal' width='5'>&nbsp;</td>"
		."<td class='nexus_normal'><a href='index.php' class='link'><img src='images/icon_nexus.png' width='16' height='16' border='0' title='".$site_long_name." Homepage'></a></td>"
		."<td class='nexus_normal'><a href='index.php' class='link'><b>Home</b></a></td>"
		."<td class='nexus_normal' width='5'>&nbsp;</td>"
		."<td class='nexus_normal'><a href='news.php' class='link'><img src='images/icon_news.gif' width='16' height='16' border='0' title='".$site_long_name." News'></a></td>"
		."<td class='nexus_normal'><a href='news.php' class='link'><b>News</b></a></td>"
		."<td class='nexus_normal' width='5'>&nbsp;</td>"
		."<td class='nexus_normal'><a href='search.php' class='link'><img src='images/icon_search.png' width='16' height='16' border='0' title='Search Community Listings'></a></td>"
		."<td class='nexus_normal'><a href='search.php' class='link'><b>Search</b></a></td>"
		."<td class='nexus_normal' width='5'>&nbsp;</td>"
		."<td class='nexus_normal'><a href='forums.php' class='link'><img src='images/icon_forums.png' width='16' height='16' border='0' title='Forums'></a></td>"
		."<td class='nexus_normal'><a href='forums.php' class='link'><b>Forums</b></a></td>"
		."<td class='nexus_normal' width='5'>&nbsp;</td>"
		."<td class='nexus_normal'><a href='chat_requests.php' class='link'><img src='images/icon_yahoo.png' width='16' height='16' border='0' title='Chat Requests'></a></td>"
		."<td class='nexus_normal'><a href='chat_requests.php' class='link'><b>Chat Requests</b></a></td>"
		."<td class='nexus_normal' width='5'>&nbsp;</td>"
		."<td class='nexus_normal'><a href='links.php' class='link'><img src='images/icon_links.png' width='16' height='16' border='0' title='Links'></a></td>"
		."<td class='nexus_normal'><a href='links.php' class='link'><b>Links</b></a></td>"
		."<td class='nexus_normal' width='5'>&nbsp;</td>"		
		."<td class='nexus_normal'><a href='faq.php' class='link'><img src='images/icon_faq.png' width='16' height='16' border='0' title='".$site_short_name." Frequently Asked Questions'></a></td>"
		."<td class='nexus_normal'><a href='faq.php' class='link'><b>FAQ</b></a></td>"
		."</tr></table>"
		."</td></tr>\n"
		."</table>\n"
		."<table border='0' cellspacing='0' cellpadding='10' width='100%'>\n"
		."<tr><td valign='top' width='200' style='border-right:1px dashed #aba;'>\n";
		
	return $html;
}

function html_page_sep(){
	$html = "</td><td valign='top'>";
	return $html;
}

function html_page_end(){

	global $nexus_site_long_name;
	global $nexus_site_short_name;
	
	$html = "</td></tr>"
		."<tr><td colspan='2' align='center' class='nexus_small' style='border-top:1px solid #aba;color:#777;'>".$site_long_name." is based on the PluggedOut Nexus Community Website Engine<br>Copyright Jonathan Beckett, 2004, All Rights Reserved.<br>Please read the <a href='terms_and_conditions.php'>Terms and Conditions</a><br><a href='http://www.pluggedout.com'><img src='images/powered_by_pluggedout.gif' width='125' height='32' border='0' title='Powered by PluggedOut - visit http://www.pluggedout.com'></a></td></tr>\n"
		."</table>\n";
		
	return $html;
}



function html_menu_side(){

	global $nexus_site_long_name;
	global $nexus_site_short_name;
	global $nexus_admin_name;
	global $nexus_admin_email;
	
	$html = "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td>\n";

	// check if the user is logged in
	if ($_SESSION["nexus_userid"]!=""){
	
		$con = nexus_db_connect();
		$sql = "SELECT * FROM nexus_users WHERE nUserId=".$_SESSION["nexus_userid"];
		$result = mysql_query($sql,$con);
		$row = mysql_fetch_array($result);
	
		$html .= "<table width='100%' border='0' cellspacing='1' cellpadding='2' bgcolor='#aabbaa'>\n"
			."<tr><td bgcolor='#ffffff' align='left'>\n"
			."<div class='nexus_small'>You are logged in as</div><div class='nexus_large'><b>".$row["cUsername"]."</b></div>\n"
			."</td></tr>\n";
			
		
		
		// are they enabled or not?
		if ($_SESSION["nexus_enabled"]!=""){
			// show the full menu
			// hilight if they have not filled out their profile yet
			
			if ($row["cBio"]==""){
				$html .= "<tr><td bgcolor='#ffffff'><div class='subheading'>Tip</div><div class='body'>It looks like you haven't finished filling out your profile yet - use the link below to do it now.</div></td></tr>\n";
			}
			
		} else {

			// show the form for enabling the account
			$html .= "<tr><td bgcolor='#ffffff'>\n"
				."<form method='POST' action='user_enable_exec.php'>\n"
				."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aabbaa'>\n"
				."<tr><td colspan='2' class='nexus_small' bgcolor='#aabbaa'><b>Member Enable Form</b></td></tr>\n"
				."<tr><td class='nexus_small' bgcolor='#ffffff'>Enabling Code</td><td bgcolor='#ffffff'><input type='text' name='enable_code' class='text'></td></tr>\n"
				."<tr><td colspan='2' bgcolor='#ffffff' align='right'><input type='submit' value='Enable' class='button'></td></tr>\n"
				."<tr><td class='nexus_small' bgcolor='#ffffff' colspan='2'>If you have lost your enabling code, you can re-send it by clicking <b><a href='enable_code_send.php'>here</a></b>.</td></tr>\n"
				."</table>\n"
				."</form>\n"
				."</td></tr>\n";
		}

		// show generic menu items
		$html .= "<tr><td bgcolor='#aabbaa' class='nexus_small' valign='top'><b>Member Options</b></td></tr>\n"
			."<tr><td bgcolor='#ffffff' valign='top'>\n"
			."  <table border='0' cellspacing='0' cellpadding='1'>\n"
			."  <tr><td colspan='4' class='nexus_small'>&nbsp;</td></tr>\n"
			."  <tr><td colspan='4' class='subheading'>Website Sections</td></tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='index.php'><img src='images/icon_nexus.png' width='16' height='16' title='".$nexus_site_short_name." Homepage' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='index.php'>Home</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='news.php'><img src='images/icon_news.gif' width='16' height='16' title='".$nexus_site_short_name." News' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='news.php'>News</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='search.php'><img src='images/icon_search.png' width='16' height='16' title='Search ".$nexus_site_short_name." Listings' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='search.php'>Search</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='forums.php'><img src='images/icon_forums.png' width='16' height='16' title='Forums' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='forums.php'>Forums</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='chat_requests.php'><img src='images/icon_yahoo.png' width='16' height='16' title='Chat Requests' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='chat_requests.php'>Chat Requests</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='links.php'><img src='images/icon_links.png' width='16' height='16' title='Links' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='links.php'>Links</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='faq.php'><img src='images/icon_faq.png' width='16' height='16' title='Frequently Asked Questions' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='faq.php'>FAQ</a></td>"
			."  </tr>\n"
			
			."  <tr><td colspan='4' class='nexus_small'>&nbsp;</td></tr>\n"
			."  <tr><td colspan='4' class='subheading'>Forums</td></tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='forums.php?action=view_forum&forumid=1'><img src='images/icon_forums.png' width='16' height='16' title='Forums' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='forums.php'>Forum List</a></td>"
			."  </tr>\n"
			
			."  <tr><td colspan='4' class='nexus_small'>&nbsp;</td></tr>\n"
			."  <tr><td colspan='4' class='subheading'>Message Centre</td></tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='messages.php?action=inbox'><img src='images/icon_inbox.png' width='16' height='16' title='Community Message Centre In-Box' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='messages.php?action=inbox'>In-Box</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='messages.php?action=outbox'><img src='images/icon_outbox.png' width='16' height='16' title='Community Message Centre Out-Box' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='messages.php?action=outbox'>Out-Box</a></td>"
			."  </tr>\n"
			."  <tr><td colspan='4' class='nexus_small'>&nbsp;</td></tr>\n"
			."  <tr><td colspan='4' class='subheading'>User Profile</td></tr>\n"			
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='user_view.php?userid=".$_SESSION["nexus_userid"]."'><img src='images/icon_user_view.png' width='16' height='16' title='View Your User Profile' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='user_view.php?userid=".$_SESSION["nexus_userid"]."'>View Profile</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='user_edit.php'><img src='images/icon_edit_profile.png' width='16' height='16' title='Edit User Profile' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='user_edit.php'>Edit Profile</a></td>"
			."  </tr>\n"
			."  <tr><td colspan='4' class='nexus_small'>&nbsp;</td></tr>\n"
			."  <tr><td colspan='4' class='subheading'>Other</td></tr>\n"			
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='user_cancel.php'><img src='images/delete.gif' width='16' height='16' title='Cancel your ".$nexus_site_long_name." account' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='user_cancel.php'>Cancel Membership</a></td>"
			."  </tr>\n"
			."  <tr>"
			."  <td width='10' class='nexus_small'>&nbsp;</td>"
			."  <td width='16'><a href='logout_exec.php'><img src='images/icon_logout.png' width='16' height='16' title='Logout of ".$nexus_site_long_name."' border='0'></a></td>"
			."  <td width='5' class='nexus_small'>&nbsp;</td>"
			."  <td class='nexus_normal'><a href='logout_exec.php'>Logout</a></td>"
			."  </tr>\n"
			."  <tr><td colspan='4' class='nexus_small'>&nbsp;</td></tr>\n"
			."  </table>\n"
			."</td></tr>\n"
			."</table>\n";
			
			/*
			."<div class='nexus_small'>&nbsp;</div>\n"
			."<div class='subheading'>Website Sections</div>\n"
			."<div class='body'><tr><td></td><td><a href='index.php'>Home</a></td></div>\n"
			."<div class='body'>&nbsp;&raquo;&nbsp;<a href='search.php'>Search</a></div>\n"
			."<div class='nexus_small'>&nbsp;</div>\n"
			."<div class='subheading'>Message Centre</div>\n"
			."<div class='body'>&nbsp;&raquo;&nbsp;<a href='messages.php?action=inbox'>In-Box</a></div>\n"
			."<div class='body'>&nbsp;&raquo;&nbsp;<a href='messages.php?action=outbox'>Out-Box</a></div>\n"
			."<div class='nexus_small'>&nbsp;</div>\n"
			."<div class='subheading'>Profile</div>\n"
			."<div class='body'>&nbsp;&raquo;&nbsp;<a href='user_edit.php'>Edit Profile</a></div>\n"
			."<div class='nexus_small'>&nbsp;</div>\n"
			."<div class='subheading'>Other</div>\n"
			."<div class='body'>&nbsp;&raquo;&nbsp;<a href='logout_exec.php'>Logout</a></div>\n"
			."<div class='nexus_small'>&nbsp;</div>\n"
			."</td></tr>\n";
			*/
		
	} else {

		// show join and login information
		$html .="<p align='left'><span class='nexus_large'><b><a href='user_add.php'>Join Today!</a></b></span><br><span class='nexus_small'>Click the link above to join ".$site_short_name." - it's Free!</span></p>";

		$html .= "<form method='POST' action='login_exec.php'>\n"
			."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aabbaa'>\n"
			."<tr><td colspan='2' class='nexus_small' bgcolor='#aabbaa'><b>Login Form</b></td></tr>\n"
			."<tr><td class='nexus_small' bgcolor='#ffffff'>Username</td><td bgcolor='#ffffff'><input type='text' name='username' class='text' size='12'></td></tr>\n"
			."<tr><td class='nexus_small' bgcolor='#ffffff'>Password</td><td bgcolor='#ffffff'><input type='password' name='password' class='text' size='12'></td></tr>\n"
			."<tr><td class='nexus_normal' bgcolor='#ffffff' colspan='2' align='right'><input type='submit' value='Login' class='button'></td></tr>\n"
			."</table>\n"
			."</form>\n"
			."<p class='nexus_heading'>Forgotten Password?</p>\n"
			."<p class='detail'>If you have forgotten your username or password, click <a href='forgotten_password.php'>here</a>.</p>\n"
			."<p class='nexus_heading'>Guide to Joining</p>\n"
			."<p class='subheading'>1. Register a Username</p>\n"
			."<p class='detail'>If you click on the Join link at the top right of the page, you can reserve a username/nickname for yourself within the ".$site_long_name." website.</p>\n"
			."<p class='subheading'>2. Enable Your Membership</p>\n"
			."<p class='detail'>When you register with the site, you will be sent an email straight away with an enabling code in it. After you've logged in for the first time, you'll need to enter the code to prove you are who you say you are. It's just a little security measure to help everybody protect themselves.</p>\n"
			."<p class='subheading'>3. Fill Out Your Profile</p>\n"
			."<p class='detail'>When you first join, all you have told anybody about yourself is the nickname you would like to be known as - when you fill your profile in, you are telling anybody who might look a bit more about yourself - maybe enough got them to send you a message!</p>\n"
			."<p class='subheading'>4. Start Messaging People!</p>\n"
			."<p class='detail'>That's it - you're a member, and you can start sending messages to other members. You could of course do this straight after enabling your membership, but if somebody wonders who you are when you message them, they're not going to have much to read in your profile, are they!</p>\n"
			."<p class='nexus_heading'>What If I'm Stuck?</p>\n"
			."<p class='detail'>If you get stuck using the website, just send an email to ".$admin_name." (<a href='mailto:".$admin_email."'>".$admin_name."</a>).</p>\n";

	}

	$html .= "</td></tr></table>\n";
	
	// adverts
	$html .= "<br><table width='200' border='0' cellspacing='1' cellpadding='2' bgcolor='#aabbaa'>\n"
			."<tr><td bgcolor='#ffffff' class='detail' align='center'><a href='http://www.coolsiteoftheday.com' class='link'><img src='images/coolsiteoftheday_144_82.gif' width='144' height='82' border='0' title='Cool Site of the Day, 27th September 2004'><br>PluggedOut Nexus was awarded 'Cool Site of the Day' on 27th September 2004.</a></td></tr>\n"
			."</table>\n";
	
	$html .= "<div class='detail'>&nbsp;</div>\n";
	
	$html .= "<table width='200' border='0' cellspacing='1' cellpadding='2' bgcolor='#aabbaa'>\n"
		."<tr><td bgcolor='#ffffaa' class='nexus_normal' align='center'><b>Advertise Here!</b><br>You could be advertising here. Contact <a href='mailto:".$admin_email."'>".$admin_email."</a> for details.</td></tr>\n"
		."</table>\n";
	
	$html .= "<div class='detail'>&nbsp;</div>\n";
	
	$html .= "<table width='200' border='0' cellspacing='1' cellpadding='2' bgcolor='#aabbaa'>\n"
		."<tr><td bgcolor='#ffffff'><div class='detail' align='center'>Fed Up of Internet Explorer?<br><a href='http://www.spreadfirefox.com/?q=affiliates&amp;id=0&amp;t=75'><img border='0' alt='Get Firefox!' title='Get Firefox!' src='http://www.spreadfirefox.com/community/images/affiliates/Buttons/120x60/trust.gif'/></a></div></td></tr>\n"
		."</table>\n";
	
	return $html;
}


function html_user_add(){

	$html .= "<p class='title'>Account Creation</p>\n"
		."<p class='heading'>Terms and Conditions of Membership</p>\n"
		."<p class='body'>Before joining this website you must read and agree to the <a href='terms_and_conditions.php'><b>terms and conditions</b></a> of the website.</p>\n"
		."<p class='heading'>Joining Form</p>\n"
		."<p class='body'>Use the form below to create a user account at ".$site_long_name.".</p>\n";

	$html .= $html_problem;

	$html .= "<div style='padding-left:40px;'><form method='POST' action='user_add.php'>\n"
		."<input type='hidden' name='action' value='submit'>\n"
		."<table border='0' cellspacing='1' cellpadding='2' bgcolor='#aabbaa'>\n"
		."<tr><td bgcolor='#aabbaa' class='small'><b>User Join Form</b></td></tr>\n"
		."<tr><td bgcolor='#ffffff' class='small'>Username</td><td bgcolor='#ffffff'><input class='text' name='username' type='text' value='".$_POST["username"]."'></td></tr>\n"
		."<tr><td bgcolor='#ffffff' class='small'>Password</td><td bgcolor='#ffffff'><input class='text' name='password' type='password' value='".$_POST["password"]."'></td></tr>\n"
		."<tr><td bgcolor='#ffffff' class='small'>E-Mail</td><td bgcolor='#ffffff'><input class='text' name='email' type='text' value='".$_POST["email"]."'></td></tr>\n"
		."<tr><td bgcolor='#ffffff' colspan='2' align='right'><input class='button' type='submit' value='Add User'></td></tr>\n"
		."</table>\n"
		."</form></div>\n";

	$html .= "<p class='body'>After you have joined the site, an account enabling code will be sent to the email address you enter in the form above. When you login to the site for the first time, it will ask for that enabling code. Your listing will not appear in the site until you have enabled your account.</p>\n"
		."<p class='body'>The email address you enter above will never be shown to anybody - it is kept on file by the website in order to send you notifications, and for the administrators of the site to contact you should they ever need to.</p>\n"
		."<p class='body'>If you get stuck, just send an email to <a href='mailto:".$admin_email."'>".$admin_email."</a>.</p>\n"
		."<p class='heading'>Disclaimer</p>\n"
		."<p class='body'>By joining ".$site_short_name." you agree that you will not abuse the facilities of the site in any way. You also agree that you are posting your details entirely at your own risk and will not hold the site responsible for the actions of others abusing those facilities.</p>\n";

	return $html;
}

function exec_user_add(){
	// Check for mandatory fields
	if ($_POST["username"]!="" && $_POST["password"]!="" && $_POST["email"]!=""){
		
		// check if the data looks okay
		if (1==1) {
			
			// check if the username already exists
			$con = db_connect();
			$sql = "SELECT COUNT(nUserId) AS nCountUsers FROM nexus_users WHERE cUsername LIKE '".$_POST["username"]."' OR cPassword LIKE '".$_POST["password"]."'";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				
				$row = mysql_fetch_array($result);
				
				if ($row["nCountUsers"]==0){
					
					// username does not exist - therefore generate an enable code
					// add the record, and send the email
					$username = mysql_escape_string($_POST["username"]);
					$password = mysql_escape_string($_POST["password"]);
					$email = mysql_escape_string($_POST["email"]);
					$enable_code = misc_createcode(12);
					
					$sql = "INSERT INTO nexus_users (cUsername,cPassword,cEMailPrivate,cEnableCode,dAdded)"
						." VALUES ('".$username."','".$password."','".$email."','".$enable_code."',now())";
					
					$result = mysql_query($sql,$con);
					

					$sql = "SELECT * FROM nexus_users WHERE cUsername='".$_POST["username"]."'";
					$result = mysql_query($sql,$con);
					if ($result!=false){

						$row = mysql_fetch_array($result);

						$from = $site_admin_email;
						$to = $row["cEMailPrivate"];
						$subject = "Welcome message from ".$site_long_name."!";
						$body = "Welcome to the ".$site_long_name." Website!\n\n"
							."Here are the login details you used for future reference. If you ever forget your username and password, there is a button on the login page to re-send your username and password.\n\n"
							."Username : ".$row["cUsername"]."\n"
							."Password : ".$row["cPassword"]."\n\n"
							."The enabling code for your membership is : ".$row["cEnableCode"]."\n\n"
							."The ".$site_short_name." website will ask for your enabling code after you login. Until you enable your account, you will not be able to see how to contact people.\n\n"
							."If you get stuck at all, send an email to ".$admin_email.".\n\n"
							."Jonathan Beckett\n"
							."".$admin_email."\n";

						send_email($from,$to,$subject,$body);

						$url = "Location: user_add_success.php";
					} else {
						$url = "Location: site_problem.php";
					}
				} else {
					$html_problem = "<p class='body'><b>Please choose a different username and/or email address. '".$_POST["username"]."' or '".$_POST["email"]."' has already been taken.</b></p>";
				}
			} else {
				$html_problem = "<p>Problem accessing database.</p>\n";
			}
		} else {
		}
		
	} else {
		// data was missing in form
		$html_problem = "<p>All the fields must be filled out.</p>\n";
	}
}


// start actually building the plugin content

$html = html_page_start("Homepage");

// left hand side
$html .= html_menu_side();

$html .= html_page_sep();

// content
$html .= "<table border='0' cellspacing='0' cellpadding='0'><tr>"
	."<td><img src='images/nexus.png' width='48' height='48' title='Free Online ".$site_short_name."'></td>"
	."<td width='5'>&nbsp;</td>"
	."<td><div class='nexus_title'>Welcome to ".$site_long_name."</div></td>\n"
	."</tr></table>\n"
	."<p class='body'>Welcome to ".$site_long_name." - probably the most simple Community site on the internet. This site exists because all the other sites around try and do too much - all you need to do here is register your details, tell everybody you know (who might be interested) about the site, and start messaging the people within the site. Before you know it, you'll have more friends than you have time to write to!</p>\n"
	."<p class='nexus_heading'>Help Make a Difference</p>\n"
	."<p class='body'>Most of the reason this website is here is to give something back to the internet, and to help make the world a little bit smaller and a little bit more friendly for somebody somewhere when they might just need it. Of course the only way the website can help bring people together is if they know about it, so please do everything you can think of. Put <a href='links.php'>links</a> in your websites, and add <a href='links.php'>footers</a> to your emails telling people about this simple little website that is trying to prove that people can make a difference to each other's lives.</p>\n";

$html .= html_recent_member_list();

$html .= html_page_end();

print $html;



?>