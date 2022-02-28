<template>
<div class="mb-15x">
  <nav class="page-menu">
    <ul>
      <li v-if="type == 'archive'">
        <router-link :to="{name: 'applications-archive', params: { type: $props.type }}">
          <span>Zurück</span>
        </router-link>
      </li>
      <li v-else>
        <router-link :to="{name: 'applications-current', params: { type: $props.type }}">
          <span>Zurück</span>
        </router-link>
      </li>
      <li>
        <router-link :to="{name: 'application-edit', params: { type: $props.type, uuid: $props.uuid }}" :active-class="'is-active'">
          <span>Bearbeiten</span>
        </router-link>
      </li>
      <li>
        <router-link :to="{name: 'application-comments', params: { type: $props.type, uuid: $props.uuid }}" :active-class="'is-active'">
          <span>Kommentar</span>
        </router-link>
      </li>
      <li>
        <router-link :to="{name: 'application-logs', params: { type: $props.type, uuid: $props.uuid }}" :active-class="'is-active'">
          <span>Protokoll</span>
        </router-link>
      </li>

      <li v-if="$props.application.archive == 0">
        <a href="javascript:;" @click.prevent="$refs.dialogArchive.show()">
          <span>Archivieren</span>
        </a>
      </li>
      <li v-else>
        <a href="javascript:;" @click.prevent="$refs.dialogRestore.show()">
          <span>Wiederherstellen</span>
        </a>
      </li>
      <li>
        <a href="" @click.prevent="$refs.dialogDestroy.show()">
          <span>Löschen</span>
        </a>
      </li>
    </ul>
    <slot />
  </nav>
  <dialog-wrapper ref="dialogArchive">
    <template #message>
      <div><strong>Möchten Sie das Gesuch</strong><br>{{$props.application.project_title}}<br><strong>wirklich archivieren?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="archive()">Ja, archivieren</a>
    </template>
  </dialog-wrapper>
  
  <dialog-wrapper ref="dialogRestore">
    <template #message>
      <div><strong>Möchten Sie das Gesuch</strong><br>{{$props.application.project_title}}<br><strong>wirklich wiederherstellen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="restore()">Ja, wiederherstellen</a>
    </template>
  </dialog-wrapper>

  <dialog-wrapper ref="dialogDestroy">
    <template #message>
      <div><strong>Möchten Sie das Gesuch</strong><br>{{$props.application.project_title}}<br><strong>wirklich löschen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="destroy()">Ja, löschen</a>
    </template>
  </dialog-wrapper>
</div>
</template>
<script>
import DialogWrapper from "@/components/ui/misc/Dialog.vue";

export default {

  components: {
    DialogWrapper,
  },

  props: {
    type: String,
    uuid: String,
    application: Object,
  },

  data() {
    return {

      // States
      isLoading: false,
      
      // Routes
      routes: {
        fetch: '/api/application',
        destroy: '/api/application',
        put: '/api/application/archive',
      },
    }
  },

  mounted() {

  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.axios.get(`${this.routes.fetch}/${this.$route.params.uuid}`).then(response => {
        this.data = response.data;
        this.isFetched = true;
      });
    },

    destroy() {
      this.isLoading = true;
      this.axios.delete(`${this.routes.destroy}/${this.$props.uuid}`).then(response => {
        this.isLoading = false;
        this.redirect(this.$props.type);
      });
    },

    archive() {
      this.isLoading = true;
      this.axios.put(`${this.routes.put}/${this.$route.params.uuid}`, {archive: 1}).then(response => {
        this.isLoading = false;
        this.$refs.dialogArchive.hide();
        this.redirect(this.$props.type);
      });
    },

    restore() {
      this.isLoading = true;
      this.axios.put(`${this.routes.put}/${this.$route.params.uuid}`, {archive: 0}).then(response => {
        this.isLoading = false;
        this.$refs.dialogArchive.hide();
        this.redirect('aktuell');
      });
    },

    redirect(type) {
      if (type == 'archiv') {
        return this.$router.push({ name: 'applications-archive', params: {type: 'archiv', uuid: this.$props.uuid} });
      }
      return this.$router.push({ name: 'applications-current', params: {type: 'aktuell', uuid: this.$props.uuid} });
    }

  }
}
</script>