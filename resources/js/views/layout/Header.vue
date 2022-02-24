<template>
<header class="site-header">
  <div>
    <nav class="site-menu">
      <ul>
        <li>
          Gesuche
        </li>
        <li>
          <router-link :to="{name: 'applications-current', params: { type: 'aktuell' }}" :active-class="'is-active'">
            Aktuell
          </router-link>
        </li>
        <li>
          <router-link :to="{name: 'applications-archive', params: { type: 'archiv' }}" :active-class="'is-active'">
            Archiv
          </router-link>
        </li>
        <li>
          <a href="">[b]</a>
        </li>
        <li class="user">
          <a href="/logout" class="user">{{user.email}}</a>
        </li>
      </ul>
    </nav>
  </div>
</header>
</template>
<script>

export default {
  components: {
  },

  props: {
    user: '',
  },
  watch: {
    '$route'() {
    }
  }
}
</script>
