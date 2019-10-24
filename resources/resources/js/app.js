import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './components/App'
import Index from './components/Index'
import Login from './components/Login'
import Inbox from './components/Inbox'
import MyClass from './components/MyClass'
import AllClasses from './components/AllClasses'
import MineClasses from './components/MineClasses'
import Profile from './components/Profile'
import JQuery from 'jquery'



Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(VueRouter)
window.axios = require('axios');
//window.$ = JQuery;

//Router
const router = new VueRouter({
    mode: 'history',
    base: '/catalogznk/',
    routes: [
        {
            path: '/',
            name: 'Index',
            component: Index
        },
        {
            path: '/login',
            name: 'Login',
            component: Login
        },
        {
            path: '/inbox',
            name: 'Inbox',
            component: Inbox,
        },
        {
            path: '/classes/all',
            name: 'AllClasses',
            component: AllClasses,
        },
        {
            path: '/classes/mine',
            name: 'MineClasses',
            component: MineClasses,
        },
        {
            path: '/class/:id',
            name: 'MyClass',
            component: MyClass,
        },
        {
            path: '/profile/:id',
            name: 'Profile',
            component: Profile,
        },
    ],
});
//End Router

Vue.prototype.Auth = window.Auth;
Vue.prototype.Swal = function(type, text, title){
    Swal.fire({
        type: type,
        title: title,
        text: text,
    });
}
const app = new Vue({
    el: '#app',
    components: {
        'app': App
    },
    router,
    created(){
        //this.fetchUser();
    },
    mounted(){
        
    },
    methods: {
        fetchUser: async function(){
            let data = await axios({
                url: _PAGE_URL + '/api/fetchUser',
                method: 'get',
            });
            data = data.data;
            console.log(data);
            Vue.prototype.Auth = data;
            this.Auth = data;
        },
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
                url: _PAGE_URL + '/api/login',
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
            let sum = 0;
            for(let i = 0; i < note.length; i++){
                notelemele = notelemele + `<tr><td>${i+1}</td><td>${note[i].Value}</td><td>${note[i].Date}</td></tr>`;
                sum = sum + note[i].Value;
            }
            if(note.length > 0)
            {
                let avg = sum / note.length;
                $('#materii_select_label').html(`In prezent media acestui elev este <b>${avg}</b>.`);
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
        postNewAbs: async function() {
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
        },
        choseMyChief: async function() {
            let data = await axios({
                url: _PAGE_URL + '/api/choseMyChief',
                method: 'post',
                data: {
                    _token: _token,
                    sefulclasei: sefulclasei.value
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
        buzzMyClass: async function() {
            let data = await axios({
                url: _PAGE_URL + '/api/buzzMyClass',
                method: 'post',
                data: {
                    _token: _token,
                    mesajclasa: mesajclasa.value
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
        addNewStudent: async function(){
            let data = await axios({
                url: _PAGE_URL + '/api/addNewStudent',
                method: 'post',
                data: {
                    _token: _token,
                    newLastN: newLastN.value,
                    newFirstN: newFirstN.value,
                    newEmail: newEmail.value,
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
        }
    }
});