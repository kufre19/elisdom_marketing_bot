<?php

namespace App\Traits;


trait HandleWaUser {

    

    public function index($user_id=""){
        if($user_id == "")
        {
            $user_id = $this->userphone;
        }

        


    }

}