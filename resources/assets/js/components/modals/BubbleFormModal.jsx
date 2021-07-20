const React = require('react-external');
const _ = require('lodash-external');
const URL = require('../../lib/url');
const FormModal = require('./FormModal');

function BubbleFormModal(props) {
    const modalProps = _.omit(props, ['bubbleType', 'title', 'formName', 'formMethod']);
    const id = props.formData.id;
    const bubbleType = (props.formData.type || props.bubbleType)
        .replace(/[^a-z0-9.]+/gi, '');
    const title = id ? 'Modifier un contenu' : 'Ajouter un contenu';
    const formName = `bubble.${bubbleType}`;
    const formAction = id
        ? URL.route('organisation.bubbles.update', id)
        : URL.route('organisation.bubbles.store');
    const formMethod = id ? 'put' : 'post';

    return (
        <FormModal
            {...modalProps}
            title={title}
            formName={formName}
            formMethod={formMethod}
            formAction={formAction}
        />
    );
}

BubbleFormModal.propTypes = {
    bubbleType: React.PropTypes.string,
    formData: React.PropTypes.shape({
        id: React.PropTypes.string,
        type: React.PropTypes.string,
    }),
};

BubbleFormModal.defaultProps = {
    bubbleType: 'banq_service',
    formData: {},
};

module.exports = BubbleFormModal;
