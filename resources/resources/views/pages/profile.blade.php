@extends('template')
@section('content')
<input type="hidden" id="user_id" value="{{$user->ID}}">
<div class="row">
    @if($user->InSchoolFunction == 0)
    <div class="col-md-12">
        @php 
        $abstot = 0;
        foreach($absente as $abse)
        {
            if($abse->Motivated == 0)
                $abstot++;
        }
        @endphp
        @if($abstot > 9)
        <div class="alert alert-danger">
            Acest elev are deja 10 absente nemotivate!
        </div>
        @endif
    </div>
    @endif
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fa fa-user fa-10x"></i>
                <hr>
                {{$user->LastName}} {{$user->FirstName}}<br>
                <?php 
                    if($user->InSchoolFunction == 0) 
                    {
                        echo '<span class="badge badge-danger" data-toggle="tooltip" title="Diriginte - '.$diriginte->LastName.' '.$diriginte->FirstName.' ">Elev in clasa '.$class->Number.' '.$class->Character.'</span>'; 
                    }
                    elseif($user->InSchoolFunction == 1)
                    {
                        if($class !== 0)
                        {
                            echo '<span class="badge badge-info">Profesor de '.App\Subjects::find($user->Subject)->Name.' si diriginte la clasa '.$class->Number.' '.$class->Character.'</span>';
                        }
                        else
                        {
                            echo '<span class="badge badge-info">Profesor de '.App\Subjects::find($user->Subject)->Name.'</span>';
                        }
                    }
                ?>
            </div>
        </div>
        @if($user->InSchoolFunction == 0)
        <br><br>
        <div class="card">
            <div class="card-body text-center" id="profesori_materii">
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-8">
        @if($user->InSchoolFunction == 0)
        @php
            $esteinclasa = 0;
            foreach($profesori as $prof)    
            {
                if($prof->ID == Auth::user()->ID)
                    $esteinclasa = 1;
            }
        @endphp
        @if((Auth::user()->InSchoolFunction == 1 && $esteinclasa == 1) || Auth::user()->InSchoolFunction > 1)
        <div class="card">
            <div class="card-header">Panou Administrativ al elevului {{$user->LastName}} {{$user->FirstName}}</div>
            <div class="card-body">
                @if(Auth::user()->InSchoolFunction > 1 || (Auth::user()->InSchoolFunction > 0 && Auth::user()->Class == $user->Class))

                    <label for="master_subjects">Selecteaza materia la care doriti sa ii oferiti nota.</label>
                    <select name="master_subjects" id="master_subjects" class="form-control">
                        <option>None</option>
                    </select>
                    <label for="new_grade">Introduceti nota.</label>
                    <input type="number" class="form-control" name="new_grade" id="new_grade">
                    <label for="new_date">Introduceti data notei.</label>
                    <input type="date" class="form-control" name="new_date" id="new_date" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}">
                    <br>
                    <button  class="btn btn-info border-0 rounded-0" style="width:100%" @click="postNewGrade()">Adauga-i nota!</button>

                    <hr>
                    <label for="master_subjects">Selecteaza materia la care doriti sa ii oferiti absenta.</label>
                    <select name="master_subjects_abs" id="master_subjects_abs" class="form-control">
                        <option>None</option>
                    </select>
                    <label for="new_date_abs">Introduceti data notei.</label>
                    <input type="date" class="form-control" name="new_date_abs" id="new_date_abs" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}">
                    <br>
                    <button  class="btn btn-info border-0 rounded-0" style="width:100%" @click="postNewAbs()">Adauga-i absenta nemotivata!</button>
                @else
                    <input type="hidden" id="master_subjects" name="master_subjects" value="{{Auth::user()->Subject}}">
                    <label for="new_grade">Introduceti nota.</label>
                    <input type="number" class="form-control" name="new_grade" id="new_grade">
                    <label for="new_date">Introduceti data notei.</label>
                    <input type="date" class="form-control" name="new_date" id="new_date" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}">
                    <br>
                    <button  class="btn btn-info border-0 rounded-0" style="width:100%" @click="postNewGrade()">Adauga-i nota!</button>

                    <hr>
                    <input type="hidden" id="master_subjects_abs" name="master_subjects_abs" value="{{Auth::user()->Subject}}">
                    <label for="new_date_abs">Introduceti data notei.</label>
                    <input type="date" class="form-control" name="new_date_abs" id="new_date_abs" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}">
                    <br>
                    <button  class="btn btn-info border-0 rounded-0" style="width:100%" @click="postNewAbs()">Adauga-i absenta nemotivata!</button>
                @endif
                
            </div>
        </div>
        <br>
        @endif
        @endif
        <div class="card">
            <div class="card-body">
                @if($user->InSchoolFunction == 0)
                <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-note-tab" data-toggle="pill" href="#pills-note" role="tab" aria-controls="pills-note" aria-selected="true">Note</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-absente-tab" data-toggle="pill" href="#pills-absente" role="tab" aria-controls="pills-absente" aria-selected="false">Absente</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-note" role="tabpanel" aria-labelledby="pills-note-tab">
                        <hr>
                        <label for="materii_select" id="materii_select_label" class="text-center text-danger"></label>
                        <select id="materii_select" class="form-control" @change="updateNoteList()"></select>
                        <br><br>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                    <th scope="col">Nr.</th>
                                    <th scope="col">Nota</th>
                                    <th scope="col">Data</th>
                                    </tr>
                                </thead>
                                <tbody id="note">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-absente" role="tabpanel" aria-labelledby="pills-absente-tab">
                        <hr>
                        @foreach($absente as $abs)
                        Absenta @if($abs->Motivated == 0)<b class="text-danger">nemotivata</b>@else <b class="text-success">motivata</b> @endif in data de {{ date('Y-m-d', strtotime($abs->AbsenceDate))}}, la @foreach($materii as $mat) @if($mat->ID == $abs->Subject) {{$mat->Name}} @endif @endforeach.
                        @if(Auth::user()->InSchoolFunction > 1 || Auth::user()->Class == $user->Class)
                            @if($abs->Motivated == 0)
                            <a @click="motivateAbsence({{$abs->ID}})" data-toggle="tooltip" title="Motivati aceasta absenta."><i class="fa fa-check text-success"></i></a>
                            @else
                            <a @click="demotivateAbsence({{$abs->ID}})" data-toggle="tooltip" title="Demotivati aceasta absenta."><i class="fa fa-times text-danger"></i></a>
                            @endif
                        @endif
                        <br> 
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('jscripts')
<script>
let materii = {!! json_encode($materii) !!};
let profesori = {!! json_encode($profesori) !!};
@if($user->InSchoolFunction == 0)
let text = '';
let materii_select = '<option>None</option>';
for(let i = 0; i < materii.length; i++){
    text = text + `${materii[i].Name} - ${profesori[i].LastName} ${profesori[i].FirstName}<br>`;
    materii_select = materii_select + `<option value="${materii[i].ID}">${materii[i].Name}</option>`
}
if($('#master_subjects').length > 0)
    $('#master_subjects').html(materii_select);
if($('#master_subjects_abs').length > 0)
    $('#master_subjects_abs').html(materii_select);
$('#materii_select').html(materii_select);
$('#profesori_materii').html(text);
@endif
</script>
@endsection
