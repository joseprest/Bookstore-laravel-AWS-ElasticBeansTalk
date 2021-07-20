const React = require('react-external');
const Panneau = require('panneau');
const Immutable = require('immutable-external');
const { t } = require('../../lib/trans');

const Modal = Panneau.Components.Modals.Modal;
const Form = Panneau.Components.Form.Component;

const ConditionsModal = React.createClass({
    propTypes: {
        visible: React.PropTypes.bool,
        condition: React.PropTypes.shape({
            fields: React.PropTypes.object,
        }),
        closeModal: React.PropTypes.func,
        onComplete: React.PropTypes.func,
        onClose: React.PropTypes.func,
    },

    contextTypes: {
        store: React.PropTypes.object,
    },

    getDefaultProps() {
        return {
            visible: false,
            condition: null,
            closeModal: null,
            onComplete: null,
            onClose: null,
        };
    },

    getInitialState() {
        const condition = this.props.condition ? (this.props.condition.fields || {}) : {};
        const filteredCondition = {};

        Object.entries(condition).forEach(([key, val]) => {
            if (val !== null) {
                filteredCondition[key] = val;
            }
        });

        return {
            condition: filteredCondition,
        };
    },

    onFormFieldChange(name, value) {
        const currentValue = Immutable.fromJS(this.state.condition || {});
        let newValue;

        if (value === null) {
            newValue = currentValue.delete(name);
        } else {
            newValue = currentValue.set(name, value);
        }

        if (currentValue !== newValue) {
            this.setState({
                condition: newValue.toJS(),
            });
        }
    },

    onClickClose() {
        if (this.props.closeModal) {
            this.props.closeModal();
        } else {
            this.close();
        }
    },

    onClickSave() {
        if (this.props.onComplete) {
            this.props.onComplete(this.state.condition);
        }
    },

    onClose() {
        if (this.props.onClose) {
            this.props.onClose();
        }
    },

    onConditionsSaved(it) {
        if (this.props.onComplete) {
            this.props.onComplete(it);
        }
    },

    render() {
        const formFields = [
            {
                name: 'days',
                type: 'condition_weekdays',
            },
            {
                name: 'daterange',
                type: 'condition_daterange',
            },
            {
                name: 'date',
                type: 'condition_date',
            },
            {
                name: 'time',
                type: 'condition_time',
            },
        ];

        const formData = this.state.condition || {};

        return (
            <Modal
                closeModal={this.props.closeModal}
                visible={this.props.visible}
                onClose={this.onClose}
            >
                <div className="modal-header">
                    <button
                        type="button"
                        className="close"
                        onClick={this.onClickClose}
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 className="modal-title">
                        { t('slideshow.content.conditions.title') }
                    </h4>
                </div>
                <div className="modal-body">
                    <Form
                        name="condition"
                        data={formData}
                        fields={formFields}
                        onFieldChange={this.onFormFieldChange}
                    />
                </div>
                <div className="modal-footer">
                    <button
                        type="button"
                        className="btn btn-default"
                        onClick={this.onClickClose}
                    >
                        { t('general.actions.cancel') }
                    </button>
                    <button
                        type="button"
                        className="btn btn-primary"
                        onClick={this.onClickSave}
                    >
                        { t('general.actions.save') }
                    </button>
                </div>
            </Modal>
        );
    },
});

module.exports = ConditionsModal;
