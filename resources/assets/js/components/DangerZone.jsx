const React = require('react-external');
const { t } = require('../lib/trans');
const Panneau = require('panneau');
const _ = require('lodash-external');

const FormComponent = Panneau.Components.Form.Component;

const DangerZone = React.createClass({
    propTypes: {
        form: React.PropTypes.object,
        delete: React.PropTypes.bool,
        deleteTitle: React.PropTypes.string,
        deleteDescription: React.PropTypes.string,
        deleteConfirmation: React.PropTypes.string,
        onDelete: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            delete: true,
            deleteTitle: t('components.danger_zone.title'),
            deleteDescription: '',
            deleteConfirmation: t('components.danger_zone.confirmation'),
            form: {
                buttons: [
                    {
                        className: 'btn btn-danger',
                        type: 'submit',
                        label: t('general.actions.delete'),
                    },
                ],
            },
            onDelete: null,
        };
    },

    getInitialState() {
        return {
            opened: false,
        };
    },

    onClickToggle(e) {
        e.preventDefault();

        this.setState({
            opened: !this.state.opened,
        });
    },

    onSubmitDelete(e) {
        // eslint-disable-next-line no-alert
        if (!confirm(this.props.deleteConfirmation)) {
            e.preventDefault();
        } else if (this.props.onDelete) {
            this.props.onDelete(e);
        }
    },

    render() {
        const optionsStyle = {
            display: 'none',
        };

        if (this.state.opened) {
            optionsStyle.display = 'block';
        }

        let toggleClassName = 'glyphicon glyphicon-menu-down';

        if (this.state.opened) {
            toggleClassName = 'glyphicon glyphicon-menu-up';
        }

        const formProps = _.omit(this.props.form, ['errors', 'onSubmit']);
        let deleteOptions = null;

        if (this.props.delete) {
            const deleteTitle =
                this.props.deleteTitle && this.props.deleteTitle.length
                    ? <h4>{ this.props.deleteTitle }</h4>
                    : null;
            const deleteDescription =
                this.props.deleteDescription && this.props.deleteDescription.length
                    ? <p>{ this.props.deleteDescription }</p>
                    : null;

            deleteOptions = (
                <div className="options" style={optionsStyle}>
                    <div className="option">
                        { deleteTitle }
                        { deleteDescription }
                        <FormComponent {...formProps} onSubmit={this.onSubmitDelete} />
                    </div>
                </div>
            );
        }

        return (
            <div className="danger-zone">
                <div className="toggle">
                    <button type="button" onClick={this.onClickToggle}>
                        <span className="glyphicon glyphicon-exclamation-sign" />
                        { t('components.danger_zone.legend') } <span className={toggleClassName} />
                    </button>
                </div>

                { deleteOptions }
            </div>
        );
    },
});

module.exports = DangerZone;
