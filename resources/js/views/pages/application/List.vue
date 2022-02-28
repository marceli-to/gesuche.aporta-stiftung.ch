<template>
<div>
  <site-header :user="$store.state.user">
    <nav class="site-filter" v-if="hasFilter && isFetchedStates">
      <div>
        <div class="grid-cols-12">
          <div class="span-4 start-2">
            <h2>Status</h2>
            <div v-for="state in dataStates" :key="state.id">
              <a href="" style="color: #fff" @click.prevent="setFilter('stateId', state.id)">{{state.description}}</a><br>
            </div>
          </div>
          <div class="span-4">
            <h2>Betrag</h2>
            <a href="" style="color: #fff" @click.prevent="setFilter('amount', 'lt:20000')">&lt; 20000</a><br>
            <a href="" style="color: #fff" @click.prevent="setFilter('amount', 'gt:20000')">&gt; 20000</a><br>
            <a href="" style="color: #fff" @click.prevent="setFilter('amount', 'gt:30000')">&gt; 30000</a><br>
          </div>
        </div>
      </div>
    </nav>
  </site-header>
  <site-main v-if="isFetched">
    <list v-if="data.length">
      <list-row-header>
        <list-item :cls="'span-1 list-item-header'">&nbsp;</list-item>
        <list-item :cls="'span-1 list-item-header line-after'">
          Eingang
          <a href="" @click.prevent="sort('created_at')">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-3 list-item-header line-after'">
          Organisation
          <a href="" @click.prevent="sort('name')">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-1 list-item-header line-after'">
          Beantragt
          <a href="" @click.prevent="sort('project_contribution_requested')">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-1 list-item-header line-after'">
          Bewilligt
          <a href="" @click.prevent="sort('project_finance')">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-2 list-item-header line-after'">Kontakt</list-item>
        <list-item :cls="'span-2 list-item-header'">E-Mail</list-item>
        <list-item :cls="'span-1 list-item-header flex justify-center'">Status</list-item>
      </list-row-header>
      <list-row v-for="d in sortedData" :key="d.uuid">
        <list-item :cls="'span-1 list-item-bullet'">
          <bullet />
        </list-item>
        <list-item :cls="'span-1 list-item line-after'">
          <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: d.uuid }}">
            {{ d.created_at }}
          </router-link>
        </list-item>
        <list-item :cls="'span-3 list-item line-after'">
          <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: d.uuid }}">
            {{ d.name }}
          </router-link>
        </list-item>
        <list-item :cls="'span-1 list-item line-after'">
          <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: d.uuid }}">
            {{ d.project_contribution_requested | currency }}
          </router-link>
        </list-item>
        <list-item :cls="'span-1 list-item line-after'">
          <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: d.uuid }}">
            {{ d.project_finance | currency }}
          </router-link>
        </list-item>
        <list-item :cls="'span-2 list-item line-after'">
          <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: d.uuid }}">
            {{ d.applicant_name }}
          </router-link>
        </list-item>
        <list-item :cls="'span-2 list-item line-after'">
          <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: d.uuid }}">
            {{ d.applicant_email }}
          </router-link>
        </list-item>
        <list-item :cls="'span-1 list-item-state'">
          <icon-state :id="d.application_state_id" />
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
import NProgress from 'nprogress';
import ErrorHandling from "@/mixins/ErrorHandling";
import Helpers from "@/mixins/Helpers";
import Sort from "@/mixins/Sort";
import IconSort from "@/components/ui/icons/Sort.vue";
import IconState from "@/components/ui/icons/State.vue";
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
    NProgress,
    SiteHeader,
    SiteMain,
    Bullet,
    IconSort,
    IconState,
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

      // Data states
      dataStates: [],

      // Routes
      routes: {
        list: '/api/applications',
        filter: '/api/applications/filter',
        listStates: '/api/application-states',
        toggle: '/api/application/state',
        destroy: '/api/application'
      },

      // States
      isFetched: false,
      isFetchedStates: false,
      hasFilter: false,
      hasFilterResult: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Gesuche vorhanden...',
        confirmDestroy: 'Bitte löschen bestätigen!',
        updated: 'Status geändert',
      },
    };
  },

  mounted() {
    NProgress.configure({ showBar: false });
    this.beforeFetch(this.$route.params.type)
    this.getStates();
  },

  methods: {

    beforeFetch(type) {
      if (type == 'aktuell' && this.$store.state.filter.hasFilter) {
        this.filter(type);
        return;
      }
      this.fetch(type)
    },

    fetch(type) {
      this.isFetched = false;
      NProgress.start();
      this.axios.get(`${this.routes.list}/${type}`).then(response => {
        this.data = response.data.data;
        console.log(this.data);
        this.isFetched = true;
        NProgress.done();
      });
    },

    filter() {
      this.isFetched = false;
      NProgress.start();
      this.axios.get(`${this.routes.filter}/stateId/${this.$store.state.filter.stateId}/amount/${this.$store.state.filter.amount}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
        NProgress.done();
      });
    },

    getStates() {
      this.isFetchedStates = false;
      NProgress.start();
      this.axios.get(`${this.routes.listStates}`).then(response => {
        this.dataStates = response.data.data;
        this.isFetchedStates = true;
        NProgress.done();
      });
    },

    toggleFilter() {
      this.hasFilter = this.hasFilter ? false : true;
    },

    hideFilter() {
      this.hasFilter = false;
    },

    setFilter(type, value) {
      let filter = this.$store.state.filter;
      filter[type] = value;
      filter['hasFilter'] = true;
      this.$store.commit('filter', filter);
      this.hideFilter();
      this.filter();
      console.log(this.$store.state.filter);
    }

  },
  watch: {
    '$route'() {
      this.beforeFetch(this.$route.params.type)
      this.hasFilter = false;
    }
  }
}
</script>