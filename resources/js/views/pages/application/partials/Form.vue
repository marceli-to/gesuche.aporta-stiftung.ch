<template>
<div>
  <site-header :user="$store.state.user" class="is-detail"></site-header>
  <site-main v-if="isFetched">
    <page-menu 
      :type="$route.params.type" 
      :uuid="$route.params.uuid" 
      class="has-select"
    ></page-menu>
    <form @submit.prevent="submit" v-if="isFetched">
        
        <div class="form-group">
          <label>Name der Institution <asterisk /></label>
          <input type="text" v-model="data.name">
          <required />
        </div>

        <div class="form-group">
          <label>Rechtsform<asterisk /></label>
          <input type="text" v-model="data.legal_form">
          <required />
        </div>

        <div class="form-group">
          <label>Status</label>
          <select v-model="data.application_state_id">
            <option value="null">Bitte wählen...</option>
            <option :value="s.id" v-for="s in settings.states" :key="s.id">{{s.description}}</option>
          </select>
        </div>

        <div class="form-group">
          <button type="submit" class="btn-primary">Speichern</button>
          <a href="javascript:history.back();" class="form-helper form-helper-footer">
            <span>Zurück</span>
          </a>
        </div>
    </form>
  </site-main>
</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import PageMenu from '@/views/pages/application/components/Menu.vue';
import FormRadio from "@/components/ui/form/Radio.vue";
import Required from "@/components/ui/form/Required.vue";
import Asterisk from "@/components/ui/form/Asterisk.vue";

export default {
  components: {
    SiteHeader,
    SiteMain,
    PageMenu,
    FormRadio,
    Required,
    Asterisk,
  },

  mixins: [ErrorHandling],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {
        name: null,
        legal_form: null,
      },

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

      // Messages
      messages: {
        updated: 'Änderungen gespeichert!',
      },
    };
  },

  created() {
    this.fetch();
    // this.getSettings();
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
        this.$router.push({ name: "applications" });
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },

    // getSettings() {
    //   this.isFetched = false;
    //   this.isLoading = true;
    //   this.axios.all([
    //     this.axios.get(`/api/application-states`),
    //   ]).then(axios.spread((...responses) => {
    //     this.settings = {
    //       states: responses[0].data.data,
    //     };
    //     this.isFetched = true;
    //     this.isLoading = true;
    //   }));
    // },
  },

};
</script>
