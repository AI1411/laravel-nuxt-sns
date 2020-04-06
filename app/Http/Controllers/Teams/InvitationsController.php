<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Mail\SendInvitationToJoinTeam;
use App\Models\Invitation;
use App\Models\Team;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationsController extends Controller
{
    protected $invitations;
    protected $teams;
    protected $users;

    public function __construct(Invitation $invitations, ITeam $teams, IUser $users)
    {
        $this->invitations = $invitations;
        $this->teams = $teams;
        $this->users = $users;
    }

    public function invite(Request $request, $teamId)
    {
        $team = $this->teams->find($teamId);

        $this->validate($request, [
            'email' => ['required', 'email']
        ]);
        $user = auth()->user();
        //check if the user owns the team
        if (!$user->isOwnerOfTeam($team)) {
            return response()->json([
                'email' => 'チームのオーナーではありません'
            ], 401);
        }

        //check if the email has a pending invitation
        if ($team->hasPendingInvite($request->email)) {
            return response()->json([
                'email' => 'メールがすでに送信されています'
            ], 422);
        }

        //get the recipient by email
        $recipient = $this->users->findByEmail($request->email);

        //if the recipient not exist, send invitation to join the team
        if (!$recipient) {
            $this->createInvitation(false, $team, $request->email);
            $invitation = $this->invitations->create([
                'team_id' => $team->id,
                'sender_id' => $user->id,
                'recipient_email' => $request->email,
                'token' => md5(uniqid(microtime()))
            ]);
            Mail::to($request->email)
                ->send(new SendInvitationToJoinTeam($invitation, false));

            return response()->json([
                'message' => '招待メールを送信しました'
            ], 200);
        }

        //check if the team already has the user
        if ($team->hasUser($recipient)) {
            return response()->json([
                'email' => 'このユーザーはすでにチームのメンバーです'
            ], 422);
        }

        //send the invitation to the user
        $this->createInvitation(true, $team, $request->email);
        return response()->json([
           'email' => 'すでにチームのメンバーです'
        ], 422);

    }

    public function resend($id)
    {
        $invitation = $this->invitations->find($id);
        $this->authorize('resend', $invitation);

        if (!auth()->user()->isOwnerOfTeam($invitation->team)) {
            return response()->json([
                'email' => 'チームのオーナーではありません'
            ], 401);
        }

        $recipient = $this->users->findByEmail($invitation->recipient_email);

        Mail::to($invitation->recipient_email)
            ->send(new SendInvitationToJoinTeam($invitation, !is_null($recipient)));

        return response()->json(['message' => '招待を再送信しました']);
    }

    public function respond(Request $request, $id)
    {
        $this->validate($request, [
           'token' => ['required'],
           'decision' => ['required']
        ]);

        $token = $request->token;

        $decision = $request->descision;
        $invitation = $this->invitations->find($id);

        //check if the invitation belongs to this user
        $this->authorize('respond', $invitation);

        //check to make sure that the token matches
        if ($invitation->token !== $token) {
            return response()->json([
               'message' => 'トークンが無効です'
            ], 401);
        }

        //check the accepted
        if($decision !== 'deny'){
            $this->invitations->addUserToTeam($invitation->team, auth()->id());
        }
        $invitation->delete();

        return response()->json([
            'message' => 'Successful'
        ], 200);
    }

    public function destroy($id)
    {
        $invitation = $this->invitations->find($id);
        $this->authorize('delete', $invitation);
        $invitation->delete();

        return response()->json([
           'message' => 'Deleted'
        ], 200);
    }

    protected function createInvitation(bool $user_exists, Team $team, string $email)
    {
        $invitation = $this->invitations->create([
            'team_id' => $team->id,
            'sender_id' => auth()->id(),
            'recipient_email' => $email,
            'token' => md5(uniqid(microtime()))
        ]);
        Mail::to($email)
            ->send(new SendInvitationToJoinTeam($invitation, $user_exists));
    }

}
