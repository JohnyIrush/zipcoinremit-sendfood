<?php

namespace Cchivhima\Sendfood\Http\Controllers;

use Illuminate\Http\Request;

use Session;

use  Cchivhima\Sendfood\Http\Drivers\Moneris\mpgClasses;

use  Cchivhima\Sendfood\Http\Drivers\Moneris\mpgTransaction;

use  Cchivhima\Sendfood\Http\Drivers\Moneris\mpgRequest;

use  Cchivhima\Sendfood\Http\Drivers\Moneris\mpgHttpsPost;

use  Cchivhima\Sendfood\Http\Drivers\Moneris\mpgGlobals;

class InteracOnlinePaymentController extends Controller
{
    /** Interac online **/
    public function __construct()
    {
        $this->middleware('auth');   
    }
    /**
     * Interact Online approved response
     */

    public function interacOnlineApproved(Request $request)
    {

    
    }

    /* Interact Online Declined response
    */

   public function interacOnlineDeclined(Request $request)
   {

    
   }

    /* Interact Online Canceled response
    */

    public function interacOnlineCanceled(Request $request)
    {
 
     
    }

    /* Interact Online Verification response
    */

    public function interacOnlineVerification(Request $request)
    {
 
     
    }
 


    /**
     * Render Pay with Stripe Front-end
     * 
     */
    public function payWithInteracOnline()
    {
        if (!Session::get('cart')->totalQty==0 && !Session::get('receivingmethod')==null && !Session::get('beneficiaryid')==null && !Session::get('selectpaymentmethod')==null && !Session::get('beneficiaryid')==null && !Session::get('selectpaymentmethod')==null && Session::get('selectpaymentmethod')['id']==3 )
         {
           return view('sendfood::Plugins.payment.paywithinteraconline');
         }else{

            return redirect()->back()->with(['error'=>'Please Select Payment Method']);
            Session::save();

        }

    }

    /**
     * Execute Stripe Payment
     * 
     */
    public function makeInteracOnlinePayment(Request $request)
    {


        $store_id='store5';
        $api_token= 'yesguy';
        $orderid= 'ord-'.date("dmy-G:i:s");
        
        ## step 1) create transaction hash ###
        $txnArray=array('type'=>'idebit_purchase',
                 'order_id'=>$orderid,
                 'cust_id'=>'my cust id',
                 'amount'=>'50.00',
                 'idebit_track2'=>'3728024906540591206=0609AAAAAAAAAAAAA'
                );
        
        
        ## step 2) create a transaction  object passing the hash created in
        ## step 1.
        
        $mpgTxn = new mpgTransaction($txnArray);
        
        ## step 3) create a mpgRequest object passing the transaction object created
        ## in step 2
        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode(true); //false or comment out this line for production transactions
        
        ## step 4) create mpgHttpsPost object which does an https post ##
        $mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);
        
        ## step 5) get an mpgResponse object ##
        $mpgResponse=$mpgHttpPost->getMpgResponse();
        
        ## step 6) retrieve data using get methods
        
        print("\nCardType = " . $mpgResponse->getCardType());
        print("\nTransAmount = " . $mpgResponse->getTransAmount());
        print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
        print("\nReceiptId = " . $mpgResponse->getReceiptId());
        print("\nTransType = " . $mpgResponse->getTransType());
        print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
        print("\nResponseCode = " . $mpgResponse->getResponseCode());
        print("\nISO = " . $mpgResponse->getISO());
        print("\nMessage = " . $mpgResponse->getMessage());
        print("\nAuthCode = " . $mpgResponse->getAuthCode());
        print("\nComplete = " . $mpgResponse->getComplete());
        print("\nTransDate = " . $mpgResponse->getTransDate());
        print("\nTransTime = " . $mpgResponse->getTransTime());
        print("\nTicket = " . $mpgResponse->getTicket());
        print("\nTimedOut = " . $mpgResponse->getTimedOut());
                        
    }

}
