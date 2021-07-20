const Panneau = require('panneau');
const React = require('react-external');
const $ = require('jquery-external');
const { t } = require('../../lib/trans');
require('bootstrap-toggle/js/bootstrap-toggle');

const FieldErrorsMixins = Panneau.Mixins.FieldErrors;

const CheckboxField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        label: React.PropTypes.string,
        type: React.PropTypes.string,
        value: React.PropTypes.oneOfType([
            React.PropTypes.string,
            React.PropTypes.number,
        ]),
        checked: React.PropTypes.bool,
        inputOnly: React.PropTypes.bool,
        errors: React.PropTypes.array,
        onChange: React.PropTypes.func,
    },

    mixins: [FieldErrorsMixins],

    getDefaultProps() {
        return {
            name: 'label',
            label: '',
            type: 'checkbox',
            value: null,
            checked: false,
            inputOnly: false,
            onChange: null,
            errors: null,
        };
    },

    componentDidMount() {
        const $checkbox = $(this.checkboxNode);
        $checkbox.bootstrapToggle({
            on: t('fields.toggle.yes'),
            off: t('fields.toggle.no'),
        });
        $checkbox.on('change', this.onChange);
    },

    componentWillUnmount() {
        const $checkbox = $(this.checkboxNode);
        $checkbox.bootstrapToggle('destroy');
        $checkbox.off('change', this.onChange);
    },

    onChange(e) {
        const checked = e.currentTarget.checked;

        if (this.props.onChange) {
            this.props.onChange(checked ? 1 : 0);
        }
    },

    render() {
        // eslint-disable-next-line no-unused-vars
        const { value, name, label, type, onChange, checked, ...other } = this.props;
        const inputType = type ? type.split('.')[0] : 'checkbox';
        const id = `toggle-field-${name}`;

        other.defaultChecked = checked;
        other.checked = !!value;

        const input = (
            <input
                id={id}
                ref={(node) => { this.checkboxNode = node; }}
                type={inputType}
                name={name}
                autoComplete="off"
                value={value}
                {...other}
                onChange={this.onChange}
            />
        );

        const field = (
            <div className="checkbox">
                <label className="control-label" htmlFor={id}>
                    { input } { label }
                </label>
            </div>
        );

        if (this.props.inputOnly) {
            return field;
        }

        let formGroupClassName = 'form-group form-group-checkbox form-group-toggle';
        if (this.props.errors) {
            formGroupClassName += ' has-error';
        }

        const errors = this.renderErrors(this.props.errors);

        return (
            <div className={formGroupClassName}>
                { field }
                { errors }
            </div>
        );
    },
});

module.exports = CheckboxField;
