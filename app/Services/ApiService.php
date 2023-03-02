<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use Illuminate\Support\Str;
use RuntimeException;
use Exception;
use App\Exceptions\EmailSendException
use Illuminate\Database\Eloquent\EmailSendException;
use Mail;

/**
 * You don't need to do anything here. This is just to help
 */
class ApiService
{
    /**
     * Create a new discount code for an affiliate
     *
     * @param Merchant $merchant
     *
     * @return array{id: int, code: string}
     */
    public function createDiscountCode(Merchant $merchant): array
    {
        return [
            'id' => rand(0, 100000),
            'code' => Str::uuid()
        ];
    }

    /**
     * Send a payout to an email
     *
     * @param  string $email
     * @param  float $amount
     * @return void
     * @throws RuntimeException
     */
    public function sendPayout(string $email, float $amount)
    {
        


         $email = is_validated($email);


            try {


            $getUser = User::where('email',$email)->first();

             if ($getUser) { 

               

                
                $checkMerchant = Merchant::where('user_id',$getUser->id)->first();

                $doamin = $checkMerchant->domain;
                $toName = $getUser->name;
                $toEmail = $email;




                 $body = 'Please find order payment/amount (' .$amount. ')';

                 

                    
                    $data = array( 'site'=> Statuses::OSS, 
                                   'name' => $toName,
                                   'doamin' => $doamin,
                                   'body' => $body,
                             
                                    );



   

                   // dd($to_name); 
   
                    Mail::send('emails.registerEmail', $data, function($message) use($toEmail,$toName) {
                         $message->to($toEmail, $toName)->subject('Payout Detail');
                         $message->from('<abc@xyz.com>','<COMPNAY_NAME>');
                      });



             }
       
            } catch (EmailSendException $e) {
                report($e);
         
                return false;
            }



    }
}
