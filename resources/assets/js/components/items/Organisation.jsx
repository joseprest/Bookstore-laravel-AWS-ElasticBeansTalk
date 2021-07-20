/* eslint-disable react/prefer-stateless-function */
const React = require('react-external');

const OrganisationItem = React.createClass({
    propTypes: {
        data: React.PropTypes.shape({
            name: React.PropTypes.string.isRequired,
            link: React.PropTypes.string.isRequired,
        }).isRequired,
    },

    render() {
        const name = this.props.data.name;
        const link = this.props.data.link;

        return (
            <div className="list-item list-item-organisation">
                <a href={link} className="thumbnail">
                    <span className="caption caption-lg">
                        <span>{ name }</span>
                    </span>
                </a>
            </div>
        );
    },
});

module.exports = OrganisationItem;
