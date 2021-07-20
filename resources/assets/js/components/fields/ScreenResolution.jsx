const React = require('react-external');
const { t } = require('../../lib/trans');
const SubFieldMixin = require('../../mixins/SubField');

const ScreenResolutionField = React.createClass({
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
        const inputResolutionX = this.renderTextField('x', {
            wrapperClassName: 'col-xs-6 col-md-6',
            prefix: 'x:',
            suffix: 'px',
        });
        const inputResolutionY = this.renderTextField('y', {
            wrapperClassName: 'col-xs-6 col-md-6',
            prefix: 'y:',
            suffix: 'px',
        });

        return (
            <div className="form-group form-group-screen-resolution">
                <label className="control-label" htmlFor="x">
                    { t('fields.screen_resolution.resolution') }
                </label>
                <div className="row">
                    { inputResolutionX }
                    { inputResolutionY }
                </div>
            </div>
        );
    },
});

module.exports = ScreenResolutionField;
