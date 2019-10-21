<template>
  <div>
      <div class="nav-scroller bg-white box-shadow" v-if="loaded == true">
        <nav class="nav nav-underline">
            <router-link class="nav-link" :to="{ name: 'Index' }">Acasa</router-link>
            <router-link class="nav-link" :to="{ path: '/classes/' + this.Auth.user.Class  }" v-if="this.Auth.check == true && this.Auth.user.Class !== 0">
                Clasa mea
                <span class="badge badge-pill bg-light align-text-bottom">{{this.InMyClass}}</span>
            </router-link>
            <router-link class="nav-link" :to="{ path: '/classes/mine' }" v-if="this.Auth.check == true && this.Auth.user.InSchoolFunction !== 0">
                Clasele mele
            </router-link>
            <router-link class="nav-link" :to="{ path: '/classes/all' }">
                Toate Clasele
            </router-link>
        </nav>
      </div>
  </div>
</template>
<script>
export default {
    props:{
        loaded:{
            type: Boolean
        }
    },
    created(){

    },
    mounted() {

    },
    methods: {
        
    },
}
</script>