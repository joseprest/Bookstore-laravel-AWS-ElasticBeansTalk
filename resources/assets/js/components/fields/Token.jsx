const React = require('react-external');
const TokenField = require('bootstrap-tokenfield');
const Bloodhound = require('typeahead.js');
const $ = require('jquery-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');

const TokenFieldComponent = React.createClass({

    propTypes: {
        label: React.PropTypes.string,
        name: React.PropTypes.string,
        value: React.PropTypes.oneOfType([
            React.PropTypes.string,
            React.PropTypes.array,
        ]),
        urlPrefetch: React.PropTypes.string,
        url: React.PropTypes.string,
        placeholder: React.PropTypes.string,
        queryWildcard: React.PropTypes.string,
        suggestionTemplate: React.PropTypes.string,
        helpText: React.PropTypes.string,
        transformResponse: React.PropTypes.func,
        onChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            label: null,
            name: null,
            value: null,
            urlPrefetch: null,
            url: null,
            placeholder: t('fields.token.enter_search'),
            queryWildcard: '%QUERY',
            suggestionTemplate: '<div><%= label %></div>',
            helpText: null,
            transformResponse: null,
            onChange: null,
        };
    },

    componentDidMount() {
        $(this.inputNode).on('change', this.onChange);
        this.createTokenField();
    },

    componentDidUpdate(prevProps) {
        const currentValue = this.props.value ? this.props.value.sort().join(',') : '';
        let prevValue;

        if (this.tokenfield) {
            prevValue = this.tokenfield.getTokens().map(
                token => token.value,
            ).sort().join(',');
        } else {
            prevValue = prevProps.value ? prevProps.value.sort().join(',') : '';
        }

        if (currentValue !== prevValue) {
            this.tokenfield.setTokens(this.props.value);
        }

        const currentUrl = this.props.url;
        const prevUrl = prevProps.url;

        if (currentUrl !== prevUrl) {
            this.createTokenField();
        }
    },

    componentWillUnmount() {
        if (this.tokenfield) {
            this.tokenfield.destroy();
            this.tokenfield = null;
        }

        $(this.inputNode).off('change', this.onChange);
    },

    onChange(e) {
        const value = $(e.currentTarget).val();

        if (this.props.onChange) {
            this.props.onChange(value.split(','));
        }
    },

    tokenfield: null,

    createTokenField() {
        if (this.tokenfield) {
            this.tokenfield.setTokens([]);
            this.tokenfield.destroy();
        }

        const bloodhoundOptions = {
            remote: {
                url: this.props.url,
                wildcard: this.props.queryWildcard,
                transform: this.transformResponse,
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            datumTokenizer(d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
        };

        if (this.props.urlPrefetch) {
            bloodhoundOptions.prefetch = {
                url: this.props.urlPrefetch,
                transform: this.transformResponse,
            };
        }

        this.bloodhound = new Bloodhound(bloodhoundOptions);
        this.bloodhound.initialize();

        this.tokenfield = new TokenField(this.inputNode, {
            tokens: this.props.value,
            typeahead: [null, {
                source: this.bloodhound.ttAdapter(),
                display: 'label',
                limit: Infinity,
                templates: {
                    suggestion: _.template(this.props.suggestionTemplate),
                },
            }],
        });

        return this.tokenfield;
    },

    transformResponse(response) {
        if (this.props.transformResponse && _.isFunction(this.props.transformResponse)) {
            return this.props.transformResponse(response);
        }

        return response;
    },

    render() {
        let label;
        if (this.props.label) {
            label = <label htmlFor={name} className="control-label">{ this.props.label }</label>;
        }

        let helpBlock;
        if (this.props.helpText && this.props.helpText.length) {
            helpBlock = <div className="help-block">{ this.props.helpText }</div>;
        }

        const value = Array.isArray(this.props.value)
            ? _.map(this.props.value, 'value').join(',')
            : this.props.value;

        return (
            <div className="form-group form-group-token">
                { label }
                <input type="hidden" name={this.props.name} value={value} />
                <input
                    type="text"
                    className="form-control"
                    ref={(node) => { this.inputNode = node; }}
                    placeholder={this.props.placeholder}
                />
                { helpBlock }
            </div>
        );
    },
});

module.exports = TokenFieldComponent;
