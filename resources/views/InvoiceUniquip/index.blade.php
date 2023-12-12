@extends('layouts.admin')
@section('page-title')
    {{__('Manage Invoice Uniquip')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Invoice Uniquip')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create constant custom field')
                <a href="#" data-url="{{ route('InvoiceUniquip.create') }}" data-bs-toggle="tooltip" title="{{__('Create')}}" data-ajax-popup="true" data-title="{{__('Create Invoice Uniquip')}}" class="btn btn-sm btn-primary">
                    <i class="ti ti-plus"></i>
                </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Nomor Invoice')}}</th>
                                <th> {{__('Nomor Referensi')}}</th>
                                <th> {{__('Tipe Invoice')}}</th>
                                <th> {{__('Tanggal Invoice')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($custom_fields['data'] as $field)
                                
                            <tr>
                                    <td class="Id">
                                        <a href="" class="btn btn-outline-primary">{{ $field['nomor_invoice'] }}</a>
                                    </td>
                                    <td>{{ $field['no_referensi'] }}</td>
                                    <td>{{ $field['tipe_invoice'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($field['tanggal_invoice'])->format('d/m/Y')  }}</td>
                                   
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
