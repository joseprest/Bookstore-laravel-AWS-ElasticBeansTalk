const React = require('react-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');

const ModalActions = Panneau.Actions.Modal;

const TeamAddItem = React.createClass({
    propTypes: {
        onAdd: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            onAdd: null,
        };
    },

    onClick(e) {
        e.preventDefault();

        Panneau.dispatch(ModalActions.openModal('TeamInvite', {
            element: this.mainNode,
            onComplete: this.onModalComplete,
            onClose: this.onModalClose,
        }));
    },

    onModalComplete(invitation) {
        if (this.props.onAdd) {
            this.props.onAdd(invitation);
        }
    },

    render() {
        return (
            <div
                className="list-item list-item-sm list-item-add"
                ref={(node) => { this.mainNode = node; }}
            >
                <a
                    href={URL.route('organisation.team.create')}
                    className="thumbnail thumbnail-small thumbnail-horizontal"
                    onClick={this.onClick}
                >
                    <span className="icon">
                        <span className="glyphicon glyphicon-plus" />
                    </span>
                    <span className="caption">
                        { t('team.actions.add_member') }
                    </span>
                </a>
            </div>
        );
    },
});

module.exports = TeamAddItem;
