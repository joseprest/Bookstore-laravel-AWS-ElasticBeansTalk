const ActionTypes = require('../constants/ActionTypes');

module.exports = {
    /**
     * Action creator that receives a Promise object representing the async
     * task. Returns a thunk who dispatches the ASYNCTASKS_STARTED action
     * immediately and dispatches the ASYNCTASKS_ENDED action when the promise
     * resolves or rejects.
     *
     * @param Promise promise
     * @return {function} The thunk function
     */
    add(promise) {
        const taskEndedAction = this.taskEnded();
        const taskStartedAction = this.taskStarted();

        return (dispatch) => {
            dispatch(taskStartedAction);

            promise.then(
                () => dispatch(taskEndedAction),
                () => dispatch(taskEndedAction)
            );
        };
    },

    taskStarted() {
        return {
            type: ActionTypes.ASYNCTASKS_STARTED,
        };
    },

    taskEnded() {
        return {
            type: ActionTypes.ASYNCTASKS_ENDED,
        };
    },
};
