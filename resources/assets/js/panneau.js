/* eslint import/newline-after-import: 0 */

const Panneau = require('panneau');
const moment = require('moment');

moment.locale(Panneau.config('locale'));

Panneau.pubnub = new PubNub({
    subscribe_key: Panneau.config('pubnub.subscribe_key'),
});

Panneau.pubnub.getChannelWithNamespace = (channel) => {
    const namespace = Panneau.config('pubnub.namespace');
    const parts = [];

    if (namespace && namespace.length) {
        parts.push(namespace);
    }
    parts.push(channel);

    return parts.join(':');
};

Panneau.Components.DangerZone = require('./components/DangerZone');
Panneau.Components.ScreenControls = require('./components/ScreenControls');
Panneau.Components.UserAvatar = require('./components/UserAvatar');
Panneau.Components.BackgroundTasks = require('./containers/BackgroundTasks');
Panneau.Components.PendingTasksAlert = require('./containers/PendingTasksAlert');

const AsyncTasksReducer = require('./stores/AsyncTasksStore');
Panneau.store.addReducer('asyncTasks', AsyncTasksReducer);

// Actions
const Actions = require('./actions/index');
Panneau.Actions = {
    ...Panneau.Actions,
    ...Actions,
};

// Lists
const Lists = require('./components/lists/index');
Panneau.Components.Lists = {
    ...Panneau.Components.Lists,
    ...Lists,
};

// Lists items
const Items = require('./components/items/index');
Panneau.Components.Lists.Items = {
    ...Panneau.Components.Lists.Items,
    ...Items,
};

// Modals
const Modals = require('./components/modals/index');
Panneau.Components.Modals = {
    ...Panneau.Components.Modals,
    ...Modals,
};

// Fields
const Fields = require('./components/fields/index');
Panneau.Components.Fields = {
    ...Panneau.Components.Fields,
    ...Fields,
};
