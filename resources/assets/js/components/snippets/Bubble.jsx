const React = require('react-external');
const _ = require('lodash-external');
const Img = require('image');
const { t } = require('../../lib/trans');

let i = 0;

function BubbleSnippet(props) {
    const title = props.data.title;
    let description = _.get(props, 'data.description', '');
    let subtitle = _.get(props, 'data.subtitle', '');
    let image = _.get(props, 'data.picture_thumbnail.link', null);

    if (!image || !image.length) {
        image = _.get(props, 'data.picture.link', null);
        if (image && image.length) {
            image = Img.url(image, {
                thumbnail_snippet: true,
            });
        }
    }

    if (subtitle && subtitle.length) {
        subtitle = (
            <div className="subtitle">{ subtitle }</div>
        );
    } else {
        subtitle = null;
    }

    if (props.withDescription && description && description.length) {
        const lines = description.split('\n').map((text, index) => {
            const key = `description_${index}`;
            return (
                <div key={key}>{ text }</div>
            );
        });

        description = (
            <div className="description">
                { lines }
            </div>
        );
    } else {
        description = null;
    }

    let editButton;
    if (props.editable) {
        const onClickDelete = props.onClickDelete || (() => {});

        editButton = (
            <div className="btn-group btn-group-xs">
                <a href={props.editLink} className="btn btn-default">
                    { t('general.actions.edit') }
                </a>
                <button className="btn btn-default" onClick={onClickDelete}>
                    { t('general.actions.delete') }
                </button>
            </div>
        );
    }


    let img = null;
    if (props.type === 'filter') {
        img = (
            <div className="icon">
                <div className="glyphicon glyphicon-retweet" />
            </div>
        );
    } else if (image && image.length) {
        img = (
            <img src={image} className="image" alt={title} />
        );
    } else {
        img = (
            <div className="image" />
        );
    }

    const type = _.get(props, 'data.type');
    // We use the thumbnail picture url as key, or a generated key
    const key = _.get(props, 'data.picture_thumbnail.link', i);
    i += 1;

    return (
        <div key={key} className="snippet snippet-with-thumbnail snippet-bubble">
            <div className="snippet-thumbnail">{ img }</div>
            <div className="snippet-caption">
                <div className="small">{ type }</div>
                <h4 className="title">{ title }</h4>
                { subtitle }
                { description }
                { editButton }
            </div>
        </div>
    );
}

BubbleSnippet.propTypes = {
    data: React.PropTypes.shape({
        title: React.PropTypes.string,
        description: React.PropTypes.string,
        subtitle: React.PropTypes.string,
        picture_thumbnail: React.PropTypes.object,
        picture: React.PropTypes.object,
    }),
    editable: React.PropTypes.bool,
    withDescription: React.PropTypes.bool,
    editLink: React.PropTypes.string,
    type: React.PropTypes.string,
    onClickDelete: React.PropTypes.func,
};

BubbleSnippet.defaultProps = {
    data: {
        title: '',
        description: '',
        subtitle: '',
        picture_thumbnail: null,
        picture: null,
    },
    editable: false,
    withDescription: false,
    editLink: '',
    type: '',
    onClickDelete: null,
};

module.exports = BubbleSnippet;
