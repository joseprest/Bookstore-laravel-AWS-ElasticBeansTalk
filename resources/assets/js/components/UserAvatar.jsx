const React = require('react-external');
const Panneau = require('panneau');
const Requests = require('../requests/index');

const PictureField = Panneau.Components.Fields.Picture;

const UserAvatar = React.createClass({
    propTypes: {
        user: React.PropTypes.shape({
            avatar: React.PropTypes.object,
        }).isRequired,
        uploadUrl: React.PropTypes.string,
    },

    getDefaultProps() {
        return {
            uploadUrl: '/panneau/upload/picture',
        };
    },

    getInitialState() {
        return {
            value: this.props.user.avatar,
        };
    },

    onChange(picture) {
        if (picture === null) {
            Requests.Users.removeAvatar(this.props.user)
                    .then(this.onPictureRemoved);
        } else {
            Requests.Users.updateAvatar(this.props.user, picture)
                    .then(this.onPictureUpdated);
        }
    },

    onPictureUpdated(user) {
        this.setState({
            value: user.avatar,
        });
    },

    onPictureRemoved() {
        this.setState({
            value: null,
        });
    },

    render() {
        return (
            <PictureField
                value={this.state.value}
                uploadUrl={this.props.uploadUrl}
                onChange={this.onChange}
            />
        );
    },
});

module.exports = UserAvatar;
