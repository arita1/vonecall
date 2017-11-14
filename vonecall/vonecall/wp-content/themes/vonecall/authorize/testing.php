<?php
require 'vendor/autoload.php'; 
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

define("AUTHORIZENET_LOG_FILE","phplog");
Class authorize {
 private $refId;

function __construct(){
     $refId = 'ref' . time();
     global $merchantAuthentication;
      $merchantAuthentication = $this->initialize();
     }

function initialize(){
   $merchant = new AnetAPI\MerchantAuthenticationType();
    $merchant->setName(\SampleCode\Constants::MERCHANT_LOGIN_ID);
    $merchant->setTransactionKey(\SampleCode\Constants::MERCHANT_TRANSACTION_KEY);

    return $merchant;
}



function authorizeCreditCard($amount)
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
   $merchantAuthentication = $this->initialize();
    
    // Set the transaction's refId
   

    // Create the payment data for a credit card
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber("4111111111111111");
    $creditCard->setExpirationDate("2038-12");
    $creditCard->setCardCode("123");

    // Add the payment data to a paymentType object
    $paymentOne = new AnetAPI\PaymentType();
    $paymentOne->setCreditCard($creditCard);

    // Create order information
    $order = new AnetAPI\OrderType();
    $order->setInvoiceNumber("10101");
    $order->setDescription("Golf Shirts");

    // Set the customer's Bill To address
    $customerAddress = new AnetAPI\CustomerAddressType();
    $customerAddress->setFirstName("Ellen");
    $customerAddress->setLastName("Johnson");
    $customerAddress->setCompany("Souveniropolis");
    $customerAddress->setAddress("14 Main Street");
    $customerAddress->setCity("Pecan Springs");
    $customerAddress->setState("TX");
    $customerAddress->setZip("44628");
    $customerAddress->setCountry("USA");

    // Set the customer's identifying information
    $customerData = new AnetAPI\CustomerDataType();
    $customerData->setType("individual");
    $customerData->setId("99999456654");
    $customerData->setEmail("EllenJohnson@example.com");

    // Add values for transaction settings
    $duplicateWindowSetting = new AnetAPI\SettingType();
    $duplicateWindowSetting->setSettingName("duplicateWindow");
    $duplicateWindowSetting->setSettingValue("60");

    // Add some merchant defined fields. These fields won't be stored with the transaction,
    // but will be echoed back in the response.
    $merchantDefinedField1 = new AnetAPI\UserFieldType();
    $merchantDefinedField1->setName("customerLoyaltyNum");
    $merchantDefinedField1->setValue("1128836273");

    $merchantDefinedField2 = new AnetAPI\UserFieldType();
    $merchantDefinedField2->setName("favoriteColor");
    $merchantDefinedField2->setValue("blue");

    // Create a TransactionRequestType object and add the previous objects to it
    $transactionRequestType = new AnetAPI\TransactionRequestType();
    $transactionRequestType->setTransactionType("authOnlyTransaction"); 
    $transactionRequestType->setAmount($amount);
    $transactionRequestType->setOrder($order);
    $transactionRequestType->setPayment($paymentOne);
    $transactionRequestType->setBillTo($customerAddress);
    $transactionRequestType->setCustomer($customerData);
    $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
    $transactionRequestType->addToUserFields($merchantDefinedField1);
    $transactionRequestType->addToUserFields($merchantDefinedField2);

    // Assemble the complete transaction request
    $request = new AnetAPI\CreateTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setTransactionRequest($transactionRequestType);

    // Create the controller and get the response
    $controller = new AnetController\CreateTransactionController($request);
    $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);


    if ($response != null) {
        // Check to see if the API request was successfully received and acted upon
        if ($response->getMessages()->getResultCode() == \SampleCode\Constants::RESPONSE_OK) {
            // Since the API request was successful, look for a transaction response
            // and parse it to display the results of authorizing the card
            $tresponse = $response->getTransactionResponse();
        
            if ($tresponse != null && $tresponse->getMessages() != null) {
                echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
                echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
                echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
                echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
                echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";
            } else {
                echo "Transaction Failed \n";
                if ($tresponse->getErrors() != null) {
                    echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                }
            }
            // Or, print errors if the API request wasn't successful
        } else {
            echo "Transaction Failed \n";
            $tresponse = $response->getTransactionResponse();
        
            if ($tresponse != null && $tresponse->getErrors() != null) {
                echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
            } else {
                echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
            }
        }      
    } else {
        echo  "No response returned \n";
    }

    return $response;
}

function createCustomerProfile($card_details)
{

  
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
   $merchantAuthentication = $this->initialize();
    // Create a Customer Profile Request
    //  1. (Optionally) create a Payment Profile
    //  2. (Optionally) create a Shipping Profile
    //  3. Create a Customer Profile (or specify an existing profile)
    //  4. Submit a CreateCustomerProfile Request
    //  5. Validate Profile ID returned

    // Set credit card information for payment profile
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber($card_details['card_number']);
    $creditCard->setExpirationDate($card_details['exp_date']);
   // $creditCard->setCardCode($card_details['cvv']);
    
    
    $paymentCreditCard = new AnetAPI\PaymentType();
    $paymentCreditCard->setCreditCard($creditCard);

    // Create a new CustomerPaymentProfile object
    $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
    $paymentProfile->setCustomerType('individual');
   // $paymentProfile->setBillTo($billTo);
    $paymentProfile->setPayment($paymentCreditCard);
    $paymentProfile->setDefaultpaymentProfile(true);
    $paymentProfiles[] = $paymentProfile;


    // Create a new CustomerProfileType and add the payment profile object
    $customerProfile = new AnetAPI\CustomerProfileType();
    $customerProfile->setDescription("Vonecall web customer");
    $customerProfile->setMerchantCustomerId("M_" . time());
    $customerProfile->setpaymentProfiles($paymentProfiles);


    // Assemble the complete transaction request
    $request = new AnetAPI\CreateCustomerProfileRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setProfile($customerProfile);
 
    // Create the controller and get the response
    $controller = new AnetController\CreateCustomerProfileController($request);
    $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
   
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
        $paymentProfiles = $response->getCustomerPaymentProfileIdList();
           $Return_response = array('profileId'=>$response->getCustomerProfileId() ,"payment_profile" => 
            $paymentProfiles[0]);
           return $Return_response ;
    } else {
        // echo "ERROR :  Invalid response\n";
        // $errorMessages = $response->getMessages()->getMessage();
        // echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
        return 0;
    }
 
}



function chargeCustomerProfile($profileid, $paymentprofileid, $amount)
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = $this->initialize();
 
    $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
    $profileToCharge->setCustomerProfileId($profileid);
    $paymentProfile = new AnetAPI\PaymentProfileType();
    $paymentProfile->setPaymentProfileId($paymentprofileid);
    $profileToCharge->setPaymentProfile($paymentProfile);

    $transactionRequestType = new AnetAPI\TransactionRequestType();
    $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
    $transactionRequestType->setAmount($amount);
    $transactionRequestType->setProfile($profileToCharge);

    $request = new AnetAPI\CreateTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
      // Set the transaction's refId
    $request->setRefId( $refId);

    $request->setTransactionRequest( $transactionRequestType);
    $controller = new AnetController\CreateTransactionController($request);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

    if ($response != null)
    {
      if($response->getMessages()->getResultCode() == \SampleCode\Constants::RESPONSE_OK)
      {
        $tresponse = $response->getTransactionResponse();
        
        if ($tresponse != null && $tresponse->getMessages() != null)   
        {
          // echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
          // echo  "Charge Customer Profile APPROVED  :" . "\n";
          // echo " Charge Customer Profile AUTH CODE : " . $tresponse->getAuthCode() . "\n";
          // echo " Charge Customer Profile TRANS ID  : " . $tresponse->getTransId() . "\n";
          // echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n"; 
          // echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";

          $transresponse = array("response_code"=> $tresponse->getResponseCode() ,"approved" => $tresponse->getMessages()[0]->getCode() ,"Auth_code"=>$tresponse->getAuthCode(),"trans_id"=>$tresponse->getTransId(),"description"=>$tresponse->getMessages()[0]->getDescription());
          return $transresponse;
        }
        else
        {
          echo "Transaction Failed \n";
          if($tresponse->getErrors() != null)
          {
            // echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
            // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n"; 

             $transresponse = array();
             return $transresponse;           
          }
        }
      }
      else
      {
       // echo "Transaction Failed \n";
        $tresponse = $response->getTransactionResponse();
        if($tresponse != null && $tresponse->getErrors() != null)
        {
          // echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
          // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
          $transresponse = array();
             return $transresponse;                         
        }
        else
        {
          $transresponse = array();
             return $transresponse;
          // echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
          // echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
        }
      }
    }
    else
    {
      $transresponse = array();
      return $transresponse;
      //echo  "No response returned \n";
    }

    return $response;
  }
  function createCustomerShippingAddress($existingcustomerprofileid = "36152127", 
    $phoneNumber="000-000-0000"
) {
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCode\Constants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCode\Constants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    $refId = 'ref' . time();
    
    // Use An existing customer profile id for this merchant name and transaction key

    // Create the customer shipping address
    $customershippingaddress = new AnetAPI\CustomerAddressType();
    $customershippingaddress->setFirstName("James");
    $customershippingaddress->setLastName("White");
    $customershippingaddress->setCompany("Addresses R Us");
    $customershippingaddress->setAddress(rand() . " North Spring Street");
    $customershippingaddress->setCity("Toms River");
    $customershippingaddress->setState("NJ");
    $customershippingaddress->setZip("08753");
    $customershippingaddress->setCountry("USA");
    $customershippingaddress->setPhoneNumber($phoneNumber);
    $customershippingaddress->setFaxNumber("999-999-9999");

    // Create a new customer shipping address for an existing customer profile

    $request = new AnetAPI\CreateCustomerShippingAddressRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setCustomerProfileId($existingcustomerprofileid);
    $request->setAddress($customershippingaddress);
    $controller = new AnetController\CreateCustomerShippingAddressController($request);
    $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
        echo "Create Customer Shipping Address SUCCESS: ADDRESS ID : " . $response-> getCustomerAddressId() . "\n";
    } else {
        echo "Create Customer Shipping Address  ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }
    return $response;
}
function getCustomerProfile()
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCode\Constants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCode\Constants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    $refId = 'ref' . time();

    // Create the payment data for a credit card
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber( "4111111111111111" );
    $creditCard->setExpirationDate("2038-12");
    $paymentCreditCard = new AnetAPI\PaymentType();
    $paymentCreditCard->setCreditCard($creditCard);

    // Create the Bill To info
    $billto = new AnetAPI\CustomerAddressType();
    $billto->setFirstName("Ellen");
    $billto->setLastName("Johnson");
    $billto->setCompany("Souveniropolis");
    $billto->setAddress("14 Main Street");
    $billto->setCity("Pecan Springs");
    $billto->setState("TX");
    $billto->setZip("44628");
    $billto->setCountry("USA");
  
    // Create a Customer Profile Request
    //  1. create a Payment Profile
    //  2. create a Customer Profile   
    //  3. Submit a CreateCustomerProfile Request
    //  4. Validate Profiiel ID returned

    $paymentprofile = new AnetAPI\CustomerPaymentProfileType();

    $paymentprofile->setCustomerType('individual');
    $paymentprofile->setBillTo($billto);
    $paymentprofile->setPayment($paymentCreditCard);
    $paymentprofiles[] = $paymentprofile;
    $customerprofile = new AnetAPI\CustomerProfileType();
    $customerprofile->setDescription("Customer 2 Test PHP");
    $merchantCustomerId = time().rand(1,150);
    $customerprofile->setMerchantCustomerId($merchantCustomerId);
    $customerprofile->setEmail("test2@domain.com");
    $customerprofile->setPaymentProfiles($paymentprofiles);

    $request = new AnetAPI\CreateCustomerProfileRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId( $refId);
    $request->setProfile($customerprofile);
    $controller = new AnetController\CreateCustomerProfileController($request);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {
      echo "SUCCESS: CreateCustomerProfile PROFILE ID : " . $response->getCustomerProfileId() . "\n";

      $profileIdRequested = $response->getCustomerProfileId();
     }
    else
    {
      echo "ERROR :  CreateCustomerProfile: Invalid response\n";
      $errorMessages = $response->getMessages()->getMessage();
      echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }
    // Retrieve an existing customer profile along with all the associated payment profiles and shipping addresses

    $request = new AnetAPI\GetCustomerProfileRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setCustomerProfileId($profileIdRequested);
    $controller = new AnetController\GetCustomerProfileController($request);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {
    echo "GetCustomerProfile SUCCESS : " .  "\n";
    $profileSelected = $response->getProfile();
    $paymentProfilesSelected = $profileSelected->getPaymentProfiles();
    echo "Profile Has " . count($paymentProfilesSelected). " Payment Profiles" . "\n";

    if($response->getSubscriptionIds() != null) 
    {
      if($response->getSubscriptionIds() != null)
      {

        echo "List of subscriptions:";
        foreach($response->getSubscriptionIds() as $subscriptionid)
          echo $subscriptionid . "\n";
      }
    }
    }
    else
    {
    echo "ERROR :  GetCustomerProfile: Invalid response\n";
    $errorMessages = $response->getMessages()->getMessage();
    echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }
    return $response;
  }
  function createCustomerProfileFromTransaction($transId= "2249066517")
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCode\Constants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCode\Constants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    $refId = 'ref' . time();

    $customerProfile = new AnetAPI\CustomerProfileBaseType();
    $customerProfile->setMerchantCustomerId("123212");
    $customerProfile->setEmail(rand(0, 10000) . "@test" .".com");
    $customerProfile->setDescription(rand(0, 10000) ."sample description");
      
    $request = new AnetAPI\CreateCustomerProfileFromTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setTransId($transId);

    // You can either specify the customer information in form of customerProfileBaseType object
    $request->setCustomer($customerProfile);
    //  OR   
    // You can just provide the customer Profile ID
        //$request->setCustomerProfileId("123343");

    $controller = new AnetController\CreateCustomerProfileFromTransactionController($request);

    $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
        echo "SUCCESS: PROFILE ID : " . $response->getCustomerProfileId() . "\n";
    } else {
        echo "ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }
    return $response;
}
function updateCustomerPaymentProfile($customerProfileId = "36731856",
    $customerPaymentProfileId = "33211899"
) {
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCode\Constants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCode\Constants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    $refId = 'ref' . time();

    //Set profile ids of profile to be updated
    $request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setCustomerProfileId($customerProfileId);
    $controller = new AnetController\GetCustomerProfileController($request);


    // We're updating the billing address but everything has to be passed in an update
    // For card information you can pass exactly what comes back from an GetCustomerPaymentProfile
    // if you don't need to update that info
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber( "4111111111111111" );
    $creditCard->setExpirationDate("2038-12");
    $paymentCreditCard = new AnetAPI\PaymentType();
    $paymentCreditCard->setCreditCard($creditCard);

    // Create the Bill To info for new payment type
    $billto = new AnetAPI\CustomerAddressType();
    $billto->setFirstName("Mrs Mary");
    $billto->setLastName("Doe");
    $billto->setAddress("1 New St.");
    $billto->setCity("Brand New City");
    $billto->setState("WA");
    $billto->setZip("98004");
    $billto->setPhoneNumber("000-000-0000");
    $billto->setfaxNumber("999-999-9999");
    $billto->setCountry("USA");
    

    // Create the Customer Payment Profile object
    $paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
    $paymentprofile->setCustomerPaymentProfileId($customerPaymentProfileId);
    $paymentprofile->setBillTo($billto);
    $paymentprofile->setPayment($paymentCreditCard);

    // Submit a UpdatePaymentProfileRequest
    $request->setPaymentProfile( $paymentprofile );

    $controller = new AnetController\UpdateCustomerPaymentProfileController($request);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {
     echo "Update Customer Payment Profile SUCCESS: " . "\n";
     
     // Update only returns success or fail, if success
     // confirm the update by doing a GetCustomerPaymentProfile
     $getRequest = new AnetAPI\GetCustomerPaymentProfileRequest();
     $getRequest->setMerchantAuthentication($merchantAuthentication);
     $getRequest->setRefId( $refId);
     $getRequest->setCustomerProfileId($customerProfileId);
     $getRequest->setCustomerPaymentProfileId($customerPaymentProfileId);

     $controller = new AnetController\GetCustomerPaymentProfileController($getRequest);
     $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
     if(($response != null)){
        if ($response->getMessages()->getResultCode() == "Ok")
        {
          echo "GetCustomerPaymentProfile SUCCESS: " . "\n";
          echo "Customer Payment Profile Id: " . $response->getPaymentProfile()->getCustomerPaymentProfileId() . "\n";
          echo "Customer Payment Profile Billing Address: " . $response->getPaymentProfile()->getbillTo()->getAddress(). "\n";
        }
        else
        {
          echo "GetCustomerPaymentProfile ERROR :  Invalid response\n";
          $errorMessages = $response->getMessages()->getMessage();
              echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
        }
      }
      else{
        echo "NULL Response Error";
      }

     }
    else
    {
      echo "Update Customer Payment Profile: ERROR Invalid response\n";
      $errorMessages = $response->getMessages()->getMessage();
      echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }
    return $response;
  }
}// class close
?>
