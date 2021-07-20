<?php namespace Manivelle\Http\Controllers\Organisation;

use Auth;
use Illuminate\Http\Request;
use Manivelle\Http\Controllers\Controller;

use Manivelle\Http\Requests\InvitationRegisterRequest;

use Manivelle\User;
use Manivelle\Models\Organisation;
use Manivelle\Models\OrganisationInvitation;
use Localizer;

class InvitationController extends Controller
{
    public function index(Request $request, Organisation $organisation, OrganisationInvitation $invitation)
    {
        if (!$invitation->id) {
            return abort(404);
        }
        
        Auth::logout();
        
        if ($organisation->id !== $invitation->organisation->id) {
            return redirect()->route(
                Localizer::routeName('organisation.invitation'),
                [$invitation->organisation->slug, $invitation->invitation_key]
            );
        }
        
        $user = $invitation->user ?
            $invitation->user :
            User::where('email', 'LIKE', strtolower($invitation->email))->first();
        
        if ($user) {
            $currentOrganisation = $user->organisations->first(function ($key, $item) use ($organisation) {
                return (int)$item->organisation_id === (int)$organisation->id;
            });
            if ($currentOrganisation) {
                return redirect()->route(Localizer::routeName('organisation.home'), [$organisation->slug]);
            }
            
            $this->middleware('panneau.auth');
            
            return view('organisation.invitation.link', [
                'user' => $user,
                'invitation' => $invitation,
                'organisation' => $organisation
            ]);
        } else {
            return view('organisation.invitation.register', [
                'invitation' => $invitation,
                'organisation' => $organisation
            ]);
        }
    }
    
    public function store(
        InvitationRegisterRequest $request,
        Organisation $organisation,
        OrganisationInvitation $invitation
    ) {
        if ($organisation->id !== $invitation->organisation->id) {
            return redirect()->route(
                Localizer::routeName('organisation.invitation'),
                [$invitation->organisation->slug, $invitation->invitation_key]
            );
        }
        
        $input = $request->all();
        
        $user = new User();
        $user->organisation_id = $organisation->id;
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->locale = $input['locale'];
        $user->save();
        
        $role = $invitation->role;
        $organisation->attachUser($user, $role);
        
        $invitation->delete();

        $effectiveLocale = $user->locale;
        
        return redirect()->route(Localizer::routeName('organisation.home', $effectiveLocale), [$organisation->slug]);
    }
    
    public function link(Request $request, Organisation $organisation, OrganisationInvitation $invitation)
    {
        if ($organisation->id !== $invitation->organisation->id) {
            return redirect()->route(
                Localizer::routeName('organisation.invitation'),
                [$invitation->organisation->slug, $invitation->invitation_key]
            );
        }
        
        $user = $invitation->user ?
            $invitation->user :
            User::where('email', 'LIKE', strtolower($invitation->email))->first();
        $role = $invitation->role;
        $organisation->attachUser($user, $role);
        
        $invitation->delete();
        
        return redirect()->route(Localizer::routeName('organisation.home'), [$organisation->slug]);
    }
}
