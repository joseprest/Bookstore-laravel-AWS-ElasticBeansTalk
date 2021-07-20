const agent = require('superagent-promise-external')(require('superagent-external'), Promise);
const Panneau = require('panneau');
const _ = require('lodash-external');

function buildArgs(params, types) {
    const queryArgs = [];
    const objectArgs = [];

    if (_.isObject(params)) {
        Object.entries(params).forEach(([k, v]) => {
            const type = _.get(types, k, Array.isArray(v) ? '[String]' : 'String');
            queryArgs.push(`$${k}: ${type}`);
            objectArgs.push(`${k}: $${k}`);
        });
    }

    let query = queryArgs.join(',');
    let object = objectArgs.join(',');

    if (queryArgs.length) query = `(${query})`;
    if (objectArgs.length) object = `(${object})`;

    return {
        object,
        query,
    };
}

module.exports = (rawQuery, params = null, options = {}) => {
    const opts = {
        argsTypes: {},
        fragments: [],
        ...options,
    };
    let query = rawQuery;

    if (query.match(/<(object|query)Args>/)) {
        const args = buildArgs(params, opts.argsTypes);
        query = query.replace(/<objectArgs>/g, args.object)
                    .replace(/<queryArgs>/g, args.query);
    }

    query = opts.fragments.reduce(
        (prevQuery, fragment) => prevQuery + fragment,
        query
    );

    const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');

    return agent
        .post(Panneau.config('graphQL.url'))
        .send({
            query,
            params,
        })
        .set('X-CSRF-TOKEN', csrfToken)
        .set('Accept', 'application/json')
        .end()
        .then((response) => {
            if (response.body.errors || !response.body.data) {
                return Promise.reject(response.body.errors);
            }

            return response.body.data;
        });
};
