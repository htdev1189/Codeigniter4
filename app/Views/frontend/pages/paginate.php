<?php $pager->setSurroundCount(1) ?>
<!-- 
        1  |  2  |  3  |  4  |  5
        Trang hiện tại là 3, vậy 2 bên sẽ có 2 nếu khai báo  setSurroundCount(2)
        1  |  2  |  3 
        Trang hiện tại là 2, vậy 2 bên sẽ có 1 nếu khai báo  setSurroundCount(1)
-->
<div class="col-12">
    <div class="row">
        <div class="col-12">
            <nav class="mt-4">
                <!-- pagination -->
                <nav class="mb-md-50">
                    <ul class="pagination justify-content-center">
                        <?php if ($pager->hasPreviousPage()) : ?>

                            <!-- <li>
                                <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
                                    <span aria-hidden="true"><?= lang('Pager.first') ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= $pager->getPreviousPage() ?>" aria-label="<?= lang('Pager.previous') ?>">
                                    <span aria-hidden="true"><?= lang('Pager.previous') ?></span>
                                </a>
                            </li> -->
                            <li class="page-item">
                                <a href="<?= $pager->getFirst() ?>" class="page-link">First</a>
                            </li>

                            <li class="page-item">

                                <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Pagination Arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"></path>
                                    </svg>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php foreach ($pager->links('cats') as $link) : ?>
                            <li class="page-item <?= ($link['active']) ? 'active ' : '' ?>"> <a href="<?= $link['uri'] ?>" class="page-link">
                                    <?= $link['title'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <!-- kiem tra dieu kien next -->
                        <?php if ($pager->hasNextPage()) : ?>

                            <!-- <li>
                                <a href="<?= $pager->getNextPage() ?>" aria-label="<?= lang('Pager.next') ?>">
                                    <span aria-hidden="true"><?= lang('Pager.next') ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
                                    <span aria-hidden="true"><?= lang('Pager.last') ?></span>
                                </a>
                            </li> -->
                            <li class="page-item">
                                <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Pagination Arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"></path>
                                    </svg>
                                </a>
                            </li>
                            <li class="page-item">
                                <a href="<?= $pager->getLast() ?>" class="page-link">Last</a>
                            </li>

                        <?php endif; ?>
                    </ul>
                </nav>
            </nav>
        </div>
    </div>
</div>