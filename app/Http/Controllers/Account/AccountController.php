<?php namespace Manivelle\Http\Controllers\Account;

use Auth;
use Manivelle\Http\Controllers\Controller;
use Manivelle\Http\Requests\AccountRequest;
use Illuminate\Http\Request;
use Localizer;

class AccountController extends Controller
{
    public function index()
    {
        $item = Auth::user();
        $item->load('organisations');
        
        return view('account.index', [
            'item' => $item
        ]);
    }
    
    public function update(AccountRequest $request)
    {
        $item = Auth::user();
        
        $input = $request->all();
        $item->fill($input);
        $item->save();
        $newLocale = $item->locale;
        
        $item->syncOrganisations(array_get($input, 'organisations', []));
        
        return redirect()->route(Localizer::routeName('account', $newLocale));
    }
    
    public function delete(Request $request)
    {
        $item = Auth::user();
        
        $item->delete();
        
        Auth::logout();
        
        return redirect()->route(Localizer::routeName('home'));
    }
}
