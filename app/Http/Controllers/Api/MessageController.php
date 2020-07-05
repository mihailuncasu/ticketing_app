<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSentEvent;
use App\Group;
use App\Http\Resources\MessageResource;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    private $message;

    function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $group = Group::where('slug', $request->group_slug)->first();
        return MessageResource::collection($this->message->where('group_id', $group->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $group = Group::where('slug', $request->group_slug)->first();
        $message = $this->message->create([
            'user_id' => Auth::user()->id,
            'group_id' => $group->id,
            'text' => $request->text
        ]);

        event(new MessageSentEvent($message));

        return response()->json([
            'message' => 'Message sent',
           // 'payload' => MessageResource::make($message)
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
