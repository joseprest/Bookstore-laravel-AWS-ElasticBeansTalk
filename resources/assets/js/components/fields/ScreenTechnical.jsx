const React = require('react-external');
const { t } = require('../../lib/trans');
const SubFieldMixin = require('../../mixins/SubField');
const ScreenResolutionField = require('./ScreenResolution');

const ScreenTechnicalField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        value: React.PropTypes.object,
        onChange: React.PropTypes.func,
    },

    mixins: [SubFieldMixin],

    getDefaultProps() {
        return {
            name: null,
            value: null,
            onChange: null,
        };
    },

    render() {
        const fieldScreenResolution = this.renderSubField(ScreenResolutionField, 'resolution', {});
        const fieldVendor = this.renderTextField('vendor', {
            wrapperClassName: 'col-xs-12 col-sm-4',
            label: t('fields.screen_technical.vendor'),
        });
        const fieldModel = this.renderTextField('model', {
            wrapperClassName: 'col-xs-12 col-sm-4',
            label: t('fields.screen_technical.model'),
        });
        const fieldSerialNumber = this.renderTextField('serial_number', {
            wrapperClassName: 'col-xs-12 col-sm-4',
            label: t('fields.screen_technical.serial_number'),
        });
        const fieldSize = this.renderTextField('size', {
            label: t('fields.screen_technical.size'),
        });

        return (
            <div className="form-group form-group-screen-technical">
                <div className="row">
                    { fieldVendor }
                    { fieldModel }
                    { fieldSerialNumber }
                </div>

                <div className="row">
                    <div className="col-sm-4">
                        { fieldSize }
                    </div>
                    <div className="col-sm-8">
                        { fieldScreenResolution }
                    </div>
                </div>
            </div>
        );
    },
});

module.exports = ScreenTechnicalField;
