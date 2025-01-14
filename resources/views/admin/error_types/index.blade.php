@extends('layouts.admin')

@section('title', 'ErrorTypes')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/css/common.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
@endsection

@section('content')
<div class="content-wrapper">
	<div class="content">

        <!-- Add and Edit New errorType -->
        <div class="modal fade" id="ajaxerrorTypeFormModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ajaxerrorTypeModalTitle"><span id="errorTypeFormModalHeading"></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <form id="errorType_form">
                        <span id="generalError" class="text-danger error_msgs"></span><br>

                        <input type="hidden" name="error_type_id" id="error_type_id">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="name">Name</label> <span class="star-color">*</span>
                                  <input type="text" name="name" id="name" class="form-control" placeholder="Error name">
                                <span id="nameError" class="error_msgs text-danger"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label>Status</label> <span class="star-color">*</span>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="" disabled selected>Choose Option</option>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span id="statusError" class="error_msgs text-danger"></span>
                            </div>
                        </div>
                    </form>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button id="errorTypeSaveBtn" class="btn btn-success"></button>
                  </div>
              </div>
            </div>
        </div>
        <!-- End Add New ErrorType -->


        <!-- Show ErrorType -->
        <div class="modal fade" id="ajaxShowErrorTypeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Show Error Type</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <td id="show_errorType_name"></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="show_errorType_status"></td>
                            </tr>

                        </thead>
                    </table>

                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
              </div>
            </div>
        </div>
        <!-- End Show errorType -->

        <a href="javascript:void(0)" id="ajaxNewErrorType" data-toggle="modal" class="btn btn-info mt-2 mb-4">New Error Type</a>

        <div class="d-flex justify-content-between mb-3">
            <h4 class="text-dark font-weight-medium"><b>Error Types</b></h4>
        </div>
        <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5 mb-6">

            <div class="table-responsive">
            <table id="errorTypes-table" class="table mt-3 table-striped text-center" style="width:100%">
                <thead>
                    <th>Name</th>
                    <th>Status</th>
                    <th style="width: 20%">Actions</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script>

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $("#errorTypes-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.error_types.index') }}",
            columns: [
                {data: 'name'},
                {data: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });


        // Save Lesson
        $('#errorTypeSaveBtn').click(function (e) {
            e.preventDefault();
            // $('#loader').loading({show: true});
            $(this).html("Saving...");
            $(this).attr('disabled', true);
            $('.error_msgs').html('');

            $.ajax({
                data: $('#errorType_form').serialize(),
                url: "{{ route('admin.error_types.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (response) {
                    $('#errorType_form').trigger("reset");
                    $('#ajaxerrorTypeFormModal').modal('hide');
                    toastr['success'](response.message)
                    table.draw();
                },
                error: function (data) {
                    if(data.responseJSON.errors){
                        $("#nameError").html(data.responseJSON.errors.name);
                        $("#statusError").html(data.responseJSON.errors.status);
                    }
                    else if(data.responseJSON.error){
                        $("#generalError").html(data.responseJSON.error);
                    }
                    $('#errorTypeSaveBtn').html('Save');
                    $('#errorTypeSaveBtn').attr('disabled', false);
                }
            });
        });

        // Show ErrorType
        $('body').on('click', '.showButton', function () {

            var error_type_id = $(this).data("id");

            $.ajax({
                data: $('#errorType_form').serialize(),
                url: "{{ route('admin.error_types.show', '') }}"+ "/"+ error_type_id,
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#ajaxShowErrorTypeModal').modal('show');

                    if (response.error) {
                        $('#show_errorType_name').html(response.error.name);
                        $('#show_errorType_status').html(response.error.status);
                    }
                },
                error: function (data) {
                    if(data.responseJSON.errors){
                        $("#nameError").html(data.responseJSON.errors.name);
                        $("#statusError").html(data.responseJSON.errors.status);
                    }
                    else if(data.responseJSON.error){
                        toastr['error'](data.responseJSON.error);
                    }
                    else {
                        toastr['error']('Something went wrong, please refresh webpage and try again.');
                    }
                }
            });

        });

        // Edit ErrorType
        $('body').on('click', '.editButton', function () {

            $('#errorTypeSaveBtn').html('Save');
            $('#errorTypeSaveBtn').attr('disabled', false);

            var error_type_id = $(this).data("id");
            var url = "{{ route('admin.error_types.edit', ':id') }}";
            url = url.replace(':id', error_type_id);

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#ajaxerrorTypeFormModal').modal('show');
                    $('#errorTypeFormModalHeading').html("Edit Error Type");

                    if (response.error) {
                        $('#error_type_id').val(response.error.id);
                        $('#name').val(response.error.name);
                        $("#status option[value='"+ response.error.status +"']").attr("selected", true);
                    }
                    // $("#errorType_status option[value='"+ response.status +"']").attr("selected", '');
                    console.log(response)
                },
                error: function (data) {

                    if(data.responseJSON.errors){
                        $("#nameError").html(data.responseJSON.errors.name);
                        $("#statusError").html(data.responseJSON.errors.status);
                    }
                    else if(data.responseJSON.error){
                        toastr['error'](data.responseJSON.error);
                    }
                    else {
                        toastr["error"]('Something went wrong, please refresh webpage and try again, if still problem persist contact with administrator');
                    }

                }
            });

        });

        // Deleting Post
        $('body').on('click', '.deleteButton', function () {

            var error_type_id = $(this).data("id");

            if(confirm("Are You sure want to delete !")){
                $.ajax({
                type: "DELETE",
                url: "{{ route('admin.error_types.destroy', '') }}" +'/'+ error_type_id,
                success: function (data) {

                    if (data.message) {
                        toastr["info"](data.message);
                    }

                    table.draw();
                },
                error: function (data) {

                    if(data.responseJSON.error){
                        toastr["error"](data.responseJSON.error);
                    }
                    else {
                        toastr["error"]('Something went wrong, please refresh webpage and try again, if still problem persist contact with administrator');
                    }

                }
                });
            }


        });


        $('#ajaxNewErrorType').click(function () {
            $("#error_type_id").val('');
            $('#ajaxerrorTypeFormModal').modal('show');
            $('#errorTypeSaveBtn').html("Save");
            $('#errorTypeFormModalHeading').html("New Error Type");
            $('.error_msgs').html('');
            $('#errorTypeSaveBtn').attr('disabled', false);
            $('#errorType_form').trigger("reset");
        });

        $('body').on('click', '.editButton', function () {
            $('.error_messages').html('');
        });

        $('#ajaxerrorTypeFormModal').on('hidden.bs.modal', function() {
            console.log('lcos')
            $('.error_msgs').html('');
            $("#status option").attr("selected", false);
        });


    });

</script>


@endsection
