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
    <div class="col-lg-8 mb-5 mb-lg-0">
        <article>
            <img loading="lazy" decoding="async" src="<?= base_url('uploads/posts/' . get_post_by_slug($post['slug'])->featured_image) ?>" alt="Post Thumbnail" class="w-100">
            <ul class="post-meta mb-2 mt-4">
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="margin-right:5px;margin-top:-4px" class="text-dark" viewBox="0 0 16 16">
                        <path d="M5.5 10.5A.5.5 0 0 1 6 10h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"></path>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"></path>
                        <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z"></path>
                    </svg> <span>29 May, 2021</span>
                </li>
            </ul>
            <h1 class="my-3"><?= get_post_by_slug($post['slug'])->title ?></h1>
            <ul class="post-meta mb-4">
                <li> <a href="/categories/destination">destination</a>
                </li>
            </ul>
            <div class="content text-left">
                <?= get_post_by_slug($post['slug'])->content ?>
            </div>
        </article>

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
<script>hljs.highlightAll();</script>


<?= $this->endSection(); ?>