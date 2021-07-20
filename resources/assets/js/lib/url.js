const routes = {};

module.exports = {
    route(key, opts = {}) {
        const prefix = `http://${window.location.host}`;
        let url = (routes[key] || key).replace(prefix, '');

        url = Object.entries(opts).reduce(
            (prevUrl, [optKey, optVal]) => prevUrl.replace(`:${optKey}`, optVal),
            url
        );

        return url;
    },

    setRoutes(newRoutes) {
        Object.entries(newRoutes).forEach(([key, url]) => {
            routes[key] = url;
        });
    },
};
