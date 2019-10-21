<template>
  <div>
    <div class="row" v-if="loaded">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    Clasa a {{ this.class.Number}}-a {{this.class.Character}} (Diriginte {{this.diriginte.LastName}} {{this.diriginte.FirstName}})
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
                                <tr v-for="(s, index) in students">
                                    <td>{{ index }}</td>
                                    <td>
                                        {{s.LastName}} {{s.FirstName}}
                                        <div v-if="isChief(s.ID)" class="badge badge-danger" data-toggle="tooltip" title="Seful clasei."><i class="fa fa-user-secret"></i> Seful clasei</div>
                                    </td>
                                    <td>
                                        <router-link class="nav-link" :to="{ path: '/profile/' + s.ID }">
                                            Acceseaza profilul
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
            <div class="card" v-if="Auth.checked == true && Auth.user.InSchoolFunction == 1 && Auth.user.Class == this.class.ID">
                <div class="card-header text-center">
                    Administreaza clasa.
                </div>
                <div class="card-body">
                    <label for="sefulclasei">Alegeti seful clasei.</label>
                    <select name="sefulclasei" id="sefulclasei" class="form-control" data-toggle="tooltip" v-bind:data-title="'Actualul sef al clasei - ' + this.class.chief.LastName + ' ' + this.class.chief.FirstName" v-bind:title="'Actualul sef al clasei - ' + this.class.chief.LastName + ' ' + this.class.chief.FirstName">
                        <option v-bind:value="this.class.Chief" class="bg-danger text-white">{{this.class.chief.LastName}} {{this.class.chief.FirstName}}</option>
                        <option v-for="s in students" v-bind:value="s.ID" v-if="!isChief(s.ID)">{{s.LastName}} {{s.FirstName}}</option>
                    </select>
                    <br>
                    <button class="btn btn-info rounded-0" style="width:100%;" v-on:click="choseMyChief()">Alegeti!</button>
                    <hr>
                    <label for="mesajclasa">Trimiteti mesaj pentru toata clasa.</label>
                    <input type="text" name="mesajclasa" id="mesajclasa" class="form-control" v-bind:placeholder="'Scrieti un mesaj ce va fi trimis tuturor elevilor ai clasei a ' + this.class.Number + '-a ' + this.class.Character + '!'">
                    <br>
                    <button class="btn btn-info rounded-0" style="width:100%;" @click="buzzMyClass()">Trimiteti!</button>
                    <hr>
                    <label for="newLastN">Introduceti numele noului elev.</label>
                    <input type="text" class="form-control" name="newLastN" id="newLastN">
                    <label for="newFirstN">Introduceti prenumele noului elev.</label>
                    <input type="text" class="form-control" name="newFirstN" id="newFirstN">
                    <label for="newEmail">Introduceti Email-ul noului elev.</label>
                    <input type="email" class="form-control" name="newEmail" id="newEmail">
                    <span class="text-danger">Parola va fi trimisa pe mail-ul de mai sus.Aceasta va fi aleatorie si va fi formata din 8 caractere de tipul a-Z, 0-9.</span>
                    <button class="btn btn-info rounded-0" style="width:100%;" @click="addNewStudent()">Adaugati!</button>
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
            class: [],
            diriginte: [],
            students: [],
            profesori: [],
            materii: []
        }
    },
    created(){
        this.fetchMyClass();
    },
    mounted() {
        
    },
    methods: {
        isChief: function(id){
            if(id == this.class.Chief)
                return true;
            else return false;
        },
        fetchMyClass: async function(page = 1) {
            let data = await axios({
                url: _PAGE_URL + '/api/fetchMyClass/' + this.$route.params.id,
                method: 'get',
            });
            data = data.data;
            this.class = data.class;
            this.diriginte = data.diriginte;
            this.materii = data.materii;
            this.profesori = data.profesori;
            this.students = data.students;
            this.loaded = true;
            console.log(data);
        }
    },
};
</script>