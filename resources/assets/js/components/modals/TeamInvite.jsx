/* eslint-disable react/no-unused-prop-types */
const React = require('react-external');
const Immutable = require('immutable-external');
const { t } = require('../../lib/trans');
const Panneau = require('panneau');
const SubFormMixins = require('../../mixins/SubForm');
const Requests = require('../../requests/index');

const Popover = Panneau.Components.Modals.Popover;

const TeamInviteModal = React.createClass({
    propTypes: {
        visible: React.PropTypes.bool,
        element: React.PropTypes.instanceOf(HTMLElement),
        offsetX: React.PropTypes.number,
        offsetY: React.PropTypes.number,
        formName: React.PropTypes.string,
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
            formName: 'organisation.team.invite',
            onClose: null,
            onComplete: null,
        };
    },

    getInitialState() {
        return {
            formData: null,
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
                    email: [''],
                    role_id: [''],
                }),
            });

            return;
        }

        const email = this.state.formData.get('email') || '';
        const roleId = this.state.formData.get('role_id') || '';

        Requests.Organisations.inviteUser(email, roleId)
                .then(this.onUserInvited, this.onUserInviteError);
    },

    onUserInvited(invitation) {
        this.props.closeModal();

        if (this.props.onComplete) {
            this.props.onComplete(invitation);
        }
    },

    onUserInviteError(errors) {
        this.updateFormErrors(errors);
    },

    render() {
        // eslint-disable-next-line no-unused-vars, react/prop-types
        const { email, role, onClose, onOpen, ...other } = this.props;
        const title = t('team.actions.add_member');

        // Form
        const formProps = Panneau.Schema.Form[this.props.formName];
        formProps.name = this.props.formName;
        formProps.onSubmit = this.onSubmit;
        const formContainer = this.renderForm(formProps);

        return (
            <Popover title={title} {...other} onClose={this.onClose}>
                <p>{ t('team.inputs.email_to_invite') }</p>
                { formContainer }
            </Popover>
        );
    },
});

module.exports = TeamInviteModal;
