const React = require('react-external');
const { t } = require('../../lib/trans');
const SubFieldMixin = require('../../mixins/SubField');

const SizeField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        value: React.PropTypes.object,
        onChange: React.PropTypes.func,
    },

    mixins: [SubFieldMixin],

    getDefaultProps() {
        return {
            name: '',
            value: null,
            onChange: null,
        };
    },

    render() {
        const inputSize = this.renderTextField('size', {
            label: t('fields.screen_technical.size'),
            wrapperClassName: 'col-xs-4 col-md-4',
        });
        const inputResolutionX = this.renderTextField('resolution_x', {
            wrapperClassName: 'col-xs-6 col-md-6',
            prefix: 'x:',
            suffix: 'px',
        });
        const inputResolutionY = this.renderTextField('resolution_y', {
            wrapperClassName: 'col-xs-6 col-md-6',
            prefix: 'y:',
            suffix: 'px',
        });

        return (
            <div className="form-group form-group-size">
                <div className="row">
                    { inputSize }
                    <div className="col-xs-8 col-md-8">
                        <label className="control-label" htmlFor="resolution_x">
                            { t('fields.screen_resolution.resolution') }
                        </label>
                        <div className="row">
                            { inputResolutionX }
                            { inputResolutionY }
                        </div>
                    </div>
                </div>
            </div>
        );
    },
});

module.exports = SizeField;
