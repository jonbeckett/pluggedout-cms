<?php

// security functions
function get_user_content_permissions($userid,$contentid){
	
	global $db_tableprefix;
	
	$con = db_connect();
	$sql = "SELECT DISTINCT con.nContentId,cs.cView,cs.cAdd,cs.cEdit,cs.cDelete,cs.cApprove"
		." FROM ".$db_tableprefix."Content con"
		." INNER JOIN ".$db_tableprefix."ContentType ct ON con.nContentTypeId=ct.nContentTypeId"
		." INNER JOIN ".$db_tableprefix."ContentSecurity cs ON ct.nContentTypeId=cs.nContentTypeId"
		." INNER JOIN ".$db_tableprefix."UserTypeMember um ON cs.nUserTypeId=um.nUserTypeId"
		." WHERE um.nUserId=".$userid." AND con.nContentId=".$contentid;
	
	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
			while ($row =@ mysql_fetch_array($result)){
				if ($row["cView"]!=""){
					$perm["view"]="x";
				}
				if ($row["cAdd"]!=""){
					$perm["add"]="x";
				}
				if ($row["cEdit"]!=""){
					$perm["edit"]="x";
				}
				if ($row["cDelete"]!=""){
					$perm["delete"]="x";
				}
				if ($row["cApprove"]!=""){
					$perm["approve"]="x";
				}
			}
			$result = $perm;
		} else {
			$result = "";
		}
	} else {
		$result = "";
	}
	return $result;
}



function get_user_page_permissions($userid,$pageid){
	
	global $db_tableprefix;
	
	$con = db_connect();
	$sql = "SELECT DISTINCT pag.nPageId,ps.cView,ps.cAdd,ps.cEdit,ps.cDelete,ps.cApprove"
		." FROM ".$db_tableprefix."Pages pag"
		." INNER JOIN ".$db_tableprefix."PageType pt ON pag.nPageTypeId=pt.nPageTypeId"
		." INNER JOIN ".$db_tableprefix."PageSecurity ps ON pt.nPageTypeId=ps.nPageTypeId"
		." INNER JOIN ".$db_tableprefix."UserTypeMember um ON ps.nUserTypeId=um.nUserTypeId"
		." WHERE um.nUserId=".$userid." AND pag.nPageId=".$pageid;
	
	$result = mysql_query($sql,$con);
	if ($result!=false){
		if (mysql_num_rows($result)>0){
			while ($row =@ mysql_fetch_array($result)){
				if ($row["cView"]!=""){
					$perm["view"]="x";
				}
				if ($row["cAdd"]!=""){
					$perm["add"]="x";
				}
				if ($row["cEdit"]!=""){
					$perm["edit"]="x";
				}
				if ($row["cDelete"]!=""){
					$perm["delete"]="x";
				}
				if ($row["cApprove"]!=""){
					$perm["approve"]="x";
				}
			}
			$result = $perm;
		} else {
			$result = "";
		}
	} else {
		$result = "";
		print $sql;
	}
	return $result;
}


function can_user_add_content(){
	
	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT ct.nContentTypeId"
			." FROM ".$db_tableprefix."ContentType ct"
			." INNER JOIN ".$db_tableprefix."ContentSecurity cs ON ct.nContentTypeId=cs.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON cs.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND cs.cAdd='x'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$result = "1";
			} else {
				$result = "";
			}
		} else {
			$result = "";
		}
		
	} else {
		$result = "";
	}
	
	return $result;
}

function can_user_add_pages(){
	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT pt.nPageTypeId"
			." FROM ".$db_tableprefix."PageType pt"
			." INNER JOIN ".$db_tableprefix."PageSecurity ps ON pt.nPageTypeId=ps.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON ps.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND ps.cAdd='x'";
			
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$result = "1";
			} else {
				$result = "";
			}
		} else {
			$result = "";
		}
		
	} else {
		$result = "";
	}
	return $result;
}

function get_user_add_contenttypes(){

	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT ct.nContentTypeId"
			." FROM ".$db_tableprefix."ContentType ct"
			." INNER JOIN ".$db_tableprefix."ContentSecurity cs ON ct.nContentTypeId=cs.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON cs.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND cs.cAdd='x'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				while($row =@ mysql_fetch_array($result)){
					$a_result[]=$row["nContentTypeId"];
				}
			}
		} else {
			$result = "";
		}
		
		$result = "";
	} else {
		$result = "";
	}
	
	if (is_array($a_result)){
		return $a_result;
	} else {
		return $result;
	}
}

function get_user_add_pagetypes(){
	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT pt.nPageTypeId"
			." FROM ".$db_tableprefix."PageType pt"
			." INNER JOIN ".$db_tableprefix."PageSecurity ps ON pt.nContentTypeId=ps.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON ps.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND ps.cAdd='x'";
			
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				while($row =@ mysql_fetch_array($result)){
					$a_result[]=$row["nContentTypeId"];
				}
			}
		} else {
			$result = "";
		}
		
	} else {
		$result = "";
	}
	
	if (is_array($a_result)){
		return $a_result;
	} else {
		return $result;
	}
	print $sql;
	return $result;
}


function can_user_edit_content(){
	
	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT ct.nContentTypeId"
			." FROM ".$db_tableprefix."ContentType ct"
			." INNER JOIN ".$db_tableprefix."ContentSecurity cs ON ct.nContentTypeId=cs.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON cs.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND cs.cEdit='x'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$result = "1";
			}
		} else {
			$result = "";
		}
		
	} else {
		$result = "";
	}
	
	return $result;
}

function can_user_edit_pages(){
	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT pt.nPageTypeId"
			." FROM ".$db_tableprefix."PageType pt"
			." INNER JOIN ".$db_tableprefix."PageSecurity ps ON pt.nPageTypeId=ps.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON ps.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND ps.cEdit='x'";
			
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$result = "1";
			}
		} else {
			$result = "";
		}
		
	} else {
		$result = "";
	}
	return $result;
}

function get_user_edit_contenttypes(){

	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT ct.nContentTypeId"
			." FROM ".$db_tableprefix."ContentType ct"
			." INNER JOIN ".$db_tableprefix."ContentSecurity cs ON ct.nContentTypeId=cs.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON cs.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND cs.cEdit='x'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				while($row =@ mysql_fetch_array($result)){
					$a_result[]=$row["nContentTypeId"];
				}
			}
		} else {
			$result = "";
		}
		
		$result = "";
	} else {
		$result = "";
	}
	
	if (is_array($a_result)){
		return $a_result;
	} else {
		return $result;
	}
}

function get_user_edit_pagetypes(){
	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT pt.nPageTypeId"
			." FROM ".$db_tableprefix."PageType pt"
			." INNER JOIN ".$db_tableprefix."PageSecurity ps ON pt.nContentTypeId=ps.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON ps.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND ps.cEdit='x'";
			
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				while($row =@ mysql_fetch_array($result)){
					$a_result[]=$row["nContentTypeId"];
				}
			}
		} else {
			$result = "";
		}
		
	} else {
		$result = "";
	}
	
	if (is_array($a_result)){
		return $a_result;
	} else {
		return $result;
	}
	print $sql;
	return $result;
}

function can_user_edit_this_contenttype($contenttypeid){
	
	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!="" && $contenttypeid!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT ct.nContentTypeId"
			." FROM ".$db_tableprefix."ContentType ct"
			." INNER JOIN ".$db_tableprefix."ContentSecurity cs ON ct.nContentTypeId=cs.nContentTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON cs.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND cs.cEdit='x' AND ct.nContentTypeId=".$contenttypeid;
			
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$result = "1";
			} else {
				$result = "";
			}
		} else {
			$result = "";
		}
		
	} else {
		$result = "";
	}
	//print $sql;
	return $result;

}


function can_user_edit_this_pagetype($pagetypeid){
	
	global $db_tableprefix;
	
	if ($_SESSION["cms_userid"]!="" && $pagetypeid!=""){
		
		$con = db_connect();
		$sql = "SELECT DISTINCT pt.nPageTypeId"
			." FROM ".$db_tableprefix."PageType pt"
			." INNER JOIN ".$db_tableprefix."PageSecurity ps ON pt.nPageTypeId=ps.nPageTypeId"
			." INNER JOIN ".$db_tableprefix."UserTypeMember um ON ps.nUserTypeId=um.nUserTypeId"
			." WHERE um.nUserId=".$_SESSION["cms_userid"]." AND ps.cEdit='x' AND pt.nPageTypeId=".$pagetypeid;
			
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$result = "1";
			} else {
				$result = "";
			}
		} else {
			$result = "";
		}
		
	} else {
		$result = "";
	}
	//print $sql;
	return $result;

}
?>