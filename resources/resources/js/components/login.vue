<template>
  <div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
        <div class="card border-0 rounded-0">
          <div class="card-header text-center h5">
            <i class="fa fa-sign-in-alt"></i> Conecteaza-te!
          </div>
          <div class="card-body">
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-users"></i></div>
              </div>
              <input type="text" class="form-control" id="lastname" placeholder="Numele de familie">
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-user"></i></div>
              </div>
              <input type="text" class="form-control" id="firstname" placeholder="Prenumele">
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-key"></i></div>
              </div>
              <input type="text" class="form-control" id="password" placeholder="Parola">
            </div>
            <hr>
            <button type="button" class="btn btn-info border-0 rounded-0" style="width:100%" @click="loginForm()">Conecteaza-te!</button>
          </div>
        </div>
      </div>
      <div class="col-md-2"></div>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      message: 'This is the home component!'
    };
  },
  
};
</script>