<?php

namespace App\Http\Controllers;

use App\Models\chartOfAccountSubType;
use App\Models\ChartOfAccountType;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountSubTypeController extends Controller
{

    public function index()
    {
        if(1)
        //\Auth::user()->can('manage constant chart of account type')
        {
            $types = chartOfAccountSubType::select(
                "chart_of_account_sub_types.id as id",
                "chart_of_account_types.name AS type", 
                "chart_of_account_sub_types.name as name"

            )
            ->join("chart_of_account_types", "chart_of_account_types.id", "=", "chart_of_account_sub_types.type")
            ->get();


            return view('chartOfAccountSubType.index', compact('types'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $types = ChartOfAccountType::get()->pluck('name', 'id');
        $types->prepend('--', 0);

        return view('chartOfAccountSubType.create', compact('types'));
    }


    public function store(Request $request)
    {
        if(1)
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

            $account             = new ChartOfAccountSubType();
            $account->name       = $request->name;
            $account->type       = $request->type;
            $account->save();

            return redirect()->route('chart-of-account-sub-type.index')->with('success', __('Chart of account type successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(ChartOfAccountSubType $chartOfAccountSubType)
    {
        //
    }


    public function edit(ChartOfAccountSubType $chartOfAccountSubType)
    {
        return view('chartOfAccountSubType.edit', compact('chartOfAccountSubType'));
    }


    public function update(Request $request, ChartOfAccountSubType $chartOfAccountSubType)
    {
        if(1)
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

            $chartOfAccountSubType->name = $request->name;
            $chartOfAccountSubType->save();

            return redirect()->route('chart-of-account-sub-type.index')->with('success', __('Chart of account type successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(chartOfAccountSubType $chartOfAccountSubType)
    {
        if(\Auth::user()->can('delete constant chart of account type'))
        {
            $chartOfAccountSubType->delete();

            return redirect()->route('chart-of-account-sub-type.index')->with('success', __('Chart of account type successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
