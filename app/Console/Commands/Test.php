<?php

namespace App\Console\Commands;

use App\Services\Chain\Drives\Erc20\Erc20;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {
            $chain = new Erc20();

            //私钥
            $keys='';
            //rpc地址
            $rpc='';
            //合约地址
            $contract_address='';
            //转出地址
            $from_address='';
            //转入地址
            $to_address='';
            //数量
            $amount=1;
            $result = $chain->rpc($rpc)->contract($contract_address)->from($from_address)->privateKey($keys)->to($to_address)->amount($amount)->transaction();

            //交易hash
            echo $result['txid'];

            $this->info('ok');
        }catch (\Exception $exception){
            $this->error($exception->getMessage());;
        }

    }
}
