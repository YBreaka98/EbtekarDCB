<?php

namespace Ybreaka98\EbtekarDCB\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ybreaka98\EbtekarDCB\EbtekarDCBPages
 */
class EbtekarDCBPages extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ybreaka98\EbtekarDCB\EbtekarDCBPages::class;
    }
}
