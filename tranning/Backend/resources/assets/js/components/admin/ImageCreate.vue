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
        <h4>Create image</h4>
      </div>
      <div id="main-body">
        <div>
          <p class="alert alert-danger" v-if="errors" v-for="error in errors">{{error}}</p>
        </div>
        <div id="drop-zone">
          <vue-dropzone
            ref="dropzone"
            id="dropzone"
            @vdropzone-file-added="addFile"
            :options="dropzoneOptions"
            @vdropzone-success="vdropzoneSuccess"
            @vdropzone-error="vdropzoneError"
            :destroyDropzone="false"
          ></vue-dropzone>
        </div>

        <button id="btnCancel" @click="cancelAddNew">Cancel</button>
        <div id="listImage">
          <div id="image-uploaded" v-for="image in listImage">
            <img :src="image.upload_path" alt="image" />
            <br>
            <p>{{image.name}}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AdminHeader from "./AdminHeader.vue";
import AdminSideBar from "./AdminSideBar.vue";
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import { message } from '../../message';

export default {
  name: "imageCreate",
  components: {
    AdminHeader,
    AdminSideBar,
    vueDropzone: vue2Dropzone
  },
  created() {},
  beforeDestroy() {},
  data() {
    return {
      dropzoneOptions: {
        url: "http://adminsite.local/api/images",
        thumbnailWidth: 150,
        dictDefaultMessage:
          "<img src='https://img.icons8.com/ios/50/000000/upload-to-cloud.png'><br>Drop or choose image"
      },
      errors: [],
      listImage: []
    };
  },
  methods: {
    vdropzoneSuccess(file, response) {
      this.errors = [];
      this.listImage.push(response.data);
    },
    addFile() {
      this.errors = [];
    },
    vdropzoneError(file, message, xhr) {
      this.errors = [];
      this.errors.push(message.errors.file[0]);
    },
    getUploadPath(id) {
      axios
        .get("/images/" + id + "/uploadPath")
        .then(res => {
          this.listImage.push(res.data.data);
        })
        .catch(err => {
          console.log(err);
        });
    },
    cancelAddNew() {
      this.$router.push({
        name: "imageManages"
      });
    },
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
  color: orange;
  font-size: 20px;
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
  padding: 3%;
  padding-top: 1%;
}

#drop-zone {
  width: 100%;
}

#listImage {
  widows: 100%;
  height: 180px;
}

#btnCancel {
  background-color: #fff2cc;
  padding: 0.5% 2%;
  margin: 2% 0%;
}

#listImage img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  margin-right: 1%;
  float: none;
}

.alert-danger {
  padding-top: 0.4%;
  padding-bottom: 0.4%;
}

#image-uploaded {
  float: left;
  width: 13%;
  margin-right: 1%;
}
</style>