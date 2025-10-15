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
                <label for="">Parent</label>
                <select name="parent" class="form-control">
                    <option value="">no parent</option>
                    <?php foreach ($categories as $cat) : ?>
                        <option <?= $cat['id'] == $category['parent_id'] ? 'selected' : '' ?> value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            </div>
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
            <label for="">Seo Title</label>
            <textarea class="form-control" name="seo_title" placeholder="Enter title" rows="2"><?= old('seo_title', $category['seo_title']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="">Seo Keyword</label>
            <textarea class="form-control" name="seo_keyword" placeholder="Enter keywords" rows="2"><?= old('seo_keyword', $category['seo_keyword']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea class="form-control" name="seo_des" placeholder="Enter description" rows="5"><?= old('seo_des', $category['seo_des']) ?></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
            <a href="<?= route_to('admin.category.list') ?>" class="btn btn-sm btn-secondary">Back</a>

        </div>
    </form>
</div>


<?= $this->endSection(); ?>