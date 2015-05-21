var $ = require('jquery');
function base(presentspec) {
  presentspec = presentspec || {};
  return function(data) {
    var info = {};
    $.each(data, function(key, datum) {
      info[key] = datum;
    });

    $.each(presentspec, function(key, method) {
      if ( $.isFunction(method)) {
        method(info[key], info);
      }
    });
    
    return info;
  };
}
module.exports = base;