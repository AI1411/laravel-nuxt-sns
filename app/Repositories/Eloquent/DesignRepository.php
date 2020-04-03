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

    public function applyTags($id, array $data)
    {
        $design = $this->find($id);

        $design->retag($data);
    }

    public function addComment($designId, array $data)
    {
        $design = $this->find($designId);
        $comment = $design->comments()->create($data);

        return $comment;
    }
}
