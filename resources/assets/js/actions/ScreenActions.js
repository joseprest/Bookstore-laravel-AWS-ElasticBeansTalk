const Panneau = require('panneau');
const request = require('superagent-external');
const $ = require('jquery-external');

const FormActions = Panneau.Actions.Form;
const ModalActions = Panneau.Actions.Modal;
const ResourceActions = Panneau.Actions.Resource;

module.exports = {

    linkScreen(authCode, form) {
        return (dispatch) => {
            request.post(URL.route('organisation.screens.link'))
                .send({
                    auth_code: authCode,
                })
                .set('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'))
                .set('Accept', 'application/json')
                .end((err, response) => {
                    if (err) {
                        if (response && response.body) {
                            dispatch(FormActions.updateErrors(response.body, form || 'organisation.screens.link'));
                        } else {
                            dispatch(FormActions.addError('auth_code', 'error', form || 'organisation.screens.link'));
                        }

                        return;
                    }

                    const screen = response.body;

                    dispatch(ResourceActions.updateResource('screens', screen.id, screen));
                    dispatch(ModalActions.closeModal());
                });
        };
    },
};
