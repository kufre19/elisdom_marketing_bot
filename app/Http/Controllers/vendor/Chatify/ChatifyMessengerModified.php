<?php

namespace App\Http\Controllers\vendor\Chatify;

use Chatify\Facades\ChatifyMessenger as Chatify;
use App\Models\ChMessage as Message;


class ChatifyMessengerModified extends Chatify  {

    /**
     * Fetch & parse message and return the message card
     * view as a response(My Modified Version!).
     *
     * @param Message $prefetchedMessage
     * @param int $id
     * @return array
     */
    public static function parseMessagemodified($sender = null, $prefetchedMessage = null, $id = null)
    {
        $msg = null;
        $attachment = null;
        $attachment_type = null;
        $attachment_title = null;
        if (!!$prefetchedMessage) {
            $msg = $prefetchedMessage;
        } else {
            $msg = Message::where('id', $id)->first();
            if(!$msg){
                return [];
            }
        }
        if (isset($msg->attachment)) {
            $attachmentOBJ = json_decode($msg->attachment);
            $attachment = $attachmentOBJ->new_name;
            $attachment_title = htmlentities(trim($attachmentOBJ->old_name), ENT_QUOTES, 'UTF-8');
            $ext = pathinfo($attachment, PATHINFO_EXTENSION);
            $attachment_type = in_array($ext, Chatify::getAllowedImages()) ? 'image' : 'file';
        }
        return [
            'id' => $msg->id,
            'from_id' => $msg->from_id,
            'to_id' => $msg->to_id,
            'message' => $msg->body,
            'attachment' => (object) [
                'file' => $attachment,
                'title' => $attachment_title,
                'type' => $attachment_type
            ],
            'timeAgo' => $msg->created_at->diffForHumans(),
            'created_at' => $msg->created_at->toIso8601String(),
            'isSender' => ($msg->from_id == $sender),
            'seen' => $msg->seen,
        ];
    }


}