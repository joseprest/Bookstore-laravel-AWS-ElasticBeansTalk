/* eslint-disable jsx-a11y/label-has-for */
const React = require('react-external');
const { t } = require('../../lib/trans');

const WeekdaysField = require('../fields/Weekdays');

const ConditionWeekdaysField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        value: React.PropTypes.array,
        onChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            name: 'condition_weekdays',
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
        if (this.props.onChange) {
            this.props.onChange(value);
        }
    },

    render() {
        const value = this.props.value;
        const checked = !!value;

        let field = null;
        if (value) {
            field = (
                <WeekdaysField name={this.props.name} value={value} onChange={this.onChange} />
            );
        }

        return (
            <div className="form-group form-group-condition form-group-condition-weekdays">
                <div className="form-group form-group-checkbox">
                    <div className="checkbox">
                        <label>
                            <input
                                type="checkbox"
                                autoComplete="off"
                                checked={checked}
                                onChange={this.onCheckChange}
                            />
                            <span>{ t('fields.condition_weekdays.only_some_days') }</span>
                        </label>
                    </div>
                </div>
                { field }
            </div>
        );
    },
});

module.exports = ConditionWeekdaysField;
