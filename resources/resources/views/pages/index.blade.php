@extends('template')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white card-body border-0 rounded-0">
            <div class="row">
                <div class="col-md-3">
                    <i class="fa fa-user-graduate fa-4x"></i>
                </div>
                <div class="col-md-9 text-right h4">
                    Total Elevi<br>
                    {{$total_students}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white card-body border-0 rounded-0">
            <div class="row">
                <div class="col-md-3">
                    <i class="fa fa-chalkboard-teacher fa-4x"></i>
                </div>
                <div class="col-md-9 text-right h4">
                    Total Profesori<br>
                    {{$total_teachers}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white card-body border-0 rounded-0">
            <div class="row">
                <div class="col-md-3">
                    <i class="fa fa-school fa-4x"></i>
                </div>
                <div class="col-md-9 text-right h4">
                    Total Clase<br>
                    {{$total_classes}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <hr>
        <div class="card border-0 rounded-0">
            <div class="card-header">Despre {{$school->name}}</div>
            <div class="card-body">
                {!!$school->description!!}
            </div>            
        </div>
    </div>
</div>
<Br><br>
@endsection