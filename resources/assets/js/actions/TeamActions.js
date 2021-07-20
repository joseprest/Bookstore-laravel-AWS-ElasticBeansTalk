const Panneau = require('panneau');
const request = require('superagent-external');
const $ = require('jquery-external');

const FormActions = Panneau.Actions.Form;
const ModalActions = Panneau.Actions.Modal;
const ListActions = Panneau.Actions.List;

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
                            dispatch(FormActions.updateErrors(response.body, form || 'organisation.screen'));
                        } else {
                            dispatch(FormActions.addError('auth_code', 'error', form || 'organisation.screen'));
                        }

                        return;
                    }

                    dispatch(ListActions.addItem(response.body, 'organisation.screens'));
                    dispatch(ModalActions.closeModal());
                });
        };
    },
};
