<template>
<div>
  <header :class="[$parent.hasFilter ? 'site-header has-filter' : 'site-header']">
    <div>
      <nav class="site-menu">
        <ul>
          <li class="span-1">
            Gesuche
          </li>
          <li class="span-1">
            <router-link :to="{name: 'applications-current', params: { type: 'aktuell' }}" :active-class="'is-active'">
              Aktuell
            </router-link>
          </li>
          <li class="span-1">
            <router-link :to="{name: 'applications-archive', params: { type: 'archiv' }}" :active-class="'is-active'">
              Archiv
            </router-link>
          </li>
          <li class="span-1">
            <a href="" class="icon-export">
              <icon-export />
            </a>
          </li>
          <li class="span-4 flex justify-center">
            <a href="" class="icon-filter" @click.prevent="toggleFilter()">
              <icon-filter v-if="!$parent.hasFilter" />
              <icon-cross v-if="$parent.hasFilter" />
            </a>
          </li>
          <li class="user">
            <a href="/logout" class="user">{{user.email}}</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>
  <slot />
</div>
</template>
<script>
import IconExport from "@/components/ui/icons/Export.vue";
import IconFilter from "@/components/ui/icons/Filter.vue";
import IconCross from "@/components/ui/icons/Cross.vue";

export default {
  components: {
    IconExport,
    IconFilter,
    IconCross,
  },

  props: {
    user: '',
  },

  methods: {
    toggleFilter() {
      this.$parent.toggleFilter();
    }
  },

  watch: {
    '$route'() {
    }
  }
}
</script>
