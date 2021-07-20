const Panneau = require('panneau');
const Requests = require('../requests/index');

const ListActions = Panneau.Actions.List;

module.exports = {
    waitForNewData() {
        return ListActions.updateData({
            type: 'loading',
        }, 'bubbles.timeline');
    },

    updateForScreen(screenId) {
        return (dispatch) => {
            dispatch(ListActions.updateData({
                type: 'loading',
            }, 'bubbles.timeline'));
            Requests.Timeline.loadForScreen(screenId)
                .then(
                    timeline => dispatch(ListActions.updateData(timeline, 'bubbles.timeline')),
                    () => dispatch(ListActions.updateData([], 'bubbles.timeline'))
                );
        };
    },
};
