@extends('layouts.header')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div style="display: flex; flex-direction:row; justify-content:space-between;">
                    <h6 class="card-title">View ticket details</h6>

                    @if(auth()->user()->role->name != "User" && $ticket->status == "Open" && ($ticket->assign_by == auth()->user()->id))
                    <div>
                        <form method="post" action="{{ url('tickets/acknowledgement/'.$ticket->id) }}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="ticketing_type" value="Acknowledgement">
    
                            <button type="submit" class="btn btn-success">
                                Acknowledge Ticket
                            </button>
                        </form>
    
                        <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#closeTicket{{ $ticket->id }}">
                            Attach an Image or Screenshot
                        </button>
                        <form method="post" action="{{ url('tickets/acknowledgement/'.$ticket->id) }}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="ticketing_type" value="Awaiting 1st acknowledgement">
                            
                            <button type="submit" class="btn btn-success">
                                Awaiting 1st acknowledgement
                            </button>
                        </form>
                        <form method="post" action="{{ url('tickets/acknowledgement/'.$ticket->id) }}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="ticketing_type" value="Awaiting 2nd acknowledgement">

                            <button type="submit" class="btn btn-success">
                                Awaiting 2nd acknowledgement
                            </button>
                        </form>
                        <form method="post" action="{{ url('tickets/acknowledgement/'.$ticket->id) }}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="ticketing_type" value="Closing Ticket">

                            <button type="submit" class="btn btn-warning">
                                Closing Ticket
                            </button>
                        </form>
                        <form method="post" action="{{ url('tickets/acknowledgement/'.$ticket->id) }}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="ticketing_type" value="Resolving Ticket">

                            <button type="submit" class="btn btn-primary">
                                Resolving a Ticket
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <dl class="row">
                            <dt class="col-sm-3 text-right">Ticket # :</dt>
                            <dd class="col-sm-9">#{{ str_pad($ticket->id, '7', 0, STR_PAD_LEFT) }}</dd>
                            <dt class="col-sm-3 text-right">Viber # :</dt>
                            <dd class="col-sm-9">{{ $ticket->viber_number }}</dd>
                            <dt class="col-sm-3 text-right">Department :</dt>
                            <dd class="col-sm-9">{{ $ticket->department->name }}</dd>
                            <dt class="col-sm-3 text-right">Status :</dt>
                            <dd class="col-sm-9">{{ $ticket->status }}</dd>
                            <dt class="col-sm-3 text-right">Priority :</dt>
                            <dd class="col-sm-9">{{ $ticket->priority }}</dd>
                        </dl>
                    </div>
                    <div class="col-lg-6">
                        <dl class="row">
                            <dt class="col-sm-3 text-right">Ticket by :</dt>
                            <dd class="col-sm-9">{{ $ticket->createdBy->name }}</dd>
                            <dt class="col-sm-3 text-right">Assigned to :</dt>
                            <dd class="col-sm-9">
                                @if ($ticket->assignTo)
                                    {{ $ticket->assignTo->name }}
                                @else
                                    No IT assign yet
                                @endif
                            </dd>
                            <dt class="col-sm-3 text-right">Category :</dt>
                            <dd class="col-sm-9">
                                @if($ticket->category)
                                {{ $ticket->category->name }}
                                @else
                                No category yet
                                @endif
                            </dd>
                            <dt class="col-sm-3 text-right">Date Created :</dt>
                            <dd class="col-sm-9">{{ date('M d Y', strtotime($ticket->created_at)) }}</dd>
                            <dt class="col-sm-3 text-right">Proof :</dt>
                            <dd class="col-sm-9">
                                @if($ticket->proof)
                                    <a href="{{ url($ticket->proof) }}" target="_blank">
                                        <i class="fa fa-file"></i>
                                    </a>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
                <hr>
                <h6>Ticket Thread</h6>
                <div class="ibox ibox-primary border border-primary">
                    <div class="ibox-head">{{ $ticket->subject }}</div>
                    <div class="ibox-body">
                        <p>{!! nl2br(e(strip_tags($ticket->task))) !!}</p>
                        @if($ticket->attachment)
                        <img src="{{ url($ticket->attachment) }}" style="width:min-content; height:400px;">
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Comments</div>
                            </div>
                            <div class="ibox-body">
                                <ul class="media-list media-list-divider m-0">
                                    @if (count($ticket->ticketing_thread) > 0)
                                        @foreach ($ticket->ticketing_thread as $thread)
                                        <li class="media">
                                            <a class="media-img" href="javascript:;">
                                                <img class="img-circle" src="{{ asset('assets/img/admin-avatar.png') }}" width="40">
                                            </a>
                                            <div class="media-body">
                                                <div class="media-heading">{{ $thread->user->name }} <small class="float-right text-muted">{{ $thread->updated_at->diffForHumans() }}</small></div>
                                                <div class="font-13">{!! nl2br(e(strip_tags($thread->comment))) !!}</div>
                                                <div style="display: flex; flex-direction:row; column-gap:5px; margin-top:20px;">
                                                    <div>
                                                        <small><a class="text-primary" onclick="editComment({{ $thread->id }})">Edit</a></small>
                                                    </div>
                                                    <form method="POST" id="deleteForm" action="{{ url('tickets/delete_comment/'.$thread->id) }}">
                                                        @csrf
                                                        
                                                        <small><a class="text-primary deleteComment">Delete</a></small>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    @else
                                        <li>No comments...</li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <form method="POST" action="{{ url('/tickets/comment/'.$ticket->id) }}">
                            @csrf
                            
                            <input type="hidden" name="threadId">

                            <div class="row">
                                <div class="col-md-12">
                                    Comment :
                                    <textarea name="comment" class="form-control input-sm" placeholder="Write a comment..." cols="30" rows="10"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success float-right mt-4">Comment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('tickets.close_ticket')
@endsection

@section('js')
<script>
    function editComment(id)
    {
        $.ajax({
            type: "POST",
            url:"{{ url('tickets/get_comment') }}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data) {
                $("[name='threadId']").val(data.id)
                $(".summernote").summernote('code', data.comment)
            }
        })
    }

    function deleteComment()
    {
        $(this).closest('#deleteForm').submit()
    }

    $(document).ready(function() {

        $(document).on('click', '.deleteComment', function() {
            $(this).closest('form').submit();
        })
    })
</script>
@endsection