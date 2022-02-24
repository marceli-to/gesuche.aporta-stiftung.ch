<template>
<div>
  <site-header 
    :user="$store.state.user"
    class="is-detail">
  </site-header>
  <site-main v-if="isFetched">
    <page-menu />
    <application-wrapper>
      <application-grid>

        <div>
          <h2>Organisation</h2>
          <application-row>
            <div class="span-1"><label>Name</label></div>
            <div class="span-3">{{data.name}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Adresse</label></div>
            <div class="span-3" v-html="data.applicant_address"></div>
          </application-row>
        </div>

        <div>
          <h2>Projekt</h2>
          <application-column>

          </application-column>
        </div>

        <div>
          <h2>Projektkosten und Finanzierung</h2>
          <application-column>

          </application-column>
        </div>
      </application-grid>
    </application-wrapper>

  </site-main>
</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import PageMenu from '@/views/pages/application/partials/Menu.vue';
import ApplicationWrapper from '@/views/pages/application/components/Wrapper.vue';
import ApplicationGrid from '@/views/pages/application/components/Grid.vue';
import ApplicationRow from '@/views/pages/application/components/Row.vue';

export default {
  components: {
    SiteHeader,
    SiteMain,
    PageMenu,
    ApplicationWrapper,
    ApplicationGrid,
    ApplicationRow
  },

  mixins: [ErrorHandling],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {},

      // Routes
      routes: {
        fetch: '/api/application',
      },

      // States
      isFetched: true,
      isLoading: false,

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
  },

};
</script>
