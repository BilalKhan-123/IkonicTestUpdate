<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
        protected MerchantService $merchantService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method




        $merchant = new Merchant;
        $checkUser = User::where('email',$data['customer_email'])->first();

        if ($checkUser) {
        
            $checkAffiliate = Affiliate::where('user_id',$checkUser->id)->first();
            $checkMerchant = Merchant::where('user_id',$checkUser->id)->first();

            if (!$checkAffiliate) {
                    
                  $checkAffiliate =  (new AffiliateService())->register($merchant, $request);
            
                 
            }



                    $affiliate_id  = $checkAffiliate->id;
                    $discount_code  = $checkAffiliate->discount_code;
                    $commission_rate  = $checkAffiliate->commission_rate;

                    $merchant_id = $checkMerchant->id
                    $domain = $checkMerchant->domain;
            


    

            $order = new Order;
            $order->merchant_id = $merchant_id;
            $order->affiliate_id = ($merchant_id) ? $merchant_id :NULL;
            $order->subtotal = $data['subtotal_price'] ? $data['subtotal_price'] : 0;
            $order->commission_owed = $commission_rate;
            $order->payout_status = Order::STATUS_UNPAID;
            

            return ($order->save()) ? $order->save(); : response()->json([
                    'error' => "System couldn't processed the order!"
                    
                ]);


            




        }


        else {


              return response()->json([
                    'error' => "User couldn't found!"
                    
                ]);
        }


     





    }
}
