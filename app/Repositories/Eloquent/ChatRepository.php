<?php
namespace App\Repositories\Eloquent;

use App\Models\Chat;
use App\Repositories\Contracts\IChat;

class ChatRepository extends BaseRepository implements IChat
{

    public function model()
    {
        return Chat::class;
    }
}
