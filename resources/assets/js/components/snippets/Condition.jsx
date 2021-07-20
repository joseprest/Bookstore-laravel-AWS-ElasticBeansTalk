const React = require('react-external');

function ConditionSnippet(props) {
    const title = props.data.title;
    let description = props.data.description;
    description = description.split('\n');
    description = description.map((text, index) => {
        const key = `description_${index}`;

        return (
            <div key={key}>{ text }</div>
        );
    });

    return (
        <div className="snippet snippet-condition">
            <div className="snippet-caption">
                <h4 className="title">{ title }</h4>
                <div className="description">
                    { description }
                </div>
            </div>
        </div>
    );
}

ConditionSnippet.propTypes = {
    data: React.PropTypes.shape({
        title: React.PropTypes.string,
        description: React.PropTypes.string,
    }),
};

ConditionSnippet.defaultProps = {
    data: {
        title: '',
        description: '',
    },
};

module.exports = ConditionSnippet;
