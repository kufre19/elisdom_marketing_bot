<?php

namespace App\Traits;

use App\Models\FaqsModel;

use Illuminate\Support\Facades\Config;

trait HandleButton
{
    use SendMessage, HandleLiveChat;

    public function button_index()
    {
        
        if ($this->button_id == "chat us") {
            return $this->index_live_chat();
        }
      
    }


    public function get_command_and_value_button()
    {
        $data = explode(":", $this->button_id);
        return $data;
    }

    public function determin_button()
    {
    }



    public function test_response(array $data)
    {
        dd($this->username);
    }
}
