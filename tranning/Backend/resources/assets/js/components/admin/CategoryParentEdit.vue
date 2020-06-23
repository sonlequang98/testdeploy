<template>
  <div>
    <div id="header">
      <admin-header></admin-header>
    </div>
    <div id="body">
      <div id="menu">
        <admin-side-bar></admin-side-bar>
      </div>
    </div>
    <div id="main">
      <div id="main-header">
        <h4>Edit category parent</h4>
      </div>
      <div id="main-body">
        <form @submit.prevent="edit" autocomplete="off">
          <div class="error" v-if="errors">
            <p class="alert alert-danger" v-for="error in errors">{{ error }}</p>
          </div>
          <label for>Category name</label>
          <input type="text" v-model="category.category_name" id="input-name" />
          <br />
          <label for>Global display</label>
          <input type="checkbox" v-model="category.global_display" />
          <br />
          <label for>Menu display</label>
          <input type="checkbox" v-model="category.menu_display" />
          <br />
          <button type="submit" id="btnSave">Save</button>
        </form>
        <button id="btnCancel" @click="cancel">Cancel</button>
      </div>
    </div>
    <loading
      :active.sync="isLoading"
      :is-full-page="fullPage"
      :height="64"
      :width="64"
      :opacity="0.2"
    ></loading>
  </div>
</template>

<script>
import AdminHeader from "./AdminHeader.vue";
import AdminSideBar from "./AdminSideBar.vue";
import { message } from "../../message";
import 'vue-loading-overlay/dist/vue-loading.css';
import Loading from 'vue-loading-overlay';

export default {
  name: "categoryCreate",
  components: {
    AdminHeader,
    AdminSideBar,
    Loading
  },
  mounted() {
    if (
      this.$store.state.currentUser.role_id ===
      this.$store.state.role.contributor
    ) {
      this.$router.push({ name: "home" });
    }
  },
  created() {
    this.isLoading = true;
    axios
      .get("/categories/" + this.$route.params.id + "/edit")
      .then(res => {
        this.category = res.data.data;
        this.isLoading = false;
      })
      .catch(err => {
        console.log(err.errors);
        this.isLoading = false;
      });
  },
  data() {
    return {
      isLoading: false,
      fullPage: false,
      category: [],
      errors: []
    };
  },
  methods: {
    cancel() {
      this.$router.push({ name: "category" });
    },
    checkValidate() {
      this.errors = [];
      if (this.category.category_name.length == 0) {
        this.errors.push(message.emptyCategory);
        return false;
      }
      if (
        this.category.category_name.length < 5 ||
        this.category.category_name.length > 50
      ) {
        this.errors.push(message.overCategoryLength);
        return false;
      }
      return true;
    },
    edit() {
      this.errors = [];
      if (!this.checkValidate()) {
        return;
      }
      axios
        .put("/categories/parent/" + this.category.id, this.category)
        .then(res => {
          this.$router.push({ name: "category" });
        })
        .catch(err => {
          this.errors.push(message.errSaveCategory);
        });
    }
  }
};
</script>

<style scoped>
a:hover {
  text-decoration: none;
}

h4 {
  font-weight: bold;
  margin-bottom: 30px;
  font-size: 20px;
  color: orange;
}

body {
  font-family: Sans-serif;
  size: 20px;
  width: 100%;
  font-size: 20px;
}
#body {
  width: 100%;
}
#header {
  width: 100%;
}
#menu {
  padding-top: 10px;
  width: 15%;
  float: left;
  min-height: calc(100vh - 70px);
  border-right: 1px solid black;
}

#main {
  position: relative;
  padding-top: 35px;
  width: 85%;
  float: right;
}
#main-header {
  margin-left: 30px;
}

#main-body {
  width: 100%;
  padding: 3%;
  padding-top: 1%;
}

#main-body label {
  margin-right: 5%;
}

#main-body label,
input {
  margin-top: 2%;
}
#main-body #input-name {
  text-indent: 10px;
  width: 70%;
}

button {
  border: 1px solid #5b9bd5;
}

#btnSave {
  width: 5%;
  background-color: #deebf7;
  margin-top: 2%;
  float: left;
  padding: 0.5% 0;
}
#btnCancel {
  width: 10%;
  background-color: #fff2cc;
  float: left;
  padding: 0.5% 0;
  margin-top: 2%;
  margin-left: 2%;
}
.errors {
  color: #ec4134;
  text-align: left;
}

.alert-danger {
  padding-top: 0.4%;
  padding-bottom: 0.4%;
  margin-right: 20%;
}
</style>