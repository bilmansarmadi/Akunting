<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
class InvoiceUniquipController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        if(\Auth::user()->can('manage invoice uniquip'))
        {

            $client = new PendingRequest();
            $response = $client->get('https://uniquip.io/staging/api/invoice');
            $custom_fields = $response->json();
            
            return view('InvoiceUniquip.index', compact('custom_fields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create invoice uniquip'))
        {
            $types   = ['PO','DO','Service'];
            $modules = CustomField::$modules;

            return view('InvoiceUniquip.create', compact('types', 'modules'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create invoice uniquip'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'no_referensi' => 'required',
                                   'tipe_invoice' => 'required',
                                   'tanggal_invoice' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('InvoiceUniquip.index')->with('error', $messages->first());
            }

            $formatdate = date('d/m/Y', strtotime($request->tanggal_invoice));
            if($request->tipe_invoice == 0){
                $tipe = 'po';
            }elseif($request->tipe_invoice == 1){
                $tipe = 'do';
            }else{
                $tipe = 'service';
            }

            $response = Http::post('https://uniquip.io/staging/api/invoice/store', [
                'tanggal_invoice' =>  $formatdate,
                'no_referensi' => $request->no_referensi,
                'tipe_invoice' => $tipe
            ]);
            if ($response->status() == 400) {
                $error = $response->json();
                 return redirect()->route('InvoiceUniquip.index')->with('error', __($error['message']));
            }else{
                return redirect()->route('InvoiceUniquip.index')->with('success', __('Invoice successfully created!'));
            }

           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function show(CustomField $customField)
    {
        return redirect()->route('customFields.index');
    }

    public function edit(CustomField $customField)
    {
        if(\Auth::user()->can('edit constant custom field'))
        {
            if($customField->created_by == \Auth::user()->creatorId())
            {
                $types   = CustomField::$fieldTypes;
                $modules = CustomField::$modules;

                return view('customFields.edit', compact('customField', 'types', 'modules'));
            }
            else
            {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }


    public function update(Request $request, CustomField $customField)
    {
        if(\Auth::user()->can('edit constant custom field'))
        {

            if($customField->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('custom-field.index')->with('error', $messages->first());
                }

                $customField->name = $request->name;
                $customField->save();

                return redirect()->route('custom-field.index')->with('success', __('Custom Field successfully updated!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy(CustomField $customField)
    {
        if(\Auth::user()->can('delete constant custom field'))
        {
            if($customField->created_by == \Auth::user()->creatorId())
            {
                $customField->delete();

                return redirect()->route('custom-field.index')->with('success', __('Custom Field successfully deleted!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
