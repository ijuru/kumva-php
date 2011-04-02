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
 * Purpose: Administration AJAX interface
 */

include_once '../inc/kumva.php';

$action = Request::getPostParam('action', NULL);
$targetId = (int)Request::getPostParam('targetId', 0);

function ajax_result($result) {
	// Print JSON encoded result
	echo json_encode($result);
	exit;
}

switch ($action) {
case 'delete-comment':
	ajax_result(AJAXEvents::deleteComment($targetId));
//case 'queries-since':
	//ajax_result(AJAXEvents::getQueriesSince());
}

class AJAXEvents {
	public static function deleteComment($commentId) {
		Session::requireRole(Role::ADMINISTRATOR);	
		return Dictionary::getChangeService()->voidComment($commentId);	
	}
}

?>
