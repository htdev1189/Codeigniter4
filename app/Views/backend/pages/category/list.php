<?= $this->extend('backend/layout/page-layout'); ?>

<?= $this->section('stylesheets') ?>
<link
    rel="stylesheet"
    type="text/css"
    href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
<?= $this->endSection('stylesheets') ?>
<?= $this->section('content'); ?>




<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Categories</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Categories
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Simple Datatable start -->
<div class="card-box mb-30">
    <!-- <div class="pd-20">
        <h4 class="text-blue h4">Data Table Simple</h4>
        <p class="mb-0">
            you can find more options
            <a
                class="text-primary"
                href="https://datatables.net/"
                target="_blank">Click Here</a>
        </p>
    </div> -->
    <div class="pt-20 pb-20">
        <table id="categoryTable" class="table stripe hover nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>created_at</th>
                    <th>updated_at</th>
                    <th>action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- Simple Datatable End -->
<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>

<!-- Datatable Setting js -->
<script src="/backend/vendors/scripts/datatable-setting.js"></script>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        toastr.success("<?= session()->getFlashdata('success') ?>");
    </script>
<?php endif; ?>

<!-- datatable -->

<script>
    $('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= route_to('admin.category.data') ?>',
            type: 'GET'
        },
        columns: [{
                title: 'ID'
            },
            {
                title: 'Name'
            },
            {
                title: 'Slug'
            },
            {
                title: 'Created At'
            },
            {
                title: 'Updated At'
            },
            {
                title: 'Actions',
                orderable: false,
                searchable: false
            }
        ]
    });
</script>

<?= $this->endsection() ?>