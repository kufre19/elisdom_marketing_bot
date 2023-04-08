<?php

namespace App\Http\Controllers;

use App\Jobs\SendCampaign;
use App\Models\WaUser;
use App\Traits\HandleTemplateMessage;
use App\Traits\MessagesType;
use App\Traits\SendMessage;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;


class WaUserController extends Controller
{
    use SendMessage, MessagesType, HandleTemplateMessage;
    protected $model = WaUser::class;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view("platform::WaUsers.index");
    }

    public function list_contact()
    {

        $customers = WaUser::paginate(10);
        return view('platform::WaUsers.list', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required',
            'whatsapp_number' => ['required', 'unique:wa_users,phone'],
        ], [
            'whatsapp_number.unique' => 'This phone number is already in use.',
        ]);
    
        // Display success message
        Toast::success('Customer contact saved successfully!');
    
        $waUser = new WaUser;
        $waUser->name = $validatedData['customer_name'];
        $waUser->phone = $validatedData['whatsapp_number'];
        $waUser->save();
    
        return redirect()->to('wa-user');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $waUser = WaUser::findOrFail($id);
        $waUser->delete();

        Toast::error('Customer contact deleted!');

        return redirect()->to('wa-users/list');
    }

    public function send_campaign(Request $request)
    {
        $language = $request->input("language");
        $validity_date = $request->input("validity_date");
        $number_of_products = $request->input("number_of_products");
        $products = str_replace("\r\n","",$request->input("products"));
    
        $job = new SendCampaign($language, $validity_date, $number_of_products, $products);
        dispatch($job);
        Toast::info("Campaign sent to queue for processing.");
        return redirect()->back();
    }
    
}
