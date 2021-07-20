const React = require('react-external');
const { t } = require('../../lib/trans');
const SubFieldMixin = require('../../mixins/SubField');
const ToggleField = require('./Toggle');

const QuizzAnswer = React.createClass({
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
        const inputText = this.renderTextField('text', {
            label: t('fields.answers.text_label'),
            wrapperClassName: 'col-sm-8',
        });

        const goodField = this.renderSubField(ToggleField, 'good', {
            wrapper: false,
        });

        const inputExplanation = this.renderTextField('explanation', {
            label: t('fields.answers.explanation_label'),
            type: 'textarea',
        });

        return (
            <div className="form-group form-group-answer">
                <div className="row">
                    {inputText}
                    <div className="col-sm-4">
                        <label className="control-label" htmlFor="good">{t('fields.answers.good_label')}</label>
                        {goodField}
                    </div>
                </div>
                {inputExplanation}
            </div>
        );
    },
});

module.exports = QuizzAnswer;
