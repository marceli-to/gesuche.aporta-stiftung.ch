export default {

  data() {
    return {
      hasSelector: false,
      hasSelectorCurrent: false,
      hasSelectorArchive: false,
      hasSelectorResult: false,
      cachebuster: this.randomString(),
    };
  },

  methods: {
    toggleSelector(type) {
      this.hasSelector = this.hasSelector ? false : true;

      if (type == 'aktuell') {
        this.hasSelectorCurrent = this.hasSelectorCurrent ? false : true;
        this.hasSelectorArchive = false;
      }
      else if (type == 'archiv') {
        this.hasSelectorArchive = this.hasSelectorArchive ? false : true;
        this.hasSelectorCurrent = false;
      }

      this.cachebuster = this.randomString(12);
      this.hideFilter();
    },

    hideSelector() {
      this.hasSelectorCurrent = false;
      this.hasSelectorArchive = false;
    },

    resetSelector() {
      let selector = {
        set: false,
        type: null,
        year: null,
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
    },

    randomString(length) {
      var result = [];
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for (var i = 0; i < length; i++) {
        result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
      }
      return result.join('');
    }

  },
  watch: {
    '$route'() {
      this.hasSelector = false;
      this.hasSelectorCurrent = false;
      this.hasSelectorArchive = false;     
    }
  }
}
