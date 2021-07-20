/* eslint-disable react/no-unused-prop-types */
const Panneau = require('panneau');
const React = require('react-external');
const _ = require('lodash-external');

const RoleField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        label: React.PropTypes.string,
        errors: React.PropTypes.array,
        value: React.PropTypes.oneOfType([
            React.PropTypes.string,
            React.PropTypes.number,
        ]),
        onChange: React.PropTypes.func,
    },

    mixins: [Panneau.Mixins.FieldErrors],

    getDefaultProps() {
        return {
            name: 'role',
            label: null,
            errors: null,
            value: null,
            onChange: null,
        };
    },

    onChange(value) {
        if (this.props.onChange) {
            this.props.onChange(value);
        }
    },

    renderSelect() {
        const selectProps = _.omit(this.props, ['onChange', 'label']);
        const Select = Panneau.Components.Fields.Select;

        return (
            <Select {...selectProps} onChange={this.onChange} />
        );
    },

    render() {
        let label;
        if (this.props.label) {
            label = (
                <label htmlFor={name} className="control-label">
                    { this.props.label }
                </label>
            );
        }

        const select = this.renderSelect();
        let formGroupClassName = 'form-group form-group-role';

        if (this.props.errors) {
            formGroupClassName += ' has-error';
        }

        const errors = this.renderErrors(this.props.errors);

        return (
            <div className={formGroupClassName}>
                { label }
                { select }
                { errors }
                <div className="description" />
            </div>
        );
    },
});

module.exports = RoleField;
