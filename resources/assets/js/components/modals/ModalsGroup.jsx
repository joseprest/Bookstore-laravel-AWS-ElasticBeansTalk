const React = require('react-external');
const _ = require('lodash-external');
const Panneau = require('panneau');
const ModalComponents = require('./index');

const ModalsGroup = React.createClass({
    propTypes: {
        modals: React.PropTypes.object,
        actions: React.PropTypes.bool.isRequired,
    },

    getDefaultProps() {
        return {
            modals: null,
        };
    },

    getInitialState() {
        return {
            modals: this.props.modals,
            nextModals: null,
        };
    },

    componentWillReceiveProps(nextProps) {
        const modalName = _.get(this.props, 'modals.name');
        const nextModalName = _.get(nextProps, 'modals.name');

        if (modalName && nextModalName && modalName !== nextModalName) {
            this.setState({
                nextModals: nextProps.modals,
            });
        } else {
            this.setState({
                modals: nextProps.modals,
                nextModals: null,
            });
        }
    },

    onModalClose(modal) {
        if (this.state.nextModals) {
            this.setState({
                modals: this.state.nextModals,
                nextModals: null,
            });
        } else if (modal.destroy) {
            this.props.actions.destroyModal(modal.name);
        }
    },

    render() {
        const actions = this.props.actions;
        let modal = null;
        const currentModals = this.state.modals || {};
        const { name, ...modalProps } = currentModals;
        const modalName = name;
        const onClose = modalProps.onClose || null;
        modalProps.onClose = () => {
            if (onClose) {
                onClose();
            }

            this.onModalClose(currentModals);
        };

        const Modals = Panneau.Components.Modals || ModalComponents;

        if (modalName) {
            const Modal = _.find(
                Modals,
                (Component, componentKey) => (
                    Component && componentKey.toLowerCase() === modalName.toLowerCase()
                )
            );

            if (Modal) {
                modal = (
                    <Modal {...modalProps} closeModal={actions.closeModal} />
                );
            }
        }

        return (
            <div className="modals-container">
                { modal }
            </div>
        );
    },

});

module.exports = ModalsGroup;
