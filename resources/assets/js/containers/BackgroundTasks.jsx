const { connect } = require('react-redux');
const BackgroundTasks = require('../components/BackgroundTasks');

module.exports = connect(state => (
    {
        nbTasks: state.asyncTasks.tasksCount,
    }
))(BackgroundTasks);
