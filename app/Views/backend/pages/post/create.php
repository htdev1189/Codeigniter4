<?= $this->extend('backend/layout/page-layout'); ?>
<?= $this->section('content'); ?>

<!-- hien thi loi -->
<?php $custom_errors = session()->getFlashdata('CustomException') ?? []; ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Create Post</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Create Post
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= route_to('admin.post.list') ?>" class="btn btn-primary btn-sm">View all posts</a>
        </div>
    </div>
</div>
<!-- hay quen enctype -->
<form action="<?= route_to('admin.post.store') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field(); ?>
    <div class="row">
        <div class="col-md-9">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter the title" value="<?= old('title') ?>">
                        <?php if ($validation = session()->getFlashdata('validation')): ?>
                            <?php if ($validation->getError('title')): ?>
                                <span class="text-danger"><?= $validation->getError('title') ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!empty($custom_errors['title'])): ?>
                            <span class="text-danger"><?= $custom_errors['title'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Content</label>
                        <textarea name="content" id="content" rows="10" class="form-control" placeholder="Enter the content"><?= old('content') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card card-box mb-2">
                <h5 class="card-header">Seo</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Post meta keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" data-role="tagsinput" placeholder="Enter the title" value="<?= old('meta_keywords') ?>">
                    </div>
                    <div class="form-group">
                        <label for="">Post meta description</label>
                        <textarea name="meta_description" id="meta_description" rows="10" class="form-control" placeholder="Enter the description"><?= old('meta_description') ?></textarea>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Category</label>
                        <select name="category" id="category" class="custom-select form-control">
                            <option value="">Choose ...</option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- featured image -->
                    <div class="form-group">
                        <label for="">Post featured image</label>
                        <input type="file" name="featured_image" id="featured_image" class="form-control form-control-file" height="auto" value="<?= old('featured_image') ?>">
                        <?php if (!empty($custom_errors['file'])): ?>
                            <span class="text-danger"><?= $custom_errors['file'] ?></span>
                        <?php endif; ?>
                    </div>
                    <!-- thumbnail -->
                    <div class="form-group" style="max-width: 250px;">
                        <label for="">Thumbnail</label>
                        <img onerror="this.attr('src','https\:\/\/placehold.co\/600x400'); " src="<?= old('featured_image') ?>" alt="" id="image_previewer">
                    </div>
                    <!-- tags -->
                    <div class="form-group">
                        <label for="">Tags</label>
                        <input type="text" name="tags" id="tags" class="form-control" data-role="tagsinput" value="<?= old('tags')?>">
                    </div>
                    <!-- visibility -->
                    <div class="form-group">
                        <label for="">Visibility</label>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" id="customRadio1" name="visibility" class="custom-control-input" value="1">
                            <label class="custom-control-label" for="customRadio1">Public</label>
                        </div>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" id="customRadio2" name="visibility" class="custom-control-input" value="0" checked>
                            <label class="custom-control-label" for="customRadio2">Private</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-sm btn-primary">Create</button>
        <a href="<?= route_to('admin.post.list') ?>" class="btn btn-sm btn-secondary">Back</a>
    </div>
</form>
<?= $this->endSection(); ?>

<?= $this->section('stylesheets') ?>
<link
    rel="stylesheet"
    type="text/css"
    href=<?= base_url('backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') ?> />
<style>
    .bootstrap-tagsinput{display: block;}
</style>
<?= $this->endsection() ?>

<?= $this->section('script') ?>
<!-- bootstrap-tagsinput js -->
<script src=<?= base_url('backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') ?>></script>

<script>
    $('#featured_image').on('change', function(e) {
        // o day chi co 1 file
        var file = e.target.files[0];
        // console.log(file.type);

        // kiem tra dinh dang
        var allowed = [
            'image/jpg',
            'image/jpeg',
            'image/png'
        ];
        if ($.inArray(file.type, allowed) === -1) {
            toastr.error('Chỉ chấp nhận ảnh JPG hoặc PNG');
            $(this).val(''); // reset input
            return;
        }

        // Kiểm tra dung lượng
        if (file.size > 2 * 1024 * 1024) {
            toastr.error('File quá lớn, chỉ cho phép tối đa 2MB');
            $(this).val('');
            return;
        }


        var reader = new FileReader();
        reader.onload = function(event) {
            $('#image_previewer').attr('src', event.target.result);
        }
        reader.readAsDataURL(file);
    });
</script>
<?= $this->endSection() ?>