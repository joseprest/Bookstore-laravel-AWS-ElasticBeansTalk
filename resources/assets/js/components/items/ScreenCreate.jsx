const React = require('react-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');

const ModalActions = Panneau.Actions.Modal;

const ScreenCreateItem = React.createClass({
    propTypes: {
        onCreate: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            canCreate: false,
            onCreate: null,
        };
    },

    onClick(e) {
        e.preventDefault();

        Panneau.dispatch(ModalActions.openModal('ScreenCreate', {
            element: this.mainNode,
            onComplete: this.onModalComplete,
            onClose: this.onModalClose,
        }));
    },

    onModalComplete(screen) {
        if (this.props.onCreate) {
            this.props.onCreate(screen);
        }
    },

    render() {
        return (
            <div
                className="list-item list-item-sm list-item-add"
                ref={(node) => { this.mainNode = node; }}
            >
                <a href="#" className="thumbnail" onClick={this.onClick}>
                    <span className="icon">
                        <span className="glyphicon glyphicon-plus btn-icon-screen" />
                    </span>

                    <span className="caption">
                        { t('screen.actions.create') }
                    </span>
                </a>
            </div>
        );
    },
});

module.exports = ScreenCreateItem;
