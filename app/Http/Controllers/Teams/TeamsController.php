<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Repositories\Contracts\ITeam;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamsController extends Controller
{
    protected $teams;

    public function __construct(ITeam $teams)
    {
        $this->teams = $teams;
    }
    public function index()
    {

    }

    public function store(Request $request)
    {
        $this->validate($request, [
           'name' => ['required', 'string', 'max:80', 'unique:teams,name'],
        ]);

        $team = $this->teams->create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    public function findById()
    {

    }

    public function findBySlug()
    {

    }

    public function fetchUserTeams()
    {
        //
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
