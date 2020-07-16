<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\Http\Resources\TicketResource;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    private $ticket;

    function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(Request $request)
    {
        $pendingTickets = TicketResource::collection(Ticket::where(['group_slug' => $request->group_slug, 'status' => 0, 'assigned_to' => Auth::user()->id])->get()->sortBy('priority', true));
        $activeTickets = TicketResource::collection(Ticket::where(['group_slug' => $request->group_slug, 'status' => 1, 'assigned_to' => Auth::user()->id])->get()->sortBy('priority', true));
        $completedTickets = TicketResource::collection(Ticket::where(['group_slug' => $request->group_slug, 'status' => 2, 'assigned_to' => Auth::user()->id])->get()->sortBy('priority', true));
        return [
            $pendingTickets,
            $activeTickets,
            $completedTickets
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // If the ticket is self made then we use the author value;
        if ($request->selfMade) {
            $request->merge(['created_by' => Auth::user()->id]);
        } else {
            $request->merge(['created_by' => $request->author]);
        }

        $uuid = Str::uuid();

        if ($request->autoAssign) {
            $group = Group::where('slug', $request->group_slug)->first();
            foreach ($group->users as $user) {
                if ($user->hasPermissionTo($request->group_slug, 'view-tickets-dashboard')) {
                    $ticket = $this->ticket->create([
                        'title' => $request->title,
                        'priority' => $request->priority['value'],
                        'status' => 0,
                        'group_slug' => $request->group_slug,
                        'description' => $request->description,
                        'created_by' => $request->created_by,
                        'assigned_to' => $user->id,
                        'uuid' => $uuid
                    ]);
                }
            }
        } else {
            $request->merge(['assigned_to' => $request->users]);
            $ticket = $this->ticket->create([
                'title' => $request->title,
                'priority' => $request->priority['value'],
                'status' => 0,
                'group_slug' => $request->group_slug,
                'description' => $request->description,
                'created_by' => $request->created_by,
                'assigned_to' => $request->assigned_to,
                'uuid' => $uuid
            ]);
        }
        return response()->json([
            'message' => 'Ticket created',
            'payload' => TicketResource::make($ticket)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Ticket $ticket
     * @return array
     */
    public function update(Request $request, Ticket $ticket)
    {
        $uuid = Str::uuid();
        $oldUuid = $ticket->uuid;

        $ticket->update([
            'status' => $request->status,
            'uuid' => $uuid,
            'updated_at' => now()
        ]);

        Ticket::where('uuid', $oldUuid)->delete();

        $pendingTickets = TicketResource::collection(Ticket::where(['group_slug' => $request->group_slug, 'status' => 0, 'assigned_to' => Auth::user()->id])->get()->sortBy('priority', true));
        $activeTickets = TicketResource::collection(Ticket::where(['group_slug' => $request->group_slug, 'status' => 1, 'assigned_to' => Auth::user()->id])->get()->sortBy('priority', true));
        $completedTickets = TicketResource::collection(Ticket::where(['group_slug' => $request->group_slug, 'status' => 2, 'assigned_to' => Auth::user()->id])->get()->sortBy('priority', true));
        return [
            'message' => 'Ticket updated',
            'payload' => [
                $pendingTickets,
                $activeTickets,
                $completedTickets
            ]
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
