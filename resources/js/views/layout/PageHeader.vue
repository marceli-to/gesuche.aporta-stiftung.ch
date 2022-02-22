<template>
<div>
  <header class="page-header">
    <div>
      <a href="/administration/dashboard" class="brand">
       à Porta Stiftung
      </a>
    </div>
  </header>
  <nav :class="[!menuVisible ? '' : 'is-visible', 'page']">
    <header>
      <span>
        <strong>{{user}}</strong><br>
        <a href="/logout" class="feather-icon feather-icon--prepend">
          <span>Logout</span>
        </a>
      </span>
    </header>

  </nav>
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
