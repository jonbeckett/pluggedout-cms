<?php

// CMS API
// (c) Jonathan Beckett
// version 0.4.9
// last change - 2005-05-07 10:08


// Include library functions
include "../../lib/config.php";
include "../../lib/database.php";
include "../../lib/dms.php";



// default the result
$result = "";

// connect to the database
$con = db_connect();

// page_get_id(key) = id
if ($_REQUEST["action"]=="page_get_id"){
	if ($_REQUEST["pagekey"]!=""){
		$sql = "SELECT nPageId FROM ".$db_tableprefix."Pages WHERE cPageKey='".$_REQUEST["pagekey"]."'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["nPageId"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// page_get_key(id) = key
if ($_REQUEST["action"]=="page_get_key"){
	if ($_REQUEST["pageid"]!=""){
		$sql = "SELECT cPageKey FROM ".$db_tableprefix."Pages WHERE nPageId='".$_REQUEST["pageid"]."'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["cPageKey"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// page_get_title(id) = title
if ($_REQUEST["action"]=="page_get_title"){
	if ($_REQUEST["pageid"]!=""){
		$sql = "SELECT cTitle FROM ".$db_tableprefix."Pages WHERE nPageId='".$_REQUEST["pageid"]."'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["cTitle"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// page_content_list(pageid) = contentkey,contentkey,contentkey,contentkey
if ($_REQUEST["action"]=="page_content_list"){
	if ($_REQUEST["pageid"]!=""){
		$sql = "SELECT cContentKey FROM ".$db_tableprefix."PageContent WHERE nPageId=".$_REQUEST["pageid"]." ORDER BY nTemplateElementId,nIndex";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$i=0;
				while ($row =@ mysql_fetch_array($result)){
					$i++;
					$aresult[$i] = $row["cContentKey"];
				}
				$result = implode($aresult,",");
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// content_get_id(key) = id
if ($_REQUEST["action"]=="content_get_id"){
	if ($_REQUEST["contentkey"]!=""){
		$sql = "SELECT nContentId FROM ".$db_tableprefix."Content WHERE cContentKey='".$_REQUEST["contentkey"]."'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["nContentId"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// content_get_key(id) = key
if ($_REQUEST["action"]=="content_get_key"){
	if ($_REQUEST["contentid"]!=""){
		$sql = "SELECT cContentKey FROM ".$db_tableprefix."Content WHERE nContentId=".$_REQUEST["contentid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["cContentKey"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// content_get_title(id) = title
if ($_REQUEST["action"]=="content_get_title"){
	if ($_REQUEST["contentid"]!=""){
		$sql = "SELECT cTitle FROM ".$db_tableprefix."Content WHERE nContentId=".$_REQUEST["contentid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = stripslashes($row["cTitle"]);
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// content_get_body(id) = body
if ($_REQUEST["action"]=="content_get_body"){
	if ($_REQUEST["contentid"]!=""){
		$sql = "SELECT cBody FROM ".$db_tableprefix."Content WHERE nContentId=".$_REQUEST["contentid"];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = stripslashes($row["cBody"]);
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// content_add(key,template,type,title,func,file,body) = contentid
if ($_REQUEST["action"]=="content_add"){
	$key = mysql_escape_string($_REQUEST["key"]);
	$template = mysql_escape_string($_REQUEST["template"]);
	$type = mysql_escape_string($_REQUEST["type"]);
	$title = mysql_escape_string($_REQUEST["title"]);
	$func = mysql_escape_string($_REQUEST["func"]);
	$file = mysql_escape_string($_REQUEST["file"]);
	$body = mysql_escape_string($_REQUEST["body"]);

	if ($key!="" && $template!="" && $type!="" && $title!="" && $body!=""){
		$sql = "INSERT INTO ".$db_tableprefix."Content (cContentKey,nTemplateId,nContentTypeId,cTitle,cFunction,cFile,cBody,dAdded) VALUES ("
			."'".$key."'"
			.",".$template
			.",".$type
			.",'".$title."'"
			.",'".$func."'"
			.",'".$file."'"
			.",'".$body."'"
			.",NOW()"
			.")";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$result = mysql_insert_id();
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// page_add(key,pagetypeid,title,notes,templateid) = pageid
if ($_REQUEST["action"]=="page_add"){

	$key = mysql_escape_string($_REQUEST["key"]);
	$title = mysql_escape_string($_REQUEST["title"]);
	$notes = mysql_escape_string($_REQUEST["notes"]);
	$pagetypeid = mysql_escape_string($_REQUEST["pagetypeid"]);
	$templateid = mysql_escape_string($_REQUEST["templateid"]);

	if ($key!="" && $title!="" && $templateid!="" && $pagetypeid!=""){
		$sql = "INSERT INTO ".$db_tableprefix."Pages (cPageKey,nPageTypeId,cTitle,cNotes,nTemplateId,dAdded) VALUES ("
			."'".$key."'"
			.",".$pagetypeid
			.",'".$title."'"
			.",'".$notes."'"
			.",".$templateid
			.",NOW()"
			.")";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$result = mysql_insert_id();
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}



// page_del(pageid) = "1"/"" = True, False
if ($_REQUEST["action"]=="page_del"){

	$pageid = mysql_escape_string($_REQUEST["pageid"]);
	if ($pageid!=""){
		$sql = "DELETE FROM ".$db_tableprefix."PageContent WHERE nPageId=".$pageid;
		$result = mysql_query($sql,$con);
		if ($result!=false){

			$sql = "DELETE FROM ".$db_tableprefix."Pages WHERE nPageId=".$pageid;
			$result = mysql_query($sql,$con);
			if ($result!=false){
				$result = "1";
			} else {
				$result = "0";
			}

		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}

}

// page_content_add(pageid,contentkey,element,template,index) = pagecontentid
if ($_REQUEST["action"]=="page_content_add"){

	$pageid = mysql_escape_string($_REQUEST["pageid"]);
	$contentkey = mysql_escape_string($_REQUEST["contentkey"]);
	$element = mysql_escape_string($_REQUEST["element"]);
	$template = mysql_escape_string($_REQUEST["template"]);
	$index = mysql_escape_string($_REQUEST["index"]);

	if ($pageid!="" && $contentkey!="" && $element!="" && $template!="" && $index!=""){

		// check if the pageid and contentkey exist as page and content
		$sql = "SELECT nPageId FROM ".$db_tableprefix."Pages WHERE nPageId=".$pageid;
		$result = mysql_query($sql,$con);
		if ($result!=false){

			if (mysql_num_rows($result)>0){

				$sql = "SELECT nContentId FROM ".$db_tableprefix."Content WHERE cContentKey='".$contentkey."'";
				$result = mysql_query($sql,$con);

				if ($result!=false){

					if (mysql_num_rows($result)>0){

						$sql = "INSERT INTO ".$db_tableprefix."PageContent (nPageId,cContentKey,nTemplateElementId,nTemplateId,nIndex,dAdded) VALUES ("
							.$pageid
							.",'".$contentkey."'"
							.",".$element
							.",".$template
							.",".$index
							.",NOW()"
							.")";
						$result = mysql_query($sql,$con);
						if ($result!=false){
							$result = mysql_insert_id();
						} else {
							// problem with insert sql
							$result = "-2";
						}


					} else {
						// no content found
						$result = "-3";
					}

				} else {
					// problem with content lookup sql
					$result = "-2";
				}


			} else {
				// no page found
				$result = "-3";
			}

		} else {
			// problem with page lookup sql
			$result = "-2";
		}

	} else {
		// not enough data supplied
		$result = "-1";
	}
}


// template_get_id(title,type) = id     (type = "page","pagecontent", "content")
if ($_REQUEST["action"]=="template_get_id"){

	$title = mysql_escape_string($_REQUEST["title"]);
	$type = mysql_escape_string($_REQUEST["type"]);

	if ($title!="" && $type!=""){
		$sql = "SELECT nTemplateId FROM ".$db_tableprefix."Templates WHERE cTitle='".$title."' AND cType='".$type."'";
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["nTemplateId"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}


// page_content_del(pagecontentid) = 1/"" = true/false
if ($_REQUEST["action"]=="page_content_del"){

	$pagecontentid = mysql_escape_string($_REQUEST["pagecontentid"]);

	if ($pagecontentid!=""){
		$sql = "DELETE FROM ".$db_tableprefix."PageContent WHERE nPageContentId=".$pagecontentid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			$result = "1";
		} else {
			$result = "0";
		}
	} else {
		$result = "-1";
	}

}


// contenttype_get_id(name) = contenttypeid
if ($_REQUEST["action"]=="contenttype_get_id"){
	$contenttypename = mysql_escape_string($_REQUEST["contenttypename"]);
	if ($contenttypename!=""){
		$sql = "SELECT nContentTypeId FROM ".$db_tableprefix."ContentType WHERE cContentTypeName='".$contenttypename."'";
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["nContentTypeId"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}


// contenttype_get_name(contenttypeid) = contenttypename
if ($_REQUEST["action"]=="contenttype_get_name"){
	$contenttypeid = mysql_escape_string($_REQUEST["contenttypeid"]);
	if ($contenttypeid!=""){
		$sql = "SELECT cContentTypeName FROM ".$db_tableprefix."ContentType WHERE nContentTypeId=".$contenttypeid;
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = stripslashes($row["cContentTypeName"]);
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}


// contenttype_property_list(contenttyppeid) = propertyid,propertyid,propertyid
// Returns the ids of the properties (not the data records) used by a contenttype

if ($_REQUEST["action"]=="contenttype_property_list"){
	$contenttypeid = mysql_escape_string($_REQUEST["contenttypeid"]);
	if ($contenttypeid!=""){
		$sql = "SELECT nContentTypePropertyId FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypeId=".$contenttypeid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$i=0;
				while ($row=@mysql_fetch_array($result)){
					$i++;
					$aresult[$i] = $row["nContentTypePropertyId"];
				}
				$result = implode($aresult,",");
			} else {
				$result = "";
			}
		}
	} else {
		$result = "-1";
	}
}

// content_property_get_name(propertyid) = property name
if ($_REQUEST["action"]=="content_property_get_name"){
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	if ($propertyid!=""){
		$sql = "SELECT cPropertyName FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["cPropertyName"];
			} else {
				$result = "-3";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// content_property_get(contentid,propertyid) = value
if ($_REQUEST["action"]=="content_property_get"){
	$contentid = mysql_escape_string($_REQUEST["contentid"]);
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	
	if ($contentid!="" && $propertyid!=""){
	
		// find out which propertytype we are looking for from the data table
		$sql = "SELECT cDataType FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				
				$datatype = $row["cDataType"];
				
				if ($datatype!=""){
				
					// look up the value in the ContentData table
					$sql = "SELECT ".$row["cDataType"]." FROM ".$db_tableprefix."ContentData WHERE nContentId=".$contentid." AND nPropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							// data has been recorded
							$row = mysql_fetch_array($result);
							$result = stripslashes($row[$datatype]);
						} else {
							// no data has been recorded in there yet
							$result = "";
						}
					} else {
						$result = "-5";
					}
					
				} else {
					$result = "-4";
				}
			} else {
				$result = "-3";
			}
		} else {
			$result = "-2";
		}

	
	} else {
		$result = "-1";
	}
}

// content_property_set(contentid,propertyid,value) = 1,0 (success, failure)
if ($_REQUEST["action"]=="content_property_set"){

	$contentid = mysql_escape_string($_REQUEST["contentid"]);
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	$value = mysql_escape_string($_REQUEST["value"]);

	if ($contentid!="" && $propertyid!=""){

		// 1 - check the datatype of the property record
		$sql = "SELECT cDataType FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){

				$row = mysql_fetch_array($result);

				$datatype = $row["cDataType"];

				// 2 - validate the value passed to this function
				$result = "1";

				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value)){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value)!=-1){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "bDataBoolean":
						if ($value=="T" || $value=="F" || $value=="0" || $value=="1"){
							switch($value){
								case "T":
									$value = "1";
									break;
								case "F":
									$value = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value)){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value)){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value)){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				// check if the value exists
				if ($result>0){
					$sql = "SELECT * FROM ".$db_tableprefix."ContentData WHERE nContentId=".$contentid." AND nPropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							// do an update
							$row = mysql_fetch_array($result);
							$sql = "UPDATE ".$db_tableprefix."ContentData SET ".$datatype."=".$value." WHERE nContentDataId=".$row["nContentDataId"];
							$result = mysql_query($sql,$con);
							if ($result!=false){
								// success
								$result = "1";
							} else {
								// failure
								$result = "-2";
							}
						} else {
							// do an insert
							$sql = "INSERT INTO ".$db_tableprefix."ContentData (nContentId,nPropertyId,".$datatype.") VALUES ("
								.$contentid
								.",".$propertyid
								.",".$value
								.")";
							$result = mysql_query($sql,$con);
							if ($result!=false){
								// success
								$result = "1";
							} else {
								// failure
								$result = "-2";
							}
						}
					} else {
						$result = "-2";
					}
				}

			} else {
				$result = "-2";
			}
		} else {
			$result = "-1";
		}


	} else {
		$result = "-1";
	}

}


// ***************


// pagetype_get_id(pagetypename) = pagetypeid
if ($_REQUEST["action"]=="pagetype_get_id"){
	$pagetypename = mysql_escape_string($_REQUEST["pagetypename"]);
	if ($pagetypename!=""){
		$sql = "SELECT nPageTypeId FROM ".$db_tableprefix."PageType WHERE cPageTypeName='".$pagetypename."'";
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["nPageTypeId"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}


// pagetype_get_name(pagetypeid) = pagetypename
if ($_REQUEST["action"]=="pagetype_get_name"){
	$pagetypeid = mysql_escape_string($_REQUEST["pagetypeid"]);
	if ($pagetypeid!=""){
		$sql = "SELECT cPageTypeName FROM ".$db_tableprefix."PageType WHERE nPageTypeId=".$pagetypeid;
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = stripslashes($row["cPageTypeName"]);
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}


// pagetype_property_list(pagetyppeid) = propertyid,propertyid,propertyid
// Returns the ids of the properties (not the data records) used by a pagetype

if ($_REQUEST["action"]=="pagetype_property_list"){
	$pagetypeid = mysql_escape_string($_REQUEST["pagetypeid"]);
	if ($pagetypeid!=""){
		$sql = "SELECT nPageTypePropertyId FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypeId=".$pagetypeid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$i=0;
				while ($row=@mysql_fetch_array($result)){
					$i++;
					$aresult[$i] = $row["nPageTypePropertyId"];
				}
				$result = implode($aresult,",");
			} else {
				$result = "";
			}
		}
	} else {
		$result = "-1";
	}
}

// page_property_get_name(propertyid) = property name
if ($_REQUEST["action"]=="page_property_get_name"){
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	if ($propertyid!=""){
		$sql = "SELECT cPropertyName FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["cPropertyName"];
			} else {
				$result = "-3";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// page_property_get(pageid,propertyid) = value
if ($_REQUEST["action"]=="page_property_get"){
	$pageid = mysql_escape_string($_REQUEST["pageid"]);
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	
	if ($pageid!="" && $propertyid!=""){
	
		// find out which propertytype we are looking for from the data table
		$sql = "SELECT cDataType FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				
				$datatype = $row["cDataType"];
				
				if ($datatype!=""){
				
					// look up the value in the PageData table
					$sql = "SELECT ".$row["cDataType"]." FROM ".$db_tableprefix."PageData WHERE nPageId=".$pageid." AND nPropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							// data has been recorded
							$row = mysql_fetch_array($result);
							$result = stripslashes($row[$datatype]);
						} else {
							// no data has been recorded in there yet
							$result = "";
						}
					} else {
						$result = "-5";
					}
					
				} else {
					$result = "-4";
				}
			} else {
				$result = "-3";
			}
		} else {
			$result = "-2";
		}

	
	} else {
		$result = "-1";
	}
}

// page_property_set(pageid,propertyid,value) = 1,0 (success, failure)
if ($_REQUEST["action"]=="page_property_set"){

	$pageid = mysql_escape_string($_REQUEST["pageid"]);
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	$value = mysql_escape_string($_REQUEST["value"]);

	if ($pageid!="" && $propertyid!=""){

		// 1 - check the datatype of the property record
		$sql = "SELECT cDataType FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){

				$row = mysql_fetch_array($result);

				$datatype = $row["cDataType"];

				// 2 - validate the value passed to this function
				$result = "1";

				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value)){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value)!=-1){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "bDataBoolean":
						if ($value=="T" || $value=="F" || $value=="0" || $value=="1"){
							switch($value){
								case "T":
									$value = "1";
									break;
								case "F":
									$value = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value)){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value)){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value)){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				// check if the value exists
				if ($result>0){
					$sql = "SELECT * FROM ".$db_tableprefix."PageData WHERE nPageId=".$pageid." AND nPropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							// do an update
							$row = mysql_fetch_array($result);
							$sql = "UPDATE ".$db_tableprefix."PageData SET ".$datatype."=".$value." WHERE nPageDataId=".$row["nPageDataId"];
							$result = mysql_query($sql,$con);
							if ($result!=false){
								// success
								$result = "1";
							} else {
								// failure
								$result = "-2";
							}
						} else {
							// do an insert
							$sql = "INSERT INTO ".$db_tableprefix."PageData (nPageId,nPropertyId,".$datatype.") VALUES ("
								.$pageid
								.",".$propertyid
								.",".$value
								.")";
							$result = mysql_query($sql,$con);
							if ($result!=false){
								// success
								$result = "1";
							} else {
								// failure
								$result = "-2";
							}
						}
					} else {
						$result = "-2";
					}
				}

			} else {
				$result = "-2";
			}
		} else {
			$result = "-1";
		}


	} else {
		$result = "-1";
	}

}


// document_add(filename,documenttypeid)
// (presumes you have already put the file in the pickup directory)
if ($_REQUEST["action"]=="document_add"){
	
	$filename = $_REQUEST["filename"];
	$doctypeid = $_REQUEST["doctypeid"];
	
	if ($filename!="" && $doctypeid!=""){
	
		// first make sure the repository has been set
		$sql = "SELECT nRepositoryId FROM ".$db_tableprefix."DocumentType WHERE nDocumentTypeId=".$doctypeid;
		$result = mysql_query($sql,$con);
		if ($result!=false) {
	
			if (mysql_num_rows($result)>0){
	
				$row = mysql_fetch_array($result);
				
				$repositoryid = $row["nRepositoryId"];
				
				if ($repositoryid>0){
				
					$pickup_file = $pickup_path."/".$filename;

					if (file_exists($pickup_file)){

						// make a document record
						// store the document record
						$sql = "INSERT INTO ".$db_tableprefix."Document ("
							."nDocumentTypeId,nVersion,dAdded,dEdited,nAddedBy,nEditedBy,cFilename,cOriginalFilename"
							.") VALUES ("
							.$doctypeid
							.",1"
							.",now()"
							.",now()"
							.",2"
							.",2"
							.",''"
							.",'".$filename."'"
							.")";

						$result = mysql_query($sql,$con);
						if ($result!=false){

							// find out what documentid it got
							$documentid = mysql_insert_id();
							
							// move the file!
							$result = store_file($documentid,$pickup_file,$repositoryid);

							if ($result>0){
								$result = $documentid;
							} else {
								// leave result as it is - reflecting a problem in store_file()
							}
							
						} else {
							// problem with document insert SQL
							$result = "-1";
						}

					} else {
						// pickup file not found
						$result = "-5";
					}
				} else {
					// repository was not set
					$result = "-4";
				}
			} else {
				// no datatype record found while looking for repositoryid
				$result = "-3";
			}
		} else {
			// repository lookup SQL failed
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// doctype_get_id(doctypename)=doctypeid
if ($_REQUEST["action"]=="doctype_get_id"){
	$doctypename = mysql_escape_string($_REQUEST["doctypename"]);
	if ($doctypename!=""){
		$sql = "SELECT nDocumentTypeId FROM ".$db_tableprefix."DocumentType WHERE cName='".$doctypename."'";
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["nDocumentTypeId"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// doctype_get_name(doctypeid)=doctypename
if ($_REQUEST["action"]=="doctype_get_name"){
	$doctypeid = mysql_escape_string($_REQUEST["doctypeid"]);
	if ($doctypeid!=""){
		$sql = "SELECT cName FROM ".$db_tableprefix."DocumentType WHERE nDocumentTypeId=".$doctypeid;
		$result = mysql_query($sql,$con);
		if ($result!=false) {
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["cName"];
			} else {
				$result = "";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}

// doctype_property_list(doctypeid)=propid,propid,propid
if ($_REQUEST["action"]=="doctype_property_list"){
	$doctypeid = mysql_escape_string($_REQUEST["doctypeid"]);
	if ($doctypeid!=""){
		$sql = "SELECT nDocumentTypePropertyId FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypeId=".$doctypeid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$i=0;
				while ($row=@mysql_fetch_array($result)){
					$i++;
					$aresult[$i] = $row["nDocumentTypePropertyId"];
				}
				$result = implode($aresult,",");
			} else {
				$result = "";
			}
		}
	} else {
		$result = "-1";
	}
}

// document_property_get(documentid,propertyid)=prop value
if ($_REQUEST["action"]=="document_property_get"){
	$documentid = mysql_escape_string($_REQUEST["documentid"]);
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	if ($documentid!="" && $propertyid!=""){
	
		// find out which propertytype we are looking for from the data table
		$sql = "SELECT cDataType FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				
				$datatype = $row["cDataType"];
				
				if ($datatype!=""){
				
					// look up the value in the PageData table
					$sql = "SELECT ".$datatype." FROM ".$db_tableprefix."DocumentData WHERE nDocumentId=".$documentid." AND nPropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							// data has been recorded
							$row = mysql_fetch_array($result);
							$result = stripslashes($row["$datatype"]);
						} else {
							// no data has been recorded in there yet
							$result = "";
						}
					} else {
						$result = "-2";
					}
					
				} else {
					$result = "-4";
				}
			} else {
				$result = "-3";
			}
		} else {
			$result = "-2";
		}

	
	} else {
		$result = "-1";
	}
}

// document_property_set(documentid,propertyid,value) = 1/neg = success, failure
if ($_REQUEST["action"]=="document_property_set"){

	$documentid = mysql_escape_string($_REQUEST["documentid"]);
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	$value = mysql_escape_string($_REQUEST["value"]);

	if ($documentid!="" && $propertyid!=""){

		// 1 - check the datatype of the property record
		$sql = "SELECT cDataType FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){

				$row = mysql_fetch_array($result);

				$datatype = $row["cDataType"];

				// 2 - validate the value passed to this function
				$result = "1";

				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value)){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value)!=-1){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "bDataBoolean":
						if ($value=="T" || $value=="F" || $value=="0" || $value=="1"){
							switch($value){
								case "T":
									$value = "1";
									break;
								case "F":
									$value = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value)){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value)){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value)){
							$result = "-4";
						} else {
							$value = "'".$value."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				// check if the value exists
				if ($result>0){
					$sql = "SELECT * FROM ".$db_tableprefix."DocumentData WHERE nDocumentId=".$documentid." AND nPropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							// do an update
							$row = mysql_fetch_array($result);
							$sql = "UPDATE ".$db_tableprefix."DocumentData SET ".$datatype."=".$value." WHERE nDocumentDataId=".$row["nDocumentDataId"];
							$result = mysql_query($sql,$con);
							if ($result!=false){
								// success
								$result = "1";
							} else {
								// failure
								$result = "-2";
							}
						} else {
							// do an insert
							$sql = "INSERT INTO ".$db_tableprefix."DocumentData (nDocumentId,nPropertyId,".$datatype.") VALUES ("
								.$documentid
								.",".$propertyid
								.",".$value
								.")";
							$result = mysql_query($sql,$con);
							if ($result!=false){
								// success
								$result = "1";
							} else {
								// failure
								$result = "-2";
							}
						}
					} else {
						$result = "-2";
					}
				}

			} else {
				$result = "-2";
			}
		} else {
			$result = "-1";
		}


	} else {
		$result = "-1";
	}

}


// document_property_get_name(propertyid) = propertyname
if ($_REQUEST["action"]=="document_property_get_name"){
	$propertyid = mysql_escape_string($_REQUEST["propertyid"]);
	if ($propertyid!=""){
		$sql = "SELECT cPropertyName FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypePropertyId=".$propertyid;
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$result = $row["cPropertyName"];
			} else {
				$result = "-3";
			}
		} else {
			$result = "-2";
		}
	} else {
		$result = "-1";
	}
}


// document_find() = docid
if ($_REQUEST["action"]=="document_find"){
	
	
	$j = 0;
	$k = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("p[0-9]+",$key)){
			$j++;
			$propertyid[$j] = substr($key,1,strlen($key)-1);
			$value[$j] = $val;
		}
	}
	
	for($i=1;$i<=$j;$i++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypePropertyId=".$propertyid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value[$j])!=-1){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($value[$j]=="T" || $value[$j]=="F" || $value[$j]=="0" || $value[$j]=="1"){
							switch($value){
								case "T":
									$value[$j] = "1";
									break;
								case "F":
									$value[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT doc.nDocumentId FROM ".$db_tableprefix."Document doc"
						." INNER JOIN ".$db_tableprefix."DocumentTypeProperties dtp ON doc.nDocumentTypeId=dtp.nDocumentTypeId"
						." INNER JOIN ".$db_tableprefix."DocumentData dd ON (dtp.nDocumentTypePropertyId=dd.nPropertyId AND dd.nDocumentId=doc.nDocumentId)"
						." WHERE dtp.nDocumentTypePropertyId=".$propertyid[$j]." AND dd.".$datatype."=".$value[$j];

					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nDocumentId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	if(is_array($a_overall_result) && $result>=0){
		$result = implode(",",$a_overall_result);
	} else {
		$result = "";
	}
	
}

// document_find() = docid
if ($_REQUEST["action"]=="document_find_set"){
	
	
	$j = 0;
	$k = 0;
	$m = 0;
	$n = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("f[0-9]+",$key)){
			$m++;
			$findpropid[$m] = substr($key,1,strlen($key)-1);
			$findvalue[$m] = $val;
		}

		if (ereg("s[0-9]+",$key)){
			$n++;
			$setpropid[$n] = substr($key,1,strlen($key)-1);
			$setvalue[$n] = $val;
		}
	}
	
	
	for($j=1;$j<=$m;$j++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypePropertyId=".$findpropid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($findvalue[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($findvalue[$j])!=-1){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($findvalue[$j]=="T" || $findvalue[$j]=="F" || $findvalue[$j]=="0" || $findvalue[$j]=="1"){
							switch($value){
								case "T":
									$findvalue[$j] = "1";
									break;
								case "F":
									$findvalue[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($findvalue[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($findvalue[$j])){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($findvalue[$j])){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT doc.nDocumentId FROM ".$db_tableprefix."Document doc"
						." INNER JOIN ".$db_tableprefix."DocumentTypeProperties dtp ON doc.nDocumentTypeId=dtp.nDocumentTypeId"
						." INNER JOIN ".$db_tableprefix."DocumentData dd ON (dtp.nDocumentTypePropertyId=dd.nPropertyId AND dd.nDocumentId=doc.nDocumentId)"
						." WHERE dtp.nDocumentTypePropertyId=".$findpropid[$j]." AND dd.".$datatype."=".$findvalue[$j];

					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nDocumentId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	// a_overall_result now has all the documentid's in we want to work on
	// for each document, try to set the values presented in $setvalue[]
	if (count($a_overall_result)>0){
	
		foreach($a_overall_result as $documentid){

			foreach($setpropid as $m => $propertyid){

				$value = $setvalue[$m];

				if ($documentid!="" && $propertyid!="" && $value!=""){

					// 1 - check the datatype of the property record
					$sql = "SELECT cDataType FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypePropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){

							$row = mysql_fetch_array($result);

							$datatype = $row["cDataType"];

							// 2 - validate the value passed to this function
							$result = "1";

							switch($datatype){
								case "nDataBigInt":
									if (!is_numeric($value)){
										$result = "-4";
									}
									break;
								case "dDataDate":
									if (!strtotime($value)!=-1){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "bDataBoolean":
									if ($value=="T" || $value=="F" || $value=="0" || $value=="1"){
										switch($value){
											case "T":
												$value = "1";
												break;
											case "F":
												$value = "0";
												break;
										}
									} else {
										$result = "-4";
									}
									break;
								case "nDataFloat":
									if (!is_numeric($value)){
										$result = "-4";
									}
									break;
								case "cDataVarchar":
									if (!is_string($value)){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "cDataMediumText":
									if (!is_string($value)){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "bDataBlob":
									break;
							}


							// check if the value exists
							if ($result>0){
								$sql = "SELECT * FROM ".$db_tableprefix."DocumentData WHERE nDocumentId=".$documentid." AND nPropertyId=".$propertyid;
								$result = mysql_query($sql,$con);
								if ($result!=false){
									if (mysql_num_rows($result)>0){
										// do an update
										$row = mysql_fetch_array($result);
										$sql = "UPDATE ".$db_tableprefix."DocumentData SET ".$datatype."=".$value." WHERE nDocumentDataId=".$row["nDocumentDataId"];
										$result = mysql_query($sql,$con);
										if ($result!=false){
											// success
											$result = "1";
										} else {
											// failure
											$result = "-2";
										}
									} else {
										// do an insert
										$sql = "INSERT INTO ".$db_tableprefix."DocumentData (nDocumentId,nPropertyId,".$datatype.") VALUES ("
											.$documentid
											.",".$propertyid
											.",".$value
											.")";
										$result = mysql_query($sql,$con);
										if ($result!=false){
											// success
											$result = "1";
										} else {
											// failure
											$result = "-2";
										}
									}
								} else {
									$result = "-2";
								}
							}

						} else {
							$result = "-2";
						}
					} else {
						$result = "-1";
					}
				} else {
					$result = "-1";
				}
			} // end of each foreach property we are setting
		} // end of foreach document we are working on
	}
}


if ($_REQUEST["action"]=="document_del"){
	$documentid = mysql_escape_string($_REQUEST["documentid"]);
	if ($documentid!=""){
		
		// find out where the file is
		$sql = "SELECT rep.cPath FROM ".$db_tableprefix."Document doc"
			." INNER JOIN ".$db_tableprefix."DocumentType dt ON doc.nDocumentTypeId=dt.nDocumentTypeId"
			." INNER JOIN ".$db_tableprefix."Repository rep ON dt.nRepositoryId=rep.nRepositoryId"
			." WHERE nDocumentId=".$documentid;
		
		$result = mysql_query($sql,$con);
		
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				
				$filename = stripslashes($row["cPath"])."/".$documentid;
				if (file_exists($filename)){
					
					// file removed
					$result = unlink($filename);
					
					// remove document
					$sql = "DELETE FROM ".$db_tableprefix."Document WHERE nDocumentId=".$documentid;
					$result = mysql_query($sql,$con);
					
					$sql = "DELETE FROM ".$db_tableprefix."DocumentData WHERE nDocumentId=".$documentid;
					$result = mysql_query($sql,$con);
					
					if ($result!=false){
						$result = "1";
					} else {
						$result = "-1";
					}
					
				} else {
					$result = "-5";
				}
			} else {
				$result = "-3";
			}
		} else {
			$result = "-2";
		}
		
	} else {
		$result = "-1";
	}
}


// document_find() = docid
if ($_REQUEST["action"]=="document_find_delete"){
	
	
	$j = 0;
	$k = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("p[0-9]+",$key)){
			$j++;
			$propertyid[$j] = substr($key,1,strlen($key)-1);
			$value[$j] = $val;
		}
	}
	
	for($i=1;$i<=$j;$i++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."DocumentTypeProperties WHERE nDocumentTypePropertyId=".$propertyid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value[$j])!=-1){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($value[$j]=="T" || $value[$j]=="F" || $value[$j]=="0" || $value[$j]=="1"){
							switch($value){
								case "T":
									$value[$j] = "1";
									break;
								case "F":
									$value[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT doc.nDocumentId FROM ".$db_tableprefix."Document doc"
						." INNER JOIN ".$db_tableprefix."DocumentTypeProperties dtp ON doc.nDocumentTypeId=dtp.nDocumentTypeId"
						." INNER JOIN ".$db_tableprefix."DocumentData dd ON (dtp.nDocumentTypePropertyId=dd.nPropertyId AND dd.nDocumentId=doc.nDocumentId)"
						." WHERE dtp.nDocumentTypePropertyId=".$propertyid[$j]." AND dd.".$datatype."=".$value[$j];

					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nDocumentId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){								
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	if(is_array($a_overall_result) && $result>=0){

		if (count($a_overall_result)>0){

			foreach($a_overall_result as $documentid){

				if ($documentid!=""){

					// find out where the file is
					$sql = "SELECT rep.cPath FROM ".$db_tableprefix."Document doc"
						." INNER JOIN ".$db_tableprefix."DocumentType dt ON doc.nDocumentTypeId=dt.nDocumentTypeId"
						." INNER JOIN ".$db_tableprefix."Repository rep ON dt.nRepositoryId=rep.nRepositoryId"
						." WHERE nDocumentId=".$documentid;

					$result = mysql_query($sql,$con);

					if ($result!=false){
						if (mysql_num_rows($result)>0){
							$row = mysql_fetch_array($result);

							$filename = stripslashes($row["cPath"])."/".$documentid;
							if (file_exists($filename)){

								// file removed
								$result = unlink($filename);

								// remove document
								$sql = "DELETE FROM ".$db_tableprefix."Document WHERE nDocumentId=".$documentid;
								$result = mysql_query($sql,$con);

								$sql = "DELETE FROM ".$db_tableprefix."DocumentData WHERE nDocumentId=".$documentid;
								$result = mysql_query($sql,$con);

								if ($result!=false){
									$result = "1";
								} else {
									$result = "-1";
								}

							} else {
								$result = "-5";
							}
						} else {
							$result = "-3";
						}
					} else {
						$result = "-2";
					}

				} else {
					$result = "-1";
				}
			}

		}
		
		
	} else {
		$result = "";
	}
	
}



if ($_REQUEST["action"]=="content_find_set"){
		
	$j = 0;
	$k = 0;
	$m = 0;
	$n = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("f[0-9]+",$key)){
			$m++;
			$findpropid[$m] = substr($key,1,strlen($key)-1);
			$findvalue[$m] = $val;
		}

		if (ereg("s[0-9]+",$key)){
			$n++;
			$setpropid[$n] = substr($key,1,strlen($key)-1);
			$setvalue[$n] = $val;
		}
	}
	
	
	for($j=1;$j<=$m;$j++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypePropertyId=".$findpropid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($findvalue[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($findvalue[$j])!=-1){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($findvalue[$j]=="T" || $findvalue[$j]=="F" || $findvalue[$j]=="0" || $findvalue[$j]=="1"){
							switch($value){
								case "T":
									$findvalue[$j] = "1";
									break;
								case "F":
									$findvalue[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($findvalue[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($findvalue[$j])){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($findvalue[$j])){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT con.nContentId FROM ".$db_tableprefix."Content con"
						." INNER JOIN ".$db_tableprefix."ContentTypeProperties ctp ON con.nContentTypeId=ctp.nContentTypeId"
						." INNER JOIN ".$db_tableprefix."ContentData cd ON (ctp.nContentTypePropertyId=cd.nPropertyId AND cd.nContentId=con.nContentId)"
						." WHERE ctp.nContentTypePropertyId=".$findpropid[$j]." AND cd.".$datatype."=".$findvalue[$j];
										
					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nContentId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	// a_overall_result now has all the documentid's in we want to work on
	// for each document, try to set the values presented in $setvalue[]
	if (count($a_overall_result)>0){
	
		foreach($a_overall_result as $documentid){

			foreach($setpropid as $m => $propertyid){

				$value = $setvalue[$m];

				if ($documentid!="" && $propertyid!="" && $value!=""){

					// 1 - check the datatype of the property record
					$sql = "SELECT cDataType FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypePropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){

							$row = mysql_fetch_array($result);

							$datatype = $row["cDataType"];

							// 2 - validate the value passed to this function
							$result = "1";

							switch($datatype){
								case "nDataBigInt":
									if (!is_numeric($value)){
										$result = "-4";
									}
									break;
								case "dDataDate":
									if (!strtotime($value)!=-1){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "bDataBoolean":
									if ($value=="T" || $value=="F" || $value=="0" || $value=="1"){
										switch($value){
											case "T":
												$value = "1";
												break;
											case "F":
												$value = "0";
												break;
										}
									} else {
										$result = "-4";
									}
									break;
								case "nDataFloat":
									if (!is_numeric($value)){
										$result = "-4";
									}
									break;
								case "cDataVarchar":
									if (!is_string($value)){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "cDataMediumText":
									if (!is_string($value)){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "bDataBlob":
									break;
							}


							// check if the value exists
							if ($result>0){
								$sql = "SELECT * FROM ".$db_tableprefix."ContentData WHERE nContentId=".$documentid." AND nPropertyId=".$propertyid;
								$result = mysql_query($sql,$con);
								if ($result!=false){
									if (mysql_num_rows($result)>0){
										// do an update
										$row = mysql_fetch_array($result);
										$sql = "UPDATE ".$db_tableprefix."ContentData SET ".$datatype."=".$value." WHERE nContentDataId=".$row["nContentDataId"];
										$result = mysql_query($sql,$con);
										if ($result!=false){
											// success
											$result = "1";
										} else {
											// failure
											$result = "-2";
										}
									} else {
										// do an insert
										$sql = "INSERT INTO ".$db_tableprefix."ContentData (nContentId,nPropertyId,".$datatype.") VALUES ("
											.$documentid
											.",".$propertyid
											.",".$value
											.")";
										$result = mysql_query($sql,$con);
										if ($result!=false){
											// success
											$result = "1";
										} else {
											// failure
											$result = "-2";
										}
									}
								} else {
									$result = "-2";
								}
							}

						} else {
							$result = "-2";
						}
					} else {
						$result = "-1";
					}
				} else {
					$result = "-1";
				}
			} // end of each foreach property we are setting
		} // end of foreach document we are working on
	}
}


if ($_REQUEST["action"]=="page_find_set"){
		
	$j = 0;
	$k = 0;
	$m = 0;
	$n = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("f[0-9]+",$key)){
			$m++;
			$findpropid[$m] = substr($key,1,strlen($key)-1);
			$findvalue[$m] = $val;
		}

		if (ereg("s[0-9]+",$key)){
			$n++;
			$setpropid[$n] = substr($key,1,strlen($key)-1);
			$setvalue[$n] = $val;
		}
	}
	
	
	for($j=1;$j<=$m;$j++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypePropertyId=".$findpropid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($findvalue[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($findvalue[$j])!=-1){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($findvalue[$j]=="T" || $findvalue[$j]=="F" || $findvalue[$j]=="0" || $findvalue[$j]=="1"){
							switch($value){
								case "T":
									$findvalue[$j] = "1";
									break;
								case "F":
									$findvalue[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($findvalue[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($findvalue[$j])){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($findvalue[$j])){
							$result = "-4";
						} else {
							$findvalue[$j] = "'".$findvalue[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT pag.nPageId FROM ".$db_tableprefix."Pages pag"
						." INNER JOIN ".$db_tableprefix."PageTypeProperties ptp ON pag.nPageTypeId=ptp.nPageTypeId"
						." INNER JOIN ".$db_tableprefix."PageData pd ON (ptp.nPageTypePropertyId=pd.nPropertyId AND pd.nPageId=pag.nPageId)"
						." WHERE ptp.nPageTypePropertyId=".$findpropid[$j]." AND pd.".$datatype."=".$findvalue[$j];

					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nPageId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	// a_overall_result now has all the documentid's in we want to work on
	// for each document, try to set the values presented in $setvalue[]
	if (count($a_overall_result)>0){
	
		foreach($a_overall_result as $documentid){

			foreach($setpropid as $m => $propertyid){

				$value = $setvalue[$m];

				if ($documentid!="" && $propertyid!="" && $value!=""){

					// 1 - check the datatype of the property record
					$sql = "SELECT cDataType FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypePropertyId=".$propertyid;
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){

							$row = mysql_fetch_array($result);

							$datatype = $row["cDataType"];

							// 2 - validate the value passed to this function
							$result = "1";

							switch($datatype){
								case "nDataBigInt":
									if (!is_numeric($value)){
										$result = "-4";
									}
									break;
								case "dDataDate":
									if (!strtotime($value)!=-1){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "bDataBoolean":
									if ($value=="T" || $value=="F" || $value=="0" || $value=="1"){
										switch($value){
											case "T":
												$value = "1";
												break;
											case "F":
												$value = "0";
												break;
										}
									} else {
										$result = "-4";
									}
									break;
								case "nDataFloat":
									if (!is_numeric($value)){
										$result = "-4";
									}
									break;
								case "cDataVarchar":
									if (!is_string($value)){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "cDataMediumText":
									if (!is_string($value)){
										$result = "-4";
									} else {
										$value = "'".$value."'";
									}
									break;
								case "bDataBlob":
									break;
							}


							// check if the value exists
							if ($result>0){
								$sql = "SELECT * FROM ".$db_tableprefix."PageData WHERE nPageId=".$documentid." AND nPropertyId=".$propertyid;
								$result = mysql_query($sql,$con);
								if ($result!=false){
									if (mysql_num_rows($result)>0){
										// do an update
										$row = mysql_fetch_array($result);
										$sql = "UPDATE ".$db_tableprefix."PageData SET ".$datatype."=".$value." WHERE nPageDataId=".$row["nPageDataId"];
										$result = mysql_query($sql,$con);
										if ($result!=false){
											// success
											$result = "1";
										} else {
											// failure
											$result = "-2";
										}
									} else {
										// do an insert
										$sql = "INSERT INTO ".$db_tableprefix."PageData (nPageId,nPropertyId,".$datatype.") VALUES ("
											.$documentid
											.",".$propertyid
											.",".$value
											.")";
										$result = mysql_query($sql,$con);
										if ($result!=false){
											// success
											$result = "1";
										} else {
											// failure
											$result = "-2";
										}
									}
								} else {
									$result = "-2";
								}
							}

						} else {
							$result = "-2";
						}
					} else {
						$result = "-1";
					}
				} else {
					$result = "-1";
				}
			} // end of each foreach property we are setting
		} // end of foreach document we are working on
	}
}


if ($_REQUEST["action"]=="content_find_delete"){
	
	
	$j = 0;
	$k = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("p[0-9]+",$key)){
			$j++;
			$propertyid[$j] = substr($key,1,strlen($key)-1);
			$value[$j] = $val;
			
		}
	}
	
	for($i=1;$i<=$j;$i++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypePropertyId=".$propertyid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value[$j])!=-1){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($value[$j]=="T" || $value[$j]=="F" || $value[$j]=="0" || $value[$j]=="1"){
							switch($value){
								case "T":
									$value[$j] = "1";
									break;
								case "F":
									$value[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT con.nContentId FROM ".$db_tableprefix."Content con"
						." INNER JOIN ".$db_tableprefix."ContentTypeProperties ctp ON con.nContentTypeId=ctp.nContentTypeId"
						." INNER JOIN ".$db_tableprefix."ContentData cd ON (ctp.nContentTypePropertyId=cd.nPropertyId AND cd.nContentId=con.nContentId)"
						." WHERE ctp.nContentTypePropertyId=".$propertyid[$j]." AND cd.".$datatype."=".$value[$j];

					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nContentId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	if(is_array($a_overall_result) && $result>=0){

		if (count($a_overall_result)>0){

			foreach($a_overall_result as $contentid){

				if ($contentid!=""){

					$sql = "DELETE FROM ".$db_tableprefix."Content WHERE nContentId=".$contentid;
					$result = mysql_query($sql,$con);

					$sql = "DELETE FROM ".$db_tableprefix."ContentData WHERE nContentId=".$contentid;
					$result = mysql_query($sql,$con);

					if ($result!=false){
						$result = "1";
					} else {
						$result = "-1";
					}

				} else {
					$result = "-1";
				}
			}

		}
		
		
	} else {
		$result = "";
	}
	
}


if ($_REQUEST["action"]=="page_find_delete"){
	
	
	$j = 0;
	$k = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("p[0-9]+",$key)){
			$j++;
			$propertyid[$j] = substr($key,1,strlen($key)-1);
			$value[$j] = $val;
		}
	}
	
	for($i=1;$i<=$j;$i++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypePropertyId=".$propertyid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value[$j])!=-1){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($value[$j]=="T" || $value[$j]=="F" || $value[$j]=="0" || $value[$j]=="1"){
							switch($value){
								case "T":
									$value[$j] = "1";
									break;
								case "F":
									$value[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT pag.nPageId FROM ".$db_tableprefix."Pages pag"
						." INNER JOIN ".$db_tableprefix."PageTypeProperties ptp ON pag.nPageTypeId=ptp.nPageTypeId"
						." INNER JOIN ".$db_tableprefix."PageData pd ON (ptp.nPageTypePropertyId=pd.nPropertyId AND pd.nPageId=pag.nPageId)"
						." WHERE ptp.nPageTypePropertyId=".$propertyid[$j]." AND pd.".$datatype."=".$value[$j];

					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nPageId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	if(is_array($a_overall_result) && $result>=0){

		if (count($a_overall_result)>0){

			foreach($a_overall_result as $pageid){

				if ($pageid!=""){

					$sql = "DELETE FROM ".$db_tableprefix."Pages WHERE nPageId=".$pageid;
					$result = mysql_query($sql,$con);

					$sql = "DELETE FROM ".$db_tableprefix."PageData WHERE nPageId=".$pageid;
					$result = mysql_query($sql,$con);

					$sql = "DELETE FROM ".$db_tableprefix."PageContent WHERE nPageId=".$pageid;
					if ($result!=false){
						$result = "1";
					} else {
						$result = "-1";
					}

				} else {
					$result = "-1";
				}
			}

		}
		
		
	} else {
		$result = "";
	}
	
}


if ($_REQUEST["action"]=="content_find"){
		
	$j = 0;
	$k = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("p[0-9]+",$key)){
			$j++;
			$propertyid[$j] = substr($key,1,strlen($key)-1);
			$value[$j] = $val;
		}
	}
	
	for($i=1;$i<=$j;$i++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."ContentTypeProperties WHERE nContentTypePropertyId=".$propertyid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value[$j])!=-1){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($value[$j]=="T" || $value[$j]=="F" || $value[$j]=="0" || $value[$j]=="1"){
							switch($value){
								case "T":
									$value[$j] = "1";
									break;
								case "F":
									$value[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT con.nContentId FROM ".$db_tableprefix."Content con"
						." INNER JOIN ".$db_tableprefix."ContentTypeProperties ctp ON con.nContentTypeId=ctp.nContentTypeId"
						." INNER JOIN ".$db_tableprefix."ContentData cd ON (ctp.nContentTypePropertyId=cd.nPropertyId AND cd.nContentId=con.nContentId)"
						." WHERE ctp.nContentTypePropertyId=".$propertyid[$j]." AND cd.".$datatype."=".$value[$j];

					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nContentId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	if(is_array($a_overall_result) && $result>=0){
		$result = implode(",",$a_overall_result);
	} else {
		$result = "";
	}
	
}


if ($_REQUEST["action"]=="page_find"){
		
	$j = 0;
	$k = 0;
	
	// capture the properties from the REQUEST array
	// e.g. ?action=document_find&p1=a&p2=b
	foreach ($_REQUEST as $key => $val) {
		if (ereg("p[0-9]+",$key)){
			$j++;
			$propertyid[$j] = substr($key,1,strlen($key)-1);
			$value[$j] = $val;
		}
	}
	
	for($i=1;$i<=$j;$i++){

		// what field/datatype should we be looking in for the propertyid?
		$sql = "SELECT cDataType FROM ".$db_tableprefix."PageTypeProperties WHERE nPageTypePropertyId=".$propertyid[$j];
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result);
				$datatype = $row["cDataType"];

				// prepare the value appropriately
				$result = "0";
				switch($datatype){
					case "nDataBigInt":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "dDataDate":
						if (!strtotime($value[$j])!=-1){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBoolean":
						if ($value[$j]=="T" || $value[$j]=="F" || $value[$j]=="0" || $value[$j]=="1"){
							switch($value){
								case "T":
									$value[$j] = "1";
									break;
								case "F":
									$value[$j] = "0";
									break;
							}
						} else {
							$result = "-4";
						}
						break;
					case "nDataFloat":
						if (!is_numeric($value[$j])){
							$result = "-4";
						}
						break;
					case "cDataVarchar":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "cDataMediumText":
						if (!is_string($value[$j])){
							$result = "-4";
						} else {
							$value[$j] = "'".$value[$j]."'";
						}
						break;
					case "bDataBlob":
						break;
				}

				if ($result>=0) {

					// make the SQL statement to search the document
					$k++;

					$sql = "SELECT DISTINCT pag.nPageId FROM ".$db_tableprefix."Pages pag"
						." INNER JOIN ".$db_tableprefix."PageTypeProperties ptp ON pag.nPageTypeId=ptp.nPageTypeId"
						." INNER JOIN ".$db_tableprefix."PageData pd ON (ptp.nPageTypePropertyId=pd.nPropertyId AND pd.nPageId=pag.nPageId)"
						." WHERE ptp.nPageTypePropertyId=".$propertyid[$j]." AND pd.".$datatype."=".$value[$j];

					// combine the results
					$result = mysql_query($sql,$con);
					if ($result!=false){
						if (mysql_num_rows($result)>0){
							unset($a_single_result);
							while ($row =@ mysql_fetch_array($result)){
								$a_single_result[] = $row["nPageId"];									
							}

							// intersect the single result into the overall results
							if ($k==1){
								$a_overall_result = $a_single_result;
							} else {
								$a_overall_result = array_intersect($a_overall_result,$a_single_result);
							}

						}
					} else {
						$result = "-2";
						break;
					}

				} else {
					$result = "-1";
					break;
				}

			} else {
				$result = "-3";
				break;
			}
		} else {
			$result = "-2";
			break;
		}

	}
	
	if(is_array($a_overall_result) && $result>=0){
		$result = implode(",",$a_overall_result);
	} else {
		$result = "";
	}
	
}


// output the result
if ($result!=""){
	print $result;
}

?>