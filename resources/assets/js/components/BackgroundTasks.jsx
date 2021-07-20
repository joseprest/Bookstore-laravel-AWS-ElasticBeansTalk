const React = require('react-external');
const { t } = require('../lib/trans');

/**
 * Displays a "throbber" when there is at least one task waiting.
 */
function BackgroundTasks(props) {
    const hasTasks = props.nbTasks > 0;
    const nbTasksMessage = props.nbTasks === 1
        ? t('layout.pending_tasks.one_task_is')
        : t('layout.pending_tasks.nb_tasks_are', { nb: props.nbTasks });
    const message = hasTasks
        ? t('layout.pending_tasks.tasks_executing', { tasks: nbTasksMessage })
        : '';

    return (
        <div className={`${hasTasks ? 'visible' : ''} background-tasks`}>
            <span className="message">{ message }</span>
            <i className="spinner glyphicon glyphicon-refresh" />
        </div>
    );
}

BackgroundTasks.propTypes = {
    nbTasks: React.PropTypes.number,
};

BackgroundTasks.defaultProps = {
    nbTasks: 1,
};

module.exports = BackgroundTasks;
