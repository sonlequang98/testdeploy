<template>
  <div id="header">
    <div id="menu_left">
      <h3 class="text-center">SPORT MAGAZINE</h3>
    </div>
    <div id="main"></div>
    <div id="menu_right">
      <ul class="nav navbar-nav navbar-right" id="drop-down">
        <li class="dropdown">
          <button
            class="btn btn-secondary dropdown-toggle"
            type="button"
            id="dropdownMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >{{user.name}}</button>
          <!-- <h3 class="dropdown-toggle" data-toggle="dropdown">{{user.name}}</h3> -->
          <ul class="dropdown-menu">
            <li>
              <form @submit.prevent="logout">
                <button type="submit" id="btn-logout" style="color:black;">Logout</button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: "admin-header",
  data() {
    return {
      user: []
    };
  },
  mounted() {
    this.user = this.$store.state.currentUser;
  },
  methods: {
    logout() {
      this.$store.dispatch("logout");
      this.$router.push({ name: "login" });
    }
  }
};
</script>

<style scoped>
body {
  background-color: #5b9bd5;
  height: 70px;
  width: 100%;
  font-family: Sans-serif;
}
#header {
  width: 100%;
  background-color: #5b9bd5;
  height: 70px;
  padding-bottom: 15px;
}
#menu_left {
  color: white;
  padding-top: 10px;
  background-color: #5b9bd5;
  width: 17%;
  float: left;
  height: 55px;
}
#main {
  background-color: #5b9bd5;
  width: 70%;
  float: left;
  height: 55px;
}
#menu_right {
  color: white;
  padding-top: 10px;
  background-color: #5b9bd5;
  width: 13%;
  padding-right: 1%;
  height: 55px;
  float: right;
}
#btn-logout {
  background-color: #ffffff;
  border: 1px solid #5b9bd5;
  width: 100%;
  height: 2.5em;
}
h3 {
  margin-top: 10px;
  font-size: 1.7vw;
}
#dropdownMenuButton {
  color: #ffffff;
  background-color: #5b9bd5;
  font-size: 1.7vw;
}

#drop-down {
  padding-right: 60px;
}
#menu_right ul li ul {
  border: none;
  box-shadow: none;
}
</style>
