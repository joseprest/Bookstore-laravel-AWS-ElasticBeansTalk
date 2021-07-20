const React = require('react-external');
const _ = require('lodash-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');

const ModalActions = Panneau.Actions.Modal;

const TeamItem = React.createClass({
    propTypes: {
        editable: React.PropTypes.bool,
        data: React.PropTypes.object,
        onUpdate: React.PropTypes.func,
        onRemove: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            editable: false,
            data: {},
            onUpdate: null,
            onRemove: null,
        };
    },

    onClick(e) {
        e.preventDefault();

        Panneau.dispatch(ModalActions.openModal('TeamEdit', {
            element: this.mainNode,
            placement: 'auto',
            data: this.props.data,
            onComplete: this.onModalComplete,
            onClose: this.onModalClose,
        }));
    },

    onModalComplete(data) {
        if (data === null) {
            if (this.props.onRemove) {
                this.props.onRemove();
            }

            return;
        }

        if (this.props.onUpdate) {
            this.props.onUpdate(data);
        }
    },

    render() {
        let name = _.get(this.props, 'data.name');
        if (!name || !name.length) {
            name = _.get(this.props, 'data.email');
        }

        const role = _.get(this.props, 'data.role.name', null);
        const type = _.get(this.props, 'data.type', 'invitation');
        const link = URL.route('organisation.team.show', {
            user_id: _.get(this.props, 'data.id'),
        });
        const imageStyle = {
            backgroundImage: `url(${_.get(this.props, 'data.avatar.link', '/img/avatar.png')})`,
        };

        let description = <span>{ role }</span>;
        if (type === 'invitation') {
            description = <span>{ t('invitation.processing') }</span>;
        }

        const editable = this.props.editable;
        let thumbnail;
        if (editable) {
            thumbnail = (
                <a
                    href={link}
                    className="thumbnail thumbnail-small thumbnail-horizontal"
                    onClick={this.onClick}
                >
                    <span className="image" style={imageStyle}>&nbsp;</span>
                    <span className="caption">
                        <strong>{ name }</strong>
                        { description }
                    </span>
                </a>
            );
        } else {
            thumbnail = (
                <div className="thumbnail thumbnail-small thumbnail-horizontal">
                    <span className="image" style={imageStyle}>&nbsp;</span>
                    <span className="caption">
                        <strong>{ name }</strong>
                        { description }
                    </span>
                </div>
            );
        }

        return (
            <div
                className="list-item list-item-sm"
                ref={(node) => { this.mainNode = node; }}
            >
                { thumbnail }
            </div>
        );
    },
});

module.exports = TeamItem;
