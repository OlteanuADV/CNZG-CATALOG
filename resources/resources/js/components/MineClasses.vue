<template>
  <div>
    <div class="row" v-if="loaded">
        <div class="col-md-12">
            <h5 class="text-center">Clasele mele.</h5>
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
                        <tr v-for="(clas, index) in this.classes">
                            <td>
                                {{ index+1 }}
                            </td>
                            <td>
                                <router-link :to="{ path: '/class/' + clas.ID }">{{clas.Number}} {{clas.Character}}</router-link>
                            </td>
                            <td>
                                <router-link :to="{ path: '/profile/' + clas.diriginte.ID }">{{clas.diriginte.LastName}} {{clas.diriginte.FirstName}}</router-link>
                            </td>
                            <td>
                                {{clas.users.length}}
                            </td>
                        </tr>
                    </tbody>
                </table>
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
            classes: [],
        }
    },
    created(){
        this.fetchMineClasses();
    },
    mounted() {
        
    },
    methods: {
        fetchMineClasses: async function() {
            if(this.Auth.check == false)
                return this.$router.push('/');
            let data = await axios({
                url: _PAGE_URL + '/api/fetchMineClasses/',
                method: 'get',
            });
            data = data.data;
            this.classes = data;
            this.loaded = true;
            console.log(data);
        },
    },
};
</script>