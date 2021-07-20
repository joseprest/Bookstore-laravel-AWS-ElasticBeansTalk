const React = require('react-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');

const PasswordField = Panneau.Components.Fields.Password;

const PasswordChangeField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        label: React.PropTypes.string,
        value: React.PropTypes.string,
        errors: React.PropTypes.array,
        onChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            name: null,
            label: null,
            value: null,
            errors: null,
            onChange: null,
        };
    },

    getInitialState() {
        return {
            update: this.props.errors && this.props.errors.length,
        };
    },

    onClick(e) {
        e.preventDefault();

        this.setState({
            update: true,
        });
    },

    render() {
        const { label, ...props } = this.props;

        let labelElement = null;
        if (label && label.length) {
            labelElement = (
                <label htmlFor={this.props.name} className="control-label">
                    { label }
                </label>
            );
        }

        let link = null;
        if (!this.state.update) {
            link = (
                <div className="link">
                    <a href="#" onClick={this.onClick}>
                        { t('fields.password_change.change') }
                    </a>
                </div>
            );
        }

        let passwordField = null;
        if (this.state.update) {
            passwordField = (
                <div className="field">
                    <PasswordField
                        {...props}
                        label={t('fields.password_change.new')}
                        confirmationLabel={t('fields.password_change.confirm')}
                    />
                </div>
            );
        }

        return (
            <div className="form-group form-group-password-change">
                { labelElement }
                { link }
                { passwordField }
            </div>
        );
    },
});

module.exports = PasswordChangeField;
