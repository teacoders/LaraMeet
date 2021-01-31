<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('receiveMessage.{id}', function ($user, $id) {   
  if($user->id == $id)
    return true;
});
Broadcast::channel('callToUser.{id}', function ($user, $id) {   
  if($user->id == $id)
    return true;
});
Broadcast::channel('sendCallResponse.{id}', function ($user, $id) {   
  if($user->id == $id)
    return true;
});
Broadcast::channel('endCall.{id}', function ($user, $id) {   
  if($user->id == $id)
    return true;
});
