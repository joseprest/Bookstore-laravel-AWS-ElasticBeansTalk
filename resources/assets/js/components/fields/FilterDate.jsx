const Panneau = require('panneau');
const React = require('react-external');
const Immutable = require('immutable-external');
const { t } = require('../../lib/trans');

const DateField = Panneau.Components.Fields.Date;

const FilterDateField = React.createClass({
    propTypes: {
        value: React.PropTypes.oneOfType([
            React.PropTypes.string,
            React.PropTypes.array,
        ]),
        onChange: React.PropTypes.func,
        types: React.PropTypes.array,
    },

    getDefaultProps() {
        return {
            value: null,
            onChange: null,
            types: [
                {
                    name: 'future',
                    label: t('fields.filter_date.future'),
                },
                {
                    name: 'date',
                    label: t('fields.filter_date.date'),
                },
                {
                    name: 'range',
                    label: t('fields.filter_date.range'),
                },
            ],
        };
    },

    getInitialState() {
        let type = null;

        if (this.props.value === 'future') {
            type = 'future';
        } else if (Array.isArray(this.props.value)) {
            type = 'range';
        } else if (this.props.value && this.props.value.length) {
            type = 'date';
        }

        return {
            type,
        };
    },

    componentDidUpdate(prevProps, prevState) {
        if (prevState.type !== this.state.type) {
            if (this.state.type === 'future') {
                this.props.onChange('future');
            } else {
                this.props.onChange(null);
            }
        }
    },

    onTypeClick(type) {
        this.setState({
            type: type.name,
        });
    },

    onDateChange(dateValue) {
        this.props.onChange(dateValue);
    },

    onStartDateChange(dateValue) {
        let value = this.props.value || ['', ''];
        const currentValue = Immutable.fromJS(value);
        const newValue = currentValue.set(0, dateValue);

        if (currentValue !== newValue) {
            value = newValue.toJS();
            this.props.onChange(value);
        }
    },

    onEndDateChange(dateValue) {
        let value = this.props.value || ['', ''];
        const currentValue = Immutable.fromJS(value);
        const newValue = currentValue.set(1, dateValue);

        if (currentValue !== newValue) {
            value = newValue.toJS();
            this.props.onChange(value);
        }
    },

    renderTypes(types) {
        return types.map(this.renderType);
    },

    renderType(type, index) {
        const key = `type_${index}`;
        const onClick = (e) => {
            e.preventDefault();
            this.onTypeClick(type);
        };

        return (
            <li key={key}>
                <a href="#" onClick={onClick}>{ type.label }</a>
            </li>
        );
    },

    render() {
        let value = this.props.value;
        const valueType = this.state.type;
        const type = this.props.types.find(
            type_ => type_.name === valueType
        );

        let typeLabel = t('fields.filter.select');

        if (type) {
            typeLabel = type.label;
        }

        const types = this.renderTypes(this.props.types);

        let dateField;
        if (type && type.name === 'date') {
            dateField = (
                <div className="form-group-inline">
                    <DateField value={value} onChange={this.onDateChange} />
                </div>
            );
        } else if (type && type.name === 'range') {
            value = value || [];
            const valueStart = value[0];
            const valueEnd = value[1];

            dateField = (
                <div className="form-group-inline">
                    <div className="form-group-row">
                        <div className="form-group-inline form-group-inline-daterange">
                            <DateField value={valueStart} onChange={this.onStartDateChange} />
                        </div>
                        <div className="form-group-inline form-group-label">
                            { t('fields.filter_date.and') }
                        </div>
                        <div className="form-group-inline form-group-inline-daterange">
                            <DateField value={valueEnd} onChange={this.onEndDateChange} />
                        </div>
                    </div>
                </div>
            );
        }

        return (
            <div className="form-group form-group-filter-date form-group-row">
                <div className="form-group-inline form-group-label">
                    { t('fields.filter.is') }
                </div>
                <div className="form-group-inline">
                    <button
                        type="button"
                        className="btn btn-default dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span className="tag">{ typeLabel }</span>
                        <span className="glyphicon glyphicon glyphicon-menu-down" />
                    </button>
                    <ul className="dropdown-menu">
                        { types }
                    </ul>
                </div>
                { dateField }
            </div>
        );
    },
});

module.exports = FilterDateField;
