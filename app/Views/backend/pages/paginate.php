<?php

/**
 * @var CodeIgniter\Pager\PagerRenderer $pager
 */
?>

<?php $pager->setSurroundCount(2) ?>

<p>
    Showing <span class="font-medium"><?= $pager->getPerPageStart() ?></span>
    to <span class="font-medium"><?= $pager->getPerPageEnd() ?></span>
    of <span class="font-medium"><?= $pager->getTotal() ?></span> results
</p>

<?php if ($pager->getPageCount() > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">

            <!-- Previous -->

            <?php if ($pager->hasPreviousPage()) : ?>
                <li>
                    <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
                        <span aria-hidden="true"><?= lang('Pager.first') ?></span>
                    </a>
                </li>
                <li>
                    <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="<?= lang('Pager.previous') ?>">
                        <span aria-hidden="true"><?= lang('Pager.previous') ?></span>
                    </a>
                </li>
            <?php endif ?>

            <!-- Page numbers -->
            <?php foreach ($pager->links() as $link): ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $link['uri'] ?>">
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Next -->
            <?php if ($pager->hasNextPage()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="<?= lang('Pager.next') ?>">
                        <span aria-hidden="true"><?= lang('Pager.next') ?></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
                        <span aria-hidden="true"><?= lang('Pager.last') ?></span>
                    </a>
                </li>
            <?php endif ?>

        </ul>
    </nav>
<?php endif; ?>