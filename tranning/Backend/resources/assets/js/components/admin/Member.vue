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
        <h4>Manage member</h4>
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
              <input type="text" v-model="searchData.name" placeholder="Enter name" />
              <br />
              <input type="text" v-model="searchData.email" placeholder="Enter email" />
            </div>
            <div class="search-label">
              <label for>Status</label>
            </div>
            <div class="search-input">
              <select name id v-model="searchData.active">
                <option value=" ">All</option>
                <option value="1">Active</option>
                <option value="0">Not active</option>
                <br />
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
        <div class="error">
          <p class="alert alert-danger" v-for="error in errors">{{error}}</p>
        </div>

        <div class="block-info-member" id="div-info-member">
          <div class="block-index">#</div>
          <div class="block-body">
            <div class="body-above">
              <div class="info-name">
                <p>User name</p>
              </div>
              <div class="info-email">
                <p>Email</p>
              </div>
              <div class="info-phone">
                <p>Number phone</p>
              </div>
            </div>

            <div class="body-bellow">
              <div class="info-dof">
                <p>Date of birth</p>
              </div>
              <div class="info-sex">
                <p>Sex</p>
              </div>
              <div class="info-address">
                <p>Address</p>
              </div>
              <div class="info-active">
                <p>Status</p>
              </div>
            </div>
          </div>
          <div class="block-action">
            <p>Create/Edit/Delete</p>
          </div>
        </div>
        <div v-if="successful">
          <p class="alert alert-success">{{successful}}</p>
        </div>

        <div class="block-register-member" v-if="(!selectedMember)">
          <form @submit.prevent="regisUser">
            <div class="block-index">#</div>
            <div class="block-body">
              <div class="body-above">
                <div class="info-name">
                  <input type="text" v-model="userRegister.name" placeholder="Enter user name" />
                </div>
                <div class="info-email">
                  <input v-model="userRegister.email" type="text" placeholder="Enter email" />
                </div>
                <div class="info-phone">
                  <input v-model="userRegister.phone" type="text" placeholder="Enter number phone" />
                </div>
              </div>

              <div class="body-bellow">
                <div class="info-dof">
                  <datepicker
                    v-model="dob"
                    @closed="selectedDP"
                    :format="'yyyy/MM/dd'"
                    :input-class="{datepicker}"
                  ></datepicker>
                </div>
                <div class="info-sex">
                  <label class="info-sex-label" for>M</label>
                  <input
                    v-model="userRegister.gender"
                    type="radio"
                    class="input-sex"
                    value="M"
                    name="sex"
                  />
                  <label class="info-sex-label" for>FM</label>
                  <input
                    v-model="userRegister.gender"
                    type="radio"
                    class="input-sex"
                    value="FM"
                    name="sex"
                  />
                </div>
                <div class="info-address">
                  <input v-model="userRegister.address" type="text" placeholder="Enter address" />
                </div>
                <div class="info-active">
                  <select name id v-model="userRegister.active">
                    <option value="1">Active</option>
                    <option value="0">Not active</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="block-action">
              <button id="btnRegister" type="submit">Save</button>
            </div>
          </form>
        </div>

        <div class="block-register-member" :key="user.id" v-for="(user, index) in users.data">
          <div class="block-index" :class="[isActive!=user.id?'active':'notActive']">
            <p :class="[isActive===user.id?'active':'notActive']">{{setIndexPage(index)}}</p>
          </div>
          <div class="block-body">
            <div class="body-above">
              <div class="info-name">
                <input
                  v-model="user.name"
                  :disabled="(isActive!=user.id)"
                  type="text"
                  placeholder="Enter user name"
                />
              </div>
              <div class="info-email">
                <input
                  v-model="user.email"
                  :disabled="(isActive!=user.id)"
                  type="text"
                  placeholder="Enter email"
                />
              </div>
              <div class="info-phone">
                <input
                  v-model="user.phone"
                  :disabled="(isActive!=user.id)"
                  type="text"
                  placeholder="Enter number phone"
                />
              </div>
            </div>

            <div class="body-bellow">
              <div class="info-dof" :class="[isActive===user.id?'active':'notActive']">
                <label
                  v-if="(isActive!=user.id)"
                  class="current-dob"
                  for
                >{{formatDate(user.date_of_birth)}}</label>
                <datepicker
                  v-model="user.date_of_birth"
                  :format="'yyyy/MM/dd'"
                  :input-class="{datepicker}"
                  v-if="(isActive===user.id)"
                ></datepicker>
              </div>
              <div class="info-sex">
                <label
                  class="current-sex"
                  :class="[isActive===user.id?'active':'notActive']"
                  for
                  v-if="(isActive!=user.id)"
                >{{user.gender}}</label>
                <label class="info-sex-label" for v-if="(isActive===user.id)">M</label>
                <input
                  type="radio"
                  v-if="(isActive===user.id)"
                  v-model="user.gender"
                  class="input-sex"
                  value="M"
                  name="sex"
                />
                <label class="info-sex-label" for v-if="(isActive===user.id)">FM</label>
                <input
                  type="radio"
                  v-if="(isActive===user.id)"
                  v-model="user.gender"
                  class="input-sex"
                  value="FM"
                  name="sex"
                />
              </div>
              <div class="info-address">
                <input
                  v-model="user.address"
                  :disabled="(isActive!=user.id)"
                  type="text"
                  placeholder="Enter address"
                />
              </div>
              <div class="info-active">
                <select
                  :class="[isActive===user.id?'active':'notActive']"
                  name
                  id
                  v-model="user.active"
                  :disabled="(isActive!=user.id)"
                >
                  <option value="1">Active</option>
                  <option value="0">Not active</option>
                </select>
              </div>
            </div>
          </div>
          <div class="block-action" :id="[isActive===user.id?'active':'notActive']">
            <button class="btnSave" @click="updateUser(user)" v-if="(isActive===user.id)">Save</button>
            <button class="btnCalcel" @click="cancelEdit" v-if="(isActive===user.id)">Cancel</button>
            <button class="btnEdit" @click="editUser(user,index)" v-if="(isActive!=user.id)">Edit</button>
            <button
              class="btnDelete"
              @click="deleteUser(user.id,index)"
              v-if="(isActive!=user.id)"
            >Delete</button>
          </div>
        </div>
        <div v-if="emptySearch" id="emptySearch">
          <p>{{emptySearch}}</p>
        </div>
      </div>
    </div>
    <div id="delete" v-if="enable">
      <div id="confirm">
        <div id="header">
          <p>Delete member</p>
        </div>
        <div id="body">
          <p>Are you sure delete this member?</p>
          <button id="confirm-delete" @click="confirmDelete">Yes</button>
          <button id="cancel-delete" @click="cancelDelete">No</button>
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
import Datepicker from "vuejs-datepicker";
import { message } from "../../message";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

let today = new Date();
export default {
  name: "member",
  components: {
    AdminHeader,
    AdminSideBar,
    Datepicker,
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
    this.getUsers(this.url);
  },
  mounted() {
    this.dob = today;
  },
  data() {
    return {
      datepicker: {
        width: "100%"
      },
      isLoading: false,
      fullPage: false,
      enable: false,
      dob: "",
      successful: "",
      selectedMember: false,
      url: "/members",
      users: [],
      isActive: "",
      paginate: {
        sizeUser: "",
        total: ""
      },
      errors: [],
      deleteItem: {
        id: "",
        index: ""
      },
      userRegister: {
        name: "",
        email: "",
        phone: "",
        date_of_birth: "",
        gender: "M",
        address: "",
        active: "1"
      },
      searchData: {
        name: "",
        email: "",
        active: " "
      },
      tempMember: {
        name: "",
        email: "",
        phone: "",
        date_of_birth: "",
        gender: "",
        address: "",
        active: ""
      },
      emptySearch: "",
      indexUserEdit: "",
      reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
    };
  },

  methods: {
    getUsers(url) {
      this.isLoading = true;
      axios
        .get(url)
        .then(res => {
          this.users = res.data.data;
          this.paginate.sizeUser = this.users.to;
          this.paginate.total = this.users.total;
          this.isLoading = false;
        })
        .catch(err => {
          this.errors.push(message.errLoadMember);
          this.isLoading = false;
        });
    },
    formatDate(date) {
      let dateUpdate = new Date(date);
      return (
        dateUpdate.getFullYear() +
        "/" +
        (dateUpdate.getMonth() + 1) +
        "/" +
        dateUpdate.getDate() +
        ""
      );
    },
    prev_page() {
      this.errors = [];
      this.getUsers(this.users.prev_page_url);
    },
    next_page() {
      this.errors = [];
      this.getUsers(this.users.next_page_url);
    },
    deleteUser(id, index) {
      this.errors = [];
      this.enable = true;
      this.deleteItem.id = id;
      this.deleteItem.index = index;
    },
    confirmDelete() {
      axios
        .delete("/members/" + this.deleteItem.id + "")
        .then(res => {
          this.enable = false;
          this.users.data.splice(this.deleteItem.index, 1);
          this.paginate.sizeUser--;
          this.paginate.total--;
          this.deleteItem.index = null;
          this.deleteItem.id = null;
        })
        .catch(err => {
          this.enable = false;
          this.errors.push(message.processErr);
        });
    },
    cancelDelete() {
      this.enable = false;
      this.deleteItem.index = null;
      this.deleteItem.id = null;
    },
    editUser(user, index) {
      this.tempMember.name = user.name;
      this.tempMember.email = user.email;
      this.tempMember.phone = user.phone;
      this.tempMember.date_of_birth = user.date_of_birth;
      this.tempMember.gender = user.gender;
      this.tempMember.address = user.address;
      this.tempMember.active = user.active;
      this.errors = [];
      this.indexUserEdit = index;
      this.isActive = user.id;
      this.selectedMember = true;
    },
    cancelEdit() {
      this.users.data[this.indexUserEdit].name = this.tempMember.name;
      this.users.data[this.indexUserEdit].email = this.tempMember.email;
      this.users.data[this.indexUserEdit].phone = this.tempMember.phone;
      this.users.data[
        this.indexUserEdit
      ].date_of_birth = this.tempMember.date_of_birth;
      this.users.data[this.indexUserEdit].gender = this.tempMember.gender;
      this.users.data[this.indexUserEdit].address = this.tempMember.address;
      this.users.data[this.indexUserEdit].active = this.tempMember.active;
      this.errors = [];
      this.isActive = "";
      this.selectedMember = false;
    },
    updateUser(user) {
      let memberDob = new Date(user.date_of_birth);
      user.date_of_birth =
        memberDob.getDate() +
        "-" +
        (memberDob.getMonth() + 1) +
        "-" +
        memberDob.getFullYear();
      if (
        !this.checkValidate(user.name, user.email, user.phone, user.address)
      ) {
        return;
      }
      axios
        .put("/members/" + user.id, user)
        .then(res => {
          this.selectedMember = false;
          this.isActive = "";
        })
        .catch(err => {
          this.errors.push(message.errSaveMember);
        });
    },
    selectedDP() {
      let memberDob = new Date(this.dob);
      this.userRegister.date_of_birth =
        memberDob.getMonth() +
        1 +
        "-" +
        memberDob.getDate() +
        "-" +
        memberDob.getFullYear();
    },
    regisUser() {
      this.selectedDP();
      if (
        !this.checkValidate(
          this.userRegister.name,
          this.userRegister.email,
          this.userRegister.phone,
          this.userRegister.address
        )
      ) {
        return;
      }
      axios
        .post("/members", this.userRegister)
        .then(res => {
          this.addUserToUsers(res.data.data);
          this.refreshFormRegister();
          this.paginate.sizeUser++;
          this.paginate.total++;
          this.successful = "Delete success";
          let self = this;
          setTimeout(function() {
            self.successful = null;
          }, 4000);
        })
        .catch(err => {
          this.errors.push(message.errSaveMember);
        });
    },
    refreshFormRegister() {
      this.userRegister.name = "";
      this.userRegister.email = "";
      this.userRegister.phone = "";
      this.userRegister.address = "";
      this.userRegister.active = "1";
      this.userRegister.gender = "M";
    },
    addUserToUsers(user) {
      this.users.data.unshift(user);
    },
    checkValidate(name, email, phone, address) {
      this.errors = [];

      if (name.length === 0) {
        this.errors.push(message.emptyNameUser);
        return false;
      }
      if (name.length > 100) {
        this.errors.push(message.overNameUserLength);
        return false;
      }
      if (email.length === 0) {
        this.errors.push(message.emptyEmail);
        return false;
      }
      if (!this.reg.test(email)) {
        this.errors.push(message.emptyEmail);
        return false;
      }
      if (phone) {
        if (phone.length > 12) {
          this.errors.push(message.overPhoneLength);
          return false;
        }
      }
      if (address) {
        if (address.length > 100) {
          this.errors.push(message.overAddressLength);
          return false;
        }
      }
      return true;
    },
    searchUser() {
      this.isLoading = true;
      this.errors = [];
      this.emptySearch = "";
      axios
        .get(
          "members/search?name=" +
            this.searchData.name +
            "&email=" +
            this.searchData.email +
            "&active=" +
            this.searchData.active
        )
        .then(res => {
          this.users = res.data.data;
          this.paginate.sizeUser = res.data.data.data.length;
          this.paginate.total = res.data.data.total;
          if (this.users.data.length == 0) {
            this.emptySearch = "検索結果がありません。";
          }
          this.isLoading = false;
        })
        .catch(err => {
          this.errors.push(message.processErr);
          this.isLoading = false;
        });
    },
    setIndexPage(index) {
      if (this.users.current_page == 1) {
        return index + 1;
      } else {
        return (this.users.current_page - 1) * this.users.per_page + index + 1;
      }
    }
  }
};
</script>
<style scoped>
a:hover {
  text-decoration: none;
}

h4 {
  margin-left: 2%;
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
  clear: both;
  float: none;
  margin-top: 30px;
  padding: 0px 3%;
  font-size: 0.8vw;
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
  background: #f2f2f2;
}

#block-search input,
select,
button {
  border: 1px solid #5b9bd5;
}

#block-search input {
  margin-top: 2%;
}

select {
  text-align-last: center;
}

#block-search .search-label {
  width: 8%;
  float: left;
}

#block-search .search-label label {
  margin-top: 2%;
}

#block-search .search-input {
  width: 15%;
  float: left;
}

#block-search .search-input button,
select {
  width: 80%;
}

#block-search button {
  background-color: #fff2cc;
  border: 1px solid #5b9bd5;
}

#block-search #search-info {
  width: 50%;
  float: right;
  text-align: center;
}

#block-search #search-info button {
  float: left;
  float: left;
  padding: 0 3%;
  margin-right: 3%;
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
  border-top: 1px solid #5b9bd5;
  padding: 30px;
  background-color: #f2f2f2;
  margin-top: -10px;
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

/* format div info member */
.block-info-member {
  width: 100%;
  text-align: center;
  border: 1px solid #5b9bd5;
  height: 48px;
  background-color: #f2f2f2;
  margin-left: 1%;
}

.block-index {
  width: 5%;
  background-color: #f2f2f2;
  float: left;
  border: 1px solid #5b9bd5;
  border-bottom: 0px;
  border-left: none;
  height: 100%;
  border-right: none;
}

.block-body {
  background-color: #f2f2f2;
  border-bottom: 0px;
  width: 80%;
  float: left;
}

.block-action {
  height: 100%;
  border-bottom: 0px;
  background-color: #f2f2f2;
  width: 15%;
  border: 1px solid #5b9bd5;
  border-left: none;
  border-right: none;
  float: right;
}

.body-above,
.body-bellow {
  background-color: #f2f2f2;
  width: 100%;
  border-bottom: 0px;
  float: none;
  border: 1px solid #5b9bd5;
  clear: both;
}
.error {
  color: #ea4335;
  margin-left: 1%;
}
.info-name {
  border: 1px solid #5b9bd5;
  width: 40%;
  border-top: none;
  border-bottom: 0px;
  background-color: #f2f2f2;
  float: left;
}

.info-email {
  background-color: #f2f2f2;
  width: 35%;
  border: 1px solid #5b9bd5;
  border-bottom: 0px;
  border-left: none;
  border-top: none;
  float: left;
}

.info-phone {
  background-color: #f2f2f2;
  width: 25%;
  border: 1px solid #5b9bd5;
  border-left: none;
  border-bottom: 0px;
  border-top: none;
  float: right;
}

.info-dof {
  width: 30%;
  background-color: #f2f2f2;
  float: left;
  border: 1px solid #5b9bd5;
  border-bottom: 0px;
  height: 29px;
  border-top: none;
}

.info-sex {
  width: 10%;
  background-color: #f2f2f2;
  float: left;
  border: 1px solid #5b9bd5;
  border-bottom: 0px;
  border-left: none;
}

.info-address {
  background-color: #f2f2f2;
  width: 35%;
  border: 1px solid #5b9bd5;
  border-left: none;
  float: left;
  border-bottom: 0px;
  border-top: none;
}

.info-active {
  width: 25%;
  background-color: #f2f2f2;
  float: right;
  border: 1px solid #5b9bd5;
  border-bottom: 0px;
  border-top: none;
  border-left: none;
  border-right: none;
}

.block-info-member .block-action {
  border-bottom: none;
}

#div-info-member .block-index,
#div-info-member .info-name,
#div-info-member .info-email,
#div-info-member .info-phone,
#div-info-member .info-dof,
#div-info-member .info-sex,
#div-info-member .info-address,
#div-info-member .info-active,
#div-info-member .block-action {
  background-color: #d0cece;
}

#div-info-member .info-dof,
#div-info-member .info-sex,
#div-info-member .info-address,
#div-info-member .info-active {
  height: 29px;
  border-top: none;
  border-right: 1px solid #5b9bd5;
}

.block-register-member {
  width: 100%;
  text-align: center;
  border: 1px solid #5b9bd5;
  border-top: 0px;
  height: 60px;
  background-color: #ffffff;
  margin-left: 1%;
}

.block-register-member button {
  border: 1px solid #5b9bd5;
}

.block-register-member input {
  text-align: center;
  width: 100%;
  border: none;
  height: 28px;
}

.block-register-member .block-index {
  background-color: #ffffff;
  height: 60px;
  border-bottom: 1px solid #5b9bd5;
  border-right: none;
}

.block-register-member .block-index p {
  height: 58px;
}
.block-register-member .info-sex {
  background-color: #ffffff;
  object-fit: cover;
  height: 29px;
  border-bottom: 1px solid #5b9bd5;
  border-bottom: none;
  border-top: none;
}

.block-register-member .info-dof {
  border-bottom: 1px solid #5b9bd5;
  border-bottom: none;
}

.block-register-member .info-sex input {
  height: auto;
}

.block-register-member label {
  background-color: #ffffff;
}
.block-register-member .input-sex {
  float: left;
  width: 30%;
}

.block-register-member select {
  width: 100%;
  height: 28px;
  border-bottom: none;
  border-left: none;
  border-top: none;
}

.block-register-member .block-action {
  background-color: #ffffff;
  height: 60px;
}

.block-register-member .block-action .btnSave {
  background-color: #e2f0d9;
  margin-top: 1%;
  padding: 0% 30%;
  float: none;
  clear: both;
}

.block-register-member .block-action .btnCalcel {
  background-color: #f2f2f2;
  padding: 0 20%;
  float: none;
  clear: both;
  margin-top: 1%;
}
.block-register-member .block-action .btnEdit {
  background-color: #deebf7;
  margin-top: 5%;
  padding: 2% 10%;
  float: left;
  margin-left: 12%;
}
.block-register-member .block-action .btnDelete {
  background-color: #fbe5d6;
  margin-top: 5%;
  padding: 2% 10%;
  float: right;
  margin-right: 15%;
}

.block-register-member .info-sex .current-sex {
  background-color: #ebebe4;
  width: 100%;
  float: left;
  height: 29px;
}
.block-register-member .info-sex .info-sex-label {
  float: left;
}
.block-register-member .current-dob {
  background-color: #ebebe4;
  height: 24px;
  width: 100%;
  text-align: left;
  text-indent: 3%;
}

.active {
  background-color: #ffffff;
}
.notActive {
  background-color: #ebebe4;
}
#btnRegister {
  width: 80%;
  background-color: #e2f0d9;
  margin-top: 6%;
  padding: 5% 15%;
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

div #active {
  background-color: #ffffff;
}

div #notActive {
  background-color: #ebebe4;
}

.alert-danger {
  padding-top: 0.4%;
  padding-bottom: 0.4%;
  margin-right: -1%;
}

#emptySearch {
  text-align: center;
  margin-top: 5%;
  font-size: 2em;
  font-weight: bold;
}

.alert-success {
  margin-top: 1%;
  padding-right: 0%;
  width: 100%;
  margin-left: 1%;
}
</style>
<style module>
.datepicker {
  width: 100%;
  height: 27px;
  text-indent: 10px;
  border: none;
}
</style>