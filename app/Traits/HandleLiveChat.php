<?php
namespace App\Traits;

use App\Models\ChatRequest;
use App\Models\Session;
use App\Models\User;

trait HandleLiveChat {
    use MessagesType, SendMessage;
    //    if chat turned on and text is sent to this index then send the text to chat app to pass to chatify and pusher**
    // i want you to take the text from the chat app and send it to chatify and pusher**
    // next when chat request is created i want you to later on write notification to tell admin about new message 
    // then when request is accepted need for you to send a hello message with admin name before a message is continued
    
    /**
     *  not complete 
     */
    public function index_live_chat()
    {

    
        $session_model = new Session();
        $chat_model = new ChatRequest();
        $session = $session_model->where('whatsapp_id', '=', $this->userphone)->first();
        if ($session){
            if($session->live_chat == 1){
                // continue chatting
                $this->continue_chat_session();
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

     /**
      * 
      */
    public function auto_admin_greet_message($admin_id,$customer_wa_id)
    {
        $user_model = new User();
        $user = $user_model->where("id",$admin_id)->first();
        $name = $user->name;
        $greeting_text = "Hi Hello my name is {$name}, how may help you today?";
        $message = $this->make_text_message($greeting_text,$customer_wa_id);
        $this->send_post_curl($message);

    }

    public function send_message_to_admin()
    {

    }

    public function send_message_to_user()
    {
        
    }

}