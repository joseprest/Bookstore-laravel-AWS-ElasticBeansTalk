const Panneau = require('panneau');
const React = require('react-external');
const OrganisationItem = require('../items/Organisation');

const OrganisationsList = React.createClass({
    propTypes: {
        items: React.PropTypes.array.isRequired,
    },

    mixins: [Panneau.Mixins.List],

    getListItemComponent() {
        return OrganisationItem;
    },

    render() {
        const items = this.renderListItems(this.props.items);

        return (
            <div className="list list-rows list-organisations">
                { items }
            </div>
        );
    },
});

module.exports = OrganisationsList;
