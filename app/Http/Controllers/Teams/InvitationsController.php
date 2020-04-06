<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    protected $invitations;

    public function __construct(Invitation $invitations)
    {
        $this->invitations = $invitations;
    }
}
