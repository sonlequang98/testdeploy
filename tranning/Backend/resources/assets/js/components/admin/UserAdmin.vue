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
        <h4>Manage user admin</h4>
      </div>
      <div id="main-body">
        <div id="block-search">
          <form @submit.prevent="searchUser">
            <div class="search-label">
              <label for>User name</label>
              <br />
              <label for>Email</label>
            </div>

            <div class="search-input">
              <input type="text" v-model="searchData.name" placeholder="Enter user name" />
              <br />
              <input type="text" v-model="searchData.email" placeholder="Enter email" />
            </div>
            <div class="search-label">
              <label for>Role</label>
            </div>
            <div class="search-input">
              <select name id v-model="searchData.role_id">
                <option value>All</option>
                <option :key="role.id" v-for="role in roles" :value="role.id">{{role.name}}</option>
              </select>
              <br />
              <button class="btn" type="submit">Search</button>
            </div>
          </form>

          <div id="search-info">
            <button class="btn" @click="prev_page" :disabled="!users.prev_page_url">⇠Prev</button>
            <button class="btn" @click="next_page" :disabled="!users.next_page_url">Next⇢</button>
            <p>{{paginate.sizeUser}} /{{paginate.total}} Result</p>
          </div>
        </div>

        <div v-if="errors">
          <p class="alert alert-danger" v-for="error in errors">{{ error }}</p>
        </div>
        <div v-if="successful">
          <p class="alert alert-success">{{successful}}</p>
        </div>
        <div class="block-infor">
          <div class="index">#</div>
          <div class="user-name">
            <p>User name</p>
          </div>
          <div class="user-role">
            <p>Role</p>
          </div>
          <div class="user-email">
            <p>Email</p>
          </div>
          <div class="user-password">
            <p>Password</p>
          </div>
          <div class="user-action">
            <p>Create・Edit。Delete</p>
          </div>
        </div>

        <div class="block-register" v-if="(!selectedUser)">
          <form @submit.prevent="registerUser()">
            <div class="index"><p>#</p></div>
            <div class="user-name">
              <input type="text" placeholder="Enter user name" v-model="regisUser.name" />
            </div>
            <div class="user-role">
              <select name v-model="regisUser.role_id">
                <option :key="role.id" v-for="role in roles" :value="role.id">{{role.name}}</option>
              </select>
            </div>
            <div class="user-email">
              <input type="text" name id placeholder="Enter email" v-model="regisUser.email" />
            </div>
            <div class="user-password">
              <input type="password" placeholder="Enter password" v-model="regisUser.password" />
            </div>
            <div class="user-action">
              <p>
                <button type="submid">Save</button>
              </p>
            </div>
          </form>
        </div>

        <div class="block-user" :key="user.id" v-for="(user, index) in users.data">
          <div class="index">
            <p
              :class="[isActive!=user.id?'active':'notActive']"
              :style="[index==(users.data.length-1)?{borderLeft: '1px solid #5b9bd5'}:{}]"
            >{{setIndexPage(index)}}</p>
          </div>
          <div class="user-name">
            <input type="text" name v-model="user.name" :disabled="isActive!=user.id" />
          </div>
          <div class="user-role" :class="[isActive!=user.id?'active':'notActive']">
            <select
              name
              id
              v-model="user.role_id"
              :disabled="isActive!=user.id"
              :class="[isActive!=user.id?'active':'notActive']"
            >
              <option :key="role.id" v-for="role in roles" :value="role.id">{{role.name}}</option>
            </select>
          </div>
          <div class="user-email">
            <input type="text" v-model="user.email" :disabled="isActive!=user.id" />
          </div>
          <div class="user-password">
            <input
              placeholder="********"
              name
              id
              :disabled="isActive!=user.id"
              v-if="!(isActive===user.id)"
            />
            <input type="password" placeholder="********" v-if="(isActive===user.id)" />
          </div>
          <div
            class="user-action"
            :style="[index==(users.data.length-1)?{borderRight: '2px solid #5b9bd5'}:{}]"
            :id="[isActive!=user.id?'active':'notActive']"
          >
            <button id="btnEdit" @click="editUser(user,index)" v-if="!(isActive===user.id)">Edit</button>
            <button
              id="btnDelete"
              @click="deleteUser(user.id,index)"
              v-if="!(isActive===user.id)"
            >Delete</button>
            <button id="btnSave" v-if="!(isActive!=user.id)" @click="updateUser(user.id,index)">Save</button>
            <button id="btnCancel" @click="cancelEdit" v-if="!(isActive!=user.id)">Cancel</button>
          </div>
        </div>
        <div v-if="emptySearch" id="emptySearch">
          <p>{{emptySearch}}</p>
        </div>

        <div id="delete" v-if="enable">
          <div id="confirm" v-if="enable">
            <div id="header">
              <p>Delete user admin</p>
            </div>
            <div id="body">
              <p>Are you sure delete this user?</p>
              <button id="confirm-delete" @click="confirmDelete">Yes</button>
              <button id="cancel-delete" @click="cancelDelete">No</button>
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
  name: "userAdmin",
  components: {
    AdminHeader,
    AdminSideBar,
    Loading
  },
  beforeCreate() {
    axios.get("/users/getCurrentRole").then(res => {
      if (
        res.data === this.$store.state.role.contributor ||
        res.data === this.$store.state.role.editor
      ) {
        this.$router.push({ name: "home" });
      }
    });
  },
  created() {
    this.isLoading = true;
    this.getUsers("/useradmins");
    this.getRole();
  },
  mounted() {},
  data() {
    return {
      deleteItem: {
        id: "",
        index: ""
      },
      isActive: "",
      disable: false,
      enable: false,
      users: [],
      roles: [],
      isLoading: false,
      fullPage: false,
      successful:'',
      selectedUser: false,
      regisUser: {
        name: "",
        email: "",
        role_id: 2,
        password: ""
      },
      passwordUpdate: "",
      errors: [],
      searchData: {
        role_id: "",
        name: "",
        email: ""
      },
      paginate: {
        sizeUser: "",
        total: ""
      },
      reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
      tempUser: {
        id: "",
        name: "",
        email: "",
        role_id: ""
      },
      indexUserEdit: "",
      emptySearch:'',
    };
  },
  methods: {
    confirmDelete() {
      axios
        .delete("/useradmins/" + this.deleteItem.id + "")
        .then(res => {
          this.users.data.splice(this.deleteItem.index, 1);
          this.deleteItem.index = null;
          this.deleteItem.id = null;
          this.enable = false;
          this.paginate.sizeUser--;
          this.paginate.total--;
        })
        .catch(err => {
          this.errors.push(message.errDeleteUserAdmin);
          this.enable = false;
        });
    },
    cancelDelete() {
      this.deleteItem.id = null;
      this.deleteItem.index = null;
      this.enable = false;
    },
    getUsers(urlGetUsers) {
      this.isLoading = true;
      axios
        .get(urlGetUsers)
        .then(res => {
          this.users = res.data.data;
          this.paginate.sizeUser = this.users.to;
          this.paginate.total = this.users.total;
          this.isLoading = false;
        })
        .catch(err => {
          this.errors.push(message.errLoadUserAdmin);
          this.isLoading = false;
        });
    },
    getRole() {
      axios
        .get("/users/role")
        .then(res => {
          this.roles = res.data;
        })
        .catch(err => {
          console.log(err.errors);
        });
    },
    editUser(user, index) {
      this.tempUser.name = user.name;
      this.tempUser.email = user.email;
      this.tempUser.id = user.id;
      this.tempUser.role_id = user.role_id;
      this.errors = [];
      this.isActive = user.id;
      this.selectedUser = true;
      this.indexUserEdit = index;
    },
    deleteUser(id, index) {
      this.errors = [];
      this.deleteItem.id = id;
      this.deleteItem.index = index;
      this.enable = true;
    },
    cancelEdit() {
      this.users.data[this.indexUserEdit].name = this.tempUser.name;
      this.users.data[this.indexUserEdit].email = this.tempUser.email;
      this.users.data[this.indexUserEdit].role_id = this.tempUser.role_id;
      this.errors = [];
      this.isActive = "";
      this.selectedUser = false;
      this.errors = [];
    },
    checkValidate(name, email, password) {
      this.errors = [];

      if (name.length == 0) {
        this.errors.push(message.emptyNameUser);
        return false;
      }
      if (name.length > 100) {
        this.errors.push(message.overNameUserLength);
        return false;
      }
      if (email.length == 0) {
        this.errors.push(message.emptyEmail);
        return false;
      }
      if (!this.reg.test(email)) {
        this.errors.push(message.emptyEmail);
        return false;
      }
      if (password.length == 0) {
        this.errors.push(message.emptyPassword);
        return false;
      }
      if (password.length > 72) {
        this.errors.push(message.overPasswordLength);
        return false;
      }
      return true;
    },
    registerUser() {
      if (
        !this.checkValidate(
          this.regisUser.name,
          this.regisUser.email,
          this.regisUser.password
        )
      ) {
        return;
      }
      axios
        .post("/useradmins", this.regisUser)
        .then(res => {
          this.users.data.unshift(res.data.data);
          this.refreshFromRegister();
          this.paginate.sizeUser++;
          this.paginate.total++;
          this.successful = 'ユーザーを保存しました。';
          let self = this;
          setTimeout(function() {
            self.successful = null;
          }, 4000);
        })
        .catch(err => {
          this.errors.push(message.errSaveUserAdmin);
        });
    },
    refreshFromRegister() {
      this.regisUser.name = "";
      this.regisUser.email = "";
      this.regisUser.password = "";
    },
    updateUser(id, index) {
      if (
        !this.checkValidate(
          this.users.data[index].name,
          this.users.data[index].email,
          "12345678"
        )
      ) {
        return;
      }
      let data = {
        name: this.users.data[index].name,
        email: this.users.data[index].email,
        role_id: this.users.data[index].role_id
      };
      axios
        .put("useradmins/" + id, data)
        .then(res => {
          this.getUsers("/useradmins");
          this.selectedUser = false;
          this.isActive = "";
        })
        .catch(err => {
          this.errors.push(message.errSaveUserAdmin);
        });
    },
    searchUser() {
      this.isLoading = true;
      this.errors = [];
      this.emptySearch = "";
      axios
        .get(
          "userAdmins/search?name=" +
            this.searchData.name +
            "&email=" +
            this.searchData.email +
            "&role_id=" +
            this.searchData.role_id
        )
        .then(res => {
          this.users = res.data.data;
          this.paginate.sizeUser = this.users.data.length;
          this.paginate.total = this.users.total;
          if(this.users.data.length ==0)
          {
            this.emptySearch = '検索結果がありません。';
          }
          this.isLoading = false;
        })
        .catch(err => {
          this.errors.push(message.processErr);
          this.isLoading = false;
        });
    },
    prev_page() {
      this.errors = [];
      this.getUsers(this.users.prev_page_url);
    },
    next_page() {
      this.errors = [];
      this.getUsers(this.users.next_page_url);
    },
      setIndexPage(index)
    {
      if(this.users.current_page == 1)
      {
        return index+1;
      }else {
        return (this.users.current_page-1)*this.users.per_page + index +1;
      }
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

p {
  font-size: 1vw;
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
#main-body {
  width: 100%;
  clear: both;
  float: none;
  margin-top: 30px;
  padding: 0px 3%;
}

.btnDelete {
  background-color: #fbe5d6;
}

.btn {
  background-color: #e2f0d9;
}

#block-search {
  border: 1px solid #5b9bd5;
  padding: 1%;
  height: 90px;
  width: 100%;
  margin: 1%;
  margin-left: 0;
  background: #f2f2f2;
}

#block-search input,
#block-search select {
  border: 1px solid #5b9bd5;
  margin-top: 2%;
}

#search-label label {
  margin-top: 3%;
}

#block-search .search-item {
  width: 50%;
  float: left;
}
#block-search .search-label {
  width: 8%;
  float: left;
}
#block-search .search-input {
  width: 17%;
  float: left;
}

#block-search .search-input button,
select {
  width: 80%;
}

#block-search .search-input button {
  width: 100%;
}

#block-search #search-info {
  width: 46%;
  float: right;
  text-align: center;
}

#block-search #search-info button {
  float: left;
  float: left;
  padding: 0 3%;
  margin-right: 3%;
}

#block-search button {
  background-color: #fff2cc;
  border: 1px solid #5b9bd5;
  border-radius: none;
}

#confirm {
  font-size: 120%;
  top: 40%;
  left: 40%;
  width: 500px;
  position: fixed;
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
  margin-left: 4%;
}
#confirm #body #confirm-delete {
  background-color: #deebf7;
}
#confirm #body #cancel-delete {
  background-color: #fff2cc;
}

.block-user {
  width: 100%;
  margin: 1%;
  margin-top: -1%;
  border: 1px solid #5b9bd5;
  height: 50px;
  margin-left: 0;
  text-align: center;
}

.block-infor {
  width: 100%;
  margin: 1%;
  margin-top: 2%;
  border: 1px solid #5b9bd5;
  height: 50px;
  margin-left: 0;
  text-align: center;
}

.index {
  background-color: #f2f2f2;
  width: 5%;
  float: left;
  height: 50px;
  border: 1px solid #5b9bd5;
}

.index p {
  height: 100%;
}

.user-name,
.user-email,
.user-password,
.user-action {
  background-color: #f2f2f2;
  width: 20%;
  float: left;
  height: 50px;
  border: 1px solid #5b9bd5;
  border-left: none;
  border-top: none;
}

.user-action {
  padding-top: 1%;
}
.user-role {
  background-color: #f2f2f2;
  width: 15%;
  float: left;
  height: 50px;
  border: 1px solid #5b9bd5;
  border-left: none;
  border-top: none;
}

.block-infor .index,
.block-infor .user-name,
.block-infor .user-role,
.block-infor .user-email,
.block-infor .user-password,
.block-infor .user-action {
  background-color: #d0cece;
}

.block-infor .index,
.block-infor .user-name,
.block-infor .user-role,
.block-infor .user-email,
.block-infor .user-password{
  padding-top: 1%;
}

.index p{
  padding-top: 13%;
}

.block-user input,
select {
  text-align-last: center;
  text-align: center;
  width: 100%;
  height: 100%;
}

.block-user #btnSave {
  background-color: #e2f0d9;
  padding: 2% 7%;
  margin-right: 0%;
}
.block-user #btnCancel {
  background-color: #fff2cc;
  padding: 2% 12%;
  margin-left: 6%;
}

.active select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  text-align-last: center;
}

.active {
  background-color: #ebebe4;
}
.notActive {
  background-color: #ffffff;
}

div #active {
  background-color: #ebebe4;
}

div #notActive {
  background-color: #ffffff;
}

.block-user button {
  padding: 1.5% 14%;
  margin-left: 2%;
  margin-bottom: 0%;
  margin-top: -3%;
}
#btnEdit {
  background-color: #deebf7;
}
#btnDelete {
  background-color: #fbe5d6;
}
.block-register {
  width: 100%;
  margin: 1%;
  border: 1px solid #5b9bd5;
  text-align: center;
  margin-top: -1%;
  margin-left: 0;
}

.block-register input {
  text-align: center;
  width: 100%;
  height: 100%;
}

.block-register .user-action {
  background-color: #ffffff;
}
.block-register .index {
  background-color: white;
}
.block-register button {
  width: 80%;
  margin-top: -1%;
  margin-left: 2%;
  height: 80%;
  background-color: #e2f0d9;
}
.errors {
  color: #ec4134;
  text-align: left;
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

.alert-danger {
  padding-top: 0.3%;
  padding-bottom: 0.3%;
  font-size: 100%;
}

#emptySearch {
  text-align: center;
  margin-top: 5%;
  font-size: 2em;
  font-weight: bold;
}
</style>