const Immutable = require('immutable-external');
const _ = require('lodash-external');
const Panneau = require('panneau');
const React = require('react-external');

const FormComponent = Panneau.Components.Form.Component;

const SubForm = {
    updateFormErrors(errors) {
        let errorsByField = {};
        _.each(errors, (error) => {
            if (error.message === 'validation') {
                errorsByField = _.merge(errorsByField, error.validation);
            } else {
                const matches = error.message.match(/\$([a-z][a-z0-9[\]_-]+)/);
                if (matches) {
                    const message =
                        error.message.match(/^\$[a-z][^\s]+:?\s*/) ?
                        error.message.replace(/^\$[a-z][^\s]+:?\s*/, '') :
                        '';
                    _.set(errorsByField, matches[1], [message]);
                }
            }
        });

        const newErrors = Immutable.fromJS(errorsByField);

        this.setState({
            formErrors: newErrors,
        });
    },

    onFormFieldChange(name, value, key) {
        const data = this.state.formData || Immutable.fromJS({});
        const newData = data.set(name, value);
        this.setState({
            [key]: newData,
        });
    },

    renderForm(props, stateKey) {
        const componentProps = {
            ...props,
            data: this.state.formData ? this.state.formData.toJS() : null,
            errors: this.state.formErrors ? this.state.formErrors.toJS() : null,
            token: document.querySelector('meta[name=csrf-token]').getAttribute('content'),
        };

        return (
            <FormComponent
                {...componentProps}
                onSubmit={this.onSubmit}
                onFieldChange={(name, value) => this.onFormFieldChange(name, value, stateKey || 'formData')}
            />
        );
    },
};

module.exports = SubForm;
