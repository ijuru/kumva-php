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
 * Purpose: Comment class
 */
 
/**
 * Comment on a change being made to the dictionary
 */
class Comment extends Entity {
	private $userId;
	private $created;
	private $approval;
	private $text;
	
	// Lazy loaded properties
	private $user;
	
	/**
	 * Constructs a comment
	 * @param int id the comment id
	 * @param int userId the user id
	 * @param int created the created timestamp
	 * @param bool approval TRUE if comment is just an approval
	 * @param string text the comment text
	 * @param bool voided TRUE if comment is voided
	 */
	public function __construct($id, $userId, $created, $approval, $text, $voided = FAlSE) {
		$this->id = (int)$id;
		$this->userId = (int)$userId;
		$this->created = (int)$created;
		$this->approval = (bool)$approval;
		$this->text = $text;
		$this->voided = (bool)$voided;
	}
	
	/**
	 * Creates a comment from the given row of database columns
	 * @param array the associative array
	 * @return Comment the comment
	 */
	public static function fromRow(&$row) {
		return new Comment($row['comment_id'], $row['user_id'], aka_timefromsql($row['created']), $row['approval'], $row['text'], $row['voided']);
	}
	
	/**
	 * Gets the user
	 * @return User the user
	 */
	public function getUser() {
		if (!$this->user)
			$this->user = Dictionary::getUserService()->getUser($this->userId);
		
		return $this->user;
	}
	
	/**
	 * Gets the created timestamp
	 * @return int the created timestamp
	 */
	public function getCreated() {
		return $this->created;
	}
	
	/**
	 * Gets the approval flag
	 * @return bool TRUE if comment is just an approval
	 */
	public function isApproval() {
		return $this->approval;
	}
	
	/**
	 * Sets the approval flag
	 * @param bool approval TRUE if comment is just an approval
	 */
	public function setApproval($approval) {
		$this->approval = $approval;
	}
	
	/**
	 * Gets the text
	 * @return string the text
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * Sets the text
	 * @param string text the text
	 */
	public function setText($text) {
		$this->text = $text;
	}
}

?>
