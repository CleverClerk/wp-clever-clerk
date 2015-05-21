var presenters = {
  tours: require('./presenters/tours.js'),
  tour: require('./presenters/tour.js')
};

function present(type, data) {
  if (presenters[type]) {
    return presenters[type](data, present);
  }

  return data;
}

module.exports = present;