<template>
<div>
  <site-header :user="$store.state.user" class="is-detail"></site-header>
  <site-main v-if="isFetched">
    <page-menu 
      :type="$route.params.type" 
      :uuid="$route.params.uuid" 
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
              <application-label :cls="'span-1'">Strasse</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.street" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-1'">Nr.</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.street_number" required @blur="validate($event)">
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
              <application-label :cls="'span-1'">Bankkonto</application-label>
              <application-input class="span-3 mb-3x">
                <textarea v-model="data.bank_account" required @blur="validate($event)"></textarea>
              </application-input>
            </application-row>

            <h2>Dokumente</h2>
            <application-row>
              <div class="span-1"><label>Portrait</label></div>
              <div class="span-3 flex justify-between" v-if="data.file_portrait">
                <a :href="`/download/${data.uuid}/${data.file_portrait}`" class="anchor-download" target="_blank" title="Download Portrait">
                  {{data.file_portrait | truncate(30, '...')}}
                </a>
                <div class="flex justify-between">
                  <div class="mr-3x" style="display: block; height: 16px; width: 16px; background: red; cursor: pointer; margin-top: 5px"></div>
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
                    <a href="javascript:;" @click="uploadBefore('file_portrait')" style="display: block; height: 16px; width: 16px; background: yellow; cursor: pointer; margin-top: 5px"></a>
                  </vue-dropzone>
                </div>
              </div>
            </application-row>
            <application-row>
              <div class="span-1"><label>Jahresbericht</label></div>
              <div class="span-3 flex justify-between">
                <a :href="`/download/${data.uuid}/${data.file_anunal_report}`" class="anchor-download" target="_blank" title="Download Jahresbericht">
                  {{data.file_anunal_report | truncate(30, '...')}}
                </a>
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
                    <a href="javascript:;" @click="uploadBefore('file_anunal_report')" style="display: block; height: 16px; width: 16px; background: yellow; cursor: pointer; margin-top: 5px"></a>
                  </vue-dropzone>
              </div>
            </application-row>
            <application-row>
              <div class="span-1"><label>Jahresrechnung</label></div>
              <div class="span-3 flex justify-between">
                <a :href="`/download/${data.uuid}/${data.file_annual_financial_report}`" class="anchor-download" target="_blank" title="Download Jahresrechnung">
                  {{data.file_annual_financial_report | truncate(30, '...')}}
                </a>
              </div>
            </application-row>
            <application-row>
              <div class="span-1"><label>Budget</label></div>
              <div class="span-3">
                <a :href="`/download/${data.uuid}/${data.file_budget}`" class="anchor-download" target="_blank" title="Download Budget">
                  {{data.file_budget | truncate(30, '...')}}
                </a>
              </div>
            </application-row>
            <application-row>
              <div class="span-1"><label>Statuen</label></div>
              <div class="span-3">
                <a :href="`/download/${data.uuid}/${data.file_bylaws}`" class="anchor-download" target="_blank" title="Download Statuen">
                  {{data.file_bylaws | truncate(30, '...')}}
                </a>
              </div>
            </application-row>
            <application-row>
              <div class="span-1"><label>Projekt</label></div>
              <div class="span-3">
                <a :href="`/download/${data.uuid}/${data.file_project_description}`" class="anchor-download" target="_blank" title="Download Projekt">
                  {{data.file_project_description | truncate(30, '...')}}
                </a>
              </div>
            </application-row>
            <application-row>
              <div class="span-1"><label>KV</label></div>
              <div class="span-3">
                <a :href="`/download/${data.uuid}/${data.file_project_estimated_costs}`" class="anchor-download" target="_blank" title="Download KV">
                  {{data.file_project_estimated_costs | truncate(30, '...')}}
                </a>
              </div>
            </application-row>
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
              <application-label :cls="'span-1'">Dauer</application-label>
              <application-input :cls="'span-3'">
                <input type="text" v-model="data.project_time" required @blur="validate($event)">
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
            <application-row v-if="data.project_add_instit_final_total > 0">
              <div class="span-3"><label class="text-grey">Beiträge Dritter</label></div>
              <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_final_total | currency}}</div>
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
              <application-label :cls="'span-3'">Beantragter Betrag</application-label>
              <application-input :cls="'span-1'">
                <input type="text" v-model="data.project_contribution_requested" class="align-right" required @blur="validate($event)">
              </application-input>
            </application-row>
            <application-row>
              <application-label :cls="'span-3'">Genehmigter Betrag</application-label>
              <application-input :cls="'span-1'">
                <input type="text" v-model="data.project_contribution_approved" class="align-right" required @blur="validate($event)">
              </application-input>
            </application-row>
          </div>
        </application-grid>
      </application-wrapper>
    </form>
  </site-main>
</div>
</template>
<script>
import NProgress from 'nprogress';
import ErrorHandling from "@/mixins/ErrorHandling";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import PageMenu from '@/views/pages/application/components/Menu.vue';
import ApplicationWrapper from '@/views/pages/application/components/Wrapper.vue';
import ApplicationGrid from '@/views/pages/application/components/Grid.vue';
import ApplicationRow from '@/views/pages/application/components/Row.vue';
import ApplicationLabel from '@/views/pages/application/components/Label.vue';
import ApplicationInput from '@/views/pages/application/components/Input.vue';
import FileUpload from 'vue-upload-component';
import vue2Dropzone from "vue2-dropzone";

export default {
  components: {
    NProgress,
    SiteHeader,
    SiteMain,
    PageMenu,
    ApplicationWrapper,
    ApplicationGrid,
    ApplicationRow,
    ApplicationLabel,
    ApplicationInput,
    FileUpload,
    vueDropzone: vue2Dropzone,
  },

  mixins: [ErrorHandling],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {},

      // Settings
      settings: {
        states: [],
      },

      // Validation
      errors: {
        name: false,
      },

      // Routes
      routes: {
        fetch: '/api/application',
        put: '/api/application'
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

      // File upload

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
  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.axios.get(`${this.routes.fetch}/${this.$route.params.uuid}`).then(response => {
        this.data = response.data;
        this.isFetched = true;
      });
    },

    submit() {
      this.isLoading = true;
      this.axios.put(`${this.routes.put}/${this.$route.params.uuid}`, this.data).then(response => {
        this.$router.push({ name: 'application-show', params: {type: this.$route.params.type, uuid: this.data.uuid} });
        this.isLoading = false;
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

    uploadBefore(field) {
      this.field_file = field;
    },

    uploadSending(file, xhr, formData) {
      NProgress.start();
      formData.append('uuid', this.$route.params.uuid);
      formData.append('field', this.field_file);
    },

    uploadSuccess(file, response) {
      let res = JSON.parse(file.xhr.response);
      this.data[this.field_file] = res.name;
      this.$refs.dropzone.removeFile(file);
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
      this.$refs.dropzone.removeFile(file);

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
