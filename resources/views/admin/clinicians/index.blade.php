@extends('layouts.admin')

@section('title', 'Clinicians')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
@endsection

@section('content')
<div class="content-wrapper">
	<div class="content">

        <!-- Add and Edit New clinician -->
        <div class="modal fade" id="ajaxclinicianFormModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ajaxclinicianModalTitle"><span id="clinicianFormModalHeading"></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <form id="clinician_form">
                        <span id="generalError" class="text-danger error_msgs"></span><br>

                        <input type="hidden" name="clinician_id" id="clinician_id">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="name">Name</label> <span class="star-color">*</span>
                                  <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <span id="nameError" class="error_msgs text-danger"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="email">Email</label> <span class="star-color">*</span>
                                  <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                <span id="emailError" class="error_msgs text-danger"></span>
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
                    <button id="clinicianSaveBtn" class="btn btn-success"></button>
                  </div>
              </div>
            </div>
        </div>
        <!-- End Add New Clinician -->


        <!-- Show Clinician -->
        <div class="modal fade" id="ajaxShowClinicianModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Show Clinician</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <td id="show_clinician_name"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="show_clinician_email"></td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td id="show_clinician_status"></td>
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
        <!-- End Show clinician -->

        <a href="javascript:void(0)" id="ajaxNewClinician" data-toggle="modal" class="btn btn-info mt-2 mb-4">New Clinician</a>

        <div class="d-flex justify-content-between mb-3">
            <h4 class="text-dark font-weight-medium"><b>Clinicians</b></h4>
        </div>
        <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5 mb-6">

            <div class="table-responsive">
            <table id="clinicians-table" class="table mt-3 table-striped text-center" style="width:100%">
                <thead>
                    <th>Name</th>
                    <th>Email</th>
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

        var table = $("#clinicians-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.clinicians.index') }}",
            columns: [
                {data: 'name'},
                {data: 'email'},
                {data: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });


        // Save Lesson
        $('#clinicianSaveBtn').click(function (e) {
            e.preventDefault();
            // $('#loader').loading({show: true});
            $(this).html("Saving...");
            $(this).attr('disabled', true);
            $('.error_msgs').html('');

            $.ajax({
                data: $('#clinician_form').serialize(),
                url: "{{ route('admin.clinicians.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (response) {
                    $('#clinician_form').trigger("reset");
                    $('#ajaxclinicianFormModal').modal('hide');
                    toastr['success'](response.message)
                    table.draw();
                },
                error: function (data) {
                    if(data.responseJSON.errors){
                        $("#nameError").html(data.responseJSON.errors.name);
                        $("#emailError").html(data.responseJSON.errors.email);
                        $("#statusError").html(data.responseJSON.errors.status);
                    }
                    else if(data.responseJSON.error){
                        $("#generalError").html(data.responseJSON.error);
                    }
                    $('#clinicianSaveBtn').html('Save');
                    $('#clinicianSaveBtn').attr('disabled', false);
                }
            });
        });

        // Show Clinician
        $('body').on('click', '.showButton', function () {

            var clinician_id = $(this).data("id");

            $.ajax({
                data: $('#clinician_form').serialize(),
                url: "{{ route('admin.clinicians.show', '') }}"+ "/"+ clinician_id,
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#ajaxShowClinicianModal').modal('show');

                    if (response.clinician) {
                        $('#show_clinician_name').html(response.clinician.name);
                        $('#show_clinician_email').html(response.clinician.email);
                        $('#show_clinician_status').html(response.clinician.status);
                    }
                },
                error: function (data) {
                    if(data.responseJSON.errors){
                        $("#nameError").html(data.responseJSON.errors.name);
                        $("#emailError").html(data.responseJSON.errors.email);
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

        // Edit Clinician
        $('body').on('click', '.editButton', function () {

            $('#clinicianSaveBtn').html('Save');
            $('#clinicianSaveBtn').attr('disabled', false);

            var clinician_id = $(this).data("id");
            var url = "{{ route('admin.clinicians.edit', ':id') }}";
            url = url.replace(':id', clinician_id);

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#ajaxclinicianFormModal').modal('show');
                    $('.clinicianFormModalHeading').html("Edit Clinician");

                    if (response.clinician) {
                        $('#clinician_id').val(response.clinician.id);
                        $('#name').val(response.clinician.name);
                        $('#email').val(response.clinician.email);
                        $("#status option[value='"+ response.clinician.status +"']").attr("selected", true);
                    }
                    // $("#clinician_status option[value='"+ response.status +"']").attr("selected", '');
                    console.log(response)
                },
                error: function (data) {

                    if(data.responseJSON.errors){
                        $("#nameError").html(data.responseJSON.errors.name);
                        $("#emailError").html(data.responseJSON.errors.email);
                        $("#statusError").html(data.responseJSON.errors.status);
                    }
                    else if(data.responseJSON.error){
                        toastr['error'](data.responseJSON.error);
                    }
                    else {
                        toastr["error"]('Something went wrong, please refresh webpage and try again, if still problem persist contact with administrator');
                    }
                    // $('#clinicianSaveBtn').html('Save');
                    // $('#clinicianSaveBtn').attr('disabled', false);
                }
            });

        });

        // Deleting Post
        $('body').on('click', '.deleteButton', function () {

            var clinician_id = $(this).data("id");

            if(confirm("Are You sure want to delete !")){
                $.ajax({
                type: "DELETE",
                url: "{{ route('admin.clinicians.destroy', '') }}" +'/'+ clinician_id,
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


        $('#ajaxNewClinician').click(function () {
            $("#clinician_id").val('');
            $('#ajaxclinicianFormModal').modal('show');
            $('#clinicianSaveBtn').html("Save");
            $('#clinicianFormModalHeading').html("New Clinician");
            $('.error_msgs').html('');
            $('#clinicianSaveBtn').attr('disabled', false);
            $('#clinician_form').trigger("reset");
        });

        $('body').on('click', '.editButton', function () {
            $('.error_messages').html('');
        });

        $('#ajaxclinicianFormModal').on('hidden.bs.modal', function() {
            console.log('lcos')
            $('.error_msgs').html('');
            $("#status option").attr("selected", false);
        });


    });

</script>


@endsection
