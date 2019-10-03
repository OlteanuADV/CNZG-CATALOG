@extends('template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
           <div class="table-responsive">
               <table class="table">
                   <thead class="bg-dark text-white">
                        <th>#</th>
                        <th>Text</th>
                        <th>Data</th>
                   </thead>
                   <tbody>
                       @foreach($notifications as $notif)
                       <tr>
                           <td>
                               {{$notif->ID}}
                           </td>
                           <td @if($notif->Read == 0) class='text-danger' @endif>
                                {{$notif->Message}}
                           </td>
                           <td>
                                {{$notif->PostedOn}}
                           </td>
                       </tr>
                       @endforeach
                   </tbody>
               </table>
               <div class="m-2 pagination justify-content-center">
                    {{$notifications->links()}}
               </div>
           </div>
        </div>
    </div>
</div>
<br><br>
@endsection