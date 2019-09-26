@extends('template')
@section('content')
<input type="hidden" id="user_id" value="{{$user->ID}}">
<div class="row">
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
                        <select id="materii_select" class="form-control" @change="updateNoteList()"></select>
                        <br><br>
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
                    <div class="tab-pane fade" id="pills-absente" role="tabpanel" aria-labelledby="pills-absente-tab">
                        <hr>
                        todo
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
$('#materii_select').html(materii_select);
$('#profesori_materii').html(text);
@endif
</script>
@endsection
