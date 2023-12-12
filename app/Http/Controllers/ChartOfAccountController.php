<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountSubType;
use App\Models\ChartOfAccountType;
use App\Models\User;
use DB;
use App\Models\Utility;
use Illuminate\Http\Request;
use App\Imports\CoaImport;
use Maatwebsite\Excel\Facades\Excel;

class ChartOfAccountController extends Controller
{

    public function index()
    {


        if(\Auth::user()->can('manage chart of account'))
        {
            $types = ChartOfAccountType::orderBy('code', 'asc')->get();

            $chartAccounts = [];
            foreach($types as $type)
            {
                $accounts = ChartOfAccount::where('type', $type->id)->where('created_by', '=', \Auth::user()->creatorId())->orderBy('code', 'asc')->get();

                $chartAccounts[$type->code.' '.$type->name] = $accounts;

            }

            return view('chartOfAccount.index', compact('chartAccounts', 'types'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $types = ChartOfAccountType::select(
            DB::raw("CONCAT(code,' ',name) AS name"),'id')
            ->pluck('name', 'id');
        $types->prepend('--', 0);

        return view('chartOfAccount.create', compact('types'));
    }


    public function store(Request $request)
    {
        $Chek = null;
        $Validasi = null;
        $accounts = ChartOfAccount::select(
            "chart_of_accounts.code as code",
            "chart_of_accounts.id as id"

        )
        ->join("chart_of_account_types", "chart_of_account_types.id", "=", "chart_of_accounts.type")
        ->where('chart_of_accounts.code', $request->code)
        ->where('chart_of_account_types.id', $request->type)
        ->get();
        
        foreach($accounts as $type)
        {
            $Chek = $type->code;
        
        }

        if($Chek != $request->code)
        {
            if(\Auth::user()->can('create chart of account'))
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                       'type' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();
    
                    return redirect()->back()->with('error', $messages->first());
                }
    
                $account              = new ChartOfAccount();
                $account->name        = $request->name;
                $account->code        = $request->code;
                $account->type        = $request->type;
                $account->description = $request->description;
                $account->is_enabled  = isset($request->is_enabled) ? 1 : 0;
                $account->created_by  = \Auth::user()->creatorId();
                $account->save();
                return redirect()->route('chart-of-account.index')->with('success', __('Account successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }else{
            return redirect()->back()->with('error', __('code  '.$request->code.' already exists.'));
        }
    }


    public function show(ChartOfAccount $chartOfAccount)
    {
        //
    }


    public function edit(ChartOfAccount $chartOfAccount)
    {
        $types = ChartOfAccountType::get()->pluck('name', 'id');
        $types->prepend('--', 0);

        return view('chartOfAccount.edit', compact('chartOfAccount', 'types'));
    }


    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {

        $accounts = ChartOfAccount::where('code', $request->code)->get();
        $Chek = null;
        $id = null;
        foreach($accounts as $type)
        {
            $Chek = $type->code;
            $id = $type->id;
        }
        if($Chek != $request->code || $id == $request->id){
            if(\Auth::user()->can('edit chart of account'))
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
    
    
                $chartOfAccount->name        = $request->name;
                $chartOfAccount->code        = $request->code;
                $chartOfAccount->description = $request->description;
                $chartOfAccount->is_enabled  = isset($request->is_enabled) ? 1 : 0;
                $chartOfAccount->save();
    
                return redirect()->route('chart-of-account.index')->with('success', __('Account successfully updated.'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }else{
            return redirect()->back()->with('error', __('code  '.$request->code.' already exists.')); 
        }


    }


    public function destroy(ChartOfAccount $chartOfAccount)
    {
        if(\Auth::user()->can('delete chart of account'))
        {
            $chartOfAccount->delete();

            return redirect()->route('chart-of-account.index')->with('success', __('Account successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getSubType(Request $request)
    {
        $types = ChartOfAccountSubType::where('type', $request->type)->get()->pluck('name', 'id')->toArray();

        return response()->json($types);
    }

    public function importFile()
    {
        return view('chartOfAccount.import');
    }


    public function import(Request $request)
    {
        $Chek = NULL;
        $rules = [
            'file' => 'required|mimes:csv,txt,xls,xlsx',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $coa = (new CoaImport())->toArray(request()->file('file'))[0];

        $totalCustomer = count($coa) - 1;
        $errorArray    = [];
        for ($i = 1; $i <= count($coa) - 1; $i++) {
            $ChartOfAccount = $coa[$i];
            $CoaCode = ChartOfAccountType::where('code', $coa[1])->get();

            foreach($CoaCode as $type)
            {
                $Chek = $type->id;
            }

            var_dump($Chek);
            $ChartOfAccountData      =      new ChartOfAccount();
            $ChartOfAccountData->type              = $Chek;
            $ChartOfAccountData->code              = $ChartOfAccount[2];
            $ChartOfAccountData->name              = $ChartOfAccount[3];
            $ChartOfAccountData->created_by        = \Auth::user()->creatorId();

            if (empty($ChartOfAccountData)) {
                $errorArray[] = $ChartOfAccountData;
            } else {
                $ChartOfAccountData->save();
            }
        }

        $errorRecord = [];
        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }
}
