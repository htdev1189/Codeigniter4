<!-- extend from page-layout -->
<?= $this->extend('backend/layout/page-layout') ?>

<!-- render content -->
<?= $this->section('content') ?>
<!-- hien thi loi -->
<?php $custom_errors = session()->getFlashdata('CustomException') ?? []; ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Edit Post</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Post
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
<form action="<?= route_to('admin.post.update', $post['id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field(); ?>
    <div class="row">
        <div class="col-md-9">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter the title" value="<?= old('title', $post['title']) ?>">
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
                        <textarea name="content" id="content" rows="10" class="form-control" placeholder="Enter the content"><?= old('content', $post['content']) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card card-box mb-2">
                <h5 class="card-header">Seo</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Post meta keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" data-role="tagsinput" placeholder="Enter the title" value="<?= old('meta_keywords', $post['meta_keywords']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="">Post meta description</label>
                        <textarea name="meta_description" id="meta_description" rows="10" class="form-control" placeholder="Enter the description"><?= old('meta_description', $post['meta_description']) ?></textarea>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Category</label>
                        <select name="category_id" id="category_id" class="custom-select form-control">
                            <option value="">Choose ...</option>
                            <?php foreach ($categories as $cat) : ?>
                                <option <?= $post['category_id'] == $cat['id'] || old('category_id') == $cat['id'] ? 'selected' : '' ?> value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- featured image -->
                    <div class="form-group">
                        <label for="">Post featured image</label>
                        <input type="file" name="featured_image" id="featured_image" class="form-control form-control-file" height="auto">
                        <?php if (!empty($custom_errors['file'])): ?>
                            <span class="text-danger"><?= $custom_errors['file'] ?></span>
                        <?php endif; ?>
                    </div>
                    <!-- thumbnail -->
                    <div class="form-group" style="max-width: 250px;">
                        <label for="">Thumbnail</label>
                        <img
                            src="<?= esc(old('featured_image') ? base_url('posts/' . old('featured_image')) : ($post['featured_image'] ? base_url('uploads/posts/' . $post['featured_image']) : 'https://placehold.co/600x400')) ?>"
                            alt="Thumbnail"
                            id="image_previewer"
                            onerror="this.src='https://placehold.co/600x400';"
                            class="img-fluid rounded shadow-sm" />


                    </div>
                    <!-- tags -->
                    <div class="form-group">
                        <label for="">Tags</label>
                        <input type="text" name="tags" id="tags" class="form-control" data-role="tagsinput" value="<?= old('tags', $post['tags']) ?>">
                    </div>
                    <!-- visibility -->
                    <div class="form-group">
                        <label for="">Visibility</label>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" id="customRadio1" name="visibility" class="custom-control-input" value="1" <?= $post['visibility'] == '1' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="customRadio1">Public</label>
                        </div>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" id="customRadio2" name="visibility" class="custom-control-input" value="0" <?= $post['visibility'] == '0' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="customRadio2">Private</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-sm btn-primary">Update</button>
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
    .bootstrap-tagsinput {
        display: block;
    }
</style>
<?= $this->endsection() ?>

<?= $this->section('script') ?>
<!-- bootstrap-tagsinput js -->
<script src=<?= base_url('backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') ?>></script>

<script src="<?= base_url('backend/ckeditor4/ckeditor.js') ?>"></script>

<script>
    // Replace the <textarea id="editor1"> with a CKEditor 4
    // instance, using default configuration.
    // CKEDITOR.replace('content');
    CKEDITOR.replace('content', {
        allowedContent: true, // Cho phép mọi thẻ HTML
        extraPlugins: 'codesnippet', // Kích hoạt plugin code
        codeSnippet_theme: 'monokai_sublime',
        versionCheck: false,
        // đây là khi click vào nút choose file trong tab Upload
        // filebrowserBrowseUrl: '<?= route_to('admin.upload.form') ?>',
        filebrowserBrowseUrl: '<?= route_to('admin.upload.browse') ?>',
        // đây là khi ấn vào nút browser server trong ckeditor tab Image Info
        filebrowserUploadUrl: '<?= route_to('admin.upload.handler') ?>'
    });
</script>


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
        if (file.size > 10 * 1024 * 1024) {
            toastr.error('File quá lớn, chỉ cho phép tối đa 10MB');
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