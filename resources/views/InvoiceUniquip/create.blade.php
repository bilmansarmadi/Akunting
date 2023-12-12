{{ Form::open(array('url' => 'InvoiceUniquip')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('no_referensi',__('Nomor Referensi'),['class'=>'form-label'])}}
            {{Form::text('no_referensi',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('tipe_invoice', __('Tipe Invoice'),['class'=>'form-label']) }}
            {{ Form::select('tipe_invoice',$types,null, array('class' => 'form-control select2 ','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('tanggal_invoice', __('Tanggal Invoice'),['class'=>'form-label']) }}
            {{Form::date('tanggal_invoice',null,array('class'=>'form-control','required'=>'required'))}}
        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
