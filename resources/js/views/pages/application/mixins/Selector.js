export default {

  data() {
    return {
      hasSelector: false,
      hasSelectorResult: false,
    };
  },

  methods: {
    toggleSelector() {
      this.hasSelector = this.hasSelector ? false : true;
      this.hideFilter();
    },

    showSelector() {
      this.hasSelector = true;
      this.hideFilter();
    },

    hideSelector() {
      this.hasSelector = false;
    },

    resetSelector() {
      let selector = {
        set: false,
        type: null,
      };
      this.$store.commit('selector', selector);
      this.hideSelector();
      this.fetch(this.$route.params.type);
    },

    setSelectorItem(type, value) {
      let selector = this.$store.state.selector;
      selector[type] = value;
      selector['set'] = true;
      this.$store.commit('selector', selector);
      //this.fetchSelected();
    },

  },
  watch: {
    '$route'() {
      this.hasSelector = false;
    }
  }
}
