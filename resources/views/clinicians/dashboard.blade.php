@extends('layouts.clinician')

@section('title', 'Clinician Dashboard')

@section('content')
    <section id="modals">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Invite</h5>
                        <button type="button" id="btnclose" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-6">
                            <input type="hidden" id="hiddenid">
                            <label class="form-label" for="">First Name</label>
                            <input class="form-control" id="firstname" type="text" name="field-name"
                                placeholder="Enter First Name">
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="">Last Name</label>
                            <input class="form-control" id="lastname" type="text" name="field-name"
                                placeholder="Enter Last Name">
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="">Invite Address</label>
                            <input class="form-control" id="emailaddress" type="text" name="field-name"
                                placeholder="Enter Email Address">
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="">Is Testing User?</label>
                            <select class="form-select" id="testinguser">
                                <option>No</option>
                                <option>Yes</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="close" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" id="actionnew" class="btn btn-primary"><span
                                id="whattodo">Invite</span></button>
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
                        <span class="badge bg-primary rounded-pill small" id="total">{{ $notFixedCounts ? $notFixedCounts: 0 }} Total</span>
                    </div>
                </div>
                <div class="col-12 col-lg-auto d-flex align-items-center">

                    <button class="flex-shrink-0 btn btn-primary d-flex align-items-center" id="create"
                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <span class="d-inline-block me-2 text-primary-light">
                            <svg viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                style="width: 16px;height: 16px">
                                <path
                                    d="M12.6667 1.33334H3.33333C2.19999 1.33334 1.33333 2.20001 1.33333 3.33334V12.6667C1.33333 13.8 2.19999 14.6667 3.33333 14.6667H12.6667C13.8 14.6667 14.6667 13.8 14.6667 12.6667V3.33334C14.6667 2.20001 13.8 1.33334 12.6667 1.33334ZM10.6667 8.66668H8.66666V10.6667C8.66666 11.0667 8.4 11.3333 8 11.3333C7.6 11.3333 7.33333 11.0667 7.33333 10.6667V8.66668H5.33333C4.93333 8.66668 4.66666 8.40001 4.66666 8.00001C4.66666 7.60001 4.93333 7.33334 5.33333 7.33334H7.33333V5.33334C7.33333 4.93334 7.6 4.66668 8 4.66668C8.4 4.66668 8.66666 4.93334 8.66666 5.33334V7.33334H10.6667C11.0667 7.33334 11.3333 7.60001 11.3333 8.00001C11.3333 8.40001 11.0667 8.66668 10.6667 8.66668Z"
                                    fill="currentColor"></path>
                            </svg>
                        </span>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8">
        <div class="px-4 pb-10 pt-10 mb-6 bg-white rounded shadow">

            <table class="table mb-0 table-borderless table-striped small text-center">
                <thead>
                    <tr class="text-secondary">
                        <th class="pt-4 pb-3 px-6">Location</th>
                        <th class="pt-4 pb-3 px-6">Clinician</th>
                        <th class="pt-4 pb-3 px-6">Patient</th>
                        <th class="pt-4 pb-3 px-6">Error Type</th>
                        <th class="pt-4 pb-3 px-6">Status</th>
                        <th class="pt-4 pb-3 px-6">Status Reason</th>
                        <th class="pt-4 pb-3 px-6">Comment</th>
                        <th class="pt-4 pb-3 px-6">Date of Service</th>
                </thead>
                <tbody>
                    @foreach ($notes as $note)
                        <tr>
                            <td>{{ $note->location ? $note->location->name: '' }}</td>
                            <td>{{ $note->clinician ? $note->clinician->name: '' }}</td>
                            <td>{{ $note->patient ? $note->patient->name: '' }}</td>
                            <td>{{ $note->errorType ? $note->errorType->name: '' }}</td>
                            <td>
                                @if ($note->status == \App\Models\Note::NOT_FIXED)
                                    <span class="badge text-bg-success" style="background: red; color:white">Not Fixed</span>
                                @elseif($note->status == \App\Models\Note::FIXED)
                                    <span class="badge text-bg-success" style="background: green; color:white">Fixed</span>
                                @elseif($note->status == \App\Models\Note::CONTACT_ME)
                                    <span class="badge text-bg-success" style="background: navy; color:white">Contact me</span>
                                @elseif($note->status == \App\Models\Note::UNFOUNDED)
                                <span class="badge text-bg-success" style="background: #BFF31F; color:black">Unfounded</span>
                                @endif
                            </td>
                            <td>{{ $note ? $note->status_reason: '' }}</td>
                            <td>{{ $note ? $note->comment: '' }}</td>
                            <td>{{ $note ? date('m-d-Y', strtotime($note->date_of_service)): '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </section>
@endsection
