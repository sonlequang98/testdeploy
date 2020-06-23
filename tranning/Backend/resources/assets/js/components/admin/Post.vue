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
          <h4>Manage article</h4>
          <button id="create-new" @click="create">Create new article</button>
        </div>
        <div id="search">
          <form @submit.prevent="search">
            <div class="search-input">
              <label for>Release number</label>
              <select name id v-model="searchData.releaseNumber">
                <option value>すべて</option>
                <option
                  v-for="releaseNumber in releaseNumbers"
                  :value="releaseNumber.id"
                >{{releaseNumber.release_number_name}}</option>
              </select>
              <br />
              <label for>Status</label>
              <select name id v-model="searchData.status">
                <option value>All</option>
                <option value="public">Public</option>
                <option value="private">Private</option>
                <option value="draft">Draft</option>
              </select>
            </div>
            <div class="search-input">
              <label for>Category parent</label>
              <select name id v-model="searchData.catParent" @change="onChangeCategoryParent()">
                <option value>All</option>
                <option
                  :value="category.id"
                  v-for="category in categoryParents"
                >{{category.category_name}}</option>
              </select>
              <br />
              <label for>Key word</label>
              <input type="text" v-model="searchData.keyWord" placeholder="Enter content" name id />
            </div>
            <div class="search-input">
              <label for>Category child</label>
              <select name id v-model="searchData.catChild">
                <option value>All</option>
                <option
                  :value="category.id"
                  v-for="category in categoryChildrent"
                >{{category.category_name}}</option>
              </select>
              <br />
              <label for></label>
              <button type="submit">Search</button>
            </div>
            <div class="search-input">
              <label style="margin-left: 110px;">{{paginate.postSize}} / {{paginate.total}}</label>
            </div>
          </form>
        </div>
        <div class="description" id="div-description">
          <div id="description-index">
            <p>#</p>
          </div>
          <div id="description-body">
            <div id="body-above">
              <div id="title">
                <p>Title</p>
              </div>
              <div id="release_number">
                <p>Release number</p>
              </div>
              <div id="category">
                <p>Category</p>
              </div>
            </div>
            <div id="body-below">
              <div class="body-below-item">
                <p>Status</p>
              </div>
              <div class="body-below-item">
                <p>Created date</p>
              </div>
              <div class="body-below-item">
                <p>Updated date</p>
              </div>
              <div class="body-below-item">
                <p>Public date</p>
              </div>
              <div class="body-below-item">
                <p>Content</p>
              </div>
            </div>
          </div>
          <div id="description-action">
            <p>Edit/Delete</p>
          </div>
        </div>
        <div class="description" :key="post.id" id="table" v-for="(post,index) in posts.data">
          <div id="description-index">
            <p>{{setIndexPage(index)}}</p>
          </div>
          <div id="description-body">
            <div id="body-above">
              <div id="title">
                <p id="post-title">{{post.title}}</p>
              </div>
              <div id="release_number">
                <p>{{post.release_number_name}}</p>
              </div>
              <div id="category">
                <p>{{post.category_parent}}-{{post.category_child}}</p>
              </div>
            </div>
            <div id="body-below">
              <div class="body-below-item">
                <p>{{post.status}}</p>
              </div>
              <div class="body-below-item">
                <p>{{post.poster}}</p>
              </div>
              <div class="body-below-item">
                <p>{{post.updated_at}}</p>
              </div>
              <div class="body-below-item">
                <p>{{post.release_number_name}}</p>
              </div>
              <div class="body-below-item">
                <p>
                  <button id="btnShow" @click="showDetail(post,index+1)">Show</button>
                </p>
              </div>
            </div>
          </div>
          <div id="description-action">
            <button id="btnSave" @click="edit(post.id)">Edit</button>
            <button id="btnCancel" @click="deletePost(post.id,index)">Delete</button>
          </div>
        </div>
        <div v-if="emptySearch" id="emptySearch">
          <p>{{emptySearch}}</p>
        </div>
        <div id="pagination">
          <button @click="prev_page" :disabled="!posts.prev_page_url">Prev</button>
          <div id="current_page">{{posts.current_page}}</div>
          <button @click="next_page" :disabled="!posts.next_page_url">Next</button>
        </div>
      </div>
    </div>
    <div id="delete" v-if="delete_confirm_enable">
      <div id="confirm">
        <div id="header">
          <p>Delete artical</p>
        </div>
        <div id="body">
          <p>Are you sure delete this artical?</p>
          <button id="confirm-delete" @click="confirmDelete">Yes</button>
          <button id="cancel-delete" @click="cancelDelete">No</button>
        </div>
      </div>
    </div>
    <transition name="fade">
      <div id="detail" v-if="displayDetail">
        <div id="post-detail">
          <div id="post-detail-header">
            <div id="title">
              <p>#{{post_detail.index}}:{{post_detail.title}}</p>
            </div>
            <div id="button">
              <button @click="disableDetail">Close</button>
            </div>
          </div>
          <div id="post-detail-body">
            <div id="body">
              <section v-html="post_detail.content"></section>
            </div>
            <div id="post-detail-footer">
              <div>
                <p>
                  <strong>Author：</strong>
                  {{post_detail.poster}}&nbsp;&nbsp;| &nbsp;&nbsp;
                  <strong>Created date：</strong>
                  {{post_detail.created}}
                </p>
              </div>
            </div>
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
import { message } from "../../message";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  name: "post",
  components: {
    AdminHeader,
    AdminSideBar,
    Loading
  },
  created() {
    this.fetchData(this.url);
    this.getCategoryParent();
    this.getReleaseNumber();
  },
  mounted() {},
  data() {
    return {
      url: "/posts",
      posts: [],
      deleteItem: {
        id: "",
        index: ""
      },
      emptySearch: "",
      isLoading: false,
      fullPage: false,
      categoryChildrent: [],
      categoryParents: [],
      releaseNumbers: [],
      searchData: {
        releaseNumber: "",
        status: "",
        catParent: "",
        catChild: "",
        keyWord: ""
      },
      delete_confirm_enable: false,
      post_detail: {
        index: "",
        title: "",
        upload_path: "",
        content: "",
        poster: "",
        created: ""
      },
      paginate: {
        postSize: "",
        total: ""
      },
      displayDetail: false
    };
  },
  methods: {
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
    fetchData(url) {
      this.isLoading = true;
      axios
        .get(url)
        .then(res => {
          this.posts = res.data.data;
          this.formatDate(this.posts.data);
          this.paginate.total = this.posts.total;
          this.paginate.postSize = this.posts.to;
          this.isLoading = false;
        })
        .catch(err => {
          console.log(err);
          this.isLoading = false;
        });
    },
    getReleaseNumber() {
      axios
        .get("/release_numbers/list")
        .then(res => {
          this.releaseNumbers = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    prev_page() {
      this.url = this.posts.prev_page_url;
      this.fetchData(this.url);
    },
    next_page() {
      this.url = this.posts.next_page_url;
      this.fetchData(this.url);
    },
    deletePost(id, index) {
      this.delete_confirm_enable = true;
      document.documentElement.style.overflow = "hidden";
      this.deleteItem.id = id;
      this.deleteItem.index = index;
    },
    confirmDelete() {
      axios
        .delete("/posts/" + this.deleteItem.id + "")
        .then(res => {
          this.posts.data.splice(this.deleteItem.index, 1);
          this.paginate.postSize--;
          this.paginate.total--;
          this.deleteItem.index = null;
          this.deleteItem.id = null;
          this.delete_confirm_enable = false;
          document.documentElement.style.overflow = "auto";
        })
        .catch(err => {
          document.documentElement.style.overflow = "auto";
          console.log(err.errors);
        });
    },
    onChangeCategoryParent() {
      this.getCategoryChild();
    },
    cancelDelete() {
      this.delete_confirm_enable = false;
      document.documentElement.style.overflow = "auto";
    },
    disableDetail() {
      this.displayDetail = false;
      document.documentElement.style.overflow = "auto";
    },
    formatDate(posts) {
      for (let post of posts) {
        let updated = new Date(post.updated_at);
        post.updated_at =
          updated.getFullYear() +
          "/" +
          updated.getMonth() +
          "/" +
          updated.getDate() +
          "";
        let created = new Date(post.created_at);
        post.created_at =
          created.getFullYear() +
          "/" +
          created.getMonth() +
          "/" +
          created.getDate() +
          "";
      }
    },
    showDetail(post, index) {
      this.post_detail.index = index;
      this.post_detail.title = post.title;
      this.post_detail.content = post.content;
      this.post_detail.upload_path = post.upload_path;
      this.post_detail.created = post.created_at;
      this.post_detail.poster = post.poster;
      document.documentElement.style.overflow = "hidden";
      this.displayDetail = true;
    },
    edit(post_id) {
      this.$router.push({ name: "PostEdit", params: { id: post_id } });
    },
    create() {
      this.$router.push({ name: "postCreate" });
    },
    getCategoryChild() {
      axios
        .get("/categories/childrent/" + this.searchData.catParent)
        .then(res => {
          this.categoryChildrent = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    search() {
      this.isLoading = true;
      this.emptySearch = "";
      axios
        .get(
          "/posts/search?releaseNumber=" +
            this.searchData.releaseNumber +
            "&status=" +
            this.searchData.status +
            "&catParent=" +
            this.searchData.catParent +
            "&catChild=" +
            this.searchData.catChild +
            "&keyWord=" +
            this.searchData.keyWord
        )
        .then(res => {
          this.posts = res.data.data;
          this.formatDate(this.posts.data);
          this.paginate.postSize = this.posts.to;
          this.paginate.total = this.posts.total;
          this.isLoading = false;
          if (this.posts.data.length == 0) {
            this.emptySearch = "検索結果がありません。";
          }
        })
        .catch(err => {
          console.log(err);
          this.isLoading = false;
        });
    },
    setIndexPage(index) {
      if (this.posts.current_page == 1) {
        return index + 1;
      } else {
        return (this.posts.current_page - 1) * this.posts.per_page + index + 1;
      }
    }
  }
};
</script>

<style scoped>
button,
select,
input {
  border: 1px solid #5b9bd5;
  text-align-last: center;
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
  float: left;
  border-right: 1px solid black;
  float: left;
  min-height: calc(100vh - 70px);
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
}
#create-new a {
  color: white;
}

#search {
  margin: 10px 28px;
  background-color: #f2f2f2;
  padding: 20px;
  height: 100px;
  border: 1px solid #5b9bd5;
}
.search-input {
  width: 25%;
  float: left;
  font-size: 0.9vw;
}

.search-input label {
  width: 37%;
}
.search-input input,
select {
  width: 60%;
  height: 30px;
}

#search button {
  width: 60%;
  background-color: #fff2cc;
  margin-top: 2%;
  padding: 2%;
}
.description {
  width: 96%;
  margin: 30px;
  background-color: #f2f2f2;
  border: 1px solid #5b9bd5;
  float: none;
  clear: both;
  height: 100px;
  font-size: 1vw;
}
#description-index {
  text-align: center;
  padding-top: 20px;
  width: 5%;
  float: left;
  border: 1px solid #5b9bd5;
  height: 100px;
}
#description-body {
  width: 80%;
  float: left;
  height: 100px;
}
#body-above {
  width: 100%;
  height: 50px;
}
#body-below {
  width: 100%;
  height: 50px;
  background-color: #f2f2f2;
}
#body-above #title {
  overflow: auto;
  width: 50%;
  height: 50px;
  text-align: center;
  float: left;
  border: 1px solid #5b9bd5;
}
#body-above #release_number {
  width: 25%;
  height: 50px;
  text-align: center;
  float: left;
  border: 1px solid #5b9bd5;
}
#body-above #category {
  width: 25%;
  height: 50px;
  text-align: center;
  float: left;
  border: 1px solid #5b9bd5;
}

#body-below .body-below-item {
  width: 20%;
  height: 50px;
  text-align: center;
  border: 1px solid #5b9bd5;
  float: left;
}

#description-action {
  padding-top: 2%;
  text-align: center;
  width: 15%;
  float: right;
  border: 1px solid #5b9bd5;
  height: 100px;
}

#div-description #description-index,
#div-description #title,
#div-description #release_number,
#div-description #category,
#div-description .body-below-item,
#div-description #description-action {
  background-color: #d0cece;
}
#table {
  margin-top: -30px;
}

#table #btnSave {
  background-color: #deebf7;
  padding: 0 8%;
}
#table #btnSave a {
  color: #ffffff;
}
#table #btnCancel {
  background-color: #fbe5d6;
  padding: 0 8%;
}
#btnShow {
  background-color: #d6dce5;
  padding-top: 0%;
  margin-top: 3%;
  padding: 0 12%;
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

#pagination {
  margin-bottom: 50px;
  margin-left: 2%;
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
p {
  font-size: 100%;
}

#post-detail {
  overflow: none;
  height: 90%;
  background-color: #fff;
  top: 5%;
  left: 10%;
  position: absolute;
  width: 80%;
  border: 1px solid #5b9bd5;
}
#post-detail-header {
  height: 50px;
  border: 1px solid #5b9bd5;
  width: 100%;
  background-color: #f2f2f2;
}
#post-detail-header #title {
  padding: 10px;
  height: 50px;
  background-color: #f2f2f2;
  border-bottom: 1px solid #5b9bd5;
  text-indent: 10px;
  width: 85%;
  font-weight: bold;
  float: left;
}
#post-detail-header #button {
  padding: 10px;
  height: 50px;
  border-bottom: 1px solid #5b9bd5;
  background-color: #f2f2f2;
  width: 15%;
  float: right;
}
#post-detail-header #button button {
  background-color: #fbe5d6;
  margin-right: 10px;
  float: right;
}
#post-detail-body {
  clear: both;
  overflow: auto;
  width: 100%;
  height: 93%;
  padding: 20px;
}
#post-detail-footer {
  float: left;
  border: 1px solid grey;
  margin: 10px;
  padding: 5px;
  width: 60%;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
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

#post-title {
  padding-left: 3%;
  width: 95%;
  overflow: auto;
}

#emptySearch {
  text-align: center;
  margin-top: 5%;
  font-size: 2em;
  font-weight: bold;
}
</style>