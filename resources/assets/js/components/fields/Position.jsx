const React = require('react-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');
const SubFieldMixin = require('../../mixins/SubField');
const MapComponent = require('../Map');

const PositionField = React.createClass({
    propTypes: {
        onChange: React.PropTypes.func,
    },

    mixins: [SubFieldMixin],

    getDefaultProps() {
        return {
            onChange: null,
        };
    },

    onMapPositionChange(position) {
        if (this.props.onChange) {
            this.props.onChange(position);
        }
    },

    onMapRadiusChange(value) {
        this.onSubFieldChange('radius', Math.round(value));
    },

    render() {
        const inputLatitude = this.renderTextField('latitude', {
            label: t('fields.position.latitude'),
            wrapperClassName: 'col-xs-6',
        });
        const inputLongitude = this.renderTextField('longitude', {
            label: t('fields.position.longitude'),
            wrapperClassName: 'col-xs-6',
        });
        const latitude = _.get(this.props, 'value.latitude', null);
        const longitude = _.get(this.props, 'value.longitude', null);
        const radius = _.get(this.props, 'value.radius', 0);

        return (
            <div className="form-group form-group-position">
                <div className="row">
                    <div className="col-sm-12">
                        <div className="row">
                            { inputLatitude }
                            { inputLongitude }
                        </div>
                    </div>
                    <div className="col-sm-12">
                        <MapComponent
                            zoom={17}
                            latitude={latitude ? parseFloat(latitude) : null}
                            longitude={longitude ? parseFloat(longitude) : null}
                            radius={parseInt(radius, 10)}
                            onPositionChange={this.onMapPositionChange}
                            onRadiusChange={this.onMapRadiusChange}
                        />
                    </div>
                </div>
            </div>
        );
    },
});

module.exports = PositionField;
