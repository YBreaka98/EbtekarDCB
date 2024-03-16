<?php

namespace Ybreaka98\EbtekarDCB\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ybreaka98\EbtekarDCB\EbtekarDCB
 */
class EbtekarDCB extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ybreaka98\EbtekarDCB\EbtekarDCB::class;
    }
}
