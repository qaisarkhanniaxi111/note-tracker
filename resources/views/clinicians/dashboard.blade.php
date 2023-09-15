@extends('layouts.clinician')

@section('title', 'Clinician Dashboard')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- notification css cdn  -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/toaster/toastr.min.css') }}">
@endsection

@section('content')
    <section id="modals">
        <div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit  Note</h5>
                        <button type="button" id="btnclose" class="btn-close" data-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <span id="general_note_error" class="text-danger error_msgs"></span>

                        <input type="hidden" id="note_id">
                        <div class="mb-6">
                            <label class="form-label" for="">Status</label>
                            <select name="note_status" id="note_status" class="form-select">
                                <option value="" selected>Choose Option</option>
                                <option value="{{ \App\Models\Note::NOT_FIXED }}">Not Fixed</option>
                                <option value="{{ \App\Models\Note::FIXED }}">Fixed</option>
                                <option value="{{ \App\Models\Note::CONTACT_ME }}">Contact me</option>
                                <option value="{{ \App\Models\Note::UNFOUNDED }}">Unfounded</option>
                            </select>
                            <span id="note_status_error" class="error_msgs text-danger text-center"></span>
                        </div>

                        <div class="mb-6" id="status_reason_div">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" name="status_reason" id="status_reason" cols="30" rows="5" placeholder="Enter reason"></textarea>
                            <span id="note_status_reason_error" class="error_msgs text-danger text-center"></span>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveBtn" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="py-8 px-6">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="col-12 col-lg-auto mb-4 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0 me-2">Not Fixed</h4>
                        <span class="badge bg-primary rounded-pill small"> <span id="not_fixed_note_counts">{{ $notFixedCounts ? $notFixedCounts: 0 }}</span> Total</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8">
        <div class="px-4 pb-10 pt-10 mb-6 bg-white rounded shadow">

            <div class="container" style="margin-bottom: 30px">
                <div class="row">
                  <div class="col-sm">
                    <select name="location" id="location" class="form-control">
                        <option value="" selected>Choose Location</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-sm">
                    <select name="clinician" id="clinician" class="form-control">
                        <option value="" selected>Choose Clinician</option>
                        @foreach ($clinicians as $clinician)
                            <option value="{{ $clinician->id }}">{{ $clinician->name }}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-sm">
                    <select name="status" id="status" class="form-control">
                        <option value="">Choose Status</option>
                        <option selected value="{{ \App\Models\Note::NOT_FIXED }}">Not Fixed</option>
                        <option value="{{ \App\Models\Note::FIXED }}">Fixed</option>
                        <option value="{{ \App\Models\Note::CONTACT_ME }}">Contact me</option>
                        <option value="{{ \App\Models\Note::UNFOUNDED }}">Unfounded</option>
                    </select>
                  </div>

                  <div class="col-sm">
                    <button id="remove_filter_btn" class="btn btn-default" style="background: #F1F5FB">Remove Filters</button>
                  </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <table id="notes-table" class="table mb-0 table-borderless table-striped small text-center">
                        <thead>
                            <tr class="text-secondary">
                                <th class="pt-4 pb-3 px-6">Date of Service</th>
                                <th class="pt-4 pb-3 px-6">Location</th>
                                <th class="pt-4 pb-3 px-6">Patient</th>
                                <th class="pt-4 pb-3 px-6">Status</th>
                                <th class="pt-4 pb-3 px-6">Status Reason</th>
                                <th class="pt-4 pb-3 px-6">Comment</th>
                                <th class="pt-4 pb-3 px-6">Error Type</th>
                                <th class="pt-4 pb-3 px-6">Actions</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('scripts')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="{{ asset('assets/admin/plugins/toaster/toastr.min.js') }}"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
<script>

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $("#notes-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('clinician.notes.index') }}",
                data: function (d) {

                    d.location = $('#location').val(),
                    d.clinician = $('#clinician').val(),
                    d.status = $('#status').val()
                }
            },
            columns: [
                {data: 'date_of_service'},
                {data: 'location'},
                {data: 'patient'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'status_reason', name: 'status_reason'},
                {data: 'comment'},
                {data: 'error_type'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#location, #clinician, #status').change(function() {
            table.draw();
        });

        $('#remove_filter_btn').click(function() {

            $("#location option:selected").prop("selected", false);
            $("#clinician option:selected").prop("selected", false);
            $("#status option:selected").prop("selected", false);

            table.draw();
        });



        // Editing note
        $('body').on('click', '.editButton', function () {

            var note_id = $(this).data("id");
            var url = "{{ route('clinician.notes.edit', ':id') }}";
            url = url.replace(':id', note_id);

            $.ajax({
            type: "GET",
            url: url,
            success: function (data) {

                if (data.note) {
                    $('#editNoteModal').modal('show');
                    $('#note_id').val(data.note.id);
                    $("#note_status option[value='"+ data.note.status +"']").attr("selected", true);
                    $('#status_reason').val(data.note.status_reason);

                    if (data.note.status == '{{ \App\Models\Note::UNFOUNDED }}') {
                        $('#status_reason_div').show();
                    }
                    else {
                        $('#status_reason_div').hide();
                    }
                }

                // table.draw();
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

        // Update note
        $('body').on('click', '#saveBtn', function () {

            $('#saveBtn').html('Saving...');
            $('#saveBtn').attr('disabled', true);

            var note_id = $('#note_id').val();
            var note_status = $('#note_status').val();
            var status_reason = $('#status_reason').val();

            $.ajax({
            type: "POST",
            data: { note_id, note_status, status_reason },
            url: "{{ route('clinician.notes.store') }}",
            success: function (data) {

                if (data.message) {
                    $('#editNoteModal').modal('hide');
                    toastr['success'](data.message)
                }

                if (data.not_fixed_note_counts) {
                    $('#not_fixed_note_counts').html(data.not_fixed_note_counts);
                }
                else {
                    $('#not_fixed_note_counts').html(0);
                }

                table.draw();

                $('#saveBtn').html('Save');
                $('#saveBtn').attr('disabled', false);
            },
            error: function (data) {

                if(data.responseJSON.errors){
                    $('#note_status_error').html(data.responseJSON.errors.note_status);
                    $('#note_status_reason_error').html(data.responseJSON.errors.status_reason);
                }
                else {
                    $('#general_note_error').html('Something went wrong, please refresh webpage and try again, if still problem persist contact with administrator');
                }

                $('#saveBtn').html('Save');
                $('#saveBtn').attr('disabled', false);

            }
        });


        });

        $('#editNoteModal').on('hidden.bs.modal', function() {
            $('.error_msgs').html('');
            $("#note_status option").attr("selected", false);
        });


        $('#status_reason_div').hide();
        var unfounded = '{{ \App\Models\Note::UNFOUNDED }}';

        $('#note_status').change(function() {

            var status = this.value;
            var unfounded = '{{ \App\Models\Note::UNFOUNDED }}';

            if (status == unfounded) {
                $('#status_reason_div').show();
            }
            else {
                $('#status_reason_div').hide();
            }
        });

    });

</script>
@endsection
