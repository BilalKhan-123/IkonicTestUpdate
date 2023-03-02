<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    ) {}

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {


        // TODO: Complete this method


        


         $getUser = User::where('email',$email)->first();

         if ($getUser) {
        
    

          $getMerchant = Merchant::where('user_id',$getUser->id)->first();

        if ($getMerchant) {
        
            $affiliate = new Affiliate;
            $affiliate->user_id = $getMerchant->user_id;
            $affiliate->merchant_id = $getMerchant->id;
            $affiliate->commission_rate = $commissionRate;
            $affiliate->discount_code = Affiliate::DISCOUNT_CODE;
            $affiliate = $affiliate->save();

            if ($affiliate) {
                   return $affiliate,
            }



        }

        else {

                return response()->json([
                    'error' => "Failed!  Merchant could not found!"
                    
                ]);
        }

        }
        else {

             return response()->json([
                    'error' => "Failed!  User could not found!"
                    
                ]);

        }


    }
}
