<?php

namespace App\Jobs;

use App\Models\WaUser;
use App\Traits\MessagesType;
use App\Traits\SendMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,MessagesType,SendMessage;
    protected $language;
    protected $validity_date;
    protected $number_of_products;
    protected $products;

    /**
     * Create a new job instance.
     *
     * @param string $language
     * @param string $validity_date
     * @param int $number_of_products
     * @param string $products
     */
    public function __construct($language, $validity_date, $number_of_products, $products)
    {
        $this->language = $language;
        $this->validity_date = $validity_date;
        $this->number_of_products = $number_of_products;
        $this->products = $products;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parameters = [
            [
                "type"=>"text",
                "text"=>$this->number_of_products
            ],
            [
                "type"=>"text",
                "text"=>$this->products
            ],
            [
                "type"=>"text",
                "text"=>$this->validity_date
            ],
        ];
        $user_model = new WaUser();
        $users = $user_model->get();
        
        foreach ($users as $key => $user) {
            $template_message = $this->make_interactive_template_message($parameters, $user->phone, "campaign", $this->language);
            try {
                $response = $this->send_post_curl($template_message);

                // Check if the API response is an error status
                if (isset($response['error'])) {
                    // Log the error
                    \Log::error('Error sending message to ' . $user->phone . ': ' . $response['error']['message']);
                }
            } catch (\Exception $e) {
                // Log the exception
                if (isset($response['error'])) {
                    // Log the error
                    \Log::error('Error sending message to ' . $user->phone . ': ' . $response['error']['message']);
                }
            }
        }
    }
}
