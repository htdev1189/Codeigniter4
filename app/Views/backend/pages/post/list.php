<!-- extend from page-layout -->
<?= $this->extend('backend/layout/page-layout') ?>

<!-- render content -->
<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>List Posts</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Posts
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= route_to('admin.post.create') ?>" class="btn btn-primary btn-sm">Add new</a>
        </div>
    </div>
</div>
<?php if(!empty($posts)) : ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">title</th>
                        <th scope="col">category</th>
                        <th scope="col">created_at</th>
                        <th scope="col">update_at</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($posts as $post) : ?>
                    <tr>
                        <th scope="row"><?= $post['id'] ?></th>
                        <th scope="row"><?= $post['title'] ?></th>
                        <th scope="row"><?= $post['category_name'] ?></th>
                        <th scope="row"><?= date('d/m/Y H:i:s',strtotime($post['created_at'])) ?></th>
                        <th scope="row"><?= date('d/m/Y H:i:s',strtotime($post['updated_at'])) ?></th>
                        <th scope="row">
                            <a href="<?= route_to('admin.post.edit', $post['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                        </th>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection(); ?>