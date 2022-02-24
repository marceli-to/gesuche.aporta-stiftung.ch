<template>
<div v-if="isFetched">

  <page-header 
    :user="$store.state.user"
    class="">
  </page-header>
  
  <page-main>
    <list v-if="data.length">
      <list-row-header>
        <list-item :cls="'span-1 list-item-header'">&nbsp;</list-item>
        <list-item :cls="'span-1 list-item-header'">
          Eingang
          <a href="">[]</a>
        </list-item>
        <list-item :cls="'span-3 list-item-header'">Organisation</list-item>
        <list-item :cls="'span-1 list-item-header'">Beantragt</list-item>
        <list-item :cls="'span-1 list-item-header'">Bewilligt</list-item>
        <list-item :cls="'span-2 list-item-header'">Kontakt</list-item>
        <list-item :cls="'span-2 list-item-header no-line'">E-Mail</list-item>
        <list-item :cls="'span-1 list-item-header flex justify-center'">Status</list-item>
      </list-row-header>
      <list-row v-for="d in data" :key="d.uuid">
        <list-item :cls="'span-1 list-item-bullet'">
          <bullet />
        </list-item>
        <list-item :cls="'span-1 list-item'">
          <a href="">{{ d.created_at }}</a>
        </list-item>
        <list-item :cls="'span-3 list-item'">
          <a href="">{{ d.name }}</a>
        </list-item>
        <list-item :cls="'span-1 list-item'">
          <a href="">{{ d.project_contribution_requested }}</a>
        </list-item>
        <list-item :cls="'span-1 list-item'">
          <a href="">{{ d.project_finance }}</a>
        </list-item>
        <list-item :cls="'span-2 list-item'">
          <a href="">{{ d.applicant_name }}</a>
        </list-item>
        <list-item :cls="'span-2 list-item'">
          <a href="">{{ d.applicant_email }}</a>
        </list-item>
        <list-item :cls="'span-1 list-item-state'">[state]</list-item>
      </list-row>
    </list>
    <list-empty v-else>
      {{messages.emptyData}}
    </list-empty>
  </page-main>

</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import Helpers from "@/mixins/Helpers";
import Bullet from "@/components/ui/misc/Bullet.vue";
import PageHeader from '@/views/layout/Header.vue';
import PageMain from '@/views/layout/Main.vue';
import List from "@/components/ui/layout/List.vue";
import ListRowHeader from "@/components/ui/layout/ListRowHeader.vue";
import ListRow from "@/components/ui/layout/ListRow.vue";
import ListItem from "@/components/ui/layout/ListItem.vue";
import ListAction from "@/components/ui/layout/ListAction.vue";
import ListEmpty from "@/components/ui/layout/ListEmpty.vue";

export default {

  components: {
    PageHeader,
    PageMain,
    Bullet,
    List,
    ListRow,
    ListRowHeader,
    ListItem,
    ListAction,
    ListEmpty,
  },

  mixins: [ErrorHandling, Helpers],

  data() {
    return {

      // Data
      data: [],

      // Routes
      routes: {
        list: '/api/applications',
        toggle: '/api/application/state',
        destroy: '/api/application'
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Gesuche vorhanden...',
        confirmDestroy: 'Bitte löschen bestätigen!',
        updated: 'Status geändert',
      }
    };
  },

  mounted() {
    this.fetch(this.$route.params.type);
  },

  methods: {

    fetch(type) {
      this.axios.get(this.routes.list).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
      });
    },

    show() {
      alert('show');
    },

    destroy(uuid, event) {
      if (confirm(this.messages.confirmDestroy)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.destroy}/${uuid}`).then(response => {
          this.fetch();
          this.isLoading = false;
        });
      }
    },
  },
  watch: {
    '$route'() {
      this.fetch(this.$route.params.type)
    }
  }
}
</script>