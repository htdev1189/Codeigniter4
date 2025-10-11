<?= $this->extend('backend/layout/page-layout'); ?>
<?= $this->section('content'); ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Settings</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Settings
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="pd-20 card-box">
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#general_setting" role="tab" aria-selected="true">General Setting</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">Logo & Favicon</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#social_media" role="tab" aria-selected="false">Social media</a>
            </li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="general_setting" role="tabpanel">
                <div class="pd-20">
                    <!-- general setting -->
                    <form action="<?= route_to('admin.update.setting') ?>" method="post" id="general-setting-form">
                        <input type="hidden" id="general-setting-form-token" name="<?= csrf_token() ?>" value="<?= csrf_hash(); ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Blog title</label>
                                    <input type="text" name="blog_title" class="form-control" placeholder="Enter blog title" value="<?= get_setting()->blog_title ?>">
                                    <span class="text-danger error-text blog_title_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Blog email</label>
                                    <input type="text" name="blog_email" class="form-control" placeholder="Enter blog email" value="<?= get_setting()->blog_email ?>">
                                    <span class="text-danger error-text blog_email_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Blog phone</label>
                                    <input type="text" name="blog_phone" class="form-control" placeholder="Enter blog phone" value="<?= get_setting()->blog_phone ?>">
                                    <span class="text-danger error-text blog_phone_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Blog keywords</label>
                                    <input type="text" name="blog_keywords" class="form-control" placeholder="Enter blog keywords" value="<?= get_setting()->blog_keywords ?>">
                                    <span class="text-danger error-text blog_keywords_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Blog description</label>
                                    <textarea name="blog_description" id="" rows="5" class="form-control" placeholder="Enter blog description"><?= get_setting()->blog_description ?></textarea>
                                    <span class="text-danger error-text blog_description_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="tab-pane fade" id="logo_favicon" role="tabpanel">
                <div class="pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Set Blog Logo</h5>
                            <div class="mb-2 mt-1" style="max-width: 257px;">
                                <img src="<?= '/images/setting/' . get_setting()->blog_logo ?>" alt="" id="logo-image-preview" class="image-thumbnail">
                            </div>
                            <form action="<?= route_to('admin.update.logo') ?>" method="post" enctype="multipart/form-data" id="setting-update-logo">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="update-logo-token">
                                <div class="mb-2">
                                    <input type="file" id="blog_logo" name="blog_logo" class="form-control">
                                    <span class="text-danger error-text blog_logo_error"></span>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Update Logo</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="social_media" role="tabpanel">
                <div class="pd-20">
                    <!-- social -->
                     <form action="<?= route_to('admin.update.social') ?>" method="post" id="admin-update-social-form">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash() ?>" id="admin-update-social-token">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Facebook</label>
                                    <input type="text" class="form-control" name="social[facebook]" value="<?= get_setting()->blog_social->facebook?>">
                                    <span class="text-danger error-text social_facebook_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Zalo</label>
                                    <input type="text" class="form-control" name="social[zalo]" value="<?= get_setting()->blog_social->zalo?>">
                                    <span class="text-danger error-text social_zalo_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">update</button>
                        </div>
                     </form>
                </div>
            </div>
        </div>
    </div>
</div>




<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<script>

    // update social
    $('#admin-update-social-form').on('submit', function(e){
        e.preventDefault();

        var form = this;
        var dataForm = new FormData(form);

        $.ajax({
            url:form.action,
            method:form.method,
            data:dataForm,
            processData:false,
            contentType:false,//important
            cache:false,
            dataType:'json',
            beforeSend:function(){
                toastr.remove();
                $(form).find("span.error-text").text('');
            },
            success:function(response){
                // update token
                $('#admin-update-social-token').val(response.token);
                if (response.status === 1) {
                    toastr.success(response.msg);
                }else{
                    toastr.error(response.msg);
                    if(!$.isEmptyObject(response.errors)){
                        $.each(response.errors, function(key,value){
                            //social.zalo => social_zalo_error
                            $(form).find('span.'+key.replace('.','_')+'_error').text(value);
                        });
                    }
                }
            },
            error:function(){
                console.log("error");
            }
        });
    });


    $('#general-setting-form').on('submit', function(e) {
        e.preventDefault();

        var form = this;
        var dataForm = new FormData(form);

        $.ajax({
            url: form.action,
            method: form.method,
            data: dataForm,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                // remove toastr
                toastr.remove();

                // clear span error text
                // $(form) -> ép kiểu sang Obj Jquery
                $(form).find('span.error-text').text('');
            },
            success: function(response) {

                // reset token 
                $('#general-setting-form').val(response.token);
                if (response.status == 1) {
                    toastr.success(response.msg);
                } else {
                    // get error
                    if (!$.isEmptyObject(response.errors)) {
                        $.each(response.errors, function(key, value) {
                            //span class="text-danger error-text blog_keywords_error"></span>
                            $('span.' + key + '_error').text(value);
                        });
                    }
                    toastr.error(response.msg);
                }
            },
            error: function(res) {
                toastr.error('update general setting failed');
            }
        });
    });

    $('#blog_logo').on('change', function(e) {
        // o day chi co 1 file
        var file = e.target.files[0];
        console.log(file.type);
        
        if (!file) {
            toastr.error('upload logo failed');
            return;
        }

        // kiem tra dinh dang
        var allowed = [
            'image/jpg',
            'image/jpeg',
            'image/png'
        ];
        if ($.inArray(file.type, allowed) === -1) {
            $('#setting-update-logo').find('span.error-text').text('Chỉ chấp nhận ảnh JPG hoặc PNG');
            $(this).val(''); // reset input
            return;
        }
        
        // Kiểm tra dung lượng
        if (file.size > 2 * 1024 * 1024) {
            $('#setting-update-logo').find('span.error-text').text('File quá lớn, chỉ cho phép tối đa 2MB');
            $(this).val('');
            return;
        }


        var reader = new FileReader();
        reader.onload = function(event) {
            $('#logo-image-preview').attr('src', event.target.result);
        }
        reader.readAsDataURL(file);
    });

    // update logo
    $('#setting-update-logo').on('submit', function(e) {
        e.preventDefault();

        var form = this;
        var dataForm = new FormData(form);

        // check empty file
        if($('#blog_logo').val().length <= 0){
            $(form).find('span.error-text').text('Chua chon file nao ca');
            return;
        }

        $.ajax({
            url: form.action,
            method: form.method,
            data: dataForm,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                // remove toastr
                toastr.remove();

                // clear span error text
                // $(form) -> ép kiểu sang Obj Jquery
                $(form).find('span.error-text').text('');
            },
            success: function(response) {

                // reset token 
                $('#update-logo-token').val(response.token);
                if (response.status == 1) {
                    toastr.success(response.msg);
                    // $('#logo-image-preview').attr('src', response.logo);
                } else {
                    // get error
                    if (!$.isEmptyObject(response.errors)) {
                        $.each(response.errors, function(key, value) {
                            //span class="text-danger error-text blog_keywords_error"></span>
                            $('span.' + key + '_error').text(value);
                        });
                    }
                    toastr.error(response.msg);
                }
            },
            error: function(res) {
                toastr.error('update logo failed');
            }
        });
    });
</script>
<?= $this->endSection() ?>