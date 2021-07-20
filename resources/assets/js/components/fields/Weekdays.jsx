const Panneau = require('panneau');
const React = require('react-external');
const { t } = require('../../lib/trans');

const CheckboxesField = Panneau.Components.Fields.Checkboxes;

const WeekdaysField = React.createClass({
    propTypes: {
        label: React.PropTypes.string,
        errors: React.PropTypes.array,
        value: React.PropTypes.array,
        onChange: React.PropTypes.func,
        days: React.PropTypes.array,
    },

    mixins: [Panneau.Mixins.FieldErrors],

    getDefaultProps() {
        return {
            label: null,
            errors: null,
            value: null,
            onChange: null,
            days: [
                {
                    label: t('fields.weekdays.monday'),
                    value: '0',
                },
                {
                    label: t('fields.weekdays.tuesday'),
                    value: '1',
                },
                {
                    label: t('fields.weekdays.wednesday'),
                    value: '2',
                },
                {
                    label: t('fields.weekdays.thursday'),
                    value: '3',
                },
                {
                    label: t('fields.weekdays.friday'),
                    value: '4',
                },
                {
                    label: t('fields.weekdays.saturday'),
                    value: '5',
                },
                {
                    label: t('fields.weekdays.sunday'),
                    value: '6',
                },
            ],
        };
    },

    onChange(value) {
        if (this.props.onChange) {
            this.props.onChange(value);
        }
    },

    render() {
        let label;
        if (this.props.label) {
            label = <label htmlFor={name} className="control-label">{ this.props.label }</label>;
        }

        let formGroupClassName = 'form-group form-group-weekdays';
        if (this.props.errors) {
            formGroupClassName += ' has-error';
        }

        const errors = this.renderErrors(this.props.errors);

        return (
            <div className={formGroupClassName}>
                { label }
                <CheckboxesField
                    type="checkbox.button"
                    value={this.props.value}
                    values={this.props.days}
                    onChange={this.onChange}
                />
                { errors }
            </div>
        );
    },
});

module.exports = WeekdaysField;
