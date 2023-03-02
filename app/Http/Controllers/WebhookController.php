<?php

namespace App\Http\Controllers;

use App\Services\AffiliateService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: Complete this method


       /* `merchant_id`, `affiliate_id`, `subtotal`, `commission_owed`, `payout_status`, `discount_code`,*/


       if ($request->validated()) {
          
           $orderProcess =  (new OrderService())->processOrder($request);
       }


       
    }
}