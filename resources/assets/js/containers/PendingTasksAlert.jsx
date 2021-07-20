const { connect } = require('react-redux');
const PendingTasksAlert = require('../components/PendingTasksAlert');

module.exports = connect(state => (
    {
        nbTasks: state.asyncTasks.tasksCount,
    }
))(PendingTasksAlert);
