<div class="widget">
    <h2 class="section-title mb-3">Categories</h2>
    <div class="widget-body">
        <?php if (count(get_sidebar_categories()) > 0) : ?>
            <ul class="widget-list">
                <li>
                    <?php foreach (get_sidebar_categories() as $cat) : ?>
                        <a href="<?= route_to('blog.category.read', $cat->slug) ?>"><?= $cat->name ?>
                            <span class="ml-auto"><?= count_posts_by_category($cat->id) > 0 ? '(' . count_posts_by_category($cat->id) .  ')' : '' ?></span>
                        </a>
                    <?php endforeach; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>