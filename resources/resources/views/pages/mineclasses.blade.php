@extends('template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
           <div class="table-responsive">
               <table class="table">
                   <thead class="bg-dark text-white">
                        <th>Nr.</th>
                        <th>Nume clasa</th>
                        <th>Diriginte</th>
                        <th>Nr. Elevi</th>
                   </thead>
                   <tbody>
                       @php $i = 0; @endphp
                       @foreach($classes as $class)
                       @php $i++; @endphp
                       <tr>
                           <td>
                               {{$i}}
                           </td>
                           <td>
                                <a href="{{URL::to('/classes/'.$class->ID)}}">{{$class->Number}} {{$class->Character}}</a>
                           </td>
                           <td>
                                <a href="{{URL::to('/profile/'.$class->diriginte->ID)}}">{{$class->diriginte->LastName}} {{$class->diriginte->FirstName}}</a>
                           </td>
                           <td>
                               {{count($class->users)}}
                           </td>
                       </tr>
                       @endforeach
                   </tbody>
               </table>
           </div>
        </div>
    </div>
</div>
<br><br>
@endsection