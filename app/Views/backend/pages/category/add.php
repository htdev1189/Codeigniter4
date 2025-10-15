<?= $this->extend('backend/layout/page-layout'); ?>
<?= $this->section('content'); ?>

<div class="pd-20 card-box mb-30">

    <h4 class="text-blue h4">Add New Category</h4>
    <form method="post" action="<?= route_to('admin.category.add') ?>">
        <?= csrf_field(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Parent</label>
                    <select name="parent" class="form-control">
                        <option value="">no parent</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
            <label for="">Seo Title</label>
            <textarea class="form-control" name="seo_title" placeholder="Enter title" rows="2"><?= old('seo_title') ?></textarea>
        </div>
        <div class="form-group">
            <label for="">Seo Keyword</label>
            <textarea class="form-control" name="seo_keyword" placeholder="Enter keywords" rows="2"><?= old('seo_keyword') ?></textarea>
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea class="form-control" name="seo_des" placeholder="Enter description" rows="5"><?= old('seo_des') ?></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-sm btn-primary">Insert</button>
        </div>
    </form>
</div>


<?= $this->endSection(); ?>