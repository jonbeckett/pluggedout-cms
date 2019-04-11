<?php
/*
#===========================================================================
#= Project: CMS Content Management System
#= File   : dms.php
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

// parameters : filename - the filename that already exists in the pickup area
function store_file($documentid,$filename,$repositoryid){
		
	global $db_tableprefix;
	
	if ($documentid!="" && $filename!="" && $repositoryid!=""){

		$con = db_connect();

		$sql = "SELECT cPath FROM ".$db_tableprefix."Repository"
			." WHERE nRepositoryId=".$repositoryid;
			
		$result = mysql_query($sql,$con);
		if ($result!=false){
			if (mysql_num_rows($result)>0){
				$rep_row = mysql_fetch_array($result);
				
				$path = $rep_row["cPath"];
				
				$source_file = realpath($filename);
				$destination_file = $path."/".$documentid;
				
				// make an entry in the RepositoryInventory table
				//$sql = "INSERT INTO ".$db_tableprefix."RepositoryInventory (nDocumentId,cFullPath) VALUES (".$documentid.",'".$destination_file."')";
				//$result = mysql_query($sql,$con);
				
				// update the document record with the filename it has been given too
				$sql = "UPDATE ".$db_tableprefix."Document SET cFilename='".mysql_escape_string($destination_file)."' WHERE nDocumentId=".$documentid;
								
				// move the file to the destination
				$result = copy($source_file,$destination_file);
				if ($result!=false){
					// change permission on file
					$old = umask(0);
					chmod($destination_file, 0755);
					umask($old);
					$result = $destination_file;
					
					// kill the original file
					$result = unlink($source_file);
					
					if ($result!=false){
						// success
						$result = "1";
					} else {
						// could not remove old file
						$result = "-6";
					}
				} else {
					// could not copy file
					$result = "-7";
				}
				
			} else {
				// could not find repository
				$result = "-3";
			}
		} else {
			// repository sql packed up
			$result = "-2";
		}

	} else {
		// missing parameters
		$result = "-1";
	}
	
	return $result;
	
}

?>