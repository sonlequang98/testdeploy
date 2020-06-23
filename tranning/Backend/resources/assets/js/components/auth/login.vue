<template>
  <div id="form-login">
    <h1 class="text-center">SPORT MAGAZINE</h1>
    <div class="error">
      <p class="alert alert-danger" v-if="authError">{{ authError }}</p>
    </div>
    <div class="error" v-if="errors">
      <p class="alert alert-danger" v-for="error in errors">{{ error }}</p>
    </div>
    <div class="card-body">
      <form @submit.prevent="authenticate">
        <div class="form-group row">
          <input
            type="text"
            v-model="form.email"
            placeholder="Enter your email"
            class="form-control form-input"
          />
        </div>

        <div class="form-group row">
          <input
            type="password"
            v-model="form.password"
            placeholder="Enter your password"
            class="form-control form-input"
          />
        </div>
        <div class="form-group row">
          <button class="btn-login">Login</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { login } from "../../helpers/auth";
export default {
  name: "login",
  data() {
    return {
      form: {
        email: "",
        password: ""
      },
      errors: [],
      reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
    };
  },
  methods: {
    checkValidate() {},
    authenticate() {
      this.errors = [];
      if (
        this.form.email.length <= 64 &&
        this.form.email.length <= 64 &&
        this.form.password.length <= 72 &&
        this.form.password.length &&
        this.reg.test(this.form.email)
      ) {
        this.$store.dispatch("login");
        login(this.$data.form)
          .then(res => {
            this.$store.commit("loginSuccess", res);
            this.$router.push({ path: "/" });
          })
          .catch(error => {
            this.$store.commit("loginFailed", { error });
          });
      }
      if (this.form.email.length === 0)
        this.errors.push("Please input your email");
      else if (this.form.email.length > 64)
        this.errors.push("Email must be 6 to 64 character");
      else if (!this.reg.test(this.form.email))
        this.errors.push("Please enter correct email");
      if (this.form.password.length === 0)
        this.errors.push("Please enter password");
      else if (this.form.password.length > 72)
        this.errors.push("Password must be 6 to 72 character");
    }
  },
  computed: {
    authError() {
      this.errors = [];
      return this.$store.getters.authError;
    }
  }
};
</script>
<style scoped>
h1 {
  font-size: 36px;
    font-weight: bold;
    color: orange;
}
#form-login {
  width: 40%;
  margin: 0 auto;
  margin-top: 70px;
  padding: 70px;
  border: 2px solid #227447;
}
.form-input {
  margin: 0 auto;
  width: 70%;
}
.btn-login {
  margin-left: 40%;
  width: 120px;
  color: #ffffff;
  border: 1px solid black;
  height: 43px;
  background-color: #5b9bd5;
}

.alert-danger {
  width: 72%;
  padding-left: 0%;
  padding: 0% 0;
  margin-top: -1%;
  margin-left: 14%;
}
</style>