<!-- footer -->
		<div class="footer">
			<p>© <?= date('Y') ?> DevLess . All Rights Reserved . Design from <a href="http://w3layouts.com/">W3layouts</a></p>
		</div>
		<!-- //footer -->
	</section>
	<script src="<?= DvAssetPath($payload, 'js/bootstrap.js') ?>"></script>
	<script src="<?= DvAssetPath($payload, 'js/ace.js') ?>"></script>
	<script src="<?= DvAssetPath($payload, 'js/mode-json.js') ?>"></script>
	<script src="<?= DvAssetPath($payload, 'js/worker-json.js') ?>"></script>
    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
    <script src="<?= DvAssetPath($payload, 'js/custom.js') ?>"></script>
    <!-- DevLess JS SDK -->
    <?=DvJSSDK()?>
</body>
</html>
