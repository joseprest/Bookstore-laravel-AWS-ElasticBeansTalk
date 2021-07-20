const _ = require('lodash-external');
const Panneau = require('panneau');
const Immutable = require('immutable-external');
const React = require('react-external');

const TextField = Panneau.Components.Fields.Text;

const SubField = {
    onSubFieldChange(key, value) {
        const currentValue = Immutable.fromJS(this.props.value || {});
        let newValue = currentValue.set(key, value);

        if (currentValue !== newValue) {
            newValue = newValue.toJS();

            if (this.onChange) {
                this.onChange(newValue);
            }

            if (this.props.onChange) {
                this.props.onChange(newValue);
            }
        }
    },

    renderTextField(key, props) {
        return this.renderSubField(TextField, key, props);
    },

    renderSubField(FieldComponent, key, props) {
        const name = `${this.props.name}[${key}]`;
        const value = _.get(this.props, `value.${key}`, null);
        const subFieldProps = {
            wrapper: true,
            wrapperClassName: '',
            ...props,
        };

        const field = (
            <FieldComponent
                name={name}
                value={value}
                onChange={val => this.onSubFieldChange(key, val)}
                {...subFieldProps}
            />
        );

        if (subFieldProps.wrapper) {
            return (
                <div className={subFieldProps.wrapperClassName}>
                    { field }
                </div>
            );
        }

        return field;
    },
};

module.exports = SubField;
