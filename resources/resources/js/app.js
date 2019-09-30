window.Vue = require('vue');
window.axios = require('axios');
//Vue.component('login', require('./components/login.vue').default);

const app = new Vue({
    el: '#app',
    mounted(){
        console.log('montat');
    },
    methods: {
        Swal(type, text, title){
            Swal.fire({
                type: type,
                title: title,
                text: text,
            });
        },
        loginForm: async function(){
            if(firstname.value.length == 0)  
                return this.Swal('error','Introdu prenumele!','Oops...');
            if(lastname.value.length == 0)
                return this.Swal('error','Introdu numele de familie!','Oops...');
            if(password.value.length == 0)
                return this.Swal('error','Introdu parola!','Oops...');
            let data = await axios({
                url: _PAGE_URL + 'login',
                method: 'post',
                data: {
                    _token: _token,
                    firstname: firstname.value,
                    lastname: lastname.value,
                    password: password.value
                }
            });
            data = data.data;
            console.log(data);
            if(data.success == 1)
            {

                this.Swal('success',data.message,'Congrats!');
                return setTimeout(function(){
                    document.location = _PAGE_URL;
                }, 1500);
            }
            else{
                return this.Swal('error', data.message ,'Oops...');
            }
        },
        updateNoteList: async function(){
            $('#note').html('');
            let materie = $('#materii_select').val();
            let user_id = $('#user_id').val();
            let data = await axios({
                url: _PAGE_URL + 'api/getGrades/'+user_id+'/'+materie,
                method: 'get'
            });
            let note = data.data;
            let notelemele = '';
            for(let i = 0; i < note.length; i++){
                notelemele = notelemele + `<tr><td>${i+1}</td><td>${note[i].Value}</td><td>${note[i].Date}</td></tr>`;
            }
            $('#note').html(notelemele);
        },
        postNewGrade: async function(){
            let data = await axios({
                url: _PAGE_URL + '/api/postNewGrade',
                method: 'post',
                data: {
                    _token: _token,
                    materie: master_subjects.value,
                    nota: new_grade.value,
                    data: new_date.value,
                    user_id: user_id.value
                }
            });
            data = data.data;

            if(data.success == 1)
            {

                this.Swal('success',data.message,'Congrats!');
                return setTimeout(function(){
                    document.location = document.location;
                }, 1500);
            }
            else {
                return this.Swal('error', data.message ,'Oops...');
            }
        },
        postNewAbs: async function(){
            let data = await axios({
                url: _PAGE_URL + '/api/postNewAbs',
                method: 'post',
                data: {
                    _token: _token,
                    materie: master_subjects_abs.value,
                    data: new_date_abs.value,
                    user_id: user_id.value
                }
            });
            data = data.data;

            if(data.success == 1)
            {

                this.Swal('success',data.message,'Congrats!');
                return setTimeout(function(){
                    document.location = document.location;
                }, 1500);
            }
            else {
                return this.Swal('error', data.message ,'Oops...');
            } 
        }
    }
});