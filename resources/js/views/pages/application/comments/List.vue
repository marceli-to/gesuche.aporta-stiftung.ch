<template>
<div>
  <site-header 
    :user="$store.state.user" 
    :view="'show'">
  </site-header>
  <site-main v-if="isFetched">
    <page-menu 
      :type="$route.params.type" 
      :uuid="$route.params.uuid" 
      :application="applicationData"
      class="mb-20x has-selection"
    ></page-menu>
    <a 
      href="javascript:;"
      @click.prevent="showForm()"
      class="icon-plus">
      <icon-plus />
    </a>

    <list v-if="data.length || hasForm">

      <!-- header -->
      <list-row-header>
        <list-item :cls="'span-1 start-2 list-item-header line-after'">
          Datum
          <a href="" @click.prevent="sort('created_at_timestamp')" v-if="sortedData.length > 1">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-2 list-item-header line-after'">
          Verfasser / In
          <a href="" @click.prevent="sort('user.full_name')" v-if="sortedData.length > 1">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-2 list-item-header line-after'">
          Betrifft
          <a href="" @click.prevent="sort('subject')" v-if="sortedData.length > 1">
            <icon-sort />
          </a>
        </list-item>
        <list-item :cls="'span-4 list-item-header'">
          Kommentar
          <a href="" @click.prevent="sort('comment')" v-if="sortedData.length > 1">
            <icon-sort />
          </a>
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
            <input type="text" v-model="form.subject" placeholder="Betreff" required @mouseleave="validate()">
          </span>
        </list-item>
        <list-item :cls="'span-4 list-item'">
          <span>
            <textarea v-model="form.comment" placeholder="Kommentar" @mouseleave="validate()"></textarea>
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
        <list-item :cls="'span-1'" v-if="user.id === d.user.id">
          <span>
            <a 
              href="javascript:;" 
              @click.prevent="beforeDeleteComment(d.uuid)"
              class="icon-trash ml-3x">
              <icon-trash />
            </a>
          </span>
        </list-item>
      </list-row>
    </list>

    <list-empty v-else>
      {{messages.emptyData}}
    </list-empty>

    <dialog-wrapper ref="dialogDestroyComment">
      <template #message>
        <div><strong>Möchten Sie diesen Kommentar wirklich löschen?</strong></div>
      </template>
      <template #actions>
        <a href="javascript:;" class="btn-primary mb-3x" @click.stop="deleteComment()">Ja, löschen</a>
      </template>
    </dialog-wrapper>

  </site-main>
</div>
</template>
<script>
import NProgress from 'nprogress';
import ErrorHandling from "@/mixins/ErrorHandling";
import Helpers from "@/mixins/Helpers";
import Sort from "@/mixins/Sort";
import IconSort from "@/components/ui/icons/Sort.vue";
import IconPlus from "@/components/ui/icons/Plus.vue";
import IconTrash from "@/components/ui/icons/Trash.vue";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import PageMenu from '@/components/layout/Menu.vue';
import List from "@/components/ui/layout/List.vue";
import ListRowHeader from "@/components/ui/layout/ListRowHeader.vue";
import ListRow from "@/components/ui/layout/ListRow.vue";
import ListItem from "@/components/ui/layout/ListItem.vue";
import ListAction from "@/components/ui/layout/ListAction.vue";
import ListEmpty from "@/components/ui/layout/ListEmpty.vue";
import DialogWrapper from "@/components/ui/misc/Dialog.vue";

export default {

  components: {
    NProgress,
    SiteHeader,
    SiteMain,
    PageMenu,
    IconSort,
    IconPlus,
    IconTrash,
    List,
    ListRow,
    ListRowHeader,
    ListItem,
    ListAction,
    ListEmpty,
    DialogWrapper
  },

  mixins: [ErrorHandling, Helpers, Sort],

  data() {
    return {

      // Data
      data: [],

      // Application data
      applicationData: {},

      // User data
      user: {},

      commentUuid: null,

      // Form data
      form: {
        subject: '',
        comment: '',
        uuid: this.$route.params.uuid,
      },
      created_at: new Date().toLocaleDateString("de", {year:"numeric", day:"2-digit", month:"2-digit"}),


      // Routes
      routes: {
        fetch: '/api/application',
        list: '/api/application-comments',
        post: '/api/application-comment',
        delete: '/api/application-comment',
        user: {
          fetch: '/api/user',
        }
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
    NProgress.configure({ showBar: false });
  },

  methods: {


    fetch(uuid) {
      this.isFetched = false;
      NProgress.start();
      this.axios.all([
        this.axios.get(`${this.routes.list}/${uuid}`),
        this.axios.get(`${this.routes.fetch}/${uuid}`),
        this.axios.get(`${this.routes.user.fetch}`)
      ]).then(axios.spread((...responses) => {
        this.data = responses[0].data.data;
        this.applicationData = responses[1].data
        this.user = responses[2].data;
        this.isFetched = true;
        console.log(this.user);
        NProgress.done();
      }));
    },

    store() {
      this.isLoading = true;
      NProgress.start();
      this.axios.post(this.routes.post, this.form).then(response => {
        this.isLoading = false;
        this.reset();
        this.fetch(this.$route.params.uuid);
        NProgress.done();
      });
    },

    deleteComment() {
      this.isLoading = true;
      NProgress.start();
      this.axios.delete(`${this.routes.delete}/${this.commentUuid}`).then(response => {
        this.isLoading = false;
        this.commentUuid = null;
        this.$refs.dialogDestroyComment.hide();
        this.fetch(this.$route.params.uuid);
        NProgress.done();
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
    },

    beforeDeleteComment(uuid) {
      this.commentUuid = uuid;
      this.$refs.dialogDestroyComment.show();
    },
  },

}
</script>