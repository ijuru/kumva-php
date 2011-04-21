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
 * Purpose: Notifications class
 */
 
/**
 * Class for sending notifications to users
 */
class Notifications { 
	/**
	 * Notifies admins of new website feedback
	 * @param string name the name from the feedback form
	 * @param string email the email address from the feedback form
	 * @param string feedback the feedback comment from the feedback form
	 * @return bool TRUE if successful, else FALSE 
	 */
	public static function newFeedback($name, $email, $feedback) {
		$subject = 'Feedback received';
		$message = "NAME: $name\r\nEMAIL: $email\r\nMESSAGE: $feedback";
		
		return Mailer::sendToAdmins($subject, $message, $email ? $email : NULL);
	}
	
	/**
	 * Notifies subscribers of new change 
	 * @param Change the change
	 * @return bool TRUE if successful, else FALSE 
	 */
	public static function newChange($change) {
		$subject = 'New change: '.$change->toString();
		
		$message = 'New change: '.$change->toString().' added by '.$change->getSubmitter()->getName()."\n";
		$message .= "----------------\n";
		$message .= 'To view this change, go to '.KUMVA_URL_ROOT.'/admin/change.php?id='.$change->getId();
		
		$users = self::getUsersWithSubscription(Subscription::NEW_CHANGE);
			
		return Mailer::sendToUsers($users, $subject, $message);
	}
	
	/**
	 * Notifies watchers of the given change of a new comment
	 * @param Change the change
	 * @param Comment the new comment
	 * @return bool TRUE if successful, else FALSE 
	 */
	public static function newComment($change, $comment) {
		$subject = ($comment->isApproval() ? 'New approval of change' : 'New comment on change').' '.$change->toString();
		
		$message = $comment->getUser()->getName().' ('.$comment->getUser()->getLogin().') ';
		
		if ($comment->isApproval())
			$message .= "approved the change and added the following comment:\n";
		else
			$message .= "added the following comment:\n";
		
		$message .= "----------------\n";
		$message .= $comment->getText()."\n";
		$message .= "----------------\n";
		
		$message .= 'To view this change or respond to the comment, go to '.KUMVA_URL_ROOT.'/admin/change.php?id='.$change->getId();
		
		$users1 = $change->getWatchers();
		$users2 = self::getUsersWithSubscription(Subscription::NEW_COMMENT);
		$users = self::removeCurrentUser(Entity::union($users1, $users2));
		
		return Mailer::sendToUsers($users, $subject, $message);
	}
	
	/**
	 * Notifies watchers of the given change that it has been accepted
	 * @param Change the change
	 * @return bool TRUE if successful, else FALSE 
	 */
	public static function changeAccepted($change) {
		$subject = 'Change '.$change->toString().' was accepted';
		
		$message = "Change ".$change->toString()." was accepted.\n";
		$message .= "----------------\n";
		$message .= 'To view this change go to '.KUMVA_URL_ROOT.'/admin/change.php?id='.$change->getId();
		
		$users1 = $change->getWatchers();
		$users2 = self::getUsersWithSubscription(Subscription::CHANGE_RESOLVED);
		$users = self::removeCurrentUser(Entity::union($users1, $users2));
			
		return Mailer::sendToUsers($users, $subject, $message);
	}
	
	/**
	 * Notifies watchers of the given change that it has been rejected
	 * @param Change the change
	 * @return bool TRUE if successful, else FALSE 
	 */
	public static function changeRejected($change, $comment) {
		$subject = 'Change '.$change->toString().' was rejected';
		
		$message = "Change ".$change->toString()." was rejected with the following comment:\n";
		$message .= "----------------\n";
		$message .= $comment->getText()."\n";
		$message .= "----------------\n";
		$message .= 'To view this change go to '.KUMVA_URL_ROOT.'/admin/change.php?id='.$change->getId();
		
		$users1 = $change->getWatchers();
		$users2 = self::getUsersWithSubscription(Subscription::CHANGE_RESOLVED);
		$users = self::removeCurrentUser(Entity::union($users1, $users2));
		
		return Mailer::sendToUsers($users, $subject, $message);
	}
	
	/**
	 * Gets the list of users with the given subscription, minus the current user
	 * @param int the subscription id
	 */
	private static function getUsersWithSubscription($subscriptionId) {
		$subscription = Dictionary::getUserService()->getSubscription($subscriptionId);
		return self::removeCurrentUser(Dictionary::getUserService()->getUsersWithSubscription($subscription));
	}
	
	/**
	 * Removes the current user from an array of users
	 * @param array users the array of users
	 * @return array the new array
	 */
	private static function removeCurrentUser(&$users) {
		$curUser = Session::getCurrent()->getUser();
		$new = array();
		foreach ($users as $user) {
			if (!$user->equals($curUser))
				$new[] = $user;
		}
		return $new;
	}
}
 
?>
