<template>
<div>
  <site-header :user="$store.state.user" class="is-detail"></site-header>
  <site-main v-if="isFetched">
    <page-menu 
      :type="$route.params.type" 
      :uuid="$route.params.uuid" 
      class="mb-20x has-selection"
    ></page-menu>
    <a 
      href=""
      @click.prevent="showForm()"
      style="font-size: 40px; position: absolute; left: 50%; top: 80px; display: inline-block; height: 20px; width: 20px; transform: translateX(-50%)">
      +
    </a>

    <list v-if="data.length || hasForm">

      <!-- header -->
      <list-row-header>
        <list-item :cls="'span-1 start-2 list-item-header line-after'">
          Datum
          <a href="" @click.prevent="sort('created_at')" v-if="sortedData.length > 1">[]</a>
        </list-item>
        <list-item :cls="'span-2 list-item-header line-after'">
          Verfasser / In
          <a href="" @click.prevent="sort('user.full_name')" v-if="sortedData.length > 1">[]</a>
        </list-item>
        <list-item :cls="'span-2 list-item-header line-after'">
          Betrifft
          <a href="" @click.prevent="sort('subject')" v-if="sortedData.length > 1">[]</a>
        </list-item>
        <list-item :cls="'span-4 list-item-header'">
          Kommentar
          <a href="" @click.prevent="sort('comment')" v-if="sortedData.length > 1">[]</a>
        </list-item>
      </list-row-header>

      <!-- form -->
      <list-row :class="[sortedData.length > 0 ? 'mb-6x' : '', 'no-hover']" v-if="hasForm">
        <list-item :cls="'span-1 start-2 list-item  line-after'">
          <span>{{ created_at }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item line-after'">
          <span>{{ $store.state.user.full_name }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item line-after'">
          <span>
            <input type="text" v-model="form.subject" placeholder="Betreff" required @blur="validate()">
          </span>
        </list-item>
        <list-item :cls="'span-4 list-item'">
          <span>
            <textarea v-model="form.comment" placeholder="Kommentar" @blur="validate()"></textarea>
          </span>
          <div>
            <button type="submit" @click.prevent="store()" disabled class="btn-primary is-small mb-3x">
              <span>Kommentar speichern</span>
            </button>
            <a href="" @click.prevent="reset()" class="btn-secondary is-small is-outline">
              <span>Abbrechen</span>
            </a>
          </div>
        </list-item>
      </list-row>

      <!-- data -->
      <list-row class="no-hover" v-for="d in sortedData" :key="d.uuid">
        <list-item :cls="'span-1 start-2 list-item  line-after'">
          <span>{{ d.created_at }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item line-after'">
          <span>{{ d.user.full_name }}</span>
        </list-item>
        <list-item :cls="'span-2 list-item line-after'">
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

      // Form data
      form: {
        subject: '',
        comment: '',
        uuid: this.$route.params.uuid,
      },
      created_at: new Date().toLocaleDateString("de", {year:"numeric", day:"2-digit", month:"2-digit"}),


      // Routes
      routes: {
        list: '/api/application-comments',
        post: '/api/application-comment',
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

    store() {
      this.isLoading = true;
      this.axios.post(this.routes.post, this.form).then(response => {
        this.isLoading = false;
        this.reset();
        this.fetch(this.$route.params.uuid);
      });
    },

    reset() {
      this.form.subject = '';
      this.form.comment = '';
      this.hideForm();
    },

    showForm() {
      this.hasForm = true;
    },

    hideForm() {
      this.hasForm = false;
    },

    validate() {
      let btn = document.querySelector('button[type="submit"]');
      if (this.form.subject.length > 0 && this.form.comment.length > 0) {
        btn.disabled = false;
        return true;
      }
      btn.disabled = true;
      return false;
    }
  },

}
</script>