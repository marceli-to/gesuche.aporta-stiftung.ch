<template>
<div>
  <site-header 
    :user="$store.state.user"
    class="">
  </site-header>
  <site-main v-if="isFetched">
    <list v-if="data.length">
      <list-row-header>
        <list-item :cls="'span-1 list-item-header'">&nbsp;</list-item>
        <list-item :cls="'span-1 list-item-header'">
          Eingang
          <a href="" @click.prevent="sort('created_at')">[]</a>
        </list-item>
        <list-item :cls="'span-3 list-item-header'">
          Organisation
          <a href="" @click.prevent="sort('name')">[]</a>
        </list-item>
        <list-item :cls="'span-1 list-item-header'">
          Beantragt
          <a href="" @click.prevent="sort('project_contribution_requested')">[]</a>
        </list-item>
        <list-item :cls="'span-1 list-item-header'">
          Bewilligt
          <a href="" @click.prevent="sort('project_finance')">[]</a>
        </list-item>
        <list-item :cls="'span-2 list-item-header'">Kontakt</list-item>
        <list-item :cls="'span-2 list-item-header no-line'">E-Mail</list-item>
        <list-item :cls="'span-1 list-item-header flex justify-center'">Status</list-item>
      </list-row-header>
      <list-row v-for="d in sortedData" :key="d.uuid">
        <list-item :cls="'span-1 list-item-bullet'">
          <bullet />
        </list-item>
        <list-item :cls="'span-1 list-item'">
          <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: d.uuid }}">
            {{ d.created_at }}
          </router-link>
        </list-item>
        <list-item :cls="'span-3 list-item'">
          <a href="">{{ d.name }}</a>
        </list-item>
        <list-item :cls="'span-1 list-item'">
          <a href="">{{ d.project_contribution_requested | currency }}</a>
        </list-item>
        <list-item :cls="'span-1 list-item'">
          <a href="">{{ d.project_finance | currency }}</a>
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
  </site-main>
</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import Helpers from "@/mixins/Helpers";
import Sort from "@/mixins/Sort";
import Bullet from "@/components/ui/misc/Bullet.vue";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import List from "@/components/ui/layout/List.vue";
import ListRowHeader from "@/components/ui/layout/ListRowHeader.vue";
import ListRow from "@/components/ui/layout/ListRow.vue";
import ListItem from "@/components/ui/layout/ListItem.vue";
import ListAction from "@/components/ui/layout/ListAction.vue";
import ListEmpty from "@/components/ui/layout/ListEmpty.vue";

export default {

  components: {
    SiteHeader,
    SiteMain,
    Bullet,
    List,
    ListRow,
    ListRowHeader,
    ListItem,
    ListAction,
    ListEmpty,
  },

  mixins: [ErrorHandling, Helpers, Sort],

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
      this.isFetched = false;
      this.axios.get(`${this.routes.list}/${type}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
      });
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