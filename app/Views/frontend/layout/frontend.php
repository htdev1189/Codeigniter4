<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

<html lang="en-us">

<head>
    <meta charset="utf-8">
    <title><?= isset($pageTitle) ? $pageTitle : 'New page title' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <!-- token -->
    <meta name="<?= csrf_token(); ?>" content="<?= csrf_hash() ?>">

    <!-- seo -->
    <?= $this->renderSection('page_meta'); ?>

    <link rel="icon" href="images/blog/<?= get_setting()->blog_favicon ?>" type="image/x-icon">

    <!-- theme meta -->
    <meta name="theme-name" content="reporter" />

    <!-- # Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Neuton:wght@700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- # CSS Plugins -->
    <link rel="stylesheet" href="<?= base_url('frontend/plugins/bootstrap/bootstrap.min.css') ?>">

    <!-- # Main Style Sheet -->
    <link rel="stylesheet" href="<?= base_url('frontend/css/style.css') ?>">
</head>

<body>

    <?= $this->include('frontend/inc/header') ?>

    <main>
        <section class="section">
            <div class="container">
                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </main>

    <?= $this->include('frontend/inc/footer') ?>


    <!-- # JS Plugins -->
    <script src="<?= base_url('frontend/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('frontend/plugins/bootstrap/bootstrap.min.js') ?>"></script>

    <!-- Main Script -->
    <script <?= base_url('frontend/js/script.js') ?>"></script>

</body>

</html>