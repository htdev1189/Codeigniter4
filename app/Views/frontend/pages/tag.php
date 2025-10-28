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

<!-- content -->
<?= $this->section('content') ?>
<div class="row">

    <div class="col-12">
        <div class="breadcrumbs mb-4">
            <a href="index.html">Home</a>
            <a href="index.html">Tag</a>
            <a href="index.html">Tag title</a>
        </div>
        <h1 class="mb-4 border-bottom border-primary d-inline-block"><?= $pageTitle ?></h1>
    </div>


    <div class="col-lg-8 mb-5 mb-lg-0">
        <?php if (count($posts) > 0) : ?>

            <div class="row">
                <?php foreach ($posts as $post): ?>

                    <div class="col-md-6 mb-4">
                        <article class="card article-card article-card-sm h-100">
                            <a href="<?= route_to('blog.post.read', $post->slug) ?>">
                                <div class="card-image">
                                    <div class="post-info"> <span class="text-uppercase"><?= formatDate($post->created_at, 'd M Y') ?></span>
                                        <span class="text-uppercase"><?= get_reading_time($post->content) ?></span>
                                    </div>
                                    <img loading="lazy" decoding="async" src="<?= base_url('uploads/posts/' . $post->featured_image) ?>" alt="Post Thumbnail" class="w-100" width="420" height="280">
                                </div>
                            </a>
                            <div class="card-body px-0 pb-0">
                                <?php if ($post->tags != ''): ?>
                                    <ul class="post-meta mb-2">
                                        <li>
                                            <?php foreach (explode(',', $post->tags) as $tag): ?>
                                                <a href="<?= route_to('blog.tags', urlencode($tag)) ?>"><?= $tag ?></a>
                                            <?php endforeach; ?>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                                <h2><a class="post-title" href="<?= route_to('blog.post.read', $post->slug) ?>"><?= $post->title ?></a></h2>
                                <p class="card-text"><?= limit_content($post->meta_description, 100) ?></p>
                                <div class="content"> <a class="read-more-btn" href="<?= route_to('blog.post.read', $post->slug) ?>">Read Full Article</a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- pagination -->
            <?php if ($pager && $pager->getPageCount('tags') > 1): ?>
                <?= $pager->links('tags', 'default_cat') ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>


    <div class="col-lg-4">
        <div class="widget-blocks">
            <div class="row">
                <div class="col-lg-12">
                    <?= $this->include('frontend/inc/author') ?>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?= $this->include('frontend/inc/random-post') ?>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?= $this->include('frontend/inc/sidebar_categories') ?>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?= $this->include('frontend/inc/sidebar_tags') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>