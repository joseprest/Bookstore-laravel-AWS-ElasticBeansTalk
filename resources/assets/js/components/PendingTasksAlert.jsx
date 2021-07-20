const React = require('react-external');
const $ = require('jquery-external');
const { t } = require('../lib/trans');

/**
 * "Global" flag if the beforeunload callback is installed (global in case this
 * component is mounted multiple times)
 * @type {Boolean}
 */
let beforeUnloadCallbackInstalled = false;

/**
 * Before unload call back. Called with 'this' = the component. If the
 * component.props.nbTasks is > 0, returns a message warning of
 * pending tasks. Also updates internal state to show the message.
 * @param  {[type]} event [description]
 * @return {[type]}       [description]
 */
function beforeUnloadCallback(event) {
    if (this.props.nbTasks === 0) {
        this.setState({
            tryingToLeave: false,
        });

        return;
    }

    // Note that Firefox, Chrome (and probably more and more
    // browsers) do not display the message anymore (only a
    // generic message) to prevent scamming.
    const message = t('layout.pending_tasks.confirmation');

    this.setState({
        tryingToLeave: true,
    });

    // Scroll top to show the alert
    $(window).scrollTop(0);

    // eslint-disable-next-line no-param-reassign
    event.returnValue = message;

    // eslint-disable-next-line consistent-return
    return message;
}

const PendingTasksAlert = React.createClass({
    propTypes: {
        nbTasks: React.PropTypes.number,
    },

    getDefaultProps() {
        return {
            nbTasks: 1,
        };
    },

    getInitialState() {
        return {
            tryingToLeave: false,
        };
    },

    componentDidMount() {
        this.setupBeforeUnloadHandler();
    },

    componentWillReceiveProps(nextProps) {
        if (this.props.nbTasks !== 0 && nextProps.nbTasks === 0) {
            this.setState({
                tryingToLeave: false,
            });
        }
    },

    componentWillUnmount() {
        $(window).off('beforeunload', this.beforeUnloadFunction);
        beforeUnloadCallbackInstalled = false;
    },

    setupBeforeUnloadHandler() {
        // We try to prevent multiple install of the same handler
        if (!beforeUnloadCallbackInstalled) {
            this.beforeUnloadFunction = beforeUnloadCallback.bind(this);
            $(window).on('beforeunload', this.beforeUnloadFunction);
            beforeUnloadCallbackInstalled = true;
        }
    },

    beforeUnloadFunction: null,

    render() {
        if (this.props.nbTasks === 0 || !this.state.tryingToLeave) {
            return null;
        }

        const nbTasksMessage = this.props.nbTasks === 1
            ? t('layout.pending_tasks.one_task_is')
            : t('layout.pending_tasks.nb_tasks_are', { nb: this.props.nbTasks });

        return (
            <div className="alert alert-warning" role="alert">
                <strong>{t('layout.pending_tasks.alert.title')}</strong>
                {t('layout.pending_tasks.alert.message', { nbTasks: nbTasksMessage })}
            </div>
        );
    },
});

module.exports = PendingTasksAlert;
