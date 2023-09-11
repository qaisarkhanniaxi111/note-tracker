@extends('layouts.admin')

@section('title', 'Notes')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
@endsection

@section('content')
<div class="content-wrapper">
	<div class="content">


        <a href="{{ route('admin.notes.create') }}" class="btn btn-info mt-2 mb-4">New Note</a>

        <div class="d-flex justify-content-between mb-3">
            <h4 class="text-dark font-weight-medium"><b>Notes</b></h4>
        </div>
        <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5 mb-6">

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
                        <option value="" selected>Choose Status</option>
                        <option value="{{ \App\Models\Note::NOT_FIXED }}">Not Fixed</option>
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

            <div class="table-responsive">
            <table id="notes-table" class="table mt-3 table-striped text-center" style="width:100%">
                <thead>
                    <th>Location</th>
                    <th>Clinician</th>
                    <th>Patient</th>
                    <th>Error Type</th>
                    <th>Date Of Service</th>
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

        var table = $("#notes-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.notes.index') }}",
                data: function (d) {

                    d.location = $('#location').val(),
                    d.clinician = $('#clinician').val(),
                    d.status = $('#status').val()
                }
            },
            columns: [
                {data: 'location'},
                {data: 'clinician'},
                {data: 'patient'},
                {data: 'error_type'},
                {data: 'date_of_service'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
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

            // $('#location').val('');
            // $('#clinician').val('');
            // $('#status').val('');

            table.draw();
        });



        // Deleting Post
        $('body').on('click', '.deleteButton', function () {

            var note_id = $(this).data("id");

            if(confirm("Are You sure want to delete !")){
                $.ajax({
                type: "DELETE",
                url: "{{ route('admin.notes.destroy', '') }}" +'/'+ note_id,
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

    });

</script>


@endsection
