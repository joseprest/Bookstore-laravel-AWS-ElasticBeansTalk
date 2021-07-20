const React = require('react-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');

const ModalActions = Panneau.Actions.Modal;

const PlaylistBubbleAddItem = React.createClass({
    propTypes: {
        bubblesSelectChannelsParams: React.PropTypes.object,
        onAdd: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            bubblesSelectChannelsParams: null,
            onAdd: null,
        };
    },

    onClick(e) {
        e.preventDefault();

        Panneau.dispatch(ModalActions.openModal('BubblesSelect', {
            channelsParams: this.props.bubblesSelectChannelsParams,
            onComplete: this.onModalComplete,
            onClose: this.onModalClose,
        }));
    },

    onModalComplete(result) {
        Panneau.dispatch(ModalActions.closeModal('BubblesSelect'));

        if (this.props.onAdd) {
            this.props.onAdd(result);
        }
    },

    render() {
        return (
            <div className="list-item list-item-add">
                <a href="#" className="thumbnail" onClick={this.onClick}>
                    <span className="glyphicon glyphicon-plus" />
                    <strong>
                        { t('slideshow.content.actions.add_slides') }
                    </strong>
                </a>
            </div>
        );
    },
});

module.exports = PlaylistBubbleAddItem;
