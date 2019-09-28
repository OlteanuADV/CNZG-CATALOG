@extends('template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header text-center">
                Clasa a {{$class->Number}}-a {{$class->Character}} (Diriginte {{$diriginte->LastName}} {{$diriginte->FirstName}})
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Nr.</th>
                                <th scope="col">Nume</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach($students as $s)
                            @php $i++; @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$s->LastName}} {{$s->FirstName}}</td>
                                <td>
                                    <a href="{{URL::to('/profile/'.$s->ID)}}">Acceseaza profilul</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
@endsection