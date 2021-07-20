const React = require('react-external');
const Immutable = require('immutable-external');
const _ = require('lodash-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');
const SubFormMixins = require('../../mixins/SubForm');
const Requests = require('../../requests/index');
const DangerZone = require('../DangerZone');

const AsyncTasksActions = Panneau.Actions.AsyncTasks;
const Popover = Panneau.Components.Modals.Popover;

const TeamEditModal = React.createClass({
    propTypes: {
        visible: React.PropTypes.bool,
        element: React.PropTypes.instanceOf(HTMLElement),
        offsetX: React.PropTypes.number,
        offsetY: React.PropTypes.number,
        formName: React.PropTypes.string,
        data: React.PropTypes.object.isRequired,
        onClose: React.PropTypes.func,
        onComplete: React.PropTypes.func,
        closeModal: React.PropTypes.func.isRequired,
    },

    mixins: [SubFormMixins],

    getDefaultProps() {
        return {
            visible: false,
            element: null,
            offsetX: 0,
            offsetY: 0,
            formName: 'organisation.team.edit',
            onClose: null,
            onComplete: null,
        };
    },

    getInitialState() {
        return {
            formData: Immutable.fromJS({
                role_id: _.get(this.props, 'data.role.id', ''),
            }),
            formErrors: null,
        };
    },

    onClose() {
        if (this.props.onClose) {
            this.props.onClose();
        }
    },

    onSubmit(e) {
        e.preventDefault();

        if (!this.state.formData) {
            this.setState({
                formErrors: Immutable.fromJS({
                    role_id: [''],
                }),
            });

            return;
        }

        const type = _.get(this.props, 'data.type', 'invitation');
        const roleId = this.state.formData.get('role_id') || '';

        if (type === 'invitation') {
            Requests.Organisations.updateInvitation(this.props.data.id, roleId)
                    .then(this.onUpdated, this.onUpdateError);
        } else {
            Requests.Organisations.updateUser(this.props.data.id, roleId)
                    .then(this.onUpdated, this.onUpdateError);
        }
    },

    onDelete(e) {
        e.preventDefault();

        const type = _.get(this.props, 'data.type', 'invitation');
        let request;

        if (type === 'invitation') {
            request = Requests.Organisations.removeInvitation(this.props.data.id);
        } else {
            request = Requests.Organisations.removeUser(this.props.data.id);
        }

        // Optimistic: update the UI before receiving the response
        this.onRemoved();
        // Create new async task (to display the throbber)
        Panneau.dispatch(AsyncTasksActions.add(request));
    },

    onUpdated(data) {
        this.props.closeModal();

        data.type = this.props.data.type;

        if (this.props.onComplete) {
            this.props.onComplete(data);
        }
    },

    onUpdateError(errors) {
        this.updateFormErrors(errors);
    },

    onRemoved() {
        this.props.closeModal();

        if (this.props.onComplete) {
            this.props.onComplete(null);
        }
    },

    render() {
        // eslint-disable-next-line no-unused-vars, react/prop-types
        const { role, onClose, onOpen, ...other } = this.props;
        const type = _.get(this.props, 'data.type', 'invitation');

        const title = type === 'invitation'
            ? t('invitation.actions.edit')
            : t('team.actions.edit_member');
        const deleteDescription = type === 'invitation'
            ? t('invitation.deletion.title')
            : t('team.member_deletion.title');
        const deleteConfirmation = type === 'invitation'
            ? t('invitation.deletion.confirmation')
            : t('team.member_deletion.confirmation');
        const deleteButtonLabel = type === 'invitation'
            ? t('general.actions.delete')
            : t('team.actions.remove_member');

        // Form
        const formProps = Panneau.Schema.Form[this.props.formName];
        formProps.name = this.props.formName;
        formProps.onSubmit = this.onSubmit;
        const formContainer = this.renderForm(formProps);

        return (
            <Popover title={title} {...other} onClose={this.onClose}>
                { formContainer }
                <DangerZone
                    deleteTitle=""
                    deleteDescription={deleteDescription}
                    deleteConfirmation={deleteConfirmation}
                    onDelete={this.onDelete}
                    form={{
                        buttons: [
                            {
                                className: 'btn btn-danger',
                                type: 'submit',
                                label: deleteButtonLabel,
                            },
                        ],
                    }}
                />
            </Popover>
        );
    },
});

module.exports = TeamEditModal;
