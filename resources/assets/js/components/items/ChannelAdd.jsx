const React = require('react-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');

const ModalActions = Panneau.Actions.Modal;

const ChannelAddItem = React.createClass({
    propTypes: {
        channelsAdded: React.PropTypes.array,
        onAdd: React.PropTypes.func,
        onRemove: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            channelsAdded: [],
            onAdd: null,
            onRemove: null,
        };
    },

    onClick(e) {
        e.preventDefault();

        Panneau.dispatch(ModalActions.openModal('ChannelAdd', {
            channelsAdded: this.props.channelsAdded,
            onAdd: this.onModalAdd,
            onRemove: this.onModalRemove,
            onClose: this.onModalClose,
        }));
    },

    onModalAdd(channel) {
        if (this.props.onAdd) {
            this.props.onAdd(channel);
        }
    },

    onModalRemove(channel) {
        if (this.props.onRemove) {
            this.props.onRemove(channel);
        }
    },

    render() {
        return (
            <div className="list-item list-item-xs list-item-add">
                <a href="#" className="thumbnail" onClick={this.onClick}>
                    <span className="icon">
                        <span className="glyphicon glyphicon-plus" />
                    </span>
                    <span className="caption">
                        { t('channel.actions.add') }
                    </span>
                </a>
            </div>
        );
    },
});

module.exports = ChannelAddItem;
