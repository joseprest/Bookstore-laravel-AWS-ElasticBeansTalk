const React = require('react-external');
const Immutable = require('immutable-external');
const FilterField = require('./Filter');

const FiltersField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        onChange: React.PropTypes.func,
        value: React.PropTypes.array,
        filters: React.PropTypes.array,
        channel: React.PropTypes.object.isRequired,
    },

    getDefaultProps() {
        return {
            name: 'filters',
            onChange: null,
            value: null,
            filters: [],
        };
    },

    onClickAdd(e) {
        e.preventDefault();

        const value = this.props.value || [];

        if (!value.length) {
            value.push({});
        }

        value.push({});

        if (this.props.onChange) {
            this.props.onChange(value);
        }
    },

    onFilterChange(value, index) {
        const currentValue = Immutable.fromJS(this.props.value || []);
        const newValue = currentValue.set(index, value);

        if (newValue !== currentValue) {
            const finalValue = newValue.toJS();

            if (this.props.onChange) {
                this.props.onChange(finalValue);
            }
        }
    },

    onFilterClickRemove(e, index) {
        e.preventDefault();

        let value = this.props.value || [];
        const currentValue = Immutable.fromJS(value);
        const newValue = currentValue.delete(index);

        value = newValue.toJS();

        if (this.props.onChange) {
            this.props.onChange(value);
        }
    },

    renderFilters(filters) {
        const lastIndex = filters.length - 1;

        return filters.map((filter, index) => {
            const isLast = index === lastIndex;
            return this.renderFilter(filter, index, isLast);
        });
    },

    renderFilter(filter, index, isLast) {
        const key = `filter_${index}`;
        const name = `${this.props.name}[${index}]`;

        // Event handlers
        const onChange = (value) => {
            this.onFilterChange(value, index);
        };

        const onClickRemove = (e) => {
            this.onFilterClickRemove(e, index);
        };

        let addButton;
        if (isLast) {
            addButton = (
                <button
                    type="button"
                    className="btn btn-default glyphicon glyphicon-plus-sign"
                    onClick={this.onClickAdd}
                />
            );
        }

        let removeButton;
        if (filter && filter.name) {
            removeButton = (
                <button
                    type="button"
                    className="btn btn-default glyphicon glyphicon-minus-sign"
                    onClick={onClickRemove}
                />
            );
        }

        const filters = this.props.filters;

        return (
            <div key={key} className="form-group-list-item form-group-row">
                <div className="form-group-inline">
                    <FilterField
                        channel={this.props.channel}
                        filters={filters}
                        name={name}
                        value={filter}
                        onChange={onChange}
                    />
                </div>
                <div className="form-group-inline form-group-actions">
                    <div className="btn-group">
                        { addButton }
                        { removeButton }
                    </div>
                </div>
            </div>
        );
    },

    render() {
        const value = this.props.value || [];

        if (!value.length) {
            value.push({});
        }

        const filters = this.renderFilters(value);

        return (
            <div className="form-group form-group-list form-group-filters">
                { filters }
            </div>
        );
    },
});

module.exports = FiltersField;
