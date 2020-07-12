<!DOCTYPE html>

<html lang="en">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel 6 Ajax CRUD Using Datatables - W3Adda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h2>Laravel 6 Ajax CRUD Using Datatables - W3Adda</h2>
    <br>
    <a href="javascript:void(0)" class="btn btn-info ml-3" id="add-new-post">Add New Post</a>
    <br><br>

    <table class="table table-bordered table-striped" id="laravel_datatable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Body</th>
            <th>Created at</th>
            <th>Action</th>
        </tr>
        </thead>
    </table>
</div>

<div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="postCrudModal"></h4>
            </div>
            <div class="modal-body">
                <form id="postForm" name="postForm" class="form-horizontal">
                    <input type="hidden" name="post_id" id="post_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="title" name="title" value="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Body</label>
                        <div class="col-sm-12">
                            <input class="form-control" id="body" name="body" value="" required="">
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dtable-posts.index') }}",
                type: 'GET',
            },
            columns: [
                { data: 'id', name: 'id', 'visible': false},
                { data: 'title', name: 'title' },
                { data: 'body', name: 'body' },
                { data: 'created_at', name: 'created_at' },
                {data: 'action', name: 'action', orderable: false},
            ],
            order: [[0, 'desc']]
        });

        $('#add-new-post').click(function () {
            $('#btn-save').val("create-post");
            $('#post_id').val('');
            $('#postForm').trigger("reset");
            $('#postCrudModal').html("Add New Post");
            $('#ajax-crud-modal').modal('show');
        });


        $('body').on('click', '.edit-post', function () {
            var post_id = $(this).data('id');
            $.get('dtable-posts/'+post_id+'/edit', function (data) {
                $('#name-error').hide();
                $('#email-error').hide();
                $('#postCrudModal').html("Edit Post");
                $('#btn-save').val("edit-post");
                $('#ajax-crud-modal').modal('show');
                $('#post_id').val(data.id);
                $('#title').val(data.title);
                $('#body').val(data.body);
            })
        });

        $('body').on('click', '#delete-post', function () {
            var post_id = $(this).data("id");
            confirm("Are You sure want to delete !");
            $.ajax({
                type: "get",
                url: "dtable-posts/destroy/"+post_id,
                success: function (data) {
                    var oTable = $('#laravel_datatable').dataTable();
                    oTable.fnDraw(false);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });
    });

    if ($("#postForm").length > 0) {
        $("#postForm").validate({
            submitHandler: function(form) {
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');

                $.ajax({
                    data: $('#postForm').serialize(),
                    url: "{{ route('dtable-posts.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $('#postForm').trigger("reset");
                        $('#ajax-crud-modal').modal('hide');
                        $('#btn-save').html('Save Changes');
                        var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                    }
                });
            }
        })
    }
</script>