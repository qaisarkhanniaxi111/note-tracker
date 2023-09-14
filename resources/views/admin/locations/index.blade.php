@extends('layouts.admin')

@section('title', 'Locations')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<link href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="content-wrapper">
	<div class="content">

        <!-- Add and Edit New location -->
        <div class="modal fade" id="ajaxlocationFormModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ajaxlocationModalTitle"><span id="locationFormModalHeading"></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <form id="location_form">
                        <span id="generalError" class="text-danger error_msgs"></span><br>

                        <input type="hidden" name="location_id" id="location_id">
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
                        <div class="row mb-3">
                            <div class="col">
                                <label class="text-dark font-weight-medium">Clinicians <span style="opacity: 0.5; font-size: 12px">(Optional)</span></label>
                                <select name="clinicians[]" class="js-example-basic-multiple select2_example form-control" multiple="multiple">
                                    @foreach ($clinicians as $clinician)
                                        <option value="{{ $clinician->id }}">{{ $clinician->name }}</option>
                                    @endforeach
                                </select>
                                <span id="clinicianError" class="error_msgs text-danger"></span>
                            </div>
                        </div>
                    </form>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button id="locationSaveBtn" class="btn btn-success"></button>
                  </div>
              </div>
            </div>
        </div>
        <!-- End Add New Location -->


        <!-- Show Location -->
        <div class="modal fade" id="ajaxShowLocationModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Show Location</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <td id="show_location_name"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="show_location_email"></td>
                            </tr>

                            <tr>
                                <th>Clinicians</th>
                                <td id="show_clinicians"></td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td id="show_location_status"></td>
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
        <!-- End Show location -->

        <a href="javascript:void(0)" id="ajaxNewLocation" data-toggle="modal" class="btn btn-info mt-2 mb-4">New Location</a>

        <div class="d-flex justify-content-between mb-3">
            <h4 class="text-dark font-weight-medium"><b>Locations</b></h4>
        </div>
        <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5 mb-6">

            <div class="table-responsive">
            <table id="locations-table" class="table mt-3 table-striped text-center" style="width:100%">
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
<script src="{{ asset('assets/admin/plugins/select2/js/select2.min.js') }}"></script>

<script>

// $.fn.modal.Constructor.prototype._enforceFocus = function() {};

 // when modal is open
 $('.modal').on('shown.bs.modal', function () {
        $('.select2_example').select2({
            placeholder: "    Select Option",
            allowClear: true
        });
  });

</script>
<script>

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $("#locations-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.locations.index') }}",
            columns: [
                {data: 'name'},
                {data: 'email'},
                {data: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });


        // Save Lesson
        $('#locationSaveBtn').click(function (e) {
            e.preventDefault();
            // $('#loader').loading({show: true});
            $(this).html("Saving...");
            $(this).attr('disabled', true);
            $('.error_msgs').html('');

            $.ajax({
                data: $('#location_form').serialize(),
                url: "{{ route('admin.locations.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (response) {
                    $('#location_form').trigger("reset");
                    $('#ajaxlocationFormModal').modal('hide');
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
                    $('#locationSaveBtn').html('Save');
                    $('#locationSaveBtn').attr('disabled', false);
                }
            });
        });

        // Show Location
        $('body').on('click', '.showButton', function () {

            var location_id = $(this).data("id");

            $.ajax({
                data: $('#location_form').serialize(),
                url: "{{ route('admin.locations.show', '') }}"+ "/"+ location_id,
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#ajaxShowLocationModal').modal('show');

                    if (response.location) {
                        $('#show_location_name').html(response.location.name);
                        $('#show_location_email').html(response.location.email);
                        $('#show_location_status').html(response.location.status);
                        $('#show_clinicians').html(response.location.clinicians);

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

        // Edit Location
        $('body').on('click', '.editButton', function () {

            $('#locationSaveBtn').html('Save');
            $('#locationSaveBtn').attr('disabled', false);
            $(".select2_example").val('').change();

            var location_id = $(this).data("id");
            var url = "{{ route('admin.locations.edit', ':id') }}";
            url = url.replace(':id', location_id);

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $('#ajaxlocationFormModal').modal('show');
                    $('.locationFormModalHeading').html("Edit Location");

                    if (response.location) {
                        $('#location_id').val(response.location.id);
                        $('#name').val(response.location.name);
                        $('#email').val(response.location.email);
                        $("#status option[value='"+ response.location.status +"']").attr("selected", true);
                    }

                    if (response.clinicians) {

                        var options = [];

                        $.each(response.clinicians, function(index, item) {

                            $(".select2_example option[value="+item.id+"]").remove();

                            options = $("<option selected></option>").val(item.id).text(item.name);

                            $('.select2_example').append(options).trigger('change');

                        });

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
                        toastr["error"]('Something went wrong, please refresh webpage and try again, if still problem persist contact with administrator');
                    }
                    // $('#locationSaveBtn').html('Save');
                    // $('#locationSaveBtn').attr('disabled', false);
                }
            });

        });

        // Deleting Post
        $('body').on('click', '.deleteButton', function () {

            var location_id = $(this).data("id");

            if(confirm("Are You sure want to delete !")){
                $.ajax({
                type: "DELETE",
                url: "{{ route('admin.locations.destroy', '') }}" +'/'+ location_id,
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


        $('#ajaxNewLocation').click(function () {
            $("#location_id").val('');
            $('#ajaxlocationFormModal').modal('show');
            $('#locationSaveBtn').html("Save");
            $('#locationFormModalHeading').html("New Location");
            $('.error_msgs').html('');
            $('#locationSaveBtn').attr('disabled', false);
            $('#location_form').trigger("reset");

            $('.select2_example').val(null).trigger("change");
        });

        $('body').on('click', '.editButton', function () {
            $('.error_messages').html('');
        });

        $('#ajaxlocationFormModal').on('hidden.bs.modal', function() {
            console.log('lcos')
            $('.error_msgs').html('');
            $("#status option").attr("selected", false);
        });

        // Location change status
        $('body').on('click','.switch-off', function () {

            let status = 1;

            var location_id = $(this).data("id");
            var url = "{{ route('admin.locations.status', ':id') }}";
            url = url.replace(':id', location_id);

            $.ajax({
                type: "GET",
                url: url,
                data: { status },
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


        });

        $('body').on('click','.switch-on', function () {

            let status = 0;

            var location_id = $(this).data("id");
            var url = "{{ route('admin.locations.status', ':id') }}";
            url = url.replace(':id', location_id);

            $.ajax({
                type: "GET",
                url: url,
                data: { status },
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


        });

    });

</script>


@endsection
