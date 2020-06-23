<template>
  <div>
    <h4>&nbsp;&nbsp;Menu</h4>
    <ul>
      <li v-if="checkRole.release_number">
        <router-link :to="{ name: 'releaseNumber'}">Release number</router-link>
      </li>
      <li v-if="checkRole.user_admin">
        <router-link :to="{ name: 'userAdmin'}">User admin</router-link>
      </li>
      <li v-if="checkRole.category">
        <router-link :to="{ name: 'category'}">Category</router-link>
      </li>
      <li>
        <router-link :to="{ name: 'post'}">Article</router-link>
      </li>
      <li>
        <router-link :to="{ name: 'imageManages'}">Image</router-link>
      </li>
      <li v-if="checkRole.member">
        <router-link :to="{ name: 'member'}">Member</router-link>
      </li>
      <li v-if="checkRole.setting">
        <router-link :to="{ name: 'setting'}">Setting</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "admin-side-bar",
  data() {
    return {
      checkRole: {
        release_number: true,
        user_admin: true,
        category: true,
        member: true,
        setting: true
      }
    };
  },
  created() {},
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      //  get role of current user
      if (
        this.$store.state.currentUser.role_id ==
        this.$store.state.role.contributor
      ) {
        this.checkRole.release_number = this.checkRole.category = this.checkRole.user_admin = this.checkRole.member = this.checkRole.setting = false;
      }
      if (
        this.$store.state.currentUser.role_id == this.$store.state.role.editor
      ) {
        this.checkRole.user_admin = this.checkRole.member = this.checkRole.setting = false;
      }
    }
  }
};
</script>
<style scoped>
body {
  font-family: Sans-serif;
  size: 20px;
  padding-top: 15px;
  width: 17%;
  float: right;
}
a {
  color: grey;
  font-size: 1.3vw;
}
a:hover {
  text-decoration: none;
}

#arrow {
  border-right: 2px solid black;
  border-bottom: 2px solid black;
  width: 10px;
  height: 10px;
  transform: rotate(-45deg);
  margin-top: 40px;
}
h4 {
  margin-left: 20px;
  font-size: 20px;
  font-weight: bold;
  color: orange;
}
ul li {
  margin-left: 30px;
  color: grey;
  position: relative;
  padding-bottom: 10px;
}

ul {
  list-style: none;
}

ul li:before {
  content: "";
  position: absolute;
  border-right: 2px solid black;
  border-bottom: 2px solid black;
  width: 10px;
  height: 10px;
  top: calc(50% - 4px);
  left: -20px;
  transform: translateY(-50%) rotate(-45deg);
}
.router-link-active {
  font-weight: bolder;
  color: orange;
}
</style>