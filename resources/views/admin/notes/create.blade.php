@extends('layouts.admin')

@section('title', isset($note) ? 'Edit Note': 'Create Note')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/admin/css/common.css') }}">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content">

        <a href="{{ route('admin.notes.index') }}" class="btn btn-info mt-2 mb-3">Go Back</a>

        <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5 mb-6">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="text-dark font-weight-medium">
                    <b>{{ isset($note) ? 'Edit': 'Create' }} Note</b>
                </h4>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('admin.notes.store') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="note_id" value="{{ isset($note) ? $note->id: '' }}">
                <div class="row">
                    <div class="col">

                        <div class="row mb-3">
                            <div class="col">
                                <label for="location">Location</label> <span class="star-color">*</span>
                                <select name="location" class="form-control">
                                    <option value="" selected disabled>Choose Option</option>
                                    @foreach ($locations as $location)
                                        <option @selected(old('location', isset($noteLocation) ? $noteLocation->id: '') == $location->id) value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="clinician">Clinician</label> <span class="star-color">*</span>
                                <select name="clinician" class="form-control">
                                    <option value="" selected disabled>Choose Option</option>
                                    @foreach ($clinicians as $clinician)
                                        <option @selected(old('clinician', isset($noteClinician) ? $noteClinician->id: '') == $clinician->id) value="{{ $clinician->id }}">{{ $clinician->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="error_type">Error Type</label> <span class="star-color">*</span>
                                <select name="error_type" class="form-control">
                                    <option value="" selected disabled>Choose Option</option>
                                    @foreach ($errorTypes as $errorType)
                                        <option @selected(old('error_type', isset($noteErrorType) ? $noteErrorType->id: '') == $errorType->id) value="{{ $errorType->id }}">{{ $errorType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if (isset($note))
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="note_status">Status</label> <span class="star-color">*</span>
                                    <select name="note_status" id="note_status" class="form-control">
                                        <option value="" selected disabled>Choose Option</option>
                                        <option @selected(old('note_status', $note->status) == \App\Models\Note::NOT_FIXED ) value="{{ \App\Models\Note::NOT_FIXED }}">Not Fixed</option>
                                        <option @selected(old('note_status', $note->status) == \App\Models\Note::FIXED ) value="{{ \App\Models\Note::FIXED }}">Fixed</option>
                                        <option @selected(old('note_status', $note->status) == \App\Models\Note::CONTACT_ME ) value="{{ \App\Models\Note::CONTACT_ME }}">Contact me</option>
                                        <option @selected(old('note_status', $note->status) == \App\Models\Note::UNFOUNDED ) value="{{ \App\Models\Note::UNFOUNDED }}">Unfounded</option>
                                    </select>
                                </div>
                            </div>

                            <div id="status_reason">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="status_reason">Status Reason</label> <span class="star-color">*</span>
                                        <input type="text" name="status_reason" class="form-control" placeholder="Enter reason here" value="{{ old('status_reason', $note ? $note->status_reason : '') }}">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col">
                                <label for="patient">Patient</label> <span class="star-color">*</span>
                                <input type="text" name="patient" id="patient" value="{{ old('patient', isset($notePatientName) ? $notePatientName: '') }}" class="form-control"
                                    placeholder="Patient name">
                                <ul class="ml-3" id="patient_names">
                                </ul>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="date_of_service">Date of service</label> <span class="star-color">*</span>
                                <input type="date" name="date_of_service" value="{{ old('date_of_service', isset($note) ? $note->date_of_service: '') }}" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label>Comment</label> <span style="opacity: 0.9"><small>(Optional)</small></span>
                                <textarea id="comment" name="comment" cols="10" rows="3" class="form-control"
                                    placeholder="Enter comment here...">{{ old('comment', isset($note) ? $note->comment: '') }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="row">
                    <div class="col">

                        <div class="row mb-3">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>

            </form>
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

    <script>
        $(document).ready(function() {

            var unfounded = '{{ \App\Models\Note::UNFOUNDED }}';
            var status = "{{ isset($note) ? $note->status: '' }}";
            var old_input_status = "{{ old('note_status') }}";

            if (status == unfounded || old_input_status == unfounded) {
                $('#status_reason').show();
            }
            else {
                $('#status_reason').hide();
            }

            $('#note_status').change(function() {

                var status = this.value;
                var unfounded = '{{ \App\Models\Note::UNFOUNDED }}';

                if (status == unfounded) {
                    $('#status_reason').show();
                }
                else {
                    $('#status_reason').hide();
                }
            });
        });
    </script>
@endsection
