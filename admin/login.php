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
 * Purpose: Suggest words page
 */
 
include_once '../inc/kumva.php';

$login = Request::getPostParam('login');
$password = Request::getPostParam('password');

if ($login != '' && $password != '') {
	if (Session::getCurrent()->login($login, $password)) {
		$ref = Request::getGetParam('ref');
		Request::redirect(($ref != '') ? $ref : KUMVA_URL_ROOT.'/admin/index.php');
	}
	else
		Session::getCurrent()->setAttribute('login_message', 'Invalid username or password');
}
elseif (isset($_GET['logout']))
	Session::getCurrent()->logout();
	
include_once 'tpl/header.php';
?>
<script type="text/javascript">
/* <![CDATA[ */
function loginSubmit(form) {
	$('#password').val(aka_md5($('#dummy_p').val()));	// MD5 encrypt the password and store in hidden field	
	return true;
}

// Add keypress handler to submit form on ENTER
$(document).ready(function() {
	$('.submitonenter').keypress(function(e) {
		if (e.which == 13) {
			$('#loginform').submit();
			return false;
		}
	});
});
/* ]]> */
</script>
<?php
$msg = Session::getCurrent()->getAttribute('login_message');
if ($msg != NULL)
	echo '<div class="error">'.$msg.'</div>';
Session::getCurrent()->removeAttribute('login_message');
?>	
<div style="text-align: center; padding: 20px">
	
	<form id="loginform" method="post" action="" onsubmit="return loginSubmit(this);">			
		<?php echo KU_STR_USERNAME; ?><br/>
		<input type="text" name="login" style="width: 200px" value="<?php echo $login; ?>" class="submitonenter" /><br/><br/>
		<?php echo KU_STR_PASSWORD; ?><br/>
		<input type="hidden" id="password" name="password" /><input id="dummy_p" style="width: 200px" type="password" class="submitonenter" /><br/><br/>
		<?php Templates::button('login', "aka_submit(this)", KU_STR_LOGIN); ?>
	</form>
</div>
	
<?php include_once 'tpl/footer.php'; ?>
