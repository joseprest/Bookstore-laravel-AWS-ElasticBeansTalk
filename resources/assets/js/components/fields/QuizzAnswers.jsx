const React = require('react-external');
const QuizzAnswer = require('./QuizzAnswer');
const { t } = require('../../lib/trans');

const propTypes = {
    name: React.PropTypes.string,
    value: React.PropTypes.array,
    onChange: React.PropTypes.func,
};

const defaultProps = {
    name: null,
    value: null,
    onChange: null,
};

const QuizzAnswers = ({ name, value, onChange }) => (
    <div className="form-group form-group-list form-group-answers">
        <label className="control-label">{ t('fields.answers.label') }</label>
        <div className="form-group-actions">
            <button
                type="button"
                className="btn btn-primary"
                onClick={() => onChange([...(value || []), {}])}
            >
                { t('fields.answers.add_btn') }
            </button>
        </div>
        {value === null || value.length === 0 ? (
            <div className="well">
                { t('fields.answers.no_answer') }
            </div>
        ) : (
            <div className="form-group-items">
                {(value || []).map((answer, answerIndex) => (
                    <div className="panel panel-default">
                        <div className="panel-body">
                            <QuizzAnswer
                                name={`${name}[${answerIndex}]`}
                                value={answer}
                                onRemove={() => onChange([...value.splice(answerIndex, 1)])}
                                onChange={newValue =>
                                    onChange(
                                        value.map((val, index) => (
                                            index === answerIndex ? newValue : val
                                        )),
                                    )
                                }
                            />
                        </div>
                    </div>
                ))}
            </div>
        )}
    </div>
);

QuizzAnswers.propTypes = propTypes;
QuizzAnswers.defaultProps = defaultProps;

module.exports = QuizzAnswers;
