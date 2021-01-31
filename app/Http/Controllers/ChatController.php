<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;  
use Pusher\Pusher;
use App\Events\EndCall;
use App\Events\CallToUser;
use App\Events\SendMessage;
use Illuminate\Http\Request;
use App\Events\SendCallResponse;

class ChatController extends Controller
{
    public function getUser()
    {
        return response()->json(User::where('id', '!=', auth()->id())->get(), 200);
    }
    public function getUserMessage()
    {
        return response()->json(Message::whereIn('sender_id',[ request('sender'),request('receiver') ])->whereIn('receiver_id',[ request('sender'),request('receiver') ])->get(), 200);
    }
    public function sendMessage()
    {
        if(request('file')){
            $file = request('file')->hashName();
            request('file')->store('public/files');
            $mimeType = explode('/',request('file')->getMimeType())[0];
        }
        $msg = Message::create([
            'sender_id'   => request('sender'),
            'receiver_id' => request('receiver'),
            'message'     => request('message'),
            'file'        => $file ?? null,
            'mime_type'   => $mimeType ?? null,
        ]);
        broadcast(new SendMessage(request('receiver'),request('sender'), $msg->with('sender')->latest()->first()))->toOthers();
        return response()->json($msg, 200);
    }
    public function callInit()
    {
        broadcast(new CallToUser(request('caller'),request('receiver')))->toOthers();
    }
    public function callResponse()
    {
        if(request('status') == 1)
            broadcast(new SendCallResponse(request('caller'),request('callReceiver')))->toOthers();
        broadcast(new SendCallResponse(request('caller')))->toOthers();
    }
    public function endCall()
    {
        broadcast(new EndCall(request('userId')))->toOthers();
    }
    public function auth(Request $request)
    {
        $user = $request->user();
        $socket_id = $request->socket_id;
        $channel_name = $request->channel_name;
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => false
            ]
        );
        return response(
            $pusher->presence_auth($channel_name, $socket_id, $user->id)
        );
    }
}
