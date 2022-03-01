<template>
<div>
  <header :class="cls">
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
          <li class="span-4 flex justify-center" v-if="$store.state.filter.items.length && $props.view == 'show'">
            <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: $store.state.filter.menu.prev }}">
              &laquo;
            </router-link>
            <span>{{$store.state.filter.menu.index}} / {{$store.state.filter.items.length}}</span>
            <router-link :to="{name: 'application-show', params: { type: $route.params.type, uuid: $store.state.filter.menu.next }}">
              &raquo;
            </router-link>
          </li>
          <li class="span-4 flex justify-center" v-else>
            <a href="" class="icon-filter" @click.prevent="toggleFilter()">
              <icon-filter v-if="!$parent.hasFilter" :active="$store.state.filter.set" />
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
    view: {
      type: String,
      default: ''
    },
  },

  methods: {
    toggleFilter() {
      this.$parent.toggleFilter();
    },

    getFilterPagination() {
      
    }
  },

  watch: {
    '$route'() {
      this.beforeFetch(this.$route.params.type)
      this.hasFilter = false;
      console.log(this.$store.state.filter.items);
    }
  },

  computed: {
    cls() {
      let cls = 'site-header';
      if (this.$parent.hasFilter) {
        cls = cls + ' has-filter';
      }
      if (this.$props.view == 'show') {
        cls = cls + ' is-detail';
      }
      return cls; 
    }
  },

  watch: {
  }

}
</script>
