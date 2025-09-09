<template>
<div>
  <site-header 
    :user="$store.state.user" 
    :view="'show'">
  </site-header>
  <site-main v-if="isFetched">
    <page-menu 
      :type="$route.params.type" 
      :uuid="$route.params.uuid"
      :application="data" 
      class="has-selection mb-20x"
    ></page-menu>
    <form @submit.prevent="submit" v-if="isFetched">
      <div class="application-action">
        <div>
          <div class="span-4 start-3">
            <button 
              type="submit" 
              :class="[hasErrors ? 'btn-primary is-small disabled' : 'btn-primary is-small']">
              Speichern
            </button>
          </div>
          <div class="span-4">
            <router-link 
              :to="{name: 'application-show', params: { type: $route.params.type, uuid: data.uuid }}"
              class="btn-secondary is-outline is-small">
              Abbrechen
            </router-link>
          </div>
        </div>
      </div>
      <application-wrapper>
        <application-grid>
          <div class="line-after">
            <h2>Organisation</h2>
            <application-row>
              <application-label :cls="'span-1'">Name</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.name" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Vorheriger Name</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.former_name" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Strasse</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.street" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Nr.</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.street_number" @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">PLZ</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.zip" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Ort</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.city" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Webseite</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.website">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Vorname</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.firstname" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Name</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.lastname" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Telefon</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.phone" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">E-Mail</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.email" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Rechtsform</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.legal_form" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row class="pb-3x">
              <application-label :cls="'span-1'">IBAN (CH)</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.iban" required @blur="validate($event)" />
              </application-input>
            </application-row>
            <application-row class="pb-3x">
              <application-label :cls="'span-1'">Begünstigter</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.beneficiary" required @blur="validate($event)" />
              </application-input>
            </application-row>
            <application-row class="pb-3x">
              <application-label :cls="'span-1'">Portrait</application-label>
              <application-input :cls="'span-3'">
                <textarea name="portrait" v-model="data.portrait" required @blur="validate($event)" rows="10"></textarea>
              </application-input>
            </application-row>



            <h2>Serienbrief</h2>
            <application-row class="pb-3x">
              <application-label :cls="'span-1'">Anrede</application-label>
              <application-input :cls="'span-3'">
                <textarea name="salutation" v-model="data.salutation" placeholder="Sehr geehrte Damen und Herren"></textarea>
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">IBAN (CH)</application-label>
              <application-input :class="'span-3'">
               <input type="text" v-model="data.iban" required @blur="validate($event)" />
              </application-input>
            </application-row>
            <application-row class="mt-3x">
              <application-label :cls="'span-1'">Textblock<br>Zusage</application-label>
              <application-input :class="'span-3'">
                <textarea name="textblock_approval" v-model="data.textblock_approval"></textarea>
              </application-input>
            </application-row>
            <application-row class="mt-3x">
              <application-label :cls="'span-1'">Textblock<br>Zusage (Begründung)</application-label>
              <application-input :class="'span-3'">
                <textarea name="textblock_justification" v-model="data.textblock_justification"></textarea>
              </application-input>
            </application-row>
            <application-row class="mt-3x">
              <application-label :cls="'span-1'">Textblock<br>Absage</application-label>
              <application-input :class="'span-3'">
                <textarea name="textblock_denial" v-model="data.textblock_denial"></textarea>
              </application-input>
            </application-row>

            <h2>Dokumente</h2>
            <application-row v-for="file in data.files" :key="file.uuid">
              <div class="span-1">
                <label>{{ file.title }}</label>
              </div>
              <div class="span-3 flex justify-between">
                <a :href="`/download/${data.uuid}/${file.name}`" class="anchor-download" target="_blank" :title="`Download ${file.title}`">
                  {{file.name | truncate(30, '...')}}
                </a>
                <div class="flex justify-between">
                  <vue-dropzone
                    ref="dropzone"
                    id="dropzone"
                    :options="config"
                    @vdropzone-sending="uploadSending"
                    @vdropzone-success="uploadSuccess"
                    @vdropzone-complete="uploadComplete"
                    @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                    :useCustomSlot=true
                  >
                    <a href="javascript:;" @click="beforeUpload(file.uuid)" class="icon-replace">
                      <icon-replace />
                    </a>
                  </vue-dropzone>
                  <a 
                    href="javascript:;" 
                    @click="beforeDelete(file.uuid)" 
                    class="icon-trash ml-3x" 
                    v-if="file.name">
                    <icon-trash />
                  </a>
                </div>
              </div>
            </application-row>

            <!-- Upload -->
            <div v-if="hasUpload" class="mt-3x">
              <application-row v-if="dataUpload.name">
                <application-label :cls="'span-1'">Datei</application-label>
                <div class="span-3 flex justify-between">
                  <a :href="`/download/${data.uuid}/${dataUpload.name}`" class="anchor-download" target="_blank">
                    {{dataUpload.name | truncate(30, '...')}}
                  </a>
                  <a 
                    href="javascript:;" 
                    @click="deleteUpload(dataUpload.name)" 
                    class="icon-trash ml-3x">
                    <icon-trash />
                  </a>
                </div>
              </application-row>
              <application-row>
                <application-label :cls="'span-1'">Bezeichnung</application-label>
                <application-input :cls="'span-3'">
                  <input type="text" v-model="dataUpload.title" required @blur="validate($event)">
                </application-input>
              </application-row>
              <application-row>
                <application-label :cls="'span-1'">Kommentar</application-label>
                <application-input :cls="'span-3 pb-4x'">
                  <textarea v-model="dataUpload.comment" required @blur="validate($event)"></textarea>
                </application-input>
              </application-row>
              <div class="grid-cols-12">
                <div class="span-6">
                  <a href="javascript:;" class="btn-primary is-small" @click.prevent="storeFile()">Speichern</a>
                </div>
                <div class="span-6">
                  <a href="javascript:;" class="btn-secondary is-small is-outline">Abbrechen</a>
                </div>
              </div>
            </div>
            <div class="grid-cols-12 mt-3x" v-else>
              <div class="span-3 start-4">
                  <vue-dropzone
                    ref="dropzone"
                    id="dropzone"
                    :options="config"
                    @vdropzone-sending="uploadNewSending"
                    @vdropzone-success="uploadNewSuccess"
                    @vdropzone-complete="uploadComplete"
                    @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                    :useCustomSlot=true
                  >
                  <a href="javascript:;" class="icon-plus-small">
                    <icon-plus />
                  </a>
                  </vue-dropzone>
              </div>
            </div>
            <!-- // Upload -->

          </div>

          <div class="line-after">
            <h2>Projekt</h2>
            <application-row>
              <application-label :cls="'span-1'">Titel</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.project_title" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Beschreibung</application-label>
              <application-input :cls="'span-3'">
                <textarea v-model="data.project_description" required rows="10" @blur="validate($event)"></textarea>
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Dauer</application-label>
              <application-input :cls="'span-3'">
                <textarea v-model="data.project_time" required rows="10" @blur="validate($event)"></textarea>
              </application-input>
            </application-row>
            <application-row>
              <div class="span-1"><label>Begründung</label></div>
              <div class="span-3 pb-6x">{{data.justification_funds}}</div>
            </application-row>
            <application-row>
              <div class="span-1"><label>Begünstigte</label></div>
              <div class="span-3 pb-6x">{{data.project_beneficiaries}}</div>
            </application-row>
            <application-row>
              <div class="span-1"><label>Begünstigte Zürich</label></div>
              <div class="span-3">{{data.proportion_residents_benefit_program}}</div>
            </application-row>

            <h2>Bemerkungen</h2>
            <application-row class="mb-3x">
              <div class="span-1"><label>Anteil Stadtzürcher*-innen</label></div>
              <div class="span-3"><textarea v-model="data.remarks_percentage_of_residents_zurich"></textarea></div>
            </application-row>
            <application-row class="mb-3x">
              <div class="span-1"><label>Direkter Nutzen Zielgruppe</label></div>
              <div class="span-3"><textarea v-model="data.remarks_direct_benefits_to_target_group"></textarea></div>
            </application-row>
            <application-row class="mb-3x">
              <div class="span-1"><label>Ausser-ordentlichkeit Vorhaben</label></div>
              <div class="span-3 flex items-start">
                <div class="mt-1x flex items-center">
                  <input type="radio" id="extraordinary-yes" name="remarks_exceptionality_of_project" v-bind:value="1" v-model="data.remarks_exceptionality_of_project" />
                  <label class="ml-2x" for="extraordinary-yes">Ja</label>
                </div>
                <div class="ml-3x mt-1x flex items-center">
                  <input type="radio" id="extraordinary-no" name="remarks_exceptionality_of_project" v-bind:value="0" v-model="data.remarks_exceptionality_of_project" />
                  <label class="ml-2x" for="extraordinary-no">Nein</label>
                </div>
              </div>
            </application-row>
            <application-row class="mb-3x">
              <div class="span-1"><label>Weitere relevante Informationen</label></div>
              <div class="span-3"><textarea v-model="data.remarks_additional_relevant_information"></textarea></div>
            </application-row>
            <application-row class="mb-3x">
              <div class="span-1"><label>Inhaltliche Zuordnung</label></div>
              <div class="span-3">
                <div class="mt-1x flex items-center">
                  <input type="checkbox" id="remarks_content_allocation-erhalt" name="remarks_content_allocation" value="Bau/Umbau" @change="changeOption($event)" checked v-if="hasOption('remarks_content_allocation', 'Bau/Umbau')">
                  <input type="checkbox" id="remarks_content_allocation-erhalt" name="remarks_content_allocation" value="Bau/Umbau" @change="changeOption($event)" v-else>
                  <label class="ml-2x" for="remarks_content_allocation-erhalt">Bau/Umbau</label>
                </div>
                <div class="mt-1x flex items-center">
                  <input type="checkbox" id="remarks_content_allocation-infra" name="remarks_content_allocation" value="Infrastruktur" @change="changeOption($event)" checked v-if="hasOption('remarks_content_allocation', 'Infrastruktur')">
                  <input type="checkbox" id="remarks_content_allocation-infra" name="remarks_content_allocation" value="Infrastruktur" @change="changeOption($event)" v-else>
                  <label class="ml-2x" for="remarks_content_allocation-infra">Infrastruktur (IT, Mobiliar, etc.)</label>
                </div>
                <div class="mt-1x flex items-center">
                  <input type="checkbox" id="remarks_content_allocation-projekt" name="remarks_content_allocation" value="Projekt" @change="changeOption($event)" checked v-if="hasOption('remarks_content_allocation', 'Projekt')">
                  <input type="checkbox" id="remarks_content_allocation-projekt" name="remarks_content_allocation" value="Projekt" @change="changeOption($event)" v-else />
                  <label class="ml-2x" for="remarks_content_allocation-projekt">Projekt</label>
                </div>
                <div class="mt-1x flex items-center">
                  <input type="checkbox" id="remarks_content_allocation-ao" name="remarks_content_allocation" value="Betriebsbeitrag" @change="changeOption($event)" checked v-if="hasOption('remarks_content_allocation', 'Betriebsbeitrag')">
                  <input type="checkbox" id="remarks_content_allocation-ao" name="remarks_content_allocation" value="Betriebsbeitrag" @change="changeOption($event)" v-else />
                  <label class="ml-2x" for="remarks_content_allocation-ao">a.o. Betriebsbeitrag</label>
                </div>
              </div>
            </application-row>
          </div>
          <div>
            <h2>Projektkosten und Finanzierung</h2>
            <application-row>
              <application-label :cls="'span-3'">Total</application-label>
              <application-input :cls="'span-1'">
                <input type="text" v-model="data.project_cost_total" class="align-right" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-3'">Eigenleistungen</application-label>
              <application-input :cls="'span-1'">
                <input type="text" v-model="data.project_own_contribution" class="align-right" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row v-if="data.project_add_instit_total_2 > 0">
              <div class="span-3 text-grey"><label class="text-grey">{{data.project_add_instit_2}}</label></div>
              <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_total_2 | currency}}</div>
            </application-row>
            <application-row v-if="data.project_add_instit_total_3 > 0">
              <div class="span-3 text-grey"><label class="text-grey">{{data.project_add_instit_3}}</label></div>
              <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_total_3 | currency}}</div>
            </application-row>
            <application-row v-if="data.project_add_instit_total_4 > 0">
              <div class="span-3 text-grey"><label class="text-grey">{{data.project_add_instit_4}}</label></div>
              <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_total_4 | currency}}</div>
            </application-row>
            <application-row v-if="data.project_add_instit_total_5 > 0">
              <div class="span-3 text-grey"><label class="text-grey">{{data.project_add_instit_5}}</label></div>
              <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_total_5 | currency}}</div>
            </application-row>
            <application-row>
              <application-label :cls="'span-3'">Budgetierter jährlicher Ertrag</application-label>
              <application-input :cls="'span-1'">
                <input type="text" v-model="data.project_income" class="align-right" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-3'">Beantragter Beitrag</application-label>
              <application-input :cls="'span-1'">
                <input type="text" v-model="data.project_contribution_requested" class="align-right" required @blur="validate($event)">
              </application-input>
            </application-row>
          </div>
        </application-grid>
      </application-wrapper>
    </form>
  </site-main>
  <dialog-wrapper ref="dialogDestroyFile">
    <template #message>
      <div><strong>Möchten Sie diese Datei wirklich löschen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="deleteFile()">Ja, löschen</a>
    </template>
  </dialog-wrapper>
</div>
</template>
<script>
import NProgress from 'nprogress';
import ErrorHandling from "@/mixins/ErrorHandling";
import IconReplace from "@/components/ui/icons/Replace.vue";
import IconTrash from "@/components/ui/icons/Trash-sm.vue";
import IconPlus from "@/components/ui/icons/Plus-sm.vue";
import DialogWrapper from "@/components/ui/misc/Dialog.vue";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import PageMenu from '@/components/layout/Menu.vue';
import ApplicationWrapper from '@/components/layout/Wrapper.vue';
import ApplicationGrid from '@/components/layout/Grid.vue';
import ApplicationRow from '@/components/layout/Row.vue';
import ApplicationLabel from '@/components/layout/Label.vue';
import ApplicationInput from '@/components/layout/Input.vue';
import vue2Dropzone from "vue2-dropzone";

export default {
  components: {
    NProgress,
    DialogWrapper,
    IconReplace,
    IconTrash,
    IconPlus,
    SiteHeader,
    SiteMain,
    PageMenu,
    ApplicationWrapper,
    ApplicationGrid,
    ApplicationRow,
    ApplicationLabel,
    ApplicationInput,
    vueDropzone: vue2Dropzone
  },

  mixins: [ErrorHandling],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {

      },

      dataUpload: {
        uuid: this.$route.params.uuid,
        title: null,
        comment: null,
        name: null,
      },

      // Routes
      routes: {
        fetch: '/api/application',
        put: '/api/application',
        destroy: '/api/application',
        storeFile: '/api/application-file/store',
        updateFile: '/api/application-file/update',
        deleteFile: '/api/application-file/delete',
        deleteUpload: '/api/application-file/delete-upload',
      },

      // States
      isFetched: true,
      isLoading: false,
      hasErrors: false,
      hasUpload: false,

      // Messages
      messages: {
        updated: 'Änderungen gespeichert!',
      },

      // Files
      fieldToDelete: null,
      uuidToDelete: null,

      fileUuid: null,

      // Dropzone config
      config: {
        url: "/api/application/file/upload",
        method: 'post',
        maxFilesize: 9,
        maxFiles: 100,
        createImageThumbnails: false,
        autoProcessQueue: true,
        acceptedFiles: '.pdf, .doc, .docx, .xls, .xlsx, .zip',
        previewTemplate: this.template(),
        headers: {
          'x-csrf-token': document.head.querySelector('meta[name="csrf-token"]').content
        }
      },

    };
  },

  created() {
    this.fetch();
    NProgress.configure({ showBar: false });
  },

  methods: {

    fetch() {
      NProgress.start();
      this.isFetched = false;
      this.axios.get(`${this.routes.fetch}/${this.$route.params.uuid}`).then(response => {
        this.data = response.data;
        this.data.remarks_content_allocation = this.data.content_allocation;
        this.isFetched = true;
        NProgress.done();
      });
    },

    submit() {
      NProgress.start();
      this.isFetched = true;
      this.axios.put(`${this.routes.put}/${this.$route.params.uuid}`, this.data).then(response => {
        this.$router.push({ name: 'application-show', params: {type: this.$route.params.type, uuid: this.data.uuid} });
        this.isFetched = false;
        NProgress.done();
      });
    },

    storeFile() {
      NProgress.start();
      this.axios.post(this.routes.storeFile, this.dataUpload).then(response => {
        this.data.files.push(response.data);
        this.hideUpload();
        NProgress.done();
      });
    },

    updateFile() {
      NProgress.start();
      this.axios.put(`${this.routes.updateFile}/${this.fileUuid}`, this.dataUpload).then(response => {
        NProgress.done();
      });
    },

    validate(event) {
      if (event.target.value.length > 0) {
        event.target.classList.remove('is-invalid');
        this.hasErrors = false;
        return;
      }
      event.target.classList.add('is-invalid');
      this.hasErrors = true;
    },

    changeOption(event) {
      if (event.target.name === 'remarks_content_allocation') {

        if (event.target.checked) {
          this.data.remarks_content_allocation.push(event.target.value);
          return;
        }
        else {
          const index = this.data.remarks_content_allocation.indexOf(event.target.value);
          this.data.remarks_content_allocation.splice(index, 1);
          return;
        }
        return;
      }
    },

    hasOption(array, value) {
      return this.data[array].indexOf(value) > -1 ? true : false;
    },

    showUpload() {
      this.hasUpload = true;
    },

    hideUpload() {
      this.dataUpload = {
        uuid: this.$route.params.uuid,
        title: null,
        comment: null,
        name: null,
      };
      this.hasUpload = false;
    },

    deleteFile() {
      NProgress.start();
      this.axios.delete(`${this.routes.deleteFile}/${this.fileUuid}`).then(response => {
        const index = this.data.files.findIndex(x => x.uuid === this.fileUuid);
        this.data.files.splice(index, 1);
        this.fileUuid = null;
        this.$refs.dialogDestroyFile.hide();
        NProgress.done();
      });
    },

    deleteUpload(file) {
      NProgress.start();
      this.axios.delete(`${this.routes.deleteUpload}/${this.$route.params.uuid}/${file}`).then(response => {
        this.hideUpload();
        NProgress.done();
      });
    },

    beforeDelete(uuid) {
      this.fileUuid = uuid;
      this.$refs.dialogDestroyFile.show();
    },

    beforeUpload(uuid) {
      this.fileUuid = uuid;
    },

    uploadSending(file, xhr, formData) {
      NProgress.start();
      formData.append('uuid', this.$route.params.uuid);
      formData.append('fileUuid', this.fileUuid);
    },

    uploadSuccess(file, response) {
      let res = JSON.parse(file.xhr.response);
      const index = this.data.files.findIndex(x => x.uuid == this.fileUuid);
      this.data.files[index].name = res.name;
      this.dataUpload.name = res.name;
      this.updateFile();
    },

    uploadNewSending(file, xhr, formData) {
      NProgress.start();
      formData.append('uuid', this.$route.params.uuid);
    },

    uploadNewSuccess(file, response) {
      let res = JSON.parse(file.xhr.response);
      this.dataUpload.name = res.name;
      this.showUpload();
      NProgress.done();
    },

    uploadComplete(file) {
      if (file.status == "error") {
        if (file.xhr !== undefined) {
          let res = JSON.parse(file.xhr.response);
          if (res.errors.file) {
            res.errors.file.forEach(error => console.log(error));
            this.$refs.dropzone.removeFile(file);
          }
        }
        else {
          if (file.accepted == false) {
            if (file.size > 9000000) {
              alert('image_exceeds_max_size');
            }
            else {
              alert('image_type_not_allowed');
            }

            this.$refs.dropzone.removeFile(file);
          }
        }
      }

    },

    uploadMaxFilesExceeded(file) {
      this.$refs.dropzone.removeAllFiles(true);
      alert('image_max_files_exceeded')
    },

    template: function () {
      return `<div></div>`;
    },
  },

};
</script>
