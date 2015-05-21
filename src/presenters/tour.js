var $ = require('jquery');
var moment = require('moment');
var presenter = require('./base.js');

var basises = {
  'per_person': 'Per person',
  'per_group': 'Per group'
};

function short_time(field) {
  return function(time, info) {
    info[field] = moment(time, 'hh:mm A').format('h:mma');
  };
}

function capitalize(str) {
  return str[0].toUpperCase() + str.slice(1);
}

var tour = presenter({
  price: function(price, info) {
    info['price'] = "$" + parseFloat(price, 10).toFixed(2);
  },

  price_basis: function(price_basis, info) {
    info['price_basis'] = basises[price_basis] || '';
  },

  start_time: short_time('start_time'),
  end_time: short_time('end_time'),

  days_offered: function(days_offered, info) {
    info['days_offered'] = $.map(days_offered, capitalize);
  }
});
module.exports = tour;