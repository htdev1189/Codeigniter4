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