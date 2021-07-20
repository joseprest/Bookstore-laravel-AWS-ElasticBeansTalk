const React = require('react-external');
const Panneau = require('panneau');
const $ = require('jquery-external');
const _ = require('lodash-external');
const Immutable = require('immutable-external');
const request = require('superagent-external');
const t = require('../../lib/trans').t;

const Modal = Panneau.Components.Modals.Modal;

const FormModal = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        visible: React.PropTypes.bool,
        element: React.PropTypes.instanceOf(HTMLElement),
        offsetX: React.PropTypes.number,
        offsetY: React.PropTypes.number,
        title: React.PropTypes.string,
        formName: React.PropTypes.string,
        formFields: React.PropTypes.array,
        formAction: React.PropTypes.string,
        formMethod: React.PropTypes.string,
        formData: React.PropTypes.object,
        formErrors: React.PropTypes.object,
        closeModal: React.PropTypes.func.isRequired,
        onClose: React.PropTypes.func,
        onComplete: React.PropTypes.func,
    },

    contextTypes: {
        store: React.PropTypes.object,
    },

    getDefaultProps() {
        return {
            name: '',
            visible: false,
            element: null,
            offsetX: 0,
            offsetY: 0,
            title: '',
            formName: null,
            formFields: [],
            formAction: '',
            formMethod: 'post',
            formData: null,
            formErrors: null,
            onClose: null,
            onComplete: null,
        };
    },

    getInitialState() {
        return {
            formData: this.props.formData || {},
            formErrors: this.props.formErrors || {},
        };
    },

    onClickSave(e) {
        this.onSubmit(e);
    },

    onClickClose(e) {
        e.preventDefault();
        this.props.closeModal(this.props.name);
    },

    onClose() {
        if (this.props.onClose) {
            this.props.onClose();
        }
    },

    onSubmit(e) {
        e.preventDefault();

        const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        const formMethod = this.props.formMethod.toUpperCase();
        let methodName = formMethod.toLowerCase();

        if (methodName !== 'get') {
            methodName = 'post';
        }

        const $form = $(this.formContainerNode).find('form');
        const $inputs = $form.find('input, select, textarea');
        const data = {
            _method: formMethod,
        };

        $inputs.each((i, el) => {
            const name = $(el).attr('name');
            if (name && name.length) {
                data[name] = $(el).val();
            }
        });

        const currentRequest = request[methodName](this.props.formAction)
            .set('X-CSRF-TOKEN', token)
            .set('Accept', 'application/json');

        Object.entries(data).forEach(([key, dataEntry]) => {
            currentRequest.field(key, dataEntry);
        });

        currentRequest.end(this.onFormComplete);
    },

    onFormComplete(err, response) {
        if (err) {
            this.onFormError(response.body);
            return;
        }

        this.props.closeModal();

        if (this.props.onComplete) {
            this.props.onComplete(response.body);
        }
    },

    onFormError(errors) {
        const errorsByField = {};

        errors.forEach((error) => {
            const matches = error.message.match(/\$([a-z][a-z0-9[\]_-]+)/);

            if (matches) {
                _.set(errorsByField, matches[1], ['']);
            }
        });

        this.setState({
            formErrors: errorsByField,
        });
    },

    onFormChange(data) {
        const currentValue = Immutable.fromJS(this.state.formData);
        const newValue = Immutable.fromJS(data);

        if (newValue !== currentValue) {
            const value = newValue.toJS();

            this.setState({
                formData: value,
            });
        }
    },

    render() {
        // eslint-disable-next-line no-unused-vars, react/prop-types
        const { onClose, onOpen, ...other } = this.props;
        const title = this.props.title;

        // Form
        const formSchema = this.props.formName && Panneau.Schema.Form[this.props.formName]
            ? Panneau.Schema.Form[this.props.formName]
            : {};
        const formProps = {
            token: $('meta[name=csrf-token]').attr('content'),
            fields: this.props.formFields,
            method: this.props.formMethod,
            action: this.props.formAction,
            data: this.state.formData,
            errors: this.state.formErrors,
            ...formSchema,
            buttons: [],
        };

        const FormComponent = Panneau.Components.Form.Component;

        return (
            <Modal {...other} onClose={this.onClose}>
                <div className="modal-header">
                    <button
                        type="button"
                        className="close"
                        onClick={this.onClickClose}
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 className="modal-title">{ title }</h4>
                </div>
                <div className="modal-body" ref={(node) => { this.formContainerNode = node; }}>
                    <FormComponent
                        {...formProps}
                        onSubmit={this.onSubmit}
                        onChange={this.onFormChange}
                    />
                </div>
                <div className="modal-footer">
                    <button
                        type="button"
                        className="btn btn-default"
                        onClick={this.onClickSave}
                    >
                        { t('general.actions.save') }
                    </button>
                </div>
            </Modal>
        );
    },
});

module.exports = FormModal;
