<?php

namespace App\Traits;

use App\Models\User;

use Illuminate\Support\Facades\Config;

trait HandleText
{
    use HandleButton, SendMessage,CreateActionsSession;

    public $text_intent;

    public function text_index()
    {
       
        $this->find_text_intent();
        if ($this->text_intent == "live_chat") {
           
            return $this->index_live_chat();
        }
        if ($this->text_intent == "greetings") {
            $this->send_greetings_message($this->userphone);
        }
        if ($this->text_intent == "run_action_steps") {
            $this->continue_session_step();
        }else{
            http_response_code(200);
            die;
        }
        
    }

    public function show_menu_message()
    {
    }

    public function register_user(array $data)
    {
        $model = new User();
    }


    public function find_text_intent()
    {
        $message = $this->user_message_lowered;

        $greetings = Config::get("text_intentions.greetings");
        $menu = Config::get("text_intentions.menu");
        if ($this->session_model_collection->live_chat == 1) {
            $this->text_intent = "live_chat";
            return true;
        }
      
        if (in_array($message, $greetings)) {
            $this->text_intent = "greetings";
        } elseif (in_array($message, $menu)) {
            $this->text_intent = "menu";
        }
         elseif (isset($this->user_session_data['run_action_step'])) {
            if ($this->user_session_data['run_action_step'] == 1 ) {
                $this->text_intent = "run_action_steps";
            }
        } else {
            $this->text_intent = "others";
            return true;
        }
    }




    public function  runtest(array $data)
    {
        return $this->test_response($data);
    }
}
