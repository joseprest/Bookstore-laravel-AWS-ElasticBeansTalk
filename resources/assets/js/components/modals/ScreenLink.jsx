const React = require('react-external');
const { t } = require('../../lib/trans');
const Panneau = require('panneau');
const Requests = require('../../requests/index');
const SubFormMixins = require('../../mixins/SubForm');

const Popover = Panneau.Components.Modals.Popover;

const ScreenLinkModal = React.createClass({
    propTypes: {
        visible: React.PropTypes.bool,
        element: React.PropTypes.instanceOf(HTMLElement),
        formName: React.PropTypes.string,
        onClose: React.PropTypes.func,
        closeModal: React.PropTypes.func.isRequired,
        onComplete: React.PropTypes.func,
    },

    mixins: [SubFormMixins],

    getDefaultProps() {
        return {
            visible: false,
            element: null,
            formName: 'organisation.screens.link',
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

        const authCode = this.state.formData ? this.state.formData.get('auth_code') : null;
        Requests.Organisations.linkScreen(authCode)
                .then(this.onScreenLinked, this.onScreenLinkError);
    },

    onScreenLinked(screen) {
        this.props.closeModal();

        if (this.props.onComplete) {
            this.props.onComplete(screen);
        }
    },

    onScreenLinkError(errors) {
        this.updateFormErrors(errors);
    },

    render() {
        // eslint-disable-next-line no-unused-vars, react/prop-types
        const { onClose, authCode, canCreate, ...other } = this.props;
        const title = t('screen.linking.title');

        // Form
        const formProps = Panneau.Schema.Form[this.props.formName];
        formProps.name = this.props.formName;
        formProps.onSubmit = this.onSubmit;
        const formContainer = this.renderForm(formProps);

        return (
            <Popover {...other} title={title} onClose={this.onClose}>
                <p>{ t('screen.linking.ensure_connected') }</p>
                { formContainer }
                <p>{ t('screen.linking.message_code') }</p>
            </Popover>
        );
    },
});

module.exports = ScreenLinkModal;
