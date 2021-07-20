const Panneau = require('panneau');
const React = require('react-external');
const TeamAdd = require('../items/TeamAdd');
const TeamItem = require('../items/Team');

const ListActions = Panneau.Actions.List;

const TeamList = React.createClass({
    propTypes: {
        canAdd: React.PropTypes.bool,
        canEdit: React.PropTypes.bool,
        name: React.PropTypes.string.isRequired,
        items: React.PropTypes.array.isRequired,
    },

    mixins: [Panneau.Mixins.List],

    getDefaultProps() {
        return {
            canAdd: false,
            canEdit: false,
        };
    },

    onAdd(invitation) {
        Panneau.dispatch(ListActions.addItemIfUnique(invitation, 'id', this.props.name));
    },

    onItemUpdate(index, user) {
        Panneau.dispatch(ListActions.updateItem(index, user, this.props.name));
    },

    onItemRemove(index) {
        Panneau.dispatch(ListActions.removeItem(index, this.props.name));
    },

    getListItemComponent() {
        return TeamItem;
    },

    getListItemProps(it, index) {
        return {
            editable: this.props.canEdit,
            onUpdate: (user) => {
                this.onItemUpdate(index, user);
            },
            onRemove: () => {
                this.onItemRemove(index);
            },
        };
    },

    render() {
        const items = this.renderListItems(this.props.items);

        let addItem = null;
        if (this.props.canAdd) {
            addItem = (
                <TeamAdd onAdd={this.onAdd} />
            );
        }

        return (
            <div className="list list-thumbnails list-team">
                { items }
                { addItem }
            </div>
        );
    },
});

module.exports = TeamList;
