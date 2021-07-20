/* eslint-disable react/no-find-dom-node */
const React = require('react-external');
const ReactDOM = require('react-dom-external');
const Panneau = require('panneau');
const _ = require('lodash-external');
const $ = require('jquery-external');
const { t } = require('../../lib/trans');
const BubblesChannelFiltersList = require('../lists/BubblesChannelFilters');

const Modal = Panneau.Components.Modals.Modal;
const ModalActions = Panneau.Actions.Modal;

const ChannelSettings = React.createClass({
    propTypes: {
        visible: React.PropTypes.bool,
        screen: React.PropTypes.object,
        channel: React.PropTypes.object,
        onChannelChange: React.PropTypes.func,
        onOpen: React.PropTypes.func,
        onClose: React.PropTypes.func,
        closeModal: React.PropTypes.func,
    },

    contextTypes: {
        store: React.PropTypes.object,
    },

    getDefaultProps() {
        return {
            visible: false,
            screen: null,
            channel: null,
            onChannelChange: null,
            onOpen: null,
            onClose: null,
            closeModal: null,
        };
    },

    componentDidMount() {
        $(window).on('resize', this.onResize);
        this.onResize();
    },

    componentDidUpdate() {
        this.resizeList();
    },

    componentWillUnmount() {
        $(window).off('resize', this.onResize);
    },

    onFiltersChange() {
        this.resizeList();
    },

    onBubblesLoaded() {
        this.resizeList();
    },

    onChannelChange(channel) {
        if (this.props.onChannelChange) {
            this.props.onChannelChange(channel);
        }
    },

    onResize() {
        this.resizeList();
    },

    onOpen() {
        this.resizeList();

        if (this.props.onOpen) {
            this.props.onOpen();
        }
    },

    onClickAdd(e) {
        e.preventDefault();
        this.context.store.dispatch(ModalActions.closeModal());
    },

    onClickClose() {
        if (this.props.closeModal) {
            this.props.closeModal();
        } else {
            this.close();
        }
    },

    onClose() {
        this.context.store.dispatch(ModalActions.openModal(
            'BubbleForm',
            {
                onComplete: this.onAddModalComplete,
                onClose: this.onAddModalClose,
            }
        ));

        if (this.props.onClose) {
            this.props.onClose();
        }
    },

    onAddModalComplete() {

    },

    onAddModalClose() {

    },

    resizeList() {
        const $el = $(ReactDOM.findDOMNode(this));
        const $header = $el.find('.modal-header');
        const $footer = $el.find('.modal-footer');
        const $children = $el.find('.bubbles-channel > div');
        const $list = $el.find('.list-bubbles');
        const windowHeight = $(window).height();
        let listHeight = windowHeight - $header.outerHeight() - $footer.outerHeight() - 100;

        $children.each(() => {
            if (!$(this).is('.list-bubbles')) {
                listHeight -= $(this).outerHeight();
            }
        });

        $list.css({
            height: `${listHeight}px`,
        });
    },

    render() {
        const filters = _.get(this.props, 'channel.screen_settings.filters', null);
        const canAddBubbles = _.get(this.props, 'channel.fields.settings.canAddBubbles', false);
        let addButton;

        if (canAddBubbles) {
            addButton = (
                <button
                    type="button"
                    className="btn btn-primary pull-left"
                    onClick={this.onClickAdd}
                >
                    <span className="glyphicon glyphicon-plus" /> { t('channel.add_content') }
                </button>
            );
        }

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
                        { t('channel.modification.title') }
                    </h4>
                </div>
                <div className="modal-body">
                    <BubblesChannelFiltersList
                        screen={this.props.screen}
                        channel={this.props.channel}
                        filters={filters}
                        onBubblesLoaded={this.onBubblesLoaded}
                        onFiltersChange={this.onFiltersChange}
                        onChannelChange={this.onChannelChange}
                    />
                </div>
                <div className="modal-footer">
                    { addButton }
                    <button
                        type="button"
                        className="btn btn-default"
                        onClick={this.onClickClose}
                    >
                        { t('general.actions.close') }
                    </button>
                </div>
            </Modal>
        );
    },
});

module.exports = ChannelSettings;
