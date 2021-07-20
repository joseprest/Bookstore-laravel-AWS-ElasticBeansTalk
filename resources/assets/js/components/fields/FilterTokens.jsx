const React = require('react-external');
const _ = require('lodash-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');
const TokenField = require('./Token');

const FilterTokensField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        value: React.PropTypes.array,
        filter: React.PropTypes.object,
        channel: React.PropTypes.object.isRequired,
        onChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            name: 'filter_select',
            value: [],
            filter: null,
            onChange: null,
        };
    },

    getInitialState() {
        let value = this.props.value;
        const firstValue = _.get(this.props, 'value.0');
        const tokens = _.get(this.props, 'filter.tokens');

        if (_.isObject(firstValue)) {
            value = this.props.value;
        } else if (Array.isArray(tokens) && Array.isArray(value) && tokens.length <= value.length) {
            value = _.get(this.props, 'filter.tokens');
        }

        return {
            value,
        };
    },

    componentWillReceiveProps(nextProps) {
        if (nextProps.value !== this.state.value) {
            this.setState({
                value: nextProps.value,
            });
        }
    },

    onSelectChange(rawValue) {
        const value = Array.isArray(rawValue)
            ? rawValue.map(v => v.trim())
            : rawValue.trim();

        if (this.props.onChange) {
            this.props.onChange(value);
        }
    },

    transformResponse(response) {
        return _.get(response, 'data.channelFilterTokens', []);
    },

    render() {
        const value = this.state.value;
        const props = _.cloneDeep(_.omit(this.props, ['name', 'value', 'label', 'onChange']));

        if (props.values) {
            props.values.unshift({
                value: null,
                label: t('fields.filter.select'),
            });
        }

        const query = `
            query getChannelFilterTokens(
                $id: String!,
                $filter: String!,
                $search: String
            ) {
                channelFilterTokens(
                    id: $id,
                    filter: $filter,
                    search: $search
                ) {
                    value
                    label
                }
            }
        `;
        const params = {
            id: this.props.channel.id,
            filter: this.props.filter.name,
            search: '%QUERY',
        };
        const graphQLUrl = Panneau.config('graphQL.url');
        const urlQuery = encodeURIComponent(query);
        const urlParams = JSON.stringify(params);
        const url = `${graphQLUrl}?query=${urlQuery}&params=${urlParams}`;

        return (
            <div className="form-group form-group-filter-select form-group-row">
                <div className="form-group-inline form-group-label">
                    { t('fields.filter.is') }
                </div>
                <div className="form-group-inline">
                    <TokenField
                        {...props}
                        name={this.props.name}
                        value={value}
                        url={url}
                        transformResponse={this.transformResponse}
                        onChange={this.onSelectChange}
                    />
                </div>
            </div>
        );
    },
});

module.exports = FilterTokensField;
