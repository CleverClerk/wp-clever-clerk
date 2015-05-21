var $ = require('jquery');

var template = require('./template.js');
var present = require('./present.js');

function base_url(platform) {
  return "https://" + platform + '/api/v1';
}

function request(platform) {
  var args = arguments;
  if (args.length  > 1 && args[1] instanceof Object) {
    args[1]['url'] = base_url(platform) + args[1]['url'];
  }

  return $.ajax.apply($, Array.prototype.slice.call(args, 1));
}

function render(type, data, callback) {
  callback(template(type)(present(type,data)));
}

var CleverClerk = {
  tours: function(data, callback) {
    var url = '/hotels/' + data.marketplaceid + '/tours.json';

    request(data.cleverclerkapihost, { url: url })
      .done(function(tours) { render('tours', tours, callback); });
  }
};

$(function() {
  $('.clever_clerk').each(function(index, tag) {
    var $tag = $(tag);
    var method = $tag.data('cleverClerk');
    if (method && $.isFunction(CleverClerk[method])) {
      CleverClerk[method].call(CleverClerk, $tag.data(), function(markup) {
        $tag.html(markup);
      });
    }
  });
});