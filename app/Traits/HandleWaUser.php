<?php

namespace App\Traits;

use App\Models\WaUser;

trait HandleWaUser {


    private $user;
    

    public function WaUser($user_id=""){
        if($user_id == "")
        {
            $user_id = $this->userphone;
        }

        $WaUser_model = new WaUser();
        return $this->user = $WaUser_model->where("phone",$user_id)->first();
    }


}