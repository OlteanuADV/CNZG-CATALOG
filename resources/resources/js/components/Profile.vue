<template>
  <div>
    <div class="row" v-if="loaded">
        <div class="col-md-12" v-if="this.user.InSchoolFunction == 0">
            <div class="alert alert-danger">
                Acest elev are deja {{are10absente()}} absente nemotivate!
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa fa-user fa-10x"></i>
                    <hr>
                    {{this.user.LastName}} {{this.user.FirstName}}<br>
                    <span v-if="this.user.InSchoolFunction == 0" class="badge badge-danger" data-toggle="tooltip" v-bind:title="'Diriginte - ' + this.diriginte.LastName + ' ' + this.diriginte.FirstName">Elev in clasa {{this.class.Number}} {{this.class.Character}}</span>
                    <span v-if="this.user.InSchoolFunction == 1 && this.class !== 0" class="badge badge-info">Profesor de {{this.user.prof_subject.Name}} si diriginte la clasa {{this.class.Number}} {{this.class.Character}}</span>
                    <span v-if="this.user.InSchoolFunction == 1 && this.class == 0" class="badge badge-info">Profesor de {{this.user.prof_subject.Name}}</span>
                </div>
            </div>
            <div v-if="this.user.InSchoolFunction == 0">
                <br><br>
                <div class="card">
                    <div class="card-body text-center" id="profesori_materii" ref="profesori_materii">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8" v-if="this.user.InSchoolFunction == 0">
            
            <div v-if="(this.Auth.user.InSchoolFunction == 1 && EsteInClasa()) || this.Auth.user.InSchoolFunction == 1">
                <div class="card" >
                    <div class="card-header">Panou Administrativ al elevului {{this.user.LastName}} {{this.user.FirstName}}</div>
                    <div class="card-body">
                        <div v-if="this.Auth.user.InSchoolFunction > 1 || (this.Auth.user.InSchoolFunction > 0 && this.Auth.user.Class == this.user.Class)">
                            <label for="master_subjects">Selecteaza materia la care doriti sa ii oferiti nota.</label>
                            <select name="master_subjects" id="master_subjects" class="form-control" ref="master_subjects">
                                <option>None</option>
                            </select>
                            <label for="new_grade">Introduceti nota.</label>
                            <input type="number" class="form-control" name="new_grade" id="new_grade">
                            <label for="new_date">Introduceti data notei.</label>
                            <input type="date" class="form-control" name="new_date" id="new_date" v-bind:value="new Date()" v-bind:max="new Date()">
                            <br>
                            <button  class="btn btn-info border-0 rounded-0" style="width:100%" @click="postNewGrade()">Adauga-i nota!</button>

                            <hr>
                            <label for="master_subjects">Selecteaza materia la care doriti sa ii oferiti absenta.</label>
                            <select name="master_subjects_abs" id="master_subjects_abs" class="form-control" ref="master_subjects_abs">
                                <option>None</option>
                            </select>
                            <label for="new_date_abs">Introduceti data notei.</label>
                            <input type="date" class="form-control" name="new_date_abs" id="new_date_abs" v-bind:value="new Date()" v-bind:max="new Date()">
                            <br>
                            <button  class="btn btn-info border-0 rounded-0" style="width:100%" @click="postNewAbs()">Adauga-i absenta nemotivata!</button>
                        </div>
                        <div v-else>
                            <input type="hidden" id="master_subjects" name="master_subjects" v-bind:value="this.Auth.user.Subject">
                            <label for="new_grade">Introduceti nota.</label>
                            <input type="number" class="form-control" name="new_grade" id="new_grade">
                            <label for="new_date">Introduceti data notei.</label>
                            <input type="date" class="form-control" name="new_date" id="new_date" v-bind:value="new Date()" v-bind:max="new Date()">
                            <br>
                            <button  class="btn btn-info border-0 rounded-0" style="width:100%" @click="postNewGrade()">Adauga-i nota!</button>

                            <hr>
                            <input type="hidden" id="master_subjects_abs" name="master_subjects_abs" v-bind:value="this.Auth.user.Subject">
                            <label for="new_date_abs">Introduceti data notei.</label>
                            <input type="date" class="form-control" name="new_date_abs" id="new_date_abs" v-bind:value="new Date()" v-bind:max="new Date()">
                            <br>
                            <button  class="btn btn-info border-0 rounded-0" style="width:100%" @click="postNewAbs()">Adauga-i absenta nemotivata!</button>
                        </div>
                    </div>
                </div>
                <br>
            </div>


            <div class="card">
                <div class="card-body">
                    <div v-if="this.user.InSchoolFunction == 0">
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
                                <select id="materii_select" class="form-control" @change="updateNoteList()" ref="materii_select"></select>
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
                                <div v-for="abs in absente">
                                    Absenta <b class="text-danger" v-if="abs.Motivated == 0">nemotivata</b> <b class="text-success" v-else>motivata</b> in data de {{ abs.AbsenceDate }}, la {{materiename(abs.Subject)}}.
                                    <span v-if="AcordLaMotivare()">
                                        <a v-if="abs.Motivated == 0" v-on:click="motivateAbsence(abs.ID)" data-toggle="tooltip" title="Motivati aceasta absenta."><i class="fa fa-check text-success"></i></a>
                                        <a v-else v-on:click="demotivateAbsence(abs.ID)" data-toggle="tooltip" title="Demotivati aceasta absenta."><i class="fa fa-times text-danger"></i></a>
                                    </span>
                                    <br> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script>
export default {
    data(){
        return{
            loaded: false,

            user: [],
            absente: [],
            materii: [],
            class: [],
            diriginte: [],
            profesori: [],

            data: new Date()
        }
    },
    created(){
        this.fetchMineClasses();
        //absente
        //este profesor an clasa
        /*@php
                $esteinclasa = 0;
                foreach($profesori as $prof)    
                {
                    if($prof->ID == Auth::user()->ID)
                        $esteinclasa = 1;
                }
        @endphp*/
        
    },
    mounted() {
        
    },
    methods: {
        fetchMineClasses: async function() {
            if(this.Auth.check == false)
                return this.$router.push('/');
            let data = await axios({
                url: _PAGE_URL + '/api/fetchProfile/'+this.$route.params.id,
                method: 'get',
            });
            data = data.data;

            this.user = data.user;
            this.absente = data.absente;
            this.materii = data.materii;
            this.class = data.class;
            this.profesori = data.profesori;
            this.diriginte = data.diriginte;

            this.loaded = true;
            
            console.log(data);
            
            this.loadOthers();
        },
        EsteInClasa: function(){
            let esteinclasa = 0;
            for(let i = 0; i < this.profesori.length; i++){
                if(this.profesori[i].ID == this.Auth.user.ID)
                {
                    esteinclasa = 1;
                    break;
                }
            }
            if(esteinclasa == 0)
                return false;
            else
                return true;
        },
        are10absente: function(){
            let abstot = 0;
            for(let i = 0; i < this.absente.length; i++){
                if(this.absente[i].Motivated == 0)
                    abstot++;
            }
            return abstot;
        },
        materiename: function(absidsub){
            // @foreach($materii as $mat) @if($mat->ID == $abs->Subject) {{$mat->Name}} @endif @endforeach.
            for(let i = 0; i < this.materii.length; i++){
                if(this.materii[i].ID == absidsub)
                    return this.materii[i].Name;
            }
        },
        AcordLaMotivare: function(){
            if(this.Auth.user.InSchoolFunction > 1)
            {
                return true;
            }
            else 
            {
                return false;
            }
        },
        motivateAbsence: async function(id){
            let data = await axios({
                url: _PAGE_URL + '/api/motivateAbsence',
                method: 'post',
                data: {
                    _token: _token,
                    id: id
                }
            });
            data = data.data;

            if(data.success == 1)
            {
                this.Swal('success',data.message,'Congrats!');
            }
            else {
                return this.Swal('error', data.message ,'Oops...');
            }
        },
        demotivateAbsence: async function(id){
            let data = await axios({
                url: _PAGE_URL + '/api/demotivateAbsence',
                method: 'post',
                data: {
                    _token: _token,
                    id: id
                }
            });
            data = data.data;

            if(data.success == 1)
            {
                this.Swal('success',data.message,'Congrats!');
            }
            else {
                return this.Swal('error', data.message ,'Oops...');
            }
        },
        loadOthers: function() {
            if(this.user.InSchoolFunction == 0)
            {
                console.log('LOADOTHERS');
                let text = '';
                let materii_select = '<option>None</option>';
                for(let i = 0; i < this.materii.length; i++){
                    text = text + `${this.materii[i].Name} - ${this.profesori[i].LastName} ${this.profesori[i].FirstName}<br>`;
                    materii_select = materii_select + `<option value="${this.materii[i].ID}">${this.materii[i].Name}</option>`
                }
                console.log(this.$refs);
                let timeout = setTimeout(function(){
                    if(document.getElementById('master_subjects'))
                        document.getElementById('master_subjects').innerHTML = materii_select;
                    if(document.getElementById('master_subjects_abs'))
                        document.getElementById('master_subjects_abs').innerHTML = materii_select;
                    if(document.getElementById('materii_select'))
                        document.getElementById('materii_select').innerHTML = materii_select;
                    if(document.getElementById('profesori_materii'))
                        document.getElementById('profesori_materii').innerHTML = text;
                    clearTimeout(timeout);
                }, 500);
            }
        }
    },
};
</script>