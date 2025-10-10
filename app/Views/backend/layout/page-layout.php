<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8" />
	<title><?= isset($pageTitle) ? $pageTitle : "New Page Title" ?></title>

	<!-- Site favicon -->
	<link
		rel="apple-touch-icon"
		sizes="180x180"
		href="/backend/vendors/images/apple-touch-icon.png" />
	<link
		rel="icon"
		type="image/png"
		sizes="32x32"
		href="/backend/vendors/images/favicon-32x32.png" />
	<link
		rel="icon"
		type="image/png"
		sizes="16x16"
		href="/backend/vendors/images/favicon-16x16.png" />

	<!-- Mobile Specific Metas -->
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link
		href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
	<link
		rel="stylesheet"
		type="text/css"
		href="/backend/vendors/styles/icon-font.min.css" />
	<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />

	<link rel="stylesheet" href="<?= base_url('util/ijaboCropTool/ijaboCropTool.min.css') ?>">

	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	

	<!-- customer style -->
	<?= $this->renderSection('stylesheets') ?>
</head>

<body>
	<!-- <div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="/backend/vendors/images/deskapp-logo.svg" alt="" />
				</div>
				<div class="loader-progress" id="progress_div">
					<div class="bar" id="bar1"></div>
				</div>
				<div class="percent" id="percent1">0%</div>
				<div class="loading-text">Loading...</div>
			</div>
		</div> -->

	<?= $this->include('backend/inc/header') ?>

	<?= $this->include('backend/inc/right-sidebar') ?>

	<?= $this->include('backend/inc/left-side-bar') ?>

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<?= $this->renderSection('content'); ?>
			</div>
		</div>
	</div>

	<!-- js -->
	<script src="/backend/vendors/scripts/core.js"></script>
	<script src="/backend/vendors/scripts/script.min.js"></script>
	<script src="/backend/vendors/scripts/process.js"></script>
	<script src="/backend/vendors/scripts/layout-settings.js"></script>

	<script src="<?= base_url('util/ijaboCropTool/ijaboCropTool.min.js') ?>"></script>

	<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

	<?= $this->renderSection('script') ?>
</body>

</html>