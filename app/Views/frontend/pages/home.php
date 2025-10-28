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
<div class="row no-gutters-lg">
    <div class="col-12">
        <h2 class="section-title">Latest Articles</h2>
    </div>
    <div class="col-lg-8 mb-5 mb-lg-0">
        <div class="row">
            <!-- bai viet moi nhat -->
            <?php if (get_latest_posts() != null): ?>
                <div class="col-12 mb-4">
                    <article class="card article-card">
                        <a href="<?= route_to('blog.post.read', get_latest_posts()->slug) ?>">
                            <div class="card-image">
                                <!-- <div class="post-info"> <span class="text-uppercase">04 Jun 2021</span> -->
                                <div class="post-info"> <span class="text-uppercase"><?= formatDate(get_latest_posts()->created_at, 'd M Y') ?></span>
                                    <span class="text-uppercase"><?= get_reading_time(get_latest_posts()->content) ?></span>
                                </div>
                                <img loading="lazy" decoding="async" src="<?= base_url('uploads/posts/' . get_latest_posts()->featured_image) ?>" alt="Post Thumbnail" class="w-100">
                            </div>
                        </a>
                        <div class="card-body px-0 pb-1">
                            <?php if (get_latest_posts()->tags != ''): ?>
                                <ul class="post-meta mb-2">
                                    <li>
                                        <?php foreach (explode(',', get_latest_posts()->tags) as $tag): ?>
                                            <a href="<?= route_to('blog.tags',urlencode($tag)) ?>"><?= $tag ?></a>
                                        <?php endforeach; ?>
                                    </li>
                                </ul>
                            <?php endif; ?>
                            <h2 class="h1"><a class="post-title" href="<?= route_to('blog.post.read', get_latest_posts()->slug) ?>"><?= get_latest_posts()->title ?></a></h2>
                            <p class="card-text"><?= limit_content(get_latest_posts()->content, 300) ?></p>
                            <div class="content"> <a class="read-more-btn" href="<?= route_to('blog.post.read', get_latest_posts()->slug) ?>">Read Full Article</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endif; ?>
            <!-- end bai viet moi nhat -->

            <!-- 6 bai viet tiep -->
            <?php if (count(get_posts()) > 0): ?>
                <?php foreach (get_posts() as $post) : ?>
                    <div class="col-md-6 mb-4">
                        <article class="card article-card article-card-sm h-100">
                            <a href="<?= route_to('blog.post.read', $post->slug) ?>">
                                <div class="card-image">
                                    <div class="post-info"> <span class="text-uppercase"><?= formatDate($post->created_at, 'd M Y') ?></span>
                                        <span class="text-uppercase">2 minutes read</span>
                                    </div>
                                    <img loading="lazy" decoding="async" src="<?= base_url('uploads/posts/' . $post->featured_image) ?>" alt="Post Thumbnail" class="w-100">
                                </div>
                            </a>
                            <div class="card-body px-0 pb-0">
                                <?php if ($post->tags != ''): ?>
                                    <ul class="post-meta mb-2">
                                        <li>
                                            <?php foreach (explode(',', $post->tags) as $tag): ?>
                                                <a href="#!"><?= $tag ?></a>
                                            <?php endforeach; ?>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                                <h2><a class="post-title" href="<?= route_to('blog.post.read', $post->slug) ?>"><?= $post->title ?></a></h2>
                                <p class="card-text"><?= limit_content($post->content, 100) ?></p>
                                <div class="content"> <a class="read-more-btn" href="<?= route_to('blog.post.read', $post->slug) ?>">Read Full Article</a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- end 6 bai viet tiep theo -->

        </div>
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

<?= $this->endSection() ?>