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
            <?php if (count($breadcrumbs) > 0): ?>
                <?php foreach ($breadcrumbs as $crumb): ?>
                    <span class="mx-1">/</span> <a href="<?= route_to('blog.category.read', $crumb['slug']) ?>"><?= $crumb['name'] ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
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
                                <ul class="post-meta mb-2">
                                    <li> <a href="#!">travel</a>
                                        <a href="#!">news</a>
                                    </li>
                                </ul>
                                <h2><a class="post-title" href="<?= route_to('blog.post.read', $post->slug) ?>"><?= $post->title ?></a></h2>
                                <p class="card-text"><?= limit_content($post->meta_description, 100) ?></p>
                                <div class="content"> <a class="read-more-btn" href="<?= route_to('blog.post.read', $post->slug) ?>">Read Full Article</a>
                                </div>
                            </div>
                        </article>
                    </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>


    <div class="col-lg-4">
        <div class="widget-blocks">
            <div class="row">
                <div class="col-lg-12">
                    <div class="widget">
                        <div class="widget-body">
                            <img loading="lazy" decoding="async" src="<?= base_url('frontend/images/author.jpg') ?>" alt="About Me" class="w-100 author-thumb-sm d-block">
                            <h2 class="widget-title my-3">Hootan Safiyari</h2>
                            <p class="mb-3 pb-2">Hello, I’m Hootan Safiyari. A Content writter, Developer and Story teller. Working as a Content writter at CoolTech Agency. Quam nihil …</p> <a href="about.html" class="btn btn-sm btn-outline-primary">Know
                                More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-6">
                    <div class="widget">
                        <h2 class="section-title mb-3">Random Post</h2>
                        <div class="widget-body">
                            <div class="widget-list">
                                <!-- random post -->
                                <?php if (count(get_random_posts(2))): ?>
                                    <?php foreach (get_random_posts(2) as $randomPost): ?>
                                        <a class="media align-items-center" href="<?= route_to('blog.post.read', $randomPost->slug) ?>">
                                            <img loading="lazy" decoding="async" src="<?= base_url('uploads/posts/' . $randomPost->featured_image) ?>" alt="Post Thumbnail" class="w-100">
                                            <div class="media-body ml-3">
                                                <h3 style="margin-top:-5px" title="<?= $randomPost->title ?>"><?= limit_content($randomPost->title, 20) ?></h3>
                                                <p class="mb-0 small"><?= limit_content($randomPost->content, 50) ?></p>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?= $this->include('frontend/inc/sidebar_categories') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/monokai.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script>
    hljs.highlightAll();
</script>


<?= $this->endSection(); ?>