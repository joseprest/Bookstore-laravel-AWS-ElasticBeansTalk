/*eslint-disable*/
var ActionTypes = require('../constants/ActionTypes');

var initialState = {
    tasksCount: 0
};

function AsyncTasksStore(state, action)
{
    if (typeof state === 'undefined') {
        state = initialState;
    }

    switch (action.type) {
        case ActionTypes.ASYNCTASKS_STARTED:
            return Object.assign(
                {},
                state,
                { tasksCount: state.tasksCount + 1 }
            );
        case ActionTypes.ASYNCTASKS_ENDED:
            return Object.assign(
                {},
                state,
                { tasksCount: state.tasksCount - 1 }
            );
        default:
            return state;
    }
}

module.exports = AsyncTasksStore;
