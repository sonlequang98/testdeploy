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
          <h4>Create new release number</h4>
        </div>
        <div id="form-add-new">
          <form method="post" @submit.prevent="save">
            <div v-if="createSuccess" class="alert alert-success">
              <p>{{createSuccess}}</p>
            </div>
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
            <vue-dropzone
              ref="dropzone"
              v-model="image_upload"
              id="dropzone"
              @vdropzone-file-added="addFile"
              :options="dropzoneOptions"
              @vdropzone-success="vdropzoneSuccess"
              @vdropzone-error="vdropzoneError"
              :destroyDropzone="false"
            ></vue-dropzone>

            <label for>Description</label>
            <textarea name placeholder="Enter description" v-model="release_number.release_number_description"></textarea>

            <button type="submit" id="btnSave">Save</button>
          </form>
          <button id="btnCancel" @click="cancel">Cancel</button>
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

let today = new Date();
let dd = today.getDate() - today.getDay() + 5;
let mm = String(today.getMonth()).padStart(2, "0");
let yyyy = today.getFullYear();
export default {
  name: "ReleaseNumberCreate",
  mounted() {
    if (
      this.$store.state.currentUser.role_id ===
      this.$store.state.role.contributor
    ) {
      this.$router.push({ name: "home" });
    }
    this.name = new Date(yyyy, mm, dd);
    this.release_number.release_number_name =
      this.name.getFullYear() +
      "/" +
      (Number(String(this.name.getMonth()).padStart(2, "0")) + 1).toString() +
      "/" +
      this.name.getDate() +
      "/";
  },
  components: {
    AdminHeader,
    AdminSideBar,
    Datepicker,
    vueDropzone: vue2Dropzone
  },
  data() {
    return {
      release_number: {
        release_number_name: "",
        image: null,
        release_number_description: ""
      },
      createSuccess: "",
      image_upload: "",
      name: "",
      year: "",
      month: "",
      day: "",
      errors: [],
      datepicker: {
        width: "100%",
        margin: "10px , 0"
      },

      imageDelete: true,
      dropzoneOptions: {
        url: "http://adminsite.local/api/images",
        thumbnailWidth: 150,
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
      this.errors = [];
      this.release_number.image = response.data.id;
    },
    //show error
    vdropzoneError(file, message, xhr) {
      this.errors = [];
      this.errors.push(message.errors.file[0]);
    },

    checkValidate() {
      this.errors = [];
      let check = true;
      if (this.release_number.release_number_description.length > 500) {
        this.errors.push(message.overDescLength);
        check = false;
      }
      if (!this.release_number.image) {
        this.errors.push(message.emptyImage);
        check = false;
      }
      if (this.release_number.release_number_name.length == 0) {
        this.errors.push(message.emptyReleaseNumber);
        check = false;
      }
      return check;
    },
    save() {
      if (!this.checkValidate()) {
        return;
      }
      axios
        .post("/release_numbers", this.release_number)
        .then(res => {
          this.errors = [];
          this.imageDelete = false;
          this.release_number.release_number_description = "";
          this.release_number.image = "";
          this.name = new Date(yyyy, mm, dd);
          this.removeAllFiles();
          this.createSuccess = "Create success";
          let self = this;
          setTimeout(function() {
            self.createSuccess = null;
          }, 4000);
        })
        .catch(error => {
          this.errors.push(message.processErr);
        });
    },
    cancel() {
      this.$router.push({ name: "releaseNumber" });
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
  margin-left: 1%;
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

.alert-danger {
  color: #a94442;
  background-color: #f2dede;
  border-color: #ebccd1;
  padding-top: 0.4%;
  padding-bottom: 0.4%;
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
