const Panneau = require('panneau');
const React = require('react-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');

const SelectField = Panneau.Components.Fields.Select;

const FilterSelectField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        value: React.PropTypes.string,
        onChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            name: 'filter_select',
            value: null,
            onChange: null,
        };
    },

    onSelectChange(value) {
        if (this.props.onChange) {
            this.props.onChange(value);
        }
    },

    render() {
        const value = this.props.value;
        const props = _.cloneDeep(_.omit(this.props, ['name', 'value', 'label', 'onChange']));

        if (props.values) {
            props.values.unshift({
                value: null,
                label: t('fields.filter.select'),
            });
        }

        return (
            <div className="form-group form-group-filter-select form-group-row">
                <div className="form-group-inline form-group-label">
                    { t('fields.filter.is') }
                </div>
                <div className="form-group-inline">
                    <SelectField
                        {...props}
                        name={this.props.name}
                        value={value}
                        onChange={this.onSelectChange}
                    />
                </div>
            </div>
        );
    },
});

module.exports = FilterSelectField;
