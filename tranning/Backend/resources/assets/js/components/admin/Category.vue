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
        <h4>Manage category</h4>
        <button id="create-new" @click="create">Create category</button>
      </div>
      <div id="main-body">
        <div v-if="errors">
          <p class="alert alert-danger" v-for="error in errors">{{error}}</p>
        </div>
        <div class="block-category">
          <div class="block-cat-header">
            <p><strong>Category parent</strong></p>
          </div>
          <div :key="category.id" v-for="(category,index) in listCatParent">
            <div
              class="category-item"
              :class="[isActive===category.id?'active':'notActive']"
              @click="selectedParent(category.id)"
            >
              <p>{{category.category_name}}</p>
              <button class="btnDelete" @click="deleteCategory(category.id,index,'parent')">Delete</button>
              <button class="btnEdit" @click="editCatParent(category.id)">Edit</button>
            </div>
          </div>
        </div>
        <div v-if="listCatChild" class="block-category">
          <div class="block-cat-header">
            <p><strong>Category child</strong></p>
          </div>
          <div :key="category.id" v-for="(category,index) in listCatChild">
            <div class="category-item">
              <p>{{category.category_name}}</p>
              <button class="btnDelete" @click="deleteCategory(category.id,index,'children')">Delete</button>
              <button class="btnEdit" @click="editCatChild(category.id)">Edit</button>
            </div>
          </div>
        </div>

        <div id="delete" v-if="enable">
          <div id="confirm">
            <div id="header">
              <p>Delete category</p>
            </div>
            <div id="body">
              <p>Are you sure delete this category?</p>
              <button id="confirm-delete" @click="confirmDelete">Yes</button>
              <button id="cancel-delete" @click="cancelDelete">Cancel</button>
            </div>
          </div>
        </div>
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
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: "category",
  components: {
    AdminHeader,
    AdminSideBar,
    Loading
  },
  beforeCreate() {
    axios.get("/users/getCurrentRole").then(res => {
      if (res.data === this.$store.state.role.contributor) {
        this.$router.push({ name: "home" });
      }
    });
  },
  created() {
    this.isLoading = true;
    this.getListCatParent();
  },
  mounted() {},
  data() {
    return {
      listCatParent: [],
      listCatChild: [],
      selected: "",
      isActive: "",
      deleteItem: {
        id: "",
        list: "",
        index: ""
      },
      isLoading: false,
      fullPage: false,
      enable: false,
      errors: []
    };
  },
  methods: {
    getListCatParent() {
      axios
        .get("/categories/parent")
        .then(res => {
          this.listCatParent = res.data.data;
          this.isLoading = false;
        })
        .catch(err => {
          this.errors.push(message.errLoadCategory);
          this.isLoading = false;
        });
    },
    create() {
      this.$router.push({ name: "categoryCreate" });
    },
    selectedParent(id) {
      this.isLoading = true;
      axios
        .get("/categories/childrent/" + id + "")
        .then(res => {
          this.listCatChild = res.data.data;
          this.isActive = id;
          this.isLoading = false;
        })
        .catch(err => {
          console.log(err.errors);
          this.isLoading = false;
        });
    },
    deleteCategory(id, index, list) {
      this.deleteItem.id = id;
      this.deleteItem.list = list;
      this.deleteItem.index = index;
      this.enable = true;
    },
    confirmDelete() {
      axios
        .delete("/categories/" + this.deleteItem.id + "")
        .then(res => {
          if (this.deleteItem.list === "parent") {
            this.selectedParent(this.deleteItem.id);
            this.listCatParent.splice(this.deleteItem.index, 1);
          } else if (this.deleteItem.list === "children") {
            this.listCatChild.splice(this.deleteItem.index, 1);
          }
          this.deleteItem.index = null;
          this.deleteItem.id = null;
          this.enable = false;
          this.deleteItem.list = "";
        })
        .catch(err => {
          this.enable = false;
          this.errors.push("Can't delete this category");
          let self = this;
          setTimeout(function() {
            self.errors = [];
          }, 4000);
        });
    },
    cancelDelete() {
      this.deleteItem.id = null;
      this.deleteItem.index = null;
      this.deleteItem.list = "";
      this.enable = false;
    },
    editCatParent(id) {
      this.$router.push({
        name: "categoryParentEdit",
        params: { id: id }
      });
    },
    editCatChild(id) {
      this.$router.push({
        name: "categoryChildEdit",
        params: { id: id }
      });
    }
  }
};
</script>
<style scoped>
button {
  border: 1px solid #5b9bd5;
}

a:hover {
  text-decoration: none;
}

h4 {
  margin-left: 1%;
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
  min-height: calc(100vh - 70px);
  border-right: 1px solid black;
  float: left;
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
#create-new {
  background-color: #5b9bd5;
  width: 120px;
  color: white;
  padding: 4px;
  text-align: center;
  border: none;
  margin-left: 1%;
}
#create-new a {
  color: white;
}
#main-body {
  width: 100%;
  clear: both;
  float: none;
  margin-top: 30px;
  padding: 0px 3%;
}
.block-category {
  width: 49%;
  float: left;
  margin-right: 1%;
  background-color: #f2f2f2;
}

.block-cat-header {
  width: 100%;
  text-align: center;
  font-size: 2em;
  background-color: #e7e6e6;
  padding: 5px;
}
.category-item {
  background-color: #f2f2f2;
  display: inline-block;
  padding: 5px 10px;
  width: 100%;
  border: 1px solid black;
  padding: 1.5%;
  float: left;
}

.category-item p {
  float: left;
}

.category-item button {
  float: right;
  padding: 1% 18px;
  margin-left: 2%;
  border: 1px solid #5b9bd5;
}
.btnDelete {
  background-color: #fbe5d6;
}

.btnEdit {
  background-color: #e2f0d9;
}

#confirm {
  font-size: 120%;
  top: 40%;
  left: 40%;
  width: 500px;
  position: fixed;
  border: 1px solid #deebf7;
  border: 1px solid #5b9bd5;
}
#confirm #header {
  text-align: center;
  background-color: #e2f0d9;
  padding-top: 5px;
  height: 40px;
}

#confirm #body {
  padding: 30px;
  background-color: #f2f2f2;
  margin-top: -10px;
  border-top: 1px solid #5b9bd5;
  text-align: center;
}

#confirm #body button {
  margin-top: 30px;
  margin-left: 3px;
}
#confirm #body #confirm-delete {
  background-color: #deebf7;
}
#confirm #body #cancel-delete {
  background-color: #fff2cc;
}
.active {
  background-color: rgb(255, 255, 204);
  padding: 1.5%;
}

.notActive {
  background-color: #f2f2f2;
  padding: 1.5%;
}

#delete {
  position: fixed;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  height: 100%;
  left: 0;
  top: 0;
  z-index: 1;
  width: 100%;
}

.alert {
  margin-right: 1%;
  padding-top: 0.4%;
  padding-bottom: 0.4%;
}
</style>