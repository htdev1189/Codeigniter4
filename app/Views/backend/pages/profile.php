<?= $this->extend('backend/layout/page-layout'); ?>
<?= $this->section('content'); ?>

<div class="min-height-200px">
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-photo">
                    <a href="javascript:;" class="edit-avatar" onclick="event.preventDefault();document.getElementById('user_profile_file').click();"><i class="fa fa-pencil"></i></a>
                    <input type="file" name="user_profile_file" id="user_profile_file" class="d-none">
                    <img src="<?= get_user()->picture == null ? '/images/users/default.jpg' : '/images/users/' . get_user()->picture ?>" alt="" class="avatar-photo image-previewer">

                </div>
                <h5 class="text-center h5 mb-0"><?= get_user()->name ?></h5>
                <p class="text-center text-muted font-14 ci-user-email">
                    <?= get_user()->email ?>
                </p>

            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#personal_details" role="tab">Personal details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#change_password" role="tab">Change password</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <!-- Timeline Tab start -->
                            <div class="tab-pane fade show active" id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <form action="<?= route_to('update-personal-details') ?>" method="post" id="personal_details_form">
                                        <?= csrf_field() ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Full Name</label>
                                                    <input type="text" name="name" class="form-control" placeholder="Enter name" value="<?= get_user()->name ?>">
                                                    <span class="text-danger error-text name_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Username</label>
                                                    <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?= get_user()->username ?>">
                                                    <span class="text-danger error-text username_error"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Bio</label>
                                            <textarea name="bio" rows="10" class="form-control"><?= get_user()->bio ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Timeline Tab End -->
                            <!-- Tasks Tab start -->
                            <div class="tab-pane fade" id="change_password" role="tabpanel">
                                <div class="pd-20 profile-task-wrap">
                                    <form action="" method="post" id="change_password_form">
                                        <?= csrf_field() ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Current Password</label>
                                                    <input type="password" class="form-control" name="current_password" placeholder="Enter current password">
                                                    <span class="text-danger error-text current_password_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">New Password</label>
                                                    <input type="password" class="form-control" name="new_password" placeholder="Enter new password">
                                                    <span class="text-danger error-text new_password_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Confirm New Password</label>
                                                    <input type="password" class="form-control" name="confirm_new_password" placeholder="confirm new password">
                                                    <span class="text-danger error-text confirm_new_password_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Tasks Tab End -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>

    // submit
    document.getElementById('personal_details_form').addEventListener('submit', function(e) {
        e.preventDefault();

        const xhr = new XMLHttpRequest();
        const formData = new FormData(this);

        xhr.open(this.method, this.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // 🔥 rất quan trọng cho $this->request->isAJAX()

        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText); // convert json string to json 
                    if (response.status == 1) {
                        // alert(response.msg);
                        toastr.success(response.msg);
                    } else {
                        // for in
                        for (const field in response.errors) {
                            const message = response.errors[field];
                            const span = document.querySelector(`.${field}_error`);
                            if (span) span.textContent = message;
                        }
                    }
                } catch (error) {
                    alert('❌ JSON parse error:', error);
                }
            } else {
                alert('❌ Lỗi HTTP:', xhr.status, xhr.responseText);
            }
        };

        xhr.onerror = function() {
            alert('❌ XHR Request failed');
        };

        xhr.send(formData);
    });

    // update image
    $('#user_profile_file').ijaboCropTool({
          preview : '.image-previewer',
          setRatio:1,
          allowedExtensions: ['jpg', 'jpeg','png'],
          processUrl:'<?= route_to('update-profile-picture') ?>',
          withCSRF:['<?= csrf_token() ?>','<?= csrf_hash() ?>'],
          onSuccess:function(message, element, status){
            if (status == 1) {
                toastr.success(message);
            } else {
                toastr.error(message);
            }
          },
          onError:function(message, element, status){
            alert(message);
          }
      });   
</script>
<?= $this->endSection() ?>