<template>
  <div>
    <div class="row" v-if="loaded">
      <div class="col-md-4 mb-2">
          <div class="card bg-primary text-white card-body border-0 rounded-0">
              <div class="row">
                  <div class="col-md-3">
                      <i class="fa fa-user-graduate fa-4x"></i>
                  </div>
                  <div class="col-md-9 text-right h4">
                      Total Elevi<br>
                      {{this.school_data.total_students}}
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-4 mb-2">
          <div class="card bg-warning text-white card-body border-0 rounded-0">
              <div class="row">
                  <div class="col-md-3">
                      <i class="fa fa-chalkboard-teacher fa-4x"></i>
                  </div>
                  <div class="col-md-9 text-right h4">
                      Total Profesori<br>
                      {{this.school_data.total_teachers}}
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-4 mb-2">
          <div class="card bg-danger text-white card-body border-0 rounded-0">
              <div class="row">
                  <div class="col-md-3">
                      <i class="fa fa-school fa-4x"></i>
                  </div>
                  <div class="col-md-9 text-right h4">
                      Total Clase<br>
                      {{this.school_data.total_classes}}
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-12">
          <hr>
          <div class="card border-0 rounded-0">
              <div class="card-header">Despre {{this.school_data.school.name}}</div>
              <div class="card-body" v-html="this.school_data.school.description">
              </div>            
          </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data(){
    return {
      loaded: false,
      school_data: []
    }
  },
  created(){
    this.fetchIndex();
  },
  mounted() {

  },
  methods: {
    fetchIndex: async function(){
      let data = await axios({
        url: _PAGE_URL + '/api/fetchIndex',
        method: 'get',
      });
      this.school_data = data.data;
      this.loaded = true;
    }
  },
};
</script>