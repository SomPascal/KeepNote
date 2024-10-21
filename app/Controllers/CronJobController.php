<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CronJobController extends BaseController
{
    public function clear_cache()
    {
        cache()->clean();
    }
}
