<?php
namespace App\Traits;

use App\Models\ChatRequest;
use App\Models\Session;

trait HandleLiveChat {
        // if not turn it on; write function to turn it on
        // then create a new chat request session
    //    if chat turned on and text is sent to this index then send the text to chat app to pass to chatify and pusher
    // i want you to take the text from the chat app and send it to chatify and pusher
    
    public function index_live_chat()
    {
        echo "here";
        die;
        $session_model = new Session();
        $chat_model = new ChatRequest();
        $session = $session_model->where('whatsapp_id', '=', $this->userphone)->first();
        if ($session){
            if($session->live_chat == 1){
                // continue chatting
            }else{
                $session->live_chat = 1;
                $session->save();
                $this->new_chat_request($this->userphone);
            }
        }
       
        


    }


    public function new_chat_request($user){
        // create a new chat request session
        $chat_request = new ChatRequest();
        // $chat_request->assigned_to = $customer_reps;
        $chat_request->customer_id = $user;
        $chat_request->save();

        // update the chat session column of chatting_with
      
       
    }


    /**
     * this will continue already started chat session
     */

     public function continue_chat_session(){
     }

}