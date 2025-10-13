<?= $this->extend('backend/layout/page-layout'); ?>
<?= $this->section('content'); ?>

<div class="pd-20 card-box mb-30">

    <h4 class="text-blue h4">Edit Category</h4>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>


    <form method="post" action="<?= route_to('admin.category.update', $category['id']) ?>">
        <?= csrf_field(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" value="<?= old('name', $category['name']) ?>">
                    <?php if (session('validation')) : ?>
                        <span class="text-danger"><?= session('validation')->getError('name') ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
            <a href="<?= route_to('admin.category.list') ?>" class="btn btn-sm btn-secondary">Back</a>

        </div>
    </form>
</div>


<?= $this->endSection(); ?>