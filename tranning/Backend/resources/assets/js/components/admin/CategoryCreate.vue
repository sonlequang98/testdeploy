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
        <h4>Create category</h4>
      </div>
      <div id="main-body">
        <form @submit.prevent="create" autocomplete="off">
          <div class="error" v-if="errors">
            <p class="alert alert-danger" v-for="error in errors">{{ error }}</p>
          </div>
          <label for>Category name</label>
          <input type="text" id="input-name" v-model="category.category_name" />
          <br />
          <label for>Category parent</label>
          <select v-model="category.parent_id">
            <option
              value
              v-for="category in categoryParents"
              :value="category.id"
            >{{category.category_name}}</option>
          </select>
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
  </div>
</template>

<script>
import AdminHeader from "./AdminHeader.vue";
import AdminSideBar from "./AdminSideBar.vue";
import { message } from "../../message";

export default {
  name: "categoryCreate",
  components: {
    AdminHeader,
    AdminSideBar
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
    this.getCategoryParent();
  },
  data() {
    return {
      category: {
        category_name: "",
        parent_id: null,
        global_display: 0,
        menu_display: 0
      },
      categoryParents: [],
      errors: []
    };
  },
  methods: {
    cancel() {
      this.$router.push({
        name: "category"
      });
    },
    getCategoryParent() {
      axios
        .get("/categories/parent")
        .then(res => {
          this.categoryParents = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    checkValidate() {
      this.errors = [];
      if (this.category.category_name.length === 0) {
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
    create() {
      if (!this.checkValidate()) {
        return;
      }
      axios
        .post("/categories", this.category)
        .then(res => {
          this.$router.push({
            name: "category"
          });
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
}

#main {
  position: relative;
  padding-top: 35px;
  width: 85%;
  float: right;
  border-left: 1px solid black;
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

#main-body select {
  width: 70%;
  margin-left: -1%;
  padding: 0.3% 0;
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