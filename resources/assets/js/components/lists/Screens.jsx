const Panneau = require('panneau');
const React = require('react-external');
const ScreenItem = require('../items/Screen');
const ScreenCreate = require('../items/ScreenCreate');

const ListActions = Panneau.Actions.List;

const ScreensList = React.createClass({
    propTypes: {
        canCreate: React.PropTypes.bool,
        name: React.PropTypes.string.isRequired,
        items: React.PropTypes.array.isRequired,
    },

    mixins: [Panneau.Mixins.List],

    getDefaultProps() {
        return {
            canCreate: false,
        };
    },

    onCreate(screen) {
        Panneau.dispatch(ListActions.addItemIfUnique(screen, 'id', this.props.name));
    },

    getListItemComponent() {
        return ScreenItem;
    },

    render() {
        const items = this.renderListItems(this.props.items);

        let addButton;
        if (this.props.canCreate) {
            addButton = (
                <ScreenCreate onCreate={this.onCreate} />
            );
        }

        return (
            <div className="list list-thumbnails list-screens">
                { items }
                { addButton }
            </div>
        );
    },
});

module.exports = ScreensList;
