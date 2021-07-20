const Modals = require('./modals/index');
const Lists = require('./lists/index');
const BubblesSelector = require('./BubblesSelector');
const DangerZone = require('./DangerZone');
const ScreenControls = require('./ScreenControls');
const UserAvatar = require('./UserAvatar');
const BackgroundTasks = require('./BackgroundTasks');
const PendingTasksAlert = require('./PendingTasksAlert');
const Snippets = require('./snippets/index');

Lists.Items = require('./items/index');

module.exports = {
    Modals,
    Lists,
    BubblesSelector,
    DangerZone,
    ScreenControls,
    UserAvatar,
    BackgroundTasks,
    PendingTasksAlert,
    Snippets,
};
