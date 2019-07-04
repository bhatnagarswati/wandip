@extends('layouts.servicer.app')@section('content')
<section class="content admin-forms">
   <div class="box">
      <div class="box-body">
         <div class="card">
            <h2 class="card-header">{{ __('admin/pumps.view_pump') }} #{{ $pump->pumpId }}</h2>
            <div class="card-body">
               <a href="{{ url('/servicer/pumps') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin/common.action_back') }}</button></a>		    <a href="{{ url('/servicer/pumps/' . $pump->pumpId . '/edit') }}" title="Edit Pump"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('admin/common.action_edit') }}</button></a>		    
               <form method="POST" action="{{ url('servicer/pumps' . '/' . $pump->pumpId) }}" accept-charset="UTF-8" style="display:inline">			{{ method_field('DELETE') }}			{{ csrf_field() }}			<button type="submit" class="btn btn-danger btn-sm" title="Delete Pump" onclick="return confirm( & quot; Confirm delete? & quot; )"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('admin/common.action_delete') }}</button>		    </form>
               <br/>		    <br/>		    
               <div class="table-responsive">
                  <table class="table">
                     <tbody>
                        <tr>
                           <th>ID</th>
                           <td>{{ $pump->id }}</td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/pumps.pumpTitle') }} </th>
                           <td> {{ $pump->pumpTitle }} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/pumps.pumpDescription') }} </th>
                           <td> {{ $pump->pumpDescription }} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/pumps.pumpPrice') }} </th>
                           <td> {{ '$'.$pump->pumpPrice }} </td>
                        </tr>
                        <tr>
                           <th> {{ __('admin/pumps.pumpAddress') }} </th>
                           <td> {{ $pump->pumpAddress }} </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection