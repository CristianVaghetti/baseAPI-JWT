<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait MyDatabaseTransactions
{
    protected function beginTransaction()
    {
        if (  env('APP_ENV') == 'testing' ) {
            return;
        }

        if ( DB::transactionLevel() > 0 ) {
            return;
        }

        DB::beginTransaction();
    }

    protected function commit()
    {
        if (  env('APP_ENV') == 'testing' ) {
            return;
        }

        DB::commit();
    }

    protected function rollback()
    {
        if (  env('APP_ENV') == 'testing' ) {
            return;
        }

        DB::rollback();
    }
}