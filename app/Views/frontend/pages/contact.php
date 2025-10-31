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
    <div class="col-12">
        <div class="breadcrumbs mb-4"> <a href="<?= route_to('blog.home') ?>">Home</a>
            <span class="mx-1">/</span> <a href="#!">Contact</a>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="pr-0 pr-lg-4">
            <div class="content"><?= get_setting()->blog_description ?>
                <div class="mt-5">
                    <p class="h3 mb-3 font-weight-normal"><a class="text-dark" href="mailto:<?= get_setting()->blog_email ?>"><?= get_setting()->blog_email ?></a>
                    </p>
                    <p class="mb-3"><a class="text-dark" href="tel:<?= get_setting()->blog_phone ?>"><?= get_setting()->blog_phone ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- hiển thị ra hết lỗi khi rederect về lại -->
    <?php //validation_list_errors() 
    ?>
    <div class="col-lg-6 mt-4 mt-lg-0">
        <form method="POST" action="<?= route_to('blog.contact.submit') ?>" class="row">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <input type="text" class="form-control mb-2" placeholder="Name" name="name" id="name" value="<?= old('name') ?>">
                <?php if ($error = session()->getFlashdata('errors')) : ?>
                    <?php if (isset($error['name'])): ?>
                        <span class="text-danger error-text"><?= $error['name'] ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <input type="email" class="form-control mb-2" placeholder="Email" name="email" id="email" value="<?= old('email') ?>">
                <?php if ($error = session()->getFlashdata('errors')) : ?>
                    <?php if (isset($error['email'])): ?>
                        <span class="text-danger error-text"><?= $error['email'] ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-12">
                <input type="text" class="form-control mb-2" placeholder="Subject" name="subject" id="subject" value="<?= old('subject') ?>">
                <?php if ($error = session()->getFlashdata('errors')) : ?>
                    <?php if (isset($error['subject'])): ?>
                        <span class="text-danger error-text"><?= $error['subject'] ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-12">
                <textarea name="message" id="message" class="form-control mb-2" placeholder="Type You Message Here" rows="5"><?= old('message') ?></textarea>
                <?php if ($error = session()->getFlashdata('errors')) : ?>
                    <?php if (isset($error['message'])): ?>
                        <span class="text-danger error-text"><?= $error['message'] ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-12">
                <button class="btn btn-outline-primary" type="submit">Send Message</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>