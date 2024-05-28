<template>
<div>
  <site-header :user="$store.state.user">
    <nav class="selector" v-if="hasFilter && isFetchedAttributes">
      <div>
        <div class="grid-cols-12">
          <div :class="[type == 'aktuell' ? 'span-4 start-2' : 'span-4 start-1', '']">
            <div :class="[this.searchTerm ? 'is-disabled' : '', '']">
              <h2>Status</h2>
              <div class="grid-cols-2">
                <div v-for="state in dataStates" :key="state.id">
                  <a href="javascript:;" @click.prevent="setFilterItem('state', state.id)">
                    <icon-radio-active v-if="$store.state.filter.state == state.id" />
                    <icon-radio v-else />
                    <span>{{state.description}}</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div :class="[type == 'aktuell' ? 'span-2 start-7' : 'span-2 start-6', '']">
            <div :class="[this.searchTerm ? 'is-disabled' : '', '']">
              <h2>Betrag</h2>
              <div>
                <a href="" @click.prevent="setFilterItem('amount', 'lt:20000')">
                  <icon-radio-active v-if="$store.state.filter.amount == 'lt:20000'" />
                  <icon-radio v-else />
                  <span>&lt; 20000</span>
                </a>
                <a href="" @click.prevent="setFilterItem('amount', 'gt:20000')">
                  <icon-radio-active v-if="$store.state.filter.amount == 'gt:20000'" />
                  <icon-radio v-else />
                  <span>&gt; 20000</span>
                </a>
              </div>
            </div>
          </div>
          <template v-if="type == 'archiv'">
            <div :class="[this.searchTerm ? 'is-disabled' : '', 'span-2']">
              <h2>Jahr</h2>
              <div v-for="(year, index) in dataYears" :key="index">
                <a href="javascript:;" @click.prevent="setFilterItem('year', year)">
                  <icon-radio-active v-if="$store.state.filter.year == year" />
                  <icon-radio v-else />
                  <span>{{year}}</span>
                </a>
              </div>
            </div>
          </template>
          <div :class="[type == 'aktuell' ? 'span-3' : 'span-3', '']">
            <div :class="[$store.state.filter.set ? 'is-disabled' : '', '']">
              <h2>Suche</h2>
              <div>
                <input type="text" v-model="searchTerm" class="search" placeholder="Organisation, Name oder E-Mail" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <template v-if="searchTerm == null">
        <a 
          href="javascript:;" 
          :class="[$store.state.filter.set ? 'is-active' : '', 'btn-primary is-filter']" 
          @click.prevent="hideFilter()">
          Anzeigen
        </a>
        <a 
          href="javascript:;" 
          class="btn-secondary is-outline" 
          @click.prevent="resetFilter()">
          Zurücksetzen
        </a>
      </template>
      <template v-else>
        <a 
          href="javascript:;" 
          :class="[this.searchTerm ? 'is-active' : '', 'btn-primary is-filter']" 
          @click.prevent="doSearch()">
          Suchen
        </a>
        <a 
          href="javascript:;" 
          class="btn-secondary is-outline" 
          @click.prevent="resetSearch()">
          Zurücksetzen
        </a>
      </template>
    </nav>
    <nav class="selector" v-if="hasSelectorCurrent">
      <div>
        <div class="grid-cols-12">
          <div class="span-3 start-2">
            <h2>Zusagen/Absagen</h2>
            <div>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'reply_approved')">
                <icon-radio-active v-if="$store.state.selector.type == 'reply_approved'" />
                <icon-radio v-else />
                <span>Genehmigte</span>
              </a>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'reply_denied')">
                <icon-radio-active v-if="$store.state.selector.type == 'reply_denied'" />
                <icon-radio v-else />
                <span>Abgelehnte</span>
              </a>
            </div>
          </div>
          <div class="span-3">
            <h2>Archivieren</h2>
            <div>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'archive_all')">
                <icon-radio-active v-if="$store.state.selector.type == 'archive_all'" />
                <icon-radio v-else />
                <span>Alle Gesuche</span>
              </a>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'archive_approved')">
                <icon-radio-active v-if="$store.state.selector.type == 'archive_approved'" />
                <icon-radio v-else />
                <span>Genehmigte</span>
              </a>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'archive_denied')">
                <icon-radio-active v-if="$store.state.selector.type == 'archive_denied'" />
                <icon-radio v-else />
                <span>Abgelehnte</span>
              </a>
            </div>
          </div>
          <div class="span-3">
            <h2>Exportieren</h2>
            <div>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'export_all')">
                <icon-radio-active v-if="$store.state.selector.type == 'export_all'" />
                <icon-radio v-else />
                <span>Alle Gesuche</span>
              </a>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'export_new')">
                <icon-radio-active v-if="$store.state.selector.type == 'export_new'" />
                <icon-radio v-else />
                <span>Neue</span>
              </a>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'export_approved')">
                <icon-radio-active v-if="$store.state.selector.type == 'export_approved'" />
                <icon-radio v-else />
                <span>Genehmigte</span>
              </a>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'export_denied')">
                <icon-radio-active v-if="$store.state.selector.type == 'export_denied'" />
                <icon-radio v-else />
                <span>Abgelehnte</span>
              </a>
            </div>
          </div>
        </div>
      </div>
        <a 
          :href="`/export/${$store.state.selector.type}/false/?v=${cachebuster}`" 
          target="_blank"
          class="btn-primary is-filter" 
          @click="hideSelector()"
          v-if="$store.state.selector.type && $store.state.selector.type.includes('export_')">
          Exportieren
        </a>
        <a
          :href="`/brief/${$store.state.selector.type}?v=${cachebuster}`" 
          target="_blank"
          class="btn-primary is-filter" 
          @click="hideSelector()"
          v-else>
          Exportieren
        </a>
        <a 
          href="javascript:;" 
          class="btn-secondary is-outline" 
          @click.prevent="resetSelector()">
          Abbrechen
        </a>
    </nav>
    <nav class="selector" v-if="hasSelectorArchive">
      <div>
        <div class="grid-cols-12">
          <div class="span-3 start-2">
            <h2>Exportieren</h2>
            <div>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'export_all')">
                <icon-radio-active v-if="$store.state.selector.type == 'export_all'" />
                <icon-radio v-else />
                <span>Alle Gesuche</span>
              </a>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'export_approved')">
                <icon-radio-active v-if="$store.state.selector.type == 'export_approved'" />
                <icon-radio v-else />
                <span>Genehmigte</span>
              </a>
              <a href="javascript:;" @click.prevent="setSelectorItem('type', 'export_denied')">
                <icon-radio-active v-if="$store.state.selector.type == 'export_denied'" />
                <icon-radio v-else />
                <span>Abgelehnte</span>
              </a>
            </div>
          </div>
          <div class="span-3">
            <h2>Jahr</h2>
            <div v-for="(year, index) in dataYears" :key="index">
              <a href="javascript:;" @click.prevent="setSelectorItem('year', year)">
                <icon-radio-active v-if="$store.state.selector.year == year" />
                <icon-radio v-else />
                <span>{{year}}</span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <a 
        :href="`/export/${$store.state.selector.type}/true/${$store.state.selector.year}?v=${cachebuster}`" 
        target="_blank"
        :class="[!$store.state.selector.type ? 'is-disabled': '', 'btn-primary is-filter']" 
        @click="hideSelector()">
        Exportieren
      </a>
      <a 
        href="javascript:;" 
        class="btn-secondary is-outline" 
        @click.prevent="resetSelector()">
        Abbrechen
      </a>
    </nav>
  </site-header>
  <site-main v-if="isFetched">
    <list v-if="data.length">
      <list-row-header>
        <list-item :cls="'span-1 list-item-header'">&nbsp;</list-item>
        <list-item :cls="'span-1 list-item-header line-after'">
          Eingang
          <a href="" @click.prevent="sort('created_at_timestamp')">
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
          <a href="" @click.prevent="sort('requested_contribution')">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-1 list-item-header line-after'">
          Bewilligt
          <a href="" @click.prevent="sort('project_contribution_approved')">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-2 list-item-header line-after'">Kontakt</list-item>
        <list-item :cls="'span-2 list-item-header'">E-Mail</list-item>
        <list-item :cls="'span-1 list-item-header flex direction-column align-center'">
          <div>
            Status
            <a href="" @click.prevent="sort('application_state_id')">
              <icon-sort />
            </a>
          </div>
        </list-item>
      </list-row-header>
      <list-row v-for="d in sortedData" :key="d.uuid" :class="[isNew(d) ? 'is-new' : '']">
        <list-item :cls="'span-1 list-item-bullet'">
          <bullet v-if="isNew(d)" />
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
            {{ d.project_contribution_approved | currency }}
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
        <list-item :cls="'span-1 list-item-state flex justify-center'">
          <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: d.uuid }}" class="icon-state">
            <icon-state :id="d.application_state_id" />
          </router-link>
        </list-item>
      </list-row>
    </list>
    <list-empty v-else>
      <template v-if="searchTerm">
        {{messages.noSearchResults}}
      </template>
      <template v-else>
        {{messages.emptyData}}
      </template>
    </list-empty>
  </site-main>
</div>
</template>
<script>
import NProgress from 'nprogress';
import ErrorHandling from "@/mixins/ErrorHandling";
import Helpers from "@/mixins/Helpers";
import Sort from "@/mixins/Sort";
import Filter from "@/views/pages/application/mixins/Filter";
import Selector from "@/views/pages/application/mixins/Selector";
import IconSort from "@/components/ui/icons/Sort.vue";
import IconState from "@/components/ui/icons/State.vue";
import IconRadio from "@/components/ui/icons/Radio.vue";
import IconRadioActive from "@/components/ui/icons/RadioActive.vue";
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
    IconRadio,
    IconRadioActive,
    List,
    ListRow,
    ListRowHeader,
    ListItem,
    ListAction,
    ListEmpty,
  },

  mixins: [ErrorHandling, Helpers, Sort, Filter, Selector],

  data() {
    return {

      // Data
      data: [],

      // Search term
      searchTerm: null,

      // Data states
      dataStates: [],

      // Data years
      dataYears: [],

      // Type (archive, current)
      type: 'current',

      // Routes
      routes: {
        list: '/api/applications',
        search: '/api/applications/search',
        listFilter: '/api/applications/filter',
        listStates: '/api/application-states',
        listYears: '/api/application-years',
        toggle: '/api/application/state',
        destroy: '/api/application'
      },

      // States
      isFetched: false,
      isFetchedAttributes: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Gesuche vorhanden...',
        noSearchResults: 'Keine Suchresultate gefunden...',
        confirmDestroy: 'Bitte löschen bestätigen!',
        updated: 'Status geändert',
      },
    };
  },

  mounted() {
    NProgress.configure({ showBar: false });
    this.type = this.$route.params.type;
    this.beforeFetch(this.type)
    this.fetchAttributes();

    // if searchTerm is not null, call doSearch on enter key press
    window.addEventListener('keyup', (e) => {
      if (this.searchTerm && e.keyCode === 13) {
        this.doSearch();
      }
    });
  },

  methods: {

    beforeFetch(type) {

      // Check if searchTerm in store is not null and search
      if (this.$store.state.searchTerm) {
        this.searchTerm = this.$store.state.searchTerm;
        this.doSearch();
        return;
      }

      if (type == 'aktuell' && this.$store.state.filter.set) {
        this.fetchFiltered(type);
        return;
      }
      this.fetch(type)
    },

    fetch(type) {
      this.isFetched = false;
      NProgress.start();
      this.axios.get(`${this.routes.list}/${type}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
        NProgress.done();
      });
    },

    fetchAttributes() {
      this.isFetchedAttributes = false;
      NProgress.start();
      this.axios.all([
        this.axios.get(`${this.routes.listStates}`),
        this.axios.get(`${this.routes.listYears}`)
      ]).then(this.axios.spread((states, years) => {
        this.dataStates = states.data.data;
        this.dataYears = years.data;
        this.isFetchedAttributes = true;
        NProgress.done();
      }));
    },

    fetchFiltered() {
      let param = {
        state: this.$store.state.filter.state ? this.$store.state.filter.state : null,
        amount: this.$store.state.filter.amount ? this.$store.state.filter.amount : null,
        year: this.$store.state.filter.year ? this.$store.state.filter.year : null,
      };
      NProgress.start();
      this.isFetched = false;
      this.axios.post(`${this.routes.listFilter}/${this.type}`, param).then(response => {
        this.data = response.data.data;
        this.setFilterMenu(this.data);
        this.isFetched = true;
        NProgress.done();
      });
    },

    doSearch() {
      if (this.searchTerm) {
        this.isFetched = false;
        NProgress.start();
        this.axios.get(`${this.routes.search}/${this.searchTerm}/${this.type}`).then(response => {
          this.data = response.data.data;
          this.$store.commit('searchTerm', this.searchTerm);
          this.isFetched = true;
          this.hideFilter();
          NProgress.done();
        });
      }
    },

    resetSearch() {
      this.searchTerm = null;
      this.$store.commit('searchTerm', null);
      this.resetFilter();
      this.fetch('aktuell');
    },

    isNew(application) {
      if (application.users) {
        if (application.users.length == 0) {
          return true;
        }
        const index = application.users.findIndex(x => x.user_uuid === this.$store.state.user.uuid);
        return index > -1 ? false : true;
      }
    },

  },
  watch: {
    '$route'() {
      this.type = this.$route.params.type;
      this.beforeFetch(this.type)
    },
    
    searchTerm() {
      if (this.searchTerm === '') {
        this.searchTerm = null;
        this.$store.commit('searchTerm', null);
        this.fetch(this.type);
      }
    }
  },
}
</script>