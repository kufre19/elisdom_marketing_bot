<?php

namespace App\Http\Controllers;

use App\Models\ChatRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRequestController extends Controller
{
    //
    public function index()
    {
        $userId = Auth::id();
        $chatRequestModel = new ChatRequest();
        $ongoing_request = $chatRequestModel->where("assigned_to", Auth::user()->id)->where("ongoing", 1)->get();
        $pending = $chatRequestModel->where("assigned_to", null)->where("ongoing", 0)->get();
        $pending_request_count = $pending->count();
        session()->put("pending_request_count", $pending_request_count);
      

        return view('vendor.platform.chatrequests.index', compact('ongoing_request', "pending"));
    }

    public function update_chat_request(Request $request, $id)
    {
        $chat_request = ChatRequest::find($id);
        if ($chat_request) {
            if ($request->input('action') === 'accept') {
                $chat_request->assigned_to = auth()->id();
                $chat_request->ongoing = true;
            } elseif ($request->input('action') === 'end') {
                $chat_request->ongoing = 2;
            }
            $chat_request->save();
            return redirect()->back()->with('success', 'Chat request updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Chat request not found!');
        }
    }
}
