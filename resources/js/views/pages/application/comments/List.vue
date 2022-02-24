<template>
<div>
  <site-header :user="$store.state.user" class="is-detail"></site-header>
  <site-main v-if="isFetched">
    <page-menu :type="$route.params.type" :uuid="$route.params.uuid" class="mb-20x" />
    <a 
      href=""
      @click.prevent="showForm()"
      style="font-size: 40px; position: absolute; left: 50%; top: 80px; display: inline-block; height: 20px; width: 20px; transform: translateX(-50%)">
      +
    </a>
    <list v-if="data.length">
      <list-row-header>
        <list-item :cls="'span-1 start-2 list-item-header has-line'">
          Datum
          <a href="" @click.prevent="sort('created_at')">[]</a>
        </list-item>
        <list-item :cls="'span-2 list-item-header'">
          Verfasser / In
          <a href="" @click.prevent="sort('user.full_name')">[]</a>
        </list-item>
        <list-item :cls="'span-2 list-item-header'">
          Betrifft
          <a href="" @click.prevent="sort('subject')">[]</a>
        </list-item>
        <list-item :cls="'span-4 list-item-header'">
          Kommentar
          <a href="" @click.prevent="sort('comment')">[]</a>
        </list-item>
      </list-row-header>

      <list-row class="no-hover" v-if="hasForm">
        <list-item :cls="'span-1 start-2 list-item has-line'">
          <span>{{ created_at }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item'">
          <span>{{ $store.state.user.full_name }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item'">
          <span>
            <input type="text" v-model="form.subject" placeholder="Betreff">
          </span>
        </list-item>
        <list-item :cls="'span-4 list-item'">
          <span>
            <textarea v-model="form.comment" placeholder="Kommentar"></textarea>
          </span>
        </list-item>
      </list-row>

      <list-row class="no-hover" v-for="d in sortedData" :key="d.uuid">
        <list-item :cls="'span-1 start-2 list-item has-line'">
          <span>{{ d.created_at }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item'">
          <span>{{ d.user.full_name }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item'">
          <span>{{ d.subject }}</span>
        </list-item>
        <list-item :cls="'span-4 list-item'">
          <span>{{ d.comment }}</span>
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
import PageMenu from '@/views/pages/application/partials/Menu.vue';
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

      // Form data
      form: {
        subject: null,
        comment: null,
      },
      created_at: new Date().toLocaleDateString("de", {year:"numeric", day:"2-digit", month:"2-digit"}),


      // Routes
      routes: {
        list: '/api/application-comments',
      },

      // States
      isLoading: false,
      isFetched: false,
      hasForm: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Kommentare vorhanden...',
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

    showForm() {
      this.hasForm = true;
    },

    hideForm() {
      this.hasForm = false;
    }

    // destroy(uuid, event) {
    //   if (confirm(this.messages.confirmDestroy)) {
    //     this.isLoading = true;
    //     this.axios.delete(`${this.routes.destroy}/${uuid}`).then(response => {
    //       this.fetch();
    //       this.isLoading = false;
    //     });
    //   }
    // },
  },
}
</script>