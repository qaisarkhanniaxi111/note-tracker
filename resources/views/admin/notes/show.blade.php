@extends('layouts.admin')

@section('title', 'Show Note')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content">

        <a href="{{ route('admin.notes.index') }}" class="btn btn-info mt-2 mb-3">Go Back</a>

        <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5 mb-6">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="text-dark font-weight-medium">
                    <b>Show Note</b>
                </h4>
            </div>

            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>Location</th>
                        <td>{{ $note->location ? $note->location->name: '' }}</td>
                    </tr>
                    <tr>
                        <th>Clinician</th>
                        <td>{{ $note->clinician ? $note->clinician->name: '' }}</td>
                    </tr>

                    <tr>
                        <th>Patient</th>
                        <td>{{ $note->patient ? $note->patient->name: '' }}</td>
                    </tr>

                    <tr>
                        <th>Error Type</th>
                        <td>{{ $note->errorType ? $note->errorType->name: '' }}</td>
                    </tr>

                    <tr>
                        <th>Date of service</th>
                        <td>{{ $note ? date('m-d-Y', strtotime($note->date_of_service)): '' }}</td>
                    </tr>

                    <tr>
                        <th>Created At</th>
                        <td>{{ $note ? $note->created_at->diffForHumans(): '' }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            @switch($note->status)

                                @case(0)
                                    <span class="badge badge-danger">Not Fixed</span>
                                @break

                                @case(1)
                                    <span class="badge badge-success">Fixed</span>
                                @break

                                @case(2)
                                    <span class="badge badge-info">Contact me</span>
                                @break

                                @case(3)
                                    <span class="badge badge-warning">Unfounded</span>
                                @break

                            @endswitch
                        </td>


                    </tr>

                    <tr>
                        <th>Status Reason</th>
                        <td>{{ $note ? $note->status_reason: '' }}</td>
                    </tr>

                    <tr>
                        <th>Comment</th>
                        <td>{{ $note ? strip_tags($note->comment): '' }}</td>
                    </tr>


                </thead>
            </table>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $('#comment').summernote({
            placeholder: 'Enter description here ...',
            tabsize: 10,
            height: 100,
            toolbar: [
                ['font', ['bold', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link']],
            ]
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#patient').keypress(function() {

                $('#patient_names').show();
                var patientName = $('#patient').val();

                $.ajax({
                    url: "{{ route('admin.find.patients') }}",
                    type: "GET",
                    data: { patientName },
                    dataType: 'json',
                    success: function (data) {

                        $('#patient_names').html('');

                        $.each(data.patient_names, function(index, item) {

                            $('#patient_names').append('<li>'+item.name+'</li>');

                        });

                    },
                    error: function (response) {

                        if(response.responseJSON.error){
                            // toastr['error'](response.responseJSON.error)
                        }
                        else {
                            // toastr['error']('Something went wrong please refresh webpage and try again, If still problem persist contact with administrator');
                        }
                    }
                });
            });

            $('#patient').focusout(function() {
                $('#patient_names').hide();
            })
        });
    </script>
@endsection
