<template>
<div v-if="isFetched">
  <list v-if="data.length">
    <list-row v-for="d in data" :key="d.uuid" class="">
      <list-item :cls="'span-1 flex justify-center list-item'">
        <bullet />
      </list-item>
      <list-item :cls="'span-1 list-item-line'">{{ d.created_at }}</list-item>
      <list-item :cls="'span-3 list-item-line'">{{ d.name }}</list-item>
      <list-item :cls="'span-1 list-item-line'">{{ d.project_contribution_requested }}</list-item>
      <list-item :cls="'span-1 list-item-line'">{{ d.project_finance }}</list-item>
      <list-item :cls="'span-2 list-item-line'">{{ d.applicant_name }}</list-item>
      <list-item :cls="'span-2 list-item-line'">{{ d.applicant_email }}</list-item>
      <list-item :cls="'span-1 flex justify-center list-item'">[state]</list-item>
      <!-- <list-action>
        <router-link :to="{name: 'application-update', params: { uuid: d.uuid }}">
          [bearbeiten]
        </router-link>
        <a href="" @click.prevent="destroy(d.uuid)">
          [löschen]
        </a>
      </list-action> -->
    </list-row>
  </list>
  <list-empty v-else>
    {{messages.emptyData}}
  </list-empty>
</div>
</template>
<script>

import ErrorHandling from "@/mixins/ErrorHandling";
import Helpers from "@/mixins/Helpers";
import Bullet from "@/components/ui/misc/Bullet.vue";
import List from "@/components/ui/layout/List.vue";
import ListRow from "@/components/ui/layout/ListRow.vue";
import ListItem from "@/components/ui/layout/ListItem.vue";
import ListAction from "@/components/ui/layout/ListAction.vue";
import ListEmpty from "@/components/ui/layout/ListEmpty.vue";

export default {

  components: {
    Bullet,
    List,
    ListRow,
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