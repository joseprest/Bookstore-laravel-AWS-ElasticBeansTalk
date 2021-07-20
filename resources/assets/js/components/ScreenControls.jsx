const React = require('react-external');
const moment = require('moment');
const _ = require('lodash-external');
const Panneau = require('panneau');
const Immutable = require('immutable-external');
const { t } = require('../lib/trans');
const Requests = require('../requests/index');

const ScreenControls = React.createClass({
    propTypes: {
        dateFormat: React.PropTypes.string,
        lastPingMax: React.PropTypes.number.isRequired,
        screen: React.PropTypes.object.isRequired,
        isAdmin: React.PropTypes.bool,
        pings: React.PropTypes.array,
        commands: React.PropTypes.array,
    },

    getDefaultProps() {
        return {
            dateFormat: 'D MMM YYYY, H:mm:ss',
            isAdmin: false,
            pings: [],
            commands: [],
        };
    },

    getInitialState() {
        return {
            pings: this.props.pings.map(this.convertDates),
            commands: this.props.commands.map(this.convertDates),
        };
    },

    componentDidMount() {
        _.bindAll(this, 'onPubnubMessage');

        const channel = Panneau.pubnub.getChannelWithNamespace(`screen.${this.props.screen.id}`);
        Panneau.pubnub.addListener({
            message: this.onPubnubMessage,
        });

        Panneau.pubnub.subscribe({
            channels: [channel],
        });
    },

    componentWillUnmount() {
        const channel = Panneau.pubnub.getChannelWithNamespace(`screen.${this.props.screen.id}`);
        Panneau.pubnub.removeListener({
            message: this.onPubnubMessage,
        });
        Panneau.pubnub.unsubscribe({
            channels: [channel],
        });
    },

    onClickCommand(e, command, args) {
        e.preventDefault();
        this.sendCommand(command, args);
    },

    onPubnubMessage(data) {
        const message = data.message;
        switch (message.event) {
            case 'ping.changed':
                this.addPing(_.get(message, 'data.ping'));
                break;
            case 'command.changed':
                this.addCommand(_.get(message, 'data.command'));
                break;
            default:
                // Do nothing
                break;
        }
    },

    getLastPing() {
        const pings = this.state.pings;
        let lastPing = null;
        let lastPingDate = null;

        pings.forEach((ping) => {
            if (!lastPingDate || ping.created_at.isAfter(lastPingDate)) {
                lastPingDate = ping.created_at;
                lastPing = ping;
            }
        });

        return lastPing;
    },

    getLogs() {
        const pings = this.state.pings;
        const commands = this.state.commands;
        const dateFormat = this.props.dateFormat;
        let logs = [];

        pings.forEach((ping) => {
            logs.push({
                id: `ping-${ping.id}`,
                type: 'ping',
                timestamp: ping.created_at.format('X'),
                date: ping.created_at.format(dateFormat),
                message: t('screen.log.server_ping'),
                details: this.getDetailsFromPing(ping),
                state: 'success',
            });
        });

        commands.forEach((command) => {
            const dateText = [command.created_at.format(dateFormat)];
            const commandText = [command.command].concat(command.arguments);
            const date = moment(command.created_at, 'YYYY-MM-DD HH:mm:ss');

            if (command.sended_at) {
                dateText.push(t('screen.log.sent_at', { time: command.sended_at.format(dateFormat) }));
            }

            if (command.executed_at) {
                dateText.push(t('screen.log.executed_at', { time: command.executed_at.format(dateFormat) }));
            }

            const executedState = command.return_code === 0 ? 'success' : 'error';

            logs.push({
                id: `command-${command.id}`,
                type: 'command',
                timestamp: date.format('X'),
                date: dateText.join(' | '),
                message: t('screen.log.command', { command: commandText.join(' ') }),
                details: command.output,
                state: command.executed ? executedState : 'pending',
            });
        });

        logs = _.sortBy(logs, log => log.timestamp)
            .reverse();

        return logs;
    },

    getDetailsFromPing(ping) {
        const details = [];

        if (ping.uptime) {
            const time = moment.duration(ping.uptime, 'seconds').humanize();
            details.push(t('screen.ping_data.online_since', { time }));
        }

        if (ping.memory_free) {
            const amount = this.formatSize(ping.memory_free);
            details.push(t('screen.ping_data.memory_free', { amount }));
        }

        if (ping.memory_total) {
            const amount = this.formatSize(ping.memory_total);
            details.push(t('screen.ping_data.memory_total', { amount }));
        }
        if (ping.load && ping.load.length) {
            const loads = ping.load.map(load => load.toFixed(2));
            details.push(t('screen.ping_data.load', { load: loads.join(' | ') }));
        }

        return details.join('\n');
    },

    createOnClickCommand(command, args) {
        return (e) => {
            this.onClickCommand(e, command, args);
        };
    },

    convertDates(it) {
        const newItem = {};
        Object.entries(it).forEach(([key, date]) => {
            if (key.match(/_at$/) && date) {
                newItem[key] = moment(date, 'YYYY-MM-DD HH:mm:ss');
            } else {
                newItem[key] = date;
            }
        });

        return newItem;
    },

    formatSize(bytes) {
        if (bytes === 0) return '0 Byte';

        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10);
        const qty = Math.round(bytes / (1024 ** i), 2);
        const size = sizes[i];

        return `${qty} ${size}`;
    },

    addItem(key, it) {
        const item = this.convertDates(it);
        const items = this.state[key];
        const index = _.findIndex(
            items,
            itFind => itFind.id === item.id
        );
        const currentValue = Immutable.fromJS(items);
        let newValue;

        if (index !== -1) {
            newValue = currentValue.set(index, item);
        } else {
            newValue = currentValue.push(item);
        }

        if (newValue !== currentValue) {
            this.setState({
                [key]: newValue.toJS(),
            });
        }
    },

    addPing(ping) {
        this.addItem('pings', ping);
    },

    addCommand(command) {
        this.addItem('commands', command);
    },

    sendCommand(command, args) {
        Requests.Screens.sendCommand(this.props.screen, command, args);
    },

    renderActions() {
        let restartButton;
        let updateButton;

        if (this.props.isAdmin) {
            restartButton = (
                <div className="btn-group">
                    <button
                        type="button"
                        className="btn btn-default dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        {t('screen.commands.actions.restart')} <span className="caret" />
                    </button>
                    <ul className="dropdown-menu">
                        <li><a href="#" onClick={this.createOnClickCommand('reboot')}>{t('screen.commands.computer')}</a></li>
                        <li><a href="#" onClick={this.createOnClickCommand('restart-server')}>{t('screen.commands.server')}</a></li>
                        <li><a href="#" onClick={this.createOnClickCommand('restart-electron')}>{t('screen.commands.application')}</a></li>
                        <li><a href="#" onClick={this.createOnClickCommand('restart-manivelle')}>{t('screen.commands.server_application')}</a></li>
                        <li><a href="#" onClick={this.createOnClickCommand('restart-tunnel')}>{t('screen.commands.ssh_tunnel')}</a></li>
                    </ul>
                </div>
            );

            updateButton = (
                <div className="btn-group">
                    <button
                        type="button"
                        className="btn btn-default dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        {t('screen.commands.actions.update')} <span className="caret" />
                    </button>
                    <ul className="dropdown-menu">
                        <li><a href="#" onClick={this.createOnClickCommand('git-update')}>{t('screen.commands.code')}</a></li>
                        <li><a href="#" onClick={this.createOnClickCommand('npm-update')}>{t('screen.commands.npm')}</a></li>
                    </ul>
                </div>
            );
        } else {
            restartButton = (
                <div className="btn-group">
                    <button type="button" className="btn btn-default" onClick={this.createOnClickCommand('restart-manivelle')}>
                        {t('screen.commands.actions.restart')}
                    </button>
                </div>
            );

            updateButton = (
                <div className="btn-group">
                    <button type="button" className="btn btn-default" onClick={this.createOnClickCommand('update')}>
                        {t('screen.commands.actions.update')}
                    </button>
                </div>
            );
        }

        return (
            <div className="screen-actions">
                <div className="btn-toolbar" role="toolbar">
                    { restartButton }
                    { updateButton }
                </div>
            </div>
        );
    },

    renderStatus() {
        const lastPing = this.getLastPing();

        if (!lastPing) {
            return null;
        }

        const now = moment();
        const lastPingDuration = moment.duration(lastPing.created_at.diff(now));
        const durationMinutes = lastPingDuration.asMinutes();
        const status = (durationMinutes > this.props.lastPingMax ? 'disconnected' : 'connected');
        const statusIcon = (status === 'connected' ? 'glyphicon-ok-circle' : 'glyphicon-remove-circle');
        const statusText = (status === 'connected' ? t('screen.statuses.online') : t('screen.statuses.offline'));

        return (
            <div className="screen-status">
                <div className="icon">
                    <span className={`glyphicon ${statusIcon}`} />
                </div>
                <div className="status">
                    <h4>{ statusText }</h4>
                    <p>{t('screen.log.last_ping', { time: lastPingDuration.humanize(true) })}</p>
                </div>
            </div>
        );
    },

    renderLogs(logs) {
        return logs.map(this.renderLog);
    },

    renderLog(log) {
        const key = `log-${log.id}`;
        const target = `details-${log.id}`;

        let details = null;

        if (log.details && log.details.length) {
            details = (
                <div className="details">
                    <div className="actions">
                        <button
                            type="button"
                            className="btn btn-default btn-xs"
                            data-target={`#${target}`}
                            data-toggle="collapse"
                        >
                            {t('screen.log.details')}
                        </button>
                    </div>
                    <div id={target} className="collapse">
                        <pre>{ log.details }</pre>
                    </div>
                </div>
            );
        }

        let icon = null;

        if (log.state && log.state === 'success') {
            icon = (
                <div className="label label-success">
                    <span className="glyphicon glyphicon-ok" />
                </div>
            );
        } else if (log.state && log.state === 'pending') {
            icon = (
                <div className="label label-warning">
                    <span className="glyphicon glyphicon-hourglass" />
                </div>
            );
        } else if (log.state && log.state === 'error') {
            icon = (
                <div className="label label-danger">
                    <span className="glyphicon glyphicon-remove" />
                </div>
            );
        }

        return (
            <div className="screen-log" key={key}>
                <div className="pull-right">
                    { icon }
                </div>
                <div className="message">
                    { log.message }
                </div>
                <div className="date">
                    { log.date }
                </div>
                { details }
            </div>
        );
    },

    render() {
        const logs = this.renderLogs(this.getLogs());
        const actions = this.renderActions();
        const status = this.renderStatus();

        return (
            <div className="screen-controls">
                <div className="screen-header">
                    <div className="row">
                        <div className="col-sm-8">
                            { actions }
                        </div>
                        <div className="col-sm-4">
                            { status }
                        </div>
                    </div>
                </div>
                <hr />
                <div className="screen-logs">
                    { logs }
                </div>
            </div>
        );
    },
});

module.exports = ScreenControls;
