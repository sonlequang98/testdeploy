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
          <h4>Create new article</h4>
        </div>

        <div id="form-add-new">
          <form action @submit.prevent="save">
            <div class="error">
              <p v-if="errors" v-for="error in errors">{{ error }}</p>
            </div>
            <div id="form-left">
              <div class="form-input">
                <label for>Title</label>
                <input type="text" v-model="post.title" />
              </div>
              <div class="error">
                <p class="alert alert-danger" v-if="errors.title">{{ errors.title }}</p>
              </div>
              <div class="form-input">
                <label for>Release number</label>
                <select name id v-model="post.release_number_id">
                  <option
                    value
                    v-for="release_number in release_numbers"
                    :value="release_number.id"
                  >{{release_number.release_number_name}}</option>
                </select>
                <div class="error">
                  <p
                    class="alert alert-danger"
                    v-if="errors.releaseNumber"
                  >{{ errors.releaseNumber }}</p>
                </div>
              </div>

              <div id="input-content">
                <vue-editor v-model="post.content" />
              </div>
              <div class="error">
                <p class="alert alert-danger" v-if="errors.content">{{ errors.content }}</p>
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
                <div class="error">
                  <p
                    class="alert alert-danger"
                    v-if="errors.categoryParent"
                  >{{ errors.categoryParent }}</p>
                </div>
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
                <div class="error">
                  <p
                    class="alert alert-danger"
                    v-if="errors.categoryChild"
                  >{{ errors.categoryChild }}</p>
                </div>
              </div>
              <div id="status">
                <label for>Status</label>
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
                  :destroyDropzone="false"
                ></vue-dropzone>
                <div class="error">
                  <p class="alert alert-danger" v-if="errors.image">{{ errors.image }}</p>
                </div>
              </div>
            </div>
            <div id="action">
              <button type="submit" id="btnSave">Save</button>
              <button id="btnCancel" @click="cancel">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import AdminHeader from "./AdminHeader.vue";
import AdminSideBar from "./AdminSideBar.vue";
import Datepicker from "vuejs-datepicker";
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import { message } from "../../message";
// import { VueEditor } from "vue2-editor";

export default {
  name: "PostCreate",
  components: {
    AdminHeader,
    AdminSideBar,
    vueDropzone: vue2Dropzone
    // VueEditor
  },
  mounted() {},
  created() {
    this.getCategoryParent();
    this.getReleaseNumber();
    this.post.poster = this.$store.state.currentUser.id;
  },
  data() {
    return {
      datepicker: {
        width: "100%",
        margin: "10px , 0"
      },
      post: {
        title: "",
        content: "",
        status: "public",
        category_id: "",
        category_parent_id: "",
        image_id: "",
        release_number_id: "",
        poster: ""
      },
      useCustomImageHandler: true,
      category_parent: [],
      category_child: [],
      release_numbers: [],
      errors: {
        title: null,
        releaseNumber: null,
        content: null,
        categoryParent: null,
        categoryChild: null,
        image: null
      },
      dropzoneOptions: {
        url: "http://adminsite.local/api/images",
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        dictDefaultMessage:
          "<img src='https://img.icons8.com/ios/50/000000/upload-to-cloud.png'><br>Drop or choose image"
      }
    };
  },
  methods: {
    checkValidation() {
      let check = true;
      this.errors = [];
      if (this.post.title.length == 0) {
        this.errors.title = message.emptyTitle;
        check = false;
      } else if (this.post.title.length < 10 || this.post.title.length > 300) {
        this.errors.title = message.overTitleLength;
        check = false;
      }
      if (this.post.content.length == 0) {
        this.errors.content = message.emptyContentPost;
        check = false;
      }
      if (this.post.release_number_id.length == 0) {
        this.errors.releaseNumber = message.emptyReleaseNumber;
        check = false;
      }
      if (this.post.category_parent_id.length == 0) {
        this.errors.categoryParent = message.emptyCategoryParent;
        check = false;
      }
      if (this.post.category_id.length == 0) {
        this.errors.categoryChild = message.emptyCategoryChild;
        check = false;
      }
      if (this.post.image_id.length == 0) {
        this.errors.image = message.emptyImage;
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
      // this.errors.push(message.errors.file);
      this.errors.image = message.errors.file[0];
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
    },
    save() {
      if (!this.checkValidation()) {
        return;
      }
      axios
        .post("/posts", this.post)
        .then(res => {
          this.$router.push({ name: "post" });
        })
        .catch(err => {
          this.errors.push(message.errSavePost);
        });
    },
    cancel() {
      this.$router.push({ name: "post" });
    }
  }
};
</script>
<style scoped>
h4 {
  font-weight: bold;
  margin-bottom: 30px;
  font-size: 20px;
  color: orange;
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
  width: 90%;
  margin-left: 48px;
  margin-top: 20px;
}

#form-left {
  width: 76%;
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
  width: 80%;
  margin-left: 10px;
  padding: 0.4% 0%;
  margin-bottom: 10px;
}
#input-content {
  width: 96%;
  margin-left: 1px;
}
#form-right {
  padding-left: 10px;
  margin-top: 19px;
  width: 24%;
  float: left;
  background-color: #f2f2f2;
}
#form-right label {
  margin-bottom: 5px;
}

#form-right select {
  margin-bottom: 5px;
  width: 95%;
}
#dropzone {
  width: 95%;
}
#action {
  width: 100%;
  clear: both;
  padding-top: 20px;
}
#action #btnSave {
  width: 6%;
  padding: 0.6%;
  background-color: #deebf7;
  margin-right: 15px;
  float: left;
}

#action #btnCancel {
  width: 12%;
  padding: 0.6%;
  background-color: #fff2cc;
}
.errors {
  color: #ec4134;
  text-align: left;
}

.alert-danger {
  padding-top: 0.4%;
  margin-top: 0.5%;
  padding-bottom: 0.4%;
  margin-right: 4%;
}

.ql-toolbar.ql-snow + .ql-container.ql-snow {
  border-top: 0;
  height: 450px;
  overflow: auto;
}
</style>

<style module>
</style>