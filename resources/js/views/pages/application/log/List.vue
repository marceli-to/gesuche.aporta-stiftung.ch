<template>
<div>
  <site-header :user="$store.state.user" class="is-detail"></site-header>
  <site-main v-if="isFetched">
    <page-menu 
      :type="$route.params.type" 
      :uuid="$route.params.uuid" 
      class="mb-15x has-selection"
    ></page-menu>
    <list v-if="data.length || hasForm">

      <!-- header -->
      <list-row-header>
        <list-item :cls="'span-2 start-3 list-item-header line-after'">
          Datum
          <a href="" @click.prevent="sort('created_at')" v-if="sortedData.length > 1">[]</a>
        </list-item>
        <list-item :cls="'span-2 list-item-header line-after'">
          Benutzer
          <a href="" @click.prevent="sort('user.full_name')" v-if="sortedData.length > 1">[]</a>
        </list-item>
        <list-item :cls="'span-4 list-item-header'">
          Aktion
          <a href="" @click.prevent="sort('action')" v-if="sortedData.length > 1">[]</a>
        </list-item>
      </list-row-header>

      <!-- data -->
      <list-row class="no-hover" v-for="d in sortedData" :key="d.uuid">
        <list-item :cls="'span-2 start-3 list-item  line-after'">
          <span>{{ d.created_at }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item line-after'">
          <span>{{ d.user.full_name }}</span>
        </list-item>
        <list-item :cls="'span-4 list-item'">
          <span>{{ messages.actions[d.action] }}</span>
        </list-item>
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
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import PageMenu from '@/views/pages/application/components/Menu.vue';
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
    PageMenu,
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
        list: '/api/application-log',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Einträge vorhanden...',
        actions: {
          archived: 'Gesuch archiviert',
          saved: 'Gesuch gespeichert',
          deleted: 'Gesuch gelöscht',
          approved: 'Gesuch bewilligt',
          denied: 'Gesuch abgelehnt',
        }
      }
    };
  },

  mounted() {
    this.fetch(this.$route.params.uuid);
  },

  methods: {

    fetch(uuid) {
      this.isFetched = false;
      this.axios.get(`${this.routes.list}/${uuid}/`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
      });
    },
  },

}
</script>