import { orderBy } from "lodash-es";

export default {

  data() {
    return {
      sortBy: '',
      sortDirection: 'asc'
    };
  },

  methods: {
    sort(s){
      if (s === this.sortBy) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      }
      this.sortBy = s;
    }
  },
  computed: {
    sortedData(){
      return orderBy(this.data, this.sortBy, this.sortDirection);
    }
  },
};