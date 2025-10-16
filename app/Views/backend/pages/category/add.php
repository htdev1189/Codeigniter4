<?= $this->extend('backend/layout/page-layout'); ?>
<?= $this->section('content'); ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Create Category</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Create Category
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= route_to('admin.category.list') ?>" class="btn btn-primary btn-sm">View all Category</a>
        </div>
    </div>
</div>

<form method="post" action="<?= route_to('admin.category.add') ?>">
    <?= csrf_field(); ?>

    <div class="card card-box mb-2">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">

                    <!-- <div class="form-group">
                        <label>Chọn danh mục</label>

                        <select name="parent" class="custom-select2 form-control" style="width: 100%; height: 38px;">
                            <option value="">No parent</option>
                            <?php foreach ($categories2 as $parent): ?>
                                <optgroup label="<?= esc($parent['name']) ?>">
                                    <?php foreach ($parent['children'] as $child): ?>
                                        <option value="<?= $child['id'] ?>"><?= esc($child['name']) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div> -->



                    <div class="form-group">
                        <label for="">Parent</label>
                        <select name="parent" class="custom-select form-control">
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
        </div>
    </div>

    <!-- meta Seo -->
    <div class="card car-box mb-2">
        <h5 class="card-header">Seo</h5>
        <div class="card-body">
            <div class="form-group">
                <label for="">Meta Title</label>
                <input type="text" name="seo_title" id="seo_title" class="form-control" placeholder="Enter title" value="<?= old('seo_title') ?>">
            </div>
            <div class="form-group">
                <label for="">Meta Keyword</label>
                <input type="text" class="form-control" name="seo_keyword" placeholder="Enter keywords" data-role="tagsinput" value="<?= old('seo_keyword') ?>">
            </div>
            <div class="form-group">
                <label for="">Meta Description</label>
                <textarea class="form-control" name="seo_des" placeholder="Enter description" rows="5"><?= old('seo_des') ?></textarea>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-sm btn-primary">Insert</button>
    </div>
</form>


<?= $this->endSection(); ?>

<?= $this->section('stylesheets') ?>
<link
    rel="stylesheet"
    type="text/css"
    href=<?= base_url('backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') ?> />
<style>
    .bootstrap-tagsinput {
        display: block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src=<?= base_url('backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') ?>></script>
<?= $this->endSection() ?>