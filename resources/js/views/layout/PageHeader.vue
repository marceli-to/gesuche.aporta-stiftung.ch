<template>
  <div>
    <header class="site-header">
      <div>
        <nav class="site-menu">
          <ul>
            <li>
              Gesuche
            </li>
            <li>
              <a href="">Aktuell</a>
            </li>
            <li>
              <a href="">Archiv</a>
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
  </div>
</template>
<script>

export default {
  components: {
  },

  props: {
    user: '',
  },

	data() {
		return {
			menuVisible: false
		}
  },
  
	methods: {
		toggleMenu() {
			this.menuVisible = this.menuVisible ? false : true;
		}
  },

  watch: {
    '$route'() {
      this.menuVisible = false
    }
  }
}
</script>
