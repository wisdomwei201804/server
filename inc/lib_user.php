<?php

/**
* ownCloud
*
* @author Frank Karlitschek 
* @copyright 2010 Frank Karlitschek karlitschek@kde.org 
* 
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either 
* version 3 of the License, or any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*  
* You should have received a copy of the GNU Lesser General Public 
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
* 
*/

if(!$CONFIG_INSTALLED){
	$_SESSION['user_id']=false;
	$_SESSION['username']='';
	$_SESSION['username_clean']='';
}

/**
 * Class for usermanagement
 *
 */
class OC_USER {
	
	/**
	* check if the login button is pressed and logg the user in
	*
	*/
	public static function loginlisener(){
		if(isset($_POST['loginbutton']) and isset($_POST['password']) and isset($_POST['login'])){
			if(OC_USER::login($_POST['login'],$_POST['password'])){
				OC_LOG::event($_SESSION['username'],1,'');
				return('');
			}else{
				return('error');
			} 
		}
		return('');
	}
	
	
	/**
	* try to create a new user
	*
	*/
	public static function createuser($username,$password){
		if(OC_USER::getuserid($username)!=0){
			return false;
		}else{
			$usernameclean=strtolower($username);
			$password=sha1($password);
			$username=OC_DB::escape($username);
			$usernameclean=OC_DB::escape($usernameclean);
			$query="INSERT INTO  `users` (`user_name` ,`user_name_clean` ,`user_password`) VALUES ('$username',  '$usernameclean',  '$password')";
			$result=OC_DB::query($query);
			return ($result)?true:false;
		}

	}
	
	/**
	* try to login a user
	*
	*/
	public static function login($username,$password){
		$password=sha1($password);
		$usernameclean=strtolower($username);
		$username=OC_DB::escape($username);
		$usernameclean=OC_DB::escape($usernameclean);
		$query="SELECT user_id FROM  users WHERE  user_name_clean =  '$usernameclean' AND  user_password =  '$password' LIMIT 1";
		$result=OC_DB::select($query);
		if(isset($result[0]) && isset($result[0]['user_id'])){
			$_SESSION['user_id']=$result[0]['user_id'];
			$_SESSION['username']=$username;
			$_SESSION['username_clean']=$usernameclean;
			return true;
		}else{
			return false;
		}
	}
	
	/**
	* check if the logout button is pressed and logout the user
	*
	*/
	public static function logoutlisener(){
		if(isset($_GET['logoutbutton']) && isset($_SESSION['username'])){
			OC_LOG::event($_SESSION['username'],2,'');
			$_SESSION['user_id']=false;
			$_SESSION['username']='';
			$_SESSION['username_clean']='';
		}
	}
	
	/**
	* check if a user is logged in
	*
	*/
	public static function isLoggedIn(){
		return (isset($_SESSION['user_id']) && $_SESSION['user_id'])?true:false;
	}
	
	/**
	* try to create a new group
	*
	*/
	public static function creategroup($groupname){
		if(OC_USER::getgroupid($groupname)==0){
			$groupname=OC_DB::escape($groupname);
			$query="INSERT INTO  `groups` (`group_name`) VALUES ('$groupname')";
			$result=OC_DB::query($query);
			return ($result)?true:false;
		}else{
			return false;
		}
	}
	
	/**
	* get the id of a user
	*
	*/
	public static function getuserid($username){
		$usernameclean=strtolower($username);
		$usernameclean=OC_DB::escape($usernameclean);
		$query="SELECT user_id FROM  users WHERE user_name_clean = '$usernameclean'";
		$result=OC_DB::select($query);
		if(!is_array($result)){
			return 0;
		}
		if(isset($result[0]) && isset($result[0]['user_id'])){
			return $result[0]['user_id'];
		}else{
			return 0;
		}
	}
	
	/**
	* get the id of a group
	*
	*/
	public static function getgroupid($groupname){
		$groupname=OC_DB::escape($groupname);
		$query="SELECT group_id FROM groups WHERE  group_name = '$groupname'";
		$result=OC_DB::select($query);
		if(!is_array($result)){
			return 0;
		}
		if(isset($result[0]) && isset($result[0]['group_id'])){
			return $result[0]['group_id'];
		}else{
			return 0;
		}
	}
	
	/**
	* get the name of a group
	*
	*/
	public static function getgroupname($groupid){
		$groupid=(integer)$groupid;
		$query="SELECT group_name FROM  groups WHERE  group_id =  '$groupid' LIMIT 1";
		$result=OC_DB::select($query);
		if(isset($result[0]) && isset($result[0]['group_name'])){
			return $result[0]['group_name'];
		}else{
			return 0;
		}
	}
	
	/**
	* check if a user belongs to a group
	*
	*/
	public static function ingroup($username,$groupname){
		$userid=OC_USER::getuserid($username);
		$groupid=OC_USER::getgroupid($groupname);
		if($groupid>0 and $userid>0){
			$query="SELECT * FROM  user_group WHERE group_id = '$groupid'  AND user_id = '$userid';";
			$result=OC_DB::select($query);
			if(isset($result[0]) && isset($result[0]['user_group_id'])){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	* add a user to a group
	*
	*/
	public static function addtogroup($username,$groupname){
		if(!OC_USER::ingroup($username,$groupname)){
			$userid=OC_USER::getuserid($username);
			$groupid=OC_USER::getgroupid($groupname);
			if($groupid!=0 and $userid!=0){
				$query="INSERT INTO `user_group` (`user_id` ,`group_id`) VALUES ('$userid',  '$groupid');";
				$result=OC_DB::query($query);
				if($result){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return true;
		}
	}
	
	public static function generatepassword(){
		return uniqid();
	}
	
	/**
	* get all groups the user belongs to
	*
	*/
	public static function getusergroups($username){
		$userid=OC_USER::getuserid($username);
		$query="SELECT group_id FROM  user_group WHERE  user_id =  '$userid'";
		$result=OC_DB::select($query);
		$groups=array();
		if(is_array($result)){
			foreach($result as $group){
				$groupid=$group['group_id'];
				$groups[]=OC_USER::getgroupname($groupid);
			}
		}
		return $groups;
	}
	
	/**
	* set the password of a user
	*
	*/
	public static function setpassword($username,$password){
		$password=sha1($password);
		$userid=OC_USER::getuserid($username);
		$query="UPDATE  users SET  user_password = '$password' WHERE  user_id ='$userid'";
		$result=OC_DB::query($query);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	* check the password of a user
	*
	*/
	public static function checkpassword($username,$password){
		$password=sha1($password);
		$usernameclean=strtolower($username);
		$username=OC_DB::escape($username);
		$usernameclean=OC_DB::escape($usernameclean);
		$query="SELECT user_id FROM  'users' WHERE  user_name_clean =  '$usernameclean' AND  user_password =  '$password' LIMIT 1";
		$result=OC_DB::select($query);
		if(isset($result[0]) && isset($result[0]['user_id']) && $result[0]['user_id']>0){
			return true;
		}else{
			return false;
		}
	}
}

?>