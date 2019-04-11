<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : admin_exec.php
#= Version: 0.4.9 (2005-05-10)
#= Author : Jonathan Beckett
#= Email  : jonbeckett@pluggedout.com
#= Website: http://www.pluggedout.com/index.php?pk=dev_cms
#= Support: http://www.pluggedout.com/development/forums/viewforum.php?f=14
#===========================================================================
#= Copyright (c) 2004 Jonathan Beckett
#= You are free to use and modify this script as long as this header
#= section stays intact. This file is part of CMS.
#=
#= This program is free software; you can redistribute it and/or modify
#= it under the terms of the GNU General Public License as published by
#= the Free Software Foundation; either version 2 of the License, or
#= (at your option) any later version.
#=
#= This program is distributed in the hope that it will be useful,
#= but WITHOUT ANY WARRANTY; without even the implied warranty of
#= MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#= GNU General Public License for more details.
#=
#= You should have received a copy of the GNU General Public License
#= along with CMS files; if not, write to the Free Software
#= Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#===========================================================================
*/

// Include library functions

include "./lib/session.php";
include "./lib/config.php";
include "./lib/database.php";
include "./lib/misc.php";
include "./lib/dms.php";
include "./lib/html_public.php";

// record this page being processed in the audit trail	
audit_trail();

if ( $_SESSION["cms_userid"]!="1" ) {

	// Do programming for various actions (each encapsulated in an IF statement)

	if ($_GET["action"]=="page_add"){
		// make sure we have the correct info
		$key = mysql_escape_string($_POST["key"]);
		$title = mysql_escape_string($_POST["title"]);
		$pagetypeid = mysql_escape_string($_POST["pagetypeid"]);
		$notes = mysql_escape_string($_POST["notes"]);
		$templateid = mysql_escape_string($_POST["templateid"]);
		if ($title!="") {
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."Pages (cPageKey,cTitle,nPageTypeId,cNotes,nTemplateId,nUserAdded,dAdded) VALUES ('".$key."','".$title."',".$pagetypeid.",'".$notes."',".$templateid.",".$_SESSION["cms_userid"].",now())";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				$pageid = mysql_insert_id();


				// if the base_on_page value was set, use it
				$sql = "SELECT nPageId,cContentKey,nTemplateId,nTemplateElementId,nIndex,nUserAdded,dAdded FROM ".$db_tableprefix."PageContent WHERE nPageId=".$_POST["base_on_page"];
				$result = mysql_query($sql,$con);
				if ($result!=false){
					if (mysql_num_rows($result)>0){
						while ($row =@ mysql_fetch_array($result)){

							$sql = "INSERT INTO ".$db_tableprefix."PageContent (nPageId,cContentKey,nTemplateId,nTemplateElementId,nIndex,nUserAdded,dAdded)"
								." VALUES ("
								.$pageid
								.",'".$row["cContentKey"]."'"
								.",".$row["nTemplateId"]
								.",".$row["nTemplateElementId"]
								.",".$row["nIndex"]
								.",".$row["nUserAdded"]
								.",'".$row["dAdded"]."'"
								.")";
							$insert_result = mysql_query($sql,$con);

							if ($insert_result==false){
								header("Location: admin.php?action=problem");
							}
						}
					} else {
						header("Location: admin.php?action=problem");
					}

				} else {
					header("Location: admin.php?action=problem");
				}


				header("Location: admin.php?action=page_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}


	if ($_GET["action"]=="usertype_add"){
		// make sure we have the correct info
		$name = mysql_escape_string($_POST["name"]);
		if ($name!="") {
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."UserType (cUserTypeName) VALUES ('".$name."')";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=usertype_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="usertypemember_remove"){
		// make sure we have the correct info
		$usertypememberid = mysql_escape_string($_GET["usertypememberid"]);
		if ($usertypememberid!="") {
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."UserTypeMember WHERE nUserTypeMemberId=".$usertypememberid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=user_edit&userid=".$_GET["userid"]);
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="contenttype_add"){
		// make sure we have the correct info
		$name = mysql_escape_string($_POST["name"]);
		$function = mysql_escape_string($_POST["function"]);
		$file = mysql_escape_string($_POST["file"]);
		$approval_required = mysql_escape_string($_POST["approval_required"]);
		if ($name!="") {
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."ContentType (cContentTypeName,cFunction,cApprovalRequired) VALUES ('".$name."','".$function."','".$approval_required."')";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=contenttype_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="contenttype_del"){

		$contenttypeid = $_GET["contenttypeid"];

		if ($contenttypeid!="") {
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."ContentType WHERE nContentTypeId=".$contenttypeid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=contenttype_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}
	
	if ($_GET["action"]=="contenttype_edit"){
		// make sure we have the correct info
		$contenttypeid=$_POST["contenttypeid"];
		$name = mysql_escape_string($_POST["name"]);
		$function = mysql_escape_string($_POST["function"]);
		$file = mysql_escape_string($_POST["file"]);
		$approval_required = mysql_escape_string($_POST["approval_required"]);
		if ($contenttypeid!="") {
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."ContentType SET cContentTypeName='".$name."',cFunction='".$function."',cApprovalRequired='".$approval_required."' WHERE nContentTypeId=".$contenttypeid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=contenttype_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="page_edit"){
	
		$url = "admin.php?action=page_list";
		
		// make sure we have the correct info
		$pageid = mysql_escape_string($_POST["pageid"]);
		$key = mysql_escape_string($_POST["key"]);
		$title = mysql_escape_string($_POST["title"]);
		$pagetypeid = mysql_escape_string($_POST["pagetypeid"]);
		$notes = mysql_escape_string($_POST["notes"]);
		$templateid = mysql_escape_string($_POST["templateid"]);
		if ($pageid!="" && $title!="") {
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."Pages SET cPageKey='".$key."',cTitle='".$title."',nPageTypeId=".$pagetypeid.",cNotes='".$notes."',nTemplateId=".$templateid.",nUserEdited=".$_SESSION["cms_userid"].",dEdited=Now() WHERE nPageId=".$pageid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				$url .= "";
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}

		// deal with page meta-data
		$numrows = $_POST["numrows"];
		for ($i=1;$i<=$numrows;$i++){
		
			$propid = $_POST["prop".$i."_propid"];
			$dataid = $_POST["prop".$i."_dataid"];
			$datatype = $_POST["prop".$i."_datatype"];
			$datavalue = mysql_escape_string($_POST["prop".$i."_value"]);
			
			switch ($datatype){
				case "dDataDate":
					$datavalue = "'".$datavalue."'";
					break;
				case "cDataVarchar":
					$datavalue = "'".$datavalue."'";
					break;
				case "cDataMediumText":
					$datavalue = "'".$datavalue."'";
					break;
				case "bDataBlob":
					$datavalue = "'".$datavalue."'";
					break;
			}
			
			// check if the property exists before we do anything to it
			if ($dataid!=""){
				// the record exists - update it
				$sql = "UPDATE ".$db_tableprefix."PageData SET ".$datatype."=".$datavalue." WHERE nPageDataId=".$dataid;
				$result = mysql_query($sql,$con);
				if ($result==false){
					header("Location: admin.php?action=problem");
				}
			} else {
				// the record does not exist - insert it
				$sql = "INSERT INTO ".$db_tableprefix."PageData (nPageId,nPropertyId,".$datatype.")"
					." VALUES (".$pageid.",".$propid.",".$datavalue.")";
				$result = mysql_query($sql,$con);
				if ($result==false){
					header("Location: admin.php?action=problem");
				}
			}
			
			
		}
		header("Location: ".$url);
	}

	if ($_GET["action"]=="page_del"){
		// make sure we have the correct info
		$pageid = $_GET["pageid"];

		if ($pageid!="") {
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."Pages WHERE nPageId=".$pageid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				$resid = mysql_insert_id();
				header("Location: admin.php?action=page_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}


	if ($_GET["action"]=="content_add"){
		// make sure we have the correct info
		$contentkey = mysql_escape_string($_POST["contentkey"]);
		$template = mysql_escape_string($_POST["template"]);
		$contenttypeid = mysql_escape_string($_POST["contenttypeid"]);
		$function = mysql_escape_string($_POST["function"]);
		$file = mysql_escape_string($_POST["file"]);
		$start = mysql_escape_string($_POST["start"]);
		$end = mysql_escape_string($_POST["end"]);
		$title = mysql_escape_string($_POST["title"]);
		$body = mysql_escape_string($_POST["body"]);
		if ( $title!="" && $body!="") {
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."Content (cContentKey,nTemplateId,nContentTypeId,cFunction,dStart,dEnd,cTitle,cBody,nUserAdded,dAdded) VALUES ('".$contentkey."',".$template.",".$contenttypeid.",'".$function."','".$start."','".$end."','".$title."','".$body."',".$_SESSION["cms_userid"].",Now())";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=content_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.[".$title."][".$body."]</li>\n";
		}
	}

	if ($_GET["action"]=="content_edit"){
	
		// set the return url immediately - then we can append error codes if we need to
		$url = "admin.php?action=content_list";
	
		// make sure we have the correct info
		$contentid = mysql_escape_string($_POST["contentid"]);
		$contentkey = mysql_escape_string($_POST["contentkey"]);
		$template = mysql_escape_string($_POST["template"]);
		$contenttypeid = mysql_escape_string($_POST["contenttypeid"]);
		$function = mysql_escape_string($_POST["function"]);
		$file = mysql_escape_string($_POST["file"]);
		$approve = mysql_escape_string($_POST["approve"]);
		$dstart = mysql_escape_string($_POST["start"]);
		$dend = mysql_escape_string($_POST["end"]);
		$title = mysql_escape_string($_POST["title"]);
		$body = mysql_escape_string($_POST["body"]);
		if ($contentid!="" && $title!="" && $body!="") {
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."Content SET cContentKey='".$contentkey."',nTemplateId=".$template.",nContentTypeId=".$contenttypeid.",cFunction='".$function."',dStart='".$dstart."',dEnd='".$dend."',cTitle='".$title."',cBody='".$body."',nUserEdited=".$_SESSION["cms_userid"].",dEdited=Now(),cApproved='".$approve."' WHERE nContentId=".$contentid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				$url .= "";

			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
		
		// deal with content meta-data
		$numrows = $_POST["numrows"];
		for ($i=1;$i<=$numrows;$i++){
		
			$propid = $_POST["prop".$i."_propid"];
			$dataid = $_POST["prop".$i."_dataid"];
			$datatype = $_POST["prop".$i."_datatype"];
			$datavalue = mysql_escape_string($_POST["prop".$i."_value"]);
			
			switch ($datatype){
				case "dDataDate":
					$datavalue = "'".$datavalue."'";
					break;
				case "cDataVarchar":
					$datavalue = "'".$datavalue."'";
					break;
				case "cDataMediumText":
					$datavalue = "'".$datavalue."'";
					break;
				case "bDataBlob":
					$datavalue = "'".$datavalue."'";
					break;
			}
			
			// check if the property exists before we do anything to it
			
			if ($dataid!=""){
				// the record exists - update it
				$sql = "UPDATE ".$db_tableprefix."ContentData SET ".$datatype."=".$datavalue." WHERE nContentDataId=".$dataid;
				$result = mysql_query($sql,$con);
				if ($result==false){
					header("Location: admin.php?action=problem");
				}
			} else {
				// the record does not exist - insert it
				$sql = "INSERT INTO ".$db_tableprefix."ContentData (nContentId,nPropertyId,".$datatype.")"
					." VALUES (".$contentid.",".$propid.",".$datavalue.")";
				$result = mysql_query($sql,$con);
				if ($result==false){
					header("Location: admin.php?action=problem");
				}
			}
			
		}
		
		header("Location: ".$url);
	}

	if ($_GET["action"]=="content_approve"){
		$contentid = $_GET["contentid"];
		if ($contentid!=""){
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."Content SET cApproved='x' WHERE nContentId=".$contentid;
			if ($result!=false){
				header("Location : admin.php?action=content_list");
			} else {
				header("Location : admin.php?action=problem");
			}
		}
	}

	if ($_GET["action"]=="content_unapprove"){
		$contentid = $_GET["contentid"];
		if ($contentid!=""){
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."Content SET cApproved='' WHERE nContentId=".$contentid;
			if ($result!=false){
				header("Location : admin.php?action=content_list");
			} else {
				header("Location : admin.php?action=problem");
			}
		}
	}

	if ($_GET["action"]=="content_del"){

		$contentid = $_GET["contentid"];

		if ($contentid!="") {
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."Content WHERE nContentId=".$contentid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=content_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}


	if ($_GET["action"]=="pagecontent_add"){
		$pageid = $_POST["pageid"];
		$contentkey = mysql_escape_string($_POST["contentkey"]);
		$templateid = $_POST["templateid"];
		$templateelementid = $_POST["templateelementid"];
		$index = $_POST["index"];
		if ($pageid!="" && $contentkey!="" && $index!=""){
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."PageContent (nPageId,cContentKey,nTemplateId,nTemplateElementId,nIndex,nUserAdded,dAdded) VALUES (".$pageid.",'".$contentkey."',".$templateid.",".$templateelementid.",".$index.",".$_SESSION["cms_userid"].",Now())";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				header("Location: admin.php?action=page_edit&pageid=".$pageid);
			} else {
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}


	if ($_GET["action"]=="pagecontent_edit"){
		$pagecontentid= $_POST["pagecontentid"];
		$pageid = $_POST["pageid"];
		$contentkey = $_POST["contentkey"];
		$templateid = $_POST["templateid"];
		$templateelementid = $_POST["templateelementid"];
		$index = $_POST["index"];
		if ($pageid!="" && $contentkey!="" && $templateelementid!="" && $index!=""){
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."PageContent SET nPageId=".$pageid.",cContentKey='".$contentkey."',nTemplateId=".$templateid.",nTemplateElementId=".$templateelementid.",nIndex=".$index.",nUserEdited=".$_SESSION["cms_userid"].",dEdited=Now() WHERE nPageContentId=".$pagecontentid;
			$result = mysql_query($sql,$con);
			if ($result!=false){
				header("Location: admin.php?action=page_edit&pageid=".$pageid);
			} else {
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}


	if ($_GET["action"]=="pagecontent_del"){
		$pageid = $_GET["pageid"];
		$pagecontentid = $_GET["pagecontentid"];
		if ($pagecontentid!=""){
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."PageContent WHERE nPageContentId=".$pagecontentid;
			$result = mysql_query($sql,$con);
			if ($result!=false){
				header("Location: admin.php?action=page_edit&pageid=".$pageid);
			} else {
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}


	if($_GET["action"]=="menuitem_add"){
		// make sure we have the correct info
		$pageid = mysql_escape_string($_POST["pageid"]);
		$section = mysql_escape_string($_POST["section"]);
		$title = mysql_escape_string($_POST["title"]);
		$caption = mysql_escape_string($_POST["caption"]);
		$linkid = mysql_escape_string($_POST["linkid"]);
		$index = mysql_escape_string($_POST["index"]);
		if ($pageid!="" && $title!="" && $caption!="" && $linkid!="" && $index!="") {
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."MenuItems (nPageId,cSection,cTitle,cCaption,nLinkedPageId,nIndex,nUserAdded,dAdded) VALUES (".$pageid.",'".$section."','".$title."','".$caption."',".$linkid.",".$index.",".$_SESSION["cms_userid"].",Now())";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=page_edit&pageid=".$pageid);
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="menuitem_edit"){
		// make sure we have the correct info
		$menuitemid = mysql_escape_string($_POST["menuitemid"]);
		if ($menuitemid=="") { mysql_escape_string($menuitemid=$_GET["menuitemid"]); }
		$pageid = mysql_escape_string($_POST["pageid"]);
		if ($pageid=="") { $resid=mysql_escape_string($_GET["pageid"]); }
		$section = mysql_escape_string($_POST["section"]);
		$title = mysql_escape_string($_POST["title"]);
		$caption = mysql_escape_string($_POST["caption"]);
		$linkid = mysql_escape_string($_POST["linkid"]);
		$index = mysql_escape_string($_POST["index"]);
		if ( ($_GET["action"]=="menuitem_edit" && $menuitemid!="" && $pageid!="" && $title!="" && $caption!="" && $linkid!="" && $index!="") || $_GET["action"]=="up" || $_GET["action"]=="down") {
			$con = db_connect();
			switch($_GET["action"]) {

				case "menuitem_edit":
					$sql = "UPDATE ".$db_tableprefix."MenuItems SET cSection='".$section."',cTitle='".$title."',cCaption='".$caption."',nIndex=".$index.", nLinkedPageId=".$linkid.",nUserEdited=".$_SESSION["cms_userid"].",dEdited=Now() WHERE nMenuItemId=".$menuitemid;
					break;
				case "menuitem_up":
					$sql = "UPDATE ".$db_tableprefix."MenuItems SET nIndex=nIndex-1 WHERE nMenuItemId=".$menuitemid.";";
					break;
				case "menuitem_down":
					$sql = "UPDATE ".$db_tableprefix."MenuItems SET nIndex=nIndex+1 WHERE nMenuItemId=".$menuitemid.";";
					break;
			}
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=page_edit&pageid=".$pageid);
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="menuitem_del"){
		// make sure we have the correct info
		$pageid = $_GET["pageid"];
		$menuitemid = $_GET["menuitemid"];

		if ($menuitemid!="") {
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."MenuItems WHERE nMenuItemId=".$menuitemid.";";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=page_edit&pageid=".$pageid);
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="template_add"){
		$type = mysql_escape_string($_POST["type"]);
		$title = mysql_escape_string($_POST["title"]);
		$template = mysql_escape_string($_POST["template"]);
		if ($title!="" && $template!=""){
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."Templates"
				."(cType,cTitle,cTemplate,nUserAdded,dAdded) VALUES ('".$type."','".$title."','".$template."',".$_SESSION["cms_userid"].",Now())";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				// redirect
				header("Location: admin.php?action=template_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		}
	}

	if ($_GET["action"]=="template_edit"){

		$templateid = mysql_escape_string($_POST["templateid"]);
		$type = mysql_escape_string($_POST["type"]);
		$title = mysql_escape_string($_POST["title"]);
		$template = mysql_escape_string($_POST["template"]);

		if ($templateid!="" && $title!="" && $template!=""){
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."Templates SET cType='".$type."',cTitle='".$title."',cTemplate='".$template."',nUserEdited=".$_SESSION["cms_userid"].",dEdited=Now() WHERE nTemplateId=".$templateid;

			$result = mysql_query($sql,$con);
			if ($result!=false){
				// redirect
				header("Location: admin.php?action=template_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Missing Form Data</li>\n";
		}
	}


	if ($_GET["action"]=="user_add"){

		$username = mysql_escape_string($_POST["cms_username"]);
		$password = mysql_escape_string(crypt($_POST["cms_password"],"$2a$07$doowaadiddydiddydumdiddydoo$"));
		$email = mysql_escape_string($_POST["email"]);
		$admin = mysql_escape_string($_POST["admin"]);

		if ($username!="" && $password!=""){
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."Users "
				."(cUsername,cPassword,cEMailAddress,cAdmin,nUserAdded,dAdded) VALUES ('".$username."','".$password."','".$email."','".$admin."',".$_SESSION["cms_userid"].",Now())";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				// redirect
				header("Location: admin.php?action=user_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		}
	}


	if ($_GET["action"]=="user_edit"){

		$userid = mysql_escape_string($_POST["userid"]);
		$username = mysql_escape_string($_POST["cms_username"]);
		$password = mysql_escape_string($_POST["cms_password"]);
		$email = mysql_escape_string($_POST["email"]);
		$admin = mysql_escape_string($_POST["admin"]);

		if ($userid!="" && $userid!="guest" && $userid!="admin" && $username!=""){
			$con = db_connect();
			if ($password!=""){
				$password = mysql_escape_string(crypt($_POST["cms_password"],$crypt_salt));
				$sql = "UPDATE ".$db_tableprefix."Users SET cUsername='".$username."',cPassword='".$password."',cEMailAddress='".$email."',cAdmin='".$admin."',nUserEdited=".$_SESSION["cms_userid"].",dEdited=Now() WHERE nUserId=".$userid;
			} else {
				$sql = "UPDATE ".$db_tableprefix."Users SET cUsername='".$username."',cEMailAddress='".$email."',cAdmin='".$admin."',nUserEdited=".$_SESSION["cms_userid"].",dEdited=Now() WHERE nUserId=".$userid;
			}

			$result = mysql_query($sql,$con);
			if ($result!=false){
				// redirect
				header("Location: admin.php?action=user_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			header("Location: admin.php?action=user_list");
		}
	}


	if ($_GET["action"]=="user_del"){

		$userid = mysql_escape_string($_GET["userid"]);

		if ($userid!=""){
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."Users WHERE nUserId=".$userid;

			$result = mysql_query($sql,$con);
			if ($result!=false){
				// redirect
				header("Location: admin.php?action=user_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Missing Form Data</li>\n";
		}
	}


	if ($_GET["action"]=="user_logout"){

		// remove the session variables
		$_SESSION["cms_userid"]="";
		$_SESSION["username"]="";
		$_SESSION["admin"]="";

		unset($_SESSION["cms_userid"]);
		unset($_SESSION["username"]);
		unset($_SESSION["admin"]);

		// redirect
		if ($_REQUEST["dest"]=="index.php"){
			header("Location: index.php");
		} else {
			header("Location: admin.php");
		}
	}


	if ($_GET["action"]=="user_joinusertype") {
		$userid = $_POST["userid"];
		$usertypeid = $_POST["usertypeid"];

		$con = db_connect();
		$sql = "INSERT INTO ".$db_tableprefix."UserTypeMember (nUserId,nUserTypeId) VALUES (".$userid.",".$usertypeid.");";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			header("Location: admin.php?action=user_edit&userid=".$_POST["userid"]);
		} else {
			header("Location: admin.php?action=problem");
		}
	}


	if ($_GET["action"]=="upload_delete") {
		unlink("uploads/".$_GET["filename"]);
		header("Location: admin.php?action=upload_list");
	}


	if ($_GET["action"]=="contentsecurity_edit"){

		if ($_POST["contenttypeid"]!="") {
			// get the existing security rows and update them
			$con = db_connect();
			$sql = "SELECT * FROM ".$db_tableprefix."ContentSecurity WHERE nContentTypeId=".$_POST["contenttypeid"];
			//print "<li>".$sql;
			$result = mysql_query($sql,$con);
			//print "<li>".mysql_num_rows($result);
			if ($result!=false){
				while ($row = mysql_fetch_array($result)){

					if ($_POST["chkbox_remove_".$row["nContentSecurityId"]]!="") {

						$sql = "DELETE FROM ".$db_tableprefix."ContentSecurity WHERE nContentSecurityId=".$row["nContentSecurityId"];

					} else {

						$sql = "UPDATE ".$db_tableprefix."ContentSecurity SET"
							." cView='".$_POST["chkbox_view_".$row["nContentSecurityId"]]."'"
							.",cAdd='".$_POST["chkbox_add_".$row["nContentSecurityId"]]."'"
							.",cEdit='".$_POST["chkbox_edit_".$row["nContentSecurityId"]]."'"
							.",cDelete='".$_POST["chkbox_delete_".$row["nContentSecurityId"]]."'"
							.",cApprove='".$_POST["chkbox_approve_".$row["nContentSecurityId"]]."'"
							." WHERE nContentSecurityId=".$row["nContentSecurityId"];
					}
					$change_result = mysql_query($sql,$con);
					//print "<li>".$sql;
				}

			} else {
				header("Location: admin.php?action=problem");
			}

			// add new row if required
			if ($_POST["usertype_new"]!=""){
				$sql = "INSERT INTO ".$db_tableprefix."ContentSecurity"
					."(nContentTypeId,nUserTypeId,cView,cAdd,cEdit,cDelete,cApprove)"
					." VALUES "
					."(".$_POST["contenttypeid"].",".$_POST["usertype_new"].",'".$_POST["chkbox_view_new"]."','".$_POST["chkbox_add_new"]."','".$_POST["chkbox_edit_new"]."','".$_POST["chkbox_delete_new"]."','".$_POST["chkbox_approve_new"]."')";
				$result = mysql_query($sql,$con);
				if ($result==false){
					header("Location: admin.php?action=problem");
				}
			}
		}

		header ("Location: admin.php?action=contenttype_edit&contenttypeid=".$_POST["contenttypeid"]);
	}



	if ($_GET["action"]=="file_upload"){

		ini_set("memory_limit","10M");
		ini_set("post_max_size","9M");
		ini_set("upload_max_filesize","8M");
		

		$uploaddir = $_GET["destination"];
		$uploadfile = $uploaddir."/".$_FILES['userfile']['name'];

		if (is_uploaded_file($_FILES['userfile']['tmp_name'])){
			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
				// successful upload
				chmod($uploadfile, 0755);
				// sorted
				header("Location: admin.php?action=filebrowse&path=".$_GET["destination"]);
			} else {
				// move failed
				header("Location: admin.php?action=problem");
			}
		} else {
			// upload failed
			header("Location: admin.php?action=problem");
		}
	}

	if ($_GET["action"]=="filebrowse_delete_file"){
		$filename = $_GET["file"];
		if ($filename!=""){
			// get path from filename
			unlink($filename);
			header("Location: admin.php?action=filebrowse&path=".$_GET["path"]);
		} else {
			header("Location: admin.php?action=problem");
		}
	}

	if ($_GET["action"]=="filebrowse_createdir"){
		if ($_POST["directory"]!=""){
			// check the directory name is valid
			$oldumask = umask(0);
			mkdir($_POST["path"]."/".$_POST["directory"]);
			umask($oldumask);
			chmod($_POST["path"]."/".$_POST["directory"],0777);
			header("Location: admin.php?action=filebrowse&path=".$_POST["path"]."/".$_POST["directory"]);
		} else {
			header("Location: admin.php?action=filebrowse&path=".$_POST["path"]);
		}
	}

	if ($_GET["action"]=="filebrowse_delete_directory"){
		$directory = $_GET["directory"];
		if ($directory!="") {
			$result =@ rmdir($directory);
			if ($result){
				header("Location: admin.php?action=filebrowse&path=".$_GET["path"]);
			} else {
				header("Location: admin.php?action=filebrowse&path=".$_GET["path"]);
			}
		} else {
			header("Location: admin.php?action=problem");
		}
	}

	if ($_GET["action"]=="doctype_add"){

		$name = mysql_escape_string($_POST["name"]);
		$description = mysql_escape_string($_POST["description"]);
		$repositoryid = mysql_escape_string($_POST["repositoryid"]);
		
		$sql = "INSERT INTO ".$db_tableprefix."DocumentType (cName,cDescription,nRepositoryId) VALUES ('".$name."','".$description."',".$repositoryid.")";

		$con = db_connect();

		$result = mysql_query($sql,$con);

		if ($result!=false){
			header ("Location: admin.php?action=doctype_list");
		} else {
			// problem with the insert
			header("Location: admin.php?action=problem");
		}

	}

	if ($_GET["action"]=="doctype_edit"){

		$doctypeid = mysql_escape_string($_POST["doctypeid"]);
		$name = mysql_escape_string($_POST["name"]);
		$description = mysql_escape_string($_POST["description"]);
		$repositoryid = mysql_escape_string($_POST["repositoryid"]);
		
		$sql = "UPDATE ".$db_tableprefix."DocumentType SET cName='".$name."',cDescription='".$description."',nRepositoryId=".$repositoryid." WHERE nDocumentTypeId=".$doctypeid;

		$con = db_connect();

		$result = mysql_query($sql,$con);

		if ($result!=false){
			header ("Location: admin.php?action=doctype_list");
		} else {
			// problem with the insert
			header("Location: admin.php?action=problem");
		}

	}
	
	if ($_GET["action"]=="doctype_del"){

		$doctypeid=$_GET["doctypeid"];

		$sql = "DELETE FROM ".$db_tableprefix."DocumentType WHERE nDocumentTypeId=".$doctypeid;

		$con = db_connect();

		$result = mysql_query($sql,$con);

		if ($result!=false){
			header ("Location: admin.php?action=doctype_list");
		} else {
			// problem with the insert
			header("Location: admin.php?action=problem");
		}

	}


	if ($_GET["action"]=="docsecurity_edit"){

		if ($_POST["doctypeid"]!="") {
			// get the existing security rows and update them
			$con = db_connect();
			$sql = "SELECT * FROM ".$db_tableprefix."DocumentSecurity WHERE nDocumentTypeId=".$_POST["doctypeid"];
			
			$result = mysql_query($sql,$con);
			
			if ($result!=false){
				while ($row = mysql_fetch_array($result)){

					if ($_POST["chkbox_remove_".$row["nDocumentSecurityId"]]!="") {

						$sql = "DELETE FROM ".$db_tableprefix."DocumentSecurity WHERE nDocumentSecurityId=".$row["nDocumentSecurityId"];

					} else {

						$sql = "UPDATE ".$db_tableprefix."DocumentSecurity SET"
							." cView='".$_POST["chkbox_view_".$row["nDocumentSecurityId"]]."'"
							.",cAdd='".$_POST["chkbox_add_".$row["nDocumentSecurityId"]]."'"
							.",cEdit='".$_POST["chkbox_edit_".$row["nDocumentSecurityId"]]."'"
							.",cDelete='".$_POST["chkbox_delete_".$row["nDocumentSecurityId"]]."'"
							.",cReplace='".$_POST["chkbox_replace_".$row["nDocumentSecurityId"]]."'"
							." WHERE nDocumentSecurityId=".$row["nDocumentSecurityId"];
					}
					$change_result = mysql_query($sql,$con);
					if ($change_result==false){
						header("Location: admin.php?action=problem");
					}
				}

			} else {
				header("Location: admin.php?action=problem");
			}

			// add new row if required
			if ($_POST["usertype_new"]!=""){
				$sql = "INSERT INTO ".$db_tableprefix."DocumentSecurity"
					."(nDocumentTypeId,nUserTypeId,cView,cAdd,cEdit,cDelete,cReplace)"
					." VALUES "
					."(".$_POST["doctypeid"].",".$_POST["usertype_new"].",'".$_POST["chkbox_view_new"]."','".$_POST["chkbox_add_new"]."','".$_POST["chkbox_edit_new"]."','".$_POST["chkbox_delete_new"]."','".$_POST["chkbox_replace_new"]."')";
				
				$result = mysql_query($sql,$con);
				if ($result==false){
					header("Location: admin.php?action=problem");
				}
			}
		}

		header ("Location: admin.php?action=doctype_edit&doctypeid=".$_POST["doctypeid"]);
	}



	if ($_GET["action"]=="doctype_prop_edit"){

		$doctypeid = $_POST["doctypeid"];

		$con = db_connect();

		$result = true;

		// figure out if a field needs to be added
		if ($_POST["new_name"]!=""){

			$name = mysql_escape_string($_POST["new_name"]);
			$description = mysql_escape_string($_POST["new_description"]);
			$datatype = mysql_escape_string($_POST["new_datatype"]);
			$inputmask = mysql_escape_string($_POST["new_inputmask"]);
			if ($_POST["new_mandatory"]!=""){
				$mandatory = 1;
			} else {
				$mandatory = 0;
			}
			if ($_POST["new_hidden"]!=""){
				$hidden = 1;
			} else {
				$hidden = 0;
			}
			if ($_POST["new_unique"]!=""){
				$unique = 1;
			} else {
				$unique = 0;
			}

			$sql = "INSERT INTO ".$db_tableprefix."DocumentTypeProperties (nDocumentTypeId,cPropertyName,cPropertyDescription,cDataType,cInputMask,bMandatory,bHidden,bUnique)"
				." VALUES ("
				.$doctypeid.","
				."'".$name."',"
				."'".$description."',"
				."'".$datatype."',"
				."'".$inputmask."',"
				.$mandatory.",".$hidden.",".$unique
				.")";

			$result = mysql_query($sql,$con);
			if ($result!=false){
				//header ("Location: dms_admin.php?action=doctype_edit&doctypeid=".$doctypeid);
			} else {
				header("Location: admin.php?action=problem");
			}

		}

		// loop through the existing rows and make changes as appropriate
		if ($result!=false) {
			$num_rows = $_POST["numrows"];
			for ($i=1;$i<=$num_rows;$i++){

				// only bother if the row is not marked for deletion
				if ($_POST["row".$i."_del"]!="1"){

					// prepare fields for update
					$docpropid = $_POST["row".$i."_docpropid"];
					$name = mysql_escape_string($_POST["row".$i."_name"]);
					$description = mysql_escape_string($_POST["row".$i."_description"]);
					$datatype = mysql_escape_string($_POST["row".$i."_datatype"]);
					$inputmask = mysql_escape_string($_POST["row".$i."_inputmask"]);

					if ($_POST["row".$i."_mandatory"]!=""){
						$mandatory = 1;
					} else {
						$mandatory = 0;
					}
					if ($_POST["row".$i."_hidden"]!=""){
						$hidden = 1;
					} else {
						$hidden = 0;
					}
					if ($_POST["row".$i."_unique"]!=""){
						$unique = 1;
					} else {
						$unique = 0;
					}

					$sql = "UPDATE ".$db_tableprefix."DocumentTypeProperties SET"
						." cPropertyName='".$name."'"
						.",cPropertyDescription='".$description."'"
						.",cDataType='".$datatype."'"
						.",cInputMask='".$inputmask."'"
						.",bMandatory=".$mandatory
						.",bHidden=".$hidden
						.",bUnique=".$unique
						." WHERE nDocumentTypePropertyId=".$docpropid;
					//print "<li>".$sql;
					$result = mysql_query($sql,$con);
					
					if ($result==false){
						header("Location: admin.php?action=problem");
					}
				} else {
					// do the delete instead
					$docpropid = $_POST["row".$i."_docpropid"];
					$sql = "DELETE FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypePropertyId=".$docpropid;
					
					$result = mysql_query($sql,$con);
					if ($result==false){
						header("Location: admin.php?action=problem");
					}
				}
			}
		}

		if ($result!=false){
			header ("Location: admin.php?action=doctype_edit&doctypeid=".$doctypeid);
		} else {
			header("Location: admin.php?action=problem");
		}

	}

	if ($_GET["action"]=="contenttype_prop_edit"){

		$contenttypeid = $_POST["contenttypeid"];

		$con = db_connect();

		$result = true;

		// figure out if a field needs to be added
		if ($_POST["new_name"]!=""){

			$name = mysql_escape_string($_POST["new_name"]);
			$description = mysql_escape_string($_POST["new_description"]);
			$datatype = mysql_escape_string($_POST["new_datatype"]);
			$inputmask = mysql_escape_string($_POST["new_inputmask"]);
			if ($_POST["new_mandatory"]!=""){
				$mandatory = 1;
			} else {
				$mandatory = 0;
			}
			if ($_POST["new_hidden"]!=""){
				$hidden = 1;
			} else {
				$hidden = 0;
			}
			if ($_POST["new_unique"]!=""){
				$unique = 1;
			} else {
				$unique = 0;
			}

			$sql = "INSERT INTO ".$db_tableprefix."ContentTypeProperties (nContentTypeId,cPropertyName,cPropertyDescription,cDataType,cInputMask,bMandatory,bHidden,bUnique)"
				." VALUES ("
				.$contenttypeid.","
				."'".$name."',"
				."'".$description."',"
				."'".$datatype."',"
				."'".$inputmask."',"
				.$mandatory.",".$hidden.",".$unique
				.")";

			$result = mysql_query($sql,$con);
			if ($result!=false){
				//header ("Location: dms_admin.php?action=doctype_edit&contenttypeid=".$contenttypeid);
			} else {
				header("Location: admin.php?action=problem");
			}

		}

		// loop through the existing rows and make changes as appropriate
		if ($result!=false) {
			$num_rows = $_POST["numrows"];
			for ($i=1;$i<=$num_rows;$i++){

				// only bother if the row is not marked for deletion
				if ($_POST["row".$i."_del"]!="1"){

					// prepare fields for update
					$contentpropid = $_POST["row".$i."_contentpropid"];
					$name = mysql_escape_string($_POST["row".$i."_name"]);
					$description = mysql_escape_string($_POST["row".$i."_description"]);
					$datatype = mysql_escape_string($_POST["row".$i."_datatype"]);
					$inputmask = mysql_escape_string($_POST["row".$i."_inputmask"]);

					if ($_POST["row".$i."_mandatory"]!=""){
						$mandatory = 1;
					} else {
						$mandatory = 0;
					}
					if ($_POST["row".$i."_hidden"]!=""){
						$hidden = 1;
					} else {
						$hidden = 0;
					}
					if ($_POST["row".$i."_unique"]!=""){
						$unique = 1;
					} else {
						$unique = 0;
					}

					$sql = "UPDATE ".$db_tableprefix."ContentTypeProperties SET"
						." cPropertyName='".$name."'"
						.",cPropertyDescription='".$description."'"
						.",cDataType='".$datatype."'"
						.",cInputMask='".$inputmask."'"
						.",bMandatory=".$mandatory
						.",bHidden=".$hidden
						.",bUnique=".$unique
						." WHERE nContentTypePropertyId=".$contentpropid;
					
					$result = mysql_query($sql,$con);

				} else {
					// do the delete instead
					$contentpropid = $_POST["row".$i."_contentpropid"];
					$sql = "DELETE FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypePropertyId=".$contentpropid;
					
					$result = mysql_query($sql,$con);
				}
			}
		}
		if ($result!=false){
			header ("Location: admin.php?action=contenttype_edit&contenttypeid=".$contenttypeid);
		} else {
			header("Location: admin.php?action=problem");
		}

	}

	// Cache the Page
	if ($_GET["action"]=="page_cache"){
		if ($_GET["pagekey"]!=""){

			// generate the page
			$html = html_create_page($_GET["pagekey"],"T");

			// save the page into the cache
			$filename = $site_root."/cache/".$_GET["pagekey"].".htm";
			$handle = fopen($filename, 'w');
			fwrite($handle,$html);
			fclose($handle);

			// change the permission of the file
			$oldmask = umask(0);
			chmod($filename, 0777);
			umask($oldmask);
		}
		header("Location: admin.php?action=page_list");
	}

	if ($_GET["action"]=="page_cache_clear"){
		if ($_GET["pagekey"]!=""){
			$filename = $site_root."/cache/".$_GET["pagekey"].".htm";
			unlink($filename);
		}
		header("Location: admin.php?action=page_list");
	}

	if ($_GET["action"]=="pagecontent_up"){
		if ($_GET["pagecontentid"]!=""){
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."PageContent SET nIndex=nIndex-1 WHERE nPageContentId=".$_GET["pagecontentid"];
			$result = mysql_query($sql,$con);
			if ($result!=false){
				header("Location: admin.php?action=page_edit&pageid=".$_GET["pageid"]);
			} else {
				header("Location: admin.php?action=problem");
			}
		}
	}

	if ($_GET["action"]=="pagecontent_down"){
		if ($_GET["pagecontentid"]!=""){
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."PageContent SET nIndex=nIndex+1 WHERE nPageContentId=".$_GET["pagecontentid"];
			$result = mysql_query($sql,$con);
			if ($result!=false){
				header("Location: admin.php?action=page_edit&pageid=".$_GET["pageid"]);
			} else {
				header("Location: admin.php?action=problem");
			}
		}
	}


	if ($_GET["action"]=="pagetype_add"){
		// make sure we have the correct info
		$name = mysql_escape_string($_POST["name"]);
		
		if ($name!="") {
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."PageType (cPageTypeName) VALUES ('".$name."')";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=pagetype_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="pagetype_edit"){
		// make sure we have the correct info
		$pagetypeid=$_POST["pagetypeid"];
		$name = mysql_escape_string($_POST["name"]);
		$function = mysql_escape_string($_POST["function"]);
		$file = mysql_escape_string($_POST["file"]);
		$approval_required = mysql_escape_string($_POST["approval_required"]);
		if ($pagetypeid!="") {
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."PageType SET cPageTypeName='".$name."' WHERE nPageTypeId=".$pagetypeid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=pagetype_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="pagetype_del"){

		$pagetypeid = $_GET["pagetypeid"];

		if ($pagetypeid!="") {
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."PageType WHERE nPageTypeId=".$pagetypeid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=pagetype_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
		
	}

	if ($_GET["action"]=="pagesecurity_edit"){

		if ($_POST["pagetypeid"]!="") {
			// get the existing security rows and update them
			$con = db_connect();
			$sql = "SELECT * FROM ".$db_tableprefix."PageSecurity WHERE nPageTypeId=".$_POST["pagetypeid"];
			//print "<li>".$sql;
			$result = mysql_query($sql,$con);
			//print "<li>".mysql_num_rows($result);
			if ($result!=false){
				while ($row = mysql_fetch_array($result)){

					if ($_POST["chkbox_remove_".$row["nPageSecurityId"]]!="") {

						$sql = "DELETE FROM ".$db_tableprefix."PageSecurity WHERE nPageSecurityId=".$row["nPageSecurityId"];

					} else {

						$sql = "UPDATE ".$db_tableprefix."PageSecurity SET"
							." cView='".$_POST["chkbox_view_".$row["nPageSecurityId"]]."'"
							.",cAdd='".$_POST["chkbox_add_".$row["nPageSecurityId"]]."'"
							.",cEdit='".$_POST["chkbox_edit_".$row["nPageSecurityId"]]."'"
							.",cDelete='".$_POST["chkbox_delete_".$row["nPageSecurityId"]]."'"
							.",cApprove='".$_POST["chkbox_approve_".$row["nPageSecurityId"]]."'"
							." WHERE nPageSecurityId=".$row["nPageSecurityId"];
					}
					$change_result = mysql_query($sql,$con);
					if ($change_result==false){
						header("Location: admin.php?action=problem");
					}
				}

			} else {
				print "<li>Problem with SQL [".$sql."]</li>\n";
			}

			// add new row if required
			if ($_POST["usertype_new"]!=""){
				$sql = "INSERT INTO ".$db_tableprefix."PageSecurity"
					."(nPageTypeId,nUserTypeId,cView,cAdd,cEdit,cDelete,cApprove)"
					." VALUES "
					."(".$_POST["pagetypeid"].",".$_POST["usertype_new"].",'".$_POST["chkbox_view_new"]."','".$_POST["chkbox_add_new"]."','".$_POST["chkbox_edit_new"]."','".$_POST["chkbox_delete_new"]."','".$_POST["chkbox_approve_new"]."')";
				$result = mysql_query($sql,$con);
				if ($result==false){
					header("Location: admin.php?action=problem");
				}
			}
		}

		header ("Location: admin.php?action=pagetype_edit&pagetypeid=".$_POST["pagetypeid"]);
	}


	if ($_GET["action"]=="pagetype_prop_edit"){

		$pagetypeid = $_POST["pagetypeid"];

		$con = db_connect();

		$result = true;

		// figure out if a field needs to be added
		if ($_POST["new_name"]!=""){

			$name = mysql_escape_string($_POST["new_name"]);
			$description = mysql_escape_string($_POST["new_description"]);
			$datatype = mysql_escape_string($_POST["new_datatype"]);
			$inputmask = mysql_escape_string($_POST["new_inputmask"]);
			if ($_POST["new_mandatory"]!=""){
				$mandatory = 1;
			} else {
				$mandatory = 0;
			}
			if ($_POST["new_hidden"]!=""){
				$hidden = 1;
			} else {
				$hidden = 0;
			}
			if ($_POST["new_unique"]!=""){
				$unique = 1;
			} else {
				$unique = 0;
			}

			$sql = "INSERT INTO ".$db_tableprefix."PageTypeProperties (nPageTypeId,cPropertyName,cPropertyDescription,cDataType,cInputMask,bMandatory,bHidden,bUnique)"
				." VALUES ("
				.$pagetypeid.","
				."'".$name."',"
				."'".$description."',"
				."'".$datatype."',"
				."'".$inputmask."',"
				.$mandatory.",".$hidden.",".$unique
				.")";

			$result = mysql_query($sql,$con);
			if ($result!=false){
				//header ("Location: dms_admin.php?action=pagetype_edit&pagetypeid=".$pagetypeid);
			} else {
				header("Location: admin.php?action=problem");
			}

		}

		// loop through the existing rows and make changes as appropriate
		if ($result!=false) {
			$num_rows = $_POST["numrows"];
			for ($i=1;$i<=$num_rows;$i++){

				// only bother if the row is not marked for deletion
				if ($_POST["row".$i."_del"]!="1"){

					// prepare fields for update
					$pagepropid = $_POST["row".$i."_pagepropid"];
					$name = mysql_escape_string($_POST["row".$i."_name"]);
					$description = mysql_escape_string($_POST["row".$i."_description"]);
					$datatype = mysql_escape_string($_POST["row".$i."_datatype"]);
					$inputmask = mysql_escape_string($_POST["row".$i."_inputmask"]);

					if ($_POST["row".$i."_mandatory"]!=""){
						$mandatory = 1;
					} else {
						$mandatory = 0;
					}
					if ($_POST["row".$i."_hidden"]!=""){
						$hidden = 1;
					} else {
						$hidden = 0;
					}
					if ($_POST["row".$i."_unique"]!=""){
						$unique = 1;
					} else {
						$unique = 0;
					}

					$sql = "UPDATE ".$db_tableprefix."PageTypeProperties SET"
						." cPropertyName='".$name."'"
						.",cPropertyDescription='".$description."'"
						.",cDataType='".$datatype."'"
						.",cInputMask='".$inputmask."'"
						.",bMandatory=".$mandatory
						.",bHidden=".$hidden
						.",bUnique=".$unique
						." WHERE nPageTypePropertyId=".$pagepropid;
					
					$result = mysql_query($sql,$con);
					if ($result==false){
						header("Location: admin.php?action=problem");
					}
					
				} else {
					// do the delete instead
					$pagepropid = $_POST["row".$i."_pagepropid"];
					$sql = "DELETE FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypePropertyId=".$pagepropid;
					
					$result = mysql_query($sql,$con);
					if ($result==false){
						header("Location: admin.php?action=problem");
					}
				}
			}
		}
		if ($result!=false){
			header ("Location: admin.php?action=pagetype_edit&pagetypeid=".$pagetypeid);
		} else {
			header("Location: admin.php?action=problem");
		}

	}

	if ($_GET["action"]=="template_del"){

		$templateid = $_GET["templateid"];

		if ($templateid!="") {
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."Templates WHERE nTemplateId=".$templateid;
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=template_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}
	
	if ($_GET["action"]=="document_add"){
		// get data and write it into the document and documentdata tables
		$doctypeid = $_POST["doctypeid"];
		
		// basic file
		$filename = $pickup_path."/".$_FILES['userfile']['name'];
		
		// handle the uploaded file (make sure it is in the pickup directory)
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $filename)) {
		
			$con = db_connect();
			
			// the document is now in the pickup directory - full path = $filename
			$filesize = filesize($filename);
			
			// store the document record
			$sql = "INSERT INTO ".$db_tableprefix."Document ("
				."nDocumentTypeId,nVersion,dAdded,dEdited,nAddedBy,nEditedBy,cFilename,cOriginalFilename,nFilesize"
				.") VALUES ("
				.$doctypeid
				.",1"
				.",now()"
				.",now()"
				.",".$_SESSION["cms_userid"]
				.",".$_SESSION["cms_userid"]
				.",''"
				.",'".$_FILES['userfile']['name']."'"
				.",".$filesize
				.")";

			$result = mysql_query($sql,$con);
			
			if ($result!=false){
				
				// successfully stored document record
				$documentid = mysql_insert_id();
				
				// store the properties
				$numprops = $_POST["numrows"];
				for ($i=1;$i<=$numprops;$i++){
					$propid = $_POST["prop".$i."_propid"];
					$datatype = $_POST["prop".$i."_datatype"];
					$value = $_POST["prop".$i."_value"];

					// modify value according to datatype
					switch ($datatype){
						case "dDataDate":
							$value = "'".$value."'";
							break;
						case "cDataVarchar":
							$value = "'".$value."'";
							break;
						case "cDataMediumText":
							$value = "'".$value."'";
							break;
						case "bDataBlob":
							$value = "'".$value."'";
							break;
					}

					$sql = "INSERT INTO ".$db_tableprefix."DocumentData (nDocumentId,nPropertyId,".$datatype.")"
						." VALUES (".$documentid.",".$propid.",".$value.")";
					$result = mysql_query($sql,$con);
					if ($result!=false){
						// successfully set property
						
					} else {
						// failed to set property (bad sql)
						header("Location: admin.php?action=problem");
						$result = -2;
						break;
					}
				}

				// then store the document file in the repository
				// (remembering that the store command updates the document record accordingly)
				// n.b. the store command is in the dms.php file
				if ($result>=0){
				
					// first - find out the repositoryid for the doctype
					$sql = "SELECT nRepositoryId FROM ".$db_tableprefix."DocumentType WHERE nDocumentTypeId=".$doctypeid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							$row = mysql_fetch_array($result);
							$repositoryid = $row["nRepositoryId"];
							$result = store_file($documentid,$filename,$repositoryid);
							
							// redirect
							header("Location: admin.php");
							
						} else {
							// could find no repository
							print "<li>No Repository</li>\n";
						}
					} else {
						// sql failed looking for repository
						header("Location: admin.php?action=problem");
					}
				}
			
			} else {
				// failed to store document record (bad sql)
				header("Location: admin.php?action=problem");
			}
			
		} else {
			// upload failed
			print "<li>Upload Failed</li>\n";
		}
	}

	if ($_GET["action"]=="repository_add"){
		// make sure we have the correct info
		$path = mysql_escape_string($_POST["path"]);
		if ($path!="") {
			$con = db_connect();
			$sql = "INSERT INTO ".$db_tableprefix."Repository (cPath) VALUES ('".$path."')";
			$result = mysql_query($sql,$con);
			if ($result!=false) {
				// redirect
				header("Location: admin.php?action=repository_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Required data from the form was missing.</li>\n";
		}
	}

	if ($_GET["action"]=="repository_edit"){

		$repositoryid = mysql_escape_string($_POST["repositoryid"]);
		$path = mysql_escape_string($_POST["path"]);
		
		if ($repositoryid!="" && $path!=""){
			$con = db_connect();
			$sql = "UPDATE ".$db_tableprefix."Repository SET cPath='".$path."' WHERE nRepositoryId=".$repositoryid;

			$result = mysql_query($sql,$con);
			if ($result!=false){
				// redirect
				header("Location: admin.php?action=repository_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Missing Form Data</li>\n";
		}
	}

	if ($_GET["action"]=="repository_del"){

		$userid = mysql_escape_string($_GET["repositoryid"]);

		if ($repositoryid!=""){
			
			$con = db_connect();
			$sql = "DELETE FROM ".$db_tableprefix."Repository WHERE nRepositoryId=".$repositoryid;

			$result = mysql_query($sql,$con);
			if ($result!=false){
				// redirect
				header("Location: admin.php?action=repository_list");
			} else {
				// report error
				header("Location: admin.php?action=problem");
			}
		} else {
			print "<li>Missing Form Data</li>\n";
		}
	}
	
	

} else {

	if ($_GET["action"]=="admin_set_password"){
		$password1 = mysql_escape_string($_POST["password1"]);
		$password2 = mysql_escape_string($_POST["password2"]);
		if ($password1!="" && $password2!=""){
			if ($password1==$password2){
				
				// find out if the admin account already exists
				$con = db_connect();
				$sql = "SELECT * FROM ".$db_tableprefix."Users WHERE cUsername='admin'";
				$result = mysql_query($sql,$con);
				if ($result!=false){
					if (mysql_num_rows($result)){
						// found an admin record - update it
						$password = mysql_escape_string(crypt($password1,$crypt_salt));
						$sql = "UPDATE ".$db_tableprefix."Users SET cPassword='".$password."' WHERE cUsername='admin'";
						$result = mysql_query($sql,$con);
						if ($result!=false){
							$url = "admin.php";
						} else {
							$url = "admin.php?action=problem";
						}
					} else {
						$url = "admin.php?action=problem";						
					}
				} else {
					$url = "admin.php?action=problem";
				}
				
				$url = "admin.php";
			} else {
				$url = "admin.php";
			}
		} else {
			$url = "admin.php";
		}
		header("Location: ".$url);
	}



	// if we are processing a login, do it

	if ($_GET["action"]=="user_login"){

		if ($_POST["username"]!=""){

			$username = mysql_escape_string($_POST["username"]);
			$password = mysql_escape_string(crypt($_POST["password"],$crypt_salt));
			//$password = mysql_escape_string($_POST["password"]);

			$con = db_connect();
			$sql = "SELECT * FROM ".$db_tableprefix."Users WHERE cUsername='".$username."' AND cPassword='".$password."'";
			$result = mysql_query($sql,$con);
			if ($result!=false){
				if (mysql_num_rows($result)>0){

					$row = @ mysql_fetch_array($result);

					// set the session variables
					$_SESSION["cms_userid"]=$row["nUserId"];
					$_SESSION["cms_username"]=$row["cUsername"];
					$_SESSION["cms_admin"]=$row["cAdmin"];

					// record the login
					$sql = "UPDATE ".$db_tableprefix."Users SET dLastLogon=Now() WHERE nUserId=".$_SESSION["cms_userid"];
					$result = mysql_query($sql,$con);

					if ($_REQUEST["dest"]=="index.php"){
						header("Location: index.php");
					} else {
						header("Location: admin.php");
					}

				} else {
					if ($_REQUEST["dest"]=="index.php"){
						header("Location: index.php");
					} else {
						header("Location: admin.php");
					}
				}
			} else {
				header("Location: admin.php?action=problem");
			}
		} else {
			if ($_REQUEST["dest"]=="index.php"){
				header("Location: index.php");
			} else {
				header("Location: admin.php");
			}
		}
	}

}

?>