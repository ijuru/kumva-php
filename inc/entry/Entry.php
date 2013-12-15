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
 * Purpose: Entry class
 */
 
/**
 * Media type flags
 */
class Media extends Enum {
	const AUDIO = 0;
	const IMAGE = 1;
	
	protected static $strings = array('audio', 'image');
	protected static $localized = array(KU_STR_AUDIO, KU_STR_IMAGE);
}

/**
 * Dictionary entry class
 */
class Entry extends Entity implements JsonSerializable {
	private $media;
	
	// Lazy loaded properties
	private $head;
	private $revisions;
	
	/**
	 * Constructs an entry
	 * @param int id the id
	 * @param int media the media flags
	 */
	public function __construct($id = 0, $media = 0) {
		$this->id = (int)$id;
		$this->media = (int)$media;
	}
	
	/**
	 * Creates an entry from the given row of database columns
	 * @param array the associative array
	 * @return Entry the entry
	 */
	public static function fromRow(&$row) {
		return new Entry($row['entry_id'], $row['media']);
	}
	
	/**
	 * Gets the media flags
	 * @return int the media flags
	 */
	public function getMedia() {
		return $this->media;
	}
	
	/**
	 * Sets the media flags
	 * @param int media the media flags
	 */
	public function setMedia($media) {
		$this->media = $media;
	}
	
	/**
	 * Gets if the media type exists for this entry
	 * @param int type the media type
	 * @return bool true if media type exists
	 */
	public function hasMedia($type) {
		return aka_getbit($this->media, $type);
	}
	
	/**
	 * Gets the head revision using lazy loading
	 * @return Revision the head revision
	 */
	public function getHead() {
		if (!$this->head)
			$this->head = Dictionary::getEntryService()->getEntryRevision($this, RevisionPreset::HEAD);
		
		return $this->head;
	}
	
	/**
	 * Gets all the revisions using lazy loading
	 * @return array the revisions
	 */
	public function getRevisions() {
		if ($this->revisions === NULL)
			$this->revisions = Dictionary::getEntryService()->getEntryRevisions($this);
		
		return $this->revisions;
	}
	
	/**
	 * Gets whether this entry has been deleted - i.e. it's headless
	 * @return bool TRUE if entry has been deleted
	 */
	public function isDeleted() {
		return !$this->getHead();
	}

	/**
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize() {
		$media = array();
		if ($this->hasMedia(Media::AUDIO)) {
			$media['audio'] = KUMVA_URL_MEDIA.'/audio/'.($this->id).'.mp3';
		}
		if ($this->hasMedia(Media::IMAGE)) {
			$media['audio'] = KUMVA_URL_MEDIA.'/image/'.($this->id).'.jpg';
		}

		return array_merge([ 'id' => $this->id ], $this->getHead()->jsonSerialize(), [ 'media' => $media ]);
	}
}

?>
