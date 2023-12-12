<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccountType;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;

class ChartOfAccountTypeController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage constant coa'))
        //\Auth::user()->can('manage constant chart of account type')
        {
            $types = ChartOfAccountType::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('chartOfAccountType.index', compact('types'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('chartOfAccountType.create');
    }


    public function store(Request $request)
    {
        $Chek = null;
        $Validasi = null;
        $accounts = ChartOfAccountType::where('code', $request->code)->get();

        foreach($accounts as $type)
        {
            $Chek = $type->code;
        }
        if($Chek != $request->code){
            if(\Auth::user()->can('create constant coa'))
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'code' => 'required',
                                       'name' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();
    
                    return redirect()->back()->with('error', $messages->first());
                }
    
                $account             = new ChartOfAccountType();
                $account->code       = $request->code;
                $account->name       = $request->name;
                $account->created_by = \Auth::user()->creatorId();
                $account->save();
    
                return redirect()->route('chart-of-account-type.index')->with('success', __('Chart of account type successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }else{
            return redirect()->back()->with('error', __('code  '.$request->code.' already exists.')); 
        }        
      
    }


    public function show(ChartOfAccountType $chartOfAccountType)
    {
        //
    }


    public function edit(ChartOfAccountType $chartOfAccountType)
    {
        return view('chartOfAccountType.edit', compact('chartOfAccountType'));
    }


    public function update(Request $request, ChartOfAccountType $chartOfAccountType)
    {
            $accounts = ChartOfAccountType::where('code', $request->code)->get();
            $Chek = null;
            $id = null;
            foreach($accounts as $type)
            {
                $Chek = $type->code;
                $id = $type->id;
            }

        if($Chek != $request->code || $id == $request->id){
            if(\Auth::user()->can('edit constant coa'))
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();
    
                    return redirect()->back()->with('error', $messages->first());
                }
    
                $chartOfAccountType->code = $request->code;
                $chartOfAccountType->name = $request->name;
                $chartOfAccountType->save();
    
                return redirect()->route('chart-of-account-type.index')->with('success', __('Chart of account type successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }   
            }else{
                return redirect()->back()->with('error', __('code  '.$request->code.' already exists.'));    
            }
    }


    public function destroy(ChartOfAccountType $chartOfAccountType)
    {
        $messages = "Permission denied.";

        $accounts = ChartOfAccount::where('type', $chartOfAccountType->code)->count();

        if($accounts > 0){
            $messages = "cannot be deleted because there is still $accounts data";
        } 
        
        if(\Auth::user()->can('delete constant coa')&& $accounts == 0)
        {
            $chartOfAccountType->delete();

            return redirect()->route('chart-of-account-type.index')->with('success', __('Chart of account type successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __($messages));
        }
    }
}
