<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WaUser;
use App\Traits\GeneralFunctions;
use App\Traits\HandleButton;
use App\Traits\HandleCart;
use App\Traits\HandleImage;
use App\Traits\HandleMenu;
use App\Traits\HandleSession;
use App\Traits\HandleText;
use App\Traits\MessagesType;
use App\Traits\SendMessage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BotController extends Controller
{
    use HandleText, HandleButton, HandleMenu, SendMessage, MessagesType, HandleSession, GeneralFunctions, HandleImage;

    public $user_message_original;
    public $user_message_lowered;
    public $button_id;
    public $menu_item_id;
    public $username;
    public $userphone;
    public $userfetched;
    public $message_type;
    public $user_session_data;
    public $user_session_status;
    public $wa_image_id;
    public $button_payload;

    /* 
    @$menu_item_id holds the id sent back from selecting an item from whatsapp
    @
    
    
    */

    public function __construct(Request $request)
    {
        // $data = $request->all();
        // $file = time() . '_' . rand() . '.json';
        // $destinationPath = 'upload/';

        // Storage::put($destinationPath . $file, json_encode($data));

        // exit();

        // Check if the incoming message is related to pricing/bill data



        if (!isset($request['hub_verify_token'])) {

            if (
                isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['pricing'])
                || isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['bill'])
            ) {
                return $this->message_type = "billing";

                // Ignore pricing/bill data and exit the method
            }

            if (isset($request['entry'][0]['changes'][0]["value"]['statuses'])) {
                return $this->message_type = "message_status";
                // Ignore pricing/bill data and exit the method
            }

            $this->username = $request['entry'][0]['changes'][0]["value"]['contacts'][0]['profile']['name'] ?? "Dear User";
            $this->userphone = $request['entry'][0]['changes'][0]["value"]['contacts'][0]['wa_id'];

            if (isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['text'])) {
                $this->user_message_original = $request['entry'][0]['changes'][0]["value"]['messages'][0]['text']['body'];
                $this->user_message_lowered  = strtolower($this->user_message_original);
                $this->message_type = "text";
            }

            if (isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['image'])) {
                $this->wa_image_id = $request['entry'][0]['changes'][0]["value"]['messages'][0]['image']['id'];
                $this->message_type = "image";
            }

            if (isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['button'])) {
                $this->button_payload = $request['entry'][0]['changes'][0]["value"]['messages'][0]['button']['payload'];
                $this->message_type = "button_payload";
            }




            if (isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['interactive'])) {
                $interactive_type = $request['entry'][0]['changes'][0]["value"]['messages'][0]['interactive']['type'];
                switch ($interactive_type) {
                    case 'list_reply':
                        $this->menu_item_id = $request['entry'][0]['changes'][0]["value"]['messages'][0]['interactive']['list_reply']['id'];
                        $this->message_type = "menu";

                        break;

                    case 'button_reply':

                        $this->button_id = $request['entry'][0]['changes'][0]["value"]['messages'][0]['interactive']['button_reply']['id'];
                        $this->message_type = "button";

                        break;


                    default:
                        dd("unknow command");
                        break;
                }
            }
        }
    }
    public function index(Request $request)
    {
        if (isset($request['hub_verify_token'])) {
            return response("ok",200);
            return $this->verify_bot($request);
        }

        if ($this->message_type == "billing") {
            return response("ok",200);
            exit(200);
        }

        if ($this->message_type == "message_status") {
            exit(200);
        }


        $this->fetch_user_session();
        // $this->fetch_user();
        switch ($this->message_type) {
            case 'text':
               
                $this->text_index();
                break;

            case 'button':

                $this->button_index();
                break;

            case 'menu':
                $this->menu_index();
                break;
            case 'image':
                $this->image_index();
                break;

            case "button_payload":
                $this->button_id = $this->button_payload;
                $this->button_index();
                break;

            default:
                die;
                break;
        }
    }


    public function test(Request $request)
    {
        if (isset($request['hub_verify_token'])) {
            return $this->verify_bot($request);
        }

        $this->send_text_message($this->user_message_original);
    }


    public function fetch_user()
    {
        $model = new WaUser();
        $fetch = $model->where('whatsapp_id', $this->userphone)->first();
        if (!$fetch) {
            $this->register_user();
        } else {
            $this->fetch_user_session();
        }
    }


    public function register_user()
    {
        $model = new User();
        $model->name = $this->username;
        $model->phone = $this->userphone;
        $model->save();
        $this->start_new_session();
        $this->send_greetings_message();
    }

    public function verify_bot(Request $input)
    {
        if (isset($input['hub_verify_token'])) { ## allows facebook verify that this is the right webhook
            $token  = env("VERIFY_TOKEN");
            if ($input['hub_verify_token'] === $token) {
                return $input['hub_challenge'];
                dd();
            } else {
                echo 'Invalid Verify Token';
                dd();
            }
        }
    }
}
