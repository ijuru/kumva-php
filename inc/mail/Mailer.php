<?php
/**
 * This file is part of Kumva.
 *
 * Kumva is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Kumva is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kumva.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright Rowan Seymour 2010
 * 
 * Purpose: Mailer class
 */
 
/**
 * Class for sending emails
 */
class Mailer { 
	const SYSTEM_NAME = 'Kumva';
	const SYSTEM_ADDRESS = 'kumva-no-reply@ijuru.com';
	
	/**
	 * Sends an email to all users with administrator role
	 * @param string subject the email subject
	 * @param string message the email message
	 * @param string from the from address
	 * @return bool TRUE if successfull, else FALSE
	 */
	public static function sendToAdmins($subject, $message, $replyTo = self::SYSTEM_ADDRESS){
		$adminRole = Dictionary::getUserService()->getRole(Role::ADMINISTRATOR);
		$users = Dictionary::getUserService()->getUsersWithRole($adminRole);
		return self::sendToUsers($users, $subject, $message, $replyTo);
	}
	
	/**
	 * Sends an email to the given users
	 * @users array the users
	 * @param string subject the email subject
	 * @param string message the email message
	 * @param string from the from address
	 * @return bool TRUE if successfull, else FALSE
	 */
	public static function sendToUsers($users, $subject, $message, $replyTo = self::SYSTEM_ADDRESS){
		foreach ($users as $user)
			self::send($user->getEmail(), $subject, $message, $replyTo);
		
		return TRUE;
	}

	/**
	 * Sends an email to the specified email address
	 * @param string recipient the email recipient address
	 * @param string subject the email subject
	 * @param string message the email message
	 * @return bool TRUE if successfull, else FALSE
	 */
	public static function send($recipient, $subject, $message, $replyTo = self::SYSTEM_ADDRESS){
		$headers = "From: ".self::SYSTEM_NAME." <".self::SYSTEM_ADDRESS.">\r\n";
		$headers .= "X-Mailer: PHP/".phpversion()."\r\n";
		$headers .= "Reply-To: ".$replyTo;
		$params = "-f".self::SYSTEM_ADDRESS;

		return mail($recipient, $subject, $message, $headers, $params);
	}
}
 
?>
