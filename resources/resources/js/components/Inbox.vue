<template>
  <div>
      <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead class="bg-dark text-white">
                            <th>#</th>
                            <th>Text</th>
                            <th>Data</th>
                    </thead>
                    <tbody v-if="loaded">
                        <tr v-for="notif in notifications.data">
                            <td>
                                {{notif.ID}}
                            </td>
                            <td v-bind:class="{'text-danger': notif.Read == 0 }">
                                    {{notif.Message}}
                            </td>
                            <td>
                                    {{notif.PostedOn}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="m-2 pagination justify-content-center">
                    <pagination :data="notifications" @pagination-change-page="fetchInbox"></pagination>
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
            notifications: []
        }
    },
    created(){
        this.fetchInbox();
    },
    mounted() {
        
    },
    methods: {
        fetchInbox: async function(page = 1) {
        if(this.Auth.check == false)
            return this.$router.push('/');
        let data = await axios({
            url: _PAGE_URL + '/api/fetchInbox?page=' + page,
            method: 'get',
        });
        this.notifications = data.data;
        console.log(this.notifications);
        this.loaded = true;
        }
    },
};
</script>