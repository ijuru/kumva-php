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
 * Initialize Swiftmailer to use Amazon SES if credentials are defined. Otherwise default to PHP mail()
 */
if (defined('KUMVA_AWS_ACCESS_KEY') && defined('KUMVA_AWS_SECRET_KEY'))
	$swift_transport = Swift_AWSTransport::newInstance(KUMVA_AWS_ACCESS_KEY, KUMVA_AWS_SECRET_KEY);
else
	$swift_transport = Swift_MailTransport::newInstance();

$swift_mailer = Swift_Mailer::newInstance($swift_transport);

/**
 * Class for sending emails
 */
class Mailer {
	
	/**
	 * Sends an email to all users with administrator role
	 * @param string subject the email subject
	 * @param string message the email message
	 * @param string from the from address
	 * @return bool TRUE if successfull, else FALSE
	 */
	public static function sendToAdmins($subject, $message, $replyTo = null){
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
	public static function sendToUsers($users, $subject, $message, $replyTo = null){
		foreach ($users as $user)
			self::send($user->getEmail(), $subject, $message, $replyTo);
		
		return true;
	}

	/**
	 * Sends an email to the specified email address
	 * @param string recipient the email recipient address
	 * @param string subject the email subject
	 * @param string body the email body
	 * @return bool TRUE if successfull, else FALSE
	 */
	public static function send($recipient, $subject, $body, $replyTo = null){
		global $swift_mailer;
		
		$message = Swift_Message::newInstance();
		$message->setFrom(array(KUMVA_MAILER_SYSTEM_ADDRESS => KUMVA_MAILER_SYSTEM_NAME));
		$message->setTo(array($recipient));
		$message->setSubject($subject);
		$message->setBody($body);

		if (!is_null($replyTo))
			$message->setReplyTo($replyTo);

		return $swift_mailer->send($message);
	}
}
 
?>
