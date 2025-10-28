<?php if (count(get_tags()) > 0) : ?>
    <div class="widget">
        <h2 class="section-title mb-3">Tags</h2>
        <div class="widget-body">
            <ul class="widget-list">
                <?php foreach (get_tags() as $tag) : ?>
                    <li>
                        <a href="<?= route_to('blog.tags',urlencode($tag)) ?>"><?= $tag ?>
                            <span class="ml-auto"><?= count_post_in_tag($tag) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>