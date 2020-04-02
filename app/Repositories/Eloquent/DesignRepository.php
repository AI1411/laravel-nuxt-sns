<?php

namespace App\Repositories\Eloquent;

use App\Models\Design;
use App\Repositories\Contracts\IDesign;

class DesignRepository implements IDesign
{
    public function all()
    {
        return Design::all();
    }
}
