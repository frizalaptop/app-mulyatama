<?php

namespace App\Services;

use App\Traits\ServiceLogger;
use Throwable;


class UserService
{

    use ServiceLogger;
    
    public function getUserListViewData()
    {
        try {
            return ['title' => 'User List'];
        } catch (Throwable $e) {
            $this->logException($e, __METHOD__);
            throw $e;
        }
    }


}