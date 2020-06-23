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
          <h4>Edit article</h4>
        </div>

        <div id="form-add-new">
          <form action @submit.prevent="save">
            <div id="form-left">
              <div class="form-input">
                <label for>Title</label>
                <input type="text" v-model="post.title" />
              </div>
              <div v-if="errors.title">
                <p class="alert alert-danger">{{ errors.title }}</p>
              </div>
              <div class="form-input">
                <label for>Public date</label>
                <select name id v-model="post.release_number_id">
                  <option
                    value
                    v-for="release_number in release_numbers"
                    :value="release_number.id"
                  >{{release_number.release_number_name}}</option>
                </select>
              </div>
              <br />
              <div id="input-content">
                <vue-editor v-model="post.content" />
              </div>
              <div v-if="errors.content">
                <p class="alert alert-danger">{{ errors.content }}</p>
              </div>
            </div>
            <div id="form-right">
              <div id="parent-category">
                <label for>Category parent</label>
                <br />
                <select
                  name
                  id
                  v-model="post.category_parent_id"
                  @change="onChangeCategoryParent()"
                >
                  <option
                    value
                    v-for="category in category_parent"
                    :value="category.id"
                  >{{category.category_name}}</option>
                </select>
              </div>
              <div id="child-category">
                <label for>Category child</label>
                <br />
                <select name id v-model="post.category_id">
                  <option
                    value
                    v-for="category in category_child"
                    :value="category.id"
                  >{{category.category_name}}</option>
                </select>
              </div>
              <div v-if="errors.categoryChild">
                <p class="alert alert-danger">{{ errors.categoryChild }}</p>
              </div>
              <div id="status">
                <label for>Status</label> <br />
                <select name id v-model="post.status">
                  <option value="public">Public</option>
                  <option value="private">Private</option>
                  <option value="draft">Draft</option>
                </select>
              </div>
              <div id="thumbnail">
                <label for>Thumbnail</label>
                <vue-dropzone
                  ref="dropzone"
                  id="dropzone"
                  @vdropzone-file-added="addFile"
                  :options="dropzoneOptions"
                  @vdropzone-success="vdropzoneSuccess"
                  @vdropzone-error="vdropzoneError"
                ></vue-dropzone>
              </div>
              <div v-if="errors.thumbnail">
                <p class="alert alert-danger">{{ errors.thumbnail }}</p>
              </div>
            </div>
            <div id="action">
              <button type="submit" id="btnSave">Save</button>
              <button id="btnCancel">
                <router-link :to="{ name: 'post'}">Cancel</router-link>
              </button>
            </div>
          </form>
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
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import { VueEditor } from "vue2-editor";
import { message } from "../../message";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: "PostUpdate",
  components: {
    AdminHeader,
    AdminSideBar,
    vueDropzone: vue2Dropzone,
    VueEditor,
    Loading
  },
  mounted() {},
  created() {
    this.getCategoryParent();
    this.getReleaseNumber();
    this.getPost();
  },
  data() {
    return {
      datepicker: {
        width: "100%",
        margin: "10px , 0"
      },
      post: [],
      isLoading: false,
      fullPage: false,
      category_parent: [],
      category_child: [],
      release_numbers: [],
      dropzoneOptions: {
        url: "http://adminsite.local/api/images",
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        dictDefaultMessage:
          "<img src='https://img.icons8.com/ios/50/000000/upload-to-cloud.png'><br>Drop or choose image"
      },
      errors: {
        title: null,
        content: null,
        categoryChild: null,
        thumbnail: null
      }
    };
  },
  methods: {
    getPost() {
      this.isLoading = true;
      axios
        .get("/posts/" + this.$route.params.id + "/edit")
        .then(res => {
          this.post = res.data.data;
          this.getCategoryChild();
          this.isLoading = false;
        })
        .catch(err => {
          console.log(this.err.errors);
          this.isLoading = false;
        });
    },
    checkValidation() {
      this.errors = [];
      let check = true;
      if (this.post.title.length == 0) {
        this.errors.title = message.emptyTitle;
        check = false;
      } else {
        if (this.post.title.length < 10 || this.post.title.length > 300) {
          this.errors.title = message.overTitleLength;
          check = false;
        }
      }
      if (this.post.content.length == 0) {
        this.errors.content = message.emptyContentPost;
        check = false;
      }
      if (this.post.category_id.length == 0) {
        this.errors.categoryChild = message.emptyCategoryChild;
        check = false;
      }
      return check;
    },
    addFile() {
      this.errors = [];
    },
    removeAllFiles() {
      this.$refs.dropzone.removeAllFiles();
    },
    //get image id
    vdropzoneSuccess(file, response) {
      this.errors = [];
      this.post.image_id = response.data.id;
    },
    //show error
    vdropzoneError(file, message, xhr) {
      this.errors = [];
      this.errors.thumbnail = message.errors.file[0];
    },
    getCategoryParent() {
      axios
        .get("/categories/parents/childs")
        .then(res => {
          this.category_parent = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    getCategoryChild() {
      axios
        .get("/categories/childrent/" + this.post.category_parent_id)
        .then(res => {
          this.category_child = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    getReleaseNumber() {
      axios
        .get("/release_numbers/list")
        .then(res => {
          this.release_numbers = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    onChangeCategoryParent() {
      this.getCategoryChild();
      this.post.category_id = "";
    },
    save() {
      this.errors = [];
      if (!this.checkValidation()) {
        return;
      }
      axios
        .put("/posts/" + this.post.id, this.post)
        .then(res => {
          this.$router.push({ name: "post" });
        })
        .catch(err => {
          this.errors.push(message.errSavePost);
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
  padding-top: 35px;
  width: 85%;
  float: right;
}
#main-header {
  margin-left: 30px;
}

#form-add-new {
  width: 100%;
  margin-left: 48px;
  margin-top: 20px;
}

#form-left {
  width: 75%;
  float: left;
}
.form-input {
  display: inline;
}

#form-left label {
  float: left;
  margin-right: 15px;
  margin-top: 20px;
}
#form-left input {
  width: 90%;
  margin-top: 20px;
}
#form-left select {
  margin-top: 20px;
  width: 84%;
  margin-left: 10px;
  margin-bottom: 30px;
}
#input-content {
  width: 96%;
  margin-left: 1px;
}
#form-right {
  padding-left: 10px;
  margin-top: 19px;
  width: 23%;
  float: left;
  background-color: #f2f2f2;
}
#form-right label {
  margin-bottom: 5px;
}

#form-right select {
  margin-bottom: 5px;
  width: 80%;
}
#dropzone {
  width: 80%;
}
#action {
  width: 100%;
  clear: both;
  padding-top: 3%;
}
#action #btnSave {
  width: 70px;
  background-color: #deebf7;
  margin-right: 15px;
  float: left;
}

#action #btnCancel {
  width: 150px;
  background-color: #fff2cc;
}
.errors {
  color: #ec4134;
  text-align: left;
}

.alert-danger {
  padding: 0 0;
  width: 96%;
  margin-top: 1%;
  margin-bottom: 0;
}
</style>

<style module>
</style>