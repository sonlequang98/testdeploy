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
        <h4>Manage image</h4>
        <br />
        <button id="btnAddNew" @click="addNewImage">Create new image</button>
        <div id="block-search">
          <form @submit.prevent="searchImage">
            <input v-model="searchQuery" type="text" />
            <button type="submit" id="btnSearch">Search</button>
          </form>
        </div>
      </div>
      <div id="main-body">
        <div class="block-image" :key="image.id" v-for="(image,index) in images.data">
          <div class="index">{{setIndexPage(index)}}</div>
          <div class="image" @click="zoomImage(image)">
            <img :src="image.upload_path" alt />
          </div>
          <div class="info">
            <p>Name: {{image.name}}</p>
            <p>Created date: {{formatDate(image.created_at)}}</p>
          </div>
          <div class="action">
            <button @click="deleteImage(image.id,index)" id="btnDelete">Delete</button>
            <br />
            <span id="success-copy" v-if="successCopy&&(copyIndex===index)">Copied</span>
            <button
              v-clipboard="() =>'http://adminsite.local/' + image.upload_path"
              @click="copyImage(index)"
              class="btnCopy"
            >Copy</button>
          </div>
        </div>
        <div id="pagination">
          <button @click="prevPage()" :disabled="!images.prev_page_url">Prev</button>
          <div id="current_page">{{images.current_page}}</div>
          <button @click="nextPage()" :disabled="!images.next_page_url">Next</button>
        </div>
      </div>
    </div>
    <div id="delete" v-if="deleteEnable">
      <div id="confirm">
        <div id="header">
          <p>発売号の削除</p>
        </div>
        <div id="body">
          <p>Are you sure delete this image?</p>
          <button id="confirm-delete" @click="confirmDelete">Yes</button>
          <button id="cancel-delete" @click="cancelDelete">No</button>
        </div>
      </div>
    </div>
    <transition name="fade">
      <div id="detail" v-if="showImageDetail">
        <div v-if="showImageDetail" id="image-detail">
          <div id="detail-header">
            Name：{{imageDetail.name}}
            <button @click="closeDetail" id="btnCloseDetail">Close</button>
            <button
              id="btnCopyDetail"
              v-clipboard="() =>'http://adminsite.local/' + imageDetail.upload_path"
            >Copy</button>
          </div>
          <div id="detail-body">
            <img :src="imageDetail.upload_path" alt />
          </div>
        </div>
      </div>
    </transition>
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
  name: "imageManage",
  components: {
    AdminHeader,
    AdminSideBar,
    Loading
  },
  created() {
    this.getImage("/images");
  },
  data() {
    return {
      images: [],
      deleteEnable: false,
      deleteItem: {
        id: "",
        index: ""
      },
      isLoading: false,
      fullPage: false,
      searchQuery: "",
      imageDetail: {
        name: "",
        upload_path: ""
      },
      showImageDetail: false,
      successCopy: false,
      copyIndex: ""
    };
  },
  methods: {
    getImage(url) {
      this.isLoading = true;
      axios
        .get(url)
        .then(res => {
          this.images = res.data.data;
          this.isLoading = false;
        })
        .catch(err => {
          console.log(err);
          this.isLoading = false;
        });
    },
    prevPage() {
      this.getImage(this.images.prev_page_url);
    },
    nextPage() {
      this.getImage(this.images.next_page_url);
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
    deleteImage(id, index) {
      this.deleteEnable = true;
      this.deleteItem.id = id;
      this.deleteItem.index = index;
      document.documentElement.style.overflow = "hidden";
    },
    confirmDelete() {
      axios
        .delete("/images/" + this.deleteItem.id)
        .then(res => {
          this.images.data.splice(this.deleteItem.index, 1);
          this.deleteEnable = false;
          document.documentElement.style.overflow = "auto";
        })
        .catch(err => {
          document.documentElement.style.overflow = "auto";
          console.log(err);
        });
    },
    cancelDelete() {
      this.deleteEnable = false;
      document.documentElement.style.overflow = "auto";
    },
    searchImage() {
      this.isLoading = true;
      axios
        .get("/images/search?name=" + this.searchQuery)
        .then(res => {
          this.images = res.data.data;
          this.isLoading = false;
        })
        .catch(err => {
          console.log(err);
          this.isLoading = false;
        });
    },
    zoomImage(image) {
      this.imageDetail = image;
      this.showImageDetail = true;
      document.documentElement.style.overflow = "hidden";
    },
    copyImage(index) {
      this.successCopy = true;
      this.copyIndex = index;
      let self = this;
      setTimeout(function() {
        self.successCopy = false;
      }, 3000);
    },
    addNewImage() {
      this.$router.push({
        name: "imageCreate"
      });
    },
    closeDetail() {
      this.showImageDetail = !this.showImageDetail;
      document.documentElement.style.overflow = "auto";
    },
    setIndexPage(index) {
      if (this.images.current_page == 1) {
        return index + 1;
      } else {
        return (
          (this.images.current_page - 1) * this.images.per_page + index + 1
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
  margin-left: 3%;
}

#main-body {
  width: 100%;
  padding: 3%;
  padding-top: 4%;
  padding-right: 1%;
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

#btnAddNew {
  width: 15%;
  font-size: 0.9vw;
  float: left;
  background-color: #5b9bd5;
  color: #fff;
  padding: 0.7% 0;
}

#block-search {
  width: 80%;
  float: right;
  font-size: 0.9vw;
  margin-right: 3%;
}
#block-search input {
  width: 82%;
  float: left;
  padding: 0.8% 0;
}
#block-search button {
  background-color: #fff2cc;
  width: 14%;
  margin-left: 1%;
  float: left;
  padding: 0.9% 0;
}

.block-image {
  text-align: center;
  width: 100%;
  border: 1px solid #5b9bd5;
  height: 122px;
  float: none;
  clear: both;
  margin-top: 1%;
  background-color: #f2f2f2;
}
.index {
  padding-top: 3%;
  width: 3%;
  float: left;
  height: 120px;
  border-right: 1px solid #5b9bd5;
  background-color: #deebf7;
}
.image {
  width: 37%;
  float: left;
  background-color: #f2f2f2;
  height: 120px;
}
.image img {
  width: 90%;
  padding-top: 1%;
  height: 95%;
  object-fit: cover;
}

.info {
  width: 40%;
  float: left;
  height: 120px;
  padding-top: 3%;
  background-color: #f2f2f2;
}

.action {
  padding-top: 2%;
  width: 20%;
  float: left;
  height: 120px;
  background-color: #f2f2f2;
}

#btnDelete {
  background-color: #fbe5d6;
  padding: 1% 5%;
  margin: 2%;
}

.btnCopy {
  background-color: #e2f0d9;
  padding: 1% 3%;
}

#pagination {
  margin-top: 3%;
}
#pagination button {
  background-color: #d0cece;
  float: left;
  border: 1px solid #5b9bd5;
  padding: 0.5%;
}
#pagination #current_page {
  background-color: #deebf7;
  float: left;
  padding: 0.6% 0.5%;
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

#image-detail {
  width: 70%;
  height: 70%;
  position: absolute;
  top: 5%;
  left: 16%;
}
#detail-header {
  background-color: #f2f2f2;
  height: 10%;
  border: 1px solid #5b9bd5;
  padding: 1% 1%;
}
#detail-header button {
  float: right;
}
#image-detail img {
  width: 100%;
  max-height: 80vh;
  object-fit: cover;
}
#btnCloseDetail {
  background-color: #fbe5d6;
}

#btnCopyDetail {
  background-color: #e2f0d9;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
#success-copy {
  background-color: #b7d6a3;
  position: absolute;
  z-index: 1;
  margin-left: 0%;
  padding: 0.7% 1%;
  padding-left: 0.5%;
  padding-right: 3%;
  border-radius: 13%;
  margin-top: -3%;
}

#detail {
  position: fixed;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  height: 100%;
  left: 0;
  top: 0;
  z-index: 1;
  width: 100%;
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
</style>