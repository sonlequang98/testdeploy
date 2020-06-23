<template>
  <div>
    <div id="header">
      <admin-header></admin-header>
    </div>
    <div id="body">
      <div id="menu">
        <admin-side-bar></admin-side-bar>
      </div>
      <div id="main">
        <div id="main-header">
          <h4>Manage release number</h4>
          <button id="create-new" @click="create">Create release number</button>
        </div>
        <div class="error" v-if="errors">
          <p class="alert alert-danger" v-for="error in errors">{{ error }}</p>
        </div>
        <div
          id="block_item"
          :key="release_number.id"
          v-for="(release_number, index) in release_numbers.data"
          v-if="release_number.image"
        >
          <div id="index">{{setIndexPage(index)}}</div>
          <div id="name">{{release_number.release_number_name}}</div>
          <div id="image">
            <img :src="release_number.image.upload_path" alt />
          </div>
          <div id="action">
            <button id="btnDelete" @click="deleteReleaseNumber(release_number.id,index)">Delete</button>
            <br />
            <button id="btnEdit" @click="edit(release_number.id)" type="button">Edit</button>
          </div>
        </div>

        <div id="pagination">
          <button @click="prev_page" :disabled="!release_numbers.prev_page_url">Prev</button>
          <div id="current_page">{{release_numbers.current_page}}</div>
          <button @click="next_page" :disabled="!release_numbers.next_page_url">Next</button>
        </div>
      </div>
    </div>

    <div id="delete" v-if="enablePopupDelete">
      <div id="confirm">
        <div id="header">
          <p>Delete release number</p>
        </div>
        <div id="body">
          <p>Are you sure delete this release number?</p>
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
import { finished } from "stream";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import { message } from "../../message";

export default {
  name: "ReleaseNumber",
  beforeCreate() {
    axios.get("/users/getCurrentRole").then(res => {
      if (res.data == this.$store.state.role.contributor) {
        this.$router.push({
          name: "home"
        });
      }
    });
  },
  created() {
    this.fetchData(this.url);
  },
  mounted() {},
  components: {
    AdminHeader,
    AdminSideBar,
    Loading
  },
  data() {
    return {
      release_numbers: [],
      url: "/release_numbers",
      deleteItem: {
        id: "",
        index: ""
      },
      isLoading: false,
      fullPage: false,
      enablePopupDelete: false,
      errors: []
    };
  },
  methods: {
    fetchData(url) {
      this.isLoading = true;
      axios
        .get(url)
        .then(res => {
          this.release_numbers = res.data.data;
          this.isLoading = false;
        })
        .catch(err => {
          this.errors.push(message.errLoadReleaseNumber);
          this.isLoading = false;
        });
    },
    prev_page() {
      this.url = this.release_numbers.prev_page_url;
      this.fetchData(this.url);
    },
    next_page() {
      this.url = this.release_numbers.next_page_url;
      this.fetchData(this.url);
    },
    deleteReleaseNumber(id, index) {
      this.enablePopupDelete = true;
      this.deleteItem.id = id;
      this.deleteItem.index = index;
      document.documentElement.style.overflow = "hidden";
    },
    confirmDelete() {
      axios
        .delete("/release_numbers/" + this.deleteItem.id + "")
        .then(res => {
          this.release_numbers.data.splice(this.deleteItem.index, 1);
          this.deleteItem.index = null;
          this.deleteItem.id = null;
          this.enablePopupDelete = false;
          document.documentElement.style.overflow = "auto";
        })
        .catch(err => {
          this.errors.push(message.processErr);
          document.documentElement.style.overflow = "auto";
        });
    },
    cancelDelete() {
      document.documentElement.style.overflow = "auto";
      this.enablePopupDelete = false;
    },
    edit(release_number_id) {
      this.$router.push({
        name: "ReleaseNumberEdit",
        params: { id: release_number_id }
      });
    },
    create() {
      this.$router.push({ name: "releaseNumberCreate" });
    },
    setIndexPage(index) {
      if (this.release_numbers.current_page == 1) {
        return index + 1;
      } else {
        return (
          (this.release_numbers.current_page - 1) *
            this.release_numbers.per_page +
          index +
          1
        );
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
  padding-top: 35px;
  width: 85%;
  float: right;
}
#main-header {
  margin-left: 20px;
}

#div {
  margin-left: 35px;
  margin-right: 100px;
}
h4 {
  font-weight: bold;
  margin-bottom: 30px;
  font-size: 20px;
  color: orange;
}
#create-new {
  background-color: #5b9bd5;
  width: 12%;
  color: white;
  padding: 4px;
  text-align: center;
}
#create-new a {
  color: white;
  font-size: 16px;
}
#block_item {
  position: relative;
  padding: 4px 0px;
  height: 122px;
  clear: both;
  width: 95%;
  margin: 30px 20px;
  border: 1px solid #5b9bd5;
  background-color: #f2f2f2;
  margin-right: 0px;
}

#block_item #index {
  text-align: center;
  padding-top: 50px;
  width: 4%;
  height: 134px;
  float: left;
  margin-top: -10px;
  border: 1px solid #5b9bd5;
  background-color: #deebf7;
  margin-left: -1px;
}
#block_item #name {
  text-align: center;
  padding-top: 50px;
  width: 39%;
  height: 112px;
  float: left;
}
#block_item #image {
  text-align: center;
  float: left;
  width: 42%;
  height: 120px;
  margin: 0 auto;
}
#block_item img {
  height: 95%;
  width: 100%;
  object-fit: cover;
}

#block_item #action {
  width: 13%;
  float: right;
}
#block_item #action button {
  clear: both;
  height: 35px;
  width: 75%;
  margin-top: 10px;
  margin-left: 22px;
}
#block_item #btnDelete {
  background-color: #fbe5d6;
}
#block_item #btnEdit {
  background-color: #e2f0d9;
}

#pagination {
  margin: 20px;
  margin-bottom: 50px;
}
#pagination button {
  background-color: #d0cece;
  float: left;
  width: 40px;
  height: 37px;
  margin-bottom: 60px;
}
#pagination #current_page {
  padding: 0 8px;
  background-color: #deebf7;
  float: left;
  height: 26px;
  height: 37px;
  width: 33px;
  padding-top: 8px;
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
  color: #a94442;
  background-color: #f2dede;
  border-color: #ebccd1;
  padding-top: 0.4%;
  padding-bottom: 0.4%;
}
</style>
