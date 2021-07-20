/* eslint-disable react/no-find-dom-node */
const React = require('react-external');
const ReactDOM = require('react-dom-external');
const Panneau = require('panneau');
const $ = require('jquery-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');
const BubblesSelector = require('../BubblesSelector');

const Modal = Panneau.Components.Modals.Modal;

const BubblesSelectModal = React.createClass({
    propTypes: {
        visible: React.PropTypes.bool,
        channelsParams: React.PropTypes.object,
        onComplete: React.PropTypes.func,
        closeModal: React.PropTypes.func,
        onOpen: React.PropTypes.func,
        onClose: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            visible: false,
            channelsParams: null,
            onComplete: null,
            closeModal: null,
            onOpen: null,
            onClose: null,
        };
    },

    getInitialState() {
        return {
            selectedBubbles: [],
            filters: [],
            channel: null,
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

    onResize() {
        this.resizeList();
    },

    onLoaded() {
        this.resizeList();
    },

    onChannelChange(channel) {
        this.setState({
            channel,
        });
    },

    onFiltersChange(filters) {
        this.resizeList();

        this.setState({
            filters,
        });
    },

    onBubblesChange(bubbles) {
        this.resizeList();

        this.setState({
            selectedBubbles: bubbles,
        });
    },

    onBubbleClickAdd(bubble) {
        this.setState(
            {
                selectedBubbles: [bubble],
            },
            () => {
                this.onCompleteWithBubbles(this.state.selectedBubbles);
            }
        );
    },

    onAddSelectedClick() {
        this.onCompleteWithBubbles(this.state.selectedBubbles);
    },

    onAddFilterClick() {
        const filters = [
            {
                name: 'channel_id',
                value: this.state.channel.id,
            },
            ...this.state.filters,
        ];

        this.onCompleteWithFilters(filters);
    },

    onCompleteWithBubbles(bubbles) {
        this.props.onComplete({
            bubbles,
        });
    },

    onCompleteWithFilters(filters) {
        this.props.onComplete({
            filters,
        });
    },

    onClickClose() {
        if (this.props.closeModal) {
            this.props.closeModal();
        } else {
            this.close();
        }
    },

    onOpen() {
        this.resizeList();

        if (this.props.onOpen) {
            this.props.onOpen();
        }
    },

    onClose() {
        this.setState(
            {
                selectedBubbles: [],
                filters: [],
                channel: null,
            },
            () => {
                if (this.props.onClose) {
                    this.props.onClose();
                }
            }
        );
    },

    resizeList() {
        const $el = $(ReactDOM.findDOMNode(this));
        const $header = $el.find('.modal-header');
        const $footer = $el.find('.modal-footer');
        const $children = $el.find('.bubbles-channel > div');
        const $list = $el.find('.list-bubbles');
        const hasList = $list.length > 0;
        const $body = $el.find('.modal-body');

        if (hasList) {
            $el.find('.modal-body').css('height', 'auto');
        }

        const windowHeight = $(window).height();
        const bodyPadding = ($body.outerHeight() - $body.height());
        const headerFooterHeight = $header.outerHeight() + $footer.outerHeight();
        let listHeight = windowHeight - headerFooterHeight - 100 - bodyPadding;
        let childrenHeight = 0;

        $children.each((index, element) => {
            if (!$(element).is('.list-bubbles')) {
                childrenHeight += $(element).outerHeight();
            }
        });

        listHeight -= hasList ? childrenHeight : 0;

        if (!hasList) {
            $el.find('.modal-body').css('height', `${listHeight}px`);
        } else {
            $list.css('height', `${listHeight - bodyPadding}px`);
        }
    },

    render() {
        const bubblesCount = this.state.selectedBubbles.length;
        let addSelectedButton = null;

        if (!bubblesCount) {
            addSelectedButton = (
                <button type="button" className="btn btn-default" disabled="disabled">
                    { t('slideshow.content.select_slides') }
                </button>
            );
        } else {
            const label = bubblesCount === 1
                ? t('slideshow.content.actions.add_one')
                : t('slideshow.content.actions.add_nb', { nb: bubblesCount });
            addSelectedButton = (
                <button
                    type="button"
                    className="btn btn-default"
                    onClick={this.onAddSelectedClick}
                >
                    { label }
                </button>
            );
        }

        const addFilterButtonProps = {
            type: 'button',
            className: 'btn btn-default',
            onClick: this.onAddFilterClick,
        };

        if (!_.get(this.state, 'filters.0.value')) {
            addFilterButtonProps.disabled = 'disabled';
        }

        const addFilterButton = (
            <button {...addFilterButtonProps}>
                { t('slideshow.content.actions.add_as_rule') }
            </button>
        );

        return (
            <Modal
                closeModal={this.props.closeModal}
                visible={this.props.visible}
                onClose={this.onClose}
                onOpen={this.onOpen}
            >
                <div className="modal-header">
                    <button
                        type="button"
                        className="close"
                        onClick={this.onClickClose}
                        aria-label={t('general.actions.close')}
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 className="modal-title">
                        { t('slideshow.content.addition.title') }
                    </h4>
                </div>
                <div className="modal-body">
                    <BubblesSelector
                        channelsParams={this.props.channelsParams}
                        filters={this.state.filters}
                        checkedBubbles={this.state.selectedBubbles}
                        onChannelLoaded={this.onLoaded}
                        onBubblesLoaded={this.onLoaded}
                        onChange={this.onBubblesChange}
                        onClickAdd={this.onBubbleClickAdd}
                        onChannelChange={this.onChannelChange}
                        onFiltersChange={this.onFiltersChange}
                    />
                </div>
                <div className="modal-footer">
                    <button type="button" className="btn btn-default" onClick={this.onClickClose}>
                        { t('general.actions.cancel') }
                    </button>
                    { addSelectedButton }
                    { addFilterButton }
                </div>
            </Modal>
        );
    },
});

module.exports = BubblesSelectModal;
