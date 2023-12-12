<?php

namespace App\Services\Chain\Drives\Erc20;

use App\Services\Chain\Drives\Erc20\Client\Utils;
use App\Services\Chain\Drives\Rpc;
use App\Services\Chain\Drives\Erc20\Client\Client as EthereumClient;


class Erc20 extends Rpc
{

    protected $client;

    public function __construct($url = '')
    {
        $config = [
            'base_uri' => $url,
            'timeout' => 20,
            'verify' => false,
        ];;

        $this->client = new EthereumClient($config);
    }

    public function rpc($url)
    {

        $this->__construct($url);

        $this->data = [];
        return $this;
    }

    public function amount($amount)
    {
        $this->data['value'] = Utils::ethToWei($amount, true);
        return $this;
    }

    public function privateKey($key)
    {
        if (!$key) throw new \Exception('private不存在');
        $this->client->addPrivateKeys([$key]);
        return $this;
    }

    public function transaction()
    {

        $this->data['data'] = '0x';
        if (isset($this->data['contract']) && $this->data['contract']) {
            $this->data['data'] = sprintf('%s%s%s%s%s', '0x', 'a9059cbb', '000000000000000000000000', substr($this->data['to'], 2), str_pad(substr($this->data['value'], 2), 64, 0, 0));
            $this->data['to'] = $this->data['contract'];
            $this->data['value'] = '0x0';
        }
        unset($this->data['contract']);


        $gas = $this->client->eth_estimateGas($this->data);

        //$gas = dechex(hexdec($gas) * 1.5);


        $this->data['gas'] = $gas;
        //$this->data['gas'] = '0x' . dechex(hexdec($this->client->eth_estimateGas($this->data)));
        $this->data['gasPrice'] = $this->client->eth_gasPrice();
        $this->data['nonce'] = $this->client->eth_getTransactionCount($this->data['from'], 'pending');
        $this->data['txid'] = $this->client->sendTransaction($this->data);
        $this->data['gasUsed'] = bcdiv(bcmul(hexdec($gas), hexdec($this->data['gasPrice'])), pow(10, 18), 8);
        return $this->data;

    }

    public function receipt($txid)
    {
        $result = $this->client->eth_getTransactionReceipt($txid);
        if (isset($result->status)) {
            return hexdec($result->status);
        }
        return '0';

    }

    public function __call($method, $args)
    {
        return $this->client->$method(...$args);
    }


}
