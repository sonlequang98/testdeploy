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
        <h4>Setting</h4>
      </div>
      <div id="main-body">
        <form @submit.prevent="updateSetting">
          <div class="form-content">
            <label class="title-label" for>Release number page</label>
            <input
              type="text"
              v-model="setting.release_number_page"
              @input="checkNumber(setting.release_number_page)"
            />
            <label for>/ Record</label>
          </div>
          <br />
          <div class="form-content">
            <label class="title-label" for>User admin page</label>
            <input
              type="text"
              v-model="setting.user_admin_page"
              @input="checkNumber(setting.user_admin_page)"
            />
            <label for>/ Record</label>
          </div>
          <br />
          <div class="form-content">
            <label class="title-label" for>Article page</label>
            <input type="text" v-model="setting.post_page" @input="checkNumber(setting.post_page)" />
            <label for>/ Record</label>
          </div>
          <br />
          <div class="form-content">
            <label class="title-label" for>Member page</label>
            <input
              type="text"
              v-model="setting.member_page"
              @input="checkNumber(setting.member_page)"
            />
            <label for>/ Record</label>
          </div>
          <br />
          <div class="form-content">
            <label class="title-label" for>Image page</label>
            <input type="text" v-model="setting.img_page" @input="checkNumber(setting.img_page)" />
            <label for>/ Record</label>
          </div>
          <br />
          <button id="btnSave" type="submit" :disabled="isDisableButton">Save</button>
        </form>
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
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  name: "setting",
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
    this.getSetting();
  },

  data() {
    return {
      setting: {},
      isDisableButton: false,
      isLoading: false,
      fullPage: false,
    };
  },
  methods: {
    getSetting() {
      this.isLoading = true;
      axios
        .get("/settings")
        .then(res => {
          this.setting = res.data.data[0];
          this.isLoading = false;
        })
        .catch(err => {
          console.log(err.errors);
          this.isLoading = false;
        });
        
    },
    checkNumber(number) {
      if (number < 1) {
        this.isDisableButton = true;
      } else {
        this.isDisableButton = false;
      }
    },
    updateSetting() {
      axios.put("/settings/" + this.setting.id, this.setting).catch(err => {
        console.log(err);
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
  width: 30%;
  padding: 3%;
  padding-top: 1%;
}

.form-content {
  display: inline;
  font-weight: normal;
}

.form-content label {
  font-weight: normal;
  font-size: 1vw;
}
.form-content .title-label {
  width: 60%;
  text-align: right;
  font-size: 1vw;
}
.form-content input {
  width: 12%;
}

#btnSave {
  background-color: #deebf7;
  padding: 1% 3%;
  margin-left: 10%;
  border: 1px solid #5b9bd5;
}
</style>