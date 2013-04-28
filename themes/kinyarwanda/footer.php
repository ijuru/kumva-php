			</div><!-- End of content -->
		</div><!-- End of wrapper for sticky footer -->
		
		<div id="footer">
			<div style="float: left">
				<?php Templates::icon('email'); echo '&nbsp;&nbsp;'.kumva_pagelink('feedback'); ?>
				&nbsp;&nbsp;
				|
				&nbsp;&nbsp;
				<?php Templates::icon('android'); ?>&nbsp;
				<a href="https://market.android.com/details?id=com.ijuru.kumva"><?php echo KU_STR_ANDROIDAPP; ?></a>
				&nbsp;&nbsp;
				|
				&nbsp;&nbsp;
				<?php Templates::icon('sms'); ?>&nbsp;
				<?php echo kumva_pagelink('sms-service'); ?>
				&nbsp;&nbsp;
				|
				&nbsp;&nbsp;
				<?php Templates::icon('support'); ?>&nbsp;
				<?php echo kumva_pagelink('support'); ?>
			</div>
			
			<div style="float: right">
				<i><?php echo KU_STR_POWEREDBY; ?></i>
				&nbsp;&nbsp;		
				<a href="https://github.com/ijuru/kumva-php" title="Powered by Kumva Dictionary Software">
					<img alt="Kumva" style="border-width:0; vertical-align: middle" src="gfx/button.png" />
				</a>
				<a rel="license" title="Content released under Creative Commons License" href="http://creativecommons.org/licenses/by-nc-sa/2.0/uk/">
					<img alt="Creative Commons License" style="border-width:0; vertical-align: middle" src="http://i.creativecommons.org/l/by-nc-sa/2.0/uk/80x15.png" />
				</a>
			</div>
		</div>

		<!-- Google Analytics -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-8334296-4']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
			
		<!-- Google +1 Button -->
		<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
		
		<!-- Facebook XFBML for Like button -->
		<script src="http://connect.facebook.net/en_US/all.js#appId=229770417033221&amp;xfbml=1"></script>
 	</body>
</html>
