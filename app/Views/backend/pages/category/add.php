<?= $this->extend('backend/layout/page-layout'); ?>
<?= $this->section('content'); ?>

<div class="pd-20 card-box mb-30">
    <form method="post" action="<?= route_to('admin.category.add') ?>">
        <?= csrf_field(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" value="<?= old('name') ?>">
                    <?php if (!empty(session()->getFlashdata('validation'))) : ?>
                        <?php if (session()->getFlashdata('validation')->getError('name')) : ?>
                            <span class="text-danger"><?= session()->getFlashdata('validation')->getError('name') ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <span class="text-danger"><?= session()->getFlashdata('error') ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-sm btn-primary">Insert</button>
        </div>
    </form>
</div>


<?= $this->endSection(); ?>