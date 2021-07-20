/* eslint-disable jsx-a11y/no-static-element-interactions */
const React = require('react-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');
const URL = require('../../lib/url');

const ChannelItem = React.createClass({
    propTypes: {
        withRemoveButton: React.PropTypes.bool,
        screen: React.PropTypes.object.isRequired,
        data: React.PropTypes.object.isRequired,
        onRemove: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            withRemoveButton: true,
            onRemove: null,
        };
    },

    onClickRemove() {
        if (this.props.onRemove) {
            this.props.onRemove(this.props.data);
        }
    },

    render() {
        const title = _.get(this.props, 'data.snippet.title');
        const picture = _.get(this.props, 'data.snippet.picture.link');
        const imageStyle = {
            backgroundImage: picture ? `url(${picture})` : 'none',
        };
        const url = URL.route('organisation.screens.channel', {
            screen_id: this.props.screen.id,
            channel_id: this.props.data.id,
        });

        let removeBtn;
        if (this.props.withRemoveButton) {
            removeBtn = (
                <span
                    className="remove-btn glyphicon glyphicon-minus-sign"
                    onClick={this.onClickRemove}
                    title={t('channel.actions.remove')}
                />
            );
        }

        return (
            <div className="list-item list-item-xs list-item-channel">
                <a href={url} className="thumbnail">
                    <span className="image" style={imageStyle} />
                    <span className="caption">{ title }</span>
                </a>
                { removeBtn }
            </div>
        );
    },
});

module.exports = ChannelItem;
