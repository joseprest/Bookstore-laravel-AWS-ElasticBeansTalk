const Panneau = require('panneau');
const React = require('react-external');
const _ = require('lodash-external');
const Immutable = require('immutable-external');
const { t } = require('../../lib/trans');

const Filter = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        channel: React.PropTypes.object,
        onChange: React.PropTypes.func,
        value: React.PropTypes.object,
        filters: React.PropTypes.array,
    },

    getDefaultProps() {
        return {
            name: 'filter',
            channel: null,
            onChange: null,
            value: null,
            filters: [],
        };
    },

    onFilterMenuClick(e, filter) {
        e.preventDefault();

        this.onValueChange({
            name: filter.name,
        });
    },

    onFilterValueChange(value) {
        this.onValueChange('value', value);
    },

    onValueChange(name, value) {
        const currentValue = Immutable.fromJS(this.props.value || {});
        const newValue = _.isObject(name) ? Immutable.fromJS(name) : currentValue.set(name, value);

        if (currentValue !== newValue) {
            const valueJS = newValue.toJS();

            if (this.props.onChange) {
                this.props.onChange(valueJS);
            }
        }
    },

    renderFiltersMenu(filters) {
        return filters.map(this.renderFiltersMenuItem);
    },

    renderFiltersMenuItem(filter, index) {
        const key = `filter_menu${index}`;
        const onClick = (e) => {
            this.onFilterMenuClick(e, filter);
        };

        return (
            <li key={key}>
                <a href="#" onClick={onClick}>{ filter.label }</a>
            </li>
        );
    },

    render() {
        const filterName = _.get(this.props, 'value.name', null);
        let filter = _.find(this.props.filters, 'name', filterName);

        if (filter) {
            filter = {
                ...filter,
                ...(_.get(this.props, 'value', {})),
            };
        }

        const filterValue = _.get(this.props, 'value.value', null);
        const filterLabel = filter ? filter.label : t('fields.filter.filter_by');
        const filtersMenu = this.renderFiltersMenu(this.props.filters || []);
        const filterFieldName = `${this.props.name}[name]`;
        const valueFieldName = `${this.props.name}[value]`;
        let filterFieldGroup = null;

        if (filter) {
            const filterType = `filter${filter.type.replace(/[^a-z0-9]/gi, '').toLowerCase()}`;
            const FilterField = _.find(
                Panneau.Components.Fields,
                (Component, key) => key.replace(/[^a-z0-9]/gi, '').toLowerCase() === filterType
            );

            if (FilterField) {
                const other = _.omit(filter, ['name', 'value', 'label', 'onChange']);

                filterFieldGroup = (
                    <div className="form-group-inline">
                        <FilterField
                            channel={this.props.channel}
                            filter={filter}
                            name={valueFieldName}
                            value={filterValue}
                            onChange={this.onFilterValueChange}
                            {...other}
                        />
                    </div>
                );
            }
        }

        return (
            <div className="form-group form-group-filter form-group-row">
                <div className="form-group-inline">
                    <button
                        type="button"
                        className="btn btn-default dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span className="tag">{ filterLabel }</span>
                        <span className="glyphicon glyphicon glyphicon-menu-down" />
                    </button>
                    <ul className="dropdown-menu">
                        { filtersMenu }
                    </ul>
                    <input type="hidden" name={filterFieldName} value={filterName} />
                </div>
                { filterFieldGroup }
            </div>
        );
    },
});

module.exports = Filter;
