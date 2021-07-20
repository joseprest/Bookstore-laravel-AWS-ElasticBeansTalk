const React = require('react-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');
const Immutable = require('immutable-external');
const SubFieldMixin = require('../../mixins/SubField');
const PositionField = require('./Position');
const gmap = require('../../lib/gmap');

const AddressField = React.createClass({
    propTypes: {
        withPosition: React.PropTypes.bool,
        name: React.PropTypes.string,
        value: React.PropTypes.object,
        onChange: React.PropTypes.func,
    },

    mixins: [SubFieldMixin],

    getDefaultProps() {
        return {
            withPosition: true,
            name: null,
            value: {},
            onChange: null,
        };
    },

    onGeocode(latLng) {
        this.onSubFieldChange('position', {
            latitude: latLng.lat(),
            longitude: latLng.lng(),
        });
    },

    onPositionChange(value) {
        const currentValue = Immutable.fromJS(this.props.value || {});
        const newValue = currentValue.mergeIn(['position'], value);

        if (currentValue !== newValue) {
            const changedValue = newValue.toJS();

            if (this.props.onChange) {
                this.props.onChange(changedValue);
            }
        }
    },

    onChange(value) {
        this.checkAddress(value);
    },

    // eslint-disable-next-line func-names
    checkAddress: _.debounce(function (value) {
        if (!value.address || !value.address.length) {
            return;
        }

        const address = [
            value.address,
        ];

        if (value.city && value.city.length) {
            address.push(value.city);
        } else {
            address.push('Montreal');
        }

        if (value.postalcode && value.postalcode.length) {
            address.push(value.postalcode);
        }

        gmap.loadGMap()
            .then(() => gmap.geocode(address.join(', ')))
            .then(this.onGeocode);
    }, 500),

    render() {
        const inputName = this.renderTextField('name', {
            label: t('fields.location.name'),
            wrapperClassName: 'col-xs-12 col-md-12',
        });
        const inputAddress = this.renderTextField('address', {
            label: t('fields.location.address'),
            wrapperClassName: 'col-xs-12 col-md-12',
        });
        const inputCity = this.renderTextField('city', {
            label: t('fields.location.city'),
            wrapperClassName: 'col-xs-8 col-md-8',
        });
        const inputPostalcode = this.renderTextField('postalcode', {
            label: t('fields.location.postal_code'),
            wrapperClassName: 'col-xs-4 col-md-4',
        });

        const positionValue = _.get(this.props, 'value.position', {});
        const positionName = `${this.props.name}[position]`;

        let position = null;
        if (this.props.withPosition) {
            position = (
                <PositionField
                    name={positionName}
                    value={positionValue}
                    onChange={this.onPositionChange}
                />
            );
        }

        return (
            <div className="form-group form-group-address">
                <div className="row">
                    { inputName }
                    { inputAddress }
                    { inputCity }
                    { inputPostalcode }
                </div>
                { position }
            </div>
        );
    },
});

module.exports = AddressField;
