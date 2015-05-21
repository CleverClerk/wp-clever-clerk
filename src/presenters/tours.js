var $ = require('jquery');

function tours(data, present) {
  return $.map(data, function(data) {
    return present('tour', data);
  });
}

module.exports  = tours;