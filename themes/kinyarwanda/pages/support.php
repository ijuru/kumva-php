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
 * Purpose: About page
 */
?>
<p>This is a non-profit project made by volunteers. However there are costs in maintaining a website like this, and your donation will help us keep this project 
going forward and improving.</p>

<div align="center">
	<p>We can accept donations through Bitcoin:</p>

	<script type="text/javascript" src="https://blockchain.info//Resources/wallet/pay-now-button.js"></script>

	<div style="font-size:16px;margin:0 auto;width:300px" class="blockchain-btn"
	     data-address="1B9H7wC5mNHjtYEsLyEy5zwtk49GecD6XG"
	     data-shared="false">
		<div class="blockchain stage-begin">
			<img src="https://blockchain.info//Resources/buttons/donate_64.png"/>
		</div>
		<div class="blockchain stage-loading" style="text-align:center">
			<img src="https://blockchain.info//Resources/loading-large.gif"/>
		</div>
		<div class="blockchain stage-ready">
			<p align="center">Please Donate To Bitcoin Address: <b>[[address]]</b></p>
			<p align="center" class="qr-code"></p>
		</div>
		<div class="blockchain stage-paid">
			Donation of <b>[[value]] BTC</b> Received. Thank You.
		</div>
		<div class="blockchain stage-error">
			<font color="red">[[error]]</font>
		</div>
	</div>

	<p>And PayPal:</p>

	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="MUZRRKQPGQXME">
		<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>