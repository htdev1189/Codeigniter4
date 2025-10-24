<?= $this->extend('frontend/layout/frontend'); ?>
<!-- meta -->
<?= $this->section('page_meta') ?>
<meta type="robots" content="index, follow">
<meta name="description" content="<?= get_setting()->blog_description ?>">
<meta name="keywords" content="<?= get_setting()->blog_keywords ?>">
<meta name="title" content="<?= get_setting()->blog_title ?>">
<link rel="canonical" href="<?= base_url() ?>" />

<meta property="og:title" content="<?= get_setting()->blog_title ?>" />
<meta property="og:description" content="<?= get_setting()->blog_description ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?= base_url() ?>" />
<meta property="og:image" content="<?= base_url('frontend/images/logo.png') ?>" />
<meta property="og:site_name" content="<?= get_setting()->blog_title ?>" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?= get_setting()->blog_title ?>" />
<meta name="twitter:description" content="<?= get_setting()->blog_description ?>" />
<meta name="twitter:image" content="<?= base_url('frontend/images/logo.png') ?>" />

<?= $this->endSection(); ?>


<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-10 mx-auto text-center">
        <img loading="lazy" decoding="async" src="<?= base_url('frontend/images/404.png') ?>" alt="404" class="img-fluid mb-4" width="500" height="350">
        <h1 class="mb-4">Page Not Found!</h1>
        <a href="<?= route_to('blog.home') ?>" class="btn btn-outline-primary">Back To Home</a>
    </div>
</div>
<?= $this->endSection() ?>