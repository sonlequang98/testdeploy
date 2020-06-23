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
          <h4>Edit release number</h4>
        </div>
        <div id="form-add-new">
          <form method="post" @submit.prevent="edit">
            <div class="error" v-if="errors">
              <p class="alert alert-danger" v-for="error in errors">{{ error }}</p>
            </div>
            <label for>Name</label>
            <br />
            <div id="datePicker">
              <datepicker
                @closed="selectedDP"
                v-model="name"
                :format="'yyyy/MM/dd'"
                :input-class="{datepicker}"
              ></datepicker>
            </div>
            <label for>Image</label>
            <br />
            <div id="show-image">
              <div id="current-image">
                <img :src="upload_path" alt />
              </div>
              <div id="upload-image">
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
            </div>

            <div id="description">
              <label for>Description</label>
              <textarea
                name
                placeholder="形容の入力"
                v-model="release_number.release_number_description"
              ></textarea>
            </div>

            <button type="submit" id="btnSave">Save</button>
          </form>
          <button id="btnCancel" @click="cancel">Cancel</button>
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
import { message } from "../../message";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: "ReleaseNumberEdit",
  created() {
    this.isLoading = true;
  },
  mounted() {
    if (
      this.$store.state.currentUser.role_id ===
      this.$store.state.role.contributor
    ) {
      this.$router.push({ name: "home" });
    }
    axios
      .get("/release_numbers/" + this.$route.params.id + "/edit")
      .then(res => {
        this.release_number = res.data.data;
        this.current_img = this.release_number.image_id;
        this.upload_path = this.release_number.image.upload_path;
        this.name = new Date(
          this.release_number.release_number_name
            .replace("Year", "/")
            .replace("Month", "/")
            .replace("Day", "")
        );
        this.isLoading = false;
      })
      .catch(err => {
        console.log(err.errors);
        this.isLoading = false;
      });
  },
  components: {
    AdminHeader,
    AdminSideBar,
    Datepicker,
    vueDropzone: vue2Dropzone,
    Loading
  },
  data() {
    return {
      release_number: [],
      image_id: "",
      upload_path: "",
      name: "",
      year: "",
      current_img: "",
      month: "",
      day: "",
      isLoading: false,
      fullPage: false,
      errors: [],
      datepicker: {
        width: "100%",
        margin: "10px , 0"
      },
      dropzoneOptions: {
        url: "http://adminsite.local/api/images",
        thumbnailWidth: 160,
        thumbnailHeight: 160,
        dictDefaultMessage:
          "<img src='https://img.icons8.com/ios/50/000000/upload-to-cloud.png'><br>Drop or choose image"
      }
    };
  },
  methods: {
    //format release number name
    selectedDP() {
      this.year = this.name.getFullYear();
      this.month = String(this.name.getMonth()).padStart(2, "0");
      this.day = this.name.getDate() - this.name.getDay() + 5;
      this.name = new Date(this.year, this.month, this.day);
      this.release_number.release_number_name =
        this.name.getFullYear() +
        "/" +
        (Number(String(this.name.getMonth()).padStart(2, "0")) + 1).toString() +
        "/" +
        this.name.getDate();
    },
    //remove error
    addFile() {
      this.errors = [];
    },
    removeAllFiles() {
      this.$refs.dropzone.removeAllFiles();
    },
    //get image id
    vdropzoneSuccess(file, response) {
      this.release_number.image_id = response.data.id;
    },
    //show error
    vdropzoneError(file, message, xhr) {
      this.errors = [];
      this.errors.push(message.errors.file);
    },
    checkValidate() {
      this.errors = [];
      if (this.release_number.release_number_description.length > 500) {
        this.errors.push(message.overDescLength);
        return false;
      }
      return true;
    },
    edit() {
      this.errors = [];
      if (!this.checkValidate()) {
        return;
      }
      axios
        .put("/release_numbers/" + this.release_number.id, this.release_number)
        .then(res => {
          this.$router.push({ name: "releaseNumber" });
        })
        .catch(err => {
          this.errors.push(message.processErr);
        });
    },
    cancel() {
      this.$refs.dropzone.removeAllFiles();
      this.$router.push({
        name: "releaseNumber"
      });
    },
  }
};
</script>

<style scoped>
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
  margin-left: 20px;
}

#form-add-new {
  margin-left: 35px;
  margin-right: 100px;
}
h4 {
  font-weight: bold;
  margin-bottom: 30px;
  font-size: 20px;
  color: orange;
}
#form-add-new .input {
  width: 100%;
  text-indent: 10px;
  margin: 10px 0;
}
#form-add-new label {
  font-size: 20px;
  margin: 10px 0;
}

#current-image {
  width: 50%;
  float: left;
  height: 170px;
  padding-right: 10px;
}

#current-image img {
  height: 100%;
  width: 100%;
  object-fit: cover;
}

#upload-image {
  width: 50%;
  float: right;
  height: 180px;
  padding-left: 10px;
}
#upload-image img {
  height: 100%;
  width: 100%;
  object-fit: cover;
}
#description {
  float: none;
  clear: both;
}

#form-add-new textarea {
  width: 100%;
  height: 100px;
  text-indent: 10px;
}

#form-add-new button {
  float: left;
  margin-top: 30px;
  margin-right: 30px;
}

#form-add-new #btnSave {
  width: 70px;
  background-color: #deebf7;
}

#form-add-new #btnCancel {
  width: 150px;
  background-color: #fff2cc;
  float: left;
}

.datepicker {
  width: 700px;
  margin: 10px 0;
}

#datePicker {
  border: 2px solid #f2f2f2;
}
</style>
<style module>
.datepicker {
  width: 100%;
  text-indent: 10px;
}
</style>
