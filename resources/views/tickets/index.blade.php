@extends('layouts.header')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-info color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">0</h2>
                <div class="m-b-5">TICKETS</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-success color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">0</h2>
                <div class="m-b-5">OPEN</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-danger color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">0</h2>
                <div class="m-b-5">CLOSE</div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>Tickets</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#new">
                    <i class="fa fa-plus"></i>
                    Add ticket
                </button>

                @include('components.error')

                <table class="table table-bordered table-hover table-sm tables">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Ticket #</th>
                            <th>Date Created</th>
                            <th>Subject</th>
                            <th>Priority</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $key=>$ticket)
                            <tr>
                                <td>
                                    <a href="{{ url('tickets/details/'.$ticket->id) }}" class="btn btn-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                                <td>{{ str_pad($ticket->id, '7', 0, STR_PAD_LEFT) }}</td>
                                <td>{{ date('M d, Y', strtotime($ticket->created_at)) }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>{{ $ticket->priority }}</td>
                                <td>
                                    @if($ticket->assigned_to)
                                        {{ optional($ticket->assignTo)->name }}
                                    @else
                                        No IT assigned yet
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->status == "Open")
                                    <span class="badge badge-success">
                                    @elseif($ticket->status == "Closed")
                                    <span class="badge badge-danger">
                                    @endif

                                    {{ $ticket->status }}
                                    </span>
                                </td>
                            </tr>

                            {{-- @include('companies.edit') --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('tickets.new')
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $(".tables").DataTable({
            ordering: false,
            pageLength: 15
        })

        $('#summernote').summernote({
            height: 300,
            placeholder: "Write your concern here..."
        });
    })
</script>
@endsection