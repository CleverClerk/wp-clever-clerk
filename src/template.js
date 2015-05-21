var $ = require('jquery');

var templates = {
  tour: require('./templates/tour.jade'),
  tours: function(data) {
    return $.map(data, function(data, index) {
      return template('tour')(data);
    }).join('\n');
  }
};

function template(type) {
  if (templates[type]) {
    return templates[type];
  }

  return function(data) {
    return "";
  };
}

module.exports = template;