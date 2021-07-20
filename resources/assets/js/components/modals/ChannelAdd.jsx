const React = require('react-external');
const Panneau = require('panneau');
const _ = require('lodash-external');
const Immutable = require('immutable-external');
const { t } = require('../../lib/trans');
const Requests = require('../../requests/index');

const Modal = Panneau.Components.Modals.Modal;

const ChannelAddModal = React.createClass({
    propTypes: {
        visible: React.PropTypes.bool,
        channels: React.PropTypes.array,
        channelsAdded: React.PropTypes.array,
        closeModal: React.PropTypes.func,
        onAdd: React.PropTypes.func,
        onRemove: React.PropTypes.func,
        onClose: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            visible: false,
            channels: null,
            channelsAdded: [],
            closeModal: null,
            onAdd: null,
            onRemove: null,
            onClose: null,
        };
    },

    getInitialState() {
        return {
            channels: this.props.channels,
            channelsAdded: this.props.channelsAdded,
        };
    },

    componentWillMount() {
        if (!this.state.channels) {
            Requests.Channels.load()
                .then(this.onChannelsLoaded);
        }
    },

    onChannelsLoaded(channels) {
        this.setState({
            channels,
            channel: channels && channels.length ? channels[0] : null,
        });
    },

    onClickClose() {
        if (this.props.closeModal) {
            this.props.closeModal();
        } else {
            this.close();
        }
    },

    onAdd(channel) {
        const currentChannels = Immutable.fromJS(this.state.channelsAdded);
        const newChannels = currentChannels.push(channel);

        if (currentChannels !== newChannels) {
            this.setState({
                channelsAdded: newChannels.toJS(),
            });
        }

        if (this.props.onAdd) {
            this.props.onAdd(channel);
        }
    },

    onRemove(channel) {
        const index = _.findIndex(
            this.state.channelsAdded,
            channelAdded => String(channelAdded.id) === String(channel.id)
        );

        if (index >= 0) {
            const currentChannels = Immutable.fromJS(this.state.channelsAdded);
            const newChannels = currentChannels.delete(index);

            if (currentChannels !== newChannels) {
                this.setState({
                    channelsAdded: newChannels.toJS(),
                });
            }
        }

        if (this.props.onRemove) {
            this.props.onRemove(channel);
        }
    },

    onClose() {
        if (this.props.onClose) {
            this.props.onClose();
        }
    },

    renderChannels(channels) {
        return channels.map(this.renderChannel);
    },

    renderChannel(channel) {
        const added = !!_.find(
            this.state.channelsAdded,
            channelAdded => String(channelAdded.id) === String(channel.id)
        );
        const btnLabel = added ? t('general.actions.remove') : t('general.actions.add');
        const btnIcon = `glyphicon ${added ? 'glyphicon-minus' : 'glyphicon-plus'}`;

        const onClick = () => {
            if (added) {
                this.onRemove(channel);
            } else {
                this.onAdd(channel);
            }
        };

        const key = `channel_${channel.id}`;
        const title = _.get(channel, 'snippet.title');
        const description = _.get(channel, 'snippet.description');
        const picture = _.get(channel, 'snippet.picture.link');
        const imageStyle = {
            backgroundImage: picture ? `url(${picture})` : 'none',
        };

        let descriptionParagraph = null;
        if (description && description.length) {
            descriptionParagraph = (
                <p>{ description }</p>
            );
        }

        return (
            <div key={key} className="list-item list-item-col-4">
                <div className="thumbnail">
                    <div className="image" style={imageStyle} />
                </div>
                <div className="detail">
                    <h4>{ title }</h4>
                    { descriptionParagraph }

                    <button className="btn btn-default btn-sm" onClick={onClick}>
                        <span className={btnIcon} /> { btnLabel }
                    </button>
                </div>
            </div>
        );
    },

    render() {
        let channels = null;
        if (this.state.channels) {
            channels = this.renderChannels(this.state.channels);
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
                        { t('channel.creation.title') }
                    </h4>
                </div>
                <div className="modal-body">
                    <div className="row">
                        <div className="col-sm-3">
                            <ul className="nav nav-pills nav-stacked">
                                <li role="presentation" className="active">
                                    <a href="#">{ t('channel.categories.culture') }</a>
                                </li>
                            </ul>
                        </div>
                        <div className="col-sm-9">
                            <div className="list list-add-channel">
                                <div className="list-items">
                                    <div className="list-item">
                                        <h3>{ t('channel.categories.culture') }</h3>
                                        <div className="list list-thumbnails">
                                            { channels }
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-footer">
                    <button type="button" className="btn btn-default" onClick={this.onClickClose}>
                        { t('general.actions.close') }
                    </button>
                </div>
            </Modal>
        );
    },
});

module.exports = ChannelAddModal;
