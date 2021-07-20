/* eslint-disable jsx-a11y/label-has-for */
const Panneau = require('panneau');
const React = require('react-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');

const DateRangeField = Panneau.Components.Fields.DateRange;

const ConditionDateRangeField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        value: React.PropTypes.array,
        onChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            name: 'condition_daterange',
            value: null,
            onChange: null,
        };
    },

    onCheckChange(e) {
        if (e.target.checked) {
            this.onChange([]);
        } else {
            this.onChange(null);
        }
    },

    onChange(value) {
        const start = _.get(value, 'start', null);
        const end = _.get(value, 'end', null);
        const defaultValue = value !== null ? [] : null;
        const range = start || end ? [start, end] : defaultValue;

        if (this.props.onChange) {
            this.props.onChange(range);
        }
    },

    render() {
        const value = this.props.value;
        const checked = !!value;
        const start = _.get(value || [], '0', null);
        const end = _.get(value || [], '1', null);
        const fieldValue = start || end ? { start, end } : null;

        let field = null;
        if (value) {
            field = (
                <DateRangeField
                    type="date"
                    name={this.props.name}
                    value={fieldValue}
                    onChange={this.onChange}
                />
            );
        }

        return (
            <div className="form-group form-group-condition form-group-condition-daterange">
                <div className="form-group form-group-checkbox">
                    <div className="checkbox">
                        <label>
                            <input
                                type="checkbox"
                                autoComplete="off"
                                checked={checked}
                                onChange={this.onCheckChange}
                            />
                            <span>{ t('fields.condition_daterange.only_some_dates') }</span>
                        </label>
                    </div>
                </div>
                { field }
            </div>
        );
    },
});

module.exports = ConditionDateRangeField;
