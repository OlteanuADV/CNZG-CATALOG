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
        @if(Auth::check() && Auth::user()->InSchoolFunction == 1 && Auth::user()->Class == $class->ID)
        <br>
        <div class="card">
            <div class="card-header text-center">
                Administreaza clasa.
            </div>
            <div class="card-body">
                <label for="sefulclasei">Alegeti seful clasei.</label>
                <select name="sefulclasei" id="sefulclasei" class="form-control" data-toggle="tooltip" data-title="Actualul sef al clasei - {{$class->chief()->LastName}} {{$class->chief()->FirstName}}" title="Actualul sef al clasei - {{$class->chief()->LastName}} {{$class->chief()->FirstName}}">
                    <option value="{{$class->Chief}}" class="bg-danger text-white">{{$class->chief()->LastName}} {{$class->chief()->FirstName}}</option>
                    @foreach($students as $s)
                    @if($s->ID !== $class->Chief)
                        <option value="{{$s->ID}}">{{$s->LastName}} {{$s->FirstName}}</option>
                    @endif
                    @endforeach
                </select>
                <br>
                <button class="btn btn-info rounded-0" style="width:100%;" @click="choseMyChief()">Alegeti!</button>
                <hr>
                <label for="mesajclasa">Trimiteti mesaj pentru toata clasa.</label>
                <input type="text" name="mesajclasa" id="mesajclasa" class="form-control" placeholder="Scrieti un mesaj ce va fi trimis tuturor elevilor ai clasei a {{$class->Number}}-a {{$class->Character}}!">
                <br>
                <button class="btn btn-info rounded-0" style="width:100%;" @click="buzzMyClass()">Trimiteti!</button>
            </div>
        </div>
        @endif
    </div>
</div>
<br><br>
@endsection