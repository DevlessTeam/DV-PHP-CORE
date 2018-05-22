<?php
/**
 * Created by Devless.
 * Author: paynegreen
 * Date Created: 17th of May 2018 10:14:39 AM
 * Service: Slydepay
 * Version: 1.3.6
 */
use App\Helpers\DataStore;
use App\Helpers\ActionClass;
//Action method for serviceName
class Slydepay
{
    public $serviceName = 'Slydepay';

    private $config = null;
    
    /** Slydepay URLs */
    private $invoiceUrl = 'https://app.slydepay.com.gh/api/merchant/invoice/create';
    private $confirmUrl = 'https://app.slydepay.com.gh/api/merchant/transaction/confirm';
    private $checkUrl = 'https://app.slydepay.com.gh/api/merchant/invoice/checkstatus';

    /**
     * Add products to orders table
     */
    private function buildInvoice($items, $orderId)
    {
        $temp_id = 1;
        $total_amount = 0;
        $orderItems = [];

        $object = new stdClass();

        foreach($items as $order) {
            abs( $total_price = $order['quantity'] * $order['unit_price']);
            abs( $total_amount += $total_price );

            array_push($orderItems, array(
                'itemCode' => isset($order['id']) ? $order['id'] : $temp_id,
                'itemName' => $order['name'],
                'quantity' => $order['quantity'],
                'unitPrice' => $order['unit_price'],
                'subTotal' => $order['quantity'] * $order['unit_price']
            ));
            $temp_id += 1;
        }

        $invoice = array(
            'emailOrMobileNumber' => $this->config->email_or_phone,
            'merchantKey' => $this->config->merchant_key,
            'description' => $this->config->description,
            'amount' => $total_amount,
            'orderCode' => $orderId,
            'orderItems' => $orderItems
        );

        return $invoice;
    }

    /**
     * Makes the payment initiation
     * @ACL protected
     */
    public function pay($entityId, $orderId, $items)
    {
        $this->getConfig($entityId);
        $payload = $this->buildInvoice($items, $orderId);

        $res = $this->request_processor($this->invoiceUrl, json_encode($payload));
        $res = json_decode($res, true);
        if ($res['success'] == true)
        {
            $response = DataStore::service('Slydepay', 'orders')->addData([[
                'status' => 'issued',
                'entity_id' => $entityId,
                'order_id' => (string)$orderId,
                'payload' => json_encode($payload),
                'token' => $res['result']['payToken'],
                'timestamp' => time()
            ]]);
            if ($response['status_code'] === 609) {
                $resArr = array(
                    'payToken' => $res['result']['payToken'],
                    'payUrl' => 'https://app.slydepay.com/paylive/detailsnew.aspx?pay_token='.$res['result']['payToken']
                );
            }
            return $resArr;
        }
        return $res;
    }

    /**
     * Check payment status
     * @ACL public
     */
    public function check($token)
    {
        $results = DataStore::service('Slydepay', 'orders')->where('token', $token)->queryData();
        if ($results['payload']['properties']['current_count'] > 0) {
            $this->getConfig($results['payload']['results'][0]->entity_id);
            $result = $this->request_processor($this->checkUrl, json_encode(array(
                'emailOrMobileNumber' => $this->config->email_or_phone,
                'merchantKey' => $this->config->merchant_key,
                "payToken" => $token,
                "confirmTransaction" => true
            )));

            $result = json_decode($result);
            if($result->success) {
                $resUpdate = DataStore::service('Slydepay', 'orders')
                    ->where('token', $token)->update([
                        'status' => $result->result
                    ]);
                if($resUpdate['status_code'] === 619) {
                    return $result;
                }
            }
            return $result;
        }
        return "No order found!";
    }


    /**
     * For payment confirmation
     * @ACL protected
     */
    public function confirm($orderId)
    {
        $results = DataStore::service('Slydepay', 'orders')->where('order_id', $orderId)->queryData();
        if ($results['payload']['properties']['current_count'] > 0) {
            $this->getConfig($results['payload']['results'][0]->entity_id);
            $result = $this->request_processor($this->confirmUrl, json_encode(array(
                'emailOrMobileNumber' => $this->config->email_or_phone,
                'merchantKey' => $this->config->merchant_key,
                'orderCode' => $orderId,
            )));

            $result = json_decode($result);
            if($result->success) {
                $resUpdate = DataStore::service('Slydepay', 'orders')
                    ->where($results['payload']['results'][0]['id'])->updateData([
                        'status' => $result->result
                    ]);
                if($resUpdate['status_code'] === 619) {
                    return $result->result;
                }
            }
            return $result;
        }
        return "No order found!";
    }

    /**
     * This method will execute on service importation
     * @ACL private
     */
    public function __onImport()
    {
        //add code here

    }


    /**
     * This method will execute on service exportation
     * @ACL private
     */
    public function __onDelete()
    {
        //add code here

    }

    /**
     * This method retrieves and sets the configs for the process
     * @ACL private
     */
    private function getConfig($identifier)
    {
        $results = DataStore::service('Slydepay', 'config')->where('entity_id', $identifier)->queryData();
        if ($results['payload']['properties']['current_count'] > 0) {
            return $this->config = $results['payload']['results'][0];
        }
        return 'No configuration available for such indentifier';
    }

    /**
     * This method handles the process requests
     */
    private function request_processor($url, $body) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
            ) ,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err)
        {
            return $err;
        }
        else
        {
            return $response;
        }
    }


}

