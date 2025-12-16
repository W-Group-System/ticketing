<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\TicketRequest;
use App\Ticket;
use App\Ticketing;
use App\TicketingComment;
use App\TicketingThread;
use App\TicketingType;
use App\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::with('assignTo','createdBy','department')->where('created_by', auth()->user()->id)->get();
        
        return view('tickets.index', 
            array(
                'tickets' => $tickets
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        // dd($request->all());
        $tickets = new Ticket();
        $tickets->viber_number = $request->viber_number;
        $tickets->department_id = $request->department;
        $tickets->subject = $request->title;
        $tickets->task = $request->task;
        $tickets->status = 'Open';
        $tickets->created_by = auth()->user()->id;
        if ($request->hasFile('attachment'))
        {
            $file = $request->file('attachment');
            $filename = time().'-'.$file->getClientOriginalName();
            $file->move(public_path('attachment'),$filename);
            $fileName = '/attachment/'.$filename;

            $tickets->attachment = $fileName;
        }
        $tickets->save();

        toastr()->success('Successfully Saved');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::with('department','assignTo','createdBy', 'ticketing_thread.user')->findOrFail($id);

        return view('tickets.view',
            array(
                'ticket' => $ticket
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
            'priority' => ['required'],
            'category' => ['required', 'exists:categories,id']
        ]);

        $tickets = Ticket::findOrFail($id);
        $tickets->assigned_to = $request->assigned_to;
        $tickets->priority = $request->priority;
        $tickets->category_id = $request->category;
        $tickets->date_assign = date('Y-m-d');
        $tickets->assign_by = auth()->user()->id;
        $tickets->save();

        toastr()->success('Successfully Updated');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function list(Request $request)
    {
        $tickets = Ticket::with('assignTo','createdBy','department')->get();

        $it_personnels = User::where('department_id', 1)->where('status','Active')->get();
        $categories = Category::get();

        return view('tickets.list', 
            array(
                'tickets' => $tickets,
                'it_personnels' => $it_personnels,
                'categories' => $categories
            )
        );
    }

    public function assign(Request $request)
    {
        $tickets = Ticket::with('assignTo','createdBy','department')->where('assigned_to', auth()->user()->id)->get();

        return view('tickets.assign', 
            array(
                'tickets' => $tickets
            )
        );
    }

    public function acknowledgement(Request $request,$id)
    {
        // dd($request->all());
        $ticket = Ticket::findOrFail($id);
        $ticketing_type = TicketingType::where('name', $request->ticketing_type)->first();
        $ticketing_comment = TicketingComment::where('ticketing_type_id', $ticketing_type->id)->first();
        
        $thread = new TicketingThread;
        $thread->ticket_id = $ticket->id;
        $thread->comment = $ticketing_comment->information;
        $thread->user_id = auth()->user()->id;
        $thread->save();

        toastr()->success('Successfully Saved');
        return back();
    }

    public function comment(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'comment' => 'required'
        ]);
        
        if ($request->threadId)
        {
            $thread = TicketingThread::findOrFail($request->threadId);
            $thread->comment = $request->comment;
            $thread->user_id = auth()->user()->id;
            $thread->save();
        }
        else
        {
            $thread = new TicketingThread;
            $thread->ticket_id = $id;
            $thread->comment = $request->comment;
            $thread->user_id = auth()->user()->id;
            $thread->save();
        }
        
        toastr()->success('Successfully Saved');
        return back();
    }

    public function getComment(Request $request)
    {
        $thread = TicketingThread::findOrFail($request->id);
        
        return $thread;
    }

    public function deleteComment(Request $request, $id)
    {
        $thread = TicketingThread::findOrFail($id);
        $thread->delete();
        
        toastr()->success('Successfully Deleted');
        return back();
    }

    public function closeTicket(Request $request,$id)
    {
        // dd($request->all(), $id);
        $request->validate([
            'proof' => ['required', 'max:2048']
        ]);

        $ticket = Ticket::findOrFail($id);
        if ($request->hasFile('proof'))
        {
            $file = $request->file('proof');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('proof'),$name);
            $ticket->proof = '/proof/'.$name;
        }
        $ticket->status = 'Closed';
        $ticket->date_closed = date('Y-m-d');
        $ticket->save();

        $ticketing_type = TicketingType::where('name', $request->ticketing_type)->first();
        $ticketing_comment = TicketingComment::where('ticketing_type_id', $ticketing_type->id)->first();
        
        $thread = new TicketingThread;
        $thread->ticket_id = $ticket->id;
        $thread->comment = $ticketing_comment->information;
        $thread->user_id = auth()->user()->id;
        $thread->save();

        toastr()->success('Successfully Closed');
        return back();
    }
}
