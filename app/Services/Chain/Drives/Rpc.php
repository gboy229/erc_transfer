<?php

namespace App\Services\Chain\Drives;

abstract class Rpc
{

    protected $config = [];
    protected $data = [];
    protected $decimal = 18;

    public function getConfig($config = '')
    {
        return $config ? $this->config[$config] : $this->config;
    }


    public function setConfig($config, $value = '')
    {
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        } else {
            $this->config[$config] = $value;
        }

    }

    abstract public function rpc($url);

    abstract public function amount($amount);

    abstract public function privateKey($key);

    abstract public function transaction();


    public function contract($contract)
    {
        $this->data['contract'] = $contract;
        return $this;
    }

    public function from($address)
    {
        $this->data['from'] = $address;
        return $this;
    }

    public function to($address)
    {
        $this->data['to'] = $address;
        return $this;
    }

}
