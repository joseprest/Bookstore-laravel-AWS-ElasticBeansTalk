const React = require('react-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');

const ScreenItem = React.createClass({
    propTypes: {
        data: React.PropTypes.object,
    },

    getDefaultProps() {
        return {
            data: {},
        };
    },

    render() {
        const name = _.get(this.props, 'data.snippet.title');
        let description = _.get(this.props, 'data.snippet.description');
        const id = this.props.data.id;
        const online = _.get(this.props, 'data.online', false);
        // const online = true;
        const link = URL.route(
            'organisation.screens.show',
            {
                screen_id: id,
            }
        );

        const offline = null;
        // if (!online) {
        //     offline = (
        //         <span className="banner-offline">
        //             { t('screen.statuses.offline') }
        //         </span>
        //     );
        // }

        if (description && description.length) {
            description = (
                <span className="small">
                    { description }
                </span>
            );
        }

        const infosLabel = [];
        const vendor = _.get(this.props, 'data.vendor');
        const size = _.get(this.props, 'data.size');
        let infos = null;

        if (vendor && vendor.length) {
            infosLabel.push(vendor);
        }

        if (size && size.length) {
            infosLabel.push(size);
        }

        if (infosLabel.length) {
            infos = (
                <span className="small">
                    { infosLabel.join(' - ') }
                </span>
            );
        }

        const icon = null;
        // let icon;
        // if (online) {
        //     icon = (
        //         <span className="glyphicon glyphicon-ok" />
        //     );
        // } else {
        //     icon = '!';
        // }

        return (
            <div className="list-item list-item-sm">
                <a href={link} className="thumbnail">
                    { offline }
                    <span className="icon">
                        <span className="manivelle">
                            <span className="inner">
                                <span className="screen">
                                    <span className="status-icon">
                                        {icon}
                                    </span>
                                </span>
                                <span className="handle" />
                            </span>
                        </span>
                    </span>
                    <span className="caption">
                        <strong>{ name }</strong>
                        { infos }
                    </span>
                </a>
            </div>
        );
    },
});

module.exports = ScreenItem;
