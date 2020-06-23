<template>
  <div>
    <admin-header></admin-header>
    <div id="body">
      <div id="menu">
        <admin-side-bar></admin-side-bar>
      </div>
      <div id="main"></div>
    </div>
  </div>
</template>
<script>
import AdminHeader from "./AdminHeader.vue";
import AdminSideBar from "./AdminSideBar.vue";
import { message } from '../../message';

export default {
  name: "home",
  mounted() {
    if (this.$store.state.currentUser.role_id === this.$store.state.role.member) {
      this.$store.dispatch("logout");
      this.$router.push({ name: "login" });
    }
  },
  methods: {
    logout() {
      this.$store.dispatch("logout");
      this.$router.push({ name: "login" });
    }
  },
  components: {
    AdminHeader,
    AdminSideBar
  }
};
</script>
<style scoped>
body {
  font-family: "Times New Roman", Times, serif;
  size: 20px;
  width: 100%;
}
#body {
  width: 100%;
}
#menu {
  padding-top: 10px;
  width: 15%;
  min-height: calc(100vh - 70px);
  border-right: 1px solid black;
  float: left;
}
#main {
  width: 85%;
  float: right;
}
</style>