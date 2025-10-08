<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content'); ?>
<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Reset Password</h2>
    </div>
    <h6 class="mb-20">Enter your new password, confirm and submit</h6>
    <?php $errors = session()->getFlashdata('errors') ?>
    <form method="POST" action="<?= route_to('admin.reset-password-handler',$token) ?>">
        <?= csrf_field() ?>
        
        <?php if (!empty(session()->getFlashdata('success'))) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif ?>

        <?php if (!empty(session()->getFlashdata('error'))) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif ?>
        
        <div class="input-group custom">
            <input name="password" type="text" class="form-control form-control-lg" placeholder="New Password" value="<?= set_value('password') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>
        <?php if (isset($errors['password'])) : ?>
            <div class="d-block text-danger" style="margin-top: -25px;margin-bottom: 15px;">
                <?= $errors['password'] ?>
            </div>
        <?php endif ?>
        <div class="input-group custom">
            <input name="repassword" type="text" class="form-control form-control-lg" placeholder="Confirm New Password" value="<?= set_value('repassword') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>
        <?php if (isset($errors['repassword'])) : ?>
            <div class="d-block text-danger" style="margin-top: -25px;margin-bottom: 15px;">
                <?= $errors['repassword'] ?>
            </div>
        <?php endif ?>
        <div class="row align-items-center">
            <div class="col-5">
                <div class="input-group mb-0">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>