<?php

namespace App\Services;

use App\Jobs\PayoutOrderJob;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class MerchantService
{
    /**
     * Register a new user and associated merchant.
     * Hint: Use the password field to store the API key.
     * Hint: Be sure to set the correct user type according to the constants in the User model.
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return Merchant
     */
    public function register(array $data): Merchant
    {
        // TODO: Complete this method



       



        $user = new User;
        //$this->is_company ? true : false,
        $user->name = $data['name'] ? $data['name'] : 'Bilal';
        $user->email = $data['email'];
        $user->password = $data['api_key'] ? $data['api_key'] : env('APP_KEY');
        $user->type = User::TYPE_MERCHANT;
        $userSaved = $user->save();
        

        if ($userSaved) {

            $user_id = $userSaved->id;

            $merchant = new Merchant;
            $merchant->user_id = $user_id; 
            $merchant->domain = $data['domain'] ? $data['domain'] : 'sample domain'; 
            $merchant->display_name = $data['name'] ? $data['name'] : 'sample merchant'; 
            $merchant->save();


            return response()->json([
                    'merchant' => $merchant
                    
                ]);
           
        }
        else {

                return response()->json([
                    'error' => "Failed!  User could not registered!"
                    
                ]);
        }



    }

    /**
     * Update the user
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return void
     */
    public function updateMerchant(User $user, array $data)
    {
        // TODO: Complete this method

        //`name`, `email`, `email_verified_at`, `password`, `type`,


        //`user_id`, `domain`, `display_name`, `turn_customers_into_affiliates`, `default_commission_rate`,


        $user = $this->user;

        $updateUser = User::updateOrCreate([
            "email" => $data['email']
        ],
        [
            "name" => $data['name'],
            "password" => $data['api_key'],
          
        ]);

        $merchantUpdate =  Merchant::updateOrCreate([
            'user_id' => $updateUser->id

        ],[

            "domain" => $data['domain']

        ]);



    }

    /**
     * Find a merchant by their email.
     * Hint: You'll need to look up the user first.
     *
     * @param string $email
     * @return Merchant|null
     */
    public function findMerchantByEmail(string $email): ?Merchant
    {
        // TODO: Complete this method


        $getUser = User::where('email',$email)->first();
        if ($getUser) {
            
            $getMerchatRecord = Merchant::where('user_id',$getUser->id)->first();

               return response()->json([
                    'user_as_merchant' => $getUser,
                    'merchant' => $getMerchatRecord,
                    
                ]);

        }
        else {
                return response()->json([
                    'error' => "Failed!  User could not found!"
                    
                ]);
        }


    }

    /**
     * Pay out all of an affiliate's orders.
     * Hint: You'll need to dispatch the job for each unpaid order.
     *
     * @param Affiliate $affiliate
     * @return void
     */
    public function payout(Affiliate $affiliate)
    {
        // TODO: Complete this method


        $payoutOrders = Affiliate::with('orders')->get();

        foreach ($payoutOrders as $order) {
            
            if ($order->orders->payout_status == Order::STATUS_UNPAID) {
                    dispatch(new PayoutOrderJob())->delay(5); // Slot 5
            }
            else {

                $getPaid = Order::where('affiliate_id',$payoutOrders->id)->update(
                    [ 
                    'payout_status' => Order::STATUS_PAID

                    ]);
            }



        }


    }
}
