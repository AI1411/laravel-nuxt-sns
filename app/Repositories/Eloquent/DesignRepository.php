<?php

namespace App\Repositories\Eloquent;

use App\Models\Design;
use App\Repositories\Contracts\IDesign;

class DesignRepository extends BaseRepository implements IDesign
{
    public function model()
    {
        return Design::class;
    }
}
