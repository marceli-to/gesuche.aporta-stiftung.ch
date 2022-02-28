Vue.filter('truncate', function (text, length, suffix) {
  if (text){
    let t = text.replace(/(<([^>]+)>)/ig,"");
    if (t.length > length) {
      return t.substring(0, length) + suffix;
    }
    else {
      return t;
    }
  }
});

Vue.filter('currency', function(value) {
  if (value > 0) {
    return  new Intl.NumberFormat('de-CH', {minimumFractionDigits: 2}).format(value);
  }
  return '–';
});