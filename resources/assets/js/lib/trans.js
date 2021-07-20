const Polyglot = require('node-polyglot');

const polyglotInstance = new Polyglot();

if (typeof LOCALIZED_STRINGS !== 'undefined') {
    polyglotInstance.extend(LOCALIZED_STRINGS);
}

module.exports = {
    extend(strings) {
        polyglotInstance.extend(strings);
    },

    t(key, replace) {
        return polyglotInstance.t(key, replace);
    },
};
